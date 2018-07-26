<?php
namespace App\Controller;
use App\Controller\AppController;
/* use ADmad\Tree;
use Cake\Core\Configure;
use Cake\View\Helper;
use Exception; */
/**
 * EmployeeHierarchies Controller
 *
 * @property \App\Model\Table\EmployeeHierarchiesTable $EmployeeHierarchies
 */
class EmployeeHierarchiesController extends AppController
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
        $this->paginate = [
            'contain' => ['Employees','ParentAccountingGroups','Employees']
        ];
        $employeeHierarchies = $this->paginate($this->EmployeeHierarchies);
		//pr($employeeHierarchies); exit;
        $this->set(compact('employeeHierarchies'));
        $this->set('_serialize', ['employeeHierarchies']);
    }

    /**
     * View method
     *
     * @param string|null $id Employee Hierarchy id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employeeHierarchy = $this->EmployeeHierarchies->get($id, [
            'contain' => ['Employees']
        ]);

        $this->set('employeeHierarchy', $employeeHierarchy);
        $this->set('_serialize', ['employeeHierarchy']);
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
        $employeeHierarchy = $this->EmployeeHierarchies->newEntity();
        if ($this->request->is('post')) {
            $employeeHierarchy = $this->EmployeeHierarchies->patchEntity($employeeHierarchy, $this->request->data);
			$employeesData= $this->EmployeeHierarchies->Employees->get($employeeHierarchy->employee_id);
			$employeeHierarchy->name=$employeesData->name;
			$employeeHierarchy->company_id=$st_company_id;
			
			$Employeexists = $this->EmployeeHierarchies->exists(['employee_id' => $employeeHierarchy->employee_id,'parent_id'=>$employeeHierarchy->parent_id]);
			if($Employeexists !=1 ){
            if ($this->EmployeeHierarchies->save($employeeHierarchy)) {
                $this->Flash->success(__('The employee hierarchy has been saved.'));

                return $this->redirect(['action' => 'add']);
            } else {
                $this->Flash->error(__('The employee hierarchy could not be saved. Please, try again.'));
            }
			}else{
				$this->Flash->error(__('The employee already exists.'));
			}
        }
		 $this->paginate = [
            'contain' => ['Employees','ParentAccountingGroups','Employees']
        ];
        $employeeHierarchies = $this->paginate($this->EmployeeHierarchies);
        $employees = $this->EmployeeHierarchies->Employees->find('list', ['limit' => 200]);
        $employees_parent= $this->EmployeeHierarchies->find('list', ['limit' => 200]);
		$employees_data= $this->EmployeeHierarchies->find()->where(['employee_id'=>$s_employee_id])->first();
		$children = $this->EmployeeHierarchies
			->find('children', ['for' =>$employees_data->id])
			->toArray();
		
		$emp_datas=$this->EmployeeHierarchies->find()->toArray();
		$child_exist=[];
		foreach($emp_datas as $emp_data){
			$parentExist = $this->EmployeeHierarchies->exists(['parent_id' => $emp_data->id]);
			if($parentExist==1){
				$child_exist[$emp_data->id]="Yes";
			}else{
				$child_exist[$emp_data->id]="No";
			}
		}
		//pr($child_exist); exit;
        $this->set(compact('employeeHierarchy', 'employees','employees_parent','employeeHierarchies','children','child_exist'));
        $this->set('_serialize', ['employeeHierarchy']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Hierarchy id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employeeHierarchy = $this->EmployeeHierarchies->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeeHierarchy = $this->EmployeeHierarchies->patchEntity($employeeHierarchy, $this->request->data);
            if ($this->EmployeeHierarchies->save($employeeHierarchy)) {
                $this->Flash->success(__('The employee hierarchy has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee hierarchy could not be saved. Please, try again.'));
            }
        }
        $employees = $this->EmployeeHierarchies->Employees->find('list', ['limit' => 200]);
        $this->set(compact('employeeHierarchy', 'employees'));
        $this->set('_serialize', ['employeeHierarchy']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee Hierarchy id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employeeHierarchy = $this->EmployeeHierarchies->get($id);
        if ($this->EmployeeHierarchies->delete($employeeHierarchy)) {
            $this->Flash->success(__('The employee hierarchy has been deleted.'));
        } else {
            $this->Flash->error(__('The employee hierarchy could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'add']);
    }
}
