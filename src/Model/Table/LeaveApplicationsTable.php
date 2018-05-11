<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
/**
 * LeaveApplications Model
 *
 * @method \App\Model\Entity\LeaveApplication get($primaryKey, $options = [])
 * @method \App\Model\Entity\LeaveApplication newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LeaveApplication[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LeaveApplication|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LeaveApplication patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LeaveApplication[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LeaveApplication findOrCreate($search, callable $callback = null)
 */
class LeaveApplicationsTable extends Table
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

        $this->table('leave_applications');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->belongsTo('Employees');
        $this->belongsTo('EmployeeHierarchies');
        $this->belongsTo('FinancialYears');
        $this->belongsTo('FinancialMonths');
        $this->belongsTo('LeaveTypes');
        $this->belongsTo('TravelRequests');
		$this->belongsTo('empData', [
			'className' => 'Employees',
            'foreignKey' => 'parent_employee_id',
            'joinType' => 'INNER'
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

       /*  $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');
 */
        $validator
            ->date('submission_date')
            ->requirePresence('submission_date', 'create')
            ->notEmpty('submission_date');

        $validator
            ->date('from_leave_date')
            ->requirePresence('from_leave_date', 'create')
            ->notEmpty('from_leave_date');

        $validator
            ->date('to_leave_date')
            ->requirePresence('to_leave_date', 'create')
            ->notEmpty('to_leave_date');

        $validator
            ->requirePresence('day_no', 'create')
            ->notEmpty('day_no');

        $validator
            ->requirePresence('leave_reason', 'create')
            ->notEmpty('leave_reason');

      /*   $validator
            ->requirePresence('leave_type', 'create')
            ->notEmpty('leave_type'); */


        

        return $validator;
    }
	
	public function beforeMarshal(Event $event, ArrayObject $data)
    {
        @$data['submission_date'] 		= trim(date('Y-m-d',strtotime(@$data['submission_date'])));
        @$data['from_leave_date'] 		= trim(date('Y-m-d',strtotime(@$data['from_leave_date'])));
        @$data['to_leave_date'] 		= trim(date('Y-m-d',strtotime(@$data['to_leave_date'])));
       
    }
}
