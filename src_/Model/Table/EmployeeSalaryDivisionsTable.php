<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EmployeeSalaryDivisions Model
 *
 * @method \App\Model\Entity\EmployeeSalaryDivision get($primaryKey, $options = [])
 * @method \App\Model\Entity\EmployeeSalaryDivision newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EmployeeSalaryDivision[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeSalaryDivision|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmployeeSalaryDivision patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeSalaryDivision[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeSalaryDivision findOrCreate($search, callable $callback = null)
 */
class EmployeeSalaryDivisionsTable extends Table
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

        $this->table('employee_salary_divisions');
        $this->displayField('name');
        $this->primaryKey('id');
		$this->belongsTo('LedgerAccounts');
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

        

        return $validator;
    }
}
