<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EmployeeSalaries Controller
 *
 * @property \App\Model\Table\EmployeeSalariesTable $EmployeeSalaries
 */
class EmployeeSalariesController extends AppController
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
		$s_employee_id=$this->viewVars['s_employee_id'];
        $this->paginate = [
            'contain' => ['Employees'=>['Designations'], 'EmployeeSalaryRows']
        ];
        $employeeSalaries = $this->paginate($this->EmployeeSalaries);

        $this->set(compact('employeeSalaries'));
        $this->set('_serialize', ['employeeSalaries']);
    }

    /**
     * View method
     *
     * @param string|null $id Employee Salary id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employeeSalary = $this->EmployeeSalaries->get($id, [
            'contain' => ['Employees', 'EmployeeSalaryDivisions']
        ]);

        $this->set('employeeSalary', $employeeSalary);
        $this->set('_serialize', ['employeeSalary']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function paySallary($From=null){
		//$this->viewBuilder()->layout('index_layout');
		$From1=$From;
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->EmployeeSalaries->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		if(!empty($From)){ 
			$From="01-".$From;
			$time=strtotime($From);
			$month=date("m",$time);
			$year=date("Y",$time);
			$total_day=cal_days_in_month(CAL_GREGORIAN,$month,$year);
		} 
		
		//$employees = $this->EmployeeSalaries->Employees->find()->where(['status'=>'0','id !='=>23]); 
		$employees = $this->EmployeeSalaries->Employees->find()->where(['id !='=>23])->where(['salary_company_id'=>$st_company_id])
		->contain(['EmployeeCompanies'])
			->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.company_id'=>$st_company_id,'EmployeeCompanies.freeze'=>0]);
					}
				);

		$emp_sallary_division=[];
		$basic_sallary=[];
		$loan_amount=[];
		$other_amount=[];
		foreach($employees as $dt){
			$From=date('Y-m-d',strtotime($From)); 
			$EmployeeSalary = $this->EmployeeSalaries->find()->where(['employee_id'=>$dt->id,'effective_date_from <='=>$From])->contain(['EmployeeSalaryRows'])->order(['id'=>'DESC'])->first();   
			
			$EmployeeAttendance = $this->EmployeeSalaries->EmployeeAttendances->find()->where(['employee_id'=>$dt->id,'month'=>$month,'financial_year_id'=>$financial_year->id])->first();  
			$LoanApplications = $this->EmployeeSalaries->LoanApplications->find()->where(['employee_id'=>$dt->id,'starting_date_of_loan <= '=>$From,'ending_date_of_loan >= '=>$From,'status'=>'approved'])->first();  
				if($LoanApplications){
					$loan_amount[$dt->id]=$LoanApplications->instalment_amount; 
				}
				
				if($EmployeeSalary){
					foreach($EmployeeSalary->employee_salary_rows as $dt1){ 
						$esd = $this->EmployeeSalaries->EmployeeSalaryRows->EmployeeSalaryDivisions->get($dt1->employee_salary_division_id);
						if($esd->vary_fixed=="Vary"){
							$emp_sallary_division[$dt->id][@$dt1->employee_salary_division_id]=@$EmployeeAttendance->present_day*@$dt1->amount/$total_day;
						}else{
							$emp_sallary_division[$dt->id][@$dt1->employee_salary_division_id]=@$dt1->amount;
						}
						if($esd->salary_type=="addition"){ 
							@$basic_sallary[@$dt->id]+=$dt1->amount; 
						}
						
						
					}
				}
						$ledger_account=$this->EmployeeSalaries->LedgerAccounts->find()->where(['source_model'=>'Employees','source_id'=>$dt->id,'company_id'=>$st_company_id])->first();
						$ToDate=$total_day."-".$From1;
						$to_date=date('Y-m-d',strtotime($ToDate)); 
						
						$query=$this->EmployeeSalaries->LedgerAccounts->Ledgers->find();
						$query->select(['ledger_account_id','totalDebit' => $query->func()->sum('Ledgers.debit'),'totalCredit' => $query->func()->sum('Ledgers.credit')])
						->where(['Ledgers.ledger_account_id'=>@$ledger_account->id, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>@$st_company_id])->first();
						$dr =$query->toArray()[0]['totalDebit'];
						$cr =$query->toArray()[0]['totalCredit']; 
						$other_amount[@$dt->id]=round($dr-$cr,2);
		
		} 
//pr($other_amount); exit;
		$EmployeeSalaryAddition = $this->EmployeeSalaries->EmployeeSalaryRows->EmployeeSalaryDivisions->find()->where(['salary_type'=>'addition']); 
		$EmployeeSalaryDeduction = $this->EmployeeSalaries->EmployeeSalaryRows->EmployeeSalaryDivisions->find()->where(['salary_type'=>'deduction']); 
		
		$this->set(compact('employees', 'employeeSalary', 'employeeSalaryDivisions','employeeDetails','financial_year','basic_sallary','emp_month_sallary','EmployeeSalaryAddition','EmployeeSalaryDeduction','emp_sallary_division','loan_amount','other_amount'));

	}

    public function paidSallary(){
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$st_year_id = $session->read('st_year_id');
		$employeeSalary = $this->EmployeeSalaries->newEntity();
		$financial_year = $this->EmployeeSalaries->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$From=$this->request->query('From');
		if(!empty($From)){
			$From="01-".$From;
			$time=strtotime($From);
			$month=date("m",$time);
		}
		$where1=[];
		if(!empty($month)){ 
			$where1['month LIKE']=$month;
		}else{
			$time=strtotime(date('d-m-Y'));
			$month=date("m",$time); 
			$where1['month LIKE']=$month;
		}
		//$this->EmployeeAttendances->find()->where($where1);
		$employees = $this->EmployeeSalaries->Employees->find()->where(['status'=>'0','id !='=>23]);  
		$EmployeeSalaryDivisions = $this->EmployeeSalaries->EmployeeSalaryRows->EmployeeSalaryDivisions->find(); 
		$this->set(compact('employees', 'employeeSalary', 'employeeSalaryDivisions','employeeDetails','financial_year','EmployeeSalaryDivisions'));
        $this->set('_serialize', ['employeeSalary']);
	}
    public function add($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
        $employeeSalary = $this->EmployeeSalaries->newEntity();
        if ($this->request->is('post')) {
			$effective_date_from=date('Y-m-d',strtotime($this->request->data()['effective_date_from']));
			$effective_date_to=date('Y-m-d',strtotime($this->request->data()['effective_date_to']));
            $employeeSalary = $this->EmployeeSalaries->patchEntity($employeeSalary, $this->request->data);
			$employeeSalary->effective_date_from=$effective_date_from;
			$employeeSalary->effective_date_to=$effective_date_to;
			$employeeSalary->created_on=$s_employee_id;
			$employeeSalary->amount=$employeeSalary->amount;
			if ($this->EmployeeSalaries->save($employeeSalary)) { 
                $this->Flash->success(__('The employee salary has been saved.'));

                return $this->redirect(['controller' =>'Employees' ,'action' => 'index']);
            } else {
                $this->Flash->error(__('The employee salary could not be saved. Please, try again.'));
            }
        }
        $employee = $this->EmployeeSalaries->Employees->get($id);
        $employeeDesignation = $this->EmployeeSalaries->Employees->Designations->find('list');
        $employeeSalaryDivisions = $this->EmployeeSalaries->EmployeeSalaryRows->EmployeeSalaryDivisions->find();
		$employeeDetails=[];
		foreach($employeeSalaryDivisions as $data){
			$employeeDetails[]=['text'=>$data->name,'value'=>$data->id,'salary_type'=>$data->salary_type];
		} 
		$EmployeeSalaryDetails=[];
		$EmployeeSalaryDetails = $this->EmployeeSalaries->find()->where(['employee_id'=>$id])->contain(['EmployeeSalaryRows'])->order(['id'=>'DESC'])->first();
		//pr($EmployeeSalaryDetails); exit;
        $this->set(compact('employeeSalary', 'employee', 'employeeSalaryDivisions','employeeDetails','employeeDesignation','EmployeeSalaryDetails'));
        $this->set('_serialize', ['employeeSalary']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Salary id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employeeSalary = $this->EmployeeSalaries->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeeSalary = $this->EmployeeSalaries->patchEntity($employeeSalary, $this->request->data);
            if ($this->EmployeeSalaries->save($employeeSalary)) {
                $this->Flash->success(__('The employee salary has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee salary could not be saved. Please, try again.'));
            }
        }
        $employees = $this->EmployeeSalaries->Employees->find('list', ['limit' => 200]);
        $employeeSalaryDivisions = $this->EmployeeSalaries->EmployeeSalaryDivisions->find('list', ['limit' => 200]);
        $this->set(compact('employeeSalary', 'employees', 'employeeSalaryDivisions'));
        $this->set('_serialize', ['employeeSalary']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee Salary id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employeeSalary = $this->EmployeeSalaries->get($id);
        if ($this->EmployeeSalaries->delete($employeeSalary)) {
            $this->Flash->success(__('The employee salary has been deleted.'));
        } else {
            $this->Flash->error(__('The employee salary could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
