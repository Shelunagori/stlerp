<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
/**
 * LoanApplications Model
 *
 * @method \App\Model\Entity\LoanApplication get($primaryKey, $options = [])
 * @method \App\Model\Entity\LoanApplication newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LoanApplication[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LoanApplication|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LoanApplication patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LoanApplication[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LoanApplication findOrCreate($search, callable $callback = null)
 */
class LoanApplicationsTable extends Table
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

        $this->table('loan_applications');
        $this->displayField('id');
        $this->primaryKey('id');
		 $this->belongsTo('Employees');
		$this->belongsTo('EmployeeSalaries');
		$this->belongsTo('Nppayments');
		$this->belongsTo('LedgerAccounts');
		$this->belongsTo('empData', [
			'className' => 'Employees',
            'foreignKey' => 'parent_employee_id',
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

      /*  $validator
            ->requirePresence('employee_name', 'create')
            ->notEmpty('employee_name');

         $validator
            ->requirePresence('reason_for _loan', 'create')
            ->notEmpty('reason_for _loan');

        $validator
            ->decimal('salary_pm')
            ->requirePresence('salary_pm', 'create')
            ->notEmpty('salary_pm');

        $validator
            ->requirePresence('amount _of_loan', 'create')
            ->notEmpty('amount _of_loan');

        $validator
            ->requirePresence('amount _of_loan_in_word', 'create')
            ->notEmpty('amount _of_loan_in_word');

        $validator
            ->date('starting_date_of_loan')
            ->requirePresence('starting_date_of_loan', 'create')
            ->notEmpty('starting_date_of_loan');

        $validator
            ->date('ending_date_of_loan')
            ->requirePresence('ending_date_of_loan', 'create')
            ->notEmpty('ending_date_of_loan');

        $validator
            ->requirePresence('remark', 'create')
            ->notEmpty('remark');

        $validator
            ->date('create_date')
            ->requirePresence('create_date', 'create')
            ->notEmpty('create_date'); */

        return $validator;
    }
	public function beforeMarshal(Event $event, ArrayObject $data)
    {
      //  @$data['starting_date_of_loan'] = trim(date('Y-m-d',strtotime(@$data['starting_date_of_loan'])));
      //  @$data['ending_date_of_loan'] = trim(date('Y-m-d',strtotime(@$data['ending_date_of_loan'])));
        @$data['create_date']      = trim(date('Y-m-d'));
    }
}
