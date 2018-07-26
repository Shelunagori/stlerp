<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LoanInstallments Controller
 *
 * @property \App\Model\Table\LoanInstallmentsTable $LoanInstallments
 */
class LoanInstallmentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['LoanApplications']
        ];
        $loanInstallments = $this->paginate($this->LoanInstallments);

        $this->set(compact('loanInstallments'));
        $this->set('_serialize', ['loanInstallments']);
    }

    /**
     * View method
     *
     * @param string|null $id Loan Installment id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $loanInstallment = $this->LoanInstallments->get($id, [
            'contain' => ['LoanApplications']
        ]);

        $this->set('loanInstallment', $loanInstallment);
        $this->set('_serialize', ['loanInstallment']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $loanInstallment = $this->LoanInstallments->newEntity();
        if ($this->request->is('post')) {
            $loanInstallment = $this->LoanInstallments->patchEntity($loanInstallment, $this->request->data);
            if ($this->LoanInstallments->save($loanInstallment)) {
                $this->Flash->success(__('The loan installment has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The loan installment could not be saved. Please, try again.'));
            }
        }
        $loanApplications = $this->LoanInstallments->LoanApplications->find('list', ['limit' => 200]);
        $this->set(compact('loanInstallment', 'loanApplications'));
        $this->set('_serialize', ['loanInstallment']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Loan Installment id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $loanInstallment = $this->LoanInstallments->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $loanInstallment = $this->LoanInstallments->patchEntity($loanInstallment, $this->request->data);
            if ($this->LoanInstallments->save($loanInstallment)) {
                $this->Flash->success(__('The loan installment has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The loan installment could not be saved. Please, try again.'));
            }
        }
        $loanApplications = $this->LoanInstallments->LoanApplications->find('list', ['limit' => 200]);
        $this->set(compact('loanInstallment', 'loanApplications'));
        $this->set('_serialize', ['loanInstallment']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Loan Installment id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $loanInstallment = $this->LoanInstallments->get($id);
        if ($this->LoanInstallments->delete($loanInstallment)) {
            $this->Flash->success(__('The loan installment has been deleted.'));
        } else {
            $this->Flash->error(__('The loan installment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
