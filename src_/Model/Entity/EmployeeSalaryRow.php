<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmployeeSalaryRow Entity
 *
 * @property int $id
 * @property int $employee_salary_id
 * @property int $employee_salary_division_id
 * @property float $amount
 *
 * @property \App\Model\Entity\EmployeeSalary $employee_salary
 * @property \App\Model\Entity\EmployeeSalaryDivision $employee_salary_division
 */
class EmployeeSalaryRow extends Entity
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
