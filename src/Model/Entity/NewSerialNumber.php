<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NewSerialNumber Entity
 *
 * @property int $id
 * @property int $item_id
 * @property string $serial_no
 * @property string $status
 * @property int $grn_id
 * @property int $invoice_id
 * @property int $iv_invoice_id
 * @property int $q_item_id
 * @property int $in_inventory_voucher_id
 * @property int $master_item_id
 * @property int $company_id
 * @property int $sale_return_id
 * @property int $purchase_return_id
 * @property int $inventory_transfer_voucher_id
 *
 * @property \App\Model\Entity\Item $item
 * @property \App\Model\Entity\Grn $grn
 * @property \App\Model\Entity\Invoice $invoice
 * @property \App\Model\Entity\IvInvoice $iv_invoice
 * @property \App\Model\Entity\QItem $q_item
 * @property \App\Model\Entity\InInventoryVoucher $in_inventory_voucher
 * @property \App\Model\Entity\MasterItem $master_item
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\SaleReturn $sale_return
 * @property \App\Model\Entity\PurchaseReturn $purchase_return
 * @property \App\Model\Entity\InventoryTransferVoucher $inventory_transfer_voucher
 */
class NewSerialNumber extends Entity
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
