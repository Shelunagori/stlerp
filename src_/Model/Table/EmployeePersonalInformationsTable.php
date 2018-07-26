<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
/**
 * EmployeePersonalInformations Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Employees
 *
 * @method \App\Model\Entity\EmployeePersonalInformation get($primaryKey, $options = [])
 * @method \App\Model\Entity\EmployeePersonalInformation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EmployeePersonalInformation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmployeePersonalInformation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmployeePersonalInformation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeePersonalInformation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeePersonalInformation findOrCreate($search, callable $callback = null)
 */
class EmployeePersonalInformationsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('employee_personal_informations');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Employees', [
            'foreignKey' => 'employee_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('States');
		$this->belongsTo('Districts');
		$this->hasMany('EmployeePersonalInformationRows', [
            'foreignKey' => 'employee_personal_information_id',
			'saveStrategy' => 'replace'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        /* $validator
            ->requirePresence('first_name', 'create')
            ->notEmpty('first_name');

        $validator
            ->requirePresence('middle_name', 'create')
            ->notEmpty('middle_name');

        $validator
            ->requirePresence('last_name', 'create')
            ->notEmpty('last_name');

        $validator
            ->date('date_of_birth')
            ->requirePresence('date_of_birth', 'create')
            ->notEmpty('date_of_birth');

        $validator
            ->requirePresence('family_member_type', 'create')
            ->notEmpty('family_member_type');

        $validator
            ->requirePresence('family_member_name', 'create')
            ->notEmpty('family_member_name');

        $validator
            ->requirePresence('gender', 'create')
            ->notEmpty('gender');

        $validator
            ->requirePresence('identity_mark', 'create')
            ->notEmpty('identity_mark');

        $validator
            ->requirePresence('caste', 'create')
            ->notEmpty('caste');

        $validator
            ->requirePresence('religion', 'create')
            ->notEmpty('religion');

        $validator
            ->integer('home_state')
            ->requirePresence('home_state', 'create')
            ->notEmpty('home_state');

        $validator
            ->requirePresence('adhar_card_no', 'create')
            ->notEmpty('adhar_card_no');

        $validator
            ->integer('passport_no')
            ->requirePresence('passport_no', 'create')
            ->notEmpty('passport_no');

        $validator
            ->requirePresence('account_type', 'create')
            ->notEmpty('account_type');

        $validator
            ->requirePresence('account_no', 'create')
            ->notEmpty('account_no');

        $validator
            ->requirePresence('branch_ifsc_code', 'create')
            ->notEmpty('branch_ifsc_code');

        $validator
            ->requirePresence('martial_status', 'create')
            ->notEmpty('martial_status');

        $validator
            ->integer('height')
            ->requirePresence('height', 'create')
            ->notEmpty('height');

        $validator
            ->requirePresence('category', 'create')
            ->notEmpty('category');

        $validator
            ->requirePresence('blood_group', 'create')
            ->notEmpty('blood_group');

        $validator
            ->integer('home_district')
            ->requirePresence('home_district', 'create')
            ->notEmpty('home_district');

        $validator
            ->requirePresence('driving_licence_no', 'create')
            ->notEmpty('driving_licence_no');

        $validator
            ->requirePresence('pan_card_no', 'create')
            ->notEmpty('pan_card_no');

        $validator
            ->requirePresence('bank_branch', 'create')
            ->notEmpty('bank_branch');

        $validator
            ->requirePresence('present_address', 'create')
            ->notEmpty('present_address');

        $validator
            ->integer('present_state')
            ->requirePresence('present_state', 'create')
            ->notEmpty('present_state');

        $validator
            ->integer('present_district')
            ->requirePresence('present_district', 'create')
            ->notEmpty('present_district');

        $validator
            ->requirePresence('present_pin_code', 'create')
            ->notEmpty('present_pin_code');

        $validator
            ->requirePresence('present_mobile_no', 'create')
            ->notEmpty('present_mobile_no');

        $validator
            ->requirePresence('present_phone_no', 'create')
            ->notEmpty('present_phone_no');

        $validator
            ->requirePresence('present_email', 'create')
            ->notEmpty('present_email');

        $validator
            ->requirePresence('permanent_address', 'create')
            ->notEmpty('permanent_address');

        $validator
            ->integer('permanent_state')
            ->requirePresence('permanent_state', 'create')
            ->notEmpty('permanent_state');

        $validator
            ->integer('permanent_district')
            ->requirePresence('permanent_district', 'create')
            ->notEmpty('permanent_district');

        $validator
            ->requirePresence('permanent_pin_code', 'create')
            ->notEmpty('permanent_pin_code');

        $validator
            ->requirePresence('permanent_mobile_no', 'create')
            ->notEmpty('permanent_mobile_no');

        $validator
            ->requirePresence('permanent_phone_no', 'create')
            ->notEmpty('permanent_phone_no');

        $validator
            ->requirePresence('permanent_email', 'create')
            ->notEmpty('permanent_email');

        $validator
            ->requirePresence('nominee_name', 'create')
            ->notEmpty('nominee_name');

        $validator
            ->requirePresence('relation_with_employee', 'create')
            ->notEmpty('relation_with_employee');

        $validator
            ->requirePresence('nomination_type', 'create')
            ->notEmpty('nomination_type');

        $validator
            ->requirePresence('nominee_present_address', 'create')
            ->notEmpty('nominee_present_address');

        $validator
            ->integer('nominee_state')
            ->requirePresence('nominee_state', 'create')
            ->notEmpty('nominee_state');

        $validator
            ->integer('nominee_district')
            ->requirePresence('nominee_district', 'create')
            ->notEmpty('nominee_district');

        $validator
            ->requirePresence('nominee_pin_code', 'create')
            ->notEmpty('nominee_pin_code');

        $validator
            ->requirePresence('nominee_mobile_no', 'create')
            ->notEmpty('nominee_mobile_no');

        $validator
            ->date('appointment_date')
            ->requirePresence('appointment_date', 'create')
            ->notEmpty('appointment_date');

        $validator
            ->date('dept_joining_date')
            ->requirePresence('dept_joining_date', 'create')
            ->notEmpty('dept_joining_date');

        $validator
            ->requirePresence('initial_designation', 'create')
            ->notEmpty('initial_designation');

        $validator
            ->requirePresence('office_name', 'create')
            ->notEmpty('office_name');

        $validator
            ->requirePresence('recruitment_mode', 'create')
            ->notEmpty('recruitment_mode');

        $validator
            ->integer('reporting_to')
            ->requirePresence('reporting_to', 'create')
            ->notEmpty('reporting_to');

        $validator
            ->requirePresence('basic_pay', 'create')
            ->notEmpty('basic_pay');

        $validator
            ->date('retirement_date')
            ->requirePresence('retirement_date', 'create')
            ->notEmpty('retirement_date');

        $validator
            ->requirePresence('deduction_type', 'create')
            ->notEmpty('deduction_type');

        $validator
            ->requirePresence('gpf_no', 'create')
            ->notEmpty('gpf_no'); */

        return $validator;
    }

	public function beforeMarshal(Event $event, ArrayObject $data)
    {
        @$data['date_of_birth'] = trim(date('Y-m-d',strtotime(@$data['date_of_birth'])));
        @$data['appointment_date'] = trim(date('Y-m-d',strtotime(@$data['appointment_date'])));
        @$data['dept_joining_date'] = trim(date('Y-m-d',strtotime(@$data['dept_joining_date'])));
        @$data['retirement_date'] = trim(date('Y-m-d',strtotime(@$data['retirement_date'])));
    }
	
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        //$rules->add($rules->existsIn(['employee_id'], 'Employees'));

        return $rules;
    }
}
