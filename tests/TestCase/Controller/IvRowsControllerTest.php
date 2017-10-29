<?php
namespace App\Test\TestCase\Controller;

use App\Controller\IvRowsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\IvRowsController Test Case
 */
class IvRowsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.iv_rows',
        'app.ivs',
        'app.invoices',
        'app.customer_groups',
        'app.customers',
        'app.ledger_accounts',
        'app.account_second_subgroups',
        'app.account_first_subgroups',
        'app.account_groups',
        'app.account_categories',
        'app.ledgers',
        'app.reference_details',
        'app.receipt_vouchers',
        'app.vouchers_references',
        'app.voucher_ledger_accounts',
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
        'app.item_used_by_companies',
        'app.company_banks',
        'app.quotations',
        'app.employees',
        'app.departments',
        'app.designations',
        'app.employee_contact_persons',
        'app.sales_orders',
        'app.filenames',
        'app.carrier',
        'app.customer_address',
        'app.districts',
        'app.states',
        'app.transporters',
        'app.courier',
        'app.terms_conditions',
        'app.tax_details',
        'app.creator',
        'app.logins',
        'app.user_rights',
        'app.pages',
        'app.user_logs',
        'app.request_leaves',
        'app.leave_types',
        'app.payments',
        'app.reference_balances',
        'app.bank_cashes',
        'app.item_ledgers',
        'app.inventory_vouchers',
        'app.sales_order_rows',
        'app.sale_taxes',
        'app.sale_tax_companies',
        'app.job_card_rows',
        'app.job_cards',
        'app.inventory_voucher_rows',
        'app.invoice_rows',
        'app.item_serialnumbers',
        'app.inventory_transfer_vouchers',
        'app.inventory_transfer_voucher_rows',
        'app.item_serial_numbers',
        'app.purchase_returns',
        'app.invoice_bookings',
        'app.invoice_booking_rows',
        'app.purchase_return_rows',
        'app.grn_rows',
        'app.vendors',
        'app.vendor_contact_persons',
        'app.vendor_companies',
        'app.receipts',
        'app.received_froms',
        'app.receipt_rows',
        'app.journal_vouchers',
        'app.journal_voucher_rows',
        'app.sale_returns',
        'app.account_references',
        'app.sale_return_rows',
        'app.petty_cash_vouchers',
        'app.petty_cash_voucher_rows',
        'app.nppayments',
        'app.nppayment_rows',
        'app.contra_vouchers',
        'app.contra_voucher_rows',
        'app.credit_notes',
        'app.customer_suppilers',
        'app.heads',
        'app.credit_notes_rows',
        'app.challans',
        'app.challan_rows',
        'app.rivs',
        'app.left_rivs',
        'app.right_rivs',
        'app.item_buckets',
        'app.material_indents',
        'app.material_indent_rows',
        'app.new_items',
        'app.payment_rows',
        'app.approve_leaves',
        'app.employee_companies',
        'app.editor',
        'app.quotation_close_reasons',
        'app.quotation_rows',
        'app.customer_contacts',
        'app.customer_companies',
        'app.item_companies',
        'app.new_item',
        'app.receipt_breakups',
        'app.payment_vouchers',
        'app.paid_tos',
        'app.payment_breakups',
        'app.debit_notes',
        'app.debit_notes_rows',
        'app.customer_segs',
        'app.invoice_breakups',
        'app.iv_row_items',
        'app.serial_numbers'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
