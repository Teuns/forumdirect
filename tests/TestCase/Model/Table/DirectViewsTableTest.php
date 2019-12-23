<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DirectViewsTable Test Case
 */
class DirectViewsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DirectViewsTable
     */
    public $DirectViewsTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.DirectViews',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->DirectViewsTable = TableRegistry::getTableLocator()->get('direct_views');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DirectViewsTable);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
