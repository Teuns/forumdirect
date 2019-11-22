<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;

class UsersController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow('add','logout');
    }

    public function index()
    {
        $this->set('user', $this->Auth->user());
    }

    public function view($id)
    {
        $user = $this->Users->get($id);
        $this->set(compact('user'));
    }

    public function login()
    {
        if ($this->Auth->user()){
            return $this->redirect($this->Auth->redirectUrl('/'));
        }

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Deze gegevens zijn niet bij ons bekend'));
        }
    }

    public function logout()
    {
        if ($this->request->is(['post', 'delete']) !== true) {
            throw new BadRequestException;
        }

        return $this->response
            ->withLocation($this->Auth->logout());
    }

    public function add()
    {
        if ($this->Auth->user()){
            return $this->redirect($this->Auth->redirectUrl('/'));
        }

        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('De gebruiker is opgeslagen'));
                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('Er is een fout opgetreden.'));
        }
        $this->set('user', $user);
    }

}
