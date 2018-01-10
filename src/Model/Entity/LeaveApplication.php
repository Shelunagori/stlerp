<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LeaveApplication Entity
 *
 * @property int $id
 * @property string $name
 * @property \Cake\I18n\Time $submission_date
 * @property \Cake\I18n\Time $from_leave_date
 * @property \Cake\I18n\Time $to_leave_date
 * @property string $day_no
 * @property string $leave_reason
 * @property string $leave_type
 * @property string $supporting_attached
 * @property \Cake\I18n\Time $create_date
 */
class LeaveApplication extends Entity
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
