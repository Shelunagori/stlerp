<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * DispatchDocuments Controller
 *
 * @property \App\Model\Table\DispatchDocumentsTable $DispatchDocuments
 */
class DispatchDocumentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$dispatchDocument = $this->DispatchDocuments->newEntity();
        if ($this->request->is('post')) {
            $dispatchDocument = $this->DispatchDocuments->patchEntity($dispatchDocument, $this->request->data);
            if ($this->DispatchDocuments->save($dispatchDocument)) {
                $this->Flash->success(__('The dispatch document has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The dispatch document could not be saved. Please, try again.'));
            }
        }
		
		$this->set(compact('dispatchDocument'));
        $this->set('_serialize', ['dispatchDocument']);
		
        $dispatchDocuments = $this->paginate($this->DispatchDocuments);

        $this->set(compact('dispatchDocuments'));
        $this->set('_serialize', ['dispatchDocuments']);
    }

    /**
     * View method
     *
     * @param string|null $id Dispatch Document id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dispatchDocument = $this->DispatchDocuments->get($id, [
            'contain' => []
        ]);

        $this->set('dispatchDocument', $dispatchDocument);
        $this->set('_serialize', ['dispatchDocument']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $dispatchDocument = $this->DispatchDocuments->newEntity();
        if ($this->request->is('post')) {
            $dispatchDocument = $this->DispatchDocuments->patchEntity($dispatchDocument, $this->request->data);
            if ($this->DispatchDocuments->save($dispatchDocument)) {
                $this->Flash->success(__('The dispatch document has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The dispatch document could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('dispatchDocument'));
        $this->set('_serialize', ['dispatchDocument']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Dispatch Document id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
        $dispatchDocument = $this->DispatchDocuments->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dispatchDocument = $this->DispatchDocuments->patchEntity($dispatchDocument, $this->request->data);
            if ($this->DispatchDocuments->save($dispatchDocument)) {
                $this->Flash->success(__('The dispatch document has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The dispatch document could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('dispatchDocument'));
        $this->set('_serialize', ['dispatchDocument']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Dispatch Document id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dispatchDocument = $this->DispatchDocuments->get($id);
        if ($this->DispatchDocuments->delete($dispatchDocument)) {
            $this->Flash->success(__('The dispatch document has been deleted.'));
        } else {
            $this->Flash->error(__('The dispatch document could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
