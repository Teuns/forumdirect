<?php

use Phinx\Migration\AbstractMigration;

class CreatePostsTable extends AbstractMigration
{
    public function up()
    {
        $posts = $this->table('posts')
            ->addColumn('body', 'text')
            ->addColumn('user_id', 'integer')
            ->addColumn('thread_id', 'integer')
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->save();
    }

    public function down()
    {

    }
}
