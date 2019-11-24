<?php

use Phinx\Migration\AbstractMigration;

class CreateRolesUsersTable extends AbstractMigration
{
    public function up()
    {
        $this->table('roles_users')
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('role_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->create();
    }

    public function down()
    {
        $this->dropTable('roles_users');
    }
}
