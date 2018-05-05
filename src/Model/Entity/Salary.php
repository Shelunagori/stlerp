<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Salary Entity
 *
 * @property int $id
 * @property int $employee_id
 * @property int $company_id
 * @property int $employee_salary_division_id
 * @property float $amount
 * @property float $loan_amount
 * @property float $other_amount
 *
 * @property \App\Model\Entity\Employee $employee
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\EmployeeSalaryDivision $employee_salary_division
 */
class Salary extends Entity
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
