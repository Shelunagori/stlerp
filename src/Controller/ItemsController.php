<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Items Controller
 *
 * @property \App\Model\Table\ItemsTable $Items
 */
class ItemsController extends AppController
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
		$where=[];
		//$where1=[];
		$item_name=$this->request->query('item_name');
		$item_category=$this->request->query('item_category');
		$item_group=$this->request->query('item_group');
		$item_subgroup=$this->request->query('item_subgroup');
		//$serial_no=$this->request->query('serial_no');
		$page=$this->request->query('page');
		
		$where1['ItemCompanies.company_id']=$st_company_id;

		$this->set(compact('item_name','item_category','item_group','item_subgroup','serial_no'));
		
		if(!empty($item_name)){ 
			$where['Items.name LIKE']='%'.$item_name.'%';
		}
		
		if(!empty($item_category)){
			$where['Items.item_category_id']=$item_category;
		}
		
		if(!empty($item_group)){
			$where['Items.item_group_id']=$item_group;
		}
		
		if(!empty($item_subgroup)){
			$where['Items.item_sub_group_id']=$item_subgroup;
		}
		/* $serialnoss = ['0','1'];
		if(in_array($serial_no, $serialnoss)){ 
			$where1['ItemCompanies.serial_number_enable']=$serial_no;
		}
		 */
		 //pr($where1);exit;
        $Items =$this->paginate($this->Items->find()->contain(['ItemCategories','ItemGroups','ItemSubGroups','Units',
				'ItemCompanies'=> function ($q)use($where1) {
				return $q->where($where1);
				}])
				->where($where)->order(['Items.name' => 'ASC']));
		//pr($Items->toArray());exit;
		$ItemCategories = $this->Items->ItemCategories->find('list');
		$ItemGroups = $this->Items->ItemGroups->find('list');
		$ItemSubGroups = $this->Items->ItemSubGroups->find('list');
		$serialnos = [];
		$serialnos= [['text'=>'Enable','value'=>'1'],['text'=>'Disable','value'=>'0']];
        $this->set(compact('Items','ItemCategories','ItemGroups','ItemSubGroups','serialnos'));
        $this->set('_serialize', ['Items']);
    }

    /**
     * View method
     *
     * @param string|null $id Item id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $item = $this->Items->get($id, [
            'contain' => [ 'Units', 'ItemUsedByCompanies']
        ]);

        $this->set('item', $item);
        $this->set('_serialize', ['item']);
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
        $item = $this->Items->newEntity();
        if ($this->request->is('post')) {
            $item = $this->Items->patchEntity($item, $this->request->data);
			//pr($this->request->data['companies']);exit;
			if ($this->Items->save($item)) {
				foreach($this->request->data['companies']['serial_number_enable'] as $key=>$sr_nos){
					if(!empty($sr_nos)){
						$query = $this->Items->ItemCompanies->query();
								$query->update()
									->set(['serial_number_enable' => $sr_nos])
									->where(['company_id' => $key,'item_id'=>$item->id])
									->execute();
					}
					
				}
				 $this->Flash->success(__('The item has been saved.'));

                return $this->redirect(['action' => 'Add']);
            } else { 
                $this->Flash->error(__('The item could not be saved. Please, try again.'));
            }
        }
		$ItemCategories = $this->Items->ItemCategories->find('list')->order(['ItemCategories.name' => 'ASC']);
        $units = $this->Items->Units->find('list')->order(['Units.name' => 'ASC']);
		$Companies = $this->Items->Companies->find('list');
		$Companiess = $this->Items->Companies->find();
		//$sources = $this->Items->Sources->find('list', ['Sources' => 200]);
        $this->set(compact('item','ItemCategories', 'units', 'Companies','sources','Companiess'));
        $this->set('_serialize', ['item']);

    }

    /**
     * Edit method
     *
     * @param string|null $id Item id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
        $item = $this->Items->get($id, [
            'contain' => ['Companies']
        ]);
	
        if ($this->request->is(['patch', 'post', 'put'])) {
            $item = $this->Items->patchEntity($item, $this->request->data);
			$item->ob_quantity=$item->ob_quantity;
            if ($this->Items->save($item)) {
				/* $item_id=$item->id;
				$this->Items->ItemLedgers->deleteAll(['source_id' => $item_id, 'source_model' => 'Items']);
				$this->Items->ItemSerialNumbers->deleteAll(['master_item_id' => $item_id,'status'=>'In']);
				$itemLedger = $this->Items->ItemLedgers->newEntity();
					$itemLedger->item_id = $item_id;
					$itemLedger->quantity = $item->ob_quantity;
					$itemLedger->company_id = $st_company_id;
					$itemLedger->source_model = 'Items';
					$itemLedger->source_id = $item_id;
					$itemLedger->in_out = 'In';
					$itemLedger->processed_on = date("Y-m-d");
					if($item->ob_quantity>0)
					{
						$this->Items->ItemLedgers->save($itemLedger);
					} */
					
				
                $this->Flash->success(__('The item has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The item could not be saved. Please, try again.'));
            }
        }
		$ItemCategories = $this->Items->ItemCategories->find('list')->order(['ItemCategories.name' => 'ASC']);
		$ItemGroups = $this->Items->ItemGroups->find('list')->where(['item_category_id'=>$item->item_category_id])->order(['ItemGroups.name' => 'ASC']);
		$ItemSubGroups = $this->Items->ItemSubGroups->find('list')->where(['item_group_id'=>$item->item_group_id])->order(['ItemSubGroups.name' => 'ASC']);
        $units = $this->Items->Units->find('list')->order(['Units.name' => 'ASC']);
		$Companies = $this->Items->Companies->find('list');
		//$sources = $this->Items->Sources->find('list', ['Sources' => 200]);
        $this->set(compact('item','ItemCategories','ItemGroups','ItemSubGroups', 'units', 'Companies'));
        $this->set('_serialize', ['item']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Item id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
		$QuotationRowsexists = $this->Items->QuotationRows->exists(['item_id' => $id]);
		$SalesOrderRowsexists = $this->Items->SalesOrderRows->exists(['item_id' => $id]);
		$InvoiceRowsexists = $this->Items->InvoiceRows->exists(['item_id' => $id]);
		if(!$QuotationRowsexists and !$SalesOrderRowsexists and !$InvoiceRowsexists){
			$item = $this->Items->get($id);
			//$this->Items->ItemLedgers->deleteAll(['source_id' => $id, 'source_model' => 'Items']);
			if ($this->Items->delete($item)) {
				$this->Flash->success(__('The item has been deleted.'));
			} else {
				$this->Flash->error(__('The item could not be deleted. Please, try again.'));
			}			
		}elseif($QuotationRowsexists){
			$this->Flash->error(__('Once the item has used in quotation, the item cannot be deleted.'));
		}elseif($SalesOrderRowsexists){
			$this->Flash->error(__('Once the item has used in sales-order, the item cannot be deleted.'));
		}elseif($InvoiceRowsexists){
			$this->Flash->error(__('Once the item has used in invoice, the item cannot be deleted.'));
		}
        

        return $this->redirect(['action' => 'index']);
    }
	
	public function openingBalance(){
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Items->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		
		$ItemLedger = $this->Items->ItemLedgers->newEntity();
		/* $Items=$this->Items->find()->matching('ItemCompanies', function ($q) use($st_company_id) {
			return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
			 
		});
		*/
		$Items=$this->Items->find()->order(['Items.name' => 'ASC'])->matching(
						'ItemCompanies', function ($q) use($st_company_id) {
							return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
						}
					);	
				
		$ItemsOptions=[];
		foreach($Items as $item){ 
					$ItemsOptions[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
		}
		
		if ($this->request->is('post')) {
			$item_id=$this->request->data['Item_id'];
		
			$ItemLedgersexists = $this->Items->ItemLedgers->exists(['source_model'=>'Items','item_id' => $item_id,'company_id'=>$st_company_id]);
			if($ItemLedgersexists){
				$this->Flash->error(__('Item opening balance already exists'));
				return $this->redirect(['action' => 'openingBalance']);
			}
			
			
			$ItemLedger->item_id = $this->request->data['Item_id'];
			$ItemLedger->quantity = $this->request->data['quantity'];
			$ItemLedger->rate = $this->request->data['rate'];
			$ItemLedger->source_model = 'Items';
			$ItemLedger->source_id = $this->request->data['Item_id'];
			$ItemLedger->company_id = $st_company_id;
			$ItemLedger->rate_updated = 'Yes';
			$ItemLedger->in_out = 'In';
			$ItemLedger->left_item_id = 0;
			$ItemLedger->processed_on = date('Y-m-d',strtotime($this->request->data['date']));
			//pr($this->request->data());exit;
			$this->Items->ItemLedgers->save($ItemLedger);
			
			if($this->request->data['serial_number_enable']==1){
				
				foreach($this->request->data['serial_numbers'] as $serial_number)
				{
					$ItemSerialNumber = $this->Items->SerialNumbers->newEntity();
					$ItemSerialNumber->name = $serial_number[0];
					$ItemSerialNumber->item_id = $this->request->data['Item_id'];
					$ItemSerialNumber->status = 'In';
					$ItemSerialNumber->company_id = $st_company_id;
					$ItemSerialNumber->is_opening_balance = 'Yes';
					$ItemSerialNumber->transaction_date = date('Y-m-d',strtotime($this->request->data['date']));
					$this->Items->SerialNumbers->save($ItemSerialNumber);
				}
			}
			$this->Flash->success(__('Item Opening Balance has been saved.'));
			return $this->redirect(['action' => 'Opening-Balance']);
		}
		
		$this->set(compact('Items','ItemLedger','financial_year','ItemCompanies','ItemsOptions'));
		$this->set('_serialize', ['ItemLedger']);
	}
	
	public function openingBalanceView(){
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$where=[];
		$item=$this->request->query('item');
		//pr($item); exit;
		$this->set(compact('item'));
		
		if(!empty($item)){
			$where['Items.name LIKE']='%'.$item.'%';
		}
		
		$ItemLedgers=$this->Items->ItemLedgers->find()->where($where)->where(['source_model'=>'Items','quantity !='=>0,'company_id'=>$st_company_id])->order(['ItemLedgers.processed_on'])->contain(['Items'=>['ItemCompanies'=>function($q) use($st_company_id){
			return $q->where(['ItemCompanies.company_id'=>$st_company_id]);
		}]]);
		
		$this->set(compact('ItemLedgers'));
	}
	
	public function EditItemOpeningBalance($id = null){
		
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		
		
		$financial_year = $this->Items->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$ItemLedger = $this->Items->ItemLedgers->get($id);

		$Items = $this->Items->find()->where(['id'=>$ItemLedger->item_id])->first();

		$SerialNumberEnable = $this->Items->ItemCompanies->find()->where(['item_id'=>$ItemLedger->item_id
		,'company_id'=>$st_company_id])->toArray();
		$ItemSerialNumbers = $this->Items->SerialNumbers->find()->where(['item_id'=>$ItemLedger->item_id
		,'company_id'=>$st_company_id,'is_opening_balance'=>'Yes'])->toArray();
		
		$itmsr=[];
		foreach($ItemSerialNumbers as $itmsrnos){
			$outExist = $this->Items->ItemLedgers->SerialNumbers->exists(['SerialNumbers.parent_id' => $itmsrnos->id]);
			if($outExist){
				$itmsr[$itmsrnos->id]="Yes";	
			}else{
				$itmsr[$itmsrnos->id]="No";	
			}
		}
		//pr($itmsr); exit;
		if ($this->request->is(['patch', 'post', 'put'])) {
			$item_id=$this->request->data['item_id'];
			$serial_number_enable=$this->request->data['serial_number_enable'];
			$oldquantity = $this->request->data['quantity'];
			$newquantity = $this->request->data['new_quantity'];
			$totalquantity = $oldquantity + $newquantity ;
			$ItemLedger=$this->Items->ItemLedgers->find()->where(['item_id'=>$ItemLedger->item_id,'company_id'=>$st_company_id,'source_model'=>'Items'])->first();
			
			$ItemLedger=$this->Items->ItemLedgers->get($ItemLedger->id);
			$ItemLedger->quantity=$totalquantity;
			$ItemLedger->rate=$this->request->data['rate'];
			$date=$this->request->data['date'];
			$ItemLedger->processed_on=date("Y-m-d",strtotime($date)); 
			$rows=@$this->request->data['serial_numbers'];
			if($rows>0){
			
			if($serial_number_enable == '1'){
			foreach($this->request->data['serial_numbers'] as $serial_number){
					$ItemSerialNumber = $this->Items->SerialNumbers->newEntity();
					$ItemSerialNumber->name = $serial_number[0];
					$ItemSerialNumber->item_id = $ItemLedger->item_id;
					$ItemSerialNumber->status = 'In';
					$ItemSerialNumber->company_id = $st_company_id;
					$ItemSerialNumber->is_opening_balance = 'Yes';
					$ItemSerialNumber->transaction_date = date("Y-m-d",strtotime($date));
					$this->Items->SerialNumbers->save($ItemSerialNumber);
				}
			}	
		}
				
			if($totalquantity != 0){	
			$this->Items->ItemLedgers->save($ItemLedger);
			$this->Flash->success(__('Item Opening Balance has been saved.'));
			}else{
				$this->Flash->error(__('Item Opening Balance cant not save when Quantity is 0.Please Enter Quantity'));
			}
			
			
			
			return $this->redirect(['action' => 'EditItemOpeningBalance/'.$id]);
		}
		
		
		
		$this->set(compact('Items','ItemLedger','financial_year','ItemSerialNumbers',
		'SerialNumberEnable','itmsr'));
		$this->set('_serialize', ['ItemLedger']);
	}	
	
	public function checkSerial($item_id = null){
		$this->viewBuilder()->layout('');
		
		//pr($item_id);exit;
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$ItemCompanies = $this->Items->ItemCompanies->find()->where(['company_id'=>$st_company_id,'item_id'=>$item_id])->first();
		//pr($ItemCompanies);
		$this->set(compact('ItemCompanies'));
		
	}
	
	
	public function DeleteItemOpeningBalance($id = null)
	{
		//pr($id);exit;
	//	$this->request->allowMethod(['post', 'delete']);
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$ItemLedger = $this->Items->ItemLedgers->get($id);
		$ItemSerialexists = $this->Items->SerialNumbers->exists(['status'=>'In','item_id' => $ItemLedger->item_id]);
		if($ItemSerialexists){
			$this->Items->SerialNumbers->deleteAll(['item_id' => $ItemLedger->item_id,'status'=>'In','company_id'=>$st_company_id]); 
		} 
		if ($this->Items->ItemLedgers->delete($ItemLedger)) {
			$this->Flash->success(__('The Opening Balance has been deleted.'));
		} else {
			$this->Flash->error(__('The Opening Balance could not be deleted. Please, try again.'));
		}
        return $this->redirect(['action' => 'openingBalanceView']);
    }
	
	
	public function DeleteSerialNumbers($id=null,$item_id=null,$item_ledger=null){
		
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$ItemLedger=$this->Items->ItemLedgers->find()->where(['item_id'=>$item_id,'source_model'=>'Items'])->first();
		$ItemSerialNumber = $this->Items->SerialNumbers->get($id);
		//pr($ItemSerialNumber);exit;
		
		if($ItemSerialNumber->status=='In'){
			$ItemQuantity = $ItemLedger->quantity-1;
			
			if($ItemQuantity == 0){
				$this->Items->ItemLedgers->delete($ItemLedger);
				$this->Items->SerialNumbers->delete($ItemSerialNumber);
				$this->Flash->success(__('The Item has been deleted.'));
				return $this->redirect(['action' => 'Opening-Balance']);
				
			}else{
			$query = $this->Items->ItemLedgers->query();
			$query->update()
				->set(['quantity' => $ItemQuantity])
				->where(['item_id' => $item_id,'company_id'=>$st_company_id,'source_model'=>'Items'])
				->execute();
			$this->Items->SerialNumbers->delete($ItemSerialNumber);
			$this->Flash->success(__('The Serial Number has been deleted.'));
			}	
			
			
		}
		
		
		return $this->redirect(['action' => 'EditItemOpeningBalance/'.$item_ledger]);
	}
	
	
	
	public function cost($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$ItemLedger = $this->Items->ItemLedgers->newEntity();
		
		$Items=$this->Items->find('list')->matching('ItemCompanies', function ($q) use($st_company_id) {
			return $q->where(['ItemCompanies.company_id' => $st_company_id]);
		});
		
		if ($this->request->is('post')) {
			$query = $this->Items->ItemCompanies->query();
			$query->update()
				->set(['dynamic_cost' => $this->request->data['dynamic_cost'],'minimum_selling_price_factor' => $this->request->data['minimum_selling_price_factor']])
				->where(['item_id' => $this->request->data['Item_id'],'company_id'=>$st_company_id])
				->execute();
			$this->Flash->success(__('Dynamic cost & Minimum selling price factor has been saved.'));
			return $this->redirect(['action' => 'cost']);
		}
		
		$this->set(compact('Items','ItemLedger'));
		$this->set('_serialize', ['ItemLedger']);
	}
	
	public function costView(){
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		
		$Items=$this->Items->find()->matching('ItemCompanies', function ($q) use($st_company_id) {
			return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.minimum_selling_price_factor >'=>0]);
		});
		$this->set(compact('Items'));
	}

	public function EditCompany($item_id=null)
    {
		$this->viewBuilder()->layout('index_layout');	
		$Companies = $this->Items->Companies->find();
		$Company_array=[];
		$Company_array1=[];
		$Company_array2=[];
		$Company_array3=[];
		$Item_ledgers_status=[];
		foreach($Companies as $Company){
			$Company_exist= $this->Items->ItemCompanies->exists(['item_id' => $item_id,'company_id'=>$Company->id]);
			if($Company_exist){
				$item_data= $this->Items->ItemCompanies->find()->where(['item_id' => $item_id,'company_id'=>$Company->id])->first();
				
				$Item_ledgers_data= $this->Items->ItemLedgers->exists(['item_id' => $item_id,'company_id'=>$Company->id]);
				
				$Company_array[$Company->id]='Yes';
				$Company_array1[$Company->id]=$Company->name;
				$Company_array2[$Company->id]=$item_data->freeze;
				$Company_array3[$Company->id]=$item_data->serial_number_enable;
				$Company_array4[$Company->id]=$item_data->minimum_selling_price_factor;
				$Company_array5[$Company->id]=$item_data->minimum_stock;
				$Item_ledgers_status[$Company->id]=$Item_ledgers_data;
				
			}else{

				$Company_array[$Company->id]='No';
				$Company_array1[$Company->id]=$Company->name;
				$Company_array2[$Company->id]=1;
				$Company_array3[$Company->id]=0;
				$Company_array4[$Company->id]=0;
				$Company_array5[$Company->id]=0;
			}

		} 
		//pr($Item_ledgers_status); exit;
		$item_data= $this->Items->get($item_id);
		
		$this->set(compact('item_data','Companies','customer_Company','Company_array','item_id','Company_array1','Company_array2','Company_array3','Company_array4','Company_array5','Item_ledgers_status'));

	}
	
	public function updateMinStock($item_id,$stock,$company_id){
		$query_update = $this->Items->ItemCompanies->query();
							$query_update->update()
								->set(['minimum_stock'=>$stock])
								->where(['company_id' => $company_id,'item_id'=>$item_id])
								->execute();
		exit;						
	}
	
	public function updateMinSellingFactor($item_id,$selling_factors,$company_id){
	
		$query_update = $this->Items->ItemCompanies->query();
							$query_update->update()
								->set(['minimum_selling_price_factor'=>$selling_factors])
								->where(['company_id' => $company_id,'item_id'=>$item_id])
								->execute();
		exit;						
	}
	
	public function ItemFreeze($company_id=null,$item_id=null,$freeze=null)
	{
		$query2 = $this->Items->ItemCompanies->query();
		$query2->update()
			->set(['freeze' => $freeze])
			->where(['item_id' => $item_id,'company_id'=>$company_id])
			->execute();

		return $this->redirect(['action' => 'EditCompany/'.$item_id]);
	}

public function SerialNumberEnabled($company_id=null,$item_id=null,$item_serial_no=null)
	{
		if($item_serial_no == 0){
			$ItemSerialNumbers = $this->Items->SerialNumbers->exists(['item_id'=>$item_id,'company_id'=>$company_id]);
			if($ItemSerialNumbers){ 
				$this->Flash->error(__('Item Can not Disabled.These Item has Serial Number , Firstly, you can delete serial number then you can disabled'));
			}else{
				$query2 = $this->Items->ItemCompanies->query();
				$query2->update()
						->set(['serial_number_enable' => $item_serial_no])
						->where(['item_id' => $item_id,'company_id'=>$company_id])
						->execute();
				$this->Flash->success(__('Item Serial Number Disabled Successfully '));		
			}
		}else{
				$Item_ledger_out = $this->Items->Itemledgers->exists(['item_id'=>$item_id,'company_id'=>$company_id,'in_out'=>'In']);
				if($Item_ledger_out){
					return $this->redirect(['action' => 'ItemSerialNo/'.$item_id,$company_id]);
				}
			
			
				$query2 = $this->Items->ItemCompanies->query();
				$query2->update()
						->set(['serial_number_enable' => $item_serial_no])
						->where(['item_id' => $item_id,'company_id'=>$company_id])
						->execute();
			$this->Flash->success(__('Item Serial Number Enabled Successfully '));
		}
		return $this->redirect(['action' => 'EditCompany/'.$item_id]);
	}

public function AddCompany($company_id=null,$item_id=null)
    {
		$this->viewBuilder()->layout('index_layout');	
		

		$ItemCompany = $this->Items->ItemCompanies->newEntity();
		$ItemCompany->company_id=$company_id;
		$ItemCompany->item_id=$item_id;
		$ItemCompany->freeze=0;
		$ItemCompany->serial_number_enable=1;
		
		//pr($ItemCompany); exit;
		$this->Items->ItemCompanies->save($ItemCompany);
		
		return $this->redirect(['action' => 'EditCompany/'.$item_id]);
	}
public function CheckCompany($company_id=null,$item_id=null)
    {
		$this->viewBuilder()->layout('index_layout');	
		$this->request->allowMethod(['post', 'delete']);
		
		$ledgerexist = $this->Items->ItemLedgers->exists(['item_id' => $item_id,'company_id' => $company_id]);
		
		$soexist = $this->Items->SalesOrders->find()->contain(['SalesOrderRows'=>function ($q) use($item_id){
			return $q->where(['item_id'=>$item_id]);
		}])->where(['company_id'=>$company_id]);
	
		$inSalesOrder="No";
		foreach($soexist as $so){
			if(sizeof($so->sales_order_rows) > 0){
				$inSalesOrder="Yes"; 
				goto dm; 
			};
		} dm:
		
		$qoexist = $this->Items->Quotations->find()->contain(['QuotationRows'=>function ($q) use($item_id){
			return $q->where(['item_id'=>$item_id]);
		}])->where(['company_id'=>$company_id]);
	
		$inQuoatation="No";
		foreach($qoexist as $qo){
			if(sizeof($qo->quotation_rows) > 0){
				$inQuoatation="Yes"; 
				goto gp; 
			};
		} gp:
		
		$poexist = $this->Items->PurchaseOrders->find()->contain(['PurchaseOrderRows'=>function ($q) use($item_id){
			return $q->where(['item_id'=>$item_id]);
		}])->where(['company_id'=>$company_id]);
	
		$inPurchaseOrders="No";
		foreach($poexist as $po){
			if(sizeof($po->purchase_order_rows) > 0){
				$inPurchaseOrders="Yes"; 
				goto dp; 
			};
		} dp:
		
		if(!$ledgerexist && $inSalesOrder=="No" && $inQuoatation=="No" && $inPurchaseOrders=="No"){
			$item_Company_dlt= $this->Items->ItemCompanies->find()->where(['ItemCompanies.item_id'=>$item_id,'company_id'=>$company_id])->first();
			$this->Items->ItemCompanies->delete($item_Company_dlt);
			$this->Flash->success(__('Company Deleted Successfully'));
			return $this->redirect(['action' => 'EditCompany/'.$item_id]);
		}
		
		else{
			$this->Flash->error(__('Item Can not Deleted'));
			return $this->redirect(['action' => 'EditCompany/'.$item_id]);
		}
	}
	public function ItemSerialNo($item_id=null,$company_id=null){
		$this->viewBuilder()->layout('index_layout');	
		$having=['total_rows' => 0];
		$item = $this->Items->newEntity();
		$ledger_data=
		$this->Items->ItemLedgers->find()->select(['total_rows' => 
			$this->Items->find()->func()->sum('ItemLedgers.quantity')])
				->where(['item_id' => $item_id,'company_id' => $company_id,'in_out'=>'In'])->first();
				
		if ($this->request->is('post')) {
			$data=$this->request->data();
			$query = $this->Items->ItemCompanies->query();
			$query->update()
				->set(['dynamic_cost' => $this->request->data['dynamic_cost'],'minimum_selling_price_factor' => $this->request->data['minimum_selling_price_factor']])
				->where(['item_id' => $this->request->data['Item_id'],'company_id'=>$st_company_id])
				->execute();
			$this->Flash->success(__('Dynamic cost & Minimum selling price factor has been saved.'));
			return $this->redirect(['action' => 'cost']);
		}	
				
			
		$this->set(compact('ledger_data','item'));
		//pr($ledger_data['total_rows']); exit;
	}
	
	public function itemSerialNumberManage(){
		$this->viewBuilder()->layout('index_layout');	
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');

		$Items=$this->Items->find()->matching('ItemCompanies', function ($q) use($st_company_id) {
			return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze'=>0,'ItemCompanies.serial_number_enable'=>1]);
		});
		foreach($Items as $Item){
			$ItemLedgers=$this->Items->ItemLedgers->find()
				->select(['total_quantity' => $this->Items->find()->func()->sum('ItemLedgers.quantity')])
				->where(['item_id'=>$Item->id,'company_id'=>$st_company_id,'in_out'=>'In'])
				->group(['item_id']);
				
			$ItemSerialNumbers=$this->Items->SerialNumbers->find()->where(['item_id'=>$Item->id,'company_id'=>$st_company_id]);
			
			$ItemLedgers= (Array)$ItemLedgers->toArray();
			if($ItemSerialNumbers->count()!=@$ItemLedgers[0]->total_quantity){
				echo $Item->id.'-';
			}
		}
		
		exit;
	}
	
	public function askSerialNumber($item_id=null,$company_id=null){
		$this->viewBuilder()->layout('index_layout');
		
		$item = $this->Items->newEntity();
		if ($this->request->is('post')) {
			//pr($this->request->is('post'));exit;
			foreach($this->request->data['serial_numbers'] as $serial_number){
				$query = $this->Items->SerialNumbers->query();
				$query->insert(['item_id', 'serial_no', 'status', 'master_item_id', 'company_id'])
					->values([
						'item_id' => $item_id,
						'serial_no' => $serial_number,
						'status' => 'In',
						'company_id' => $company_id
					]);
				$query->execute();
			}
			$query = $this->Items->ItemCompanies->query();
			$query->update()
				->set(['serial_number_enable' => 1])
				->where(['item_id' => $item_id,'company_id'=>$company_id])
				->execute();
			
			return $this->redirect(['action' => 'edit-company',$item_id]);
		}
		
		
		$ItemLedgers=$this->Items->ItemLedgers->find()->where(['item_id'=>$item_id,'company_id'=>$company_id]);
		$item_qty=[];
		foreach($ItemLedgers as $ItemLedger){
			if($ItemLedger->in_out=='Out'){
				@$item_qty['Out']+=$ItemLedger->quantity;
			}else{
				@$item_qty['In']+=$ItemLedger->quantity;
			}
		}
		
		$current_qty=@$item_qty['In']-@$item_qty['Out'];
		if($current_qty <= 0){
			$query = $this->Items->ItemCompanies->query();
			$query->update()
				->set(['serial_number_enable' => 1])
				->where(['item_id' => $item_id,'company_id'=>$company_id])
				->execute();
			return $this->redirect(['action' => 'edit-company',$item_id]);
		}
		$this->set(compact('current_qty', 'item'));

	}
	
	public function updateHsnCode(){
		$NewItems = $this->Items->NewItem->find();
		foreach($NewItems as $newitem){
			$query = $this->Items->query();
			$query->update()
				->set(['hsn_code' => $newitem->hsn_code])
				->where(['id' => $newitem->id])
				->execute();
		}
		exit;
	}
	
	public function	openingStock(){
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');	
		
		$ItemLedgers=$this->Items->ItemLedgers->find()->where(['source_model'=>'Items','quantity !='=>0,'company_id'=>$st_company_id])->order(['ItemLedgers.processed_on']);
		$total_stock=0;
		foreach($ItemLedgers as $ItemLedger){
				$total_stock+=$ItemLedger->rate*$ItemLedger->quantity;
		}
		echo $total_stock;
		exit;
	}
	
	public function getItemsData(){
		$this->viewBuilder()->layout('index_layout');
		
		$Items=$this->Items->find()->contain(['ItemCategories','ItemGroups','ItemSubGroups']);
		
		
		$company25=[];$company26=[];$company27=[];
		$item_name25=[];$item_name26=[];$item_cat25=[];$item_cat26=[];$item_data25=[];
		$item_cat27=[];$item_name27=[];
		$item_grp25=[];$item_subgrp25=[];
		$item_grp26=[];$item_subgrp26=[];
		$item_grp27=[];$item_subgrp27=[];
			foreach($Items as $item){
				$Company_exist= $this->Items->ItemCompanies->exists(['ItemCompanies.item_id' => $item->id,'company_id'=>'27']);
				if($Company_exist){
					$item_data= $this->Items->ItemCompanies->find()->where(['ItemCompanies.item_id' => $item->id,'company_id'=>'27'])->first();
					$company27[$item->id]=$item->id;
					$item_name27[$item->id]=$item->name;
					$item_cat27[$item->id]=$item->item_category->name;
					$item_grp27[$item->id]=$item->item_group->name; 
					$item_subgrp27[$item->id]=$item->item_sub_group->name;
					}
					
				}
				
			
//pr($company25);exit;
		$this->set(compact('Items','company25','company26','company27','item_name25','item_cat25','item_name26','item_cat26','item_cat27','item_name27','item_grp25','item_subgrp25','item_grp26','item_subgrp26','item_grp27','item_subgrp27'));
	}
	public function ItemHsnReport(){
		$item_datas= $this->Items->find()->toArray();?>
		<html>
			<body>
				<table border="1">
					
					<?php foreach($item_datas as $item_data) {?>
					<tr>
						<td><?php echo  $item_data->id; ?></td>
						<td><?php echo  $item_data->name; ?></td>
						<td><?php echo  $item_data->hsn_code; ?></td>
					</tr>
						<?php } ?>
					
				</table>
			</body>
		</html>
		<?php exit;
	}
	
	public function ItemOpeningBalanceData(){
		$this->viewBuilder()->layout('index_layout');
        $session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$Ledgers= $this->Items->ItemLedgers->find()->contain(['Items'])->where(['ItemLedgers.company_id'=>$st_company_id,'source_model'=>'Items'])->toArray();
		//pr($Ledgers); exit;
		?>
		<html>
			<body>
				<table border="1">
					<th>Item</th>
					<th>Quantity</th>
					<th>Rate</th>
					<?php foreach($Ledgers as $Ledger) {?>
					<tr>
						<td><?php echo  $Ledger->item->name; ?></td>
						<td><?php echo  $Ledger->quantity; ?></td>
						<td><?php echo  $Ledger->rate; ?></td>
					</tr>
						<?php } ?>
					
				</table>
			</body>
		</html>
		<?php exit;
	}
	
}
