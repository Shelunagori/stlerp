<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EmployeeAttendancesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EmployeeAttendancesTable Test Case
 */
class EmployeeAttendancesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EmployeeAttendancesTable
     */
    public $EmployeeAttendances;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.employee_attendances',
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
        'app.companies',
        'app.company_groups',
        'app.customers',
        'app.ledger_accounts',
        'app.account_second_subgroups',
        'app.account_first_subgroups',
        'app.account_groups',
        'app.account_categories',
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
        'app.creator',
        'app.departments',
        'app.employees',
        'app.designations',
        'app.employee_contact_persons',
        'app.states',
        'app.districts',
        'app.travel_requests',
        'app.employee_hierarchies',
        'app.travel_request_rows',
        'app.emp_data',
        'app.quotations',
        'app.sales_orders',
        'app.filenames',
        'app.invoices',
        'app.customer_groups',
        'app.item_ledgers',
        'app.inventory_vouchers',
        'app.sales_order_rows',
        'app.sale_taxes',
        'app.sale_tax_companies',
        'app.job_card_rows',
        'app.job_cards',
        'app.inventory_voucher_rows',
        'app.serial_numbers',
        'app.grn_rows',
        'app.invoice_booking_rows',
        'app.invoice_bookings',
        'app.purchase_returns',
        'app.vendors',
        'app.vendor_contact_persons',
        'app.vendor_companies',
        'app.receipt_vouchers',
        'app.receipt_breakups',
        'app.payments',
        'app.journal_vouchers',
        'app.journal_voucher_rows',
        'app.sale_returns',
        'app.transporters',
        'app.customer_address',
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
        'app.iv_rows',
        'app.invoice_rows',
        'app.iv_row_items',
        'app.nppayments',
        'app.contra_vouchers',
        'app.contra_voucher_rows',
        'app.purchase_return_rows',
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
        'app.terms_conditions',
        'app.invoice_breakups',
        'app.dispatch_documents',
        'app.send_emails',
        'app.carrier',
        'app.courier',
        'app.tax_details',
        'app.editor',
        'app.logins',
        'app.user_rights',
        'app.pages',
        'app.user_logs',
        'app.request_leaves',
        'app.leave_types',
        'app.approve_leaves',
        'app.employee_companies',
        'app.leave_applications',
        'app.quotation_close_reasons',
        'app.customer_contacts',
        'app.receipt_rows',
        'app.payment_vouchers',
        'app.paid_tos',
        'app.payment_breakups',
        'app.debit_notes',
        'app.debit_notes_rows',
        'app.opening_balances',
        'app.vouchers',
        'app.customer_segs',
        'app.customer_companies',
        'app.item_used_by_companies',
        'app.company_banks',
        'app.item_companies',
        'app.new_item'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EmployeeAttendances') ? [] : ['className' => 'App\Model\Table\EmployeeAttendancesTable'];
        $this->EmployeeAttendances = TableRegistry::get('EmployeeAttendances', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EmployeeAttendances);

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
