<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table
{
    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('username', 'Een gebruikersnaam is verplicht')
            ->notEmpty('password', 'Een wachtwoord is verplicht')
            ->add('password', [
                'compare' => [
                    'rule' => ['compareWith', 'confirm_password']
                ]
            ]);
    }

}
