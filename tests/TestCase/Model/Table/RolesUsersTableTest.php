<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RolesUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RolesUsersTable Test Case
 */
class RolesUsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RolesUsersTable
     */
    public $RolesUsersTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Roles',
        'app.RolesUsers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('roles_users') ? [] : ['className' => RolesUsersTable::class];
        $this->RolesUsersTable = TableRegistry::getTableLocator()->get('roles_users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RolesUsersTable);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
