<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmployeeSalary Entity
 *
 * @property int $id
 * @property int $employee_id
 * @property int $employee_salary_division_id
 * @property \Cake\I18n\Time $effective_date
 * @property float $amount
 * @property \Cake\I18n\Time $created_on
 *
 * @property \App\Model\Entity\Employee $employee
 * @property \App\Model\Entity\EmployeeSalaryDivision $employee_salary_division
 */
class EmployeeSalary extends Entity
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
