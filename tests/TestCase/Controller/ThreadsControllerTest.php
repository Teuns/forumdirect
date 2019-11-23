<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ThreadsController;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ThreadsController Test Case
 *
 * @uses \App\Controller\ThreadsController
 */
class ThreadsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Threads',
        'app.Users'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     *
     *
     */
    public function testAdd()
    {
        $this->get('/threads/add/1');
        $this->assertResponseCode(302);
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testAddUnauthenticatedFails()
    {
        // No session data set.
        $this->get('/threads/add/1');

        $this->assertRedirectContains(['controller' => 'Users', 'action' => 'login']);
    }

    public function testAddAuthenticated()
    {
        // Set session data
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'username' => 'testing',
                    'role' => 'user',
                ]
            ]
        ]);

        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $data = [
            'user_id' => 1,
            'slug' => 'new-thread',
            'title' => 'New Thread',
            'body' => 'New Body',
            'lastpost_date' => Time::now(),
            'lastpost_uid' => 1
        ];
        $this->post('/threads/add/1', $data);

        $this->assertResponseSuccess();
        $threads = TableRegistry::getTableLocator()->get('threads');
        $query = $threads->find()->where(['slug' => $data['slug']]);
        $this->assertEquals(1, $query->count());
    }
}
