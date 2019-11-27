<?php

use Phinx\Migration\AbstractMigration;

class CreateDirectMessagesTable extends AbstractMigration
{
    public function up()
    {
        $direct_messages = $this->table('direct_messages');
        $direct_messages->addColumn('title', 'string', ['limit' => 255])
            ->addColumn('title', 'text')
            ->addColumn('from_user_id', 'integer', ['null' => true])
            ->addColumn('to_user_id', 'integer', ['null' => true])
            ->addColumn('user_id', 'integer')
            ->addColumn('direct_id', 'integer')
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->save();
    }

    public function down()
    {
        $this->dropTable('direct_messages');
    }
}
