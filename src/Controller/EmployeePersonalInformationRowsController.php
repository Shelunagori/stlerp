<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EmployeePersonalInformationRows Controller
 *
 * @property \App\Model\Table\EmployeePersonalInformationRowsTable $EmployeePersonalInformationRows
 */
class EmployeePersonalInformationRowsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['EmpPersonalInformations']
        ];
        $employeePersonalInformationRows = $this->paginate($this->EmployeePersonalInformationRows);

        $this->set(compact('employeePersonalInformationRows'));
        $this->set('_serialize', ['employeePersonalInformationRows']);
    }

    /**
     * View method
     *
     * @param string|null $id Employee Personal Information Row id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employeePersonalInformationRow = $this->EmployeePersonalInformationRows->get($id, [
            'contain' => ['EmpPersonalInformations']
        ]);

        $this->set('employeePersonalInformationRow', $employeePersonalInformationRow);
        $this->set('_serialize', ['employeePersonalInformationRow']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $employeePersonalInformationRow = $this->EmployeePersonalInformationRows->newEntity();
        if ($this->request->is('post')) {
            $employeePersonalInformationRow = $this->EmployeePersonalInformationRows->patchEntity($employeePersonalInformationRow, $this->request->data);
            if ($this->EmployeePersonalInformationRows->save($employeePersonalInformationRow)) {
                $this->Flash->success(__('The employee personal information row has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee personal information row could not be saved. Please, try again.'));
            }
        }
        $empPersonalInformations = $this->EmployeePersonalInformationRows->EmpPersonalInformations->find('list', ['limit' => 200]);
        $this->set(compact('employeePersonalInformationRow', 'empPersonalInformations'));
        $this->set('_serialize', ['employeePersonalInformationRow']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Personal Information Row id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employeePersonalInformationRow = $this->EmployeePersonalInformationRows->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeePersonalInformationRow = $this->EmployeePersonalInformationRows->patchEntity($employeePersonalInformationRow, $this->request->data);
            if ($this->EmployeePersonalInformationRows->save($employeePersonalInformationRow)) {
                $this->Flash->success(__('The employee personal information row has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee personal information row could not be saved. Please, try again.'));
            }
        }
        $empPersonalInformations = $this->EmployeePersonalInformationRows->EmpPersonalInformations->find('list', ['limit' => 200]);
        $this->set(compact('employeePersonalInformationRow', 'empPersonalInformations'));
        $this->set('_serialize', ['employeePersonalInformationRow']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee Personal Information Row id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employeePersonalInformationRow = $this->EmployeePersonalInformationRows->get($id);
        if ($this->EmployeePersonalInformationRows->delete($employeePersonalInformationRow)) {
            $this->Flash->success(__('The employee personal information row has been deleted.'));
        } else {
            $this->Flash->error(__('The employee personal information row could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
