<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EmployeeFamilyMembers Controller
 *
 * @property \App\Model\Table\EmployeeFamilyMembersTable $EmployeeFamilyMembers
 */
class EmployeeFamilyMembersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($employeeID)
    {
		$this->viewBuilder()->layout('index_layout');
        $this->paginate = [
            'contain' => ['Employees']
        ];
        $employeeFamilyMembers = $this->paginate($this->EmployeeFamilyMembers->find()->where(['employee_id'=>$employeeID]));

		$employeeFamilyMember = $this->EmployeeFamilyMembers->newEntity();
        if ($this->request->is('post')) {
            $employeeFamilyMember = $this->EmployeeFamilyMembers->patchEntity($employeeFamilyMember, $this->request->data);
            if ($this->EmployeeFamilyMembers->save($employeeFamilyMember)) {
                $this->Flash->success(__('The employee family member has been saved.'));

                return $this->redirect(['action' => 'index',$employeeID]);
            } else {
                $this->Flash->error(__('The employee family member could not be saved. Please, try again.'));
            }
        }
		
		$Employee=$this->EmployeeFamilyMembers->Employees->get($employeeID);
        $this->set(compact('employeeFamilyMember', 'employeeFamilyMembers', 'employeeID', 'Employee'));
        $this->set('_serialize', ['employeeFamilyMember']);
    }

    /**
     * View method
     *
     * @param string|null $id Employee Family Member id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employeeFamilyMember = $this->EmployeeFamilyMembers->get($id, [
            'contain' => ['Employees']
        ]);

        $this->set('employeeFamilyMember', $employeeFamilyMember);
        $this->set('_serialize', ['employeeFamilyMember']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $employeeFamilyMember = $this->EmployeeFamilyMembers->newEntity();
        if ($this->request->is('post')) {
            $employeeFamilyMember = $this->EmployeeFamilyMembers->patchEntity($employeeFamilyMember, $this->request->data);
            if ($this->EmployeeFamilyMembers->save($employeeFamilyMember)) {
                $this->Flash->success(__('The employee family member has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee family member could not be saved. Please, try again.'));
            }
        }
        $employees = $this->EmployeeFamilyMembers->Employees->find('list', ['limit' => 200]);
        $this->set(compact('employeeFamilyMember', 'employees'));
        $this->set('_serialize', ['employeeFamilyMember']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Family Member id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employeeFamilyMember = $this->EmployeeFamilyMembers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeeFamilyMember = $this->EmployeeFamilyMembers->patchEntity($employeeFamilyMember, $this->request->data);
            if ($this->EmployeeFamilyMembers->save($employeeFamilyMember)) {
                $this->Flash->success(__('The employee family member has been saved.'));

                return $this->redirect(['action' => 'index', $employeeID]);
            } else {
                $this->Flash->error(__('The employee family member could not be saved. Please, try again.'));
            }
        }
        $employees = $this->EmployeeFamilyMembers->Employees->find('list', ['limit' => 200]);
        $this->set(compact('employeeFamilyMember', 'employees'));
        $this->set('_serialize', ['employeeFamilyMember']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee Family Member id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null, $employeeID)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employeeFamilyMember = $this->EmployeeFamilyMembers->get($id);
        if ($this->EmployeeFamilyMembers->delete($employeeFamilyMember)) {
            $this->Flash->success(__('The employee family member has been deleted.'));
        } else {
            $this->Flash->error(__('The employee family member could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index', $employeeID]);
    }
}
