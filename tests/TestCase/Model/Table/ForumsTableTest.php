<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ForumsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ForumsTable Test Case
 */
class ForumsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ForumsTable
     */
    public $Forums;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Forums',
        'app.Subforums'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Forums') ? [] : ['className' => ForumsTable::class];
        $this->Forums = TableRegistry::getTableLocator()->get('Forums', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Forums);

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
