<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GrossProfitReports Model
 *
 * @property \Cake\ORM\Association\BelongsTo $FinancialYears
 * @property \Cake\ORM\Association\BelongsTo $Invoices
 * @property \Cake\ORM\Association\BelongsTo $InvoiceRows
 *
 * @method \App\Model\Entity\GrossProfitReport get($primaryKey, $options = [])
 * @method \App\Model\Entity\GrossProfitReport newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GrossProfitReport[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GrossProfitReport|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GrossProfitReport patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GrossProfitReport[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GrossProfitReport findOrCreate($search, callable $callback = null)
 */
class GrossProfitReportsTable extends Table
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

        $this->table('gross_profit_reports');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('FinancialYears', [
            'foreignKey' => 'financial_year_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Invoices');
        $this->belongsTo('InvoiceRows');
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
            ->decimal('taxable_value')
            ->requirePresence('taxable_value', 'create')
            ->notEmpty('taxable_value');

        $validator
            ->decimal('inventory_ledger_cost')
            ->requirePresence('inventory_ledger_cost', 'create')
            ->notEmpty('inventory_ledger_cost');

        $validator
            ->decimal('sales_price')
            ->requirePresence('sales_price', 'create')
            ->notEmpty('sales_price');

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
       // $rules->add($rules->existsIn(['invoice_id'], 'Invoices'));
        $rules->add($rules->existsIn(['invoice_row_id'], 'InvoiceRows'));

        return $rules;
    }
}
