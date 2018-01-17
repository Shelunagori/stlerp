<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SendEmailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SendEmailsTable Test Case
 */
class SendEmailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SendEmailsTable
     */
    public $SendEmails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.send_emails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SendEmails') ? [] : ['className' => 'App\Model\Table\SendEmailsTable'];
        $this->SendEmails = TableRegistry::get('SendEmails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SendEmails);

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
}
