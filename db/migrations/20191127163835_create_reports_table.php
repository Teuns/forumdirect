<?php

use Phinx\Migration\AbstractMigration;

class CreateReportsTable extends AbstractMigration
{
    public function up()
    {
        $reports = $this->table('reports');
        $reports->addColumn('type', 'string')
            ->addColumn('to_id', 'integer')
            ->addColumn('user_id', 'integer')
            ->addColumn('reason', 'text')
            ->save();
    }

    public function down()
    {
        $this->dropTable('reports');
    }
}
