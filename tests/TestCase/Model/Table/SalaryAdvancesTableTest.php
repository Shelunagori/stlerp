<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SalaryAdvancesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SalaryAdvancesTable Test Case
 */
class SalaryAdvancesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SalaryAdvancesTable
     */
    public $SalaryAdvances;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.salary_advances'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SalaryAdvances') ? [] : ['className' => 'App\Model\Table\SalaryAdvancesTable'];
        $this->SalaryAdvances = TableRegistry::get('SalaryAdvances', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SalaryAdvances);

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
