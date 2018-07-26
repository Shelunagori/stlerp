<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EmployeeWorkExperiences Controller
 *
 * @property \App\Model\Table\EmployeeWorkExperiencesTable $EmployeeWorkExperiences
 */
class EmployeeWorkExperiencesController extends AppController
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
        $employeeWorkExperiences = $this->paginate($this->EmployeeWorkExperiences->find()->where(['employee_id'=>$employeeID]));
		
		$employeeWorkExperience = $this->EmployeeWorkExperiences->newEntity();
        if ($this->request->is('post')) {
            $employeeWorkExperience = $this->EmployeeWorkExperiences->patchEntity($employeeWorkExperience, $this->request->data);
            if ($this->EmployeeWorkExperiences->save($employeeWorkExperience)) {
                $this->Flash->success(__('The employee work experience has been saved.'));

                return $this->redirect(['action' => 'index',$employeeID]);
            } else {
                $this->Flash->error(__('The employee work experience could not be saved. Please, try again.'));
            }
        }
        $Employee = $this->EmployeeWorkExperiences->Employees->get($employeeID);
        $this->set(compact('employeeWorkExperience', 'Employee', 'employeeWorkExperiences', 'employeeID'));
        $this->set('_serialize', ['employeeWorkExperience']);
    }

    /**
     * View method
     *
     * @param string|null $id Employee Work Experience id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employeeWorkExperience = $this->EmployeeWorkExperiences->get($id, [
            'contain' => ['Employees']
        ]);

        $this->set('employeeWorkExperience', $employeeWorkExperience);
        $this->set('_serialize', ['employeeWorkExperience']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $employeeWorkExperience = $this->EmployeeWorkExperiences->newEntity();
        if ($this->request->is('post')) {
            $employeeWorkExperience = $this->EmployeeWorkExperiences->patchEntity($employeeWorkExperience, $this->request->data);
            if ($this->EmployeeWorkExperiences->save($employeeWorkExperience)) {
                $this->Flash->success(__('The employee work experience has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee work experience could not be saved. Please, try again.'));
            }
        }
        $employees = $this->EmployeeWorkExperiences->Employees->find('list', ['limit' => 200]);
        $this->set(compact('employeeWorkExperience', 'employees'));
        $this->set('_serialize', ['employeeWorkExperience']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Work Experience id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employeeWorkExperience = $this->EmployeeWorkExperiences->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeeWorkExperience = $this->EmployeeWorkExperiences->patchEntity($employeeWorkExperience, $this->request->data);
            if ($this->EmployeeWorkExperiences->save($employeeWorkExperience)) {
                $this->Flash->success(__('The employee work experience has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee work experience could not be saved. Please, try again.'));
            }
        }
        $employees = $this->EmployeeWorkExperiences->Employees->find('list', ['limit' => 200]);
        $this->set(compact('employeeWorkExperience', 'employees'));
        $this->set('_serialize', ['employeeWorkExperience']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee Work Experience id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null,$employeeID)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employeeWorkExperience = $this->EmployeeWorkExperiences->get($id);
        if ($this->EmployeeWorkExperiences->delete($employeeWorkExperience)) {
            $this->Flash->success(__('The employee work experience has been deleted.'));
        } else {
            $this->Flash->error(__('The employee work experience could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index',$employeeID]);
    }
}
