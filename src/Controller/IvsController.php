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
        $this->paginate = [
            'contain' => ['Invoices', 'Companies']
        ];
        $ivs = $this->paginate($this->Ivs);

        $this->set(compact('ivs'));
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
        $iv = $this->Ivs->get($id, [
            'contain' => ['Invoices', 'Companies', 'IvRows']
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
		
		$Invoice=$this->Ivs->Invoices->get($invoice_id, [
			'contain' => ['InvoiceRows'=>['Items'=>function($p) use($st_company_id){
				return $p->where(['Items.source IN'=>['Assembled','Manufactured','Purchessed/Manufactured']])->contain(['ItemCompanies']);
			}]],
			'conditions' => ['Invoices.company_id'=>$st_company_id]
		]);
		
		$item_display=[];
		
		foreach($Invoice->invoice_rows as $invoice_row){
			$so_row_id=$invoice_row->sales_order_row_id;
			$salesorderrows=$this->Ivs->Invoices->InvoiceRows->SalesOrderRows->find()->where(['SalesOrderRows.id'=>$so_row_id])->first();
			if($invoice_row->item->source=='Purchessed/Manufactured'){ 
				if($salesorderrows->source_type=="Manufactured"){
					$item_display[$invoice_row->id]=$invoice_row->item->name; 
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
           // pr($iv); exit;
			if ($this->Ivs->save($iv)) {
				foreach($iv->iv_rows as $iv_row){   
					/////For In
					$serial_numbers_iv_row = $iv_row->serial_numbers;
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
        $this->set(compact('iv', 'Invoice', 'Items','item_display','invoice_id'));
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
        $iv = $this->Ivs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $iv = $this->Ivs->patchEntity($iv, $this->request->data);
            if ($this->Ivs->save($iv)) {
                $this->Flash->success(__('The iv has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The iv could not be saved. Please, try again.'));
            }
        }
        $invoices = $this->Ivs->Invoices->find('list', ['limit' => 200]);
        $companies = $this->Ivs->Companies->find('list', ['limit' => 200]);
        $this->set(compact('iv', 'invoices', 'companies'));
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
