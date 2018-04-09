<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EmployeeHierarchies Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Employees
 *
 * @method \App\Model\Entity\EmployeeHierarchy get($primaryKey, $options = [])
 * @method \App\Model\Entity\EmployeeHierarchy newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EmployeeHierarchy[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeHierarchy|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmployeeHierarchy patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeHierarchy[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeHierarchy findOrCreate($search, callable $callback = null)
 */
class EmployeeHierarchiesTable extends Table
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

        $this->table('employee_hierarchies');
        $this->displayField('name');
        $this->primaryKey('id');
		$this->addBehavior('Tree', [
			'parent' => 'parent_id', // Use this instead of parent_id
			'left' => 'lft', // Use this instead of lft
			'right' => 'rght' // Use this instead of rght
		]);
        $this->belongsTo('Employees', [
            'foreignKey' => 'employee_id',
            'joinType' => 'INNER'
        ]);
		
		$this->belongsTo('ParentAccountingGroups', [
            'className' => 'EmployeeHierarchies',
            'foreignKey' => 'parent_id'
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
            ->integer('lft')
            ->requirePresence('lft', 'create')
            ->notEmpty('lft');

        $validator
            ->integer('rgft')
            ->requirePresence('rgft', 'create')
            ->notEmpty('rgft'); */

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
