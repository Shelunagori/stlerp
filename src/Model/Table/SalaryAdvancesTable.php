<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
/**
 * SalaryAdvances Model
 *
 * @method \App\Model\Entity\SalaryAdvance get($primaryKey, $options = [])
 * @method \App\Model\Entity\SalaryAdvance newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SalaryAdvance[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SalaryAdvance|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SalaryAdvance patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SalaryAdvance[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SalaryAdvance findOrCreate($search, callable $callback = null)
 */
class SalaryAdvancesTable extends Table
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

        $this->table('salary_advances');
        $this->displayField('id');
        $this->primaryKey('id');
		$this->belongsTo('Employees');
		$this->belongsTo('EmployeeSalaries');
		$this->belongsTo('Nppayments');
		$this->belongsTo('LedgerAccounts');
		$this->belongsTo('LoanApplications');
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

        

        return $validator;
    }
	
	public function beforeMarshal(Event $event, ArrayObject $data)
    {
        @$data['create_date']      = trim(date('Y-m-d'));
    }
}
