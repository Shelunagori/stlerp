<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CompanyWiseEmployeeSalaries Controller
 *
 * @property \App\Model\Table\CompanyWiseEmployeeSalariesTable $CompanyWiseEmployeeSalaries
 */
class CompanyWiseEmployeeSalariesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Employees', 'Companies']
        ];
        $companyWiseEmployeeSalaries = $this->paginate($this->CompanyWiseEmployeeSalaries);

        $this->set(compact('companyWiseEmployeeSalaries'));
        $this->set('_serialize', ['companyWiseEmployeeSalaries']);
    }

    /**
     * View method
     *
     * @param string|null $id Company Wise Employee Salary id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $companyWiseEmployeeSalary = $this->CompanyWiseEmployeeSalaries->get($id, [
            'contain' => ['Employees', 'Companies']
        ]);

        $this->set('companyWiseEmployeeSalary', $companyWiseEmployeeSalary);
        $this->set('_serialize', ['companyWiseEmployeeSalary']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($id)
    { 
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$this->viewBuilder()->layout('index_layout');
        $companyWiseEmployeeSalary = $this->CompanyWiseEmployeeSalaries->newEntity();
        if ($this->request->is('post')) {
            $companyWiseEmployeeSalary = $this->CompanyWiseEmployeeSalaries->patchEntity($companyWiseEmployeeSalary, $this->request->data);
            if ($this->CompanyWiseEmployeeSalaries->save($companyWiseEmployeeSalary)) {
                $this->Flash->success(__('The company wise employee salary has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The company wise employee salary could not be saved. Please, try again.'));
            }
        }
       // $employees = $this->CompanyWiseEmployeeSalaries->Employees->find('list', ['limit' => 200]);
        $companies = $this->CompanyWiseEmployeeSalaries->Companies->find('list', ['limit' => 200]);
        $this->set(compact('companyWiseEmployeeSalary', 'employees', 'companies'));
        $this->set('_serialize', ['companyWiseEmployeeSalary']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Company Wise Employee Salary id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $companyWiseEmployeeSalary = $this->CompanyWiseEmployeeSalaries->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $companyWiseEmployeeSalary = $this->CompanyWiseEmployeeSalaries->patchEntity($companyWiseEmployeeSalary, $this->request->data);
            if ($this->CompanyWiseEmployeeSalaries->save($companyWiseEmployeeSalary)) {
                $this->Flash->success(__('The company wise employee salary has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The company wise employee salary could not be saved. Please, try again.'));
            }
        }
        $employees = $this->CompanyWiseEmployeeSalaries->Employees->find('list', ['limit' => 200]);
        $companies = $this->CompanyWiseEmployeeSalaries->Companies->find('list', ['limit' => 200]);
        $this->set(compact('companyWiseEmployeeSalary', 'employees', 'companies'));
        $this->set('_serialize', ['companyWiseEmployeeSalary']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Company Wise Employee Salary id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $companyWiseEmployeeSalary = $this->CompanyWiseEmployeeSalaries->get($id);
        if ($this->CompanyWiseEmployeeSalaries->delete($companyWiseEmployeeSalary)) {
            $this->Flash->success(__('The company wise employee salary has been deleted.'));
        } else {
            $this->Flash->error(__('The company wise employee salary could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
