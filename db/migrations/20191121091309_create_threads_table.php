<?php

use Phinx\Migration\AbstractMigration;

class CreateThreadsTable extends AbstractMigration
{
    public function up()
    {
        $threads = $this->table('threads');
        $threads->addColumn('title', 'string', ['limit' => 255])
            ->addColumn('slug', 'string')
            ->addColumn('body', 'text')
            ->addColumn('user_id', 'integer')
            ->addColumn('subforum_id', 'integer')
            ->addColumn('lastpost_date', 'datetime', ['null' => true])
            ->addColumn('lastpost_uid', 'integer')
            ->addColumn('views', 'integer', ['default' => 0])
            ->addColumn('closed', 'integer', ['default' => 0])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->save();
    }

    public function down()
    {

    }
}
