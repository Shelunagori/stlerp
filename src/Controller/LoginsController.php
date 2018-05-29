<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\I18n\Time;
class LoginsController extends AppController
{
	
	public function initialize()
	{
		parent::initialize();
		$this->eventManager()->off($this->Csrf);
	}
	public function index()
    {
       $this->viewBuilder()->layout('login_layout');
	   $number=2;
	   $login = $this->Logins->newEntity();
	   if ($this->request->is('post')) 
		{ 
			$username=$this->request->data["username"];
			$password=$this->request->data["password"];
			$query = $this->Logins->findAllByUsernameAndPassword($username, $password);
			$number = $query->count();
 			
			
			foreach ($query as $row) {
				  $login_id=$row["id"];
				 $employee_id=$row["employee_id"]; 
			}
			
				$Employee=$this->Logins->Employees->EmployeeCompanies->find()->where(['EmployeeCompanies.freeze'=>'0','EmployeeCompanies.employee_id'=>$employee_id]);
				$emp_avl=$Employee->count();
			
			if($number==1 && !empty($login_id) && $emp_avl > 0){ 
			
				$this->request->session()->write('st_login_id',$login_id);
				
				$Employee=$this->Logins->Employees->get($employee_id);
				
				$emp_mobile = $Employee->mobile;

				if($employee_id == 23){
					return $this->redirect(['action' => 'Switch-Company']);
				}
				/*if(!empty($emp_mobile)){
					return $this->redirect(['controller'=>'Logins', 'action' => 'SwitchCompany']);
				}*/
				 if(!empty($emp_mobile)){
					//return $this->redirect(['action' => 'Switch-Company']);
					return $this->redirect(['controller'=>'Logins', 'action' => 'otpCodeConfirm',$employee_id,$login_id]);
				}else{
					return $this->redirect(['controller'=>'Logins', 'action' => 'errorOtp',$employee_id]);
				} 
				
			}
		}
		
		$this->set(compact('login','number'));
		$this->set('_serialize', ['login']);
    }
	
	public function logout()
	{
		$session = $this->request->session();
		$st_login_id = $session->read('st_login_id');
		$login=$this->Logins->get($st_login_id);
		$query_1 = $this->Logins->Employees->query();
					$query_1->update()
						->set(['status' => 0])
						->where(['id' => $login->employee_id])
						->execute();
		$session = $this->request->session();
		$session->delete('st_login_id');
		return $this->redirect("/login");
	}
	
	public function checkSession()
	{
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		echo $st_company_id.'-'.$st_year_id; 
		exit;
	//	$this->set(compact('st_company_id'));
	}
	
	public function checkFySession()
	{
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_year_id = $session->read('st_year_id');
		echo $st_year_id; 
		exit;
	//	$this->set(compact('st_company_id'));
	}
	
	public function add()
    {
		$this->viewBuilder()->layout('index_layout');
		$login = $this->Logins->newEntity();
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		
        if ($this->request->is('post')) {
            $login = $this->Logins->patchEntity($login, $this->request->data);
			$emp_id=$login->employee_id;
			$EmployeeIdExist = $this->Logins->exists(['employee_id' => $emp_id]);
		
			if(!$EmployeeIdExist)
				{
					if ($this->Logins->save($login)) {
						$this->Flash->success(__('Login has been saved.'));
					} else {
						$this->Flash->error(__('The Login could not be saved. Please, try again.'));
					}
				}
			else{
				$this->Flash->error(__('This user have already login.'));
				}
        }
		$employees = $this->Logins->Employees->find('list');

				/* $employees=[];
		foreach($employeees as $emp){
			$employees[] = ['text'=>$emp->name,'value'=>$emp->id];
		} */
		$Logins = $this->paginate($this->Logins->find()->contain(['Employees'])->matching(
					'Employees.EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.freeze'=>0,'EmployeeCompanies.company_id'=>$st_company_id]);
					} 
				));
//pr($Logins->toArray()); exit;
        $this->set(compact('login','employees','Logins'));
        $this->set('_serialize', ['login']);
    }
	
	function SwitchCompany($company_id=null){
		
		$this->viewBuilder()->layout('login_layout');
		$session = $this->request->session();
		$st_login_id = $session->read('st_login_id');
		
		$login=$this->Logins->get($st_login_id);
		$Employee=$this->Logins->Employees->get($login->employee_id, [
					'contain' => ['Companies']
				]);
		/* if($Employee->status == '1' || $Employee->id == 23){ */
			if(!empty($company_id)){ 
			$this->request->allowMethod(['post', 'delete']);
			
			$this->request->session()->write('st_company_id',$company_id);
			/* $query_1 = $this->Logins->Employees->query();
					$query_1->update()
						->set(['status' => 1])
						->where(['id' => $login->employee_id])
						->execute(); */
			return $this->redirect(['controller'=>'FinancialYears','action' => 'selectCompanyYear']);
			
		}
		/*$Employee=$this->Logins->Employees->get($login->employee_id, [
						'contain' => ['Companies']
		]); */
		$Employee=$this->Logins->Employees->EmployeeCompanies->find()->where(['EmployeeCompanies.freeze'=>'0','EmployeeCompanies.employee_id'=>$login->employee_id])->contain(['Companies']);
		//pr($Employee->toArray()); exit;
		$this->set(compact('st_login_id','Employee'));
		/* }else{
			return $this->redirect(['controller'=>'Logins', 'action' => 'generateOtp',$login->employee_id,$login->id]);
		} */
		
	}
	
	function otpCodeConfirm($employee_id=null,$login_id=null)
	{
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$Employee=$this->Logins->Employees->get($employee_id);
		$status = $Employee->status;
		$Emp_name = $Employee->name;		
		$mobile_no = $Employee->mobile;	
		$randomString = rand(1000, 9999);
		/* if($status == '0'){ */
				
			$query = $this->Logins->Employees->query();
					$query->update()
						->set(['otp_no' => $randomString])
						->where(['id' => $employee_id])
						->execute();
				
			$sms=str_replace(' ', '+', 'Dear '.$Emp_name.', Your one time password is '.$randomString.'.');
			$working_key='A7a76ea72525fc05bbe9963267b48dd96';
			
			$sms_sender='MOGRAG';
			
			$ch = curl_init('http://103.39.134.40/api/mt/SendSMS?user=phppoetsit&password=9829041695&senderid='.$sms_sender.'&channel=Trans&DCS=0&flashsms=0&number='.$mobile_no.'&text='.$sms.'&route=7');
		  
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

			$response2=curl_exec($ch);
			if(!empty($response2)){
			
			}else if(empty($response2)){
				return $this->redirect(['controller'=>'Logins', 'action' => 'errorOtp',$employee_id]);
			}
			curl_close($ch);
		/* } */
		return $this->redirect(['controller'=>'Logins', 'action' => 'generateOtp',$employee_id,$login_id]);
	}
	
	
	function generateOtp($employee_id=null,$login_id=null){ 
	
		$this->viewBuilder()->layout('login_layout');
		$Employee=$this->Logins->Employees->get($employee_id, [
					'contain' => ['Companies']
				]);
			
			if ($this->request->is('put')) 
			{ 
				$otp_no=$this->request->data["otp_no"];
			
				/* if($Employee['status'] == '0' && $Employee['otp_no'] == $otp_no){ */
				if($Employee['otp_no'] == $otp_no){
				
					$time = Time::now();
					$user_logs = $this->Logins->UserLogs->newEntity();
					$user_logs->login_id = $login_id;
					$user_logs->datetime = $time;
					$this->Logins->UserLogs->save($user_logs);
					
					$count=0;
						
					foreach($Employee->companies as $company){
						$count++;
					}
					if($count==1){ 
						foreach($Employee->companies as $company){
							$this->request->session()->write('st_company_id',$company->id);
							break;
						}
						$query_1 = $this->Logins->Employees->query();
						
						return $this->redirect(['controller'=>'Financial-Years','action' => 'selectCompanyYear']);
					}
					else
					{
						/* $query_1 = $this->Logins->Employees->query();
						$query_1->update()
						->set(['status' => 1])
						->where(['id' => $employee_id])
						->execute(); */
						
						return $this->redirect(['action' => 'Switch-Company']);
					}
					
				}else{
					
					$this->Flash->error(__('Please Enter Correct OTP Code'));
				
				}
			}
		
			
		$this->set(compact('st_login_id','Employee'));
		$this->set('_serialize', ['Employee']);

	}
	
	function resendOtp($employee_id=null){
		$Employee=$this->Logins->Employees->get($employee_id);
		//$this->viewBuilder()->layout('login_layout');
		
		$Emp_name = $Employee->name;		
		$mobile_no = $Employee->mobile;	
		$mobile_no = $Employee->mobile;	
		$status = $Employee->status;	
		$randomString = rand(1000, 9999);
				$query = $this->Logins->Employees->query();
					$query->update()
						->set(['otp_no' => $randomString])
						->where(['id' => $employee_id])
						->execute();
						
		/* if($status == '0'){ */
			 $sms=str_replace(' ', '+', 'Dear '.$Emp_name.', Your one time password is '.$randomString.'.');
			  $working_key='A7a76ea72525fc05bbe9963267b48dd96';
			$sms_sender='MOGRAG';
			$ch = curl_init('http://103.39.134.40/api/mt/SendSMS?user=phppoetsit&password=9829041695&senderid='.$sms_sender.'&channel=Trans&DCS=0&flashsms=0&number='.$mobile_no.'&text='.$sms.'&route=7');
			  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response2=curl_exec($ch);
			if(!empty($response2)){
			
			}else if(empty($response2)){
				return $this->redirect(['controller'=>'Logins', 'action' => 'errorOtp',$employee_id]);
			}
			curl_close($ch);
		/* }	 */
		$this->set(compact('st_login_id','Employee'));
		$this->set('_serialize', ['Employee']);
	}
	
	function errorOtp($employee_id=null){
		$this->viewBuilder()->layout('login_layout');
		
		$session = $this->request->session();
		$st_login_id = $session->read('st_login_id');
		$Employee=$this->Logins->Employees->get($employee_id);
		
		$this->set(compact('st_login_id','Employee'));
	}

	public function delete($id=null){
			$this->request->allowMethod(['post', 'delete']);
		$this->Logins->UserRights->deleteAll(['login_id' => $id]);
		$login = $this->Logins->get($id);
			if ($this->Logins->delete($login)) {
				$this->Flash->success(__('The User Login  has been deleted.'));
			} else {
				$this->Flash->error(__('The User Login could not be deleted. Please, try again.'));
			}
			return $this->redirect(['controller'=>'Logins','action' => 'Add']);
	}
	public function dashbord()
    {
       $this->viewBuilder()->layout('index_layout');
	   $session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$employee_id=$this->viewVars['s_employee_id'];
	   $quotations = $this->Logins->Quotations->find()->contain(['QuotationRows'=>['Items']])->where(['company_id'=>$st_company_id,'status'=>'Pending','Quotations.revision '=> 0])->order(['Quotations.id' => 'DESC']);
	  // pr($quotations->toArray()); exit;
	   $quotations = $quotations->select(['ct' => $quotations->func()->count('Quotations.id')])->first();
	   $pending_quotation=$quotations->ct;
	   
		$first_day_this_month = date('Y-m-d',strtotime(date('01-m-Y'))); 
		$last_day_this_month  = date('t-m-Y');
		$last_day_this_month = date('Y-m-d',strtotime($last_day_this_month)); 
		
		$query=$this->Logins->Invoices->find();
		$query->select(['sum' => $query->func()->sum('Invoices.grand_total')])
			->where(['date_created >='=>$first_day_this_month,'date_created <='=>$last_day_this_month,'company_id'=>$st_company_id])->toArray(); 
		 $monthelySaleForInvoice=$query->first()->sum;
		 
		$query=$this->Logins->SalesOrders->find();
		$query->select(['sum' => $query->func()->sum('SalesOrders.total')])
			->where(['created_on >='=>$first_day_this_month,'created_on <='=>$last_day_this_month,'company_id'=>$st_company_id])->toArray();
		$monthelySaleForSO=$query->first()->sum;
		
		$query=$this->Logins->Quotations->find();
		$query->select(['sum' => $query->func()->sum('Quotations.total')])
			->where(['created_on >='=>$first_day_this_month,'created_on <='=>$last_day_this_month,'company_id'=>$st_company_id])->toArray();
		$monthelySaleForQO=$query->first()->sum;
		
		
		
		
		$query1=$this->Logins->Invoices->LedgerAccounts->find();
		$query1->contain(['ReferenceDetails'=>function($q)use($query1){
			return $q->select(['ledger_account_id','total_debit'=>$query1->func()->sum('debit'),'ledger_account_id','total_credit'=>$query1->func()->sum('credit')])->group(['ledger_account_id']);
		}]);
		$query1->where(['company_id'=>$st_company_id,'source_model'=>'Customers']);
		
		$Total_payble=0;
		
		foreach($query1 as $d){
			@$Total_payble+=@$d->reference_details[0]->total_debit-@$d->reference_details[0]-total_credit;
		}
		
		$query2=$this->Logins->Invoices->LedgerAccounts->find();
		$query2->contain(['ReferenceDetails'=>function($q)use($query2){
			return $q->select(['ledger_account_id','total_debit'=>$query2->func()->sum('debit'),'ledger_account_id','total_credit'=>$query2->func()->sum('credit')])->group(['ledger_account_id']);
		}]);
		$query2->where(['company_id'=>$st_company_id,'source_model'=>'Vendors']);
		
		$Total_recivable=0;
		
		foreach($query2 as $d){
			@$Total_recivable+=@$d->reference_details[0]->total_debit-@$d->reference_details[0]-total_credit;
		}
	  // pr($Total_recivable);exit;
	   
	   
	   $SalesOrderRows = $this->Logins->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->Logins->SalesOrders->find();
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items']])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id])
					->order(['SalesOrders.id'=>'DESC']);
	   $total_sales=[]; $total_qty=[];
		foreach($salesOrders as $salesorder){
			$total_sales[$salesorder->id]=$salesorder->total_sales;
			foreach($salesorder->sales_order_rows as $sales_order_row){
				foreach($sales_order_row->invoice_rows as $invoice_row){
						if(sizeof($invoice_row) > 0){
							@$total_qty[$salesorder->id]+=$invoice_row->quantity;
						}
				}
			}
		}
		$pending_sales=0;
		foreach ($salesOrders as $salesOrder){ 
			if(@$total_sales[@$salesOrder->id] > @$total_qty[@$salesOrder->id]){ 
				++$pending_sales;
			}
		}
		
		$invoices=[];
		$invoice1=$this->Logins->Invoices->find()->contain(['Customers','SalesOrders','InvoiceRows'=>['Items'=>function ($q) {
			return $q->where(['source !='=>'Purchessed']);
			},'SalesOrderRows'=>function ($q) {
			return $q->where(['SalesOrderRows.source_type !='=>'Purchessed']);
			}
			]])
			->where(['Invoices.company_id'=>$st_company_id])
			->order(['Invoices.id' => 'DESC']);
		// pr($invoice1->toArray()); exit;	
			foreach($invoice1 as $invoice){
				$AccountGroupsexists = $this->Logins->Invoices->Ivs->exists(['Ivs.invoice_id' => $invoice->id]);
				if(!$AccountGroupsexists){ // pr($invoice);
					$invoices[]=$invoice;
				}
			}
			$pending_invoice=0;
			foreach($invoices as $invoice){  
				 if(sizeof($invoice->invoice_rows) > 0){
						++$pending_invoice;
				 }
			}
		$PurchaseOrderRows = $this->Logins->PurchaseOrders->PurchaseOrderRows->find();
				$purchaseOrders = $this->Logins->PurchaseOrders->find();
				$purchaseOrders->select(['id','total_sales'=>$PurchaseOrderRows->func()->sum('PurchaseOrderRows.quantity')])
				->innerJoinWith('PurchaseOrderRows')
				->group(['PurchaseOrders.id'])
				->contain(['Companies', 'Vendors','PurchaseOrderRows'=>['Items','GrnRows']])
				->autoFields(true)
				->where(['PurchaseOrders.company_id'=>$st_company_id])
				->order(['PurchaseOrders.id'=>'DESC']);
				$total_sales=[]; $total_qty=[];
				
		foreach($purchaseOrders as $salesorder){
			$total_sales[$salesorder->id]=$salesorder->total_sales;
			foreach($salesorder->purchase_order_rows as $sales_order_row){
				foreach($sales_order_row->grn_rows as $invoice_row){ 
						if(sizeof($invoice_row) > 0){
							@$total_qty[$salesorder->id]+=$invoice_row->quantity;
						}
				}
			}
		}
		$pending_po=0;
		foreach ($purchaseOrders as $salesOrder){ 
			if(@$total_sales[@$salesOrder->id] > @$total_qty[@$salesOrder->id]){ 
				++$pending_po;
			}
		}
		$grns = $this->Logins->Grns->find()->where(['status'=>'Pending'])->where(['Grns.company_id'=>$st_company_id]);
		$grns = $grns->select(['ct' => $grns->func()->count('Grns.id')])->first();
		$pending_grn=$grns->ct;
		if($employee_id==16 || $employee_id==23){
			$PendingleaveRequests = $this->Logins->LeaveApplications->find()->contain(['Employees','LeaveTypes'])->where(['leave_status'=>'Pending', 'company_id'=>$st_company_id])->toArray();
			
			$PendingTravelRequests = $this->Logins->TravelRequests->find()->where(['TravelRequests.status'=>'Pending'])->contain(['Employees'])->toArray();
			$PendingLoanApplications = $this->Logins->LoanApplications->find()->where(['LoanApplications.status'=>'Pending','company_id'=>$st_company_id])->contain(['Employees'])->toArray();
			//pr($PendingLoanApplications); exit;
			
		}
		

		$PendingleaveStatus = $this->Logins->LeaveApplications->find()->where(['employee_id'=>$employee_id])->contain(['empData'])->toArray();

		$PendingTravelRequestStatus = $this->Logins->TravelRequests->find()->where(['TravelRequests.employee_id'=>$employee_id,'TravelRequests.status'=>'Pending'])->contain(['Employees','empData'])->toArray();
		/* pr($employee_id);
		pr($PendingTravelRequestStatus); exit; */
	   $this->set(compact('st_company_id','pending_quotation','pending_sales','pending_invoice','pending_po','pending_grn','employee_id','PendingleaveStatus','PendingleaveRequests','PendingTravelRequests','PendingTravelRequestStatus','monthelySaleForInvoice','monthelySaleForSO','monthelySaleForQO','PendingLoanApplications'));
		
    }
}


