<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EmployeeSalaries Controller
 *
 * @property \App\Model\Table\EmployeeSalariesTable $EmployeeSalaries
 */
class EmployeeSalariesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Employees', 'EmployeeSalaryDivisions']
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
    public function add($id = null)
    {
        $employeeSalary = $this->EmployeeSalaries->newEntity();
        if ($this->request->is('post')) {
            $employeeSalary = $this->EmployeeSalaries->patchEntity($employeeSalary, $this->request->data);
            if ($this->EmployeeSalaries->save($employeeSalary)) {
                $this->Flash->success(__('The employee salary has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee salary could not be saved. Please, try again.'));
            }
        }
        $employee = $this->EmployeeSalaries->Employees->get($id);
		pr($employee); exit;
        $employeeSalaryDivisions = $this->EmployeeSalaries->EmployeeSalaryDivisions->find('list', ['limit' => 200]);
        $this->set(compact('employeeSalary', 'employees', 'employeeSalaryDivisions'));
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
}
