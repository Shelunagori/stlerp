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
        $loanApplications = $this->paginate($this->LoanApplications->find()->contain(['Employees']));

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

	public function approve($id = null){
		$LoanApplications = $this->LoanApplications->get($id); //pr($LoanApplications); exit;
		 $this->set(compact('LoanApplications','id'));
		
	}

	 public function approved($id = null,$approve_amount_of_loan = null,$starting_date_of_loan = null,$ending_date_of_loan = null,$no_of_instalment = null,$instalment_amount = null,$comment = null)
    {	
		$approve_date=date('Y-m-d');
		$starting_date_of_loan=date('Y-m-d',strtotime($starting_date_of_loan));
		$ending_date_of_loan=date('Y-m-d',strtotime($ending_date_of_loan));
		
		$query = $this->LoanApplications->query();
			$query->update()
				->set(['status' =>'approved','approve_date'=>$approve_date,'starting_date_of_loan'=>$starting_date_of_loan,'ending_date_of_loan'=>$ending_date_of_loan,'comment'=>$comment,'instalment_amount'=>$instalment_amount,'no_of_instalment'=>$no_of_instalment,'approve_amount_of_loan'=>$approve_amount_of_loan])
				->where(['id' => $id])
				->execute();
		return $this->redirect(['controller'=>'Logins','action' => 'dashbord']);
    }
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
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
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
		$From=date('Y-m-d');
		$EmployeeSalary = $this->LoanApplications->EmployeeSalaries->find()->where(['employee_id'=>$s_employee_id,'effective_date_from <='=>$From])->contain(['EmployeeSalaryRows'=>['EmployeeSalaryDivisions']])->order(['id'=>'DESC'])->first();  
		$empSallary=0;
		foreach($EmployeeSalary->employee_salary_rows as $data){
				if($data->employee_salary_division->salary_type=='addition'){
					$empSallary+=$data->amount;
				}
		}
        $this->set(compact('loanApplication','empData','empSallary'));
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
