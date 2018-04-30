<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EmployeeAttendances Model
 *
 * @property \Cake\ORM\Association\BelongsTo $FinancialYears
 * @property \Cake\ORM\Association\BelongsTo $Employees
 *
 * @method \App\Model\Entity\EmployeeAttendance get($primaryKey, $options = [])
 * @method \App\Model\Entity\EmployeeAttendance newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EmployeeAttendance[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeAttendance|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmployeeAttendance patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeAttendance[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeAttendance findOrCreate($search, callable $callback = null)
 */
class EmployeeAttendancesTable extends Table
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

        $this->table('employee_attendances');
        $this->displayField('id');
        $this->primaryKey('id');
		$this->belongsTo('LeaveApplications');
        $this->belongsTo('FinancialYears', [
            'foreignKey' => 'financial_year_id',
            'joinType' => 'INNER'
        ]);
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
            ->integer('month')
            ->requirePresence('month', 'create')
            ->notEmpty('month');

        $validator
            ->integer('total_month_day')
            ->requirePresence('total_month_day', 'create')
            ->notEmpty('total_month_day');

        $validator
            ->integer('no_of_leave')
            ->requirePresence('no_of_leave', 'create')
            ->notEmpty('no_of_leave');

        $validator
            ->integer('present_day')
            ->requirePresence('present_day', 'create')
            ->notEmpty('present_day');

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
        $rules->add($rules->existsIn(['financial_year_id'], 'FinancialYears'));
        $rules->add($rules->existsIn(['employee_id'], 'Employees'));

        return $rules;
    }
}
