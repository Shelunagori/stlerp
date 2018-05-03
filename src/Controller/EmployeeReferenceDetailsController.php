<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EmployeeReferenceDetails Controller
 *
 * @property \App\Model\Table\EmployeeReferenceDetailsTable $EmployeeReferenceDetails
 */
class EmployeeReferenceDetailsController extends AppController
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
        $employeeReferenceDetails = $this->paginate($this->EmployeeReferenceDetails->find()->where(['employee_id'=>$employeeID]));

		$employeeReferenceDetail = $this->EmployeeReferenceDetails->newEntity();
		if ($this->request->is('post')) {
            $employeeReferenceDetail = $this->EmployeeReferenceDetails->patchEntity($employeeReferenceDetail, $this->request->data);
            if ($this->EmployeeReferenceDetails->save($employeeReferenceDetail)) {
                $this->Flash->success(__('The employee reference detail has been saved.'));

                return $this->redirect(['action' => 'index', $employeeID]);
            } else {
                $this->Flash->error(__('The employee reference detail could not be saved. Please, try again.'));
            }
        }
        $Employee = $this->EmployeeReferenceDetails->Employees->get($employeeID);
        $this->set(compact('employeeReferenceDetail', 'employees', 'employeeReferenceDetails', 'employeeID', 'Employee'));
        $this->set('_serialize', ['employeeReferenceDetail']);
    }

    /**
     * View method
     *
     * @param string|null $id Employee Reference Detail id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employeeReferenceDetail = $this->EmployeeReferenceDetails->get($id, [
            'contain' => ['Employees']
        ]);

        $this->set('employeeReferenceDetail', $employeeReferenceDetail);
        $this->set('_serialize', ['employeeReferenceDetail']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $employeeReferenceDetail = $this->EmployeeReferenceDetails->newEntity();
        if ($this->request->is('post')) {
            $employeeReferenceDetail = $this->EmployeeReferenceDetails->patchEntity($employeeReferenceDetail, $this->request->data);
            if ($this->EmployeeReferenceDetails->save($employeeReferenceDetail)) {
                $this->Flash->success(__('The employee reference detail has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee reference detail could not be saved. Please, try again.'));
            }
        }
        $employees = $this->EmployeeReferenceDetails->Employees->find('list', ['limit' => 200]);
        $this->set(compact('employeeReferenceDetail', 'employees'));
        $this->set('_serialize', ['employeeReferenceDetail']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Reference Detail id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employeeReferenceDetail = $this->EmployeeReferenceDetails->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeeReferenceDetail = $this->EmployeeReferenceDetails->patchEntity($employeeReferenceDetail, $this->request->data);
            if ($this->EmployeeReferenceDetails->save($employeeReferenceDetail)) {
                $this->Flash->success(__('The employee reference detail has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee reference detail could not be saved. Please, try again.'));
            }
        }
        $employees = $this->EmployeeReferenceDetails->Employees->find('list', ['limit' => 200]);
        $this->set(compact('employeeReferenceDetail', 'employees'));
        $this->set('_serialize', ['employeeReferenceDetail']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee Reference Detail id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null, $employeeID)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employeeReferenceDetail = $this->EmployeeReferenceDetails->get($id);
        if ($this->EmployeeReferenceDetails->delete($employeeReferenceDetail)) {
            $this->Flash->success(__('The employee reference detail has been deleted.'));
        } else {
            $this->Flash->error(__('The employee reference detail could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index', $employeeID]);
    }
}
