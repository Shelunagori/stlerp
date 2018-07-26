<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ChallanReturnVoucher Entity
 *
 * @property int $id
 * @property string $voucher_no
 * @property int $company_id
 * @property \Cake\I18n\Time $created_on
 * @property int $created_by
 * @property \Cake\I18n\Time $transaction_date
 * @property int $challan_id
 *
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Challan $challan
 * @property \App\Model\Entity\ChallanReturnVoucherRow[] $challan_return_voucher_rows
 */
class ChallanReturnVoucher extends Entity
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
