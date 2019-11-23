<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class UsersFixture extends TestFixture
{
    public $connection = 'test';

    public $import = ['table' => 'users'];

    public $records = [
        [
            'username' => 'testing',
            'password' => '',
            'role' => 'user',
        ]
    ];
}
