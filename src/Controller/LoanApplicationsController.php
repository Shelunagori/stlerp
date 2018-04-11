<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LoanApplications Controller
 *
 * @property \App\Model\Table\LoanApplicationsTable $LoanApplications
 */
class LoanApplicationsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
		$this->viewBuilder()->layout('index_layout');
        $loanApplications = $this->paginate($this->LoanApplications->find());

        $this->set(compact('loanApplications'));
        $this->set('_serialize', ['loanApplications']);
    }

    /**
     * View method
     *
     * @param string|null $id Loan Application id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $loanApplication = $this->LoanApplications->get($id, [
            'contain' => []
        ]);

        $this->set('loanApplication', $loanApplication);
        $this->set('_serialize', ['loanApplication']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$empData=$this->LoanApplications->Employees->get($s_employee_id,['contain'=>['Designations']]);
		//pr($empData); exit;
        $loanApplication = $this->LoanApplications->newEntity();
        if ($this->request->is('post')) {
            $loanApplication = $this->LoanApplications->patchEntity($loanApplication, $this->request->data);
			if ($this->LoanApplications->save($loanApplication)) {
                $this->Flash->success(__('The loan application has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The loan application could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('loanApplication','empData'));
        $this->set('_serialize', ['loanApplication']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Loan Application id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $loanApplication = $this->LoanApplications->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $loanApplication = $this->LoanApplications->patchEntity($loanApplication, $this->request->data);
            if ($this->LoanApplications->save($loanApplication)) {
                $this->Flash->success(__('The loan application has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The loan application could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('loanApplication'));
        $this->set('_serialize', ['loanApplication']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Loan Application id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $loanApplication = $this->LoanApplications->get($id);
        if ($this->LoanApplications->delete($loanApplication)) {
            $this->Flash->success(__('The loan application has been deleted.'));
        } else {
            $this->Flash->error(__('The loan application could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
