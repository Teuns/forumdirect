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

        if ($this->Auth->user()) {
            $this->set('loggedIn', true);
            $this->set('username', $this->Auth->user('username'));
            $this->set('userId', $this->Auth->user('id'));
            $this->set('role', $this->Auth->user('role'));
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
