<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;

/**
 * EmployeePersonalInformationRows Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EmpPersonalInformations
 *
 * @method \App\Model\Entity\EmployeePersonalInformationRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\EmployeePersonalInformationRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EmployeePersonalInformationRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmployeePersonalInformationRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmployeePersonalInformationRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeePersonalInformationRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeePersonalInformationRow findOrCreate($search, callable $callback = null)
 */
class EmployeePersonalInformationRowsTable extends Table
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

        $this->table('employee_personal_information_rows');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->belongsTo('EmpPersonalInformations', [
            'foreignKey' => 'employee_personal_information_id',
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
            ->requirePresence('detail_type', 'create')
            ->notEmpty('detail_type');

        /* $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->date('dob')
            ->requirePresence('dob', 'create')
            ->notEmpty('dob');

        $validator
            ->requirePresence('address', 'create')
            ->notEmpty('address');

        $validator
            ->requirePresence('mobile_no', 'create')
            ->notEmpty('mobile_no');

        $validator
            ->requirePresence('phone_no', 'create')
            ->notEmpty('phone_no');

        $validator
            ->requirePresence('relation', 'create')
            ->notEmpty('relation');

        $validator
            ->requirePresence('dependent', 'create')
            ->notEmpty('dependent');

        $validator
            ->requirePresence('whether_employed', 'create')
            ->notEmpty('whether_employed');

        $validator
            ->requirePresence('period', 'create')
            ->notEmpty('period');

        $validator
            ->requirePresence('company_name', 'create')
            ->notEmpty('company_name');

        $validator
            ->requirePresence('designation', 'create')
            ->notEmpty('designation');

        $validator
            ->requirePresence('duties_nature', 'create')
            ->notEmpty('duties_nature'); */

        return $validator;
    }

	public function beforeMarshal(Event $event, ArrayObject $data)
    {
        @$data['dob'] = trim(date('Y-m-d',strtotime(@$data['dob'])));
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
        //$rules->add($rules->existsIn(['emp_personal_information_id'], 'EmpPersonalInformations'));

        return $rules;
    }
}
