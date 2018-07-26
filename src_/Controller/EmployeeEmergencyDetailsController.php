<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EmployeeEmergencyDetails Controller
 *
 * @property \App\Model\Table\EmployeeEmergencyDetailsTable $EmployeeEmergencyDetails
 */
class EmployeeEmergencyDetailsController extends AppController
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
        $employeeEmergencyDetails = $this->paginate($this->EmployeeEmergencyDetails->find()->where(['employee_id'=>$employeeID]));

		$employeeEmergencyDetail = $this->EmployeeEmergencyDetails->newEntity();
        if ($this->request->is('post')) {
            $employeeEmergencyDetail = $this->EmployeeEmergencyDetails->patchEntity($employeeEmergencyDetail, $this->request->data);
            if ($this->EmployeeEmergencyDetails->save($employeeEmergencyDetail)) {
                $this->Flash->success(__('The employee emergency detail has been saved.'));

                return $this->redirect(['action' => 'index', $employeeID]);
            } else {
                $this->Flash->error(__('The employee emergency detail could not be saved. Please, try again.'));
            }
        }
		$Employee=$this->EmployeeEmergencyDetails->Employees->get($employeeID);
		$this->set(compact('employeeEmergencyDetail', 'employees', 'employeeEmergencyDetails', 'employeeID', 'Employee'));
        $this->set('_serialize', ['employeeEmergencyDetails']);
    }

    /**
     * View method
     *
     * @param string|null $id Employee Emergency Detail id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employeeEmergencyDetail = $this->EmployeeEmergencyDetails->get($id, [
            'contain' => ['Employees']
        ]);

        $this->set('employeeEmergencyDetail', $employeeEmergencyDetail);
        $this->set('_serialize', ['employeeEmergencyDetail']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $employeeEmergencyDetail = $this->EmployeeEmergencyDetails->newEntity();
        if ($this->request->is('post')) {
            $employeeEmergencyDetail = $this->EmployeeEmergencyDetails->patchEntity($employeeEmergencyDetail, $this->request->data);
            if ($this->EmployeeEmergencyDetails->save($employeeEmergencyDetail)) {
                $this->Flash->success(__('The employee emergency detail has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee emergency detail could not be saved. Please, try again.'));
            }
        }
        $employees = $this->EmployeeEmergencyDetails->Employees->find('list', ['limit' => 200]);
        $this->set(compact('employeeEmergencyDetail', 'employees'));
        $this->set('_serialize', ['employeeEmergencyDetail']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Emergency Detail id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employeeEmergencyDetail = $this->EmployeeEmergencyDetails->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeeEmergencyDetail = $this->EmployeeEmergencyDetails->patchEntity($employeeEmergencyDetail, $this->request->data);
            if ($this->EmployeeEmergencyDetails->save($employeeEmergencyDetail)) {
                $this->Flash->success(__('The employee emergency detail has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee emergency detail could not be saved. Please, try again.'));
            }
        }
        $employees = $this->EmployeeEmergencyDetails->Employees->find('list', ['limit' => 200]);
        $this->set(compact('employeeEmergencyDetail', 'employees'));
        $this->set('_serialize', ['employeeEmergencyDetail']);
    }

    /**
     * Delete method  
     *
     * @param string|null $id Employee Emergency Detail id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null, $employeeID)
    {
		//echo $employeeID; exit;
        $this->request->allowMethod(['post', 'delete']);
        $employeeEmergencyDetail = $this->EmployeeEmergencyDetails->get($id);
        if ($this->EmployeeEmergencyDetails->delete($employeeEmergencyDetail)) {
            $this->Flash->success(__('The employee emergency detail has been deleted.'));
        } else {
            $this->Flash->error(__('The employee emergency detail could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index', $employeeID]);
    }
}
