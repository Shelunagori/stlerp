<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
/**
 * LeaveApplications Controller
 *
 * @property \App\Model\Table\LeaveApplicationsTable $LeaveApplications
 */
class LeaveApplicationsController extends AppController
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
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$this->viewBuilder()->layout('index_layout');
		
		
		
		$FromDate=$this->request->query('FromDate'); $FromDate1=date('Y-m-d',strtotime($FromDate));
		$ToDate=$this->request->query('ToDate'); $ToDate1=date('Y-m-d',strtotime($ToDate));
		$this->set(compact('FromDate', 'ToDate'));
		$dates=$this->date_range($FromDate, $ToDate, $step = '+1 day', $output_format = 'Y-m-d' );
		
		$where['company_id']=$st_company_id;
		$q=[];
		foreach($dates as $date){
			$q['OR'][]=[
					'from_leave_date >=' => $date,
					'to_leave_date <=' => $date
				];
		}
		if(!$FromDate or !$ToDate){
			$q['OR']=[];
		}
		
		$employee_id=$this->request->query('employee_id');
		if(!empty($employee_id)){
			$where['Employees.id']=$employee_id;
		}
		$this->set(compact('employee_id'));
		
		$status=$this->request->query('status');
		if(!empty($status)){
			$where['LeaveApplications.leave_status']=$status;
		}
		$this->set(compact('status'));
		
		$empData=$this->LeaveApplications->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
		
		if($empData->department->name=='HR & Administration' || $empData->designation->name=='Director'){ 
			$leaveApplications = $this->paginate($this->LeaveApplications->find()->contain(['Employees'])->where($where)->where($q));
		}else{
			$leaveApplications = $this->paginate($this->LeaveApplications->find()->contain(['Employees'])->where(['employee_id'=>$s_employee_id]));
		}
		
		$Employees=	$this->LeaveApplications->Employees->find('list')
					->matching(
						'EmployeeCompanies', function ($q)  {
							return $q->where(['EmployeeCompanies.freeze' =>0]);
						}
					);
		//pr($leaveApplications); exit;
       // $leaveApplications = $this->paginate($this->LeaveApplications->find()->contain(['LeaveTypes']));
        $this->set(compact('leaveApplications', 'empData', 'Employees'));
        $this->set('_serialize', ['leaveApplications']);
    }

    /**
     * View method
     *
     * @param string|null $id Leave Application id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

		
/* 	public function checkData($id = null,$sellDate=null,$single_multiple=null){
		$sellDate = date("Y-m-d",strtotime($sellDate));  
		
		$LeaveApplication = $this->LeaveApplications->find()->where(['LeaveApplications.employee_id'=>$id])->toArray();
		
		 pr($LeaveApplication); exit;
		exit;
	} */
	
		


	public function approve($id = null){
		$LeaveApplication = $this->LeaveApplications->get($id);
		 $this->set(compact('LeaveApplication','id'));
	}
    public function view($id = null)
    {
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$this->viewBuilder()->layout('index_layout');
        $leaveApplication = $this->LeaveApplications->get($id, [
            'contain' => ['Employees','LeaveTypes']
        ]);

        $this->set('leaveApplication', $leaveApplication);
        $this->set('_serialize', ['leaveApplication']);
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
		$st_year_id = $session->read('st_year_id');
		
		$financial_year = $this->LeaveApplications->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->LeaveApplications->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->LeaveApplications->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		
		 $SessionCheckDate = $this->FinancialYears->get($st_year_id);
		   $fromdate1 = date("Y-m-d",strtotime($SessionCheckDate->date_from));   
		   $todate1 = date("Y-m-d",strtotime($SessionCheckDate->date_to)); 
		   $tody1 = date("Y-m-d");

		   $fromdate = strtotime($fromdate1);
		   $todate = strtotime($todate1); 
	       $tody = strtotime($tody1);

		  if($fromdate < $tody || $todate > $tody)
		   {
			 if($SessionCheckDate['status'] == 'Open')
			 { $chkdate = 'Found'; }
			 else
			 { $chkdate = 'Not Found'; }

		   }
		   else
			{
				$chkdate = 'Not Found';	
			}
		////ends code for financial year
		 
		 
		$s_employee_id=$this->viewVars['s_employee_id'];
		$empData=$this->LeaveApplications->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
		//pr($empData); exit;
        $leaveApplication = $this->LeaveApplications->newEntity();
        if ($this->request->is('post')) {
			$files=$this->request->data['supporting_attached']; 
            $leaveApplication = $this->LeaveApplications->patchEntity($leaveApplication, $this->request->data);
			$leaveApplication->supporting_attached = $files['name'];
			$attache = $this->request->data['supporting_attached'];
			$EmployeeHierarchies=$this->LeaveApplications->EmployeeHierarchies->find()->contain(['ParentAccountingGroups'])->where(['EmployeeHierarchies.employee_id'=>$s_employee_id])->first();
			
			
			
			$leaveApplication->employee_id=$leaveApplication->employee_id;
			$leaveApplication->submission_date=date('Y-m-d');
			$leaveApplication->from_leave_date =date('Y-m-d', strtotime($leaveApplication->from_leave_date)); 
			$leaveApplication->to_leave_date =date('Y-m-d', strtotime($leaveApplication->to_leave_date)); 
			$leaveApplication->approve_leave_from =date('Y-m-d', strtotime($leaveApplication->from_leave_date)); 
			$leaveApplication->approve_leave_to =date('Y-m-d', strtotime($leaveApplication->to_leave_date)); 
			
			if($leaveApplication->single_multiple=='Single'){
				$leaveApplication->to_leave_date=$leaveApplication->from_leave_date;
				$leaveApplication->to_full_half=$leaveApplication->from_full_half;
			}
			
			$from_leave_date = strtotime($leaveApplication->from_leave_date); 
			$to_leave_date =strtotime($leaveApplication->to_leave_date); 
			$datediff =$to_leave_date - $from_leave_date;
			$leaveApplication->day_no=round($datediff / (60 * 60 * 24))+1;  //pr($leaveApplication); exit;
			if($leaveApplication->single_multiple=='Single'){
				if($leaveApplication->from_full_half!='Full Day'){
					$leaveApplication->day_no-=0.5;
				}
			}else{
				if($leaveApplication->from_full_half=='Second Half Day'){
					$leaveApplication->day_no-=0.5;
				}
				if($leaveApplication->to_full_half=='First Half Day'){
					$leaveApplication->day_no-=0.5;
				}
			}
			//pr($leaveApplication->employee_id);
			if($leaveApplication->single_multiple=="Single"){
				$dates=$this->date_range($leaveApplication->from_leave_date, $leaveApplication->from_leave_date, $step = '+1 day', $output_format = 'Y-m-d' );
			}else{
				$dates=$this->date_range($leaveApplication->from_leave_date, $leaveApplication->to_leave_date, $step = '+1 day', $output_format = 'Y-m-d' );
			}
			
			foreach($dates as $data){
				$c=$this->LeaveApplications->find()->where(['LeaveApplications.employee_id'=>$leaveApplication->employee_id,'LeaveApplications.approve_leave_from <='=>$data, 'LeaveApplications.approve_leave_to >='=>$data])->count(); 
				
				if($c>0){
					$this->Flash->error(__('Leave Application cannot be fullfilled with duplicate dates.'));
					goto a;
				}
			}
			
			foreach($dates  as $date){
				$c=$this->LeaveApplications->TravelRequests->find()->where(['TravelRequests.employee_id'=>$leaveApplication->employee_id, 'travel_mode_from_date <='=>$date, 'travel_mode_to_date >='=>$date])->count();
				if($c>0){
					$this->Flash->error(__('Travel request Applied in these dates.'));
					goto a;
				}
			}
			$leaveApplication->company_id=$st_company_id;
			$leaveApplication->financial_year_id=$st_year_id;
            if ($this->LeaveApplications->save($leaveApplication)) {
				$target_path = 'attached_file';
				$file_name   = $_FILES['supporting_attached']['name'];
				//echo $to_path     = $target_path.$attache['name'];
				if(move_uploaded_file($files['tmp_name'], $target_path.'/'.$file_name))
				{
					$this->Flash->success(__('The leave application has been saved.'));
					return $this->redirect(['action' => 'index']);
				}
				else
				{
					$this->Flash->success(__('The leave application has been saved.'));
					return $this->redirect(['action' => 'index']);
				}
            } else {
					$this->Flash->error(__('The leave application could not be saved. Please, try again.'));
            }
        }
		a:	
		$leavetypes = $this->LeaveApplications->LeaveTypes->find('list');
		$financial_year = $this->LeaveApplications->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$from_date = date("Y-m-d",strtotime($financial_year->date_from));
		@$to_date   = date("Y-m-d",strtotime($financial_year->date_to));
		$LeaveApplicationDatas=$this->LeaveApplications->find()->where(['employee_id'=>$s_employee_id,'from_leave_date >='=>$from_date,'to_leave_date <='=>$to_date,'leave_status'=>'approved']);
		$TotaalleaveTake=[];
		foreach($LeaveApplicationDatas as $LeaveApplicationData){
			@$TotaalleaveTake[@$LeaveApplicationData->leave_type_id]+=@$LeaveApplicationData->day_no;
			//$LeaveType[$leavedata->id]=$leavedata->leave_name;
		} //pr($Totaalleave); exit;
	
		$leavedatas = $this->LeaveApplications->LeaveTypes->find();
		$Totaalleave=[]; $LeaveType=[];
		foreach($leavedatas as $leavedata){
			$Totaalleave[$leavedata->id]=$leavedata->maximum_leave_in_month*12;
			//$LeaveType[$leavedata->id]=$leavedata->leave_name;
		}
		$employees = $this->LeaveApplications->Employees->find('list')->where(['id !='=>23,'salary_company_id'=>$st_company_id])->matching(
					'EmployeeCompanies', function ($q)  {
						return $q->where(['EmployeeCompanies.freeze' =>0]);
					}
				); 
        $this->set(compact('leaveApplication','empData','leavetypes','Totaalleave','leavedatas','TotaalleaveTake','financial_year','financial_month_first','financial_month_last','s_employee_id','employees'));
        $this->set('_serialize', ['leaveApplication']);
    }
	
	function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {
		
		$dates = array();
		$current = strtotime($first);
		$last = strtotime($last);

		while( $current <= $last ) {
			
			$dates[] = date($output_format, $current);
			$current = strtotime($step, $current);
		} 
		return $dates;
	}
	
	public function leaveData($EmpId)
    {
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		 $st_year_id = $session->read('st_year_id');
		
		$financial_year = $this->LeaveApplications->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->LeaveApplications->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->LeaveApplications->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		
		 $SessionCheckDate = $this->FinancialYears->get($st_year_id);
		   $fromdate1 = date("Y-m-d",strtotime($SessionCheckDate->date_from));   
		   $todate1 = date("Y-m-d",strtotime($SessionCheckDate->date_to)); 
		   $tody1 = date("Y-m-d");

		   $fromdate = strtotime($fromdate1);
		   $todate = strtotime($todate1); 
	       $tody = strtotime($tody1);

		  if($fromdate < $tody || $todate > $tody)
		   {
			 if($SessionCheckDate['status'] == 'Open')
			 { $chkdate = 'Found'; }
			 else
			 { $chkdate = 'Not Found'; }

		   }
		   else
			{
				$chkdate = 'Not Found';	
			}
		////ends code for financial year
		 
		 
		$s_employee_id=$EmpId;
		$empData=$this->LeaveApplications->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
		//pr($empData); exit;
        $leaveApplication = $this->LeaveApplications->newEntity();
        
			
		$leavetypes = $this->LeaveApplications->LeaveTypes->find('list');
		$financial_year = $this->LeaveApplications->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$from_date = date("Y-m-d",strtotime($financial_year->date_from));
		@$to_date   = date("Y-m-d",strtotime($financial_year->date_to));
		$LeaveApplicationDatas=$this->LeaveApplications->find()->where(['employee_id'=>$s_employee_id,'from_leave_date >='=>$from_date,'to_leave_date <='=>$to_date,'leave_status'=>'approved']);
		$TotaalleaveTake=[];
		foreach($LeaveApplicationDatas as $LeaveApplicationData){
			@$TotaalleaveTake[@$LeaveApplicationData->leave_type_id]+=@$LeaveApplicationData->day_no;
			//$LeaveType[$leavedata->id]=$leavedata->leave_name;
		}
	
		$leavedatas = $this->LeaveApplications->LeaveTypes->find();
		$Totaalleave=[]; $LeaveType=[];
		foreach($leavedatas as $leavedata){
			$Totaalleave[$leavedata->id]=$leavedata->maximum_leave_in_month*12;
			//$LeaveType[$leavedata->id]=$leavedata->leave_name;
		}
		$employees = $this->LeaveApplications->Employees->find('list')->where(['id !='=>23,'salary_company_id'=>$st_company_id]); 
        $this->set(compact('leaveApplication','empData','leavetypes','Totaalleave','leavedatas','TotaalleaveTake','financial_year','financial_month_first','financial_month_last','s_employee_id','employees'));
        $this->set('_serialize', ['leaveApplication']);
    }
	
	public function getsickleaveData($empId=null,$leave_type=null){
	 $first_day_this_month = date('Y-m-01'); 
	 $last_day_this_month  = date('Y-m-t');
		$empDatas=$this->LeaveApplications->find()->where(['employee_id'=>$empId,'leave_type_id'=>$leave_type,'from_leave_date >='=>$first_day_this_month,'to_leave_date <='=>$last_day_this_month]);
		$day_no=0;
		foreach($empDatas as $emp){
			$day_no+= $emp->day_no;
		}
		//echo $day_no;exit;
		if($leave_type == 2 ){
			$empData=$this->LeaveApplications->exists(['employee_id'=>$empId,'leave_type_id'=>$leave_type,'from_leave_date >='=>$first_day_this_month,'to_leave_date <='=>$last_day_this_month]);
			if($empData && $day_no > 1){
				echo "yes";
			}else{
				echo "no";
			}
			exit;
		}
		exit;
	}

    /**
     * Edit method
     *
     * @param string|null $id Leave Application id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$empData=$this->LeaveApplications->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
        $leaveApplication = $this->LeaveApplications->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
			$files=@$this->request->data['supporting_attached']; 
            $leaveApplication = $this->LeaveApplications->patchEntity($leaveApplication, $this->request->data);
			if(!empty($files['name']))
			{
				$leaveApplication->supporting_attached = $files['name'];
			}
			else
			{
				$leaveApplication->supporting_attached = $leaveApplication->doc;
			}
			$EmployeeHierarchies=$this->LeaveApplications->EmployeeHierarchies->find()->contain(['ParentAccountingGroups'])->where(['EmployeeHierarchies.employee_id'=>$s_employee_id])->first();
			//$leaveApplication->parent_employee_id=$EmployeeHierarchies->parent_accounting_group->employee_id;
			$leaveApplication->employee_id=$leaveApplication->employee_id;
			$leaveApplication->submission_date=date('Y-m-d'); 
			$leaveApplication->from_leave_date = date('Y-m-d',strtotime($leaveApplication->from_leave_date)); 
			$leaveApplication->to_leave_date = date('Y-m-d',strtotime($leaveApplication->to_leave_date)); 
			$leaveApplication->approve_leave_from = date('Y-m-d',strtotime($leaveApplication->from_leave_date)); 
			$leaveApplication->approve_leave_to = date('Y-m-d',strtotime($leaveApplication->to_leave_date)); 
			
			if($leaveApplication->single_multiple=='Single'){
				$leaveApplication->to_leave_date=$leaveApplication->from_leave_date;
				$leaveApplication->to_full_half=$leaveApplication->from_full_half;
			}
			
			$from_leave_date = strtotime($leaveApplication->from_leave_date); 
			$to_leave_date =strtotime($leaveApplication->to_leave_date); 
			
			$datediff =$to_leave_date - $from_leave_date;
			$leaveApplication->day_no=round($datediff / (60 * 60 * 24))+1; 
			if($leaveApplication->single_multiple=='Single'){
				if($leaveApplication->from_full_half!='Full Day'){
					$leaveApplication->day_no-=0.5;
				}
			}else{
				if($leaveApplication->from_full_half=='Second Half Day'){
					$leaveApplication->day_no-=0.5;
				}
				if($leaveApplication->to_full_half=='First Half Day'){
					$leaveApplication->day_no-=0.5;
				}
			}
			
			if($leaveApplication->single_multiple=="Single"){ 
				$dates=$this->date_range($leaveApplication->from_leave_date, $leaveApplication->from_leave_date, $step = '+1 day', $output_format = 'Y-m-d' );
			}else{
				$dates=$this->date_range($leaveApplication->from_leave_date, $leaveApplication->to_leave_date, $step = '+1 day', $output_format = 'Y-m-d' );
			}
			
			foreach($dates as $data){ 
				$c=$this->LeaveApplications->find()->where(['LeaveApplications.employee_id'=>$s_employee_id,'LeaveApplications.approve_leave_from <='=>$data])->andWhere(['LeaveApplications.approve_leave_to >='=>$data])->count(); 
				
				if($c>0){
					$this->Flash->error(__('Leave Application cannot be fullfilled with duplicate dates.'));
					goto a;
				}
			}
			
			foreach($dates  as $date){
				$c=$this->LeaveApplications->TravelRequests->find()->where(['TravelRequests.employee_id'=>$s_employee_id,'travel_mode_from_date <='=>$date])->andWhere(['travel_mode_to_date >='=>$date])->count();
				if($c>0){
					$this->Flash->error(__('Travel request Applied in these dates.'));
					goto a;
				}
			}
			$leaveApplication->company_id=$st_company_id;
            if ($this->LeaveApplications->save($leaveApplication)) {
				if(!empty($files['tmp_name']))
				{
					$target_path = 'attached_file';
					$file_name   = $_FILES['supporting_attached']['name'];
					move_uploaded_file($files['tmp_name'], $target_path.'/'.$file_name);
				}
				
                $this->Flash->success(__('The leave application has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The leave application could not be saved. Please, try again.'));
            }
        }
		a:	
		$employees = $this->LeaveApplications->Employees->find('list')->where(['id !='=>23,'salary_company_id'=>$st_company_id])->matching(
					'EmployeeCompanies', function ($q)  {
						return $q->where(['EmployeeCompanies.freeze' =>0]);
					}
				);  
		$leavetypes = $this->LeaveApplications->LeaveTypes->find('list');
        $this->set(compact('leaveApplication','leavetypes','empData','employees'));
        $this->set('_serialize', ['leaveApplication']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Leave Application id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function approved($id = null,$approve_leave_from = null,$approve_leave_to = null,$leave_type = null,$comment = null)
    {	
		$approve_date=date('Y-m-d');
		$approve_leave_from=date('Y-m-d',strtotime($approve_leave_from));
		$approve_leave_to=date('Y-m-d',strtotime($approve_leave_to));
		$approve_leave_from = strtotime($approve_leave_from); 
		$approve_leave_to =strtotime($approve_leave_to); 
		$datediff =$approve_leave_to - $approve_leave_from;
		$day_no=round($datediff / (60 * 60 * 24))+1;
		$query = $this->LeaveApplications->query();
			$query->update()
				->set(['leave_status' =>'approved','approve_date'=>$approve_date,'approve_leave_from'=>$approve_leave_from,'approve_leave_to'=>$approve_leave_to,'comment'=>$comment,'leave_mode'=>$leave_type,'no_of_day_approve'=>$day_no])
				->where(['id' => $id])
				->execute();
		return $this->redirect(['controller'=>'Logins','action' => 'dashbord']);
    }

	public function approveLeave($id = null){
		$this->viewBuilder()->layout('index_layout');
		$LeaveApplication = $this->LeaveApplications->get($id, [
            'contain' => ['Employees','LeaveTypes']
        ]);
		
		if ($this->request->is('post')) {
			$approve_single_multiple=$this->request->data['approve_single_multiple'];
			$approve_leave_from=date('Y-m-d',strtotime($this->request->data['approve_leave_from']));
			$approve_leave_to=date('Y-m-d',strtotime($this->request->data['approve_leave_to']));
			$approve_full_half_from=$this->request->data['approve_full_half_from'];
			$approve_full_half_to=$this->request->data['approve_full_half_to'];
			$paid_leaves=$this->request->data['paid_leaves'];
			$unpaid_leaves=$this->request->data['unpaid_leaves'];
			$prior_approval=$this->request->data['prior_approval'];
			$without_prior_approval=$this->request->data['without_prior_approval'];
			//$intimated_leave=$this->request->data['intimated_leave'];
			$unintimated_leave=$this->request->data['unintimated_leave'];
			$query = $this->LeaveApplications->query();
			$query->update()
				->set(['leave_status' =>'approved','approve_single_multiple'=>$approve_single_multiple,'approve_leave_from'=>$approve_leave_from,'approve_leave_to'=>$approve_leave_to,'approve_full_half_from'=>$approve_full_half_from,'approve_full_half_to'=>$approve_full_half_to,'paid_leaves'=>$paid_leaves,'unpaid_leaves'=>$unpaid_leaves,'without_prior_leave'=>$without_prior_approval,'prior_leave'=>$prior_approval,'unintimated_leave'=>$unintimated_leave])
				->where(['id' => $id])
				->execute();
			return $this->redirect(['controller'=>'Logins','action' => 'dashbord']);
		};
		$this->set(compact('LeaveApplication','id'));
	}
	public function cancle($id = null)
    {
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
        $leaveApplication = $this->LeaveApplications->get($id);
		
		$EmployeeHierarchies=$this->LeaveApplications->EmployeeHierarchies->find()->contain(['ParentAccountingGroups'])->where(['EmployeeHierarchies.employee_id'=>$leaveApplication->parent_employee_id])->first();

			
			$query = $this->LeaveApplications->query();
					$query->update()
						->set(['leave_status' =>'cancel'])
						->where(['id' => $id])
						->execute();
	
		return $this->redirect(['controller'=>'Logins','action' => 'dashbord']);
    }
	public function markPending($id = null)
    {
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
        $leaveApplication = $this->LeaveApplications->get($id);
		
		$EmployeeHierarchies=$this->LeaveApplications->EmployeeHierarchies->find()->contain(['ParentAccountingGroups'])->where(['EmployeeHierarchies.employee_id'=>$leaveApplication->parent_employee_id])->first();

			
			$query = $this->LeaveApplications->query();
					$query->update()
						->set(['leave_status' =>'Pending'])
						->where(['id' => $id])
						->execute();
	
		return $this->redirect(['controller'=>'LeaveApplications','action' => 'index']);
    }

	public function leaveInfoEmployees($employee_id=null, $leaveAppId=null){
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$current_date = strtotime(date('d-m-Y'));
		$now = new \DateTime('now');
		$current_month = $now->format('m');
		$employeesData = $this->LeaveApplications->Employees->get($employee_id);
		$permanent_join_month = date('m',strtotime($employeesData->permanent_join_date));
		$permanent_join_mid_month_date = date('d',strtotime($employeesData->permanent_join_date));
		$permanent_join_date = strtotime($employeesData->permanent_join_date);
		$empType='';
		if($permanent_join_date  > $current_date){
			$empType= "probabtion";
			
		}else{
			$empType= "permanent";
		}
		
		$last_month = date('m', strtotime(date('Y-m')." -1 month"));
		
		$lastMonthleaveapplicationsApproved=	$this->LeaveApplications->find()
							->where(['employee_id'=>$employee_id, 'leave_status'=>'approved', 'financial_year_id'=>$st_year_id, 'company_id'=>$st_company_id, 'LeaveApplications.id !='=>$leaveAppId,'MONTH(from_leave_date)'=>$last_month])->contain(['LeaveTypes'])->toArray();

		$leaveapplicationsApproved=	$this->LeaveApplications->find()
							->where(['employee_id'=>$employee_id, 'leave_status'=>'approved', 'financial_year_id'=>$st_year_id, 'company_id'=>$st_company_id, 'LeaveApplications.id !='=>$leaveAppId,'MONTH(from_leave_date) >='=>$current_month])->contain(['LeaveTypes'])->order(['LeaveApplications.id'=>'DESC'])->first();
		$leaveapplications=	$this->LeaveApplications->find()
							->where(['employee_id'=>$employee_id, 'financial_year_id'=>$st_year_id, 'leave_status'=>'Pending', 'company_id'=>$st_company_id, 'LeaveApplications.id'=>$leaveAppId])->contain(['LeaveTypes'])->first();
		///start code for approved leave count particular employee
		$total_paid_leave = 0;$total_unpaid_leave = 0;
		$total_uninitimate_leave = 0;$total_prior_leave = 0;$total_withoutprior_leave = 0;
		$total_past_paid_leave=0.00;$total_past_unpaid_leave=0.00;$total_past_uninitimate_leave=0;$total_past_prior_leave=0;$total_past_withoutprior_leave=0;$sick_leaves=0;$casual_leaves=0;$pre_from_leave_date="00-00-0000";
		if(!empty($leaveapplicationsApproved)){ 
			if($leaveapplicationsApproved->leave_type->leave_name == $leaveapplications->leave_type->leave_name){
				$pre_from_leave_date = date('d-m-Y',strtotime($leaveapplicationsApproved->from_leave_date));
				 $total_past_paid_leave = number_format($leaveapplicationsApproved->paid_leaves,1);
				 $total_past_unpaid_leave = $leaveapplicationsApproved->unpaid_leaves;
				 $total_past_uninitimate_leave = $leaveapplicationsApproved->unintimated_leave;
				 $total_past_prior_leave = $leaveapplicationsApproved->prior_leave;
				 $total_past_withoutprior_leave = $leaveapplicationsApproved->without_prior_leave;
			}else if($leaveapplicationsApproved->leave_type->leave_name == $leaveapplications->leave_type->leave_name){
				$pre_from_leave_date = date('d-m-Y',strtotime($leaveapplicationsApproved->from_leave_date));
				 $total_past_paid_leave = number_format($leaveapplicationsApproved->paid_leaves,1);
				 $total_past_unpaid_leave = $leaveapplicationsApproved->unpaid_leaves;
				 $total_past_uninitimate_leave = $leaveapplicationsApproved->unintimated_leave;
				 $total_past_prior_leave = $leaveapplicationsApproved->prior_leave;
				 $total_past_withoutprior_leave = $leaveapplicationsApproved->without_prior_leave;
			}
				
		}
		//pr($total_past_paid_leave);exit;
		///ends code for approved leave count particular employee
		
								
							
				
		$from_leave_date = date('d-m-Y',strtotime($leaveapplications->from_leave_date));
		
		$to_leave_date	 = date('d-m-Y',strtotime($leaveapplications->to_leave_date));	
		$from_full_half	 = $leaveapplications->from_full_half;
		$to_full_half 	 = $leaveapplications->to_full_half;
		$day_no 		 = $leaveapplications->day_no;

		$from_leave_month =	date('m',strtotime($leaveapplications->from_leave_date));$to_leave_month   = date('m',strtotime($leaveapplications->to_leave_date));

		$holidays=	$this->LeaveApplications->Events->find()
							->where(['is_deleted'=>0,'MONTH(event_start_date) >='=>$current_month,'event_start_date >='=>date('Y-m-d')]);
		$d=[];
		$d=[15,16,17,18,19,20];
		$d1=[];
		$d1=[21,22,23,24,25,26,27,28,29,30,31];	
		
		///start code for pending leave particular employee
		if($empType == "permanent"){ 
			if($permanent_join_month == $current_month && in_array($permanent_join_mid_month_date,$d)){
				$sick_leaves = number_format($leaveapplications->leave_type->maximum_leave_in_month/2,1);
			}else if($permanent_join_month == $current_month && in_array($permanent_join_mid_month_date,$d1)){
				$sick_leaves = number_format($leaveapplications->leave_type->maximum_leave_in_month/4,1);
			}else if(sizeof($lastMonthleaveapplicationsApproved) > 0){  
				$sick_leaves = $leaveapplications->leave_type->maximum_leave_in_month;
			}else{
				$sick_leaves = $leaveapplications->leave_type->maximum_leave_in_month+$leaveapplications->leave_type->maximum_leave_in_month;
			}
			
			if($permanent_join_month == $current_month && in_array($permanent_join_mid_month_date,$d)){
				$casual_leaves = number_format($leaveapplications->leave_type->maximum_leave_in_month/2,1);
			}else if($permanent_join_month == $current_month && in_array($permanent_join_mid_month_date,$d1)){
				$casual_leaves = number_format($leaveapplications->leave_type->maximum_leave_in_month/4,1);
			}else if(sizeof($lastMonthleaveapplicationsApproved) > 0){ 
				$casual_leaves = $leaveapplications->leave_type->maximum_leave_in_month;
			}else{
				$casual_leaves = $leaveapplications->leave_type->maximum_leave_in_month+$leaveapplications->leave_type->maximum_leave_in_month;
			}
		
		
			if(!empty($leaveapplications)){  
				
				foreach($holidays as $holiday){ 
					$holiday_date = date('d-m-Y',strtotime($holiday->event_start_date));
					$holiday_start_date = date('d-m-Y',strtotime($holiday->event_start_date.'-1 days'));
					$holiday_end_date = date('d-m-Y',strtotime($holiday->event_start_date.'+1 days'));
					
					
					///start code for sandwich method
					if(($from_leave_date == $holiday_start_date) && ($to_leave_date == $holiday_end_date) || ($pre_from_leave_date == $holiday_start_date) && ($from_leave_date == $holiday_start_date)){  
							if($leaveapplications->from_full_half == "Second Half Day" && $leaveapplications->to_full_half == "First Half Day"){
								$tot = $total_unpaid_leave+2;
								if($total_past_paid_leave < $casual_leaves || $total_past_paid_leave < $sick_leaves){
									if($leaveapplications->leave_type->leave_name == "Sick Leave"){
										$total_paid_leave = $sick_leaves;
										$total_unpaid_leave = $tot-$sick_leaves;
									}else{
										$total_paid_leave = $casual_leaves;
										$total_unpaid_leave = $tot-$sick_leaves;
									}
									
								}else{
									$total_unpaid_leave = $tot;
								}
							}else if($leaveapplications->from_full_half == "Full Day" && $leaveapplications->to_full_half == "Full Day"){ 
								$tot_1 = $total_unpaid_leave+3;
								if($total_past_paid_leave < $casual_leaves || $total_past_paid_leave < $sick_leaves){
									if($leaveapplications->leave_type->leave_name == "Sick Leave"){
										$total_paid_leave = $sick_leaves;
										$total_unpaid_leave = $tot_1-$sick_leaves;
									}else{
										$total_paid_leave = $casual_leaves;
										$total_unpaid_leave = $tot_1-$sick_leaves;
									}
									
								}else{
									$total_unpaid_leave = $tot_1;
								}
							}else if(($leaveapplications->from_full_half == "Full Day" && $leaveapplications->to_full_half == "First Half Day") || ($leaveapplications->from_full_half == "Second Half Day" && $leaveapplications->to_full_half == "Full Day")){ 
								$tot_3 = $total_unpaid_leave+2.5;
								if($total_past_paid_leave < $casual_leaves || $total_past_paid_leave < $sick_leaves){
									if($leaveapplications->leave_type->leave_name == "Sick Leave"){
										$total_paid_leave = $sick_leaves;
										$total_unpaid_leave = $tot_3-$sick_leaves;
									}else{
										$total_paid_leave = $casual_leaves;
										$total_unpaid_leave = $tot_3-$sick_leaves;
									}
									
								}else{
									$total_unpaid_leave = $tot_3;
								}
							}
						///ends code for sandwich method	
						}else{ 
							
								
								
							if($leaveapplications->leave_type->leave_name == "Sick Leave"){ 
								
								$total_past_paid_leave= number_format($total_past_paid_leave,1);
								//$total_sick_leave = $sick_leave - $day_no;
								if($total_past_paid_leave >= $sick_leaves){
									$total_unpaidLeave = $total_past_paid_leave-$sick_leaves;
									
									//$total_unpaid_leave=$day_no+$total_past_unpaid_leave;
									if($total_unpaidLeave > 0){
										$total_unpaid_leave= $total_unpaidLeave;
									}else{
										$total_unpaid_leave =$total_unpaidLeave;
									}
									$total_paid_leave = $sick_leaves;
								}else{
									$total_paid_leave = $day_no;
								}
							}else if($leaveapplications->leave_type->leave_name == "Casual Leave"){
								$total_past_paid_leave= number_format($total_past_paid_leave,1); 
								
								if($total_past_paid_leave >= $casual_leaves){ 
									$total_unpaidLeave = $total_past_paid_leave-$casual_leaves;
									//echo $total_unpaidLeave;exit;
									//$total_unpaid_leave=$day_no+$total_past_unpaid_leave;
									if($total_unpaidLeave > 0){
										$total_unpaid_leave= $total_unpaidLeave;
									}else{
										$total_unpaid_leave =$total_unpaidLeave+$day_no;
									}
									$total_paid_leave = $casual_leaves;
								}else{ 
									$total_paid_leave = $day_no;
								}
							}else{ 
								$lta = $leaveapplications->leave_type->maximum_leave_in_month;
							}	
							
						}
						
					}
					
			}
		}else{ 
			if(!empty($leaveapplications)){
				
				foreach($holidays as $holiday){ 
					$holiday_date = date('d-m-Y',strtotime($holiday->event_start_date));
					$holiday_start_date = date('d-m-Y',strtotime($holiday->event_start_date.'-1 days'));
					$holiday_end_date = date('d-m-Y',strtotime($holiday->event_start_date.'+1 days'));
					///start code for sandwich method
					if(($from_leave_date == $holiday_start_date) && ($to_leave_date == $holiday_end_date) || ($pre_from_leave_date == $holiday_start_date) && ($from_leave_date == $holiday_start_date)){
							if($leaveapplications->from_full_half == "Second Half Day" && $leaveapplications->to_full_half == "First Half Day"){
								$total_unpaid_leave = $total_unpaid_leave+2;
							}else if($leaveapplications->from_full_half == "Full Day" && $leaveapplications->to_full_half == "Full Day"){
								$total_unpaid_leave = $total_unpaid_leave+3;
							}else if(($leaveapplications->from_full_half == "Full Day" && $leaveapplications->to_full_half == "First Half Day") && ($leaveapplications->from_full_half == "Second Half Day" && $leaveapplications->to_full_half == "Full Day")){
								$total_unpaid_leave = $total_unpaid_leave+2.5;
							}
						///ends code for sandwich method	
						}else{
							if($leaveapplications->leave_type->leave_name == "Sick Leave"){
								$sick_leave = $leaveapplications->leave_type->maximum_leave_in_month;
								$total_sick_leave = $sick_leave - $day_no;
								if($total_sick_leave < 0){
									
									$total_unpaid_leave=$day_no+$total_past_unpaid_leave;
									if($total_unpaid_leave > 0){
										$total_unpaid_leave= $total_unpaid_leave;
									}else{
										$total_unpaid_leave =$total_unpaid_leave;
									}
								}else{
									$total_unpaid_leave = $day_no;
								}
							}else if($leaveapplications->leave_type->leave_name == "Casual Leave"){ 
								$casual_leave = $leaveapplications->leave_type->maximum_leave_in_month;
								$total_casual_leave = $casual_leave-$day_no;
								if($total_casual_leave < 0){
									$total_unpaid_leave=$day_no+$total_past_unpaid_leave;
								}else{
									
									$total_unpaid_leave = $day_no;
								}
							}else{
								$lta = $leaveapplications->leave_type->maximum_leave_in_month;
							}	
							
						}
						
					}
					
			}
		}	
			
		///ends code for pending leave particular employee
		echo $total_paid_leave.",".$total_unpaid_leave.",".$total_uninitimate_leave.",".$total_prior_leave.",".$total_withoutprior_leave.",".$day_no.",".$total_past_paid_leave.",".$total_past_unpaid_leave.",".$casual_leaves.",".$sick_leaves;
		exit;
	}
	
	public function leaveInfo($employee_id=null, $leaveAppId=null){
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$current_date = strtotime(date('d-m-Y'));
		$employeesData = $this->LeaveApplications->Employees->get($employee_id);
		$permanent_join_date = strtotime($employeesData->permanent_join_date);
		$empType='';
		 
		if($permanent_join_date  > $current_date){
			$empType= "probabtion";
			
		}else{
			$empType= "permanent";
		}
		
		$LeaveApplications=	$this->LeaveApplications->find()
							->where(['employee_id'=>$employee_id, 'leave_status'=>'approved', 'financial_year_id'=>$st_year_id, 'company_id'=>$st_company_id, 'id !='=>$leaveAppId]);
		$PastUnintimated=0; $pastPaidLeaves=0;
		foreach($LeaveApplications as $LeaveApplication){
			$PastUnintimated+=$LeaveApplication->unintimated_leave;
			if($empType == "probabtion"){
				$pastPaidLeaves=0;
			}else{
				$pastPaidLeaves+=$LeaveApplication->paid_leaves;
			}
		}
		echo '30-'.$pastPaidLeaves.'-'.$PastUnintimated.'-'.$empType;
		//echo $empType;
		exit;
	}
	public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $salaryAdvance = $this->LeaveApplications->get($id);
        if ($this->LeaveApplications->delete($salaryAdvance)) {
            $this->Flash->success(__('The salary advance has been deleted.'));
        } else {
            $this->Flash->error(__('The salary advance could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function leaveStatus(){
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$st_year_id = $session->read('st_year_id');
		
		$Employees=	$this->LeaveApplications->Employees->find()
					->matching(
						'EmployeeCompanies', function ($q)  {
							return $q->where(['EmployeeCompanies.freeze' =>0]);
						}
					)
					->group(['Employees.id']);
		
		$currentLeaves=[];
		foreach($Employees as $Employee){
			$LeaveApplications=	$this->LeaveApplications->find()
								->where(['employee_id'=>$Employee->id,'company_id'=>$st_company_id, 'financial_year_id'=>$st_year_id, 'leave_status'=>'approved']);
			foreach($LeaveApplications as $LeaveApplication){
				$fm=(int)date('m',strtotime($LeaveApplication->approve_leave_from));
				$tm=(int)date('m',strtotime($LeaveApplication->approve_leave_to));
				if($fm==$tm){
					@$currentLeaves[$Employee->id][$fm][$LeaveApplication->leave_type_id]+=$LeaveApplication->paid_leaves+$LeaveApplication->unpaid_leaves;
				}else{
					
					$lastDateOfMonth = date("Y-m-t", strtotime($LeaveApplication->approve_leave_from));
					$datediff = strtotime($lastDateOfMonth) - strtotime($LeaveApplication->approve_leave_from);
					$leaves=round($datediff / (60 * 60 * 24));
					$leaves++;
					if($LeaveApplication->approve_full_half_from!="Full Day"){
						$leaves=$leaves-0.5;
					}
					@$currentLeaves[$Employee->id][$fm][$LeaveApplication->leave_type_id]+=$leaves;
					
					$firstDateOfMonth = date("Y", strtotime($LeaveApplication->approve_leave_from)).'-'.$tm.'-1';
					$datediff = strtotime($LeaveApplication->approve_leave_to) - strtotime($firstDateOfMonth);
					$leaves=round($datediff / (60 * 60 * 24));
					$leaves++;
					if($LeaveApplication->approve_full_half_to!="Full Day"){
						$leaves=$leaves-0.5;
					}
					@$currentLeaves[$Employee->id][$tm][$LeaveApplication->leave_type_id]+=$leaves;
				}
			}
		}
		
		$LeaveTypes=$this->LeaveApplications->LeaveTypes->find();
		$this->set(compact('Employees', 'LeaveTypes', 'currentLeaves'));
	}
}
