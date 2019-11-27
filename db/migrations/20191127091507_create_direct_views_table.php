<?php

use Phinx\Migration\AbstractMigration;

class CreateDirectViewsTable extends AbstractMigration
{
    public function up()
    {
        $direct_views = $this->table('direct_views');
        $direct_views->addColumn('user_id', 'integer')
            ->addColumn('direct_id', 'integer')
            ->save();
    }

    public function down()
    {
        $this->dropTable('direct_views');
    }
}
