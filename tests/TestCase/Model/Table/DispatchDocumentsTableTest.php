<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DispatchDocumentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DispatchDocumentsTable Test Case
 */
class DispatchDocumentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DispatchDocumentsTable
     */
    public $DispatchDocuments;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.dispatch_documents'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DispatchDocuments') ? [] : ['className' => 'App\Model\Table\DispatchDocumentsTable'];
        $this->DispatchDocuments = TableRegistry::get('DispatchDocuments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DispatchDocuments);

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
