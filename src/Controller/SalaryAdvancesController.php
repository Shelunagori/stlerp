<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
/**
 * SalaryAdvances Controller
 *
 * @property \App\Model\Table\SalaryAdvancesTable $SalaryAdvances
 */
class SalaryAdvancesController extends AppController
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
		$empData=$this->SalaryAdvances->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
		
		if($empData->department->name=='HR & Administration' || $empData->designation->name=='Director'){
		$salaryAdvances = $this->paginate($this->SalaryAdvances->find()->contain(['Employees']));
		}else{
		$salaryAdvances = $this->paginate($this->SalaryAdvances->find()->contain(['Employees'])->where(['employee_id'=>$s_employee_id]));
		}
       // $salaryAdvances = $this->paginate($this->SalaryAdvances->find()->contain(['Employees']));

        $this->set(compact('salaryAdvances'));
        $this->set('_serialize', ['salaryAdvances']);
    }
	
	public function pending()
    {
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$empData=$this->SalaryAdvances->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
		
		if($empData->department->name=='HR & Administration' || $empData->designation->name=='Director'){
			$salaryAdvances = $this->SalaryAdvances->find()->contain(['Employees'])->where(['SalaryAdvances.status'=>'pending']);
		}else{
			$salaryAdvances = $this->SalaryAdvances->find()->contain(['Employees'])->where(['employee_id'=>$s_employee_id,'SalaryAdvances.status'=>'pending']);
		}
       // $salaryAdvances = $this->paginate($this->SalaryAdvances->find()->contain(['Employees']));

        $this->set(compact('salaryAdvances'));
        $this->set('_serialize', ['salaryAdvances']);
    }
	
	public function approve($id)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		
		 $salaryAdvance = $this->SalaryAdvances->get($id, [
            'contain' => ['Employees']
        ]);
		
		if ($this->request->is('post')) {
			$bank_id=$this->request->data()['bank_id'];
			$trans_date=date('Y-m-d',strtotime($this->request->data()['trans_date']));
			$salaryAdvance->status="approve";
			$this->SalaryAdvances->save($salaryAdvance);
			
			$nppayment = $this->SalaryAdvances->Nppayments->newEntity();
			$nppayment->financial_year_id=$st_year_id;
			$last_voucher_no=$this->SalaryAdvances->Nppayments->find()->select(['voucher_no'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['voucher_no' => 'DESC'])->first();
            if($last_voucher_no){
                $nppayment->voucher_no=$last_voucher_no->voucher_no+1;
            }else{
                $nppayment->voucher_no=1;
            }
			$nppayment->bank_cash_id=$bank_id;
			$nppayment->created_on=date("Y-m-d");
            $nppayment->created_by=$s_employee_id;
            $nppayment->payment_mode='NEFT/RTGS';
            $nppayment->company_id=$st_company_id;
            $nppayment->transaction_date=$trans_date;
            $nppayment->cheque_no='';
            $nppayment->advance_salary='yes';
			$this->SalaryAdvances->Nppayments->save($nppayment);
			
			$ledger_account=$this->SalaryAdvances->LedgerAccounts->find()->where(['source_model'=>'Employees','source_id'=>$salaryAdvance->employee_id,'company_id'=>$st_company_id])->first();
			
			$NppaymentRow = $this->SalaryAdvances->Nppayments->NppaymentRows->newEntity();
			$NppaymentRow->nppayment_id=$nppayment->id;
			$NppaymentRow->received_from_id=$ledger_account->id;
			$NppaymentRow->amount=$salaryAdvance->amount;
			$NppaymentRow->cr_dr='Dr';
			$NppaymentRow->narration='Advance Salary';
			$this->SalaryAdvances->Nppayments->NppaymentRows->save($NppaymentRow);
			
			$ledger = $this->SalaryAdvances->Nppayments->Ledgers->newEntity();
			$ledger->company_id=$st_company_id;
			$ledger->ledger_account_id = $bank_id;
			$ledger->credit = $salaryAdvance->amount;
			$ledger->debit = 0;
			$ledger->voucher_id = $nppayment->id;
			$ledger->voucher_source = 'Non Print Payment Voucher';
			$ledger->transaction_date = $trans_date;
			$this->SalaryAdvances->Nppayments->Ledgers->save($ledger);
			
			$ledger = $this->SalaryAdvances->Nppayments->Ledgers->newEntity();
			$ledger->company_id=$st_company_id;
			$ledger->ledger_account_id = $ledger_account->id;
			$ledger->credit = 0;
			$ledger->debit = $salaryAdvance->amount;
			$ledger->voucher_id = $nppayment->id;
			$ledger->voucher_source = 'Non Print Payment Voucher';
			$ledger->transaction_date = $trans_date;
			$this->SalaryAdvances->Nppayments->Ledgers->save($ledger);
			
			return $this->redirect(['controller' =>'Logins' ,'action' => 'dashbord']);
		}
		
		
		$vr=$this->SalaryAdvances->Nppayments->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Non Print Payment Voucher','sub_entity'=>'Cash/Bank'])->first();
        $vouchersReferences = $this->SalaryAdvances->Nppayments->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
        $where=[];
        foreach($vouchersReferences->voucher_ledger_accounts as $data){
            $where[]=$data->ledger_account_id;
        }
        if(sizeof($where)>0){
            $bankCashes = $this->SalaryAdvances->Nppayments->BankCashes->find('list',
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
        }
		$this->set(compact('bankCashes'));
		
        $this->set('salaryAdvance', $salaryAdvance);
        $this->set('_serialize', ['salaryAdvance']);
	}

    /**
     * View method
     *
     * @param string|null $id Salary Advance id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $salaryAdvance = $this->SalaryAdvances->get($id, [
            'contain' => ['Employees']
        ]);
		
        $this->set('salaryAdvance', $salaryAdvance);
        $this->set('_serialize', ['salaryAdvance']);
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
        $salaryAdvance = $this->SalaryAdvances->newEntity();
		$empData=$this->SalaryAdvances->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
        if ($this->request->is('post')) {
            $salaryAdvance = $this->SalaryAdvances->patchEntity($salaryAdvance, $this->request->data);
            $salaryAdvance->create_date =date('Y-m-d');
            if ($this->SalaryAdvances->save($salaryAdvance)) {
                $this->Flash->success(__('The salary advance has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The salary advance could not be saved. Please, try again.'));
            }
        }
		$cd  = date('d-m-Y');
		$From = date('t-m-Y', strtotime($cd));
		$From=date('Y-m-d',strtotime($From));
		$EmployeeSalary = $this->SalaryAdvances->EmployeeSalaries->find()->where(['employee_id'=>$s_employee_id,'effective_date_from <='=>$From])->contain(['EmployeeSalaryRows'=>['EmployeeSalaryDivisions']])->order(['id'=>'DESC'])->first(); 
		$empSallary=0;
		foreach(@$EmployeeSalary->employee_salary_rows as $data){
				if($data->employee_salary_division->salary_type=='addition'){
					$empSallary+=$data->amount;
				}
		}
		
		$Employees=$this->SalaryAdvances->Employees->find('list');
        $this->set(compact('salaryAdvance','empData','Employees','empSallary'));
        $this->set('_serialize', ['salaryAdvance']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Salary Advance id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $salaryAdvance = $this->SalaryAdvances->get($id, [
            'contain' => []
        ]);
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$empData=$this->SalaryAdvances->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $salaryAdvance = $this->SalaryAdvances->patchEntity($salaryAdvance, $this->request->data);
            if ($this->SalaryAdvances->save($salaryAdvance)) {
                $this->Flash->success(__('The salary advance has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The salary advance could not be saved. Please, try again.'));
            }
        }
		$cd  = date('d-m-Y');
		$From = date('t-m-Y', strtotime($cd));
		$From=date('Y-m-d',strtotime($From));
		$EmployeeSalary = $this->SalaryAdvances->EmployeeSalaries->find()->where(['employee_id'=>$salaryAdvance->employee_id,'effective_date_from <='=>$From])->contain(['EmployeeSalaryRows'=>['EmployeeSalaryDivisions']])->order(['id'=>'DESC'])->first(); 
		$empSallary=0;
		foreach(@$EmployeeSalary->employee_salary_rows as $data){
				if($data->employee_salary_division->salary_type=='addition'){
					$empSallary+=$data->amount;
				}
		}
		
		$Employees=$this->SalaryAdvances->Employees->find('list');
        $this->set(compact('salaryAdvance','empData','Employees','empSallary'));
        $this->set('_serialize', ['salaryAdvance']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Salary Advance id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $salaryAdvance = $this->SalaryAdvances->get($id);
        if ($this->SalaryAdvances->delete($salaryAdvance)) {
            $this->Flash->success(__('The salary advance has been deleted.'));
        } else {
            $this->Flash->error(__('The salary advance could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
