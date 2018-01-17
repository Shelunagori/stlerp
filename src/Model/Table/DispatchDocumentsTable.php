<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DispatchDocuments Model
 *
 * @method \App\Model\Entity\DispatchDocument get($primaryKey, $options = [])
 * @method \App\Model\Entity\DispatchDocument newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DispatchDocument[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DispatchDocument|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DispatchDocument patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DispatchDocument[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DispatchDocument findOrCreate($search, callable $callback = null)
 */
class DispatchDocumentsTable extends Table
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

        $this->table('dispatch_documents');
        $this->displayField('id');
        $this->primaryKey('id');
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
            ->requirePresence('text_line', 'create')
            ->notEmpty('text_line');

        return $validator;
    }
}
