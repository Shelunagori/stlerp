<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SendEmails Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Invoices
 *
 * @method \App\Model\Entity\SendEmail get($primaryKey, $options = [])
 * @method \App\Model\Entity\SendEmail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SendEmail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SendEmail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SendEmail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SendEmail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SendEmail findOrCreate($search, callable $callback = null)
 */
class SendEmailsTable extends Table
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

        $this->table('send_emails');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Invoices', [
            'foreignKey' => 'invoice_id',
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
            ->requirePresence('send_data', 'create')
            ->notEmpty('send_data');

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
        $rules->add($rules->existsIn(['invoice_id'], 'Invoices'));

        return $rules;
    }
}
