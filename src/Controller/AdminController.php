<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Admin Controller
 *
 *
 * @method \App\Model\Entity\Admin[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AdminController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel("Forums");
        $this->loadModel("Subforums");
        $this->loadComponent('TinyAuth.AuthUser');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $forums = $this->Forums->find('all');
        $subforums = $this->Subforums->find('all',
            ['join'=> ['Forums' => ['table' => 'forums', 'type' => 'INNER', 'conditions' => 'Forums.id = forum_id']]])
            ->select(['id', 'title', 'forum_title' => 'Forums.title']);

        $this->set('forums', $forums);
        $this->set('subforums', $subforums);
    }

    public function editForum($id = null)
    {
        $forum = $this->Forums->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $forum = $this->Forums->patchEntity($forum, $this->request->getData());

            if ($this->Forums->save($forum)) {
                $this->Flash->success(__('The forum has been saved.'));

                return $this->redirect('/admin');
            }

            $this->Flash->error(__('The forum could not be saved. Please, try again.'));
        }

        $this->set(compact('forum'));
    }

    public function editSubforum($id = null)
    {
        $subforum = $this->Subforums->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $subforum = $this->Subforums->patchEntity($subforum, $this->request->getData());

            if ($this->Subforums->save($subforum)) {
                $this->Flash->success(__('The subforum has been saved.'));

                return $this->redirect('/admin');
            }

            $this->Flash->error(__('The subforum could not be saved. Please, try again.'));
        }

        $this->set(compact('subforum'));
    }

    public function addForum()
    {
        $forum = $this->Forums->newEntity();

        if ($this->request->is('post')) {
            $forum = $this->Forums->patchEntity($forum, $this->request->getData());

            if ($this->Forums->save($forum)) {
                $this->Flash->success(__('The forum has been saved.'));

                return $this->redirect('/admin');
            }

            $this->Flash->error(__('The forum could not be saved. Please, try again.'));
        }

        $this->set(compact('forum'));
    }

    public function addSubforum()
    {
        $subforum = $this->Forums->newEntity();

        if ($this->request->is('post')) {
            $subforum = $this->Forums->patchEntity($subforum, $this->request->getData());

            if ($this->Subforums->save($subforum)) {
                $this->Flash->success(__('The subforum has been saved.'));

                return $this->redirect('/admin');
            }

            $this->Flash->error(__('The subforum could not be saved. Please, try again.'));
        }

        $this->set(compact('subforum'));
    }

    public function deleteForum($id = null)
    {
        $forum = $this->Forums->get($id);

        if ($this->Forums->delete($forum)) {
            $this->Flash->success(__('The forum has been deleted.'));

            return $this->redirect('/admin');
        }

        $this->Flash->error(__('The forum could not be deleted. Please, try again.'));
    }

    public function deleteSubforum($id = null)
    {
        $subforum = $this->Subforums->get($id);

        if ($this->Subforums->delete($subforum)) {
            $this->Flash->success(__('The subforum has been deleted.'));

            return $this->redirect('/admin');
        }

        $this->Flash->error(__('The subforum could not be deleted. Please, try again.'));
    }
}
