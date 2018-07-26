<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
/**
 * TravelRequests Model
 *
 * @property \Cake\ORM\Association\HasMany $TravelRequestRows
 *
 * @method \App\Model\Entity\TravelRequest get($primaryKey, $options = [])
 * @method \App\Model\Entity\TravelRequest newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TravelRequest[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TravelRequest|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TravelRequest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TravelRequest[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TravelRequest findOrCreate($search, callable $callback = null)
 */
class TravelRequestsTable extends Table
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

        $this->table('travel_requests');
        $this->displayField('id');
        $this->primaryKey('id');
		$this->belongsTo('Employees');
		$this->belongsTo('EmployeeHierarchies');
		$this->belongsTo('Nppayments');
		$this->belongsTo('LedgerAccounts');
		$this->belongsTo('LeaveApplications');
        $this->hasMany('TravelRequestRows', [
            'foreignKey' => 'travel_request_id',
			'saveStrategy' => 'replace'
        ]);
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


        $validator
            ->requirePresence('employee_designation', 'create')
            ->notEmpty('employee_designation');

        return $validator;
    }
	
	public function beforeMarshal(Event $event, ArrayObject $data)
    {
        @$data['travel_from_date'] 		= trim(date('Y-m-d',strtotime(@$data['travel_from_date'])));
        @$data['travel_to_date'] 		= trim(date('Y-m-d',strtotime(@$data['travel_to_date'])));
        @$data['travel_mode_from_date'] = trim(date('Y-m-d',strtotime(@$data['travel_mode_from_date'])));
        @$data['travel_mode_to_date'] 	= trim(date('Y-m-d',strtotime(@$data['travel_mode_to_date'])));
		@$data['return_mode_from_date'] = trim(date('Y-m-d',strtotime(@$data['return_mode_from_date'])));
        @$data['return_mode_to_date'] 	= trim(date('Y-m-d',strtotime(@$data['return_mode_to_date'])));
    }
}
