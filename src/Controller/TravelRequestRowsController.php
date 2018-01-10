<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * TravelRequestRows Controller
 *
 * @property \App\Model\Table\TravelRequestRowsTable $TravelRequestRows
 */
class TravelRequestRowsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['TravelRequests']
        ];
        $travelRequestRows = $this->paginate($this->TravelRequestRows);

        $this->set(compact('travelRequestRows'));
        $this->set('_serialize', ['travelRequestRows']);
    }

    /**
     * View method
     *
     * @param string|null $id Travel Request Row id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $travelRequestRow = $this->TravelRequestRows->get($id, [
            'contain' => ['TravelRequests']
        ]);

        $this->set('travelRequestRow', $travelRequestRow);
        $this->set('_serialize', ['travelRequestRow']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $travelRequestRow = $this->TravelRequestRows->newEntity();
        if ($this->request->is('post')) {
            $travelRequestRow = $this->TravelRequestRows->patchEntity($travelRequestRow, $this->request->data);
            if ($this->TravelRequestRows->save($travelRequestRow)) {
                $this->Flash->success(__('The travel request row has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The travel request row could not be saved. Please, try again.'));
            }
        }
        $travelRequests = $this->TravelRequestRows->TravelRequests->find('list', ['limit' => 200]);
        $this->set(compact('travelRequestRow', 'travelRequests'));
        $this->set('_serialize', ['travelRequestRow']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Travel Request Row id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $travelRequestRow = $this->TravelRequestRows->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $travelRequestRow = $this->TravelRequestRows->patchEntity($travelRequestRow, $this->request->data);
            if ($this->TravelRequestRows->save($travelRequestRow)) {
                $this->Flash->success(__('The travel request row has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The travel request row could not be saved. Please, try again.'));
            }
        }
        $travelRequests = $this->TravelRequestRows->TravelRequests->find('list', ['limit' => 200]);
        $this->set(compact('travelRequestRow', 'travelRequests'));
        $this->set('_serialize', ['travelRequestRow']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Travel Request Row id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $travelRequestRow = $this->TravelRequestRows->get($id);
        if ($this->TravelRequestRows->delete($travelRequestRow)) {
            $this->Flash->success(__('The travel request row has been deleted.'));
        } else {
            $this->Flash->error(__('The travel request row could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
