<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Validation\Validator;
use Cake\Mailer\Email;

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
        $this->Auth->allow('add', 'logout');
        $this->Auth->allow('password');
        $this->Auth->allow('reset');
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

        $verifyToken = uniqid();
        $user->verify_token = $verifyToken;

        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $url = Router::Url(['controller' => 'users', 'action' => 'verify'], true) . '/' . $verifyToken;
                $this->sendVerifyEmail($url, $user);
                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('An error occurred'));
        }
        $this->set('user', $user);
    }

    private function sendVerifyEmail($url, $user) {
        $email = new Email();
        $email->viewBuilder()->setTemplate('verify');
        $email->setEmailFormat('html');
        $email->setFrom(Configure::read('App.email'));
        $email->setTo($user->email, $user->username);
        $email->setSubject('Verify Your Email');
        $email->setViewVars(['url' => $url, 'username' => $user->username]);
        if ($email->send()) {
            $this->Flash->success(__('Check your email for your verify link'));
        } else {
            $this->Flash->error(__('Error sending email: ') . $email->smtpError);
        }
    }

    public function verify($token)
    {
        $user = $this->Users->find()->where(['verify_token' => $token])->first();
        $user->verify_token = null;
        $user->verified = 1;
        $user->role = 'user';
        if ($this->Users->save($user)) {
            $this->Auth->setUser($user->toArray());
            $this->Flash->success(__('The user has been verified'));
            return $this->redirect(['action' => 'login']);
        }
        $this->Flash->error(__('An error occurred'));
        return $this->redirect('/');
    }

    public function password()
    {
        if ($this->Auth->user()){
            return $this->redirect($this->Auth->redirectUrl('/'));
        }

        $user = $this->Users->findByEmail($this->request->getData('email'))->first();

        $passToken = uniqid();

        $timeout = Time::now();
        $timeout->addDay(1);

        if ($this->request->is('post')) {
             if ($this->Users->updateAll(['pass_token' => $passToken, 'timeout' => $timeout], ['id' => $user->id])) {
                $url = Router::Url(['controller' => 'users', 'action' => 'reset'], true) . '/' . $passToken;
                $this->sendResetEmail($url, $user);
                return $this->redirect(['action' => 'password']);
            }
            $this->Flash->error(__('An error occurred'));
        }
        $this->set('user', $user);
    }

    private function sendResetEmail($url, $user) {
        $email = new Email();
        $email->viewBuilder()->setTemplate('reset');
        $email->setEmailFormat('html');
        $email->setFrom(Configure::read('App.email'));
        $email->setTo($user->email, $user->username);
        $email->setSubject('Reset Your Password');
        $email->setViewVars(['url' => $url, 'username' => $user->username]);
        if ($email->send()) {
            $this->Flash->success(__('Check your email for your password reset link'));
        } else {
            $this->Flash->error(__('Error sending email: ') . $email->smtpError);
        }
    }

    public function reset($token)
    {
        $user = $this->Users->find()->where(['pass_token' => $token, 'timeout >' => time()])->first();
        $user->pass_token = null;
        $user->timeout = null;
        if ($this->request->is(['post', 'put'])) {
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The password has been reset'));
                return $this->redirect(['action' => 'login']);
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
