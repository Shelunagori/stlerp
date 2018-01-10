<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * TravelRequests Controller
 *
 * @property \App\Model\Table\TravelRequestsTable $TravelRequests
 */
class TravelRequestsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
		$this->viewBuilder()->layout('index_layout');
        $travelRequests = $this->paginate($this->TravelRequests);

        $this->set(compact('travelRequests'));
        $this->set('_serialize', ['travelRequests']);
    }

    /**
     * View method
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $travelRequest = $this->TravelRequests->get($id, [
            'contain' => ['TravelRequestRows']
        ]);

        $this->set('travelRequest', $travelRequest);
        $this->set('_serialize', ['travelRequest']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
        $travelRequest = $this->TravelRequests->newEntity();
        if ($this->request->is('post')) {
            $travelRequest = $this->TravelRequests->patchEntity($travelRequest, $this->request->data); 
            if ($this->TravelRequests->save($travelRequest)) {
				
                $this->Flash->success(__('The travel request has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The travel request could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('travelRequest'));
        $this->set('_serialize', ['travelRequest']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $travelRequest = $this->TravelRequests->get($id, [
            'contain' => ['TravelRequestRows']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $travelRequest = $this->TravelRequests->patchEntity($travelRequest, $this->request->data);
            if ($this->TravelRequests->save($travelRequest)) {
                $this->Flash->success(__('The travel request has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The travel request could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('travelRequest'));
        $this->set('_serialize', ['travelRequest']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $travelRequest = $this->TravelRequests->get($id);
        if ($this->TravelRequests->delete($travelRequest)) {
            $this->Flash->success(__('The travel request has been deleted.'));
        } else {
            $this->Flash->error(__('The travel request could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
