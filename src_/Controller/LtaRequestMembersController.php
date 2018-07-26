<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LtaRequestMembers Controller
 *
 * @property \App\Model\Table\LtaRequestMembersTable $LtaRequestMembers
 */
class LtaRequestMembersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['LtaRequests']
        ];
        $ltaRequestMembers = $this->paginate($this->LtaRequestMembers);

        $this->set(compact('ltaRequestMembers'));
        $this->set('_serialize', ['ltaRequestMembers']);
    }

    /**
     * View method
     *
     * @param string|null $id Lta Request Member id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ltaRequestMember = $this->LtaRequestMembers->get($id, [
            'contain' => ['LtaRequests']
        ]);

        $this->set('ltaRequestMember', $ltaRequestMember);
        $this->set('_serialize', ['ltaRequestMember']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ltaRequestMember = $this->LtaRequestMembers->newEntity();
        if ($this->request->is('post')) {
            $ltaRequestMember = $this->LtaRequestMembers->patchEntity($ltaRequestMember, $this->request->data);
            if ($this->LtaRequestMembers->save($ltaRequestMember)) {
                $this->Flash->success(__('The lta request member has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The lta request member could not be saved. Please, try again.'));
            }
        }
        $ltaRequests = $this->LtaRequestMembers->LtaRequests->find('list', ['limit' => 200]);
        $this->set(compact('ltaRequestMember', 'ltaRequests'));
        $this->set('_serialize', ['ltaRequestMember']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Lta Request Member id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ltaRequestMember = $this->LtaRequestMembers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ltaRequestMember = $this->LtaRequestMembers->patchEntity($ltaRequestMember, $this->request->data);
            if ($this->LtaRequestMembers->save($ltaRequestMember)) {
                $this->Flash->success(__('The lta request member has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The lta request member could not be saved. Please, try again.'));
            }
        }
        $ltaRequests = $this->LtaRequestMembers->LtaRequests->find('list', ['limit' => 200]);
        $this->set(compact('ltaRequestMember', 'ltaRequests'));
        $this->set('_serialize', ['ltaRequestMember']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Lta Request Member id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ltaRequestMember = $this->LtaRequestMembers->get($id);
        if ($this->LtaRequestMembers->delete($ltaRequestMember)) {
            $this->Flash->success(__('The lta request member has been deleted.'));
        } else {
            $this->Flash->error(__('The lta request member could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
