<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LoanApplicationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LoanApplicationsTable Test Case
 */
class LoanApplicationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LoanApplicationsTable
     */
    public $LoanApplications;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.loan_applications'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('LoanApplications') ? [] : ['className' => 'App\Model\Table\LoanApplicationsTable'];
        $this->LoanApplications = TableRegistry::get('LoanApplications', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LoanApplications);

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
