<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class RolesUsersTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('roles');
    }
}
