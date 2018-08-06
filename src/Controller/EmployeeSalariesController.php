<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
/**
 * EmployeeSalaries Controller
 *
 * @property \App\Model\Table\EmployeeSalariesTable $EmployeeSalaries
 */
class EmployeeSalariesController extends AppController
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
		$this->set(compact('From'));

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
			$c=$year.'-'.$month.'-'.$total_day;
		} 
		
		//$employees = $this->EmployeeSalaries->Employees->find()->where(['status'=>'0','id !='=>23]); 
		$employees = $this->EmployeeSalaries->Employees->find()->where(['id !='=>23])->where(['salary_company_id'=>$st_company_id])->where(['join_date <='=>$c])
		->contain(['EmployeeCompanies'])
			->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.company_id'=>$st_company_id,'EmployeeCompanies.freeze'=>0]);
					}
				);

		$emp_sallary_division=[];
		$basic_sallary=[];
		$loan_amount=[];
		$loan_app=[];
		$other_amount=[];
		$EmployeeAtten=[];$total_loan_amt=[];
		foreach($employees as $dt){
			$From=date('Y-m-d',strtotime($From)); 
			$EmployeeSalary = $this->EmployeeSalaries->find()->where(['employee_id'=>$dt->id,'effective_date_from <='=>$From])->contain(['EmployeeSalaryRows'])->order(['EmployeeSalaries.id'=>'DESC'])
			->matching(
				'EmployeeSalaryRows.EmployeeSalaryDivisions', function ($q) use($st_company_id) {
					return $q->where(['EmployeeSalaryDivisions.company_id'=>$st_company_id]);
				}
			)->first();   
			
			$EmployeeAttendance = $this->EmployeeSalaries->EmployeeAttendances->find()->where(['employee_id'=>$dt->id,'month'=>$month,'financial_year_id'=>$financial_year->id])->first();  
			$EmployeeAtten[$dt->id]=@$EmployeeAttendance->present_day;
			
			$LoanInstallments = $this->EmployeeSalaries->LoanApplications->LoanInstallments->find();
			$LoanApplications = $this->EmployeeSalaries->LoanApplications->find()
								->where(['employee_id'=>$dt->id,'installment_start_month <= '=>$month,'installment_start_year >= '=>$year,'status'=>'approved'])
								->contain(['LoanInstallments']);
			
			
			
			foreach($LoanApplications as $LoanApplication){
				$repayment=0;
					
				if($LoanApplication->loan_installments){
					foreach($LoanApplication->loan_installments as $loan_installment){
						$repayment+=$loan_installment->amount;
						$total_loan_amt[$dt->id]=$repayment;
					}
				}
				
				if($LoanApplication->approve_amount_of_loan>$repayment){
					$loan_amount[$dt->id]=$LoanApplication->instalment_amount; 
					$loan_app[$dt->id]=$LoanApplication->id;
					
					break;
				}
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
				
				$query=$this->EmployeeSalaries->LedgerAccounts->Ledgers->find()->where(['loan_amount IS NULL']);
				$query->select(['ledger_account_id','totalDebit' => $query->func()->sum('Ledgers.debit'),'totalCredit' => $query->func()->sum('Ledgers.credit')])
				->where(['Ledgers.ledger_account_id'=>@$ledger_account->id, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>@$st_company_id])->first();
				$dr =$query->toArray()[0]['totalDebit'];
				$cr =$query->toArray()[0]['totalCredit']; 
				
				$SalaryExist=$this->EmployeeSalaries->Salaries->find()->where(['company_id'=>$st_company_id,'month'=>$month,'year'=>$year,'employee_id'=>@$dt->id])->first();
				
				if($SalaryExist){
					$other_amount[@$dt->id]=round(@$SalaryExist->other_amount,2);
				}else{
					
					$other_amount[@$dt->id]=round((@$dr-@$cr),2);
				}
		
		}   
//pr($other_amount); exit;


			$vr=$this->EmployeeSalaries->Nppayments->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Non Print Payment Voucher','sub_entity'=>'Cash/Bank'])->first();
			
			$vouchersReferences = $this->EmployeeSalaries->Nppayments->VouchersReferences->get($vr->id, [
				'contain' => ['VoucherLedgerAccounts']
			]);
			
			$where=[];
			foreach($vouchersReferences->voucher_ledger_accounts as $data){
				$where[]=$data->ledger_account_id;
			}
			
			if(sizeof($where)>0){
				$bankCashes = $this->EmployeeSalaries->Nppayments->BankCashes->find('list',
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
		
		$EmployeeSalaryAddition = $this->EmployeeSalaries->EmployeeSalaryRows->EmployeeSalaryDivisions->find()->where(['salary_type'=>'addition', 'company_id'=>$st_company_id]); 
		$EmployeeSalaryDeduction = $this->EmployeeSalaries->EmployeeSalaryRows->EmployeeSalaryDivisions->find()->where(['salary_type'=>'deduction', 'company_id'=>$st_company_id]); 
		
		$this->set(compact('employees', 'employeeSalary', 'employeeSalaryDivisions','employeeDetails','financial_year','basic_sallary','emp_month_sallary','EmployeeSalaryAddition','EmployeeSalaryDeduction','emp_sallary_division','loan_amount','loan_app','other_amount','EmployeeAtten','bankCashes','total_loan_amt'));

	}
	
	
	
	public function generateSalary(){
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$st_year_id = $session->read('st_year_id');
		
		$financial_year = $this->EmployeeSalaries->FinancialYears->find()->where(['FinancialYears.id'=>$st_year_id])->first();
		
		
		//Save the salary
		if ($this->request->is('post')) {
			
			$From=$this->request->data()['From'];
			$other_amounts=$this->request->data()['other_amount'];
			$loan_amount2=$this->request->data()['loan_amount'];
			$loan_app=$this->request->data()['loan_app'];
			$trans_date=date('Y-m-d',strtotime($this->request->data()['trans_date']));
			$bank_id=$this->request->data()['bank_id'];
			
			if(!empty($From)){
				$From1=$From;
				$From="01-".$From;
				$time=strtotime($From);
				$month=date("m",$time);
				$year=date("Y",$time);
				$total_day=cal_days_in_month(CAL_GREGORIAN,$month,$year);
			} 
		
			$SalaryExist=$this->EmployeeSalaries->Salaries->find()->where(['company_id'=>$st_company_id,'month'=>$month,'year'=>$year]);
			if(sizeof($SalaryExist->toArray())>0){
				echo 'salary has already generated.';
			}			
			
			$OldVoucherNos=[];
			$oldVouchers=$this->EmployeeSalaries->Nppayments->find()->where(['salary_month'=>$month,'salary_year'=>$year, 'company_id'=>$st_company_id, 'financial_year_id'=>$st_year_id]);
			if($oldVouchers){
				foreach($oldVouchers as $oldVoucher){
					$OldVoucherNos[$oldVoucher->emp_id]=$oldVoucher->voucher_no;
					
					$this->EmployeeSalaries->Nppayments->Ledgers->deleteAll(['voucher_source' => 'Non Print Payment Voucher', 'voucher_id'=>$oldVoucher->id]);
					$this->EmployeeSalaries->Nppayments->NppaymentRows->deleteAll(['nppayment_id' => $oldVoucher->id]);
					$this->EmployeeSalaries->Nppayments->deleteAll(['Nppayments.id' => $oldVoucher->id]);
				}
			}
			
			
			$this->EmployeeSalaries->Salaries->deleteAll(['month' => $month, 'year' => $year, 'company_id'=>$st_company_id]);
			
			
			foreach($other_amounts as $key=>$value){
				if($value!=0){
					$salary = $this->EmployeeSalaries->Salaries->newEntity();
					$salary->employee_id=$key;
					$salary->company_id=$st_company_id;
					$salary->employee_salary_division_id=0;
					$salary->amount=0;
					$salary->other_amount=$value;
					$salary->month=$month;
					$salary->year=$year;
					$this->EmployeeSalaries->Salaries->save($salary);
				}
			}
			
			foreach($loan_amount2 as $key=>$value){
				if($value!=0){
					$salary = $this->EmployeeSalaries->Salaries->newEntity();
					$salary->employee_id=$key;
					$salary->company_id=$st_company_id;
					$salary->employee_salary_division_id=0;
					$salary->amount=0;
					$salary->loan_amount=$value;
					$salary->month=$month;
					$salary->year=$year;
					$this->EmployeeSalaries->Salaries->save($salary);
				}
			}
			
		
		
		$employees = $this->EmployeeSalaries->Employees->find()->where(['Employees.id !='=>23])->where(['salary_company_id'=>$st_company_id])
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
		$EmployeeAtten=[];
		
		$ledgerPosting=[];
		foreach($employees as $dt){
			$total_dr=0; $total_cr=0;
			
			//Find ledger account of employee
			$LedgerAccount=$this->EmployeeSalaries->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Employees','source_id'=>$dt->id])->first();
			
			//Save Non Print Payment Voucher//
			$Nppayment=$this->EmployeeSalaries->Nppayments->newEntity();
			
			if(@$OldVoucherNos[$dt->id]){
				$Nppayment->voucher_no=$OldVoucherNos[$dt->id];
			}else{
				$last_voucher_no=$this->EmployeeSalaries->Nppayments->find()->select(['voucher_no'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['voucher_no' => 'DESC'])->first();
				if($last_voucher_no){
					$Nppayment->voucher_no=$last_voucher_no->voucher_no+1;
				}else{
					$Nppayment->voucher_no=1;
				}
			}
			$Nppayment->financial_year_id=$st_year_id;
			$Nppayment->bank_cash_id=$bank_id;
			$Nppayment->created_on=date("Y-m-d");
            $Nppayment->created_by=$s_employee_id;
            $Nppayment->payment_mode='NEFT/RTGS';
            $Nppayment->company_id=$st_company_id;
            $Nppayment->transaction_date=$trans_date;
            $Nppayment->cheque_no="";
            $Nppayment->salary_month=$month;
            $Nppayment->salary_year=$year;
            $Nppayment->emp_id=$dt->id;
			$this->EmployeeSalaries->Nppayments->save($Nppayment);
			
			
			
			if(@$other_amounts[$dt->id]<0){
				$NppaymentRow=$this->EmployeeSalaries->Nppayments->NppaymentRows->newEntity();
				$NppaymentRow->nppayment_id=$Nppayment->id;
				$NppaymentRow->received_from_id=$LedgerAccount->id;//ledger account  of emp
				$NppaymentRow->amount=abs($other_amounts[$dt->id]);
				$NppaymentRow->cr_dr='Dr';
				$NppaymentRow->narration='Other amount';
				if($NppaymentRow->amount>0){
					$this->EmployeeSalaries->Nppayments->NppaymentRows->save($NppaymentRow);
				}
				
				
				$ledger = $this->EmployeeSalaries->Nppayments->Ledgers->newEntity();
				$ledger->company_id=$st_company_id;
				$ledger->ledger_account_id = $LedgerAccount->id;
				$ledger->credit = 0;
				$ledger->debit =abs($other_amounts[$dt->id]);
				$total_dr=$total_dr+abs($other_amounts[$dt->id]);
				$ledger->voucher_id = $Nppayment->id;
				$ledger->voucher_source = 'Non Print Payment Voucher';
				$ledger->transaction_date = $Nppayment->transaction_date;
				if($ledger->debit>0){
					$this->EmployeeSalaries->Nppayments->Ledgers->save($ledger);
				}
					
			}else if(@$other_amounts[$dt->id]>0){
				$NppaymentRow=$this->EmployeeSalaries->Nppayments->NppaymentRows->newEntity();
				$NppaymentRow->nppayment_id=$Nppayment->id;
				$NppaymentRow->received_from_id=$LedgerAccount->id;//ledger account  of emp
				$NppaymentRow->amount=abs($other_amounts[$dt->id]);
				$NppaymentRow->cr_dr='Cr';
				$NppaymentRow->narration='Other amount';
				if($NppaymentRow->amount>0){
					$this->EmployeeSalaries->Nppayments->NppaymentRows->save($NppaymentRow);
				}
				
				$ledger = $this->EmployeeSalaries->Nppayments->Ledgers->newEntity();
				$ledger->company_id=$st_company_id;
				$ledger->ledger_account_id = $LedgerAccount->id;
				$ledger->credit = abs($other_amounts[$dt->id]);
				$ledger->debit = 0;
				$total_cr=$total_cr+abs($other_amounts[$dt->id]);
				$ledger->voucher_id = $Nppayment->id;
				$ledger->voucher_source = 'Non Print Payment Voucher';
				$ledger->transaction_date = $Nppayment->transaction_date;
				if($ledger->credit>0){
					$this->EmployeeSalaries->Nppayments->Ledgers->save($ledger);
				}
			}
			
			$this->EmployeeSalaries->LoanInstallments->deleteAll(['month' => $month, 'year' => $year]);
			if(@$loan_amount2[$dt->id]>0){
				$LoanInstallment = $this->EmployeeSalaries->LoanInstallments->newEntity();
				$LoanInstallment->loan_application_id=$loan_app[$dt->id];
				$LoanInstallment->month=$month;
				$LoanInstallment->year=$year;
				$LoanInstallment->amount=$loan_amount2[$dt->id];
				$this->EmployeeSalaries->LoanInstallments->save($LoanInstallment);
				
						
				$NppaymentRow=$this->EmployeeSalaries->Nppayments->NppaymentRows->newEntity();
				$NppaymentRow->nppayment_id=$Nppayment->id;
				$NppaymentRow->received_from_id=$LedgerAccount->id;
				$NppaymentRow->amount=$loan_amount2[$dt->id];
				$NppaymentRow->cr_dr='Cr';
				$NppaymentRow->narration='Loan installment';
				if($NppaymentRow->amount>0){
					$this->EmployeeSalaries->Nppayments->NppaymentRows->save($NppaymentRow);
				}
				
				$ledger = $this->EmployeeSalaries->Nppayments->Ledgers->newEntity();
				$ledger->company_id=$st_company_id;
				$ledger->ledger_account_id = $LedgerAccount->id;
				$ledger->credit = $loan_amount2[$dt->id];
				$ledger->debit = 0;
				$total_cr=$total_cr+$loan_amount2[$dt->id];
				$ledger->voucher_id = $Nppayment->id;
				$ledger->voucher_source = 'Non Print Payment Voucher';
				$ledger->transaction_date = $Nppayment->transaction_date;
				if($ledger->credit>0){
					$this->EmployeeSalaries->Nppayments->Ledgers->save($ledger);
				}
				
			}
			
			
			$From=date('Y-m-d',strtotime($From)); 
			$EmployeeSalary = $this->EmployeeSalaries->find()->where(['employee_id'=>$dt->id,'effective_date_from <='=>$From])->contain(['EmployeeSalaryRows'])->order(['EmployeeSalaries.id'=>'DESC'])
			->matching('EmployeeSalaryRows.EmployeeSalaryDivisions', function ($q) use($st_company_id){
				return $q->where(['EmployeeSalaryDivisions.company_id' => $st_company_id]);
			})
			->first();  
			
			$EmployeeAttendance = $this->EmployeeSalaries->EmployeeAttendances->find()->where(['employee_id'=>$dt->id,'month'=>$month,'financial_year_id'=>$financial_year->id])->first();  
			$EmployeeAtten[$dt->id]=@$EmployeeAttendance->present_day;
			$LoanApplications = $this->EmployeeSalaries->LoanApplications->find()->where(['employee_id'=>$dt->id,'starting_date_of_loan <= '=>$From,'ending_date_of_loan >= '=>$From,'status'=>'approved'])->first();  
				
				if($EmployeeSalary){
					
					foreach($EmployeeSalary->employee_salary_rows as $dt1){
						
						$esd = $this->EmployeeSalaries->EmployeeSalaryRows->EmployeeSalaryDivisions->get($dt1->employee_salary_division_id);
						if($esd->vary_fixed=="Vary"){
							$emp_sallary_division[$dt->id][@$dt1->employee_salary_division_id]=@$EmployeeAttendance->present_day*@$dt1->amount/$total_day;
							
							if(@$EmployeeAttendance->present_day*@$dt1->amount/$total_day>0){
								
								$salary = $this->EmployeeSalaries->Salaries->newEntity();
								$salary->employee_id=$dt->id;
								$salary->company_id=$st_company_id;
								$salary->employee_salary_division_id=$dt1->employee_salary_division_id;
								$salary->amount=@$EmployeeAttendance->present_day*@$dt1->amount/$total_day;
								$salary->month=$month;
								$salary->year=$year;
								$this->EmployeeSalaries->Salaries->save($salary);
								
								/* $NppaymentRow=$this->EmployeeSalaries->Nppayments->NppaymentRows->newEntity();
								$NppaymentRow->nppayment_id=$Nppayment->id;
								$NppaymentRow->received_from_id=$esd->ledger_account_id;
								$NppaymentRow->amount=@$EmployeeAttendance->present_day*@$dt1->amount/$total_day;
								$NppaymentRow->cr_dr=ucfirst($esd->cr_dr);
								$NppaymentRow->narration='';
								$this->EmployeeSalaries->Nppayments->NppaymentRows->save($NppaymentRow); */
								
								if($esd->salary_type='addition'){
									if(ucfirst($esd->cr_dr)=="Cr"){
										@$ledgerPosting[$esd->ledger_account_id]['credit']+= @$EmployeeAttendance->present_day*@$dt1->amount/$total_day;
									}else{
										@$ledgerPosting[$esd->ledger_account_id]['debit']+= @$EmployeeAttendance->present_day*@$dt1->amount/$total_day;
									}
								}else{
									if(ucfirst($esd->cr_dr)=="Cr"){
										@$ledgerPosting[$esd->ledger_account_id]['credit']-= @$EmployeeAttendance->present_day*@$dt1->amount/$total_day;
									}else{
										@$ledgerPosting[$esd->ledger_account_id]['debit']-= @$EmployeeAttendance->present_day*@$dt1->amount/$total_day;
									}	
								}
								
								
								/* $ledger = $this->EmployeeSalaries->Nppayments->Ledgers->newEntity();
								$ledger->company_id=$st_company_id;
								$ledger->ledger_account_id = $esd->ledger_account_id;
								if($NppaymentRow->cr_dr=="Cr"){
									$ledger->credit = @$EmployeeAttendance->present_day*@$dt1->amount/$total_day;
									$ledger->debit = 0;
									$total_cr=$total_cr+@$EmployeeAttendance->present_day*@$dt1->amount/$total_day;
								}else{
									$ledger->credit = 0;
									$ledger->debit = @$EmployeeAttendance->present_day*@$dt1->amount/$total_day;
									$total_dr=$total_dr+@$EmployeeAttendance->present_day*@$dt1->amount/$total_day;
								}
								$ledger->voucher_id = $Nppayment->id;
								$ledger->voucher_source = 'Non Print Payment Voucher';
								$ledger->transaction_date = $Nppayment->transaction_date;
								$this->EmployeeSalaries->Nppayments->Ledgers->save($ledger); */
						
						
							}
							
						}else{
							$emp_sallary_division[$dt->id][@$dt1->employee_salary_division_id]=@$dt1->amount;
							
							if(@$dt1->amount>0){
								$salary = $this->EmployeeSalaries->Salaries->newEntity();
								$salary->employee_id=$dt->id;
								$salary->company_id=$st_company_id;
								$salary->employee_salary_division_id=$dt1->employee_salary_division_id;
								$salary->amount=$dt1->amount;
								$salary->month=$month;
								$salary->year=$year;
								$this->EmployeeSalaries->Salaries->save($salary);
								
								/* $NppaymentRow=$this->EmployeeSalaries->Nppayments->NppaymentRows->newEntity();
								$NppaymentRow->nppayment_id=$Nppayment->id;
								$NppaymentRow->received_from_id=$esd->ledger_account_id;//load ledger
								$NppaymentRow->amount=$dt1->amount;
								$NppaymentRow->cr_dr=ucfirst($esd->cr_dr);
								$NppaymentRow->narration='';
								$this->EmployeeSalaries->Nppayments->NppaymentRows->save($NppaymentRow); */
								
								if($esd->salary_type='addition'){
									if(ucfirst($esd->cr_dr)=="Cr"){
										@$ledgerPosting[$esd->ledger_account_id]['credit']+= $dt1->amount;
									}else{
										@$ledgerPosting[$esd->ledger_account_id]['debit']+= $dt1->amount;
									}
								}else{
									if(ucfirst($esd->cr_dr)=="Cr"){
										@$ledgerPosting[$esd->ledger_account_id]['credit']-= $dt1->amount;
									}else{
										@$ledgerPosting[$esd->ledger_account_id]['debit']-= $dt1->amount;
									}
								}
								
								
								/* $ledger = $this->EmployeeSalaries->Nppayments->Ledgers->newEntity();
								$ledger->company_id=$st_company_id;
								$ledger->ledger_account_id = $esd->ledger_account_id;
								if($NppaymentRow->cr_dr=="Cr"){
									$ledger->credit = $dt1->amount;
									$ledger->debit = 0;
									$total_cr=$total_cr+$dt1->amount;
								}else{
									$ledger->credit = 0;
									$ledger->debit = $dt1->amount;
									$total_dr=$total_dr+$dt1->amount;
								}
								$ledger->voucher_id = $Nppayment->id;
								$ledger->voucher_source = 'Non Print Payment Voucher';
								$ledger->transaction_date = $Nppayment->transaction_date;
								$this->EmployeeSalaries->Nppayments->Ledgers->save($ledger); */
							}
							
						}
						if($esd->salary_type=="addition"){ 
							@$basic_sallary[@$dt->id]+=$dt1->amount; 						
						}
						
						
					}
					//pr($ledgerPosting); 
					foreach($ledgerPosting as $ledgerAccId=>$ledgerPostingRow){
						$r=round(@$ledgerPostingRow['debit']-@$ledgerPostingRow['credit']);
						
						
						$NppaymentRow=$this->EmployeeSalaries->Nppayments->NppaymentRows->newEntity();
						$NppaymentRow->nppayment_id=$Nppayment->id;
						$NppaymentRow->received_from_id=$ledgerAccId;
						$NppaymentRow->amount=abs($r);
						if($r>0){
							$NppaymentRow->cr_dr='Dr';
						}else if($r<0){
							$NppaymentRow->cr_dr='Cr';
						}
						$NppaymentRow->narration='Salary of '.$month.'-'.$year.' for Employee: '.$dt->name; 
						if($r!=0){
							$this->EmployeeSalaries->Nppayments->NppaymentRows->save($NppaymentRow);
						}
								
								
						$ledger = $this->EmployeeSalaries->Nppayments->Ledgers->newEntity();
						$ledger->company_id=$st_company_id;
						$ledger->ledger_account_id = $ledgerAccId;
						if($r>0){
							$ledger->credit = 0;
							$ledger->debit = $r;
							$total_dr=$total_dr+$r;
						}else if($r<0){
							$ledger->credit = abs($r);
							$ledger->debit = 0;
							$total_cr=$total_cr+abs($r);
						}
						$ledger->voucher_id = $Nppayment->id;
						$ledger->voucher_source = 'Non Print Payment Voucher';
						$ledger->transaction_date = $Nppayment->transaction_date;
						if($r!=0){
							$this->EmployeeSalaries->Nppayments->Ledgers->save($ledger);
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
		
		
					$bankAmt=$total_dr-$total_cr;
					
					$ledger = $this->EmployeeSalaries->Nppayments->Ledgers->newEntity();
					$ledger->company_id=$st_company_id;
					$ledger->ledger_account_id = $bank_id;
					if($bankAmt > 0){
						$ledger->credit = $bankAmt;
						$ledger->debit = 0;
					}else{
						$ledger->debit = abs($bankAmt);
						$ledger->credit = 0;
					}
					$ledger->voucher_id = $Nppayment->id;
					$ledger->voucher_source = 'Non Print Payment Voucher';
					$ledger->transaction_date = $Nppayment->transaction_date;
					if($bankAmt != 0){
						$this->EmployeeSalaries->Nppayments->Ledgers->save($ledger);
					}
			$ledgerPosting=[];
			
			$rows=$this->EmployeeSalaries->Nppayments->NppaymentRows->find()->where(['nppayment_id'=>$Nppayment->id]);
			if(sizeof($rows->toArray())==0){
				$this->EmployeeSalaries->Nppayments->deleteAll(['Nppayments.id'=>$Nppayment->id]);
			}
		}
		$EmployeeSalaryAddition = $this->EmployeeSalaries->EmployeeSalaryRows->EmployeeSalaryDivisions->find()->where(['salary_type'=>'addition']); 
		$EmployeeSalaryDeduction = $this->EmployeeSalaries->EmployeeSalaryRows->EmployeeSalaryDivisions->find()->where(['salary_type'=>'deduction']); 
		
		
		}
		return $this->redirect(['controller' =>'EmployeeSalaries' ,'action' => 'paidSallary']);
		
		$this->set(compact('employees', 'employeeSalary', 'employeeSalaryDivisions','employeeDetails','financial_year','basic_sallary','emp_month_sallary','EmployeeSalaryAddition','EmployeeSalaryDeduction','emp_sallary_division','loan_amount','other_amount','EmployeeAtten'));

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
        $employeeSalaryDivisions = $this->EmployeeSalaries->EmployeeSalaryRows->EmployeeSalaryDivisions->find()->where(['company_id'=>$st_company_id]);
		$employeeDetails=[];
		foreach($employeeSalaryDivisions as $data){
			$employeeDetails[]=['text'=>$data->name,'value'=>$data->id,'salary_type'=>$data->salary_type];
		} 
		$EmployeeSalaryDetails=[];
		$EmployeeSalaryDetails = $this->EmployeeSalaries->find()->where(['employee_id'=>$id])->contain(['EmployeeSalaryRows'])->order(['EmployeeSalaries.id'=>'DESC'])
		->matching(
			'EmployeeSalaryRows.EmployeeSalaryDivisions', function ($q) use($st_company_id) {
				return $q->where(['EmployeeSalaryDivisions.company_id'=>$st_company_id]);
			}
		)
		->first();
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
	
	public function printSallary(){
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->EmployeeSalaries->FinancialYears->find()->where(['id'=>$st_year_id])->first();
        $this->set(compact('financial_year'));
		if ($this->request->is('post')) {
			$month_year=$this->request->data['month_year'];
			$month_year=explode('-',$month_year);
			$Employees=$this->EmployeeSalaries->Employees->find()
			->contain(['Salaries'=>function($q) use($month_year, $st_company_id){
				return $q->where(['month'=>$month_year[0],'year'=>$month_year[1],'Salaries.company_id'=>$st_company_id])->contain(['EmpSalDivs']);
			}])
			->matching(
					'Salaries', function ($q) use($month_year, $st_company_id) {
						return $q->where(['month'=>$month_year[0],'year'=>$month_year[1],'Salaries.company_id'=>$st_company_id]);
					}
				)
			->group(['Employees.id'])->toArray();
			$this->set(compact('Employees'));
		}
		$this->set(compact('st_company_id'));
	}
}
