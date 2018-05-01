<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EmployeeWorkExperiencesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EmployeeWorkExperiencesTable Test Case
 */
class EmployeeWorkExperiencesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EmployeeWorkExperiencesTable
     */
    public $EmployeeWorkExperiences;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.employee_work_experiences',
        'app.employees',
        'app.ledger_accounts',
        'app.account_second_subgroups',
        'app.account_first_subgroups',
        'app.account_groups',
        'app.account_categories',
        'app.customers',
        'app.districts',
        'app.states',
        'app.company_groups',
        'app.companies',
        'app.item_used_by_companies',
        'app.company_banks',
        'app.quotations',
        'app.financial_years',
        'app.financial_months',
        'app.grns',
        'app.purchase_order_rows',
        'app.purchase_orders',
        'app.items',
        'app.item_categories',
        'app.item_groups',
        'app.item_sub_groups',
        'app.units',
        'app.sources',
        'app.item_sources',
        'app.item_companies',
        'app.invoice_rows',
        'app.serial_numbers',
        'app.grn_rows',
        'app.invoice_booking_rows',
        'app.invoice_bookings',
        'app.creator',
        'app.departments',
        'app.designations',
        'app.employee_contact_persons',
        'app.travel_requests',
        'app.employee_hierarchies',
        'app.travel_request_rows',
        'app.emp_data',
        'app.ledgers',
        'app.reference_details',
        'app.old_reference_details',
        'app.receipts',
        'app.vouchers_references',
        'app.voucher_ledger_accounts',
        'app.reference_balances',
        'app.bank_cashes',
        'app.nppayment_rows',
        'app.received_froms',
        'app.payment_rows',
        'app.petty_cash_voucher_rows',
        'app.petty_cash_vouchers',
        'app.invoices',
        'app.customer_groups',
        'app.item_ledgers',
        'app.inventory_vouchers',
        'app.sales_order_rows',
        'app.sales_orders',
        'app.filenames',
        'app.carrier',
        'app.customer_address',
        'app.transporters',
        'app.courier',
        'app.terms_conditions',
        'app.tax_details',
        'app.editor',
        'app.logins',
        'app.user_rights',
        'app.pages',
        'app.user_logs',
        'app.request_leaves',
        'app.leave_types',
        'app.payments',
        'app.vendors',
        'app.vendor_contact_persons',
        'app.vendor_companies',
        'app.receipt_vouchers',
        'app.receipt_breakups',
        'app.journal_vouchers',
        'app.journal_voucher_rows',
        'app.sale_returns',
        'app.sale_taxes',
        'app.sale_tax_companies',
        'app.credit_notes',
        'app.customer_suppilers',
        'app.heads',
        'app.credit_notes_rows',
        'app.account_references',
        'app.sale_return_rows',
        'app.item_serialnumbers',
        'app.inventory_transfer_vouchers',
        'app.inventory_transfer_voucher_rows',
        'app.ivs',
        'app.job_cards',
        'app.job_card_rows',
        'app.iv_rows',
        'app.iv_row_items',
        'app.purchase_returns',
        'app.purchase_return_rows',
        'app.nppayments',
        'app.contra_vouchers',
        'app.contra_voucher_rows',
        'app.approve_leaves',
        'app.employee_companies',
        'app.leave_applications',
        'app.inventory_voucher_rows',
        'app.quotation_rows',
        'app.item_serial_numbers',
        'app.challans',
        'app.challan_rows',
        'app.challan_return_voucher_rows',
        'app.challan_return_vouchers',
        'app.rivs',
        'app.left_rivs',
        'app.right_rivs',
        'app.item_buckets',
        'app.material_indents',
        'app.material_indent_rows',
        'app.new_items',
        'app.new_serial_numbers',
        'app.iv_invoices',
        'app.q_items',
        'app.in_inventory_vouchers',
        'app.master_items',
        'app.invoice_breakups',
        'app.dispatch_documents',
        'app.send_emails',
        'app.receipt_rows',
        'app.payment_vouchers',
        'app.paid_tos',
        'app.payment_breakups',
        'app.debit_notes',
        'app.debit_notes_rows',
        'app.opening_balances',
        'app.vouchers',
        'app.new_item',
        'app.quotation_close_reasons',
        'app.customer_contacts',
        'app.customer_companies',
        'app.customer_segs'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EmployeeWorkExperiences') ? [] : ['className' => 'App\Model\Table\EmployeeWorkExperiencesTable'];
        $this->EmployeeWorkExperiences = TableRegistry::get('EmployeeWorkExperiences', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EmployeeWorkExperiences);

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
