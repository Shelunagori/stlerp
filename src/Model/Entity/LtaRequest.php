<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LtaRequest Entity
 *
 * @property int $id
 * @property int $employee_id
 * @property \Cake\I18n\Time $data_of_submission
 * @property \Cake\I18n\Time $date of_leave_required_from
 * @property \Cake\I18n\Time $date of_leave_required_to
 * @property \Cake\I18n\Time $proposed_date_of_onward_journey
 * @property \Cake\I18n\Time $probable_date_of_return_journey
 * @property string $place_of_visit
 * @property string $particulars_of_ltc_availed_for_block_year
 *
 * @property \App\Model\Entity\Employee $employee
 * @property \App\Model\Entity\LtaRequestMember[] $lta_request_members
 */
class LtaRequest extends Entity
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
