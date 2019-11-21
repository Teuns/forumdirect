<?php

use Phinx\Migration\AbstractMigration;

class CreateForumsTable extends AbstractMigration
{
    public function up()
    {
        $forums = $this->table('forums');
        $forums->addColumn('title', 'string', ['limit' => 255])
            ->save();
    }

    public function down()
    {

    }
}
