<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TravelRequestRowsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TravelRequestRowsTable Test Case
 */
class TravelRequestRowsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TravelRequestRowsTable
     */
    public $TravelRequestRows;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.travel_request_rows',
        'app.travel_requests'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TravelRequestRows') ? [] : ['className' => 'App\Model\Table\TravelRequestRowsTable'];
        $this->TravelRequestRows = TableRegistry::get('TravelRequestRows', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TravelRequestRows);

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
