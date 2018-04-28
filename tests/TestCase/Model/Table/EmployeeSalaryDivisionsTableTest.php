<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EmployeeSalaryDivisionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EmployeeSalaryDivisionsTable Test Case
 */
class EmployeeSalaryDivisionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EmployeeSalaryDivisionsTable
     */
    public $EmployeeSalaryDivisions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.employee_salary_divisions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EmployeeSalaryDivisions') ? [] : ['className' => 'App\Model\Table\EmployeeSalaryDivisionsTable'];
        $this->EmployeeSalaryDivisions = TableRegistry::get('EmployeeSalaryDivisions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EmployeeSalaryDivisions);

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
