<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Database\Expression\QueryExpression;
use Cake\I18n\Time;
use Cake\Utility\Text;
use Cake\Http\Exception\NotFoundException;

/**
 * Threads Controller
 *
 * @property \App\Model\Table\ThreadsTable $Threads
 *
 * @method \App\Model\Entity\Thread[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ThreadsController extends AppController
{
    public function  initialize()
    {
        parent::initialize();

        $this->loadModel('Posts');
        $this->loadModel('Subforums');
        $this->loadComponent('TinyAuth.AuthUser');
    }

    /**
     * View method
     *
     * @param string|null $id Thread id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($slug = null, $id = null)
    {
        $thread = $this->Threads->findBySlug($slug)->contain('Users.roles_users.roles')->first();

        if ($thread->id !== $id) {
            $thread = $this->Threads->get($id, [
                'contain' => ['Users.roles_users.roles']
            ]);

            $subforum = $this->Subforums->find('all')->where(['id' => $thread->subforum_id])->first();
        }

        if (empty($thread)) {
            throw new NotFoundException(__('Thread not found'));
        }

        $views_query = $this->Threads->query();
        $views_query->update()
            ->set(['views' => new QueryExpression('views + 1')])
            ->where(['id' => $id])
            ->execute();

        $this->paginate = [
            'limit' => 5,
            'contain' => ['Users.roles_users.roles', 'Threads']
        ];

        $posts = $this->paginate($this->Posts->find('all')->where(['thread_id' => $id])->order(['Posts.created' => 'ASC']));

        $this->set('thread', $thread);
        $this->set('subforum', $subforum);
        $this->set(compact('posts'));

        $currentPage = $this->request->getQuery('page');

        $this->set('currPage', $currentPage);

        if ($this->request->query('action') == 'lastpost' && $posts->count()) {
            $total_pages = $this->Paginator->getPagingParams()['Posts']['pageCount'];
            $last_pid = $this->Posts->find('all')->where(['thread_id' => $id])->last()->id;

            if ($total_pages > 1) {
                return $this->redirect('/threads/' . $thread->id . '-' . $thread->slug . '?page=' . $total_pages . '#pid' . $last_pid);
            } else {
                return $this->redirect('/threads/' . $thread->id . '-' . $thread->slug . '#pid' . $last_pid);
            }
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($subforumId)
    {
        $subforum = $this->Subforums->findById($subforumId);

        if (!$subforum->count()) {
            $this->Flash->error(__('The subforum does not exist.'));

            return $this->redirect('/');
        }

        $thread = $this->Threads->newEntity();

        if ($this->request->is('post')) {
            $thread['slug'] = Text::slug($this->request->getData('title'));
            $thread['subforum_id'] = $subforumId;
            $thread['user_id'] = $this->Auth->user('id');
            $thread['lastpost_uid'] = $this->Auth->user('id');
            $thread['modified'] = null;
            $thread['lastpost_date'] = Time::now();
            $thread = $this->Threads->patchEntity($thread, $this->request->getData());

            if ($this->Threads->save($thread)) {
                $this->Flash->success(__('The thread has been saved.'));

                return $this->redirect('/threads/'. $thread->id. '-'. $thread->slug);
            }

            $errors = $thread->errors();

            if ($errors) {
                dd($errors);
            }

            $this->Flash->error(__('The thread could not be saved. Please, try again.'));
        }

        $this->set(compact('thread'));
    }

    public function quote($threadId)
    {
        $post = $this->Posts->newEntity();
        $thread_data = $this->Threads->findById($threadId)->contain(['Users'])->first();
        if ($this->request->is(['post', 'put'])) {
            $post['thread_id'] = $threadId;
            $post['user_id'] = $this->Auth->user('id');
            $post['modified'] = null;
            $this->Threads->updateAll(['lastpost_date' => Time::now(), 'lastpost_uid' => $this->Auth->user('id')], ['id' => $threadId]);
            $post = $this->Posts->patchEntity($post, $this->request->getData());
            if ($this->Posts->save($post)) {
                $this->Flash->success(__('The post has been saved.'));

                // return $this->redirect('/threads/'. $thread_data->id. '-'. $thread_data->slug . '?action=lastpost');
            }
            $this->Flash->error(__('The post could not be saved. Please, try again.'));
        }
        $thread_data->body = $thread_data->user->username."\n\n".$thread_data->body;
        $this->set(compact('thread_data'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Thread id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $thread = $this->Threads->get($id, [
            'contain' => []
        ]);

        if (!$this->AuthUser->isMe($thread->user_id) && !$this->AuthUser->hasRole('mod')) {
            $this->Flash->error(__('You cannot access this location'));
            return $this->redirect('/');
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $thread = $this->Threads->patchEntity($thread, $this->request->getData());

            if ($this->Threads->save($thread)) {
                $this->Flash->success(__('The thread has been saved.'));

                return $this->redirect('/threads/'. $thread->id. '-'. $thread->slug);
            }

            $this->Flash->error(__('The thread could not be saved. Please, try again.'));
        }

        $users = $this->Threads->Users->find('list', ['limit' => 200]);
        $this->set(compact('thread', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Thread id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $thread = $this->Threads->get($id);

        if (!$this->AuthUser->isMe($thread->user_id) && !$this->AuthUser->hasRole('mod')) {
            $this->Flash->error(__('You cannot access this location'));
            return $this->redirect('/');
        }

        if ($this->Threads->delete($thread)) {
            $this->Flash->success(__('The thread has been deleted.'));
        } else {
            $this->Flash->error(__('The thread could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
