<?php

use Phinx\Migration\AbstractMigration;

class CreateWarningsTable extends AbstractMigration
{
    public function up()
    {
        $warnings = $this->table('warnings');
        $warnings->addColumn('to_user_id', 'integer')
            ->addColumn('from_user_id', 'integer')
            ->addColumn('user_id', 'integer')
            ->addColumn('percentage', 'string')
            ->addColumn('valid_until', 'datetime')
            ->addColumn('reason', 'text')
            ->save();
    }

    public function down()
    {
        $this->dropTable('warnings');
    }
}
