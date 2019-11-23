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
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        if ($this->Auth->user('role') == 'admin' || $this->Auth->user('role') == 'mod') {
            $users = $this->Users->find('all');
            $threads = $this->Threads->find('all', ['contain' => 'Users']);
            $posts = $this->Posts->find('all', ['contain' => 'Users']);

            $this->set('users', $users);
            $this->set('threads', $threads);
            $this->set('posts', $posts);
        } else {
            $this->Flash->error(__('You cannot access this location'));
            return $this->redirect('/');
        }
    }

    public function ban($userId)
    {
        $user = $this->Users->get($userId);

        $user->role = 'banned';

        $sessions = TableRegistry::get('sessions')->find('all')->where([
            'data LIKE "%'.$user->username.'%"'
        ]);

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

        $user->role = 'user';

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
}
