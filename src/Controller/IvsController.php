<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Ivs Controller
 *
 * @property \App\Model\Table\IvsTable $Ivs
 */
class IvsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
		$this->viewBuilder()->layout('index_layout');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$iv_no=$this->request->query('iv_no');
		$invoice_no=$this->request->query('invoice_no');
		$customer=$this->request->query('customer');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$this->set(compact('iv_no','customer','invoice_no','From','To'));
		$where=[];
		if(!empty($invoice_no)){
			$where['Invoices.in2 LIKE']=$invoice_no;
		}
		if(!empty($iv_no)){
			$where['Ivs.iv_number LIKE']=$iv_no;
		}
		if(!empty($customer)){
			$where['Customers.customer_name LIKE']='%'.$customer.'%';
		}
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Ivs.transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Ivs.transaction_date <=']=$To;
		}
        $this->paginate = [
            'contain' => ['Invoices'=>['Customers'],'IvRows']
        ];
		
        $this->paginate = [
            'contain' => ['Invoices'=>['Customers'],'IvRows', 'Companies']
        ];
		
		$ivs = $this->paginate($this->Ivs->find()->contain(['Invoices'])->where($where)->where(['ivs.company_id'=>$st_company_id])->order(['ivs.id' => 'DESC']));
        $this->set(compact('ivs','url'));
        $this->set('_serialize', ['ivs']);
    }

    /**
     * View method
     *
     * @param string|null $id Iv id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$iv = $this->Ivs->get($id, [
            'contain' => ['Invoices'=>['InvoiceRows'=>['Items'],'Customers'],'IvRows'=>['Items'=>['SerialNumbers','ItemCompanies'],'IvRowItems'=>['Items'=>['SerialNumbers']]], 'Companies']
        ]);

        $this->set('iv', $iv);
        $this->set('_serialize', ['iv']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($invoice_id=null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];

		
		$Invoice=$this->Ivs->Invoices->get($invoice_id, [
			'contain' => ['InvoiceRows'=>['Items'=>function($p) use($st_company_id){
				return $p->where(['Items.source IN'=>['Assembled','Manufactured','Purchessed/Manufactured']])->contain(['ItemCompanies']);
			}]],
			'conditions' => ['Invoices.company_id'=>$st_company_id]
		]);
		
		$item_display=[];
		$job_card_status='yes';
		foreach($Invoice->invoice_rows as $invoice_row){
			$so_row_id=$invoice_row->sales_order_row_id;
			$salesorderrows=$this->Ivs->Invoices->InvoiceRows->SalesOrderRows->find()->where(['SalesOrderRows.id'=>$so_row_id])->first();
			if($invoice_row->item->source=='Purchessed/Manufactured'){ 
				if(@$salesorderrows->source_type=="Manufactured"){
					$item_display[$invoice_row->id]=$invoice_row->item->name; 
				}else if(@$salesorderrows->source_type==""){
					$job_card_status='no'; 
				}
			}elseif($invoice_row->item->source=='Assembled' or $invoice_row->item->source=='Manufactured'){
				$item_display[$invoice_row->id]=$invoice_row->item->name; 
			}
		}
		
        $iv = $this->Ivs->newEntity();
        if ($this->request->is('post')) {
            $iv = $this->Ivs->patchEntity($iv, $this->request->data, [
								'associated' => ['IvRows', 'IvRows.IvRowItems']
							]);
			
			$iv->invoice_id=$invoice_id;
			$iv->company_id=$st_company_id	;
			$last_voucher_no=$this->Ivs->find()->select(['Ivs.voucher_no'])->where(['company_id' => $st_company_id])->order(['voucher_no' => 'DESC'])->first();
			if($last_voucher_no){
				$iv->voucher_no=$last_voucher_no->voucher_no+1;
			}else{
				$iv->voucher_no=1;
			}
			
			$iv->created_by=$s_employee_id;
			
          //pr($iv); exit;
			if ($this->Ivs->save($iv)) {
				foreach($iv->iv_rows as $iv_row){   
					/////For In
					$serial_numbers_iv_row = array_filter($iv_row->serial_numbers);
					 if(!empty($serial_numbers_iv_row)){
						foreach($serial_numbers_iv_row as $sr_nos){
						 $query = $this->Ivs->IvRows->SerialNumbers->query();
									$query->insert(['name', 'item_id', 'status', 'iv_row_id','company_id'])
									->values([
									'name' => $sr_nos,
									'item_id' => $iv_row->item_id,
									'status' => 'In',
									'iv_row_id' => $iv_row->id,
									'company_id'=>$st_company_id
									]);
								$query->execute(); 
						}
					} 
				 	foreach($iv_row->iv_row_items as $iv_row_item){
						//// For Out
						$serial_numbers_iv_row_item = $iv_row_item->serial_numbers;
						if(!empty($serial_numbers_iv_row_item)){
						foreach($serial_numbers_iv_row_item as $sr_nos_out){
							 $query = $this->Ivs->IvRows->SerialNumbers->query();
										$query->insert(['name', 'item_id', 'status', 'iv_row_items','company_id'])
										->values([
										'name' => $sr_nos_out,
										'item_id' => $iv_row_item->item_id,
										'status' => 'Out',
										'iv_row_items' => $iv_row_item->id,
										'company_id'=>$st_company_id
										]);
									$query->execute(); 
							}
						}
					} 
				}
                $this->Flash->success(__('The iv has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The iv could not be saved. Please, try again.'));
            }
        }
		
		$Items=$this->Ivs->IvRows->Items->find('list');
        $this->set(compact('iv', 'Invoice', 'Items','item_display','invoice_id','job_card_status'));
        $this->set('_serialize', ['iv']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Iv id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
        $iv = $this->Ivs->get($id, [
            'contain' => ['IvRows'=>['Items'=>['ItemCompanies'],'IvRowItems'=>['Items'=>['ItemCompanies']]],'Invoices'=>['InvoiceRows']]
        ]);
		//pr($iv);exit;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $iv = $this->Ivs->patchEntity($iv, $this->request->data);
			$iv->created_by=$s_employee_id;
            if ($this->Ivs->save($iv)) { 
				foreach($iv->iv_rows as $iv_row){   
					$this->Ivs->IvRows->SerialNumbers->deleteAll(['SerialNumbers.iv_row_id' => $iv_row->id,'SerialNumbers.company_id'=>$st_company_id,'status'=>'In']);
					/////For In
					$serial_numbers_iv_row = array_filter($iv_row->serial_numbers);
					 if(!empty($serial_numbers_iv_row)){
						foreach($serial_numbers_iv_row as $sr_nos){
						 $query = $this->Ivs->IvRows->SerialNumbers->query();
									$query->insert(['name', 'item_id', 'status', 'iv_row_id','company_id'])
									->values([
									'name' => $sr_nos,
									'item_id' => $iv_row->item_id,
									'status' => 'In',
									'iv_row_id' => $iv_row->id,
									'company_id'=>$st_company_id
									]);
								$query->execute(); 
						}
					} 
				 	foreach($iv_row->iv_row_items as $iv_row_item){ 
						$this->Ivs->IvRows->SerialNumbers->deleteAll(['SerialNumbers.iv_row_items' => $iv_row_item['id'],'SerialNumbers.company_id'=>$st_company_id,'status'=>'Out']);
						//// For Out
						$serial_numbers_iv_row_item = $iv_row_item['serial_numbers'];
						if(!empty($serial_numbers_iv_row_item)){
						foreach($serial_numbers_iv_row_item as $sr_nos_out){
							 $query = $this->Ivs->IvRows->SerialNumbers->query();
										$query->insert(['name', 'item_id', 'status', 'iv_row_items','company_id'])
										->values([
										'name' => $sr_nos_out,
										'item_id' => $iv_row_item['item_id'],
										'status' => 'Out',
										'iv_row_items' => $iv_row_item['id'],
										'company_id'=>$st_company_id
										]);
									$query->execute(); 
							}
						}
					} 
				}
				
				
                $this->Flash->success(__('The iv has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The iv could not be saved. Please, try again.'));
            }
        }
		$Items=$this->Ivs->IvRows->Items->find('list');
        $this->set(compact('iv', 'invoices', 'Items'));
        $this->set('_serialize', ['iv']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Iv id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $iv = $this->Ivs->get($id);
        if ($this->Ivs->delete($iv)) {
            $this->Flash->success(__('The iv has been deleted.'));
        } else {
            $this->Flash->error(__('The iv could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
