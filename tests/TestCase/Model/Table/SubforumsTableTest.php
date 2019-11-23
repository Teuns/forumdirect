<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SubforumsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SubforumsTable Test Case
 */
class SubforumsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SubforumsTable
     */
    public $Subforums;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Subforums',
        'app.Threads'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Subforums') ? [] : ['className' => SubforumsTable::class];
        $this->Subforums = TableRegistry::getTableLocator()->get('Subforums', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Subforums);

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
