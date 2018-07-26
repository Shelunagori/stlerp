<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EmployeeRecords Controller
 *
 * @property \App\Model\Table\EmployeeRecordsTable $EmployeeRecords
 */
class EmployeeRecordsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
		$this->viewBuilder()->layout('index_layout');
        $this->paginate = [
            'contain' => ['Employees']
        ];
        $employeeRecords = $this->paginate($this->EmployeeRecords);

        $this->set(compact('employeeRecords'));
        $this->set('_serialize', ['employeeRecords']);
    }

    /**
     * View method
     *
     * @param string|null $id Employee Record id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $employeeRecord = $this->EmployeeRecords->get($id, [
            'contain' => ['Employees']
        ]);

        $this->set('employeeRecord', $employeeRecord);
        $this->set('_serialize', ['employeeRecord']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
		$employee_record_type=$this->request->query('employee_record_type');
		$month_year=$this->request->query('month_year');
        $employeeRecord = $this->EmployeeRecords->newEntity();
        if ($this->request->is('post')) 
		{
            $employeeRecord = $this->EmployeeRecords->patchEntity($employeeRecord, $this->request->data);
            if ($this->EmployeeRecords->save($employeeRecord)) {
                $this->Flash->success(__('The employee record has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee record could not be saved. Please, try again.'));
            }
        }
        $employees = $this->EmployeeRecords->Employees->find('list', ['limit' => 200]);
        $EmployeeSalaryDivision = $this->EmployeeRecords->EmployeeSalaryDivisions->find('list');
		//pr($EmployeeSalaryDivision->toArray()); exit;
        $this->set(compact('employeeRecord', 'employee_record_type', 'month_year','employees','EmployeeSalaryDivision'));
        $this->set('_serialize', ['employeeRecord']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Record id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $employeeRecord = $this->EmployeeRecords->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeeRecord = $this->EmployeeRecords->patchEntity($employeeRecord, $this->request->data);
            if ($this->EmployeeRecords->save($employeeRecord)) {
                $this->Flash->success(__('The employee record has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee record could not be saved. Please, try again.'));
            }
        }
        $employees = $this->EmployeeRecords->Employees->find('list', ['limit' => 200]);
        $this->set(compact('employeeRecord', 'employees'));
        $this->set('_serialize', ['employeeRecord']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee Record id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employeeRecord = $this->EmployeeRecords->get($id);
        if ($this->EmployeeRecords->delete($employeeRecord)) {
            $this->Flash->success(__('The employee record has been deleted.'));
        } else {
            $this->Flash->error(__('The employee record could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
