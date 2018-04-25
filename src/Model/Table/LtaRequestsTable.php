<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
/**
 * LtaRequests Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Employees
 * @property \Cake\ORM\Association\HasMany $LtaRequestMembers
 *
 * @method \App\Model\Entity\LtaRequest get($primaryKey, $options = [])
 * @method \App\Model\Entity\LtaRequest newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LtaRequest[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LtaRequest|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LtaRequest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LtaRequest[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LtaRequest findOrCreate($search, callable $callback = null)
 */
class LtaRequestsTable extends Table
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

        $this->table('lta_requests');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Employees', [
            'foreignKey' => 'employee_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('LtaRequestMembers', [
            'foreignKey' => 'lta_request_id',
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

        $validator
            ->requirePresence('data_of_submission', 'create')
            ->notEmpty('data_of_submission');

        $validator
            ->requirePresence('date_of_leave_required_from', 'create')
            ->notEmpty('date_of_leave_required_from');

        $validator
            ->requirePresence('date_of_leave_required_to', 'create')
            ->notEmpty('date_of_leave_required_from');

        $validator
            ->requirePresence('proposed_date_of_onward_journey', 'create')
            ->notEmpty('proposed_date_of_onward_journey');

        $validator
            ->requirePresence('probable_date_of_return_journey', 'create')
            ->notEmpty('probable_date_of_return_journey');

        $validator
            ->requirePresence('place_of_visit', 'create')
            ->notEmpty('place_of_visit');

        $validator
            ->requirePresence('particulars_of_ltc_availed_for_block_year', 'create')
            ->notEmpty('particulars_of_ltc_availed_for_block_year');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
	 
	public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
	{
		$data['data_of_submission'] = date('Y-m-d',strtotime($data['data_of_submission']));
		$data['date_of_leave_required_from'] = date('Y-m-d',strtotime($data['date_of_leave_required_from']));
		$data['date_of_leave_required_to'] = date('Y-m-d',strtotime($data['date_of_leave_required_to']));
		$data['proposed_date_of_onward_journey'] = date('Y-m-d',strtotime($data['proposed_date_of_onward_journey']));
		$data['probable_date_of_return_journey'] = date('Y-m-d',strtotime($data['probable_date_of_return_journey']));
	}
	
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['employee_id'], 'Employees'));

        return $rules;
    }
}
