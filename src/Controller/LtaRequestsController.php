<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LtaRequests Controller
 *
 * @property \App\Model\Table\LtaRequestsTable $LtaRequests
 */
class LtaRequestsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
		$this->viewBuilder()->layout('index_layout');
        $this->paginate = [
            'contain' => ['Employees']
        ];
        $ltaRequests = $this->paginate($this->LtaRequests);

        $this->set(compact('ltaRequests'));
        $this->set('_serialize', ['ltaRequests']);
    }

    /**
     * View method
     *
     * @param string|null $id Lta Request id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ltaRequest = $this->LtaRequests->get($id, [
            'contain' => ['Employees', 'LtaRequestMembers']
        ]);

        $this->set('ltaRequest', $ltaRequest);
        $this->set('_serialize', ['ltaRequest']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
        $ltaRequest = $this->LtaRequests->newEntity();
        if ($this->request->is('post')) {
            $ltaRequest = $this->LtaRequests->patchEntity($ltaRequest, $this->request->data);
            if ($this->LtaRequests->save($ltaRequest)) {
                $this->Flash->success(__('The lta request has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The lta request could not be saved. Please, try again.'));
            }
        }
        $employees = $this->LtaRequests->Employees->find('list', ['limit' => 200]);
        $this->set(compact('ltaRequest', 'employees'));
        $this->set('_serialize', ['ltaRequest']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Lta Request id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $ltaRequest = $this->LtaRequests->get($id, [
            'contain' => ['LtaRequestMembers']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ltaRequest = $this->LtaRequests->patchEntity($ltaRequest, $this->request->data);
            if ($this->LtaRequests->save($ltaRequest)) {
                $this->Flash->success(__('The lta request has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The lta request could not be saved. Please, try again.'));
            }
        }
        $employees = $this->LtaRequests->Employees->find('list', ['limit' => 200]);
        $this->set(compact('ltaRequest', 'employees'));
        $this->set('_serialize', ['ltaRequest']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Lta Request id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ltaRequest = $this->LtaRequests->get($id);
        if ($this->LtaRequests->delete($ltaRequest)) {
            $this->Flash->success(__('The lta request has been deleted.'));
        } else {
            $this->Flash->error(__('The lta request could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
