<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmployeeAttendance Entity
 *
 * @property int $id
 * @property int $financial_year_id
 * @property int $month
 * @property int $employee_id
 * @property int $total_month_day
 * @property int $no_of_leave
 * @property int $present_day
 *
 * @property \App\Model\Entity\FinancialYear $financial_year
 * @property \App\Model\Entity\Employee $employee
 */
class EmployeeAttendance extends Entity
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
