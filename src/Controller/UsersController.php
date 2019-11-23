<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validator;

class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Posts');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow('add','logout');
    }

    public function index()
    {
        $total_posts = $this->Posts->findByUserId($this->Auth->user('id'))->count();
        $total_threads = $this->Threads->findByUserId($this->Auth->user('id'))->count();

        $last_threads = $this->Threads->findByUserId($this->Auth->user('id'));

        $this->set('user', $this->Auth->user());
        $this->set('total_posts', $total_posts);
        $this->set('total_threads', $total_threads);
        $this->set('last_threads', $last_threads);
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
            $this->Flash->error(__('The details are unknown to us'));
        }
    }

    public function logout()
    {
        if ($this->request->is(['post', 'delete']) !== true) {
            throw new BadRequestException;
        }

        $last_login_query = $this->Users->query();
        $last_login_query->update()
            ->set(['last_seen' => null])
            ->where(['id' => $this->Auth->user('id')])
            ->execute();

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
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('An error occurred'));
        }
        $this->set('user', $user);
    }

    public function editAvatar()
    {
        $validator = new Validator();
        $validator
            ->add('image', 'file', [
                'rule' => ['mimeType', ['image/jpeg', 'image/png']]
            ]);
        $user = $this->Users->get($this->Auth->user('id'));
        if ($this->request->is(['post', 'put'])) {
            $image = $this->request->getData('image');
            $errors = $validator->errors($this->request->getData());
            if (empty($errors)) {
                if (move_uploaded_file($image['tmp_name'], WWW_ROOT . '/img/' . $user->id . '-' . time() . '-' . $image['name'])) {
                    $user->avatar = '/img/' . $user->id . '-' . time() . '-' . $image['name'];
                    if ($this->Users->save($user, ['validate' => 'avatar'])) {
                        $this->Auth->setUser($user->toArray());
                        $this->Flash->success(__('The avatar image has been saved.'));

                        return $this->redirect(['action' => 'index']);
                    }
                } else {
                    $this->Flash->success(__('Avatar upload unsuccessful'));
                }
            }
            $this->Flash->error(__('The avatar image could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    public function editProfile()
    {
        $user = $this->Users->get($this->Auth->user('id'));
        if ($this->request->is(['post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Auth->setUser($user->toArray());
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('An error occurred'));
        }
        $this->set('user', $user);
    }
}
