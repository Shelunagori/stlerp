<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmployeeRecord Entity
 *
 * @property int $id
 * @property int $employee_id
 * @property float $amount
 * @property int $total_attenence
 * @property int $overtime
 * @property \Cake\I18n\Time $month_year
 * @property \Cake\I18n\Time $create_date
 *
 * @property \App\Model\Entity\Employee $employee
 */
class EmployeeRecord extends Entity
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
