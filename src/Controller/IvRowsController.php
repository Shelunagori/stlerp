<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * IvRows Controller
 *
 * @property \App\Model\Table\IvRowsTable $IvRows
 */
class IvRowsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Ivs', 'InvoiceRows', 'Items']
        ];
        $ivRows = $this->paginate($this->IvRows);

        $this->set(compact('ivRows'));
        $this->set('_serialize', ['ivRows']);
    }

    /**
     * View method
     *
     * @param string|null $id Iv Row id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ivRow = $this->IvRows->get($id, [
            'contain' => ['Ivs', 'InvoiceRows', 'Items', 'IvRowItems', 'SerialNumbers']
        ]);

        $this->set('ivRow', $ivRow);
        $this->set('_serialize', ['ivRow']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ivRow = $this->IvRows->newEntity();
        if ($this->request->is('post')) {
            $ivRow = $this->IvRows->patchEntity($ivRow, $this->request->data);
            if ($this->IvRows->save($ivRow)) {
                $this->Flash->success(__('The iv row has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The iv row could not be saved. Please, try again.'));
            }
        }
        $ivs = $this->IvRows->Ivs->find('list', ['limit' => 200]);
        $invoiceRows = $this->IvRows->InvoiceRows->find('list', ['limit' => 200]);
        $items = $this->IvRows->Items->find('list', ['limit' => 200]);
        $this->set(compact('ivRow', 'ivs', 'invoiceRows', 'items'));
        $this->set('_serialize', ['ivRow']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Iv Row id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ivRow = $this->IvRows->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ivRow = $this->IvRows->patchEntity($ivRow, $this->request->data);
            if ($this->IvRows->save($ivRow)) {
                $this->Flash->success(__('The iv row has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The iv row could not be saved. Please, try again.'));
            }
        }
        $ivs = $this->IvRows->Ivs->find('list', ['limit' => 200]);
        $invoiceRows = $this->IvRows->InvoiceRows->find('list', ['limit' => 200]);
        $items = $this->IvRows->Items->find('list', ['limit' => 200]);
        $this->set(compact('ivRow', 'ivs', 'invoiceRows', 'items'));
        $this->set('_serialize', ['ivRow']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Iv Row id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ivRow = $this->IvRows->get($id);
        if ($this->IvRows->delete($ivRow)) {
            $this->Flash->success(__('The iv row has been deleted.'));
        } else {
            $this->Flash->error(__('The iv row could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
