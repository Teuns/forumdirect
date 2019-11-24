<?php

use Phinx\Migration\AbstractMigration;

class CreateSubForumsTable extends AbstractMigration
{
    public function up()
    {
        $subforums = $this->table('subforums');
        $subforums->addColumn('title', 'string', ['limit' => 255])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('forum_id', 'integer')
            ->save();
    }

    public function down()
    {
        $this->dropTable('subforums');
    }
}
