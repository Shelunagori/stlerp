<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SerialNumbers Controller
 *
 * @property \App\Model\Table\SerialNumbersTable $SerialNumbers
 */
class SerialNumbersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Items', 'IvRows']
        ];
        $serialNumbers = $this->paginate($this->SerialNumbers);

        $this->set(compact('serialNumbers'));
        $this->set('_serialize', ['serialNumbers']);
    }
	
	
	public function getSerialNumberList($item_id=null){
		$item_id=$this->request->query('item_id');
		$sr_nos=$this->request->query('sr_nos');
		$sr_nos=explode(',',$sr_nos);
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		
		$this->viewBuilder()->layout('');
		
		$options=[];$values=[];
        $query = $this->SerialNumbers->find()->where(['SerialNumbers.company_id'=>$st_company_id]);
		
		$totalInCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['status' => 'In']),
				$query->newExpr()->add(['name']),
				'integer'
			);
		$totalOutCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['status' => 'Out']),
				$query->newExpr()->add(['name']),
				'integer'
			);

			
		$query->select([
			'total_in' => $query->func()->count($totalInCase),
			'total_out' => $query->func()->count($totalOutCase),'id','item_id'
		])
		->where(['company_id'=>$st_company_id,'item_id'=>$item_id])
		->group('SerialNumbers.name')
		->autoFields(true);
		$SerialNumbers =$query->toArray();
		
		foreach($SerialNumbers as $serialnumbers){
			if($serialnumbers->total_in > $serialnumbers->total_out){
				$options[]=['text' =>$serialnumbers->name, 'value' => $serialnumbers->name];
				//$values=$sr_nos;
			}
		}
		//pr($values);exit;
        $this->set(compact('options', 'values'));
        $this->set('_serialize', ['serialNumbers']);
	}
	
	
	
	public function getSerialNumberEditList(){
		$item_id=$this->request->query('item_id');
		$sr_nos=$this->request->query('sr_nos');
		$sr_no=explode(',',$sr_nos);
		
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		
		$this->viewBuilder()->layout('');
		
		$options=[];$values=[];
        $query = $this->SerialNumbers->find()->where(['SerialNumbers.company_id'=>$st_company_id]);
		
		$totalInCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['status' => 'In']),
				$query->newExpr()->add(['name']),
				'integer'
			);
		$totalOutCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['status' => 'Out']),
				$query->newExpr()->add(['name']),
				'integer'
			);

			
		$query->select([
			'total_in' => $query->func()->count($totalInCase),
			'total_out' => $query->func()->count($totalOutCase)
		])
		->where(['company_id'=>$st_company_id,'item_id'=>$item_id])
		->group('SerialNumbers.name')
		->autoFields(true);
		$SerialNumbers =$query->toArray();
		
		foreach($SerialNumbers as $serialnumbers){
			if(($serialnumbers->total_in > $serialnumbers->total_out) || (in_array($serialnumbers->name,$sr_no))){
				$options[]=['text' =>$serialnumbers->name, 'value' => $serialnumbers->name];
			}	
			$values=$sr_no;
		}
		//pr($values);exit;
        $this->set(compact('options', 'values'));
        $this->set('_serialize', ['serialNumbers']);
	}

	public function getSerialNumberEditListById(){
		$row_id=$this->request->query('inventory_transfer_voucher_row_id');
		$sr_nos=$this->request->query('sr_nos');
		$sr_no=explode(',',$sr_nos);//pr($sr_no);exit;
		
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		
		$this->viewBuilder()->layout('');
		
		$options=[];$values=[];
        $query = $this->SerialNumbers->find()->where(['SerialNumbers.company_id'=>$st_company_id]);
		
		$totalInCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['status' => 'In']),
				$query->newExpr()->add(['name']),
				'integer'
			);
		$totalOutCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['status' => 'Out']),
				$query->newExpr()->add(['name']),
				'integer'
			);

			
		$query->select([
			'total_in' => $query->func()->count($totalInCase),
			'total_out' => $query->func()->count($totalOutCase)
		])
		->where(['company_id'=>$st_company_id,'itv_row_id'=>$row_id])
		->group('SerialNumbers.name')
		->autoFields(true);
		$SerialNumbers =$query->toArray();
		
		foreach($SerialNumbers as $serialnumbers){
			if(($serialnumbers->total_in > $serialnumbers->total_out) || (in_array($serialnumbers->name,$sr_no))){
				$options[]=['text' =>$serialnumbers->name, 'value' => $serialnumbers->name];
			}	
			$values=$sr_no;
		}
		//pr($options);exit;
        $this->set(compact('options', 'values'));
        $this->set('_serialize', ['serialNumbers']);
	}
    /**
     * View method
     *
     * @param string|null $id Serial Number id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $serialNumber = $this->SerialNumbers->get($id, [
            'contain' => ['Items', 'IvRows']
        ]);

        $this->set('serialNumber', $serialNumber);
        $this->set('_serialize', ['serialNumber']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $serialNumber = $this->SerialNumbers->newEntity();
        if ($this->request->is('post')) {
            $serialNumber = $this->SerialNumbers->patchEntity($serialNumber, $this->request->data);
            if ($this->SerialNumbers->save($serialNumber)) {
                $this->Flash->success(__('The serial number has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The serial number could not be saved. Please, try again.'));
            }
        }
        $items = $this->SerialNumbers->Items->find('list', ['limit' => 200]);
        $ivRows = $this->SerialNumbers->IvRows->find('list', ['limit' => 200]);
        $this->set(compact('serialNumber', 'items', 'ivRows'));
        $this->set('_serialize', ['serialNumber']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Serial Number id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $serialNumber = $this->SerialNumbers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $serialNumber = $this->SerialNumbers->patchEntity($serialNumber, $this->request->data);
            if ($this->SerialNumbers->save($serialNumber)) {
                $this->Flash->success(__('The serial number has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The serial number could not be saved. Please, try again.'));
            }
        }
        $items = $this->SerialNumbers->Items->find('list', ['limit' => 200]);
        $ivRows = $this->SerialNumbers->IvRows->find('list', ['limit' => 200]);
        $this->set(compact('serialNumber', 'items', 'ivRows'));
        $this->set('_serialize', ['serialNumber']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Serial Number id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $serialNumber = $this->SerialNumbers->get($id);
        if ($this->SerialNumbers->delete($serialNumber)) {
            $this->Flash->success(__('The serial number has been deleted.'));
        } else {
            $this->Flash->error(__('The serial number could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
