<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
/**
 * LoanApplications Controller
 *
 * @property \App\Model\Table\LoanApplicationsTable $LoanApplications
 */
class LoanApplicationsController extends AppController
{

	public function beforeFilter(Event $event) {
		 $this->eventManager()->off($this->Csrf);
	}
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
		$s_employee_id=$this->viewVars['s_employee_id'];
		$empData=$this->LoanApplications->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
		
		if($empData->department->name=='HR & Administration' || $empData->designation->name=='Director'){
			$loanApplications = $this->paginate($this->LoanApplications->find()->contain(['Employees'])->where(['company_id'=>$st_company_id]));
		}else{
			$loanApplications = $this->paginate($this->LoanApplications->find()->contain(['Employees'])->where(['employee_id'=>$s_employee_id,'company_id'=>$st_company_id]));
		}

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

	public function getSalary($id = null){
		$cd  = date('d-m-Y');
		$From = date('t-m-Y', strtotime($cd));
		$From=date('Y-m-d',strtotime($From));
		$EmployeeSalary = $this->LoanApplications->EmployeeSalaries->find()->where(['employee_id'=>$id,'effective_date_from <='=>$From])->contain(['EmployeeSalaryRows'=>['EmployeeSalaryDivisions']])->order(['id'=>'DESC'])->first(); 
		$empSallary=0;
		foreach(@$EmployeeSalary->employee_salary_rows as $data){
				if($data->employee_salary_division->salary_type=='addition'){
					$empSallary+=$data->amount;
				}
		}
		//$EmployeeLoan = $this->LoanApplications->find()->where(['employee_id'=>$id])->contain(['LoanInstallments']);
		$EmployeeLoan = $this->LoanApplications->find()->where(['employee_id'=>$id])->contain(['LoanInstallments' => function ($q) {
					$q->select(['LoanInstallments.loan_application_id','LoanInstallments.id','total_loan_amt' => $q->func()->sum('LoanInstallments.amount')]);
					return $q;
				}]);
				//pr($EmployeeLoan->toArray()); exit;
		$loan_amt=0;
		foreach($EmployeeLoan as $data){   
			if(@$data->loan_installments[0]->total_loan_amt){ 
				if($data->amount_of_loan >= @$data->loan_installments[0]->total_loan_amt){
					$loan_amt=$data->instalment_amount;
				}
			}
		}
		echo $empSallary-$loan_amt; exit;
		 // pr($empSallary); exit;
	}
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
            'contain' => ['Employees']
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
		$empData=$this->LoanApplications->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
		//pr($empData->department); exit;
        $loanApplication = $this->LoanApplications->newEntity();
        if ($this->request->is('post')) {
            $loanApplication = $this->LoanApplications->patchEntity($loanApplication, $this->request->data);
			$loanApplication->company_id=$st_company_id;
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
		if(sizeof(@$EmployeeSalary->employee_salary_rows)>0){
			foreach(@$EmployeeSalary->employee_salary_rows as $data){
				if($data->employee_salary_division->salary_type=='addition'){
					$empSallary+=$data->amount;
				}
			}
		}
		
		$Employees=$this->LoanApplications->Employees->find('list')->matching(
					'EmployeeCompanies', function ($q)  {
						return $q->where(['EmployeeCompanies.freeze' =>0]);
					}
				);  ;
        $this->set(compact('loanApplication','empData','empSallary','Employees'));
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

		$s_employee_id=$this->viewVars['s_employee_id'];
		$empData=$this->LoanApplications->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $loanApplication = $this->LoanApplications->patchEntity($loanApplication, $this->request->data);
			$loanApplication->company_id=$st_company_id;
            if ($this->LoanApplications->save($loanApplication)) {
                $this->Flash->success(__('The loan application has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The loan application could not be saved. Please, try again.'));
            }
        }
		$From=date('Y-m-d');
		$EmployeeSalary = $this->LoanApplications->EmployeeSalaries->find()->where(['employee_id'=>$loanApplication->employee_id,'effective_date_from <='=>$From])->contain(['EmployeeSalaryRows'=>['EmployeeSalaryDivisions']])->order(['id'=>'DESC'])->first();  
		$empSallary=0;
		
		if(sizeof($EmployeeSalary->employee_salary_rows)>0){
			foreach(@$EmployeeSalary->employee_salary_rows as $data){
				if($data->employee_salary_division->salary_type=='addition'){
					$empSallary+=$data->amount;
				}
			}
		}
		
		$Employees=$this->LoanApplications->Employees->find('list')->matching(
					'EmployeeCompanies', function ($q)  {
						return $q->where(['EmployeeCompanies.freeze' =>0]);
					}
				);  
        $this->set(compact('loanApplication','empData','empSallary','Employees'));
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
	
	public function approveLoan($loanId){
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_year_id = $session->read('st_year_id');
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$st_year_id = $session->read('st_year_id');
		
		$financial_year = $this->LoanApplications->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$yearFrom = date("Y",strtotime($financial_year->date_from));
		
		$LoanApplications = $this->LoanApplications->get($loanId, [
            'contain' => ['Employees']
        ]); 
		
		$lastLoanApplication=$this->LoanApplications->find()->where(['employee_id'=>$LoanApplications->employee_id])->order(['id'=>'DESC'])->contain(['LoanInstallments'])->first();
		
		if ($this->request->is(['patch', 'post', 'put'])) {
            $trans_date=date('Y-m-d',strtotime($this->request->data['trans_date']));
            //$starting_date_of_loan=date('Y-m-d',strtotime($this->request->data['starting_date_of_loan']));
            //$ending_date_of_loan=date('Y-m-d',strtotime($this->request->data['ending_date_of_loan']));
            $comment=$this->request->data['comment'];
            $instalment_amount=$this->request->data['instalment_amount'];
            $no_of_instalment=$this->request->data['no_of_instalment'];
            $approve_amount_of_loan=$this->request->data['approve_amount_of_loan'];
            $bank_id=$this->request->data['bank_id'];
            $installment_from=$this->request->data['installment_from'];
            $installment_from=explode('-',$installment_from);
			$approve_date=date('Y-m-d');
			//$starting_date_of_loan=date('Y-m-d',strtotime($starting_date_of_loan));
			//$ending_date_of_loan=date('Y-m-d',strtotime($ending_date_of_loan));

			$query = $this->LoanApplications->query();
			$query->update()
				->set(['status' =>'approved','approve_date'=>$approve_date,'installment_start_month'=>$installment_from[0],'installment_start_year'=>$installment_from[1],'comment'=>$comment,'instalment_amount'=>$instalment_amount,'no_of_instalment'=>$no_of_instalment,'approve_amount_of_loan'=>$approve_amount_of_loan])
				->where(['id' => $loanId])
				->execute();
				
			
			$Nppayment=$this->LoanApplications->Nppayments->newEntity();
			$Nppayment->company_id=$st_company_id;
			//Voucher Number Increment
			$last_voucher_no=$this->LoanApplications->Nppayments->find()->select(['voucher_no'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['voucher_no' => 'DESC'])->first();
			if($last_voucher_no){
				$Nppayment->voucher_no=$last_voucher_no->voucher_no+1;
			}else{
				$Nppayment->voucher_no=1;
			}
			$Nppayment->created_on=date("Y-m-d");
			$Nppayment->financial_year_id=$st_year_id;
			$Nppayment->created_by=$s_employee_id;
			$Nppayment->bank_cash_id=$bank_id;
			$Nppayment->payment_mode='NEFT/RTGS';
			$Nppayment->cheque_no='';
			$Nppayment->transaction_date=$trans_date;
			$Nppayment->loan_amount = 'yes';
			$this->LoanApplications->Nppayments->save($Nppayment);
			
			$LedgerAccount=$this->LoanApplications->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Employees','source_id'=>$LoanApplications->employee_id])->first();
			
			$NppaymentRow=$this->LoanApplications->Nppayments->NppaymentRows->newEntity();
			$NppaymentRow->nppayment_id=$Nppayment->id;
			$NppaymentRow->received_from_id=$LedgerAccount->id;
			$NppaymentRow->amount=$approve_amount_of_loan;
			$NppaymentRow->cr_dr='Dr';
			$NppaymentRow->narration='Loan approved';
			$this->LoanApplications->Nppayments->NppaymentRows->save($NppaymentRow);
			
			$ledger = $this->LoanApplications->Nppayments->Ledgers->newEntity();
			$ledger->company_id=$st_company_id;
			$ledger->ledger_account_id = $LedgerAccount->id;
			$ledger->credit = 0;
			$ledger->debit = $approve_amount_of_loan;
			$ledger->voucher_id = $Nppayment->id;
			$ledger->voucher_source = 'Non Print Payment Voucher';
			$ledger->transaction_date = $trans_date;
			$ledger->loan_amount = 'yes';
			$this->LoanApplications->Nppayments->Ledgers->save($ledger);
			
			
				
				
			$ledger = $this->LoanApplications->Nppayments->Ledgers->newEntity();
			$ledger->company_id=$st_company_id;
			$ledger->ledger_account_id = $bank_id;
			$ledger->credit = $approve_amount_of_loan;
			$ledger->debit = 0;
			$ledger->voucher_id = $Nppayment->id;
			$ledger->voucher_source = 'Non Print Payment Voucher';
			$ledger->transaction_date = $trans_date;
			$ledger->loan_amount = 'yes';
			$this->LoanApplications->Nppayments->Ledgers->save($ledger);
					
					
			return $this->redirect(['controller'=>'Logins','action' => 'dashbord']);
		
        }
		
		$vr=$this->LoanApplications->Nppayments->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Non Print Payment Voucher','sub_entity'=>'Cash/Bank'])->first();
		$vouchersReferences = $this->LoanApplications->Nppayments->VouchersReferences->get($vr->id, [
			'contain' => ['VoucherLedgerAccounts']
		]);
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		$bankCashes = $this->LoanApplications->Nppayments->BankCashes->find('list',
			['keyField' => function ($row) {
				return $row['id'];
			},
			'valueField' => function ($row) {
				if(!empty($row['alias'])){
					return  $row['name'] . ' (' . $row['alias'] . ')';
				}else{
					return $row['name'];
				}
				
			}])->where(['BankCashes.id IN' => $where]);
		$this->set(compact('LoanApplications','id', 'bankCashes','lastLoanApplication', 'yearFrom'));
	}
}
