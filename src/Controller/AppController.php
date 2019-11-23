<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');

        $this->loadComponent('Auth', [
            'authorize' => ['CakeDC/Auth.SimpleRbac'],
            'loginRedirect' => [
                'controller' => 'Pages',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Pages',
                'action' => 'display',
                'home'
            ]
        ]);

        $this->loadModel('Users');
        $this->loadModel('Threads');
        $this->loadModel('Posts');

        if ($this->Auth->user()) {
            $this->set('loggedIn', true);
            $this->set('username', $this->Auth->user('username'));
            $this->set('userId', $this->Auth->user('id'));
            $this->set('role', $this->Auth->user('role'));

            $last_seen_query = $this->Users->query();
            $last_seen_query->update()
                ->set(['last_seen' => date('Y-m-d H:i:s')])
                ->where(['id' => $this->Auth->user('id')])
                ->execute();
        } else {
            $this->set('loggedIn', false);
        }

        $this->loadModel('Forums');
        $this->loadModel('Threads');

        $forums = $this->Forums->find('all');
        $forums
            ->contain(['Subforums.Threads' => function($q) {
                return $q
                    ->select(['threads.id', 'threads.title', 'threads.slug', 'threads.subforum_id', 'threads.lastpost_date', 'users.username', 'posts_total' => $q->func()->count('DISTINCT posts.id')])
                    ->order(['threads.lastpost_date' =>'DESC'])
                    ->join([
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => 'users.id = threads.lastpost_uid',
                    ])
                    ->join([
                        'table' => 'posts',
                        'type' => 'LEFT',
                        'conditions' => 'posts.thread_id = threads.id',
                    ])
                    ->group(['threads.id']);
            }]);
        $this->set('forums', $forums);

        $online_users = $this->Users->find('all')->where(['last_seen > NOW() - INTERVAL 15 MINUTE']);
        $this->set('online_users', $online_users);

        $total_users = $this->Users->find('all')->count();
        $total_threads = $this->Threads->find('all')->count();
        $total_posts = $this->Posts->find('all')->count();

        $this->set('total_users', $total_users);
        $this->set('total_threads', $total_threads);
        $this->set('total_posts', $total_posts);

        $recent_activity = $this->Threads->find('all')->contain(['Users']);
        $recent_activity
            ->order(['lastpost_date'  => 'DESC'])
            ->limit(10);
        $this->set('recent_activity', $recent_activity);

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['index', 'view', 'display']);
    }
}
