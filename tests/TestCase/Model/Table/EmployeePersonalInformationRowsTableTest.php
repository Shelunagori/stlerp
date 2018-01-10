<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EmployeePersonalInformationRowsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EmployeePersonalInformationRowsTable Test Case
 */
class EmployeePersonalInformationRowsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EmployeePersonalInformationRowsTable
     */
    public $EmployeePersonalInformationRows;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.employee_personal_information_rows',
        'app.emp_personal_informations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EmployeePersonalInformationRows') ? [] : ['className' => 'App\Model\Table\EmployeePersonalInformationRowsTable'];
        $this->EmployeePersonalInformationRows = TableRegistry::get('EmployeePersonalInformationRows', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EmployeePersonalInformationRows);

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
