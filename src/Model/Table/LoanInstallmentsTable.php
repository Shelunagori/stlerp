<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LoanInstallments Model
 *
 * @property \Cake\ORM\Association\BelongsTo $LoanApplications
 *
 * @method \App\Model\Entity\LoanInstallment get($primaryKey, $options = [])
 * @method \App\Model\Entity\LoanInstallment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LoanInstallment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LoanInstallment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LoanInstallment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LoanInstallment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LoanInstallment findOrCreate($search, callable $callback = null)
 */
class LoanInstallmentsTable extends Table
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

        $this->table('loan_installments');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('LoanApplications', [
            'foreignKey' => 'loan_application_id',
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
            ->integer('month')
            ->requirePresence('month', 'create')
            ->notEmpty('month');

        $validator
            ->integer('year')
            ->requirePresence('year', 'create')
            ->notEmpty('year');

        $validator
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmpty('amount');

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
        $rules->add($rules->existsIn(['loan_application_id'], 'LoanApplications'));

        return $rules;
    }
}
