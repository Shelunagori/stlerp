<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OpeningBalance Entity
 *
 * @property int $id
 * @property \Cake\I18n\Time $transaction_date
 * @property int $ledger_account_id
 * @property float $debit
 * @property float $credit
 * @property int $voucher_id
 * @property string $voucher_source
 * @property int $company_id
 * @property string $ref_no
 * @property \Cake\I18n\Time $reconciliation_date
 *
 * @property \App\Model\Entity\LedgerAccount $ledger_account
 * @property \App\Model\Entity\Voucher $voucher
 * @property \App\Model\Entity\Company $company
 */
class OpeningBalance extends Entity
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
