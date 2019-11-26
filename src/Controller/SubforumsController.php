<?php

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;

class SubforumsController extends AppController
{
    public function view($id = null)
    {
        try {
            $subforum = $this->Subforums->get($id, ['contain' => ['Threads' => function ($q) {
                return $q
                    ->select(['threads.id', 'threads.title', 'threads.slug', 'threads.subforum_id', 'threads.lastpost_date', 'threads.views', 'users.username', 'posts_total' => $q->func()->count('posts.id')])
                    ->order(['threads.lastpost_date' => 'DESC'])
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
            }]]);
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $exeption) {
            throw new NotFoundException(__('Thread not found'));
        }

        $this->set(compact('subforum'));

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
    }
}
