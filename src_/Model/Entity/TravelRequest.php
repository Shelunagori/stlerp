<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TravelRequest Entity
 *
 * @property int $id
 * @property string $employee_name
 * @property string $employee_designation
 * @property string $purpose
 * @property string $purpose_specification
 * @property float $advance_amt
 * @property \Cake\I18n\Time $travel_from_date
 * @property \Cake\I18n\Time $travel_to_date
 * @property string $travel_mode
 * @property string $return_travel_mode
 * @property \Cake\I18n\Time $travel_mode_from_date
 * @property \Cake\I18n\Time $travel_mode_to_date
 * @property \Cake\I18n\Time $returnl_mode_to_date
 * @property \Cake\I18n\Time $returnl_mode_from_date
 * @property string $other_mode
 * @property \Cake\I18n\Time $create_date
 *
 * @property \App\Model\Entity\TravelRequestRow[] $travel_request_rows
 */
class TravelRequest extends Entity
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
