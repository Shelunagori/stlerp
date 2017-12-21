<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OldReferenceDetail Entity
 *
 * @property int $id
 * @property int $ledger_account_id
 * @property int $receipt_id
 * @property int $payment_id
 * @property int $invoice_id
 * @property string $reference_no
 * @property float $credit
 * @property float $debit
 * @property string $reference_type
 * @property int $invoice_booking_id
 * @property int $credit_note_id
 * @property int $journal_voucher_id
 * @property int $auto_inc
 * @property int $sale_return_id
 * @property int $purchase_return_id
 * @property int $petty_cash_voucher_id
 * @property int $nppayment_id
 * @property int $contra_voucher_id
 *
 * @property \App\Model\Entity\LedgerAccount $ledger_account
 * @property \App\Model\Entity\Receipt $receipt
 * @property \App\Model\Entity\Payment $payment
 * @property \App\Model\Entity\Invoice $invoice
 * @property \App\Model\Entity\InvoiceBooking $invoice_booking
 * @property \App\Model\Entity\CreditNote $credit_note
 * @property \App\Model\Entity\JournalVoucher $journal_voucher
 * @property \App\Model\Entity\SaleReturn $sale_return
 * @property \App\Model\Entity\PurchaseReturn $purchase_return
 * @property \App\Model\Entity\PettyCashVoucher $petty_cash_voucher
 * @property \App\Model\Entity\Nppayment $nppayment
 * @property \App\Model\Entity\ContraVoucher $contra_voucher
 */
class OldReferenceDetail extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
