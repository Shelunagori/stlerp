<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * IvRowItems Controller
 *
 * @property \App\Model\Table\IvRowItemsTable $IvRowItems
 */
class IvRowItemsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['IvRows', 'Items']
        ];
        $ivRowItems = $this->paginate($this->IvRowItems);

        $this->set(compact('ivRowItems'));
        $this->set('_serialize', ['ivRowItems']);
    }

    /**
     * View method
     *
     * @param string|null $id Iv Row Item id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ivRowItem = $this->IvRowItems->get($id, [
            'contain' => ['IvRows', 'Items']
        ]);

        $this->set('ivRowItem', $ivRowItem);
        $this->set('_serialize', ['ivRowItem']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ivRowItem = $this->IvRowItems->newEntity();
        if ($this->request->is('post')) {
            $ivRowItem = $this->IvRowItems->patchEntity($ivRowItem, $this->request->data);
            if ($this->IvRowItems->save($ivRowItem)) {
                $this->Flash->success(__('The iv row item has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The iv row item could not be saved. Please, try again.'));
            }
        }
        $ivRows = $this->IvRowItems->IvRows->find('list', ['limit' => 200]);
        $items = $this->IvRowItems->Items->find('list', ['limit' => 200]);
        $this->set(compact('ivRowItem', 'ivRows', 'items'));
        $this->set('_serialize', ['ivRowItem']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Iv Row Item id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ivRowItem = $this->IvRowItems->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ivRowItem = $this->IvRowItems->patchEntity($ivRowItem, $this->request->data);
            if ($this->IvRowItems->save($ivRowItem)) {
                $this->Flash->success(__('The iv row item has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The iv row item could not be saved. Please, try again.'));
            }
        }
        $ivRows = $this->IvRowItems->IvRows->find('list', ['limit' => 200]);
        $items = $this->IvRowItems->Items->find('list', ['limit' => 200]);
        $this->set(compact('ivRowItem', 'ivRows', 'items'));
        $this->set('_serialize', ['ivRowItem']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Iv Row Item id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ivRowItem = $this->IvRowItems->get($id);
        if ($this->IvRowItems->delete($ivRowItem)) {
            $this->Flash->success(__('The iv row item has been deleted.'));
        } else {
            $this->Flash->error(__('The iv row item could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
