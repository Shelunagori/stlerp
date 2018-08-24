<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Employees Controller
 *
 * @property \App\Model\Table\EmployeesTable $Employees
 */
class EmployeesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($status = null)
    {
        $url = $this->request->here();
        $url = parse_url($url, PHP_URL_QUERY);
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
        $this->viewBuilder()->layout('index_layout');
        $this->paginate = [
            'contain' => ['Departments', 'Designations','EmployeeCompanies'=>function ($q) use($st_company_id) {
						   return $q
								->where(['EmployeeCompanies.company_id'=>$st_company_id]);
						}]
        ];

        $where = [];
        $employee_name = $this->request->query('employee_name');
        $department_name = $this->request->query('department_name');

        $this->set(compact('employee_name', 'department_name'));
        if (!empty($employee_name)) {
            $where['Employees.name LIKE'] = '%' . $employee_name . '%';
        }
        if (!empty($department_name)) {
            $where['Departments.name LIKE'] = '%' . $department_name . '%';
        }
        $employees = $this->paginate($this->Employees->find()->where($where)->order(['Employees.name' => 'ASC']));

		
        $this->set(compact('employees', 'status'));
        $this->set('_serialize', ['employees']);
        $this->set(compact('url'));
    }
	
	function listForSalary(){
        $this->viewBuilder()->layout('index_layout');
		
		$employees=$this->Employees->find()->select(['id','name','salary_company_id'])->order(['name'=>'ASC'])
					->matching(
						'EmployeeCompanies', function ($q)  {
							return $q->where(['EmployeeCompanies.freeze' =>0]);
						}
					)->group(['Employees.id']);
		
		$companies=$this->Employees->Companies->find('list');
		$this->set(compact('employees', 'companies'));
	}
	
	function saveSalaryInfo($c_id,$employee_id){
		$Employee=$this->Employees->get($employee_id);
		$Employee->salary_company_id=$c_id;
		$this->Employees->save($Employee);
		exit;
	}

    /**
     * View method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->viewBuilder()->layout('index_layout');
        $employee = $this->Employees->get($id, [
            'contain' => ['Departments', 'Designations', 'EmployeeContactPersons', 'EmployeeFamilyMembers', 'EmployeeEmergencyDetails', 'EmployeeReferenceDetails', 'EmployeeWorkExperiences']
        ]);

		if($employee->home_state){
			$state=$this->Employees->States->find()->where(['id'=>$employee->home_state])->first();
			$stateName=$state->name;
		}else{
			$stateName="";
		}
		
		if($employee->present_state){
			$state=$this->Employees->States->find()->where(['id'=>$employee->present_state])->first();
			$presentStateName=$state->name;
		}else{
			$presentStateName="";
		}
		
		if($employee->permanent_state){
			$state=$this->Employees->States->find()->where(['id'=>$employee->permanent_state])->first();
			$permanentStateName=$state->name;
		}else{
			$permanentStateName="";
		}
		
		if($employee->nominee_state){
			$state=$this->Employees->States->find()->where(['id'=>$employee->nominee_state])->first();
			$nomineeStateName=$state->name;
		}else{
			$nomineeStateName="";
		}
		
		if($employee->reporting_to){
			$em=$this->Employees->find()->where(['id'=>$employee->reporting_to])->first();
			$reportingTo=$em->name;
		}else{
			$reportingTo="";
		}
		
		$this->set(compact('employee', 'stateName', 'presentStateName', 'permanentStateName', 'nomineeStateName', 'reportingTo'));
        $this->set('_serialize', ['employee']);
    }
	
	

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->viewBuilder()->layout('index_layout');
        $employee = $this->Employees->newEntity();
        if ($this->request->is('post')) {
            $employee = $this->Employees->patchEntity($employee, $this->request->data);
            $employee->dob = date("Y-m-d", strtotime($employee->dob));
            $employee->date_of_anniversary = date("Y-m-d", strtotime($employee->date_of_anniversary));
            $employee->join_date = date("Y-m-d", strtotime($employee->join_date));
            $employee->permanent_join_date = date("Y-m-d", strtotime($employee->permanent_join_date));

            $file = $this->request->data['signature'];
            $ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
            $arr_ext = array('jpg', 'jpeg', 'png'); //set allowed extensions
            $setNewFileName = uniqid();
            $employee->signature = $setNewFileName . '.' . $ext;
            if (in_array($ext, $arr_ext)) {
                move_uploaded_file($file['tmp_name'], WWW_ROOT . '/signatures/' . $setNewFileName . '.' . $ext);
            }
			
            if ($this->Employees->save($employee)) {


                foreach ($employee->companies as $data) 
				{
                    $ledgerAccount = $this->Employees->LedgerAccounts->newEntity();
                    $ledgerAccount->account_second_subgroup_id = $employee->account_second_subgroup_id;
                    $ledgerAccount->name = $employee->name;
                    $ledgerAccount->source_model = 'Employees';
                    $ledgerAccount->source_id = $employee->id;
                    $ledgerAccount->company_id = $data->id;
                    $this->Employees->LedgerAccounts->save($ledgerAccount);
					//
					$VouchersReferences = $this->Employees->VouchersReferences->find()->where(['company_id'=>$data->id,'voucher_entity'=>'PaymentVoucher -> Paid To'])->first();
					$voucherLedgerAccount = $this->Employees->VoucherLedgerAccounts->newEntity();
					$voucherLedgerAccount->vouchers_reference_id =$VouchersReferences->id;
					$voucherLedgerAccount->ledger_account_id =$ledgerAccount->id;
					$this->Employees->VoucherLedgerAccounts->save($voucherLedgerAccount);
					//
					$VouchersReferences = $this->Employees->VouchersReferences->find()->where(['company_id'=>$data->id,'voucher_entity'=>'Receipt Voucher -> Received From'])->first();
					$voucherLedgerAccount = $this->Employees->VoucherLedgerAccounts->newEntity();
					$voucherLedgerAccount->vouchers_reference_id =$VouchersReferences->id;
					$voucherLedgerAccount->ledger_account_id =$ledgerAccount->id;
					$this->Employees->VoucherLedgerAccounts->save($voucherLedgerAccount);
					
					//
					$VouchersReferences = $this->Employees->VouchersReferences->find()->where(['company_id'=>$data->id,'voucher_entity'=>'Non Print PaymentVoucher -> Paid To'])->first();
					$voucherLedgerAccount = $this->Employees->VoucherLedgerAccounts->newEntity();
					$voucherLedgerAccount->vouchers_reference_id =$VouchersReferences->id;
					$voucherLedgerAccount->ledger_account_id =$ledgerAccount->id;
					$this->Employees->VoucherLedgerAccounts->save($voucherLedgerAccount);
				
                }


                $this->Flash->success(__('The employee has been saved.'));
                return $this->redirect(['action' => 'index']);

            } else {
                $this->Flash->error(__('The employee could not be saved. Please, try again.'));
            }
        }
		
		$states=[];
		$state_details=$this->Employees->States->find();
		if(sizeof($state_details)>0)
		{
			foreach($state_details as $state)
			{ 
				$name = $state->name.' ( '.$state->state_code.' )';
				$states[] = ['value'=>$state->id,'text'=>$name];
			}
		}
		
        $departments = $this->Employees->Departments->find('list')->order(['Departments.name' => 'ASC']);
        $designations = $this->Employees->Designations->find('list')->order(['Designations.name' => 'ASC']);
        $AccountCategories = $this->Employees->AccountCategories->find('list')->order(['AccountCategories.name' => 'ASC']);
        $Companies = $this->Employees->Companies->find('list');
        $Employees = $this->Employees->find('list');
        $this->set(compact('employee', 'departments', 'designations', 'AccountCategories', 'Companies', 'states', 'Employees'));
        $this->set('_serialize', ['employee']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->viewBuilder()->layout('index_layout');
        $employee = $this->Employees->get($id, [
            'contain' => ['EmployeeContactPersons', 'Companies']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employee = $this->Employees->patchEntity($employee, $this->request->data);
            $employee->dob = date("Y-m-d", strtotime($employee->dob));
            $employee->date_of_anniversary = date("Y-m-d", strtotime($employee->date_of_anniversary));
            $employee->join_date = date("Y-m-d", strtotime($employee->join_date));
            $employee->permanent_join_date = date("Y-m-d", strtotime($employee->permanent_join_date));

            $file = $this->request->data['signature'];
			
            if (!empty($file['name'])) {
                $ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
                $arr_ext = array('png','jpg'); //set allowed extensions
                $setNewFileName = uniqid();

                $employee->signature = $setNewFileName . '.' . $ext;
                @unlink(WWW_ROOT . '/signatures/' . $employee->getOriginal('signature'));
                if (in_array($ext, $arr_ext)) { echo WWW_ROOT . '/signatures/' . $setNewFileName . '.' . $ext;
                    move_uploaded_file($file['tmp_name'], WWW_ROOT . '/signatures/' . $setNewFileName . '.' . $ext);
                }
				pr($file); 
            } else {
                $employee->signature = $employee->getOriginal('signature');
            }
			//pr($employee); exit;
            if ($this->Employees->save($employee)) {
                $query = $this->Employees->LedgerAccounts->query();
                $query->update()
                    ->set(['account_second_subgroup_id' => $employee->account_second_subgroup_id])
                    ->where(['id' => $employee->ledger_account_id])
                    ->execute();
                $this->Flash->success(__('The employee has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
				pr($employee); exit;
                $this->Flash->error(__('The employee could not be saved. Please, try again.'));
            }
        }
		
		$states=[];
		$state_details=$this->Employees->States->find();
		if(sizeof($state_details)>0)
		{
			foreach($state_details as $state)
			{ 
				$name = $state->name.' ( '.$state->state_code.' )';
				$states[] = ['value'=>$state->id,'text'=>$name];
			}
		}
		
        $departments = $this->Employees->Departments->find('list')->order(['Departments.name' => 'ASC']);
        $designations = $this->Employees->Designations->find('list')->order(['Designations.name' => 'ASC']);
        $AccountCategories = $this->Employees->AccountCategories->find('list')->order(['AccountCategories.name' => 'ASC']);
        $AccountGroups = $this->Employees->AccountGroups->find('list');
        $AccountFirstSubgroups = $this->Employees->AccountFirstSubgroups->find('list');
        $AccountSecondSubgroups = $this->Employees->AccountSecondSubgroups->find('list');
        $Companies = $this->Employees->Companies->find('list');
        $Employees = $this->Employees->find('list');
        $this->set(compact('employee', 'departments', 'designations', 'AccountCategories', 'AccountGroups', 'AccountFirstSubgroups', 'AccountSecondSubgroups', 'Companies', 'states', 'Employees'));
        $this->set('_serialize', ['employee']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $Quotationsexists = $this->Employees->Quotations->exists(['employee_id' => $id]);
        $SalesOrdersexists = $this->Employees->SalesOrders->exists(['employee_id' => $id]);
        $Invoicesexists = $this->Employees->Invoices->exists(['employee_id' => $id]);


        if (!$Quotationsexists) {

            $login = $this->Employees->Logins->find()->where(['employee_id' => $id])->first();
            $this->Employees->Logins->UserRights->deleteAll(['login_id' => $id]);
            $employee = $this->Employees->get($id);
            // 	pr($login->id);exit;
            $login_data = $this->Employees->Logins->get($login->id);
            //pr($login_data);exit;
            $this->Employees->Logins->delete($login_data);
            if ($this->Employees->delete($employee)) {

                $this->Flash->success(__('The employee has been deleted.'));
            } else {
                $this->Flash->error(__('The employee could not be deleted. Please, try again.'));
            }
        } elseif ($Quotationsexists) {
            $this->Flash->error(__('Once the quotations has created with employees, the employees cannot be deleted.'));
        } elseif ($SalesOrdersexists) {
            $this->Flash->error(__('Once the sales-order has created with sales-order, the employees cannot be deleted.'));
        } elseif ($Invoicesexists) {
            $this->Flash->error(__('Once the sales-order has created with invoice, the employees cannot be deleted.'));
        }


        return $this->redirect(['action' => 'index']);
    }

    public function EditCompany($employee_id = null)
    {
        $this->viewBuilder()->layout('index_layout');
        $Companies = $this->Employees->Companies->find();
        //pr($Companies->toArray()); exit;
        $Company_array = [];
        $Company_array1 = [];
		$Company_array2=[];
        foreach ($Companies as $Company) {
            $employee_Company_exist = $this->Employees->EmployeeCompanies->exists(['employee_id' => $employee_id, 'company_id' => $Company->id]);

            if ($employee_Company_exist) {
				$employee_data= $this->Employees->EmployeeCompanies->find()->where(['employee_id' => $employee_id,'company_id'=>$Company->id])->first();
                $Company_array[$Company->id] = 'Yes';
                $Company_array1[$Company->id] = $Company->name;
				$Company_array2[$Company->id]=$employee_data->freeze;
            } else {
                $Company_array[$Company->id] = 'No';
                $Company_array1[$Company->id] = $Company->name;
				$Company_array2[$Company->id]='1';

            }
        }
        $employe_data = $this->Employees->get($employee_id,
							['contain'=>'EmployeeCompanies']
		);
		$emp_data=[];$emp_effective_date=[];
			foreach($employe_data->employee_companies as $data){
				
				$emp_data[$data['company_id']]=$data['freeze'];
				$emp_effective_date[$data['company_id']]=$data['effective_date'];
			}
		
		//pr($emp_data);exit;
        $this->set(compact('employe_data', 'Companies', 'customer_Company', 'Company_array', 'employee_id', 'Company_array1','Company_array2','emp_data','emp_effective_date'));

    }


    public function updateffectivedate($employee_id = null,$effective_date = null,$company_id = null){
		$query_update = $this->Employees->EmployeeCompanies->query();
							$query_update->update()
								->set(['effective_date'=>date('Y-m-d',strtotime($effective_date))])
								->where(['company_id' => $company_id,'employee_id'=>$employee_id])
								->execute();
		exit;
	}
    public function CheckCompany($company_id = null, $employee_id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $this->request->allowMethod(['post', 'delete']);
        $employees_ledger = $this->Employees->LedgerAccounts->find()->where(['source_model' => 'Employees', 'source_id' => $employee_id, 'company_id' => $company_id])->first();
		
        $ledgerexist = $this->Employees->Ledgers->exists(['ledger_account_id' => $employees_ledger->id]);
        if (!$ledgerexist) {
            $customer_Company_dlt = $this->Employees->EmployeeCompanies->find()->where(['EmployeeCompanies.employee_id' => $employee_id, 'company_id' => $company_id])->first();
            $customer_ledger_dlt = $this->Employees->LedgerAccounts->find()->where(['source_model' => 'Employees', 'source_id' => $employee_id, 'company_id' => $company_id])->first();
			
            $VoucherLedgerAccountsexist = $this->Employees->VoucherLedgerAccounts->exists(['ledger_account_id' => $employees_ledger->id]);

            if ($VoucherLedgerAccountsexist) {
                $Voucherref = $this->Employees->VouchersReferences->find()->contain(['VoucherLedgerAccounts'])->where(['VouchersReferences.company_id' => $company_id]);
                foreach ($Voucherref as $Voucherref) {
                    foreach ($Voucherref->voucher_ledger_accounts as $voucher_ledger_account) {
                        if ($voucher_ledger_account->ledger_account_id == $employees_ledger->id) {
                            $this->Employees->VoucherLedgerAccounts->delete($voucher_ledger_account);
                        }
                    }

                }

            }

            $this->Employees->EmployeeCompanies->delete($customer_Company_dlt);
            $this->Employees->LedgerAccounts->delete($customer_ledger_dlt);
            return $this->redirect(['action' => 'EditCompany/' . $employee_id]);

        } else {
            $this->Flash->error(__('Company Can not Deleted'));
            return $this->redirect(['action' => 'EditCompany/' . $employee_id]);
        }
    }

    public function AddCompany($company_id = null, $employee_id = null)
    {
        $this->viewBuilder()->layout('index_layout');
        //pr($company_id);

        $EmployeeCompany = $this->Employees->EmployeeCompanies->newEntity();
        $EmployeeCompany->company_id = $company_id;
        $EmployeeCompany->employee_id = $employee_id;

        $this->Employees->EmployeeCompanies->save($EmployeeCompany);

        $employee_details = $this->Employees->get($employee_id);
        //pr($employee_details); exit;
        $ledgerAccount = $this->Employees->LedgerAccounts->newEntity();
        $ledgerAccount->account_second_subgroup_id = $employee_details->account_second_subgroup_id;
        $ledgerAccount->name = $employee_details->name;
        //$ledgerAccount->alias = $employee_details->alias;
        //$ledgerAccount->bill_to_bill_account = $employee_details->bill_to_bill_account;
        $ledgerAccount->source_model = 'Employees';
        $ledgerAccount->source_id = $employee_details->id;
        $ledgerAccount->company_id = $company_id;
        //pr($ledgerAccount); exit;
        $this->Employees->LedgerAccounts->save($ledgerAccount);
		//
		$VouchersReferences = $this->Employees->VouchersReferences->find()->where(['company_id'=>$company_id,'voucher_entity'=>'PaymentVoucher -> Paid To'])->first();
					$voucherLedgerAccount = $this->Employees->VoucherLedgerAccounts->newEntity();
					$voucherLedgerAccount->vouchers_reference_id =$VouchersReferences->id;
					$voucherLedgerAccount->ledger_account_id =$ledgerAccount->id;
					$this->Employees->VoucherLedgerAccounts->save($voucherLedgerAccount);
					//
					$VouchersReferences = $this->Employees->VouchersReferences->find()->where(['company_id'=>$company_id,'voucher_entity'=>'Receipt Voucher -> Received From'])->first();
				$voucherLedgerAccount = $this->Employees->VoucherLedgerAccounts->newEntity();
				$voucherLedgerAccount->vouchers_reference_id =$VouchersReferences->id;
				$voucherLedgerAccount->ledger_account_id =$ledgerAccount->id;
				$this->Employees->VoucherLedgerAccounts->save($voucherLedgerAccount);

        return $this->redirect(['action' => 'EditCompany/' . $employee_id]);
    }
	
	public function EmployeeFreeze($company_id=null,$employee_id=null,$freeze=null)
	{
		$query2 = $this->Employees->EmployeeCompanies->query();
				$query2->update();
		if($freeze == 0){
			$query2->set(['freeze' => $freeze,'effective_date'=>'0000-00-00']);
		}else{
			$query2->set(['freeze' => $freeze]);
		}
			$query2->where(['employee_id' => $employee_id,'company_id'=>$company_id])
			->execute();

		return $this->redirect(['action' => 'EditCompany/'.$employee_id]);
	}
	 
	 
	 
	 public function employeeInformation($id=null)
    {
			
        $this->viewBuilder()->layout('index_layout');
		$id = $this->request->query('id');
		$employee = $this->Employees->get($id, [
            'contain' => ['Departments', 'Designations', 'EmployeeContactPersons']
        ]);
	//pr($employee); exit;

        $this->set('employee', $employee);
        $this->set('_serialize', ['employee']);
       
	}
	
	 public function employeeList()
    {
        $url = $this->request->here();
        $url = parse_url($url, PHP_URL_QUERY);

        $this->viewBuilder()->layout('index_layout');
        $this->paginate = [
            'contain' => ['Departments', 'Designations']
        ];

        $where = [];
        $employee_name = $this->request->query('employee_name');
        $department_name = $this->request->query('department_name');

        $this->set(compact('employee_name', 'department_name'));
        if (!empty($employee_name)) {
            $where['Employees.name LIKE'] = '%' . $employee_name . '%';
        }
        if (!empty($department_name)) {
            $where['Departments.name LIKE'] = '%' . $department_name . '%';
        }
        $employees = $this->paginate($this->Employees->find()->where($where)->order(['Employees.name' => 'ASC']));


        $this->set(compact('employees', 'status'));
        $this->set('_serialize', ['employees']);
        $this->set(compact('url'));
    }
}
