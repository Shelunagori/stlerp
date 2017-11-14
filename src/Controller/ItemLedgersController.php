<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ItemLedgers Controller
 *
 * @property \App\Model\Table\ItemLedgersTable $ItemLedgers
 */
class ItemLedgersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($item_id=null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
        $this->paginate = [
            'contain' => ['Items']
        ];
        $itemLedgers2 = $this->paginate($this->ItemLedgers->find()->where(['ItemLedgers.item_id'=>$item_id,'ItemLedgers.company_id'=>$st_company_id])->order(['processed_on'=>'DESC']));
		$itemLedgers=[];
		foreach($itemLedgers2 as $itemLedger){
			if($itemLedger->source_model =='Items'){
				$itemLedger->voucher_info='-';
				$itemLedger->party_type='Item';
				$itemLedger->party_info='-'; 
			}else{
				$result=$this->GetVoucherParty($itemLedger->source_model,$itemLedger->source_id); 
				$itemLedger->voucher_info=$result['voucher_info'];
				$itemLedger->party_type=$result['party_type'];
				$itemLedger->party_info=$result['party_info']; 	
			}
			$itemLedgers[]=$itemLedger;
		}
        $this->set(compact('itemLedgers'));
        $this->set('_serialize', ['itemLedgers']);
    }
	
	public function GetVoucherParty($source_model=null,$source_id=null)
    {
		
		//return $source_model.$source_id;
		if($source_model=="Grns"){
			$Grn=$this->ItemLedgers->Grns->get($source_id);
			//pr($Grn); 
			$Vendor=$this->ItemLedgers->Vendors->get($Grn->vendor_id);
			return ['voucher_info'=>$Grn,'party_type'=>'-','party_info'=>$Vendor];
		}
		
		if($source_model=="Inventory Vouchers"){ //echo "IV"; exit;
			$InventoryVoucher=$this->ItemLedgers->InventoryVouchers->get($source_id);
			//pr($InventoryVoucher); exit;
			return ['voucher_info'=>$InventoryVoucher,'party_type'=>'-','party_info'=>''];
		}
		if($source_model=="Invoices"){
			$Invoice=$this->ItemLedgers->Invoices->get($source_id);
			$Customer=$this->ItemLedgers->Customers->get($Invoice->customer_id);
			return ['voucher_info'=>$Invoice,'party_type'=>'Customer','party_info'=>$Customer];
		}
		if($source_model=="Challan"){ 
			$Challan=$this->ItemLedgers->Challans->get($source_id);
			
			if($Challan->challan_for=='Customer'){
			$Party=$this->ItemLedgers->Customers->get($Challan->customer_id);
			}else{ 
			$Party=$this->ItemLedgers->Vendors->get($Challan->vendor_id);
			}
			return ['voucher_info'=>$Challan,'party_type'=>$Challan->challan_for,'party_info'=>$Party];
		}
		if($source_model=="Purchase Return"){
			$PurchaseReturn=$this->ItemLedgers->PurchaseReturns->get($source_id);
			
			$Vendor=$this->ItemLedgers->Vendors->get($PurchaseReturn->vendor_id);
			return ['voucher_info'=>$PurchaseReturn,'party_type'=>'Purchase','party_info'=>$Vendor];
		}
		if($source_model=="Sale Return"){
			$SaleReturn=$this->ItemLedgers->SaleReturns->get($source_id);
			$Customer=$this->ItemLedgers->Customers->get($SaleReturn->customer_id);
			return ['voucher_info'=>$SaleReturn,'party_type'=>'Sale','party_info'=>$Customer];
		}
		 if($source_model=="Inventory Transfer Voucher"){ 
			$InventoryTransferVouchers=$this->ItemLedgers->InventoryTransferVouchers->get($source_id);//pr($source_id);exit;
			//$Item=$this->ItemLedgers->Items->get($source_id);
			return ['voucher_info'=>$InventoryTransferVouchers,'party_type'=>'-','party_info'=>'-'];
		} 
		if($source_model=="Inventory Return"){ 
			$Inventoryreturn=$this->ItemLedgers->Rivs->get($source_id);
			//pr($source_id);exit;
			//pr($Inventoryreturn);exit;
			return ['voucher_info'=>$Inventoryreturn,'party_type'=>'-','party_info'=>'-'];
		} 
       return $source_model.$source_id;
    }
	
	public function stockLedger(){
		
	$this->viewBuilder()->layout('index_layout');
        $session = $this->request->session();
        $items=$this->request->query('items');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		
		$this->set(compact('From','To'));
		$where=[];
		if(!empty($items)){  
			$where['id']=$items;
			
		}
				
		$Items = $this->ItemLedgers->Items->find('list')->where($where)->order(['Items.name' => 'ASC']);
		$Items_list = $this->ItemLedgers->Items->find('list')->order(['Items.name' => 'ASC']);
		$this->set(compact('Items', 'items','From','To','Items_list'));
		$this->set('_serialize', ['itemLedgers']); 
    }
	
	public function GetItemVouchers($source_model_id=null,$source_id1=null)
    {
		if($source_model_id=="Invoices"){
			$Invoice=$this->ItemLedgers->Invoices->get($source_id1);
			return ['voucher_info'=>$Invoice];
		}
		
		if($source_model_id=="Grns"){ 
			$Grns=$this->ItemLedgers->Grns->get($source_id1);
			return ['voucher_info'=>$Grns];
		}
		
		if($source_model_id=="Inventory Vouchers"){ 
			$InventoryVoucher=$this->ItemLedgers->InventoryVouchers->get($source_id1);
			return ['voucher_info'=>$InventoryVoucher];
		}
		
		if($source_model_id=="Challan"){ 
			$Challan=$this->ItemLedgers->Challans->get($source_id1);
			
			return ['voucher_info'=>$Challan];
		}
		if($source_model_id=="Purchase Return"){
			$PurchaseReturn=$this->ItemLedgers->PurchaseReturns->get($source_id1);
			
			return ['voucher_info'=>$PurchaseReturn];
		}
		if($source_model_id=="Sale Return"){
			$SaleReturn=$this->ItemLedgers->SaleReturns->get($source_id1);
			return ['voucher_info'=>$SaleReturn];
		}
		 if($source_model_id=="Inventory Transfer Voucher"){ 
			$InventoryTransferVouchers=$this->ItemLedgers->InventoryTransferVouchers->get($source_id1);
			
			return ['voucher_info'=>$InventoryTransferVouchers];
		} 
		if($source_model_id=="Inventory Return"){ 
			$Inventoryreturn=$this->ItemLedgers->Rivs->get($source_id1);
			return ['voucher_info'=>$Inventoryreturn];
		} 
		return $source_model_id.$source_id1;
    }
    /**
     * View method
     *
     * @param string|null $id Item Ledger id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $itemLedger = $this->ItemLedgers->get($id, [
            'contain' => ['Items', 'Sources', 'Companies']
        ]);

        $this->set('itemLedger', $itemLedger);
        $this->set('_serialize', ['itemLedger']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $itemLedger = $this->ItemLedgers->newEntity();
        if ($this->request->is('post')) {
            $itemLedger = $this->ItemLedgers->patchEntity($itemLedger, $this->request->data);
            if ($this->ItemLedgers->save($itemLedger)) {
                $this->Flash->success(__('The item ledger has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The item ledger could not be saved. Please, try again.'));
            }
        }
        $items = $this->ItemLedgers->Items->find('list', ['limit' => 200]);
        $sources = $this->ItemLedgers->Sources->find('list', ['limit' => 200]);
        $companies = $this->ItemLedgers->Companies->find('list', ['limit' => 200]);
        $this->set(compact('itemLedger', 'items', 'sources', 'companies'));
        $this->set('_serialize', ['itemLedger']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Item Ledger id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $itemLedger = $this->ItemLedgers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $itemLedger = $this->ItemLedgers->patchEntity($itemLedger, $this->request->data);
            if ($this->ItemLedgers->save($itemLedger)) {
                $this->Flash->success(__('The item ledger has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The item ledger could not be saved. Please, try again.'));
            }
        }
        $items = $this->ItemLedgers->Items->find('list', ['limit' => 200]);
        $sources = $this->ItemLedgers->Sources->find('list', ['limit' => 200]);
        $companies = $this->ItemLedgers->Companies->find('list', ['limit' => 200]);
        $this->set(compact('itemLedger', 'items', 'sources', 'companies'));
        $this->set('_serialize', ['itemLedger']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Item Ledger id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $itemLedger = $this->ItemLedgers->get($id);
        if ($this->ItemLedgers->delete($itemLedger)) {
            $this->Flash->success(__('The item ledger has been deleted.'));
        } else {
            $this->Flash->error(__('The item ledger could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function stockReport($stockstatus=null){
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		
		$this->viewBuilder()->layout('index_layout');
        $session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$stockstatus=$this->request->query('stockstatus');
		$item_name=$this->request->query('item_name');
		$item_category=$this->request->query('item_category');
		$item_group=$this->request->query('item_group_id');
		$from_date=$this->request->query('from_date');
		$to_date=$this->request->query('to_date');
		$stock=$this->request->query('stock');
		$status=$this->request->query('status');
		$item_sub_group=$this->request->query('item_sub_group_id');
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->ItemLedgers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$date = $financial_year->date_from;
		$due_date= $financial_year->date_from;
		if(empty($from_date)){
			$from_date=$date;
			$to_date=date('Y-m-d');
		};
		$where=[];
		$where1=[];
		
		$this->set(compact('item_category','item_group','item_sub_group','stock','item_name'));
		if(!empty($item_name)){ 
			$where['Item_id']=$item_name;
			
		}
		if(!empty($item_category)){
			$where['Items.item_category_id']=$item_category;
		}
		if(!empty($item_group)){
			$where['Items.item_group_id ']=$item_group;
		}
		if(!empty($item_sub_group)){
			$where['Items.item_sub_group_id ']=$item_sub_group;
		}
		if(!empty($search_date)){
			$search_date=date("Y-m-d",strtotime($search_date));
            $where1['processed_on <=']=$search_date;
		}
			//pr($where);exit;
		$item_stocks =[];$items_names =[];
		
		$query = $this->ItemLedgers->find()->where(['ItemLedgers.processed_on >='=> date("Y-m-d",strtotime($from_date)), 'ItemLedgers.processed_on <=' =>date("Y-m-d",strtotime($to_date)),'company_id'=>$st_company_id]);
		
	
		
		$totalInCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['in_out' => 'In']),
				$query->newExpr()->add(['quantity']),
				'integer'
			);
		$totalOutCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['in_out' => 'Out']),
				$query->newExpr()->add(['quantity']),
				'integer'
			);

			
		$query->select([
			'total_in' => $query->func()->sum($totalInCase),
			'total_out' => $query->func()->sum($totalOutCase),'id','item_id'
		])
		->contain(['Items'=>function ($q) use($where) { 
				return $q->where($where)->contain(['Units']);
				}])
		->where(['company_id'=>$st_company_id])
		->group('item_id')
		->autoFields(true)
		->where($where)
		
		->order(['Items.name'=>'ASC']);
		$results =$query->toArray();
		
		//pr($results); exit;
		
		
		if($stock == "Negative"){
			foreach($results as $result){
				if($result->total_in - $result->total_out < 0){
					$item_stocks[$result->item_id] = $result->total_in - $result->total_out;
					$items_names[$result->item_id] = $result->item->name;
					$items_unit_names[$result->item_id] = $result->item->unit->name;
				}
			}
		}elseif($stock == "Zero"){
			foreach($results as $result){
				if($result->total_in - $result->total_out == 0){
					$item_stocks[$result->item_id] = $result->total_in - $result->total_out;
					$items_names[$result->item_id] = $result->item->name;
					$items_unit_names[$result->item_id] = $result->item->unit->name;
				}
			}
		}elseif($stock == "Positive"){
			foreach($results as $result){
				if($result->total_in - $result->total_out > 0){
					$item_stocks[$result->item_id] = $result->total_in - $result->total_out;
					$items_names[$result->item_id] = $result->item->name;
					$items_unit_names[$result->item_id] = $result->item->unit->name;
					//pr($item_stocks);
				}
			}
		}elseif($stockstatus == "Positive"){
			foreach($results as $result){
				if($result->total_in - $result->total_out > 0){
					$item_stocks[$result->item_id] = $result->total_in - $result->total_out;
					$items_names[$result->item_id] = $result->item->name;
					$items_unit_names[$result->item_id] = $result->item->unit->name;
					
				}
			}
		}else{
			foreach($results as $result){
				
					$item_stocks[$result->item_id] = $result->total_in - $result->total_out;
					$items_names[$result->item_id] = $result->item->name;
					$items_unit_names[$result->item_id] = $result->item->unit->name;
				
				}
			}
		$ItemLedgers = $this->ItemLedgers->find()->contain(['Items'=>function ($q) use($where){
			return $q->where($where);
		}])->where(['ItemLedgers.company_id'=>$st_company_id,'ItemLedgers.rate >'=>0]);
		
		$item_rate=[];
		$in_qty=[];
		foreach($ItemLedgers as $ItemLedger){
				if($ItemLedger->in_out == 'In'){
					@$item_rate[$ItemLedger->item_id] += ($ItemLedger->quantity*$ItemLedger->rate);
					@$in_qty[$ItemLedger->item_id] += $ItemLedger->quantity;
				}
		}
		
		
		$where=[];
		if(!empty($item_name)){ 
			$where['Items.id']=$item_name;
			
		}
		if(!empty($item_category)){
			$where['Items.item_category_id']=$item_category;
		}
		if(!empty($item_group)){
			$where['Items.item_group_id ']=$item_group;
		}
		if(!empty($item_sub_group)){
			$where['Items.item_sub_group_id ']=$item_sub_group;
		}
		if(!empty($search_date)){
			$search_date=date("Y-m-d",strtotime($search_date));
            $where1['processed_on <=']=$search_date;
		}
		 $ItemDatas=[];
		 $ItemUnits=[];
		if(!$stock){
		$Items =$this->ItemLedgers->Items->find()->contain(['Units','ItemCompanies'=>function($p) use($st_company_id){
						return $p->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
		}])->where($where);
		
		
		foreach($Items as $Item){ 
			$ItemLedgersexists = $this->ItemLedgers->exists(['item_id' => $Item->id,'company_id'=>$st_company_id]);
			if(empty($ItemLedgersexists)){
				$ItemDatas[$Item->id]=$Item->name;
				$ItemUnits[$Item->id]=$Item->unit->name;
			}
		}
	}		
		$ItemCategories = $this->ItemLedgers->Items->ItemCategories->find('list')->order(['ItemCategories.name' => 'ASC']);
		$ItemGroups = $this->ItemLedgers->Items->ItemGroups->find('list')->order(['ItemGroups.name' => 'ASC']);
		$ItemSubGroups = $this->ItemLedgers->Items->ItemSubGroups->find('list')->order(['ItemSubGroups.name' => 'ASC']);
		$Items = $this->ItemLedgers->Items->find('list')->order(['Items.name' => 'ASC']);
        $this->set(compact('itemLedgers', 'item_name','item_stocks','items_names','ItemCategories','ItemGroups','ItemSubGroups','item_rate','in_qty','Items','from_date','to_date','ItemDatas','items_unit_names','ItemUnits','url','stockstatus'));
		$this->set('_serialize', ['itemLedgers']); 
    }
	
	public function stockSummery($stockstatus=null){ 
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		
		$this->viewBuilder()->layout('index_layout');
        $session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$stockstatus=$this->request->query('stockstatus');
		$item_name=$this->request->query('item_name');
		$item_category=$this->request->query('item_category');
		$item_group=$this->request->query('item_group_id');
		$from_date=$this->request->query('from_date');
		$to_date=$this->request->query('to_date');
		$stock=$this->request->query('stock');
		$status=$this->request->query('status');
		$item_sub_group=$this->request->query('item_sub_group_id');
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->ItemLedgers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$date = $financial_year->date_from;
		$due_date= $financial_year->date_from;
		if(empty($from_date)){
			$from_date=$date;
			$to_date=date('Y-m-d');
		};
		$where=[];
		$where1=[];
		
		$this->set(compact('item_category','item_group','item_sub_group','stock','item_name'));
		if(!empty($item_name)){ 
			$where['Item_id']=$item_name;
			
		}
		if(!empty($item_category)){
			$where['Items.item_category_id']=$item_category;
		}
		if(!empty($item_group)){
			$where['Items.item_group_id ']=$item_group;
		}
		if(!empty($item_sub_group)){
			$where['Items.item_sub_group_id ']=$item_sub_group;
		}
		if(!empty($search_date)){
			$search_date=date("Y-m-d",strtotime($search_date));
            $where1['processed_on <=']=$search_date;
		}
		//pr($where);exit;
		$item_stocks =[];$items_names =[];
		
		$query = $this->ItemLedgers->find()->where(['ItemLedgers.processed_on >='=> date("Y-m-d",strtotime($from_date)), 'ItemLedgers.processed_on <=' =>date("Y-m-d",strtotime($to_date)),'company_id'=>$st_company_id]);
		
		$totalInCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['in_out' => 'In']),
				$query->newExpr()->add(['quantity']),
				'integer'
			);
		$totalOutCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['in_out' => 'Out']),
				$query->newExpr()->add(['quantity']),
				'integer'
			);

			
		$query->select([
			'total_in' => $query->func()->sum($totalInCase),
			'total_out' => $query->func()->sum($totalOutCase),'id','item_id'
		])
		->contain(['Items'=>function ($q) use($where) { 
				return $q->where($where)->contain(['Units']);
				}])
		->where(['company_id'=>$st_company_id])
		->group('item_id')
		->autoFields(true)
		->where($where)
		
		->order(['Items.name'=>'ASC']);
		$results =$query->toArray();
		
		//pr($results); exit;
		
		
		if($stock == "Negative"){
			foreach($results as $result){
				if($result->total_in - $result->total_out < 0){
					$item_stocks[$result->item_id] = $result->total_in - $result->total_out;
					$items_names[$result->item_id] = $result->item->name;
					$items_unit_names[$result->item_id] = $result->item->unit->name;
				}
			}
		}elseif($stock == "Zero"){
			foreach($results as $result){
				if($result->total_in - $result->total_out == 0){
					$item_stocks[$result->item_id] = $result->total_in - $result->total_out;
					$items_names[$result->item_id] = $result->item->name;
					$items_unit_names[$result->item_id] = $result->item->unit->name;
				}
			}
		}elseif($stock == "Positive"){ 
			foreach($results as $result){
				if($result->total_in - $result->total_out > 0){
					$item_stocks[$result->item_id] = $result->total_in - $result->total_out;
					$items_names[$result->item_id] = $result->item->name;
					$items_unit_names[$result->item_id] = $result->item->unit->name;
					
				}
				 
			}
		}elseif($stockstatus == "Positive"){
			foreach($results as $result){
				if($result->total_in - $result->total_out > 0){
					$item_stocks[$result->item_id] = $result->total_in - $result->total_out;
					$items_names[$result->item_id] = $result->item->name;
					$items_unit_names[$result->item_id] = $result->item->unit->name;
					
				}
			}
		}else{ 
			foreach($results as $result){
				
					$item_stocks[$result->item_id] = $result->total_in - $result->total_out;
					$items_names[$result->item_id] = $result->item->name;
					$items_unit_names[$result->item_id] = $result->item->unit->name;
				
				}
			}
		
		//pr($item_stocks); exit;
		
		$ItemLedgers = $this->ItemLedgers->find()->contain(['Items'=>function ($q) use($where){
			return $q->where($where);
		}])->where(['ItemLedgers.company_id'=>$st_company_id,'ItemLedgers.rate >'=>0]);
		
		$item_rate=[];
		$in_qty=[];
		foreach($ItemLedgers as $ItemLedger){
				if($ItemLedger->in_out == 'In'){
					@$item_rate[$ItemLedger->item_id] += ($ItemLedger->quantity*$ItemLedger->rate);
					@$in_qty[$ItemLedger->item_id] += $ItemLedger->quantity;
				}
		}
		
		
		$where=[];
		if(!empty($item_name)){ 
			$where['Items.id']=$item_name;
			
		}
		if(!empty($item_category)){
			$where['Items.item_category_id']=$item_category;
		}
		if(!empty($item_group)){
			$where['Items.item_group_id ']=$item_group;
		}
		if(!empty($item_sub_group)){
			$where['Items.item_sub_group_id ']=$item_sub_group;
		}
		if(!empty($search_date)){
			$search_date=date("Y-m-d",strtotime($search_date));
            $where1['processed_on <=']=$search_date;
		} 
		 $ItemDatas=[];
		 $ItemUnits=[]; 

		 $Items =$this->ItemLedgers->Items->find()->contain(['Units','ItemCompanies'=>function($p) use($st_company_id){
						return $p->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
		}])->where($where);
		
		$itemSerialNumberStatus=[];
		foreach($Items as $Item){ 
			$ItemLedgersexists = $this->ItemLedgers->exists(['item_id' => $Item->id,'company_id'=>$st_company_id]);
			$itemSerialNumberStatus[$Item->id]=@$Item->item_companies[0]->serial_number_enable;
			
			if(empty($ItemLedgersexists)){
				$ItemDatas[$Item->id]=$Item->name;
				$ItemUnits[$Item->id]=$Item->unit->name;
			}
		}
	
		//Stock valuation Start// disable   fhgdf
		$stock=[];  $sumValue=[];
		foreach($Items as $Item){
			$StockLedgers=$this->ItemLedgers->find()->where(['ItemLedgers.item_id'=>$Item->id,'ItemLedgers.company_id'=>$st_company_id])->order(['ItemLedgers.processed_on'=>'ASC']);
			foreach($StockLedgers as $StockLedger){ 
				if($StockLedger->in_out=='In'){
					if(($StockLedger->source_model=='Grns' and $StockLedger->rate_updated=='Yes') or ($StockLedger->source_model!='Grns')){
						for($inc=0;$inc<$StockLedger->quantity;$inc++){
							$stock[$Item->id][]=$StockLedger->rate;
						}
					}
				}
			}
			foreach($StockLedgers as $StockLedger){
				if($StockLedger->in_out=='Out'){
					if(sizeof(@$stock[$Item->id])>0){
						$stock[$Item->id] = array_slice($stock[$Item->id], $StockLedger->quantity); 
					}
				}
			}
			//echo "hello"; exit;
			if(sizeof(@$stock[$Item->id]) > 0){ 
				foreach(@$stock[$Item->id] as $stockRate){
					@$sumValue[$Item->id]+=@$stockRate;
				}
			}
		}
		/////fdgdfgdrc disavble
		
	$ItemSerialNumbers =$this->ItemLedgers->Items->ItemSerialNumbers->find()->where(['ItemSerialNumbers.company_id' => $st_company_id,'ItemSerialNumbers.status'=>"In"]);
	
	$itemSerialRate=[]; $itemSerialQuantity=[]; $i=1;
	foreach($ItemSerialNumbers as $ItemSerialNumber){
		if(@$ItemSerialNumber->grn_id > 0){
			$ItemLedgerData =$this->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->grn_id,'source_model'=>"Grns",'item_id'=>$ItemSerialNumber->item_id])->first();
			@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
			@$itemSerialRate[@$ItemSerialNumber->item_id]+=@$ItemLedgerData['rate'];
		}
		else if(@$ItemSerialNumber->master_item_id > 0){
			$ItemLedgerData =$this->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->item_id,'source_model'=>"Items",'item_id'=>$ItemSerialNumber->item_id])->first();
			@$itemSerialRate[@$ItemSerialNumber->item_id]+=@$ItemLedgerData['rate'];
			@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
		}else if(@$ItemSerialNumber->sale_return_id > 0){
			$ItemLedgerData =$this->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->sale_return_id,'source_model'=>"Sale Return",'item_id'=>$ItemSerialNumber->item_id])->first();
			@$itemSerialRate[@$ItemSerialNumber->item_id]+=@$ItemLedgerData['rate'];
			@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
		}else if(@$ItemSerialNumber->inventory_transfer_voucher_id > 0){
			$ItemLedgerData =$this->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->inventory_transfer_voucher_id,'source_model'=>"Inventory Transfer Voucher",'item_id'=>$ItemSerialNumber->item_id])->first();
			@$itemSerialRate[@$ItemSerialNumber->item_id]+=@$ItemLedgerData['rate'];
			@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
		}
	}
	
	//pr($itemSerialRate); exit;
	$unitRate=[]; $totalRate=[];
		foreach ($item_stocks as $key=> $item_stock1){
			$r=@$itemSerialRate[$key];
			$q=@$itemSerialQuantity[$key];
			if($q > 0){
			$UR=$r/$q;
			$unitRate[$key]=$UR;
			$totalRate[$key]=$UR*$q;
			}
			//$RowTotal=$UR*$q;
		}
	//	pr($unitRate); exit;
	
	
	
		$ItemCategories = $this->ItemLedgers->Items->ItemCategories->find('list')->order(['ItemCategories.name' => 'ASC']);
		$ItemGroups = $this->ItemLedgers->Items->ItemGroups->find('list')->order(['ItemGroups.name' => 'ASC']);
		$ItemSubGroups = $this->ItemLedgers->Items->ItemSubGroups->find('list')->order(['ItemSubGroups.name' => 'ASC']);
		$Items = $this->ItemLedgers->Items->find('list')->order(['Items.name' => 'ASC']);
        $this->set(compact('itemLedgers', 'item_name','item_stocks','items_names','ItemCategories','ItemGroups','ItemSubGroups','item_rate','in_qty','Items','from_date','to_date','ItemDatas','items_unit_names','ItemUnits','url','stockstatus', 'stock', 'sumValue','itemSerialNumberStatus','unitRate','totalRate'));
		$this->set('_serialize', ['itemLedgers']); 
    }
	
	public function excelStock(){
		$this->viewBuilder()->layout('');
        $session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$item_name=$this->request->query('item_name');
		$item_category=$this->request->query('item_category');
		$item_group=$this->request->query('item_group_id');
		$from_date=$this->request->query('from_date');
		$to_date=$this->request->query('to_date');
		$stock=$this->request->query('stock');
		$status=$this->request->query('status');
		$item_sub_group=$this->request->query('item_sub_group_id');
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->ItemLedgers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$date = $financial_year->date_from;
		$due_date= $financial_year->date_from;
		if(empty($from_date)){
			$from_date=$date;
			$to_date=date('Y-m-d');
		};
		

		
		$where=[];
		$where1=[];
		$this->set(compact('item_category','item_group','item_sub_group','stock','item_name'));
		if(!empty($item_name)){ 
			$where['Item_id']=$item_name;
			
		}
		if(!empty($item_category)){
			$where['Items.item_category_id']=$item_category;
		}
		if(!empty($item_group)){
			$where['Items.item_group_id ']=$item_group;
		}
		if(!empty($item_sub_group)){
			$where['Items.item_sub_group_id ']=$item_sub_group;
		}
		if(!empty($search_date)){
			$search_date=date("Y-m-d",strtotime($search_date));
            $where1['processed_on <=']=$search_date;
		}
			//pr($where);exit;
		$item_stocks =[];$items_names =[];
		$query = $this->ItemLedgers->find()->where(['ItemLedgers.processed_on >='=> date("Y-m-d",strtotime($from_date)), 'ItemLedgers.processed_on <=' =>date("Y-m-d",strtotime($to_date))]);
				//pr($query->toArray()); exit;
		$totalInCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['in_out' => 'In']),
				$query->newExpr()->add(['quantity']),
				'integer'
			);
		$totalOutCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['in_out' => 'Out']),
				$query->newExpr()->add(['quantity']),
				'integer'
			);

			
		$query->select([
			'total_in' => $query->func()->sum($totalInCase),
			'total_out' => $query->func()->sum($totalOutCase),'id','item_id'
		])
		->contain(['Items'=>function ($q) use($where) { 
				return $q->where($where)->contain(['Units']);
				}])
		->where(['company_id'=>$st_company_id])
		->group('item_id')
		->autoFields(true)
		->where($where)
		
		->order(['Items.name'=>'ASC']);
		$results =$query->toArray();
		
		
		if($stock == "Negative"){
			foreach($results as $result){
				if($result->total_in - $result->total_out < 0){
					$item_stocks[$result->item_id] = $result->total_in - $result->total_out;
					$items_names[$result->item_id] = $result->item->name;
					$items_unit_names[$result->item_id] = $result->item->unit->name;
				}
			}
		}elseif($stock == "Zero"){
			foreach($results as $result){
				if($result->total_in - $result->total_out == 0){
					$item_stocks[$result->item_id] = $result->total_in - $result->total_out;
					$items_names[$result->item_id] = $result->item->name;
					$items_unit_names[$result->item_id] = $result->item->unit->name;
				}
			}
		}elseif($stock == "Positive"){
			foreach($results as $result){
				if($result->total_in - $result->total_out > 0){
					$item_stocks[$result->item_id] = $result->total_in - $result->total_out;
					$items_names[$result->item_id] = $result->item->name;
					$items_unit_names[$result->item_id] = $result->item->unit->name;
					//pr($item_stocks);
				}
			}
		}else{
			foreach($results as $result){
				
					$item_stocks[$result->item_id] = $result->total_in - $result->total_out;
					$items_names[$result->item_id] = $result->item->name;
					$items_unit_names[$result->item_id] = $result->item->unit->name;
				
				}
			}
		$ItemLedgers = $this->ItemLedgers->find()->contain(['Items'=>function ($q) use($where){
			return $q->where($where);
		}])->where(['ItemLedgers.company_id'=>$st_company_id,'ItemLedgers.rate >'=>0]);
		//pr(sizeof($item_stocks)); exit;
		$item_rate=[];
		$in_qty=[];
		foreach($ItemLedgers as $ItemLedger){
				if($ItemLedger->in_out == 'In'){
					@$item_rate[$ItemLedger->item_id] += ($ItemLedger->quantity*$ItemLedger->rate);
					@$in_qty[$ItemLedger->item_id] += $ItemLedger->quantity;
				}
		}
		
		
		$where=[];
		if(!empty($item_name)){ 
			$where['Items.id']=$item_name;
			$ItemsName = $this->ItemLedgers->Items->find()->where(['Items.id' => $item_name])->first();
		}
		if(!empty($item_category)){
			$where['Items.item_category_id']=$item_category;
			$ItemCategories = $this->ItemLedgers->Items->ItemCategories->find()->where(['ItemCategories.id' => $item_category])->first();
		}
		if(!empty($item_group)){
			$where['Items.item_group_id ']=$item_group;
			$itemGroups = $this->ItemLedgers->Items->ItemGroups->find()->where(['ItemGroups.id' => $item_group])->first();
		}
		if(!empty($item_sub_group)){
			$where['Items.item_sub_group_id ']=$item_sub_group;
			$ItemSubGroups = $this->ItemLedgers->Items->ItemSubGroups->find()->where(['ItemSubGroups.id' => $item_sub_group])->first();

		}
		
		if(!empty($search_date)){
			$search_date=date("Y-m-d",strtotime($search_date));
            $where1['processed_on <=']=$search_date;
		}
		 $ItemDatas=[];
		 $ItemUnits=[];
		if(!$stock){
		$Items =$this->ItemLedgers->Items->find()->contain(['Units','ItemCompanies'=>function($p) use($st_company_id){
						return $p->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
		}])->where($where);
		
		
		foreach($Items as $Item){ 
			$ItemLedgersexists = $this->ItemLedgers->exists(['item_id' => $Item->id,'company_id'=>$st_company_id]);
			if(empty($ItemLedgersexists)){
				$ItemDatas[$Item->id]=$Item->name;
				$ItemUnits[$Item->id]=$Item->unit->name;
			}
		}
	}		
		//$ItemCategories = $this->ItemLedgers->Items->ItemCategories->find('list')->order(['ItemCategories.name' => //'ASC']);
		//pr($ItemCategories);exit;
		//$ItemGroups = $this->ItemLedgers->Items->ItemGroups->find('list')->order(['ItemGroups.name' => 'ASC']);
		//$ItemSubGroups = $this->ItemLedgers->Items->ItemSubGroups->find('list')->order(['ItemSubGroups.name' => 'ASC']);
		$Items = $this->ItemLedgers->Items->find('list')->order(['Items.name' => 'ASC']);
        $this->set(compact('itemLedgers', 'item_name','item_stocks','items_names','ItemCategories','itemGroups','ItemSubGroups','item_rate','in_qty','Items','from_date','to_date','ItemDatas','items_unit_names','ItemUnits','url','ItemsName','itemGroup','stock'));
		$this->set('_serialize', ['itemLedgers']); 
	}
	
	public function redirectStock(){
		//exit;
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$Itemdatas = $this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$st_company_id,'in_out'=>'In','rate >'=>0]);
		
		foreach($Itemdatas as $Itemdata){
				$Itemledger_rate=0;
				$Itemledger_qty=0;
				$Itemledgers = $this->ItemLedgers->find()->where(['item_id'=>$Itemdata['item_id'],'in_out'=>'In','processed_on <='=>$Itemdata['processed_on'],'rate >'=>0]);
				//pr($Itemledgers->toArray()); 
				if($Itemledgers){ 
					$j=0; $qty_total=0; $total_amount=0;
						foreach($Itemledgers as $Itemledger){
							$Itemledger_qty = $Itemledger_qty+$Itemledger['quantity'];
							$Itemledger_rate = $Itemledger_rate+($Itemledger['rate']*$Itemledger['quantity']);
						}
						$per_unit_cost=$Itemledger_rate/$Itemledger_qty;
				}
				else{
					$per_unit_cost=0;
				}
				
				$query2 = $this->ItemLedgers->query();
						$query2->update()
							->set(['rate' => $per_unit_cost,'in_out' => 'In'])
							->where(['id' => $Itemdata['id']])
							->execute();
			}
			 return $this->redirect(['action'=>'stockReport?status=completed']);
			
	}
	
	public function materialIndent($id=null,$status=null){
		$this->viewBuilder()->layout('index_layout'); 
		$status = $this->request->query('status');
		$id = $this->request->query('id');
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$qty_ids = explode(',',$id);
		$qty_ids = array_filter($qty_ids);
		$salesOrders=[];
		foreach($qty_ids as $id){
			if($status == 'salesorder'){
				$salesOrders[]=$this->ItemLedgers->SalesOrders->find()->where(['SalesOrders.id'=>$id,'company_id'=>$st_company_id])->first();
			}
			else if($status == 'jobcard'){
				$salesOrders[]=$this->ItemLedgers->JobCards->find()->where(['JobCards.id'=>$id,'company_id'=>$st_company_id])->first();
			}
			else if($status == 'purchaseorder'){
				$salesOrders[]=$this->ItemLedgers->PurchaseOrders->find()->where(['PurchaseOrders.id'=>$id,'company_id'=>$st_company_id])->first();
			}
			else if($status == 'quotation'){
				$salesOrders[]=$this->ItemLedgers->Quotations->find()->where(['Quotations.id'=>$id,'company_id'=>$st_company_id])->first();
			}
			else if($status == 'mi'){
				$salesOrders[]=$this->ItemLedgers->MaterialIndents->find()->where(['MaterialIndents.id'=>$id,'company_id'=>$st_company_id])->first();
			}
		}
		
		$this->set(compact('salesOrders','status'));
		
	}
	
	
	 public function materialindentreport($stockstatus=null){
		 
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$this->viewBuilder()->layout('index_layout'); 
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$item_name=$this->request->query('item_name');
		$item_category=$this->request->query('item_category');
		$item_group=$this->request->query('item_group_id');
		$item_sub_group=$this->request->query('item_sub_group_id');
		$company_name=$this->request->query('company_name');
		$stock=$this->request->query('stock');
		$stockstatus=$this->request->query('stockstatus');

		$where=[];
		$whereItem=[];
		
		
		$this->set(compact('item_category','item_group','item_sub_group','item_name','company_name','stock'));
		if(!empty($item_name)){ 
			$where['Item_id']=$item_name;
			$whereItem['id']=$item_name;
		}
		
		if(!empty($item_category)){
			$where['Items.item_category_id']=$item_category;
			$whereItem['Items.item_category_id']=$item_category;
		}
		if(!empty($item_group)){
			$where['Items.item_group_id ']=$item_group;
			$whereItem['Items.item_group_id ']=$item_group;
		}
		if(!empty($item_sub_group)){
			$where['Items.item_sub_group_id ']=$item_sub_group;
			$whereItem['Items.item_sub_group_id ']=$item_sub_group;
		}

		$mit=$this->ItemLedgers->newEntity();
		
		if ($this->request->is(['post'])) {
			$check=$this->request->data['check']; 
			$suggestindent=$this->request->data['suggestindent']; 
			$to_send=[];
			foreach($check as $item_id){
				$to_send[$item_id]=$suggestindent[$item_id];
			}
			$to=json_encode($to_send); 
			$this->redirect(['controller'=>'MaterialIndents','action' => 'add/'.$to.'']);
		}
		$where1=[];$where2=[];$where3=[];
		$where4=[];$where5=[];$where6=[];
		$where7=[];
		
		if(!empty($company_name)){
		
		$company_names=array_filter($company_name);
		// pr($company_names);
			foreach($company_names as $names){  
					$where1['SalesOrders.company_id IN'][]=$names;
					$where2['JobCards.company_id IN'][]=$names;
					$where3['PurchaseOrders.company_id IN'][]=$names;
					$where4['MaterialIndents.company_id IN'][]=$names;
					$where5['Quotations.company_id IN'][]=$names;
					$where6['ItemLedgers.company_id IN'][]=$names;
					$where7['ItemCompanies.company_id IN'][]=$names;
			}
		}
//exit;
		
	$SalesOrders = $this->ItemLedgers->SalesOrders->find()->contain(['SalesOrderRows','Invoices'=>['InvoiceRows' => function($q) {
				return $q->select(['invoice_id','sales_order_row_id','item_id','total_qty' => $q->func()->sum('InvoiceRows.quantity')])->group('InvoiceRows.sales_order_row_id');
	}]])->where($where1);
		
	$sales_order_qty=[];
	$invoice_qty=[];
	$sales_id=[];
		foreach($SalesOrders as $SalesOrder){ $sales_qty=[];
			foreach($SalesOrder->invoices as $invoice){
				foreach($invoice->invoice_rows as $invoice_row){
					@$invoice_qty[$invoice_row['item_id']]+=$invoice_row['total_qty'];
				}
			}
			foreach($SalesOrder->sales_order_rows as $sales_order_row){  
				@$sales_order_qty[$sales_order_row['item_id']]+=$sales_order_row['quantity'];
				@$sales_qty[$sales_order_row['item_id']]+=$sales_order_row['quantity'];
			}
			foreach(@$sales_qty as $key=>$sales_order_qt){
					if(@$sales_order_qt > @$invoice_qty[$key] ){ 
						@$sales_id[$key].=@$SalesOrder->id.',';
					}
				
			}
		}
		
	$PurchaseOrders = $this->ItemLedgers->PurchaseOrders->find()->contain(['PurchaseOrderRows','Grns'=>['GrnRows' => function($q) {
				return $q->select(['grn_id','purchase_order_row_id','item_id','total_qty' => $q->func()->sum('GrnRows.quantity')])->group('GrnRows.purchase_order_row_id');
	}]])->where($where3);
		//pr($PurchaseOrders->toArray()); exit;
	$purchase_order_qty=[];
	$grn_qty=[];
	$purchase_id=[];
		foreach($PurchaseOrders as $PurchaseOrder){ $sales_qty=[];
			foreach($PurchaseOrder->grns as $grn){
				foreach($grn->grn_rows as $grn_row){
					@$grn_qty[$grn_row['item_id']]+=$grn_row['total_qty'];
				}
			}
			foreach($PurchaseOrder->purchase_order_rows as $purchase_order_row){  
				@$purchase_order_qty[$purchase_order_row['item_id']]+=$purchase_order_row['quantity'];
				@$sales_qty[$purchase_order_row['item_id']]+=$purchase_order_row['quantity'];
			}
			foreach(@$sales_qty as $key=>$sales_order_qt){
					if(@$sales_order_qt > @$grn_qty[$key] ){ 
						@$purchase_id[$key].=@$PurchaseOrder->id.',';
					}
				
			}
		}
		
	$Quotations = $this->ItemLedgers->Quotations->find()->contain(['QuotationRows','SalesOrders'=>['SalesOrderRows' => function($q) {
				return $q->select(['sales_order_id','quotation_row_id','item_id','total_qty' => $q->func()->sum('SalesOrderRows.quantity')])->group('SalesOrderRows.quotation_row_id');
	}]])->where($where5);
	//	pr($Quotations->toArray()); exit;
	$qo_qty=[];
	$so_qty=[];
	$qotation_id=[];
		foreach($Quotations as $Quotation){ $sales_qty=[];
			foreach($Quotation->sales_orders as $sales_order){
				foreach($sales_order->sales_order_rows as $sales_order_row){
					@$so_qty[$sales_order_row['item_id']]+=$sales_order_row['total_qty'];
				}
			}
			foreach($Quotation->quotation_rows as $quotation_row){  
				@$qo_qty[$quotation_row['item_id']]+=$quotation_row['quantity'];
				@$sales_qty[$quotation_row['item_id']]+=$quotation_row['quantity'];
			}
			foreach(@$sales_qty as $key=>$sales_order_qt){
					if(@$sales_order_qt > @$so_qty[$key] ){  
						@$qotation_id[$key].=@$Quotation->id.',';
					}
				
			}
		}
	//pr($qo_qty)	; pr($so_qty)	;  exit;
	$MaterialIndents = $this->ItemLedgers->MaterialIndents->find()->contain(['MaterialIndentRows','PurchaseOrders'=>['PurchaseOrderRows' => function($q) {
				return $q->select(['purchase_order_id','material_indent_row_id','item_id','total_qty' => $q->func()->sum('PurchaseOrderRows.quantity')])->group('PurchaseOrderRows.material_indent_row_id');
	}]])->where($where4);
		
		//pr($MaterialIndents->toArray()); exit;
	$mi_qty=[];
	$po_qty=[];
	$mi_id=[];
		foreach($MaterialIndents as $MaterialIndent){ $sales_qty=[];
			foreach($MaterialIndent->purchase_orders as $purchase_order){
				foreach($purchase_order->purchase_order_rows as $purchase_order_row){ 
					if($purchase_order_row->material_indent_row_id){
						@$po_qty[$purchase_order_row['item_id']]+=$purchase_order_row['total_qty'];
					}
				}
			}
		//	pr($MaterialIndents->toArray()); exit;
			foreach(@$MaterialIndent->material_indent_rows as $material_indent_row){  
				@$mi_qty[$material_indent_row['item_id']]+=$material_indent_row['required_quantity'];
				@$sales_qty[$material_indent_row['item_id']]+=$material_indent_row['required_quantity'];
			}
			foreach(@$sales_qty as $key=>$sales_order_qt){
					if(@$sales_order_qt > @$po_qty[$key] ){ 
						@$mi_id[$key].=@$MaterialIndent->id.',';
					}
				
			}
		}

//	pr($mi_qty)	;	
	//pr($po_qty)	; 
	//exit;
		
		if(!empty($company_name)){ 
		$ItemLedgers = $this->ItemLedgers->find();
				$totalInCase = $ItemLedgers->newExpr()
					->addCase(
						$ItemLedgers->newExpr()->add(['in_out' => 'In']),
						$ItemLedgers->newExpr()->add(['quantity']),
						'integer'
					);
				$totalOutCase = $ItemLedgers->newExpr()
					->addCase(
						$ItemLedgers->newExpr()->add(['in_out' => 'Out']),
						$ItemLedgers->newExpr()->add(['quantity']),
						'integer'
					);

				$ItemLedgers->select([
					'total_in' => $ItemLedgers->func()->sum($totalInCase),
					'total_out' => $ItemLedgers->func()->sum($totalOutCase),'id','item_id'
				])
				->group('item_id')
				->autoFields(true)
				->where($where)
				->where($where6)
				->contain(['Items' => function($q) use($where7,$where){
					return $q->where($where)->where(['Items.source'=>'Purchessed/Manufactured'])->orWhere(['Items.source'=>'Purchessed'])->contain(['ItemCompanies'=>function($p) use($where7){
						return $p->where($where7)->where(['ItemCompanies.freeze' => 0]);
					}]);
				}]);
			//	pr($ItemLedgers->toArray());  exit;
		}else{ // exit;
			$ItemLedgers = $this->ItemLedgers->find();
				$totalInCase = $ItemLedgers->newExpr()
					->addCase(
						$ItemLedgers->newExpr()->add(['in_out' => 'In']),
						$ItemLedgers->newExpr()->add(['quantity']),
						'integer'
					);
				$totalOutCase = $ItemLedgers->newExpr()
					->addCase(
						$ItemLedgers->newExpr()->add(['in_out' => 'Out']),
						$ItemLedgers->newExpr()->add(['quantity']),
						'integer'
					);

				$ItemLedgers->select([
					'total_in' => $ItemLedgers->func()->sum($totalInCase),
					'total_out' => $ItemLedgers->func()->sum($totalOutCase),'id','item_id'
				])
				->group('item_id')
				->autoFields(true)
				->where($where)
				->where(['company_id'=>$st_company_id])
				->contain(['Items' => function($q) use($where,$st_company_id){
					return $q->where($where)->where(['Items.source'=>'Purchessed/Manufactured'])->orWhere(['Items.source'=>'Purchessed'])->contain(['ItemCompanies'=>function($p) use($st_company_id){
						return $p->where(['company_id'=>$st_company_id,'ItemCompanies.freeze' => 0]);
					}]);
				}]);
		}	
		
		$ItemMiniStock=[];
		$Items =$this->ItemLedgers->Items->ItemCompanies->find()->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
			foreach($Items as $Item){ 
					$ItemMiniStock[$Item->item_id]=$Item->minimum_stock;
				
			}
		
				$material_report=[];
		foreach ($ItemLedgers as $itemLedger){ //pr($itemLedger->item->item_companies[0]->minimum_stock); exit;
			
			$item_name=$itemLedger->item->name;
			$item_id=$itemLedger->item->id;
			$Current_Stock=$itemLedger->total_in-$itemLedger->total_out;
			$material_report[]=array('item_name'=>$item_name,'item_id'=>$item_id,'Current_Stock'=>$Current_Stock,'minimum_stock'=>@$itemLedger->item->item_companies[0]->minimum_stock);
			
		} 
		
//pr($ItemLedgers->toArray()); exit;
		
		//exit;
		$ItemCategories = $this->ItemLedgers->Items->ItemCategories->find('list')->order(['ItemCategories.name' => 'ASC']);
		$ItemGroups = $this->ItemLedgers->Items->ItemGroups->find('list')->order(['ItemGroups.name' => 'ASC']);
		$ItemSubGroups = $this->ItemLedgers->Items->ItemSubGroups->find('list')->order(['ItemSubGroups.name' => 'ASC']);
		$Items = $this->ItemLedgers->Items->find('list')->order(['Items.name' => 'ASC']);
		$Companies = $this->ItemLedgers->Companies->find('list')->order(['Companies.name' => 'ASC']);
			
		$this->set(compact('material_report','mit','url','ItemCategories','ItemGroups','ItemSubGroups','Items','Companies','st_company_id','total_indent','stockstatus','jobCardQty','ItemDatas','stock','ItemMiniStock','invoice_qty','sales_order_qty','sales_id','purchase_order_qty','grn_qty','purchase_id','qotation_id','qo_qty','so_qty','mi_qty','po_qty','mi_id'));
			
	 }
	
	
	public function excelMetarialExport(){
		$this->viewBuilder()->layout(''); 
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		//$Items = $this->ItemLedgers->Items->find()->where(['source'=>'Purchessed/Manufactured'])->orWhere(['source'=>'Purchessed']); 
		/* $material_items_for_purchase=[];
		$material_items_for_purchase[]=array('item_name'=>'Kgn212','item_id'=>'144','quantity'=>'25','company_id'=>'25','employee_name'=>'Gopal','company_name'=>'STL','material_indent_id'=>'2');
		
		$to=json_encode($material_items_for_purchase);
		//pr($to); exit;
		$this->redirect(['controller'=>'PurchaseOrders','action' => 'add/'.$to.'']); */
		$mit=$this->ItemLedgers->newEntity();
		
		if ($this->request->is(['post'])) {
			$check=$this->request->data['check']; 
			$suggestindent=$this->request->data['suggestindent']; 
			$to_send=[];
			foreach($check as $item_id){
				$to_send[$item_id]=$suggestindent[$item_id];
			}

			$to=json_encode($to_send); 
			//rwjihf dfgdf?3qrrg
			//$this->redirect(['controller'=>'PurchaseOrders','action' => 'add/'.$to.'']);
			$this->redirect(['controller'=>'MaterialIndents','action' => 'add/'.$to.'']);
		}
		
		$salesOrders=$this->ItemLedgers->SalesOrders->find()
			->select(['total_rows'=>$this->ItemLedgers->SalesOrders->find()->func()->count('SalesOrderRows.id')])
			->leftJoinWith('SalesOrderRows', function ($q) {
				return $q->where(['SalesOrderRows.processed_quantity < SalesOrderRows.quantity']);
			})
			->where(['company_id'=>$st_company_id])
			->group(['SalesOrders.id'])
			->autoFields(true)
			->having(['total_rows >' => 0])
			->contain(['SalesOrderRows'])
			->toArray();
			//pr($salesOrders); exit; 
			
			$sales=[];
			foreach($salesOrders as $data){
				foreach($data->sales_order_rows as $row){ 
				//pr($row->quantity);
				$item_id=$row->item_id;
				$quantity=$row->quantity;
				$processed_quantity=$row->processed_quantity;
				$Sales_Order_stock=$quantity-$processed_quantity;
				$sales[$row->item_id]=@$sales[$row->item_id]+$Sales_Order_stock;
				}
				//$sales[$item_id]=@$sales[$item_id]+$Sales_Order_stock;
			}
			//pr($sales);exit;
		$JobCards=$this->ItemLedgers->JobCards->find()->where(['status'=>'Pending','company_id'=>$st_company_id])->contain(['JobCardRows']);
		
		$job_card_items=[];
		foreach($JobCards as $JobCard){
			foreach($JobCard->job_card_rows as $job_card_row){
				$job_card_items[$job_card_row->item_id]=@$job_card_items[$job_card_row->item_id]+$job_card_row->quantity;
			}
		}		
		//pr($job_card_items); exit;
		
		$ItemLedgers = $this->ItemLedgers->find();
				$totalInCase = $ItemLedgers->newExpr()
					->addCase(
						$ItemLedgers->newExpr()->add(['in_out' => 'In']),
						$ItemLedgers->newExpr()->add(['quantity']),
						'integer'
					);
				$totalOutCase = $ItemLedgers->newExpr()
					->addCase(
						$ItemLedgers->newExpr()->add(['in_out' => 'Out']),
						$ItemLedgers->newExpr()->add(['quantity']),
						'integer'
					);

				$ItemLedgers->select([
					'total_in' => $ItemLedgers->func()->sum($totalInCase),
					'total_out' => $ItemLedgers->func()->sum($totalOutCase),'id','item_id'
				])
				->group('item_id')
				->autoFields(true)
				->contain(['Items' => function($q) use($st_company_id){
					return $q->where(['Items.source'=>'Purchessed/Manufactured'])->orWhere(['Items.source'=>'Purchessed'])->contain(['ItemCompanies'=>function($p) use($st_company_id){
						return $p->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
					}]);
				}]);
				//pr($ItemLedgers->toArray()); exit;
		foreach ($ItemLedgers as $itemLedger){
			if($itemLedger->company_id==$st_company_id){
			$item_name=$itemLedger->item->name;
			$item_id=$itemLedger->item->id;
			$Current_Stock=$itemLedger->total_in-$itemLedger->total_out;
			
			
			$material_report[]=array('item_name'=>$item_name,'item_id'=>$item_id,'Current_Stock'=>$Current_Stock,'sales_order'=>@$sales[$item_id],'job_card_qty'=>@$job_card_items[$item_id]);
			}
		} 
			
		$this->set(compact('material_report','mit'));
			
	}
	public function fetchLedger($item_id=null,$from_date=null,$to_date=null)
    {
		//$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
        $this->paginate = [
            'contain' => ['Items']
        ];
		$where =[];
		$where['item_id']=$item_id;
		$where['company_id']=$st_company_id;
		if(!empty($from_date)){
			$From=date("Y-m-d",strtotime($from_date));
			$where['processed_on >=']=$From;
		}
		if(!empty($to_date)){
			$To=date("Y-m-d",strtotime($to_date));
			$where['processed_on <=']=$To;
		}
        $itemLedgers2 = $this->ItemLedgers->find()->where($where)->order(['processed_on'=>'DESC']);
		$itemLedgers=[];
		foreach($itemLedgers2 as $itemLedger){
			if($itemLedger->source_model =='Items'){
				$itemLedger->voucher_info='-';
				$itemLedger->party_type='Item';
				$itemLedger->party_info='-'; 
			}else{
				$result=$this->GetVoucherParty($itemLedger->source_model,$itemLedger->source_id); 
				$itemLedger->voucher_info=$result['voucher_info'];
				$itemLedger->party_type=$result['party_type'];
				$itemLedger->party_info=$result['party_info']; 	
			}
			$itemLedgers[]=$itemLedger;
		}
		$this->set(compact('itemLedgers'));
	}
	
	public function inventoryDailyReport(){ 
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$from_date=$this->request->query('From');
		$to_date=$this->request->query('To');
		$where =[];
		if(!empty($from_date)){
			$From=date("Y-m-d",strtotime($from_date));
			$where['processed_on >=']=$From;
		}
		if(!empty($to_date)){
			$To=date("Y-m-d",strtotime($to_date));
			$where['processed_on <=']=$To;
		}
		$itemLedgers = $this->ItemLedgers->find()
						->where($where)
						->order(['processed_on'=>'DESC'])
						->contain(['Items'])
						->where(['ItemLedgers.company_id' => $st_company_id]); 
		
		$itemDatas=[];
		foreach($itemLedgers as $itemLedger){
			$itemDatas[$itemLedger['source_model'].$itemLedger['source_id']][]=$itemLedger;
			
		}
		//pr($itemLedgers->toArray());exit;
		$serial_nos=[];
		$voucher_no=[];
		$link=[];
		foreach($itemDatas as $key=>$itemData){
			foreach($itemData as $itemDetail){
				///query in item serial nos where source model && sourch id invoice_id
				if($itemDetail['source_model']=='Invoices'){
					$serialnoarray=$this->ItemLedgers->Items->SerialNumbers->find()->where(['invoice_id'=>$itemDetail['source_id'],'item_id'=>$itemDetail['item_id']]);
					//pr($itemDetail['source_id']);  exit;
					$invoice=$this->ItemLedgers->Invoices->find()->where(['Invoices.id'=>$itemDetail['source_id']])->first();
					$serial_nos[$key][$itemDetail->item_id]=$serialnoarray->toArray();
					$in_id=$itemDetail['source_id'];
					@$invoice1=($invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4);
					//if()
					
					$voucher_no[$key][]=$invoice1;
					
					if($invoice['invoice_type']=="GST"){
						$link1 = ['controller'=>'Invoices','action' => 'gst-confirm'];
					}else{ 
						$link1 = ['controller'=>'Invoices','action' => 'confirm'];
					}
					$link[$key]=$link1;
				}
				if($itemDetail['source_model']=='Grns'){
					$serialnoarray=$this->ItemLedgers->Items->SerialNumbers->find()->where(['grn_id'=>$itemDetail['source_id'],'item_id'=>$itemDetail['item_id']]);
					//pr($serialnoarray->toArray());
					$grn=$this->ItemLedgers->Grns->find()->where(['Grns.id'=>$itemDetail['source_id']])->first();
					$voucher_no[$key][]=($grn->grn1.'/GRN-'.str_pad($grn->grn2, 3, '0', STR_PAD_LEFT).'/'.$grn->grn3.'/'.$grn->grn4);
					$serial_nos[$key][$itemDetail->item_id]=$serialnoarray->toArray();
					
					$link1 = ['controller'=>'Grns','action' => 'View'];
					
					$link[$key]=$link1;
					
					
				}if($itemDetail['source_model']=='Inventory Vouchers'){
					//ww
					$InventoryVoucher=$this->ItemLedgers->InventoryVouchers->find()->where(['InventoryVouchers.id'=>$itemDetail['source_id']])->first();
					
					$serialnoarray=$this->ItemLedgers->Items->SerialNumbers->find()->where(['iv_row_id'=>$itemDetail['iv_row_id'],'item_id'=>$itemDetail['item_id']]);
									
					$serial_nos[$key][$itemDetail->item_id]=$serialnoarray->toArray();
					$voucher_no[$key][]=('#'.str_pad($InventoryVoucher->iv_number, 4, '0', STR_PAD_LEFT));
					$link1 = ['controller'=>'InventoryVouchers','action' => 'View'];
					
					$link[$key]=$link1;
				}if($itemDetail['source_model']=='Inventory Transfer Voucher'){
					$serialnoarray=$this->ItemLedgers->Items->SerialNumbers->find()->where(['itv_id'=>$itemDetail['source_id'],'item_id'=>$itemDetail['item_id']]);
					
					$InventoryTransferVoucher=$this->ItemLedgers->InventoryTransferVouchers->find()->where(['InventoryTransferVouchers.id'=>$itemDetail['source_id']])->first();
					
					 if($InventoryTransferVoucher->in_out=='in_out')
					    { 
							$voucher_no[$key][]=('ITV-'.str_pad($InventoryTransferVoucher->voucher_no, 4, '0', STR_PAD_LEFT));
							$link1 = ['controller'=>'InventoryTransferVouchers','action' => 'View'];
							$link[$key]=$link1;
						}
						else if($InventoryTransferVoucher->in_out=='in') 
						{ 
							$voucher_no[$key][]=('ITVI-'.str_pad($InventoryTransferVoucher->voucher_no, 4, '0', STR_PAD_LEFT));
							$link1 = ['controller'=>'InventoryTransferVouchers','action' => 'inView'];
							$link[$key]=$link1;
						}else {
							$voucher_no[$key][]=('ITVO-'.str_pad($InventoryTransferVoucher->voucher_no, 4, '0', STR_PAD_LEFT)) ;
							$link1 = ['controller'=>'InventoryTransferVouchers','action' => 'outView'];
							$link[$key]=$link1;
						} 
					$serial_nos[$key][$itemDetail->item_id]=$serialnoarray->toArray();
				}if($itemDetail['source_model']=='Purchase Return'){
					$serialnoarray=$this->ItemLedgers->Items->SerialNumbers->find()->where(['purchse_return_id'=>$itemDetail['source_id'],'item_id'=>$itemDetail['item_id']]);
					$PurchaseReturn=$this->ItemLedgers->PurchaseReturns->find()->where(['PurchaseReturns.id'=>$itemDetail['source_id']])->first();
					
					$serial_nos[$key][$itemDetail->item_id]=$serialnoarray->toArray();
					$voucher_no[$key][]=('#'.str_pad($PurchaseReturn->voucher_no, 4, '0', STR_PAD_LEFT));
					
					$link1 = ['controller'=>'PurchaseReturns','action' => 'View'];
					$link[$key]=$link1;
				}if($itemDetail['source_model']=='Sale Return'){
					$serialnoarray=$this->ItemLedgers->Items->SerialNumbers->find()->where(['sales_return_id'=>$itemDetail['source_id'],'item_id'=>$itemDetail['item_id']]);
					$SaleReturn=$this->ItemLedgers->SaleReturns->find()->where(['SaleReturns.id'=>$itemDetail['source_id']])->first();
					
					$serial_nos[$key][$itemDetail->item_id]=$serialnoarray->toArray();
					@$voucher_no[@$key][]=($SaleReturn->sr1.'/SR-'.str_pad($SaleReturn->sr2, 3, '0', STR_PAD_LEFT).'/'.$SaleReturn->sr3.'/'.$SaleReturn->sr4);
					$link1 = ['controller'=>'SaleReturns','action' => 'View'];
					$link[$key]=$link1;
				}
			}
			
		}
	//pr($serial_nos);
	// exit;
	//pr($link);exit;
		$this->set(compact('itemDatas','serial_nos','voucher_no','From','To','link','url'));
	}
	
	public function excelInventory(){
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$from_date=$this->request->query('From');
		$to_date=$this->request->query('To');
		$where =[];
		if(!empty($from_date)){
			$From=date("Y-m-d",strtotime($from_date));
			$where['processed_on >=']=$From;
		}
		if(!empty($to_date)){
			$To=date("Y-m-d",strtotime($to_date));
			$where['processed_on <=']=$To;
		}
		$itemLedgers = $this->ItemLedgers->find()
						->where($where)
						->order(['processed_on'=>'DESC'])
						->contain(['Items'])
						->where(['ItemLedgers.company_id' => $st_company_id]); 
		
		$itemDatas=[];
		foreach($itemLedgers as $itemLedger){
			$itemDatas[$itemLedger['source_model'].$itemLedger['source_id']][]=$itemLedger;
			
		}
		$serial_nos=[];
		$voucher_no=[];
		$link=[];
		foreach($itemDatas as $key=>$itemData){
			foreach($itemData as $itemDetail){
				///query in item serial nos where source model && sourch id invoice_id
				if($itemDetail['source_model']=='Invoices'){
					$serialnoarray=$this->ItemLedgers->Items->ItemSerialNumbers->find()->where(['invoice_id'=>$itemDetail['source_id'],'item_id'=>$itemDetail['item_id']]);
					//pr($itemDetail['source_id']);  exit;
					$invoice=$this->ItemLedgers->Invoices->find()->where(['Invoices.id'=>$itemDetail['source_id']])->first();
					$serial_nos[$key][$itemDetail->item_id]=$serialnoarray->toArray();
					$in_id=$itemDetail['source_id'];
					$invoice1=($invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4);
					//if()
					
					$voucher_no[$key][]=$invoice1;
					
					if($invoice['invoice_type']=="GST"){
						$link1 = ['controller'=>'Invoices','action' => 'gst-confirm'];
					}else{ 
						$link1 = ['controller'=>'Invoices','action' => 'confirm'];
					}
					$link[$key]=$link1;
				}
				if($itemDetail['source_model']=='Grns'){
					$serialnoarray=$this->ItemLedgers->Items->ItemSerialNumbers->find()->where(['grn_id'=>$itemDetail['source_id'],'item_id'=>$itemDetail['item_id']]);
					//pr($serialnoarray->toArray());
					$grn=$this->ItemLedgers->Grns->find()->where(['Grns.id'=>$itemDetail['source_id']])->first();
					$voucher_no[$key][]=($grn->grn1.'/GRN-'.str_pad($grn->grn2, 3, '0', STR_PAD_LEFT).'/'.$grn->grn3.'/'.$grn->grn4);
					$serial_nos[$key][$itemDetail->item_id]=$serialnoarray->toArray();
					
					$link1 = ['controller'=>'Grns','action' => 'View'];
					
					$link[$key]=$link1;
					
					
				}if($itemDetail['source_model']=='Inventory Vouchers'){
					//ww
					$InventoryVoucher=$this->ItemLedgers->InventoryVouchers->find()->where(['InventoryVouchers.id'=>$itemDetail['source_id']])->first();
					
					$serialnoarray=$this->ItemLedgers->Items->ItemSerialNumbers->find()->where(['iv_invoice_id'=>$InventoryVoucher->invoice_id,'item_id'=>$itemDetail['item_id']]);
									
					$serial_nos[$key][$itemDetail->item_id]=$serialnoarray->toArray();
					$voucher_no[$key][]=('#'.str_pad($InventoryVoucher->iv_number, 4, '0', STR_PAD_LEFT));
					$link1 = ['controller'=>'InventoryVouchers','action' => 'View'];
					
					$link[$key]=$link1;
				}if($itemDetail['source_model']=='Inventory Transfer Voucher'){
					$serialnoarray=$this->ItemLedgers->Items->ItemSerialNumbers->find()->where(['inventory_transfer_voucher_id'=>$itemDetail['source_id'],'item_id'=>$itemDetail['item_id']]);
					
					$InventoryTransferVoucher=$this->ItemLedgers->InventoryTransferVouchers->find()->where(['InventoryTransferVouchers.id'=>$itemDetail['source_id']])->first();
					
					 if($InventoryTransferVoucher->in_out=='in_out'){ 
							$voucher_no[$key][]=('ITV-'.str_pad($InventoryTransferVoucher->voucher_no, 4, '0', STR_PAD_LEFT));
							$link1 = ['controller'=>'InventoryTransferVouchers','action' => 'View'];
							$link[$key]=$link1;
						}else if($InventoryTransferVoucher->in_out=='in') { 
							$voucher_no[$key][]=('ITVI-'.str_pad($InventoryTransferVoucher->voucher_no, 4, '0', STR_PAD_LEFT));
							$link1 = ['controller'=>'InventoryTransferVouchers','action' => 'inView'];
							$link[$key]=$link1;
						}else {
							$voucher_no[$key][]=('ITVO-'.str_pad($InventoryTransferVoucher->voucher_no, 4, '0', STR_PAD_LEFT)) ;
							$link1 = ['controller'=>'InventoryTransferVouchers','action' => 'outView'];
							$link[$key]=$link1;
						} 
					$serial_nos[$key][$itemDetail->item_id]=$serialnoarray->toArray();
				}if($itemDetail['source_model']=='Purchase Return'){
					$serialnoarray=$this->ItemLedgers->Items->ItemSerialNumbers->find()->where(['invoice_id'=>$itemDetail['source_id'],'item_id'=>$itemDetail['item_id']]);
					$PurchaseReturn=$this->ItemLedgers->PurchaseReturns->find()->where(['PurchaseReturns.id'=>$itemDetail['source_id']])->first();
					
					$serial_nos[$key][$itemDetail->item_id]=$serialnoarray->toArray();
					$voucher_no[$key][]=('#'.str_pad($PurchaseReturn->voucher_no, 4, '0', STR_PAD_LEFT));
					
					$link1 = ['controller'=>'PurchaseReturns','action' => 'View'];
					$link[$key]=$link1;
				}if($itemDetail['source_model']=='Sale Return'){
					$serialnoarray=$this->ItemLedgers->Items->ItemSerialNumbers->find()->where(['invoice_id'=>$itemDetail['source_id'],'item_id'=>$itemDetail['item_id']]);
					$SaleReturn=$this->ItemLedgers->SaleReturns->find()->where(['SaleReturns.id'=>$itemDetail['source_id']])->first();
					
					$serial_nos[$key][$itemDetail->item_id]=$serialnoarray->toArray();
					$voucher_no[$key][]=($SaleReturn->sr1.'/SR-'.str_pad($SaleReturn->sr2, 3, '0', STR_PAD_LEFT).'/'.$SaleReturn->sr3.'/'.$SaleReturn->sr4);
					$link1 = ['controller'=>'SaleReturns','action' => 'View'];
					$link[$key]=$link1;
				}
			}
			
		}
	
	//pr($link);exit;
		$this->set(compact('itemDatas','serial_nos','voucher_no','From','To','link','from_date','to_date'));
	}
	
	
	
	public function addToBucket($item_id=null,$qty=null){
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$ItemBuckets = $this->ItemLedgers->ItemBuckets->newEntity();
		$ItemBuckets->item_id=$item_id;
		$ItemBuckets->quantity=$qty;
		
		//$ItemBuckets->mi_number=$qty;
		//pr($ItemBuckets);exit;
		$this->ItemLedgers->ItemBuckets->save($ItemBuckets);

		$this->set(compact('ItemBuckets','item_id','qty'));
		
	}

	public function	openingStock(){
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		pr($st_company_id);
		exit;
	}
	

	
	public function ItemBucket()
	{
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$materialIndent = $this->MaterialIndents->newEntity();
		
		$ItemBuckets = $this->ItemLedgers->ItemBuckets->find()->contain(['Items'])->toArray();
		
		$this->set(compact('ItemBuckets'));
	}
	public function entryCount(){
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');

		$ItemLedgers=$this->ItemLedgers->find();
		$Items=$this->ItemLedgers->Items->find(); ?>
		<table border="1">
			<tr>
				<th>S.N</th>
				<th>Item Id</th>
				<th>Quantity</th>
				<th>Rate</th>
				
			</tr>
		<?php $i=1;
		  foreach($ItemLedgers as $ItemLedger){ 
			$AccountGroupsexists = $this->ItemLedgers->Items->exists(['Items.id' => $ItemLedger->item_id]);
				if(!$AccountGroupsexists){ ?>
			<tr>
				<td><?php echo $i++; ?></td>
				<td><?php echo $ItemLedger->item_id; ?></td>
				<td><?php echo $ItemLedger->quantity; ?></td>
				<td><?php echo $ItemLedger->rate; ?></td>
			</tr>
		<?php }
			} exit;
	}
	
	public function weightedAvgCostIvs($item_id=null){
			$this->viewBuilder()->layout('');
			$session = $this->request->session();
			$st_company_id = $session->read('st_company_id');
			
			$Items = $this->ItemLedgers->Items->get($item_id, [
				'contain' => ['ItemCompanies'=>function($q) use($st_company_id){
					return $q->where(['company_id'=>$st_company_id]);
				}]
			]);
			
			if($Items->item_companies[0]->serial_number_enable == '0'){  
				$stock=[];  $sumValue=[];
				foreach($Items as $Item){
					$StockLedgers=$this->ItemLedgers->find()->where(['ItemLedgers.item_id'=>$Item->id,'ItemLedgers.company_id'=>$st_company_id])->order(['ItemLedgers.processed_on'=>'ASC']);
					foreach($StockLedgers as $StockLedger){ 
						if($StockLedger->in_out=='In'){
							if(($StockLedger->source_model=='Grns' and $StockLedger->rate_updated=='Yes') or ($StockLedger->source_model!='Grns')){
								for($inc=0;$inc<$StockLedger->quantity;$inc++){
									$stock[$Item->id][]=$StockLedger->rate;
								}
							}
						}
					}
					foreach($StockLedgers as $StockLedger){
						if($StockLedger->in_out=='Out'){
							if(sizeof(@$stock[$Item->id])>0){
								$stock[$Item->id] = array_slice($stock[$Item->id], $StockLedger->quantity); 
							}
						}
					}
					//echo "hello"; exit;
					if(sizeof(@$stock[$Item->id]) > 0){ 
						foreach(@$stock[$Item->id] as $stockRate){
							@$sumValue[$Item->id]+=@$stockRate;
						}
					}
				}
			
			}else{
				
			}
		exit;	
	}
	
	public function inventoryDailyReports(){ 
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$from_date=$this->request->query('From');
		$to_date=$this->request->query('To');
		$where =[];
		if(!empty($from_date)){
			$From=date("Y-m-d",strtotime($from_date));
			$where['processed_on >=']=$From;
		}
		if(!empty($to_date)){
			$To=date("Y-m-d",strtotime($to_date));
			$where['processed_on <=']=$To;
		}
		$itemLedgers = $this->ItemLedgers->find()
						->where($where)
						->order(['processed_on'=>'DESC'])
						->contain(['Items'])
						->where(['ItemLedgers.company_id' => $st_company_id]); 
		
		$itemDatas=[];
		foreach($itemLedgers as $itemLedger){
			$itemDatas[$itemLedger['source_model'].$itemLedger['source_id'].','.$itemLedger['processed_on'].','.$itemLedger['source_id'].','.$itemLedger['in_out'].','.$itemLedger['quantity']][]=$itemLedger;
			
		}
		//pr($itemLedgers->toArray());exit;
		$serial_nos=[];
		$voucher_no=[];
		$sourceData=[];
		$link=[];
		foreach($itemDatas as $key=>$itemData){
			foreach($itemData as $itemDetail){
				$keyVal = explode(',',$key);
				if($itemDetail['source_model']=='Invoices'){
					$invoice=$this->ItemLedgers->Invoices->find()->where(['Invoices.id'=>$itemDetail['source_id']])->contain(['InvoiceRows'=>['Items','SerialNumbers']])->first();
					$sourceData[$key] = $invoice->invoice_rows;
					$invoice=$this->ItemLedgers->Invoices->find()->where(['Invoices.id'=>$itemDetail['source_id']])->first();
					@$invoice1=($invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4);
					
					$voucher_no[$keyVal[0]][]=$invoice1;
					
					if($invoice['invoice_type']=="GST"){
						$link1 = ['controller'=>'Invoices','action' => 'gst-confirm'];
					}else{ 
						$link1 = ['controller'=>'Invoices','action' => 'confirm'];
					}
					$link[$keyVal[0]]=$link1;
				}
				if($itemDetail['source_model']=='Grns')
				{
					$grnDetail=$this->ItemLedgers->Grns->find()->where(['Grns.id'=>$itemDetail['source_id']])->contain(['GrnRows'=>['Items','SerialNumbers']])->first();
					$sourceData[$key] = $grnDetail->grn_rows;
					
					$grn=$this->ItemLedgers->Grns->find()->where(['Grns.id'=>$itemDetail['source_id']])->first();
					$voucher_no[$keyVal[0]][]=($grn->grn1.'/GRN-'.str_pad($grn->grn2, 3, '0', STR_PAD_LEFT).'/'.$grn->grn3.'/'.$grn->grn4);
					
					$link1 = ['controller'=>'Grns','action' => 'View'];
					$link[$keyVal[0]]=$link1;
				}
				if($itemDetail['source_model']=='Inventory Vouchers')
				{
					$InventoryVoucherDetail=$this->ItemLedgers->InventoryVouchers->find()->where(['InventoryVouchers.id'=>$itemDetail['source_id']])->contain(['InventoryVoucherRows'=>['Items','SerialNumbers']])->first();
					$sourceData[$key] = $InventoryVoucherDetail->inventory_voucher_rows;
					
					$InventoryVoucher=$this->ItemLedgers->InventoryVouchers->find()->where(['InventoryVouchers.id'=>$itemDetail['source_id']])->first();
					$voucher_no[$keyVal[0]][]=('#'.str_pad($InventoryVoucher->iv_number, 4, '0', STR_PAD_LEFT));
					$link1 = ['controller'=>'InventoryVouchers','action' => 'View'];
					
					$link[$keyVal[0]]=$link1;
				}
				if($itemDetail['source_model']=='Inventory Transfer Voucher')
				{
					$InventoryTransferVoucher=$this->ItemLedgers->InventoryTransferVouchers->find()->where(['InventoryTransferVouchers.id'=>$itemDetail['source_id']])->first();
					
					 if($InventoryTransferVoucher->in_out=='in_out')
					    { 
							$voucher_no[$keyVal[0]][]=('ITV-'.str_pad($InventoryTransferVoucher->voucher_no, 4, '0', STR_PAD_LEFT));
							$link1 = ['controller'=>'InventoryTransferVouchers','action' => 'View'];
							$link[$keyVal[0]]=$link1;
						}
						else if($InventoryTransferVoucher->in_out=='in') 
						{ 
							$voucher_no[$keyVal[0]][]=('ITVI-'.str_pad($InventoryTransferVoucher->voucher_no, 4, '0', STR_PAD_LEFT));
							$link1 = ['controller'=>'InventoryTransferVouchers','action' => 'inView'];
							$link[$keyVal[0]]=$link1;
						}else {
							$voucher_no[$keyVal[0]][]=('ITVO-'.str_pad($InventoryTransferVoucher->voucher_no, 4, '0', STR_PAD_LEFT)) ;
							$link1 = ['controller'=>'InventoryTransferVouchers','action' => 'outView'];
							$link[$keyVal[0]]=$link1;
						} 
					
				}
				if($itemDetail['source_model']=='Purchase Return')
				{
					$serialnoarray=$this->ItemLedgers->Items->SerialNumbers->find()->where(['purchse_return_id'=>$itemDetail['source_id'],'item_id'=>$itemDetail['item_id']]);
					$PurchaseReturn=$this->ItemLedgers->PurchaseReturns->find()->where(['PurchaseReturns.id'=>$itemDetail['source_id']])->first();
					
					$serial_nos[$key][$itemDetail->item_id]=$serialnoarray->toArray();
					$voucher_no[$key][]=('#'.str_pad($PurchaseReturn->voucher_no, 4, '0', STR_PAD_LEFT));
					
					$link1 = ['controller'=>'PurchaseReturns','action' => 'View'];
					$link[$key]=$link1;
				}
				if($itemDetail['source_model']=='Sale Return')
				{
					$serialnoarray=$this->ItemLedgers->Items->SerialNumbers->find()->where(['sales_return_id'=>$itemDetail['source_id'],'item_id'=>$itemDetail['item_id']]);
					$SaleReturn=$this->ItemLedgers->SaleReturns->find()->where(['SaleReturns.id'=>$itemDetail['source_id']])->first();
					
					$serial_nos[$key][$itemDetail->item_id]=$serialnoarray->toArray();
					@$voucher_no[@$key][]=($SaleReturn->sr1.'/SR-'.str_pad($SaleReturn->sr2, 3, '0', STR_PAD_LEFT).'/'.$SaleReturn->sr3.'/'.$SaleReturn->sr4);
					$link1 = ['controller'=>'SaleReturns','action' => 'View'];
					$link[$key]=$link1;
				}
			}
			
		}
		/* pr($sourceData);
	exit; */
		$this->set(compact('itemDatas','serial_nos','voucher_no','From','To','link','url','sourceData'));
	}
}

