<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NewSerialNumbers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Items
 * @property \Cake\ORM\Association\BelongsTo $Grns
 * @property \Cake\ORM\Association\BelongsTo $Invoices
 * @property \Cake\ORM\Association\BelongsTo $IvInvoices
 * @property \Cake\ORM\Association\BelongsTo $QItems
 * @property \Cake\ORM\Association\BelongsTo $InInventoryVouchers
 * @property \Cake\ORM\Association\BelongsTo $MasterItems
 * @property \Cake\ORM\Association\BelongsTo $Companies
 * @property \Cake\ORM\Association\BelongsTo $SaleReturns
 * @property \Cake\ORM\Association\BelongsTo $PurchaseReturns
 * @property \Cake\ORM\Association\BelongsTo $InventoryTransferVouchers
 *
 * @method \App\Model\Entity\NewSerialNumber get($primaryKey, $options = [])
 * @method \App\Model\Entity\NewSerialNumber newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\NewSerialNumber[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NewSerialNumber|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NewSerialNumber patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\NewSerialNumber[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\NewSerialNumber findOrCreate($search, callable $callback = null)
 */
class NewSerialNumbersTable extends Table
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

        $this->table('new_serial_numbers');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Items', [
            'foreignKey' => 'item_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Grns', [
            'foreignKey' => 'grn_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Invoices', [
            'foreignKey' => 'invoice_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('IvInvoices', [
            'foreignKey' => 'iv_invoice_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('QItems', [
            'foreignKey' => 'q_item_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('InInventoryVouchers', [
            'foreignKey' => 'in_inventory_voucher_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('MasterItems', [
            'foreignKey' => 'master_item_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
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
        $this->belongsTo('InventoryTransferVouchers', [
            'foreignKey' => 'inventory_transfer_voucher_id',
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
            ->requirePresence('serial_no', 'create')
            ->notEmpty('serial_no');

        $validator
            ->requirePresence('status', 'create')
            ->notEmpty('status');

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
        $rules->add($rules->existsIn(['item_id'], 'Items'));
        $rules->add($rules->existsIn(['grn_id'], 'Grns'));
        $rules->add($rules->existsIn(['invoice_id'], 'Invoices'));
        $rules->add($rules->existsIn(['iv_invoice_id'], 'IvInvoices'));
        $rules->add($rules->existsIn(['q_item_id'], 'QItems'));
        $rules->add($rules->existsIn(['in_inventory_voucher_id'], 'InInventoryVouchers'));
        $rules->add($rules->existsIn(['master_item_id'], 'MasterItems'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['sale_return_id'], 'SaleReturns'));
        $rules->add($rules->existsIn(['purchase_return_id'], 'PurchaseReturns'));
        $rules->add($rules->existsIn(['inventory_transfer_voucher_id'], 'InventoryTransferVouchers'));

        return $rules;
    }
}
