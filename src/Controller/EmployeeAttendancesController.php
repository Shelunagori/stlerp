<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EmployeeAttendances Controller
 *
 * @property \App\Model\Table\EmployeeAttendancesTable $EmployeeAttendances
 */
class EmployeeAttendancesController extends AppController
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
		$st_year_id = $session->read('st_year_id');
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
		//pr($where1); exit;
        $this->paginate = [
            'contain' => ['FinancialYears', 'Employees']
        ];
        $employeeAttendances = $this->paginate($this->EmployeeAttendances->find()->where($where1));

        $this->set(compact('employeeAttendances'));
        $this->set('_serialize', ['employeeAttendances']);
    }

    /**
     * View method
     *
     * @param string|null $id Employee Attendance id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employeeAttendance = $this->EmployeeAttendances->get($id, [
            'contain' => ['FinancialYears', 'Employees']
        ]);

        $this->set('employeeAttendance', $employeeAttendance);
        $this->set('_serialize', ['employeeAttendance']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function getAttendenceList($From=null){
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$From1=$From;
		$From="01-".$From;
		$employees = $this->EmployeeAttendances->Employees->find()->where(['id !='=>23])
		->contain(['EmployeeCompanies'])
			->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.company_id'=>$st_company_id,'EmployeeCompanies.freeze'=>0]);
					}
				);
		$employee_leave=[];
		foreach($employees as $data){
			$time=strtotime($From);
			$month=date("m",$time);
			$year=date("Y",$time);
			$total_day=cal_days_in_month(CAL_GREGORIAN,$month,$year);
			$to_date=$total_day.'-'.$From1;
			$from_date=date('Y-m-d',strtotime($From));
			$to_date=date('Y-m-d',strtotime($to_date));
			
			
			$employeeLeave = $this->EmployeeAttendances->LeaveApplications->find()->where(['employee_id'=>$data->id,'leave_status'=>'approved','leave_type'=>'Paid']);
			
				foreach($employeeLeave as $data1){ 
					$data1->to_leave_date=date('Y-m-d',strtotime($data1->to_leave_date));
					$data1->from_leave_date=date('Y-m-d',strtotime($data1->from_leave_date));
					$addMonth = date('Y-m-d', strtotime("+1 months", strtotime($to_date)));
					$minusMonth = date('Y-m-d', strtotime("-1 months", strtotime($from_date)));
					if($data1->from_leave_date >= $from_date && $data1->to_leave_date <= $to_date){ 
						@$employee_leave[@$data->id]+=@$data1->day_no;
					}else if(($data1->from_leave_date > $to_date && $data1->to_leave_date > $to_date)){

					}else if(($data1->from_leave_date < $from_date && $data1->to_leave_date < $from_date)){

					}else if(($data1->from_leave_date >= $minusMonth && $data1->to_leave_date > $to_date) && ($data1->from_leave_date >= $from_date && $data1->to_leave_date < $addMonth)){ 
						$dt=date('d-m-Y',strtotime($data1->from_leave_date));
						$dt1=strtotime($dt);
						$d1=date("d",$dt1); 
						@$employee_leave[@$data->id]+=@$total_day+1-$d1;
					}else if(($data1->from_leave_date >= $minusMonth && $data1->to_leave_date < $to_date) && ($data1->to_leave_date >= $from_date && $data1->to_leave_date < $addMonth)){ 
						$dt=date('d-m-Y',strtotime($data1->to_leave_date));
						$dt1=strtotime($dt); 
						$d1=date("d",$dt1);  
						@$employee_leave[@$data->id]+=$d1;
					}
					
				} 
			

		}
		$this->set(compact('employeeAttendance', 'financialYears', 'employees','employee_leave','total_day'));
	}
	
	   public function getAttendenceListEdit($From=null){
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->EmployeeAttendances->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$From1=$From;
		$From="01-".$From;
		$time=strtotime($From);
		$month=date("m",$time); 
		$year=date("Y",$time);
		$total_day=cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$employees = $this->EmployeeAttendances->find()->where(['financial_year_id'=>$financial_year->id,'month'=>$month])->contain(['Employees']);
		//pr($employees->toArray()); exit;
		$this->set(compact('employeeAttendance', 'financialYears', 'employees','employee_leave','total_day'));
	}
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->EmployeeAttendances->FinancialYears->find()->where(['id'=>$st_year_id])->first();
        $employeeAttendance = $this->EmployeeAttendances->newEntity();
        if ($this->request->is('post')) {
            $employeeAttendance = $this->EmployeeAttendances->patchEntity($employeeAttendance, $this->request->data);
			$employeeAttendance->effective_date_from='01-'.$employeeAttendance->From;
			$time=strtotime($employeeAttendance->effective_date_from);
			$month=date("m",$time);
			$year=date("Y",$time);
			//pr($month); exit;
			$total_day=cal_days_in_month(CAL_GREGORIAN,$month,$year);
			foreach($employeeAttendance->employee_attendances as $data){ 
				$employeeAtten = $this->EmployeeAttendances->newEntity();
				$employeeAtten->employee_id = $data['employee_id'];
				$employeeAtten->present_day = $data['present_day'];
				$employeeAtten->month = $month;
				$employeeAtten->financial_year_id = $financial_year->id;
				$employeeAtten->total_month_day = $total_day;
				$employeeAtten->no_of_leave = $total_day-$data['present_day'];
				$this->EmployeeAttendances->save($employeeAtten);
			}
				$this->Flash->success(__('The employee attendance has been saved.'));
				return $this->redirect(['action' => 'index']);
			
		  }
       // $financialYears = $this->EmployeeAttendances->FinancialYears->find('list', ['limit' => 200]);
        $employees = $this->EmployeeAttendances->Employees->find()->where(['status'=>'0','id !='=>23]);  
		//pr(date('d-m-Y',strtotime($financial_year->date_from))); exit;
        $this->set(compact('employeeAttendance', 'financialYears', 'employees','financial_year'));
        $this->set('_serialize', ['employeeAttendance']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Attendance id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        /* $employeeAttendance = $this->EmployeeAttendances->get($id, [
            'contain' => []
        ]); */
		$employeeAttendance = $this->EmployeeAttendances->newEntity();
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->EmployeeAttendances->FinancialYears->find()->where(['id'=>$st_year_id])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeeAttendance = $this->EmployeeAttendances->patchEntity($employeeAttendance, $this->request->data);
            $employeeAttendance->effective_date_from='01-'.$employeeAttendance->From;
			$time=strtotime($employeeAttendance->effective_date_from);
			$month=date("m",$time);
			$year=date("Y",$time);
			//pr($month); exit;
			$total_day=cal_days_in_month(CAL_GREGORIAN,$month,$year);
			$this->EmployeeAttendances->deleteAll(['financial_year_id' => $financial_year->id,'month'=>$month]);
			foreach($employeeAttendance->employee_attendances as $data){ 
				$employeeAtten = $this->EmployeeAttendances->newEntity();
				$employeeAtten->employee_id = $data['employee_id'];
				$employeeAtten->present_day = $data['present_day'];
				$employeeAtten->month = $month;
				$employeeAtten->financial_year_id = $financial_year->id;
				$employeeAtten->total_month_day = $total_day;
				$employeeAtten->no_of_leave = $total_day-$data['present_day'];
				$this->EmployeeAttendances->save($employeeAtten);
			}
				$this->Flash->success(__('The employee attendance has been saved.'));
				return $this->redirect(['action' => 'index']);
        }
        $financialYears = $this->EmployeeAttendances->FinancialYears->find('list', ['limit' => 200]);
        $employees = $this->EmployeeAttendances->Employees->find('list', ['limit' => 200]);
        $this->set(compact('employeeAttendance', 'financialYears', 'employees'));
        $this->set('_serialize', ['employeeAttendance']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee Attendance id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employeeAttendance = $this->EmployeeAttendances->get($id);
        if ($this->EmployeeAttendances->delete($employeeAttendance)) {
            $this->Flash->success(__('The employee attendance has been deleted.'));
        } else {
            $this->Flash->error(__('The employee attendance could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
