<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * TravelRequests Controller
 *
 * @property \App\Model\Table\TravelRequestsTable $TravelRequests
 */
class TravelRequestsController extends AppController
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
		$st_year_id = $session->read('st_year_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
        $travelRequests = $this->paginate($this->TravelRequests->find()->contain(['Employees'])->where(['employee_id'=>$s_employee_id]));

        $this->set(compact('travelRequests'));
        $this->set('_serialize', ['travelRequests']);
    }

    /**
     * View method
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $travelRequest = $this->TravelRequests->get($id, [
            'contain' => ['TravelRequestRows']
        ]);

        $this->set('travelRequest', $travelRequest);
        $this->set('_serialize', ['travelRequest']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		 $st_year_id = $session->read('st_year_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$empData=$this->TravelRequests->Employees->get($s_employee_id,['contain'=>['Designations']]);
		//pr($empData);
        $travelRequest = $this->TravelRequests->newEntity();
        if ($this->request->is('post')) {
            $travelRequest = $this->TravelRequests->patchEntity($travelRequest, $this->request->data);
			$travelRequest->employee_id=$s_employee_id;
			$EmployeeHierarchies=$this->TravelRequests->EmployeeHierarchies->find()->contain(['ParentAccountingGroups'])->where(['EmployeeHierarchies.employee_id'=>$s_employee_id])->first();
			$travelRequest->parent_employee_id=$EmployeeHierarchies->parent_accounting_group->employee_id;
			$travelRequest->travel_from_date=date('Y-m-d',strtotime($travelRequest->travel_mode_from_date));
			$travelRequest->travel_to_date=date('Y-m-d',strtotime($travelRequest->return_mode_to_date));

            if ($this->TravelRequests->save($travelRequest)) {
				
                $this->Flash->success(__('The travel request has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The travel request could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('travelRequest','empData'));
        $this->set('_serialize', ['travelRequest']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		 $st_year_id = $session->read('st_year_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		//$empData=$this->TravelRequests->Employees->get($s_employee_id,['contain'=>['Designations']]);
		
        $travelRequest = $this->TravelRequests->get($id, [
            'contain' => ['TravelRequestRows','Employees'=>['Designations']]
        ]); //pr($travelRequest);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $travelRequest = $this->TravelRequests->patchEntity($travelRequest, $this->request->data);
			$travelRequest->employee_id=$s_employee_id;
			$EmployeeHierarchies=$this->TravelRequests->EmployeeHierarchies->find()->contain(['ParentAccountingGroups'])->where(['EmployeeHierarchies.employee_id'=>$s_employee_id])->first();
			$travelRequest->parent_employee_id=$EmployeeHierarchies->parent_accounting_group->employee_id;
			$travelRequest->travel_from_date=date('Y-m-d',strtotime($travelRequest->travel_mode_from_date));
			$travelRequest->travel_to_date=date('Y-m-d',strtotime($travelRequest->return_mode_to_date));
            if ($this->TravelRequests->save($travelRequest)) {
                $this->Flash->success(__('The travel request has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The travel request could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('travelRequest'));
        $this->set('_serialize', ['travelRequest']);
    }
	
	public function approved($id = null)
    {
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
        $leaveApplication = $this->TravelRequests->get($id);
		
		$EmployeeHierarchies=$this->TravelRequests->EmployeeHierarchies->find()->contain(['ParentAccountingGroups'])->where(['EmployeeHierarchies.employee_id'=>$leaveApplication->parent_employee_id])->first();
		if($EmployeeHierarchies->parent_id != null){ 
			$query = $this->TravelRequests->query();
					$query->update()
						->set(['parent_employee_id' => $EmployeeHierarchies->parent_accounting_group->employee_id])
						->where(['id' => $id])
						->execute();
			
		}else{
			$approve_date=date('Y-m-d');
			$query = $this->TravelRequests->query();
					$query->update()
						->set(['status' =>'approved','approve_date'=>$approve_date])
						->where(['id' => $id])
						->execute();
		}
		return $this->redirect(['controller'=>'Logins','action' => 'dashbord']);
    }

	public function cancle($id = null)
    {
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
        $leaveApplication = $this->TravelRequests->get($id);
		
		$EmployeeHierarchies=$this->TravelRequests->EmployeeHierarchies->find()->contain(['ParentAccountingGroups'])->where(['EmployeeHierarchies.employee_id'=>$leaveApplication->parent_employee_id])->first();

			
			$query = $this->TravelRequests->query();
					$query->update()
						->set(['leave_status' =>'cancle'])
						->where(['id' => $id])
						->execute();
	
		return $this->redirect(['controller'=>'Logins','action' => 'dashbord']);
    }
    /**
     * Delete method
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $travelRequest = $this->TravelRequests->get($id);
        if ($this->TravelRequests->delete($travelRequest)) {
            $this->Flash->success(__('The travel request has been deleted.'));
        } else {
            $this->Flash->error(__('The travel request could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
