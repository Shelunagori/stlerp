<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EmployeeSalaryDivisions Controller
 *
 * @property \App\Model\Table\EmployeeSalaryDivisionsTable $EmployeeSalaryDivisions
 */
class EmployeeSalaryDivisionsController extends AppController
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

		$employeeSalaryDivision = $this->EmployeeSalaryDivisions->newEntity();
        if ($this->request->is('post')) {
            $employeeSalaryDivision = $this->EmployeeSalaryDivisions->patchEntity($employeeSalaryDivision, $this->request->data);
            if ($this->EmployeeSalaryDivisions->save($employeeSalaryDivision)) {
                $this->Flash->success(__('The employee salary division has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee salary division could not be saved. Please, try again.'));
            }
        }
        $employeeSalaryDivisions = $this->paginate($this->EmployeeSalaryDivisions);

        $this->set(compact('employeeSalaryDivisions','employeeSalaryDivision'));
        $this->set('_serialize', ['employeeSalaryDivisions']);
    }

    /**
     * View method
     *
     * @param string|null $id Employee Salary Division id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employeeSalaryDivision = $this->EmployeeSalaryDivisions->get($id, [
            'contain' => []
        ]);

        $this->set('employeeSalaryDivision', $employeeSalaryDivision);
        $this->set('_serialize', ['employeeSalaryDivision']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $employeeSalaryDivision = $this->EmployeeSalaryDivisions->newEntity();
        if ($this->request->is('post')) {
            $employeeSalaryDivision = $this->EmployeeSalaryDivisions->patchEntity($employeeSalaryDivision, $this->request->data);
            if ($this->EmployeeSalaryDivisions->save($employeeSalaryDivision)) {
                $this->Flash->success(__('The employee salary division has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee salary division could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('employeeSalaryDivision'));
        $this->set('_serialize', ['employeeSalaryDivision']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Salary Division id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$st_year_id = $session->read('st_year_id');
        $employeeSalaryDivision = $this->EmployeeSalaryDivisions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeeSalaryDivision = $this->EmployeeSalaryDivisions->patchEntity($employeeSalaryDivision, $this->request->data);
            if ($this->EmployeeSalaryDivisions->save($employeeSalaryDivision)) {
                $this->Flash->success(__('The employee salary division has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee salary division could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('employeeSalaryDivision'));
        $this->set('_serialize', ['employeeSalaryDivision']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee Salary Division id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employeeSalaryDivision = $this->EmployeeSalaryDivisions->get($id);
        if ($this->EmployeeSalaryDivisions->delete($employeeSalaryDivision)) {
            $this->Flash->success(__('The employee salary division has been deleted.'));
        } else {
            $this->Flash->error(__('The employee salary division could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
