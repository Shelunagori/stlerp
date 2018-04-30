<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EmployeeSalaries Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Employees
 * @property \Cake\ORM\Association\BelongsTo $EmployeeSalaryDivisions
 *
 * @method \App\Model\Entity\EmployeeSalary get($primaryKey, $options = [])
 * @method \App\Model\Entity\EmployeeSalary newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EmployeeSalary[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeSalary|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmployeeSalary patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeSalary[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeSalary findOrCreate($search, callable $callback = null)
 */
class EmployeeSalariesTable extends Table
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

        $this->table('employee_salaries');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Employees', [
            'foreignKey' => 'employee_id',
            'joinType' => 'INNER'
        ]);
         $this->hasMany('EmployeeSalaryRows', [
            'foreignKey' => 'employee_salary_id',
			'saveStrategy' => 'replace'
        ]);
		
		$this->belongsTo('FinancialYears');
		$this->belongsTo('EmployeeAttendances');
		
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
            ->date('effective_date_from')
            ->requirePresence('effective_date_from', 'create')
            ->notEmpty('effective_date_from');

		$validator
            ->date('effective_date_to')
            ->requirePresence('effective_date_to', 'create')
            ->notEmpty('effective_date_to');

     
        $validator
            ->date('created_on')
            ->requirePresence('created_on', 'create')
            ->notEmpty('created_on');

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
        //$rules->add($rules->existsIn(['employee_salary_division_id'], 'EmployeeSalaryDivisions'));

        return $rules;
    }
}
