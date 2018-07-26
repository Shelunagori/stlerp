<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * IvRow Entity
 *
 * @property int $id
 * @property int $iv_id
 * @property int $invoice_row_id
 * @property int $item_id
 * @property float $quantity
 *
 * @property \App\Model\Entity\Iv $iv
 * @property \App\Model\Entity\InvoiceRow $invoice_row
 * @property \App\Model\Entity\Item $item
 * @property \App\Model\Entity\IvRowItem[] $iv_row_items
 * @property \App\Model\Entity\SerialNumber[] $serial_numbers
 */
class IvRow extends Entity
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
