<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LoanApplication Entity
 *
 * @property int $id
 * @property string $employee_name
 * @property string $reason_for _loan
 * @property float $salary_pm
 * @property string $amount _of_loan
 * @property string $amount _of_loan_in_word
 * @property \Cake\I18n\Time $starting_date_of_loan
 * @property \Cake\I18n\Time $ending_date_of_loan
 * @property string $remark
 * @property \Cake\I18n\Time $create_date
 */
class LoanApplication extends Entity
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
