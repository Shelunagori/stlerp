<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LeaveApplications Controller
 *
 * @property \App\Model\Table\LeaveApplicationsTable $LeaveApplications
 */
class LeaveApplicationsController extends AppController
{

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
		$empData=$this->LeaveApplications->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
		
		if($empData->department->name=='HR & Administration' || $empData->designation->name=='Director'){
		$leaveApplications = $this->paginate($this->LeaveApplications->find()->contain(['Employees']));
		}else{
		$leaveApplications = $this->paginate($this->LeaveApplications->find()->contain(['Employees'])->where(['employee_id'=>$s_employee_id]));
		}
       // $leaveApplications = $this->paginate($this->LeaveApplications->find()->contain(['LeaveTypes']));
        $this->set(compact('leaveApplications'));
        $this->set('_serialize', ['leaveApplications']);
    }

    /**
     * View method
     *
     * @param string|null $id Leave Application id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

		
	public function approve($id = null){
		$LeaveApplication = $this->LeaveApplications->get($id);
		 $this->set(compact('LeaveApplication','id'));
	}
    public function view($id = null)
    {
        $leaveApplication = $this->LeaveApplications->get($id, [
            'contain' => []
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
			
			$from_leave_date = strtotime($leaveApplication->from_leave_date); 
			$to_leave_date =strtotime($leaveApplication->to_leave_date); 
			$datediff =$to_leave_date - $from_leave_date;
			$leaveApplication->day_no=round($datediff / (60 * 60 * 24))+1;  //pr($leaveApplication); exit;
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
		$employees = $this->LeaveApplications->Employees->find('list')->where(['id !='=>23,'salary_company_id'=>$st_company_id]); 
        $this->set(compact('leaveApplication','empData','leavetypes','Totaalleave','leavedatas','TotaalleaveTake','financial_year','financial_month_first','financial_month_last','s_employee_id','employees'));
        $this->set('_serialize', ['leaveApplication']);
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
			
			$from_leave_date = strtotime($leaveApplication->from_leave_date); 
			$to_leave_date =strtotime($leaveApplication->to_leave_date); 
			
			$datediff =$to_leave_date - $from_leave_date;
			$leaveApplication->day_no=round($datediff / (60 * 60 * 24))+1; 
			//pr($leaveApplication); exit;
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
		$employees = $this->LeaveApplications->Employees->find('list')->where(['id !='=>23,'salary_company_id'=>$st_company_id]); 
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

	public function cancle($id = null)
    {
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
        $leaveApplication = $this->LeaveApplications->get($id);
		
		$EmployeeHierarchies=$this->LeaveApplications->EmployeeHierarchies->find()->contain(['ParentAccountingGroups'])->where(['EmployeeHierarchies.employee_id'=>$leaveApplication->parent_employee_id])->first();

			
			$query = $this->LeaveApplications->query();
					$query->update()
						->set(['leave_status' =>'cancle'])
						->where(['id' => $id])
						->execute();
	
		return $this->redirect(['controller'=>'Logins','action' => 'dashbord']);
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
}
