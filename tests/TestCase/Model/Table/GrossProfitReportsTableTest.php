<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GrossProfitReportsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GrossProfitReportsTable Test Case
 */
class GrossProfitReportsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GrossProfitReportsTable
     */
    public $GrossProfitReports;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.gross_profit_reports',
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
        'app.employee_family_members',
        'app.employee_emergency_details',
        'app.employee_reference_details',
        'app.employee_work_experiences',
        'app.salaries',
        'app.employee_salary_divisions',
        'app.emp_sal_divs',
        'app.leave_applications',
        'app.employee_hierarchies',
        'app.leave_types',
        'app.payments',
        'app.vendors',
        'app.vendor_contact_persons',
        'app.districts',
        'app.states',
        'app.vendor_companies',
        'app.receipt_vouchers',
        'app.invoices',
        'app.customer_groups',
        'app.item_ledgers',
        'app.inventory_vouchers',
        'app.sales_order_rows',
        'app.sales_orders',
        'app.filenames',
        'app.quotations',
        'app.editor',
        'app.travel_requests',
        'app.nppayments',
        'app.travel_request_rows',
        'app.emp_data',
        'app.logins',
        'app.user_rights',
        'app.pages',
        'app.user_logs',
        'app.request_leaves',
        'app.approve_leaves',
        'app.employee_companies',
        'app.loan_applications',
        'app.employee_salaries',
        'app.employee_salary_rows',
        'app.employee_attendances',
        'app.loan_installments',
        'app.terms_conditions',
        'app.quotation_close_reasons',
        'app.quotation_rows',
        'app.customer_contacts',
        'app.carrier',
        'app.customer_address',
        'app.transporters',
        'app.courier',
        'app.tax_details',
        'app.sale_taxes',
        'app.sale_tax_companies',
        'app.job_cards',
        'app.job_card_rows',
        'app.inventory_voucher_rows',
        'app.serial_numbers',
        'app.grn_rows',
        'app.invoice_booking_rows',
        'app.invoice_bookings',
        'app.purchase_returns',
        'app.purchase_return_rows',
        'app.account_references',
        'app.ivs',
        'app.iv_rows',
        'app.invoice_rows',
        'app.sale_return_rows',
        'app.item_serialnumbers',
        'app.inventory_transfer_vouchers',
        'app.inventory_transfer_voucher_rows',
        'app.sale_returns',
        'app.credit_notes',
        'app.customer_suppilers',
        'app.journal_vouchers',
        'app.journal_voucher_rows',
        'app.heads',
        'app.credit_notes_rows',
        'app.iv_row_items',
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
        'app.receipt_breakups',
        'app.contra_vouchers',
        'app.contra_voucher_rows',
        'app.events',
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
        'app.company_banks',
        'app.item_used_by_companies',
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
        $config = TableRegistry::exists('GrossProfitReports') ? [] : ['className' => 'App\Model\Table\GrossProfitReportsTable'];
        $this->GrossProfitReports = TableRegistry::get('GrossProfitReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GrossProfitReports);

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
