<?php

namespace App\Controller;

class SubforumsController extends AppController
{
    public function view($id = null)
    {
        $subforum = $this->Subforums->get($id, ['contain' => ['Threads' => function($q) {
            return $q
                ->select(['threads.id', 'threads.title', 'threads.slug', 'threads.subforum_id', 'threads.lastpost_date', 'users.username', 'posts_total' => $q->func()->count('posts.id')])
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
        }]]);
        $this->set(compact('subforum'));

        $recent_activity = $this->Threads->find('all')->contain(['Users']);
        $recent_activity
            ->order(['lastpost_date'  => 'DESC'])
            ->limit(10);
        $this->set('recent_activity', $recent_activity);
    }
}
