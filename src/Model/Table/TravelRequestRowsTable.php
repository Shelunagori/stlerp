<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
/**
 * TravelRequestRows Model
 *
 * @property \Cake\ORM\Association\BelongsTo $TravelRequests
 *
 * @method \App\Model\Entity\TravelRequestRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\TravelRequestRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TravelRequestRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TravelRequestRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TravelRequestRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TravelRequestRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TravelRequestRow findOrCreate($search, callable $callback = null)
 */
class TravelRequestRowsTable extends Table
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

        $this->table('travel_request_rows');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('TravelRequests', [
            'foreignKey' => 'travel_request_id',
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

        /*$validator
            ->requirePresence('party_name', 'create')
            ->notEmpty('party_name');

        $validator
            ->requirePresence('destination', 'create')
            ->notEmpty('destination');

         $validator
            ->requirePresence('meeting_person', 'create')
            ->notEmpty('meeting_person');

        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmpty('date');

        $validator
            ->time('reporting_time')
            ->requirePresence('reporting_time', 'create')
            ->notEmpty('reporting_time');
 */
        return $validator;
    }
	public function beforeMarshal(Event $event, ArrayObject $data)
    {
        @$data['date'] 		= trim(date('Y-m-d',strtotime(@$data['date'])));
    }
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    /* public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['travel_request_id'], 'TravelRequests'));

        return $rules;
    } */
}
