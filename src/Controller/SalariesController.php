<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Salaries Controller
 *
 * @property \App\Model\Table\SalariesTable $Salaries
 */
class SalariesController extends AppController
{

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
