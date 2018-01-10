<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TravelRequestRow Entity
 *
 * @property int $id
 * @property int $travel_request_id
 * @property string $party_name
 * @property string $destination
 * @property string $meeting_person
 * @property \Cake\I18n\Time $date
 * @property \Cake\I18n\Time $reporting_time
 *
 * @property \App\Model\Entity\TravelRequest $travel_request
 */
class TravelRequestRow extends Entity
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
