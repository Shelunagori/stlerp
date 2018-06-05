<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
/**
 * Salaries Controller
 *
 * @property \App\Model\Table\SalariesTable $Salaries
 */
class SalariesController extends AppController
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
        $this->paginate = [
            'contain' => ['Employees', 'Companies', 'EmployeeSalaryDivisions']
        ];
        $salaries = $this->paginate($this->Salaries);

        $this->set(compact('salaries'));
        $this->set('_serialize', ['salaries']);
    }

	public function paySlip(){
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Salaries->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		
		if ($this->request->is('post')) {
			$month_year=$this->request->data['month_year'];
			$month_year=explode('-',$month_year);
			$Employees=$this->Salaries->Employees->find()
			->contain(['Salaries'=>function($q) use($month_year){
				return $q->where(['month'=>$month_year[0],'year'=>$month_year[1]])->contain(['EmployeeSalaryDivisions'])
				->order(['Salaries.id'=>'ASC']);
			}])
			->contain(['Designations', 'Departments'])
			->matching(
				'Salaries', function ($q) use($st_company_id) {
					return $q->where(['Salaries.company_id'=>$st_company_id]);
				}
			)
			->group(['Employees.id']);
			
			foreach($Employees as $Employee){
				$LeaveApplications=	$this->Salaries->LeaveApplications->find()
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
			$this->set(compact('month_year', 'currentLeaves'));
		}
		
		$em=$this->Salaries->Employees->find()
			->matching(
				'Departments', function ($q) use($st_company_id) {
					return $q->where(['Departments.id'=>6]);
				}
			)->first();
		
		$company=$this->Salaries->Companies->get($st_company_id);
		$this->set(compact('financial_year','Employees', 'company', 'em'));
	}
    /**
     * View method
     *
     * @param string|null $id Salary id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $salary = $this->Salaries->get($id, [
            'contain' => ['Employees', 'Companies', 'EmployeeSalaryDivisions']
        ]);

        $this->set('salary', $salary);
        $this->set('_serialize', ['salary']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $salary = $this->Salaries->newEntity();
        if ($this->request->is('post')) {
            $salary = $this->Salaries->patchEntity($salary, $this->request->data);
            if ($this->Salaries->save($salary)) {
                $this->Flash->success(__('The salary has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The salary could not be saved. Please, try again.'));
            }
        }
        $employees = $this->Salaries->Employees->find('list', ['limit' => 200]);
        $companies = $this->Salaries->Companies->find('list', ['limit' => 200]);
        $employeeSalaryDivisions = $this->Salaries->EmployeeSalaryDivisions->find('list', ['limit' => 200]);
        $this->set(compact('salary', 'employees', 'companies', 'employeeSalaryDivisions'));
        $this->set('_serialize', ['salary']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Salary id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $salary = $this->Salaries->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $salary = $this->Salaries->patchEntity($salary, $this->request->data);
            if ($this->Salaries->save($salary)) {
                $this->Flash->success(__('The salary has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The salary could not be saved. Please, try again.'));
            }
        }
        $employees = $this->Salaries->Employees->find('list', ['limit' => 200]);
        $companies = $this->Salaries->Companies->find('list', ['limit' => 200]);
        $employeeSalaryDivisions = $this->Salaries->EmployeeSalaryDivisions->find('list', ['limit' => 200]);
        $this->set(compact('salary', 'employees', 'companies', 'employeeSalaryDivisions'));
        $this->set('_serialize', ['salary']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Salary id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $salary = $this->Salaries->get($id);
        if ($this->Salaries->delete($salary)) {
            $this->Flash->success(__('The salary has been deleted.'));
        } else {
            $this->Flash->error(__('The salary could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
