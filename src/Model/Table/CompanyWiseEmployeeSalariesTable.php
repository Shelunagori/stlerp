<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CompanyWiseEmployeeSalaries Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Employees
 * @property \Cake\ORM\Association\BelongsTo $Companies
 *
 * @method \App\Model\Entity\CompanyWiseEmployeeSalary get($primaryKey, $options = [])
 * @method \App\Model\Entity\CompanyWiseEmployeeSalary newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CompanyWiseEmployeeSalary[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CompanyWiseEmployeeSalary|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CompanyWiseEmployeeSalary patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CompanyWiseEmployeeSalary[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CompanyWiseEmployeeSalary findOrCreate($search, callable $callback = null)
 */
class CompanyWiseEmployeeSalariesTable extends Table
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

        $this->table('company_wise_employee_salaries');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Employees', [
            'foreignKey' => 'employee_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
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
            ->date('from_date')
            ->requirePresence('from_date', 'create')
            ->notEmpty('from_date');

        $validator
            ->date('to_date')
            ->requirePresence('to_date', 'create')
            ->notEmpty('to_date');

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
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }
}
