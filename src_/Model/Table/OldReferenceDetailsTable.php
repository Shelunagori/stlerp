<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OldReferenceDetails Model
 *
 * @property \Cake\ORM\Association\BelongsTo $LedgerAccounts
 * @property \Cake\ORM\Association\BelongsTo $Receipts
 * @property \Cake\ORM\Association\BelongsTo $Payments
 * @property \Cake\ORM\Association\BelongsTo $Invoices
 * @property \Cake\ORM\Association\BelongsTo $InvoiceBookings
 * @property \Cake\ORM\Association\BelongsTo $CreditNotes
 * @property \Cake\ORM\Association\BelongsTo $JournalVouchers
 * @property \Cake\ORM\Association\BelongsTo $SaleReturns
 * @property \Cake\ORM\Association\BelongsTo $PurchaseReturns
 * @property \Cake\ORM\Association\BelongsTo $PettyCashVouchers
 * @property \Cake\ORM\Association\BelongsTo $Nppayments
 * @property \Cake\ORM\Association\BelongsTo $ContraVouchers
 *
 * @method \App\Model\Entity\OldReferenceDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\OldReferenceDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OldReferenceDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OldReferenceDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OldReferenceDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OldReferenceDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OldReferenceDetail findOrCreate($search, callable $callback = null)
 */
class OldReferenceDetailsTable extends Table
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

        $this->table('old_reference_details');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('LedgerAccounts', [
            'foreignKey' => 'ledger_account_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Receipts', [
            'foreignKey' => 'receipt_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Payments', [
            'foreignKey' => 'payment_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Invoices', [
            'foreignKey' => 'invoice_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('InvoiceBookings', [
            'foreignKey' => 'invoice_booking_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('CreditNotes', [
            'foreignKey' => 'credit_note_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('JournalVouchers', [
            'foreignKey' => 'journal_voucher_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SaleReturns', [
            'foreignKey' => 'sale_return_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('PurchaseReturns', [
            'foreignKey' => 'purchase_return_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('PettyCashVouchers', [
            'foreignKey' => 'petty_cash_voucher_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Nppayments', [
            'foreignKey' => 'nppayment_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ContraVouchers', [
            'foreignKey' => 'contra_voucher_id',
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
            ->requirePresence('reference_no', 'create')
            ->notEmpty('reference_no');

        $validator
            ->decimal('credit')
            ->requirePresence('credit', 'create')
            ->notEmpty('credit');

        $validator
            ->decimal('debit')
            ->requirePresence('debit', 'create')
            ->notEmpty('debit');

        $validator
            ->requirePresence('reference_type', 'create')
            ->notEmpty('reference_type');

        $validator
            ->integer('auto_inc')
            ->requirePresence('auto_inc', 'create')
            ->notEmpty('auto_inc');

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
        $rules->add($rules->existsIn(['ledger_account_id'], 'LedgerAccounts'));
        $rules->add($rules->existsIn(['receipt_id'], 'Receipts'));
        $rules->add($rules->existsIn(['payment_id'], 'Payments'));
        $rules->add($rules->existsIn(['invoice_id'], 'Invoices'));
        $rules->add($rules->existsIn(['invoice_booking_id'], 'InvoiceBookings'));
        $rules->add($rules->existsIn(['credit_note_id'], 'CreditNotes'));
        $rules->add($rules->existsIn(['journal_voucher_id'], 'JournalVouchers'));
        $rules->add($rules->existsIn(['sale_return_id'], 'SaleReturns'));
        $rules->add($rules->existsIn(['purchase_return_id'], 'PurchaseReturns'));
        $rules->add($rules->existsIn(['petty_cash_voucher_id'], 'PettyCashVouchers'));
        $rules->add($rules->existsIn(['nppayment_id'], 'Nppayments'));
        $rules->add($rules->existsIn(['contra_voucher_id'], 'ContraVouchers'));

        return $rules;
    }
}
