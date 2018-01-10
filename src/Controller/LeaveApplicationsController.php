<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LeaveApplications Controller
 *
 * @property \App\Model\Table\LeaveApplicationsTable $LeaveApplications
 */
class LeaveApplicationsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
		$this->viewBuilder()->layout('index_layout');
        $leaveApplications = $this->paginate($this->LeaveApplications);

        $this->set(compact('leaveApplications'));
        $this->set('_serialize', ['leaveApplications']);
    }

    /**
     * View method
     *
     * @param string|null $id Leave Application id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $leaveApplication = $this->LeaveApplications->get($id, [
            'contain' => []
        ]);

        $this->set('leaveApplication', $leaveApplication);
        $this->set('_serialize', ['leaveApplication']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
        $leaveApplication = $this->LeaveApplications->newEntity();
        if ($this->request->is('post')) {
			$files=$this->request->data['supporting_attached']; 
            $leaveApplication = $this->LeaveApplications->patchEntity($leaveApplication, $this->request->data);
			$leaveApplication->supporting_attached = $files['name'];
			$attache = $this->request->data['supporting_attached'];
			//pr($files);
            if ($this->LeaveApplications->save($leaveApplication)) {
				$target_path = 'attached_file';
				$file_name   = $_FILES['supporting_attached']['name'];
				//echo $to_path     = $target_path.$attache['name'];
				if(move_uploaded_file($files['tmp_name'], $target_path.'/'.$file_name))
				{
					$this->Flash->success(__('The leave application has been saved.'));
					return $this->redirect(['action' => 'index']);
				}
            } else {
					$this->Flash->error(__('The leave application could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('leaveApplication'));
        $this->set('_serialize', ['leaveApplication']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Leave Application id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $leaveApplication = $this->LeaveApplications->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
			$files=$this->request->data['supporting_attached']; 
            $leaveApplication = $this->LeaveApplications->patchEntity($leaveApplication, $this->request->data);
			if(!empty($files['name']))
			{
				$leaveApplication->supporting_attached = $files['name'];
			}
			else
			{
				$leaveApplication->supporting_attached = $leaveApplication->doc;
			}
            if ($this->LeaveApplications->save($leaveApplication)) {
				if(!empty($files['tmp_name']))
				{
					$target_path = 'attached_file';
					$file_name   = $_FILES['supporting_attached']['name'];
					move_uploaded_file($files['tmp_name'], $target_path.'/'.$file_name);
				}
				
                $this->Flash->success(__('The leave application has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The leave application could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('leaveApplication'));
        $this->set('_serialize', ['leaveApplication']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Leave Application id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $leaveApplication = $this->LeaveApplications->get($id);
        if ($this->LeaveApplications->delete($leaveApplication)) {
            $this->Flash->success(__('The leave application has been deleted.'));
        } else {
            $this->Flash->error(__('The leave application could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
