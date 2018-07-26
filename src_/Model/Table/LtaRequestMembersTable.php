<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LtaRequestMembers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $LtaRequests
 *
 * @method \App\Model\Entity\LtaRequestMember get($primaryKey, $options = [])
 * @method \App\Model\Entity\LtaRequestMember newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LtaRequestMember[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LtaRequestMember|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LtaRequestMember patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LtaRequestMember[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LtaRequestMember findOrCreate($search, callable $callback = null)
 */
class LtaRequestMembersTable extends Table
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

        $this->table('lta_request_members');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->belongsTo('LtaRequests', [
            'foreignKey' => 'lta_request_id',
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('age', 'create')
            ->notEmpty('age');

        $validator
            ->requirePresence('relation', 'create')
            ->notEmpty('relation');

        $validator
            ->requirePresence('whether_dependent', 'create')
            ->notEmpty('whether_dependent');

        return $validator;
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
        $rules->add($rules->existsIn(['lta_request_id'], 'LtaRequests'));

        return $rules;
    }
}
