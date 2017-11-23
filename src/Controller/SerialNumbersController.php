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
	
	
	public function getSerialNumberList(){
		$item_id=$this->request->query('item_id');
		$sr_nos=$this->request->query('sr_nos');
		$sr_nos=explode(',',$sr_nos);
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		
		$this->viewBuilder()->layout('');
		
		$options=[];$values=[];
		$serialnumbers = $this->SerialNumbers->find()->where(['company_id'=>$st_company_id,'item_id'=>$item_id,'status'=>'In']);
		foreach($serialnumbers as $serialnumber){
			$outExist = $this->SerialNumbers->exists(['SerialNumbers.parent_id' => $serialnumber->id]);
			if($outExist == 0){
				$options[]=['text' =>$serialnumber->name, 'value' => $serialnumber->id];
			}
		}
		//pr($values);exit;
        $this->set(compact('options', 'values'));
        $this->set('_serialize', ['serialNumbers']);
	}
	
	public function getSerialNumberListIV(){
		$iv_row_id=$this->request->query('iv_row_id');
		$item_id=$this->request->query('item_id');
		
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		
		$this->viewBuilder()->layout('');
		
		$options=[];$values=[];
        /* $query = $this->SerialNumbers->find()->where(['SerialNumbers.company_id'=>$st_company_id]);
		
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
		->where(['company_id'=>$st_company_id,'SerialNumbers.item_id'=>$item_id])
		->group('SerialNumbers.name')
		->autoFields(true);
		$SerialNumbers =$query->toArray(); */
		
		$query = $this->SerialNumbers->find('list')->where(['SerialNumbers.company_id'=>$st_company_id]);
		$query->where(['company_id'=>$st_company_id,'item_id'=>$item_id,'iv_row_id'=>$iv_row_id,'status'=>'In']);
		$SerialNumbers_in = $query->toArray();
		
		foreach($SerialNumbers_in as $sr_in) {
			$options[]=$sr_in;
		}
		/* 
		foreach($SerialNumbers as $serialnumbers){
			if($serialnumbers->total_in > $serialnumbers->total_out || ){
				$options[]=$serialnumbers->name;
				//$values=$sr_nos;
			}
		} */
		
        $this->set(compact('options', 'values'));
        $this->set('_serialize', ['serialNumbers']);
		
	}
	
	public function getSerialNumberListIVItemsEdit(){
		$iv_row_items=$this->request->query('iv_row_items');
		$item_id=$this->request->query('item_id');
		
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
		
		$query = $this->SerialNumbers->find('list')->where(['SerialNumbers.company_id'=>$st_company_id]);
		$query->where(['company_id'=>$st_company_id,'item_id'=>$item_id,'iv_row_items'=>$iv_row_items,'status'=>'Out']);
		$SerialNumbers_out = $query->toArray();
		
		foreach($SerialNumbers_out as $sr_out) {
			$values[]=$sr_out;
			$options[]=['text' =>$sr_out, 'value' => $sr_out];
		}
		//pr($options);exit;
		
		foreach($SerialNumbers as $serialnumbers){
			if(($serialnumbers->total_in > $serialnumbers->total_out)){
				$options[]=['text' =>$serialnumbers->name, 'value' => $serialnumbers->name];
			}	
			///$values=$sr_no;
		}
		
        $this->set(compact('options', 'values'));
        $this->set('_serialize', ['serialNumbers']);
	}
	
	public function getSerialNumberEditList(){
		$item_id=$this->request->query('item_id');
		$sr_nos=$this->request->query('sr_nos');
		$in_row_id=$this->request->query('in_row_id');
		$invoice_id=$this->request->query('invoice_id');
		//pr($invoice_id); exit;
		$sr_no=explode(',',$sr_nos);
		
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$this->viewBuilder()->layout('');
		
		
		$options=[];$values=[];
		$serialnumbers = $this->SerialNumbers->find()->where(['company_id'=>$st_company_id,'item_id'=>$item_id,'status'=>'In']);
		foreach($serialnumbers as $serialnumber){
			$outExist = $this->SerialNumbers->exists(['SerialNumbers.parent_id' => $serialnumber->id,'invoice_row_id'=>$in_row_id]);
			if($outExist > 0){
				$values[]=$serialnumber->id;
			}
			$inExist = $this->SerialNumbers->exists(['SerialNumbers.parent_id' => $serialnumber->id,'invoice_row_id != '=>$in_row_id]);
			
			if($inExist == 0){
				$options[]=['text' =>$serialnumber->name, 'value' => $serialnumber->id];
			}
		}
		//pr($in_row_id);
        $this->set(compact('options', 'values'));
        $this->set('_serialize', ['serialNumbers']);
	}	
	
	
	public function getSerialNumberSalesReturnList(){
		 $item_id=$this->request->query('item_id'); 
		 $in_row_id=$this->request->query('in_row_id'); 
		
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		
		$this->viewBuilder()->layout('');
		
		$options=[];$values=[];
        $query = $this->SerialNumbers->find('list')->where(['SerialNumbers.company_id'=>$st_company_id]);
		$query->where(['company_id'=>$st_company_id,'item_id'=>$item_id,'invoice_row_id'=>$in_row_id,'status'=>'Out']);
		$SerialNumbers_out = $query->toArray();
		
		$serialnumbers_in = $this->SerialNumbers->find('list')->where(['SerialNumbers.company_id'=>$st_company_id,'item_id'=>$item_id,'invoice_row_id'=>$in_row_id,'status'=>'In'])->toArray();

		$out_dropdown = array_diff($SerialNumbers_out,$serialnumbers_in);
		foreach($out_dropdown as $option){  	
			$options[]=['text' =>$option, 'value' => $option];
		}
		
        $this->set(compact('options', 'values','out_dropdown'));
        $this->set('_serialize', ['out_dropdown']);
	}


	public function getSerialNumberSalesReturnEditList(){
		
		$item_id=$this->request->query('item_id'); 
		$in_row_id=$this->request->query('in_row_id'); 
		$sale_row_id=$this->request->query('sale_row_id'); 
		
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		
		$this->viewBuilder()->layout('');
		
		$options=[];$values=[];
        $query = $this->SerialNumbers->find('list')->where(['SerialNumbers.company_id'=>$st_company_id]);
		$query->where(['company_id'=>$st_company_id,'item_id'=>$item_id,'invoice_row_id'=>$in_row_id,'status'=>'Out']);
		$SerialNumbers_out = $query->toArray();
		
		$serialnumbers_in = $this->SerialNumbers->find('list')->where(['SerialNumbers.company_id'=>$st_company_id,'item_id'=>$item_id,'invoice_row_id'=>$in_row_id,'status'=>'In'])->toArray();
		
		$serialnumbers_in1 = $this->SerialNumbers->find('list')->where(['SerialNumbers.company_id'=>$st_company_id,'item_id'=>$item_id,'invoice_row_id'=>$in_row_id,'status'=>'In','sales_return_row_id'=>$sale_row_id])->toArray();
		
		
		foreach($serialnumbers_in1 as $sr_in) {
			$values=$sr_in;
			$options[]=['text' =>$sr_in, 'value' => $sr_in];
		}
		
		$out_dropdown = array_diff($SerialNumbers_out,$serialnumbers_in);
		
		foreach($out_dropdown as $option){  	
			$options[]=['text' =>$option, 'value' => $option];
			
		}
		
        $this->set(compact('options', 'values','out_dropdown'));
        $this->set('_serialize', ['out_dropdown']);
	}
	
	public function getSerialNumberPurchaseReturnList(){
		$grn_row_id=$this->request->query('grn_row_id'); 
		$in_row_id=$this->request->query('in_row_id'); 
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		
		$this->viewBuilder()->layout('');
		
		$options=[];$values=[];
        $query = $this->SerialNumbers->find('list');
		$query->where(['SerialNumbers.company_id'=>$st_company_id,'grn_row_id'=>$grn_row_id,'SerialNumbers.status'=>'In']);
		$SerialNumbers_in = $query->toArray();
	//	pr($SerialNumbers_in); exit;
		$serialnumbers_out = $this->SerialNumbers->find('list')->where(['SerialNumbers.company_id'=>$st_company_id,'SerialNumbers.status'=>'Out','purchase_return_row_id >'=>0])->toArray();

		
		$out_dropdown = array_diff($SerialNumbers_in,$serialnumbers_out);
		foreach($out_dropdown as $option){  	
			$options[]=['text' =>$option, 'value' => $option];
		}
		
        $this->set(compact('options', 'values','out_dropdown'));
        $this->set('_serialize', ['out_dropdown']);
	}
	
	public function getSerialNumberPurchaseReturnEditList(){
		$item_id=$this->request->query('item_id'); 
		$in_row_id=$this->request->query('in_row_id'); 
		$purchsereturn_row_id=$this->request->query('purchsereturn_row_id'); 
		
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		
		$this->viewBuilder()->layout('');
		
		$options=[];$values=[];
        $query = $this->SerialNumbers->find('list');
		
		$query->where(['SerialNumbers.company_id'=>$st_company_id,'SerialNumbers.item_id'=>$item_id,'SerialNumbers.status'=>'In']);
		$SerialNumbers_in = $query->toArray();
		//pr($SerialNumbers_in);exit;
		$serialnumbers_out = $this->SerialNumbers->find('list')->where(['SerialNumbers.company_id'=>$st_company_id,'SerialNumbers.item_id'=>$item_id,'SerialNumbers.status'=>'Out','purchase_return_row_id >'=>0])->toArray();
		
		$serialnumbers_out1 = $this->SerialNumbers->find('list')->where(['SerialNumbers.company_id'=>$st_company_id,'item_id'=>$item_id,'SerialNumbers.status'=>'Out','purchase_return_row_id'=>$purchsereturn_row_id])->toArray();
		
		//pr($SerialNumbers_in);
		// pr($serialnumbers_out1);
		foreach($serialnumbers_out1 as $sr_in) {
			$values[]=$sr_in;
			$options[]=['text' =>$sr_in, 'value' => $sr_in];
		}
		//pr($values);
		$out_dropdown = array_diff($SerialNumbers_in,$serialnumbers_out);
		
		 foreach($out_dropdown as $option){  	
			$options[]=['text' =>$option, 'value' => $option];
			
		} 
		
        $this->set(compact('options', 'values','out_dropdown'));
        $this->set('_serialize', ['out_dropdown']);
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
