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
        'app.Subforums',
        'app.Threads',
        'app.Users',
        'app.TinyAuthAclRules',
        'app.Roles',
        'app.RolesUsers',
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
                    'username' => 'testing'
                ]
            ]
        ]);

        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $data = [
                'id' => 1,
                'title' => 'Lorem ipsum dolor sit amet',
                'slug' => 'Lorem ipsum dolor sit amet',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'user_id' => 1,
                'subforum_id' => 1,
                'lastpost_uid' => 1,
                'lastpost_date' => '2019-11-21 09:14:56',
                'created' => '2019-11-21 09:14:56',
                'modified' => '2019-11-21 09:14:56'
        ];

        $this->post('/threads/add/1', $data);

        $this->assertResponseSuccess();

        $threads = TableRegistry::getTableLocator()->get('threads');
        $query = $threads->find()->where(['id' => $data['id']]);

        $this->assertEquals(1, $query->count());
    }
}
