<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;

/**
 * Posts Controller
 *
 * @property \App\Model\Table\PostsTable $Posts
 *
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PostsController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Threads');
        $this->loadComponent('TinyAuth.AuthUser');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Threads']
        ];
        $posts = $this->paginate($this->Posts);

        $this->set(compact('posts'));
    }

    /**
     * View method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $post = $this->Posts->get($id, [
            'contain' => ['Users', 'Threads']
        ]);

        $this->set('post', $post);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($threadId)
    {
        $post = $this->Posts->newEntity();
        $thread = $this->Threads->get($threadId);
        if ($this->request->is('post')) {
            $post['thread_id'] = $threadId;
            $post['user_id'] = $this->Auth->user('id');
            $post['modified'] = null;
            $post = $this->Posts->patchEntity($post, $this->request->getData());
            if ($this->Posts->save($post)) {
                $this->Threads->updateAll(['lastpost_date' => Time::now(), 'lastpost_uid' => $this->Auth->user('id')], ['id' => $threadId]);
                $this->Flash->success(__('The post has been saved.'));

                return $this->redirect('/threads/'. $thread->id. '-'. $thread->slug . '?action=lastpost');
            }
            $this->Flash->error(__('The post could not be saved. Please, try again.'));
        }
        $this->set(compact('post'));
    }

    public function quote($postId)
    {
        $post = $this->Posts->newEntity();
        $post_data = $this->Posts->findById($postId)->contain(['Users'])->first();
        $thread = $this->Threads->get($post_data->thread_id);
        if ($this->request->is(['post', 'put'])) {
            $post['thread_id'] = $thread->id;
            $post['user_id'] = $this->Auth->user('id');
            $post['modified'] = null;
            $this->Threads->updateAll(['lastpost_date' => Time::now(), 'lastpost_uid' => $this->Auth->user('id')], ['id' => $thread->id]);
            $post = $this->Posts->patchEntity($post, $this->request->getData());
            if ($this->Posts->save($post)) {
                $this->Flash->success(__('The post has been saved.'));

                return $this->redirect('/threads/'. $thread->id. '-'. $thread->slug . '?action=lastpost');
            }
            $this->Flash->error(__('The post could not be saved. Please, try again.'));
        }
        $post_data->body = $post_data->user->username."\n\n".$post_data->body;
        $this->set(compact('post_data'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $post = $this->Posts->get($id, [
            'contain' => []
        ]);

        if (!$this->AuthUser->isMe($post->user_id) && !$this->AuthUser->hasRole('mod')) {
            $this->Flash->error(__('You cannot access this location'));
            return $this->redirect('/');
        }

        $thread = $this->Threads->get($post->thread_id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $post = $this->Posts->patchEntity($post, $this->request->getData());
            if ($this->Posts->save($post)) {
                $this->Flash->success(__('The post has been saved.'));

                return $this->redirect('/threads/'. $thread->id. '-'. $thread->slug);
            }
            $this->Flash->error(__('The post could not be saved. Please, try again.'));
        }
        $users = $this->Posts->Users->find('list', ['limit' => 200]);
        $threads = $this->Posts->Threads->find('list', ['limit' => 200]);
        $this->set(compact('post', 'users', 'threads'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $post = $this->Posts->get($id);

        if (!$this->AuthUser->isMe($post->user_id) && !$this->AuthUser->hasRole('mod')) {
            $this->Flash->error(__('You cannot access this location'));
            return $this->redirect('/');
        }

        if ($this->Posts->delete($post)) {
            $this->Flash->success(__('The post has been deleted.'));
        } else {
            $this->Flash->error(__('The post could not be deleted. Please, try again.'));
        }

        return $this->redirect('/');
    }
}
