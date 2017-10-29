<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * IvRows Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Ivs
 * @property \Cake\ORM\Association\BelongsTo $InvoiceRows
 * @property \Cake\ORM\Association\BelongsTo $Items
 * @property \Cake\ORM\Association\HasMany $IvRowItems
 * @property \Cake\ORM\Association\HasMany $SerialNumbers
 *
 * @method \App\Model\Entity\IvRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\IvRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\IvRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\IvRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IvRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\IvRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\IvRow findOrCreate($search, callable $callback = null)
 */
class IvRowsTable extends Table
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

        $this->table('iv_rows');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Ivs', [
            'foreignKey' => 'iv_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('InvoiceRows', [
            'foreignKey' => 'invoice_row_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Items', [
            'foreignKey' => 'item_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('IvRowItems', [
            'foreignKey' => 'iv_row_id'
        ]);
        $this->hasMany('SerialNumbers', [
            'foreignKey' => 'iv_row_id'
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
            ->decimal('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmpty('quantity');

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
        $rules->add($rules->existsIn(['iv_id'], 'Ivs'));
        $rules->add($rules->existsIn(['invoice_row_id'], 'InvoiceRows'));
        $rules->add($rules->existsIn(['item_id'], 'Items'));

        return $rules;
    }
}
