<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table
{
    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('username', 'An username is required')
            ->add('username', [
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => 'Passwords need to be at least 10 characters long',
                ]
            ])
            ->notEmpty('password', 'Een wachtwoord is verplicht')
            ->add('password', [
                'length' => [
                    'rule' => ['minLength', 8],
                    'message' => 'Passwords need to be at least 8 characters long',
                ]
            ])
            ->add('password', [
                'compare' => [
                    'rule' => ['compareWith', 'confirm_password']
                ]
            ]);
    }
}
