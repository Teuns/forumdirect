<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    public function up()
    {
        $users = $this->table('users');
        $users->addColumn('username', 'string', ['limit' => 50])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('email', 'string')
            ->addColumn('avatar', 'string', ['default' => '/img/default_avatar.png', 'limit' => 1000])
            ->addColumn('role', 'string', ['default' => 'verify'])
            ->addColumn('last_seen', 'datetime', ['null' => true])
            ->addColumn('verify_token', 'string', ['null' => true])
            ->addColumn('verified', 'integer', ['default' => 0])
            ->addColumn('pass_token', 'string', ['null' => true])
            ->addColumn('timeout', 'timestamp', ['null' => true])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->addIndex(['username'], ['unique' => true])
            ->save();
    }

    public function down()
    {

    }
}
