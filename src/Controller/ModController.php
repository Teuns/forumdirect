<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Mod Controller
 *
 *
 * @method \App\Model\Entity\Mod[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ModController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Users');
        $this->loadModel('Threads');
        $this->loadModel('Posts');
        $this->loadComponent('TinyAuth.AuthUser');
        $this->loadModel('Warnings');
        $this->loadModel('DirectMessages');
        $this->loadModel('Reports');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        if ($this->AuthUser->hasRole('admin') || $this->AuthUser->hasRole('mod')) {
            $users = $this->Users->find('all');
            $threads = $this->Threads->find('all', ['contain' => 'Users']);
            $posts = $this->Posts->find('all', ['contain' => 'Users']);
            $reports = $this->Reports->find('all', ['contain' => 'Users']);

            $this->set('reports', $reports);
            $this->set('users', $users);
            $this->set('threads', $threads);
            $this->set('posts', $posts);
        } else {
            $this->Flash->error(__('You cannot access this location'));
            return $this->redirect('/');
        }
    }

    public function warn()
    {
        if ($this->AuthUser->hasRole('admin') || $this->AuthUser->hasRole('mod')) {
            $warning = $this->Warnings->newEntity();

            if ($this->request->is('post')) {
                $user = $this->Users->find('all')->where(['username' => $this->request->getData('to_username')])->first();

                if (empty($user)) {
                    $this->Flash->error(__('The warning could not be given. Please, try again.'));
                    return $this->redirect('/');
                }

                $warning['to_user_id'] = $user->id;
                $warning['from_user_id'] = $this->Auth->user('id');
                $warning['user_id'] = $this->Auth->user('id');

                $warning = $this->Warnings->patchEntity($warning, $this->request->getData());

                if ($this->Warnings->save($warning)) {
                    $direct = $this->DirectMessages->newEntity();

                    $direct->title = 'You have been warned';
                    $direct->body = 'Given reason: ' . $warning->reason . "\n\n" . 'Percentage: ' . $warning->percentage . '%' . "\n\n" . 'Valid until: ' . $warning->valid_until;
                    $direct->to_user_id = $user->id;
                    $direct->from_user_id = $this->Auth->user('id');
                    $direct->user_id = $this->Auth->user('id');

                    if ($this->DirectMessages->save($direct)) {
                        $direct_id_query = $this->DirectMessages->query();
                        $direct_id_query->update()
                            ->set(['direct_id' => $direct->id])
                            ->where(['id' => $direct->id]);
                    }

                    if ($direct_id_query->execute()) {
                        $this->Flash->success(__('The warning has been given.'));

                        return $this->redirect('/');
                    }
                }

                $errors = $warning->errors();

                if ($errors) {
                    dd($errors);
                }

                $this->Flash->error(__('The warning could not be given. Please, try again.'));
            }

            $this->set(compact('warning'));
        } else {
            $this->Flash->error(__('You cannot access this location'));
            return $this->redirect('/');
        }
    }

    public function ban($userId)
    {
        $user = $this->Users->get($userId);

        $role_table = TableRegistry::get('roles');

        $role = $role_table->find('all')->where(['alias' => 'banned'])->first();
        $member_role = $role_table->find('all')->where(['alias' => 'user'])->first();

        $roles_users = TableRegistry::get('roles_users');
        $roles_users->query()->update()
            ->set([
                'role_id' => $role->id
            ])
            ->where(['user_id' => $userId, 'role_id' => $member_role->id])
            ->execute();

        $sessions = TableRegistry::get('sessions')->find('all')->where([
            'data LIKE "%'.$user->username.'%"'
        ]);

        $user->primary_role = $role->id;

        foreach ($sessions as $session) {
            $sessions = TableRegistry::get('sessions');

            $result = $sessions->get($session->id);

            $sessions->delete($result);
        }

        if ($this->Users->save($user)) {
            $this->Flash->success(__('The user has been banned'));
            return $this->redirect('/mod');
        } else {
            $this->Flash->error(__('An error occured'));
            return $this->redirect('/mod');
        }
    }

    public function unban($userId)
    {
        $user = $this->Users->get($userId);

        $role_table = TableRegistry::get('roles');

        $role = $role_table->find('all')->where(['alias' => 'banned'])->first();
        $member_role = $role_table->find('all')->where(['alias' => 'user'])->first();

        $roles_users = TableRegistry::get('roles_users');
        $roles_users->query()->update()
            ->set([
                'role_id' => $member_role->id
            ])
            ->where(['user_id' => $userId, 'role_id' => $role->id])
            ->execute();

        $user->primary_role = $member_role->id;

        $sessions = TableRegistry::get('sessions')->find('all')->where([
            'data LIKE "%'.$user->username.'%"'
        ]);

        foreach ($sessions as $session) {
            $sessions = TableRegistry::get('sessions');

            $result = $sessions->get($session->id);

            $sessions->delete($result);
        }

        if ($this->Users->save($user)) {
            $this->Flash->success(__('The user has been successfully un-banned'));
            return $this->redirect('/mod');
        } else {
            $this->Flash->error(__('An error occured'));
            return $this->redirect('/mod');
        }
    }

    public function close($threadId)
    {
        $thread = $this->Threads->get($threadId);

        $thread->closed = 1;

        if ($this->Threads->save($thread)) {
            $this->Flash->success(__('The thread has been closed'));
            return $this->redirect('/mod');
        }
    }

    public function open($threadId)
    {
        $thread = $this->Threads->get($threadId);

        $thread->closed = 0;

        if ($this->Threads->save($thread)) {
            $this->Flash->success(__('The thread has been opened'));
            return $this->redirect('/mod');
        }
    }
}
