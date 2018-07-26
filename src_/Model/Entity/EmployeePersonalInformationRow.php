<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmployeePersonalInformationRow Entity
 *
 * @property int $id
 * @property int $emp_personal_information_id
 * @property string $detail_type
 * @property string $name
 * @property \Cake\I18n\Time $dob
 * @property string $address
 * @property string $mobile_no
 * @property string $phone_no
 * @property string $relation
 * @property string $dependent
 * @property string $whether_employed
 * @property string $period
 * @property string $company_name
 * @property string $designation
 * @property string $duties_nature
 *
 * @property \App\Model\Entity\EmpPersonalInformation $emp_personal_information
 */
class EmployeePersonalInformationRow extends Entity
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
