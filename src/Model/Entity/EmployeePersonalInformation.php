<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmployeePersonalInformation Entity
 *
 * @property int $id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property \Cake\I18n\Time $date_of_birth
 * @property string $family_member_type
 * @property string $family_member_name
 * @property string $gender
 * @property string $identity_mark
 * @property string $caste
 * @property string $religion
 * @property int $home_state
 * @property string $adhar_card_no
 * @property int $passport_no
 * @property string $account_type
 * @property int $account_no
 * @property string $branch_ifsc_code
 * @property string $martial_status
 * @property int $height
 * @property string $category
 * @property string $blood_group
 * @property int $home_district
 * @property string $driving_licence_no
 * @property string $pan_card_no
 * @property string $bank_branch
 * @property string $present_address
 * @property int $present_state
 * @property int $present_district
 * @property string $present_pin_code
 * @property string $present_mobile_no
 * @property string $present_phone_no
 * @property string $present_email
 * @property string $permanent_address
 * @property int $permanent_state
 * @property int $permanent_district
 * @property string $permanent_pin_code
 * @property string $permanent_mobile_no
 * @property string $permanent_phone_no
 * @property string $permanent_email
 * @property string $nominee_name
 * @property string $relation_with_employee
 * @property string $nomination_type
 * @property string $nominee_present_address
 * @property int $nominee_state
 * @property int $nominee_district
 * @property string $nominee_pin_code
 * @property string $nominee_mobile_no
 * @property \Cake\I18n\Time $appointment_date
 * @property string $employee_id
 * @property \Cake\I18n\Time $dept_joining_date
 * @property string $initial_designation
 * @property string $office_name
 * @property string $recruitment_mode
 * @property int $reporting_to
 * @property int $basic_pay
 * @property \Cake\I18n\Time $retirement_date
 * @property string $deduction_type
 * @property string $gpf_no
 *
 * @property \App\Model\Entity\Employee $employee
 */
class EmployeePersonalInformation extends Entity
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
