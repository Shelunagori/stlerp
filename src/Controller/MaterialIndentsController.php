<?php
namespace App\Controller;

use App\Controller\AppController;


/**
 * MaterialIndents Controller
 *
 * @property \App\Model\Table\MaterialIndentsTable $MaterialIndents
 */
class MaterialIndentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($status=null)
    {
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		//pr($url); exit;
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		
		$status=$this->request->query('status');
		$mi_no=$this->request->query('mi_no');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		
		
		
		$this->set(compact('mi_no','From','To'));
		$where=[];
		
		if(!empty($mi_no)){
			$where['MaterialIndents.mi_number LIKE']=$mi_no;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['MaterialIndents.created_on >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['MaterialIndents.created_on <=']=$To;
		}
	
	 if($status==null or $status=='Open' ){
			$having=['total_open_rows >' => 0];
		}elseif($status=='Close'){
			$having=['total_open_rows =' => 0];
		}
	
	
	$materialIndents=$this->paginate(
			$this->MaterialIndents->find()->select(['total_open_rows' => 
				$this->MaterialIndents->find()->func()->count('MaterialIndentRows.id')])
					->leftJoinWith('MaterialIndentRows', function ($q) {
						return $q->where(['MaterialIndentRows.required_quantity >MaterialIndentRows.processed_quantity']);
					})	
					->group(['MaterialIndents.id'])
					->autoFields(true)
					->having($having)
					->where($where)
					->where(['company_id'=>$st_company_id])
					->order(['MaterialIndents.id' => 'DESC'])
			);
	 
		//pr($MaterialIndents); exit;
	  
	
        $this->set(compact('materialIndents','url','status'));
        $this->set('_serialize', ['materialIndents']);
    }
	
	public function excelExport($status=null){ 
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		
		$status=$this->request->query('status');
		$mi_no=$this->request->query('mi_no');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		
		
		
		$this->set(compact('mi_no','From','To'));
		$where=[];
		
		if(!empty($mi_no)){
			$where['MaterialIndents.mi_number LIKE']=$mi_no;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['MaterialIndents.created_on >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['MaterialIndents.created_on <=']=$To;
		}
	
	 if($status==null or $status=='Open' ){
			$having=['total_open_rows >' => 0];
		}elseif($status=='Close'){
			$having=['total_open_rows =' => 0];
		}
	
	
	$materialIndents=
			$this->MaterialIndents->find()->select(['total_open_rows' => 
				$this->MaterialIndents->find()->func()->count('MaterialIndentRows.id')])
					->leftJoinWith('MaterialIndentRows', function ($q) {
						return $q->where(['MaterialIndentRows.required_quantity >MaterialIndentRows.processed_quantity']);
					})	
					->group(['MaterialIndents.id'])
					->autoFields(true)
					->having($having)
					->where($where)
					->where(['company_id'=>$st_company_id])
					->order(['MaterialIndents.id' => 'DESC'])
			;
	 
		//pr($MaterialIndents); exit;
	  
	
        $this->set(compact('materialIndents','url','status'));
        $this->set('_serialize', ['materialIndents']);
	}
/**
     * View method
     *
     * @param string|null $id Material Indent id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->viewBuilder()->layout('index_layout');
		$materialIndent = $this->MaterialIndents->get($id, [
            'contain' => ['Companies','Creator',  'MaterialIndentRows'=>['Items']]
        ]);

        $this->set('materialIndent', $materialIndent);
        $this->set('_serialize', ['materialIndent']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
	 
	public function AddNew($material=null)
    {
		
		$this->viewBuilder()->layout('index_layout');
		$pull_request=$this->request->query('pull-request');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$mireport=$this->MaterialIndents->newEntity();
		 
		$query = $this->MaterialIndents->MaterialIndentRows->find()
			->where(['MaterialIndentRows.status'=>'open'])
			->group(['MaterialIndentRows.item_id']);
		$query->matching('MaterialIndents', function ($q) use($st_company_id){
			return $q->where(['MaterialIndents.company_id' => $st_company_id]);
		});
		$MaterialIndentRows=$query->contain(['Items']);
		//pr($MaterialIndentRows->toArray()); exit;
		
	  
		if ($this->request->is(['post'])) {
			$to_be_send=$this->request->data['to_be_send'];
			//pr($to_be_send); exit;
			$this->redirect(['controller'=>'PurchaseOrders','action' => 'add/'.json_encode($to_be_send).'']);
		}
        $this->set(compact('MaterialIndentRows','pull_request','mireport'));
        $this->set('_serialize', ['materialIndents']);
	}
    public function add($material=null)
    {
		$this->viewBuilder()->layout('index_layout');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		if(!empty($material)){
			$Employees=$this->MaterialIndents->Employees->get($s_employee_id);
			$employee_name=$Employees->name; 
			$company=$this->MaterialIndents->Companies->get($st_company_id);
			$company_name=$company->name;
			$material_items=array();
			$materials=json_decode($material);
			foreach($materials as $key=>$value){
				$item=$this->MaterialIndents->Items->get($key);
				$item_name=$item->name;
				$material_items[]=array('item_name'=>$item_name,'item_id'=>$key,'quantity'=>$value,'company_id'=>$st_company_id,'employee_name'=>$employee_name,'company_name'=>$company_name);
			}
			//pr($material_items); exit;
			$this->set(compact('material_items'));
		}

		
		
		$materialIndent = $this->MaterialIndents->newEntity();
		
		$last_company_no=$this->MaterialIndents->find()->select(['mi_number'])->where(['company_id' => $st_company_id])->order(['mi_number' => 'DESC'])->first();
		if(!empty($last_company_no)){
			$materialIndent->mi_number=$last_company_no->mi_number+1;
		}else{
			$materialIndent->mi_number=1;
		}
		
        if ($this->request->is('post')) {
            $materialIndent = $this->MaterialIndents->patchEntity($materialIndent, $this->request->data);
			$materialIndent->created_by=$s_employee_id; 
			$materialIndent->created_on=date("Y-m-d");
			$materialIndent->company_id=$st_company_id;
            if ($this->MaterialIndents->save($materialIndent)) {
				
                $this->Flash->success(__('The material indent has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else { 
                $this->Flash->error(__('The material indent could not be saved. Please, try again.'));
            }
        }
		/* $last_mi_no=$this->MaterialIndents->find()->select(['mi2'])->where(['company_id' => $st_company_id])->order(['mi2' => 'DESC'])->first();
			if($last_mi_no){
				@$last_mi_no->mi2=$last_mi_no->mi2+1;
			}else{
				@$last_mi_no->mi2=1;
			} */
		
        $companies = $this->MaterialIndents->Companies->find('list', ['limit' => 200]);
        $items = $this->MaterialIndents->Items->find('list', ['limit' => 200]);
        //$jobCards = $this->MaterialIndents->JobCards->find('list', ['limit' => 200]);
        $this->set(compact('materialIndent', 'companies','items','current_stock'));
        $this->set('_serialize', ['materialIndent']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Material Indent id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
	 public function AddToCart($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$materialIndent = $this->MaterialIndents->newEntity();
		if ($this->request->is('post')) { 
            $materialIndent = $this->MaterialIndents->patchEntity($materialIndent, $this->request->data);
			
			$materialIndent->created_by=$s_employee_id; 
			$materialIndent->created_on=date("Y-m-d");
			$materialIndent->company_id=$st_company_id;
			$last_voucher_no=$this->MaterialIndents->find()->select(['mi_number'])->where(['MaterialIndents.company_id' => $st_company_id])->order(['mi_number' => 'DESC'])->first();
			if($last_voucher_no){
				$materialIndent->mi_number=$last_voucher_no->mi_number+1;
			}else{
				$materialIndent->mi_number=1;
			}
			//pr($materialIndent); 
            if ($this->MaterialIndents->save($materialIndent)) {
				$this->MaterialIndents->ItemBuckets->deleteAll(array('1 = 1'));
				//$this->MaterialIndents->ItemBuckets->deleteAll(['is_spam' => true]);
				///$this->MaterialIndents->ItemBuckets->deleteAll();
                $this->Flash->success(__('The material indent has been saved.'));
			
                return $this->redirect(['action' => 'index']);
            } else { 
                $this->Flash->error(__('The material indent could not be saved. Please, try again.'));
            }
        }
		
	
		$ItemBuckets = $this->MaterialIndents->ItemBuckets->find()->contain(['Items'])->toArray();
		
		$this->set(compact('ItemBuckets','materialIndent','total_indent'));
	}
	 
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
        $materialIndent = $this->MaterialIndents->get($id, [
            'contain' => ['MaterialIndentRows'=>['Items']]
        ]); 
        if ($this->request->is(['patch', 'post', 'put'])) {
            $materialIndent = $this->MaterialIndents->patchEntity($materialIndent, $this->request->data);
			foreach($materialIndent->material_indent_rows as $material_indent_row){
				$material_indent_row->required_quantity+=$material_indent_row->processed_quantity;
			}
			if ($this->MaterialIndents->save($materialIndent)) {
				
                $this->Flash->success(__('The material indent has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The material indent could not be saved. Please, try again.'));
            }
        }
        $companies = $this->MaterialIndents->Companies->find('list', ['limit' => 200]);
      
        $this->set(compact('materialIndent', 'companies', 'jobCards'));
        $this->set('_serialize', ['materialIndent']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Material Indent id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        //$this->request->allowMethod(['post', 'delete']);
        $ItemBucket = $this->MaterialIndents->ItemBuckets->get($id);
        if ($this->MaterialIndents->ItemBuckets->delete($ItemBucket)) {
            $this->Flash->success(__('The Item has been deleted.'));
        } else {
            $this->Flash->error(__('The Item could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'AddToCart']);
    }
	
	
	
	public function report()
	{
		$this->viewBuilder()->layout('index_layout');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$mi = $this->MaterialIndents->newEntity();
		
		if ($this->request->is('post')) {
			
			$mi_data=$this->request->data['selected_data'];

 			$check=json_encode(); 
			$this->redirect(['controller'=>'PurchaseOrders','action' => 'add/'.$check.'']);
		}
		
		$materialIndents=$this->MaterialIndents->find()->contain(['MaterialIndentRows'=>['Items']])->toArray();
		$this->set(compact('materialIndents','mi'));
	}
}
