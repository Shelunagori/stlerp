<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * IvRowItem Entity
 *
 * @property int $id
 * @property int $iv_row_id
 * @property int $item_id
 * @property float $quantity
 *
 * @property \App\Model\Entity\IvRow $iv_row
 * @property \App\Model\Entity\Item $item
 */
class IvRowItem extends Entity
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
