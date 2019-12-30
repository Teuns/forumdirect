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
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Database\Expression\QueryExpression;

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
            'authorize' => ['TinyAuth.Tiny'],
            'loginRedirect' => [
                'controller' => 'Pages',
                'action' => 'index',
            ],
            'logoutRedirect' => [
                'controller' => 'Pages',
                'action' => 'display',
                'home'
            ]
        ]);

        $this->loadComponent('TinyAuth.AuthUser');

        $this->loadModel('Users');
        $this->loadModel('Threads');
        $this->loadModel('Posts');
        $this->loadModel('DirectMessages');
        $this->loadModel('Warnings');
        $this->loadModel('Reports');
        $this->loadModel('Chats');

        if ($this->Auth->user()) {
            $warnings = $this->Warnings->find('all')
                ->select(['percentageTotal' => 'SUM(percentage)'])
                ->where(['to_user_id' => $this->Auth->user('id'), 'valid_until >= NOW()'])
                ->first();
            $count_warnings = $this->Warnings->find('all')->where(['to_user_id' => $this->Auth->user('id')])->count();
            $role_table = TableRegistry::get('roles');
            $role = $role_table->find('all')->where(['alias' => 'banned'])->first();
            $member_role = $role_table->find('all')->where(['alias' => 'user'])->first();
            $user = $this->Users->findById($this->Auth->user('id'))->first();
            if ($warnings->percentageTotal >= 100) {
                if ($this->Auth->user('primary_role') !== $role->id) {
                    $roles_users = TableRegistry::get('roles_users');
                    if (!$roles_users->find('all')->where(['role_id' => $role->id])->count()) {
                        $roles_users->query()->update()
                            ->set([
                                'role_id' => $role->id
                            ])
                            ->where(['user_id' => $this->Auth->user('id')])
                            ->execute();

                        $users = TableRegistry::get('users');
                        $users->query()->update()
                            ->set([
                                'primary_role' => $role->id
                            ])
                            ->where(['id' => $this->Auth->user('id')])
                            ->execute();
                    }
                }
            } elseif ($count_warnings && $user->primary_role == $role->id) {
                $roles_users = TableRegistry::get('roles_users');
                $roles_users->query()->update()
                    ->set([
                        'role_id' => $member_role->id
                    ])
                    ->where(['user_id' => $this->Auth->user('id')])
                    ->execute();

                $users = TableRegistry::get('users');
                $users->query()->update()
                    ->set([
                        'primary_role' => $member_role->id
                    ])
                    ->where(['id' => $this->Auth->user('id')])
                    ->execute();
            }

            $this->set('loggedIn', true);
            $this->set('username', $this->Auth->user('username'));
            $this->set('userId', $this->Auth->user('id'));
            $this->set('role', $this->Auth->user('role'));
            $this->set('verified', $this->Auth->user('verified'));

            $last_seen_query = $this->Users->query();
            $last_seen_query->update()
                ->set(['last_seen' => date('Y-m-d H:i:s')])
                ->where(['id' => $this->Auth->user('id')])
                ->execute();

            $direct_views = TableRegistry::get('direct_views')->find('all')
                ->select(['direct_views.direct_id'])
                ->where(['user_id' => $this->Auth->user('id')]);

            $direct_messages = $this->DirectMessages->find('all')->where(['to_user_id' => $this->Auth->user('id')])->andWhere([
                'NOT' => [
                    'DirectMessages.id IN' => $direct_views
                    ]
            ])->contain(['Users']);

            $this->set('direct_messages', $direct_messages);

            $reports = $this->Reports->find('all');

            $this->set('reports', $reports);
        } else {
            $this->set('loggedIn', false);

            $this->set('direct_messages', null);

            $this->set('reports', null);
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

        $chats = $this->Chats->find('all', [
            'order' => ['Chats.created' => 'ASC']
        ])->contain('Users')
            ->where(['from_user_id IS NULL'])
            ->andWhere(['to_user_id IS NULL'])
            ->orWhere([
                ['OR' =>
                    ['from_user_id' => $this->Auth->user('id'), 'to_user_id' => $this->Auth->user('id')]
                ]
            ]);

        $this->set('chats', $chats);

        $online_users = $this->Users->find('all')->where(['last_seen > NOW() - INTERVAL 15 MINUTE']);
        $this->set('online_users', $online_users);

        $total_users = $this->Users->find('all')->count();
        $total_threads = $this->Threads->find('all')->count();
        $total_posts = $this->Posts->find('all')->count();

        $this->set('total_users', $total_users);
        $this->set('total_threads', $total_threads);
        $this->set('total_posts', $total_posts);

        $roles = TableRegistry::get('roles')->find('all');
        $this->set('roles', $roles);

        $recent_activity = $this->Threads->find('all');
        $recent_activity
            ->select(['id', 'title', 'slug', 'subforum_id', 'lastpost_date', 'users.username'])
            ->join([
                'table' => 'users',
                'type' => 'LEFT',
                'conditions' => 'users.id = lastpost_uid',
            ])
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
