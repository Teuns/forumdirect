<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

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
        $this->loadModel('Users');
        $this->loadComponent('TinyAuth.AuthUser');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        if (is_null($this->Auth->user())) {
            $this->redirect('/');
        }

        $forums = $this->Forums->find('all');
        $subforums = $this->Subforums->find('all',
            ['join'=> ['Forums' => ['table' => 'forums', 'type' => 'INNER', 'conditions' => 'Forums.id = forum_id']]])
            ->select(['id', 'title', 'forum_title' => 'Forums.title']);

        $users = $this->Users->find('all');

        $this->set('users', $users);
        $this->set('forums', $forums);
        $this->set('subforums', $subforums);
    }

    public function editUser($userId)
    {
        $user = $this->Users->findById($userId)->contain(['roles_users.roles'])->first();
        $roles_users = TableRegistry::get('roles_users');
        $result = $roles_users->find('all')->where(['user_id' => $userId])->contain(['roles'])->find('all');
        $this->set('roles', $result->toArray());
        if ($this->request->is(['post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->request->getData('primary_role')) {
                $roles_users = TableRegistry::get('roles_users');
                $result = $roles_users->find('all')->where(['role_id' => $this->request->getData('primary_role'), 'user_id' => $userId])->count();
                if ($result) {
                    $user->primary_role = $this->request->getData('primary_role');
                }
            }
            $roles = TableRegistry::get('roles');
            if ($this->request->getData('addRole') && $roles->find('all')->where(['alias' => $this->request->getData('role')])->count()) {
                $roles_users = TableRegistry::get('roles_users')->query();
                $roles_users->insert(['user_id', 'role_id'])
                    ->values([
                        'user_id' => $user->id,
                        'role_id' => $roles->find('all')->where(['alias' => $this->request->getData('role')])->first()->id
                    ])
                    ->execute();
                $user->primary_role = $roles->find('all')->where(['alias' => $this->request->getData('role')])->first()->id;
            }
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('An error occurred'));
        }
        $this->set('user', $user);
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

        $forums = $this->Forums->find('list');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $subforum = $this->Subforums->patchEntity($subforum, $this->request->getData());

            if ($this->Subforums->save($subforum)) {
                $this->Flash->success(__('The subforum has been saved.'));

                return $this->redirect('/admin');
            }

            $this->Flash->error(__('The subforum could not be saved. Please, try again.'));
        }

        $this->set(compact('subforum'));
        $this->set('forums', $forums);
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

        $forums = $this->Forums->find('list');

        if ($this->request->is('post')) {
            $subforum = $this->Forums->patchEntity($subforum, $this->request->getData());

            if ($this->Subforums->save($subforum)) {
                $this->Flash->success(__('The subforum has been saved.'));

                return $this->redirect('/admin');
            }

            $this->Flash->error(__('The subforum could not be saved. Please, try again.'));
        }

        $this->set(compact('subforum'));
        $this->set('forums', $forums);
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
