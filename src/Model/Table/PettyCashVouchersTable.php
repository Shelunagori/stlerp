<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PettyCashVouchers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $BankCashes
 * @property \Cake\ORM\Association\BelongsTo $Companies
 * @property \Cake\ORM\Association\HasMany $PettyCashVoucherRows
 *
 * @method \App\Model\Entity\PettyCashVoucher get($primaryKey, $options = [])
 * @method \App\Model\Entity\PettyCashVoucher newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PettyCashVoucher[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PettyCashVoucher|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PettyCashVoucher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PettyCashVoucher[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PettyCashVoucher findOrCreate($search, callable $callback = null)
 */
class PettyCashVouchersTable extends Table
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

        $this->table('petty_cash_vouchers');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('VouchersReferences');
        $this->belongsTo('FinancialYears');
        $this->belongsTo('FinancialMonths');
        $this->belongsTo('ReferenceBalances');
        $this->belongsTo('ReferenceDetails');
        $this->belongsTo('Ledgers');


        $this->belongsTo('BankCashes', [
            'className' => 'LedgerAccounts',
            'foreignKey' => 'bank_cash_id',
            'propertyName' => 'BankCash',
        ]);
        $this->belongsTo('ReceivedFroms', [
            'className' => 'LedgerAccounts',
            'foreignKey' => 'received_from_id',
            'propertyName' => 'ReceivedFrom',
        ]);
        
        $this->belongsTo('Creator', [
            'className' => 'Employees',
            'foreignKey' => 'created_by',
            'propertyName' => 'creator',
        ]);

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('PettyCashVoucherRows', [
            'foreignKey' => 'petty_cash_voucher_id',
			'saveStrategy' => 'replace'
        ]);
		$this->hasMany('Grns', [
            'foreignKey' => 'payment_id',
            'joinType' => 'INNER'
		 ]);
		 $this->hasMany('Invoices', [
            'foreignKey' => 'payment_id',
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
            ->integer('voucher_no')
            ->requirePresence('voucher_no', 'create')
            ->notEmpty('voucher_no');

        $validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmpty('created_by');

        $validator
            ->date('created_on')
            ->requirePresence('created_on', 'create')
            ->notEmpty('created_on');

        $validator
            ->requirePresence('payment_mode', 'create')
            ->notEmpty('payment_mode');

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
        $rules->add($rules->existsIn(['bank_cash_id'], 'BankCashes'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }
}
