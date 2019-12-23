<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DirectMessagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DirectMessagesTable Test Case
 */
class DirectMessagesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DirectMessagesTable
     */
    public $DirectMessages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.DirectMessages',
        'app.Users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('DirectMessages') ? [] : ['className' => DirectMessagesTable::class];
        $this->DirectMessages = TableRegistry::getTableLocator()->get('DirectMessages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DirectMessages);

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

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
