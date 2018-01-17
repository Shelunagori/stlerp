<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EmployeeRecords Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Employees
 *
 * @method \App\Model\Entity\EmployeeRecord get($primaryKey, $options = [])
 * @method \App\Model\Entity\EmployeeRecord newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EmployeeRecord[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeRecord|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmployeeRecord patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeRecord[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeRecord findOrCreate($search, callable $callback = null)
 */
class EmployeeRecordsTable extends Table
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

        $this->table('employee_records');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Employees', [
            'foreignKey' => 'employee_id',
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
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmpty('amount');

        $validator
            ->requirePresence('total_attenence', 'create')
            ->notEmpty('total_attenence');

        $validator
            ->requirePresence('overtime', 'create')
            ->notEmpty('overtime');

        $validator
            ->date('month_year')
            ->requirePresence('month_year', 'create')
            ->notEmpty('month_year');

        $validator
            ->date('create_date')
            ->requirePresence('create_date', 'create')
            ->notEmpty('create_date');

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
        $rules->add($rules->existsIn(['employee_id'], 'Employees'));

        return $rules;
    }
}
