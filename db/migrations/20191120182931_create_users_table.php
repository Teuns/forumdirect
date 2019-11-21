<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    public function up()
    {
        $users = $this->table('users');
        $users->addColumn('username', 'string', ['limit' => 50])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('avatar', 'string', ['default' => '/img/default_avatar.png'])
            ->addColumn('role', 'string', ['default' => 'user'])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->addIndex(['username'], ['unique' => true])
            ->save();
    }

    public function down()
    {

    }
}
