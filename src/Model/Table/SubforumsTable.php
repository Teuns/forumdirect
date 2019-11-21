<?php

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class SubforumsTable extends Table
{
    public function initialize(array $config)
    {
        $this->hasMany('threads');
    }
}
