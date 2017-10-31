<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Ivs Controller
 *
 * @property \App\Model\Table\IvsTable $Ivs
 */
class IvsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Invoices', 'Companies']
        ];
        $ivs = $this->paginate($this->Ivs);

        $this->set(compact('ivs'));
        $this->set('_serialize', ['ivs']);
    }

    /**
     * View method
     *
     * @param string|null $id Iv id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $iv = $this->Ivs->get($id, [
            'contain' => ['Invoices', 'Companies', 'IvRows']
        ]);

        $this->set('iv', $iv);
        $this->set('_serialize', ['iv']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($invoice_id=null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$Invoice=$this->Ivs->Invoices->get($invoice_id, [
			'contain' => ['InvoiceRows'=>['Items']]
		]);
		
			
        $iv = $this->Ivs->newEntity();
        if ($this->request->is('post')) {
            $iv = $this->Ivs->patchEntity($iv, $this->request->data, [
								'associated' => ['IvRows', 'IvRows.IvRowItems']
							]);
			
			$iv->voucher_no=1;
            //pr($iv); exit;
			if ($this->Ivs->save($iv)) {
                $this->Flash->success(__('The iv has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The iv could not be saved. Please, try again.'));
            }
        }
		
		$Items=$this->Ivs->IvRows->Items->find('list');
        $this->set(compact('iv', 'Invoice', 'Items'));
        $this->set('_serialize', ['iv']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Iv id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $iv = $this->Ivs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $iv = $this->Ivs->patchEntity($iv, $this->request->data);
            if ($this->Ivs->save($iv)) {
                $this->Flash->success(__('The iv has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The iv could not be saved. Please, try again.'));
            }
        }
        $invoices = $this->Ivs->Invoices->find('list', ['limit' => 200]);
        $companies = $this->Ivs->Companies->find('list', ['limit' => 200]);
        $this->set(compact('iv', 'invoices', 'companies'));
        $this->set('_serialize', ['iv']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Iv id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $iv = $this->Ivs->get($id);
        if ($this->Ivs->delete($iv)) {
            $this->Flash->success(__('The iv has been deleted.'));
        } else {
            $this->Flash->error(__('The iv could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
