<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ChallanReturnVoucherRows Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ChallanReturnVouchers
 * @property \Cake\ORM\Association\BelongsTo $Items
 * @property \Cake\ORM\Association\BelongsTo $ChallanRows
 *
 * @method \App\Model\Entity\ChallanReturnVoucherRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\ChallanReturnVoucherRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ChallanReturnVoucherRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ChallanReturnVoucherRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChallanReturnVoucherRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ChallanReturnVoucherRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ChallanReturnVoucherRow findOrCreate($search, callable $callback = null)
 */
class ChallanReturnVoucherRowsTable extends Table
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

        $this->table('challan_return_voucher_rows');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('ChallanReturnVouchers', [
            'foreignKey' => 'challan_return_voucher_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Items', [
            'foreignKey' => 'item_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ChallanRows', [
            'foreignKey' => 'challan_row_id',
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
        $rules->add($rules->existsIn(['challan_return_voucher_id'], 'ChallanReturnVouchers'));
        $rules->add($rules->existsIn(['item_id'], 'Items'));
        $rules->add($rules->existsIn(['challan_row_id'], 'ChallanRows'));

        return $rules;
    }
}
