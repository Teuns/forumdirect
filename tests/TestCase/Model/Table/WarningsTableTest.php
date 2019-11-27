<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WarningsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WarningsTable Test Case
 */
class WarningsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\WarningsTable
     */
    public $Warnings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Warnings',
        'app.ToUsers',
        'app.FromUsers',
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
        $config = TableRegistry::getTableLocator()->exists('Warnings') ? [] : ['className' => WarningsTable::class];
        $this->Warnings = TableRegistry::getTableLocator()->get('Warnings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Warnings);

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
