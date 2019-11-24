<?php
/**
 * Copyright 2010 - 2019, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2018, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/*
 * IMPORTANT:
 * This is an example configuration file. Copy this file into your config directory and edit to
 * setup your app permissions.
 *
 * This is a quick roles-permissions implementation
 * Rules are evaluated top-down, first matching rule will apply
 * Each line define
 *      [
 *          'role' => 'role' | ['roles'] | '*'
 *          'prefix' => 'Prefix' | , (default = null)
 *          'plugin' => 'Plugin' | , (default = null)
 *          'controller' => 'Controller' | ['Controllers'] | '*',
 *          'action' => 'action' | ['actions'] | '*',
 *          'allowed' => true | false | callback (default = true)
 *      ]
 * You could use '*' to match anything
 * 'allowed' will be considered true if not defined. It allows a callable to manage complex
 * permissions, like this
 * 'allowed' => function (array $user, $role, Request $request) {}
 *
 * Example, using allowed callable to define permissions only for the owner of the Posts to edit/delete
 *
 * (remember to add the 'uses' at the top of the permissions.php file for Hash, TableRegistry and Request
   [
        'role' => ['user'],
        'controller' => ['Posts'],
        'action' => ['edit', 'delete'],
        'allowed' => function(array $user, $role, Request $request) {
            $postId = Hash::get($request->params, 'pass.0');
            $post = TableRegistry::get('Posts')->get($postId);
            $userId = Hash::get($user, 'id');
            if (!empty($post->user_id) && !empty($userId)) {
                return $post->user_id === $userId;
            }
            return false;
        }
    ],
 */

use Cake\Network\Request;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

return [
    'CakeDC/Auth.permissions' => [
        [
            'role' => 'admin',
            'prefix' => '*',
            'extension' => '*',
            'plugin' => '*',
            'controller' => '*',
            'action' => '*',
        ],

        [
            'role' => 'mod',
            'controller' => ['Mod', 'Users', 'Threads', 'Posts'],
            'action' => '*',
        ],

        [
            'role' => 'user',
            'controller' => 'Users',
            'action' => ['editProfile', 'editAvatar', 'logout'],
        ],

        [
            'role' => 'user',
            'controller' => ['Threads', 'Posts'],
            'action' => ['add'],
            'allowed' => function(array $user, $role, Request $request) {
                $threadId = Hash::get($request->params, 'pass.0');
                $thread = TableRegistry::get('Threads')->get($threadId);

                return !$thread->closed;
            }
        ],

        [
            'role' => ['user'],
            'controller' => ['Posts'],
            'action' => ['edit', 'delete'],
            'allowed' => function(array $user, $role, Request $request) {
                $postId = Hash::get($request->params, 'pass.0');
                $post = TableRegistry::get('Posts')->get($postId);
                $thread = TableRegistry::get('Threads')->get($post->thread_id);
                $userId = Hash::get($user, 'id');
                if (!empty($post->user_id) && !empty($userId)) {
                    return !$thread->closed && $post->user_id === $userId;
                }
                return false;
            }
        ],

        [
            'role' => ['user'],
            'controller' => ['Threads'],
            'action' => ['edit', 'delete'],
            'allowed' => function(array $user, $role, Request $request) {
                $postId = Hash::get($request->params, 'pass.0');
                $thread = TableRegistry::get('Threads')->get($postId);
                $userId = Hash::get($user, 'id');
                if (!empty($thread->user_id) && !empty($userId)) {
                    return !$thread->closed && $thread->user_id === $userId;
                }
                return false;
            }
        ],

        [
            'role' => '*',
            'controller' => 'Pages',
            'action' => 'display',
        ],
    ]
];
