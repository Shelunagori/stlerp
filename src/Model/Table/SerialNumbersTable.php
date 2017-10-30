<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SerialNumbers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Items
 * @property \Cake\ORM\Association\BelongsTo $IvRows
 * @property \Cake\ORM\Association\BelongsTo $IvRowItems
 *
 * @method \App\Model\Entity\SerialNumber get($primaryKey, $options = [])
 * @method \App\Model\Entity\SerialNumber newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SerialNumber[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SerialNumber|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SerialNumber patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SerialNumber[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SerialNumber findOrCreate($search, callable $callback = null)
 */
class SerialNumbersTable extends Table
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

        $this->table('serial_numbers');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->belongsTo('Grns', [
            'foreignKey' => 'grn_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('GrnRows', [
            'foreignKey' => 'grn_row_id',
            'joinType' => 'INNER'
        ]);		
		
        $this->belongsTo('IvRows', [
            'foreignKey' => 'iv_row_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('IvRowItems', [
            'foreignKey' => 'iv_row_item_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('Items', [
            'foreignKey' => 'item_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('InvoiceRows', [
            'foreignKey' => 'invoice_row_id',
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
        $rules->add($rules->existsIn(['item_id'], 'Items'));
        $rules->add($rules->existsIn(['iv_row_id'], 'IvRows'));
        $rules->add($rules->existsIn(['iv_row_item_id'], 'IvRowItems'));

        return $rules;
    }
}
