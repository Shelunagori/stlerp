<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EmployeePersonalInformations Controller
 *
 * @property \App\Model\Table\EmployeePersonalInformationsTable $EmployeePersonalInformations
 */
class EmployeePersonalInformationsController extends AppController
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
            'contain' => ['EmployeePersonalInformationRows']
        ];
        $employeePersonalInformations = $this->paginate($this->EmployeePersonalInformations);
        $this->set(compact('employeePersonalInformations'));
        $this->set('_serialize', ['employeePersonalInformations']);
    }

    /**
     * View method
     *
     * @param string|null $id Employee Personal Information id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employeePersonalInformation = $this->EmployeePersonalInformations->get($id, [
            'contain' => ['EmployeePersonalInformationRows']
        ]);

        $this->set('employeePersonalInformation', $employeePersonalInformation);
        $this->set('_serialize', ['employeePersonalInformation']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
        $employeePersonalInformation = $this->EmployeePersonalInformations->newEntity();
        if ($this->request->is('post')) {
			//pr($this->request->data());exit;
            $employeePersonalInformation = $this->EmployeePersonalInformations->patchEntity($employeePersonalInformation, $this->request->data);
			//pr($employeePersonalInformation);exit;
            if ($this->EmployeePersonalInformations->save($employeePersonalInformation)) {
				//pr($employeePersonalInformation);exit;
                $this->Flash->success(__('The employee personal information has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee personal information could not be saved. Please, try again.'));
            }
        }
        //$employees = $this->EmployeePersonalInformations->Employees->find('list', ['limit' => 200]);
		$states=[];
		$state_details=$this->EmployeePersonalInformations->States->find();
		if(sizeof($state_details)>0)
		{
			foreach($state_details as $state)
			{ 
				$name = $state->name.' ( '.$state->state_code.' )';
				$states[] = ['value'=>$state->id,'text'=>$name];
			}
		}
		//pr($states);exit;
        $this->set(compact('employeePersonalInformation', 'employees', 'states'));
        $this->set('_serialize', ['employeePersonalInformation']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Personal Information id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $employeePersonalInformation = $this->EmployeePersonalInformations->get($id, [
            'contain' => ['EmployeePersonalInformationRows']
        ]); 
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeePersonalInformation = $this->EmployeePersonalInformations->patchEntity($employeePersonalInformation, $this->request->data);
            if ($this->EmployeePersonalInformations->save($employeePersonalInformation)) {
                $this->Flash->success(__('The employee personal information has been saved.'));
                //pr($employeePersonalInformation);exit;
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee personal information could not be saved. Please, try again.'));
            }
        }
		$states=[];
		$state_details=$this->EmployeePersonalInformations->States->find();
		if(sizeof($state_details)>0)
		{
			foreach($state_details as $state)
			{ 
				$name = $state->name.' ( '.$state->state_code.' )';
				$states[] = ['value'=>$state->id,'text'=>$name];
			}
		}
		$districts=$this->EmployeePersonalInformations->Districts->find('list'); 
		$this->set(compact('employeePersonalInformation', 'districts', 'states'));
        $this->set('_serialize', ['employeePersonalInformation']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee Personal Information id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employeePersonalInformation = $this->EmployeePersonalInformations->get($id);
        if ($this->EmployeePersonalInformations->delete($employeePersonalInformation)) {
            $this->Flash->success(__('The employee personal information has been deleted.'));
        } else {
            $this->Flash->error(__('The employee personal information could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function getDistrictByState($state_id=null,$discrict_name=null)
	{
        $this->viewBuilder()->layout('');
		$districts = $this->EmployeePersonalInformations->Districts->find('list')->where(['Districts.state_id'=>$state_id])->order(['Districts.district' => 'ASC']);
		$this->set(compact('districts','discrict_name'));
		$this->set('_serialize', ['districts']);
    }
}
