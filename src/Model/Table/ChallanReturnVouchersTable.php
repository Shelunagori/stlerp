<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ChallanReturnVouchers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Companies
 * @property \Cake\ORM\Association\BelongsTo $Challans
 * @property \Cake\ORM\Association\HasMany $ChallanReturnVoucherRows
 *
 * @method \App\Model\Entity\ChallanReturnVoucher get($primaryKey, $options = [])
 * @method \App\Model\Entity\ChallanReturnVoucher newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ChallanReturnVoucher[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ChallanReturnVoucher|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChallanReturnVoucher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ChallanReturnVoucher[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ChallanReturnVoucher findOrCreate($search, callable $callback = null)
 */
class ChallanReturnVouchersTable extends Table
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

        $this->table('challan_return_vouchers');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Challans', [
            'foreignKey' => 'challan_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ChallanReturnVoucherRows', [
            'foreignKey' => 'challan_return_voucher_id'
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
            ->requirePresence('voucher_no', 'create')
            ->notEmpty('voucher_no');

        $validator
            ->date('created_on')
            ->requirePresence('created_on', 'create')
            ->notEmpty('created_on');

        $validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmpty('created_by');

        $validator
            ->date('transaction_date')
            ->requirePresence('transaction_date', 'create')
            ->notEmpty('transaction_date');

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
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['challan_id'], 'Challans'));

        return $rules;
    }
}
