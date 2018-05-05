<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SalaryAdvances Controller
 *
 * @property \App\Model\Table\SalaryAdvancesTable $SalaryAdvances
 */
class SalaryAdvancesController extends AppController
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
