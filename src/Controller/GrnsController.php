<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Grns Controller
 *
 * @property \App\Model\Table\GrnsTable $Grns
 */
class GrnsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($status=null)
    {   
		$this->viewBuilder()->layout('index_layout');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Grns->FinancialYears->find()->where(['id'=>$st_year_id])->first();
       /*  $this->paginate = [
            'contain' => ['PurchaseOrders', 'Companies','Vendors']
        ]; */
		$pull_request=$this->request->query('pull-request');
		$grn_pull_request=$this->request->query('grn-pull-request');
		$Actionstatus="";
		$where1=[];
		$grn_no=$this->request->query('grn_no');
		$po_no=$this->request->query('po_no');
		$vendor=$this->request->query('vendor');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$this->set(compact('grn_no','vendor','From','po_no','To','grn_pull_request','pull_request'));
		if(!empty($grn_no)){
			//pr($grn_no); exit;
			$where1['grn2 LIKE']=$grn_no;
		}
		if(!empty($po_no)){
			$findpo = $this->Grns->PurchaseOrders->find()->where(['PurchaseOrders.po2'=>$po_no])->first();
			//pr($findpo->id); exit;
			$where1['Grns.purchase_order_id']=@$findpo->id;
		}
		
		if(!empty($vendor)){
			$where1['Vendors.company_name LIKE']='%'.$vendor.'%';
		}
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where1['Grns.date_created >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where1['Grns.date_created <=']=$To;
		}
      
		$where=[];
		if($status==null or $status=='Pending'){
			$where['status']='Pending';
		}elseif($status=='Invoice-Booked'){
			$where['status']='Invoice-Booked';
		}
		//$tdate=date('d-m-Y',strtotime($financial_year->date_to)); 
		if($grn_pull_request=="true"){
			$grns = $this->Grns->find()->contain(['PurchaseOrders', 'Companies','Vendors'])->where($where)->where($where1)->where(['Grns.company_id'=>$st_company_id])->order(['Grns.id' => 'DESC']);
		}else{ 
			
			$grns = $this->Grns->find()->contain(['PurchaseOrders', 'Companies','Vendors'])->where($where)->where($where1)->where(['Grns.company_id'=>$st_company_id,'Grns.financial_year_id'=>$st_year_id])->order(['Grns.id' => 'DESC']);
		}
		
		
		
		
        $this->set(compact('grns','pull_request','status','grn_pull_request','url','financial_year'));
        $this->set('_serialize', ['grns']);
    }
	
	/* public function ItemLedgerEntry()
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id'); //pr($st_company_id); exit;
		$Grns=$this->Grns->find()->contain(['GrnRows']);
		//pr($Grns->toArray()); exit;
		foreach($Grns as $grn){ 
			foreach($grn->grn_rows as $grn_row){ //pr($grn); exit;
				$itemLedger = $this->Grns->ItemLedgers->newEntity();
				$itemLedger->item_id = $grn_row->item_id;
				$itemLedger->quantity = $grn_row->quantity;
				$itemLedger->company_id = $grn->company_id;
				$itemLedger->source_model = 'Grns';
				$itemLedger->source_id = $grn->id;
				$itemLedger->in_out = 'In';
				$itemLedger->processed_on = $grn->transaction_date;
				$itemLedger->source_row_id = $grn_row->id;
				$this->Grns->ItemLedgers->save($itemLedger);
			}
		}
		
		foreach($Grns as $grn){ 
			$NewSerialNumbers=$this->Grns->ItemLedgers->NewSerialNumbers->find()->where(['grn_id'=>$grn->id]);
			foreach($NewSerialNumbers as $NewItem){
				$grn_row=$this->Grns->GrnRows->find()->where(['grn_id'=>$NewItem->grn_id,'item_id'=>$NewItem->item_id])->first();
				$SerialNumber = $this->Grns->ItemLedgers->SerialNumbers->newEntity();
				$SerialNumber->name = $NewItem->serial_no;
				$SerialNumber->item_id = $grn_row->item_id;
				$SerialNumber->status = 'In';
				$SerialNumber->grn_id = $grn_row->grn_id;
				$SerialNumber->grn_row_id = $grn_row->id;
				$SerialNumber->source_model = 'Grns';
				$SerialNumber->transaction_date = $grn->transaction_date;
				$SerialNumber->company_id = $grn->company_id;
				$this->Grns->ItemLedgers->SerialNumbers->save($SerialNumber);
			}
		} 
		
		echo "done"; exit;
	}
	
	public function DataMigrate()
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$PurchaseOrders=$this->Grns->PurchaseOrders->PurchaseOrderRows->find();
		//	pr($PurchaseOrders->toArray()); exit;
		foreach($PurchaseOrders as $PurchaseOrder){
			$Grns=$this->Grns->find()->contain(['GrnRows'])->where(['Grns.purchase_order_id'=>$PurchaseOrder->purchase_order_id])->toArray();
			
			if($Grns){
				if(sizeof($Grns) > 0){ 
					foreach($Grns as $Grn){
						foreach($Grn->grn_rows as $grn_row){ //pr($grn_row); exit;
							$query = $this->Grns->GrnRows->query();
							$query->update()
								->set(['purchase_order_row_id' => $PurchaseOrder->id])
								->where(['item_id' => $PurchaseOrder->item_id,'grn_id'=>$Grn->id])
								->execute();
							}
					}
				}
			}
		}
		echo "done"; exit;
	}  */
	
	public function exportExcel($status=null){
		$this->viewBuilder()->layout('');
		if(empty($status)){ $status="Pending"; }
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
        $this->paginate = [
            'contain' => ['PurchaseOrders', 'Companies','Vendors']
        ];
		$pull_request=$this->request->query('pull-request');
		$grn_pull_request=$this->request->query('grn-pull-request');
		$Actionstatus="";
		$where1=[];
		$grn_no=$this->request->query('grn_no');
		$po_no=$this->request->query('po_no');
		$vendor=$this->request->query('vendor');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$this->set(compact('grn_no','vendor','From','po_no','To','grn_pull_request','pull_request'));
		if(!empty($grn_no)){
			//pr($grn_no); exit;
			$where1['grn2 LIKE']=$grn_no;
		}
		if(!empty($po_no)){
			$findpo = $this->Grns->PurchaseOrders->find()->where(['PurchaseOrders.po2'=>$po_no])->first();
			//pr($findpo->id); exit;
			$where1['Grns.purchase_order_id']=@$findpo->id;
		}
		
		if(!empty($vendor)){
			$where1['Vendors.company_name LIKE']='%'.$vendor.'%';
		}
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where1['Grns.date_created >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where1['Grns.date_created <=']=$To;
		}
      
		$where=[];
		if($status==null or $status=='Pending'){
			$where['status']='Pending';
		}elseif($status=='Invoice-Booked'){
			$where['status']='Invoice-Booked';
		}
		
		$grns =$this->Grns->find()->where($where)->where($where1)->where(['Grns.company_id'=>$st_company_id])->contain(['PurchaseOrders', 'Companies','Vendors'])->order(['Grns.id' => 'DESC']);
        $this->set(compact('grns','pull_request','status','grn_pull_request','url'));
        $this->set('_serialize', ['grns']);
	}

    /**
     * View method
     *
     * @param string|null $id Grn id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$grn = $this->Grns->get($id, [
            'contain' => ['Companies','SerialNumbers','Creator','Vendors','PurchaseOrders'=>['PurchaseOrderRows','Grns'=>['GrnRows']],'GrnRows'=>['SerialNumbers','Items' => ['SerialNumbers','ItemCompanies' =>function($q) use($st_company_id){
									return $q->where(['company_id'=>$st_company_id]);
								}]]
        ]]);
		//pr($grn->grn_rows->item); exit;

        $this->set('grn', $grn);
        $this->set('_serialize', ['grn']);
    }

    public function report()
    {
		$grn = $this->Grns->find();
		
		foreach($grn as $grn)
		{
			$query = $this->Grns->query();
			$query->update()
				->set(['transaction_date' => $grn->date_created])
				->where(['id' => $grn->id])
				->execute();
		} 
		exit;
	}	
	
	
    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
		$purchase_order_id=@(int)$this->request->query('purchase-order');
		
		$purchase_order=array();
		
		if(!empty($purchase_order_id))
		{
			$purchase_order = $this->Grns->PurchaseOrders->get($purchase_order_id, [
				'contain' => [
						'PurchaseOrderRows.Items' => function ($q) {
						   return $q
								->contain(['ItemCompanies']);
						},'Companies','Vendors'
					]
			]);
		}
		$this->set(compact('purchase_order'));
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');

		 $st_year_id = $session->read('st_year_id');
		   $SessionCheckDate = $this->FinancialYears->get($st_year_id);
		   $fromdate1 = date("Y-m-d",strtotime($SessionCheckDate->date_from));   
		   $todate1 = date("Y-m-d",strtotime($SessionCheckDate->date_to)); 
		   $tody1 = date("Y-m-d");

		   $fromdate = strtotime($fromdate1);
		   $todate = strtotime($todate1); 
		   $tody = strtotime($tody1);

		  if($fromdate < $tody || $todate > $tody)
		   {
			 if($SessionCheckDate['status'] == 'Open')
			 { $chkdate = 'Found'; }
			 else
			 { $chkdate = 'Not Found'; }

		   }
		   else
			{
				$chkdate = 'Not Found';	
			}


		 $grn = $this->Grns->newEntity();
        if ($this->request->is('post')) {
			$grn->vendor_id=$purchase_order->vendor_id;
			$last_grn_no=$this->Grns->find()->select(['grn2'])->where(['company_id' => $st_company_id])->order(['grn2' => 'DESC'])->first();
			if($last_grn_no){
				$grn->grn2=(int)$last_grn_no->grn2+1;
			}else{
				$grn->grn2=1;
			}
			if($this->request->data['serial_numbers']){
			$serial_numbers=$this->request->data['serial_numbers']; 
			$item_serial_numbers=[];
			foreach($serial_numbers as $item_id=>$data){
				foreach($data as $sr)
				$item_serial_numbers[]=['item_id'=>$item_id,'serial_no'=>$sr,'status'=>'In'];
			}
			
			$this->request->data['item_serial_numbers']=$item_serial_numbers;
			//pr($this->request->data); exit;
			}
			$grn = $this->Grns->patchEntity($grn, $this->request->data);
			$grn->date_created = date("Y-m-d",strtotime($grn->date_created)); 
			
			$grn->purchase_order_id=$purchase_order_id;
			$grn->company_id=$st_company_id ;
			$grn->created_by=$this->viewVars['s_employee_id'];
			//pr($grn);exit;
			 if ($this->Grns->save($grn)) 
			 {
					if(!empty($purchase_order_id)){
						$grn->check=array_filter($grn->check);
						$i=0; 
						
						foreach($grn->check as $purchase_order_row_id){
							$qty=$grn->grn_rows[$i]['quantity'];
							$item_id=$grn->grn_rows[$i]['item_id'];
							$i++;

							//Insert in Item Ledger//
							$itemLedger = $this->Grns->ItemLedgers->newEntity();
							$itemLedger->item_id = $item_id;
							$itemLedger->quantity = $qty;
							$itemLedger->company_id = $grn->company_id;
							$itemLedger->source_model = 'Grns';
							$itemLedger->source_id = $grn->id;
							$itemLedger->in_out = 'In';
							$itemLedger->processed_on = $grn->date_created;
							$this->Grns->ItemLedgers->save($itemLedger);
						} 
						
					} 
					
					$this->Flash->success(__('The grn has been saved.'));

					return $this->redirect(['action' => 'index']);
				} else {// pr($grn); exit;
					$this->Flash->error(__('The grn could not be saved. Please, try again.'));
				}
			}
		$items = $this->Grns->Items->find('list');
		$companies = $this->Grns->Companies->find('all');
        $purchaseOrders = $this->Grns->PurchaseOrders->find('all');
		
        
        $this->set(compact('grn', 'purchaseOrders', 'companies','customers','chkdate'));
        $this->set('_serialize', ['grn']);
    }

	
	public function AddNew()
    {
		$this->viewBuilder()->layout('index_layout');
		$purchase_order_id=@(int)$this->request->query('purchase-order');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Grns->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->Grns->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->Grns->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		$purchase_order=array();
		
		$totalQty=[];
		$actuleQty=[];
		$PurchaseOrdersDetail = $this->Grns->PurchaseOrders->get($purchase_order_id, [
	'contain' => ['Grns'=>['GrnRows'],'PurchaseOrderRows']]);
	    if(!empty($PurchaseOrdersDetail->grns))
		{
			foreach($PurchaseOrdersDetail->grns as $grn)
			{
				if(!empty($grn->grn_rows))
				{
					foreach($grn->grn_rows as $grn_row)
					{
						@$totalQty[@$grn_row->purchase_order_row_id] +=$grn_row->quantity;
					}
				}
			}
		}
		//pr($totalQty);exit;
		foreach($PurchaseOrdersDetail->purchase_order_rows as $purchase_order_row)
		{
			@$actuleQty[@$purchase_order_row->id]=@$purchase_order_row->quantity-$totalQty[@$purchase_order_row->id];
		}
		
		if(!empty($purchase_order_id))
		{
			$purchase_order = $this->Grns->PurchaseOrders->get($purchase_order_id, [
				'contain' => [
						'PurchaseOrderRows.Items' => function ($q) use($st_company_id){
						   return $q
								->contain(['ItemCompanies'=>function($q) use($st_company_id){
									return $q->where(['company_id'=>$st_company_id]);
								}]);
						},'Companies','Vendors'
					]
			]);
 
		} 
			
	
		
		$this->set(compact('purchase_order'));
		

			   $st_year_id = $session->read('st_year_id');

			   $SessionCheckDate = $this->FinancialYears->get($st_year_id);
			   $fromdate1 = date("Y-m-d",strtotime($SessionCheckDate->date_from));   
			   $todate1 = date("Y-m-d",strtotime($SessionCheckDate->date_to)); 
			   $tody1 = date("Y-m-d");

			   $fromdate = strtotime($fromdate1);
			   $todate = strtotime($todate1); 
			   $tody = strtotime($tody1);

			  if($fromdate > $tody || $todate < $tody || $SessionCheckDate['status'] == 'Closed')
			   {
				   $chkdate = 'Not Found';
			   }
			   else
			   {
				  $chkdate = 'Found';
			   }


		$grn = $this->Grns->newEntity();
        if ($this->request->is('post')) { 
		   $grn->vendor_id=$purchase_order->vendor_id;
			$last_grn_no=$this->Grns->find()->select(['grn2'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['grn2' => 'DESC'])->first();
			if($last_grn_no){
				$grn->grn2=(int)$last_grn_no->grn2+1;
			}else{
				$grn->grn2=1;
			}
			
			$serial_numbers=@$this->request->data['serial_numbers']; 
            /*
			if(sizeof($serial_numbers)>0)
			{
				$item_serial_numbers=[];
				foreach($serial_numbers as $item_id=>$data){
					foreach($data as $sr)
					$item_serial_numbers[]=['item_id'=>$item_id,'serial_no'=>$sr,'company_id'=>$st_company_id,'status'=>'In'];
				}
				
				$this->request->data['item_serial_numbers']=$item_serial_numbers;
			}
			*/
			
			
			
			$grn = $this->Grns->patchEntity($grn, $this->request->data);
			$grn->date_created = date("Y-m-d"); 
			//pr($grn->transaction_date); exit;
			$transaction_date=date("Y-m-d",strtotime($grn->transaction_date));
			$grn->purchase_order_id=$purchase_order_id;
			$grn->company_id=$st_company_id;
			$grn->financial_year_id=$st_year_id;
			$grn->created_by=$this->viewVars['s_employee_id'];
			
			if ($this->Grns->save($grn)) {
				foreach($grn->grn_rows as $key => $grn_row)
				{
					if(isset($grn_row->serial_numbers))
					{
						foreach($grn_row->serial_numbers as $data)
						{ 
							$query = $this->Grns->SerialNumbers->query();
							$query->insert(['name', 'item_id', 'status', 'grn_id','grn_row_id','company_id','transaction_date'])
							->values([
							'name' => $data,
							'item_id' => $grn_row->item_id,
							'status' => 'In',
							'grn_id' => $grn->id,
							'grn_row_id' => $grn_row->id,
							'company_id'=>$st_company_id,
							'transaction_date'=>$transaction_date
							]);
							$query->execute();										
						}
					}
					
					$itemLedger = $this->Grns->ItemLedgers->newEntity();
					$itemLedger->item_id = $grn_row->item_id;
					$itemLedger->quantity = $grn_row->quantity;
					$itemLedger->company_id = $grn->company_id;
					$itemLedger->source_model = 'Grns';
					$itemLedger->source_id = $grn->id;
					$itemLedger->in_out = 'In';
					$itemLedger->processed_on = $transaction_date;
					$itemLedger->source_row_id = $grn_row->id;
					$this->Grns->ItemLedgers->save($itemLedger);
				}		
					
					/* if(!empty($purchase_order_id)){

						$grn->check=array_filter($grn->check);
						$i=0; 
						
						
						
						/* foreach($grn->check as $purchase_order_row_id)
						{
							$qty=$grn->grn_rows[$i]['quantity'];
							
							$item_id=$grn->grn_rows[$i]['item_id'];
							/* $PurchaseOrderRows = $this->Grns->PurchaseOrderRows->get($purchase_order_row_id);
							$PurchaseOrderRows->processed_quantity=$PurchaseOrderRows->processed_quantity+$qty;
							$this->Grns->PurchaseOrderRows->save($PurchaseOrderRows); */
							//$i++;
							
							//Insert in Item Ledger//
							/* $itemLedger = $this->Grns->ItemLedgers->newEntity();
							$itemLedger->item_id = $item_id;
							$itemLedger->quantity = $qty;
							$itemLedger->company_id = $grn->company_id;
							$itemLedger->source_model = 'Grns';
							$itemLedger->source_id = $grn->id;
							$itemLedger->in_out = 'In';
							$itemLedger->processed_on = $transaction_date;
							$this->Grns->ItemLedgers->save($itemLedger); */
						/*}  */
					//} 
					
					$this->Flash->success(__('The grn has been saved.'));

					return $this->redirect(['action' => 'index']);
				} else { //pr($grn); exit;
					$this->Flash->error(__('The grn could not be saved. Please, try again.'));
				}
			}
		
		//pr($actuleQty);exit;
		$items = $this->Grns->Items->find('list');
		$companies = $this->Grns->Companies->find('all');
        $purchaseOrders = $this->Grns->PurchaseOrders->find('all');
		
		//pr($purchase_order->toArray());
		//exit;
		
        $this->set(compact('grn', 'purchaseOrders', 'companies','customers','chkdate','financial_year','financial_month_first','financial_month_last','actuleQty'));
        $this->set('_serialize', ['grn']);
    }
    /**
     * Edit method
     *
     * @param string|null $id Grn id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */

	////
	
	
	public function EditNew($id = null)
    {
	
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Grns->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->Grns->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->Grns->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		$this->viewBuilder()->layout('index_layout');
		$grn = $this->Grns->get($id, [
				'contain' => ['GrnRows'=>['SerialNumbers']
					]
		]);
			   $st_year_id = $session->read('st_year_id');
			   $SessionCheckDate = $this->FinancialYears->get($st_year_id);
			   $fromdate1 = date("Y-m-d",strtotime($SessionCheckDate->date_from));   
			   $todate1 = date("Y-m-d",strtotime($SessionCheckDate->date_to)); 
			   $tody1 = date("Y-m-d");

			   $fromdate = strtotime($fromdate1);
			   $todate = strtotime($todate1); 
			   $tody = strtotime($tody1);

			  if($fromdate > $tody || $todate < $tody || $SessionCheckDate['status'] == 'Closed')
			   {
				   $chkdate = 'Not Found';
			   }
			   else
			   {
				  $chkdate = 'Found';
			   }			
	
		$parentSerialNo[] =[];
		if ($this->request->is(['patch', 'post', 'put'])) 
		{

			
            $grn = $this->Grns->patchEntity($grn, $this->request->data);
			$grn->edited_on = date("Y-m-d"); 
			$grn->edited_by=$this->viewVars['s_employee_id'];
			$transaction_date=$grn->transaction_date;
			unset($grn->count_serial_no);
			unset($grn->q);
			//pr($grn); exit;
				if ($this->Grns->save($grn)) 
				{
					$this->Grns->ItemLedgers->deleteAll(['source_id' => $grn->id, 'source_model' => 'Grns','company_id' =>$st_company_id ]);
						//$grn->check=array_filter($grn->check);
						$i=0; 
						
						
					foreach($grn->grn_rows as $grn_row)
					{ 
                        
						if(!empty($grn_row->serial_number))
						{
							foreach($grn_row->serial_number as $serial_number)
							{ 
						       $query = $this->Grns->SerialNumbers->query();
									$query->insert(['name', 'item_id', 'status', 'grn_id','grn_row_id','company_id','transaction_date'])
									->values([
									'name' => $serial_number,
									'item_id' => $grn_row->item_id,
									'status' => 'In',
									'grn_id' => $grn->id,
									'grn_row_id' => $grn_row->id,
									'company_id'=>$st_company_id,
									'transaction_date'=>$grn->transaction_date
									]);
									$query->execute();
							}
						}
						
							$query = $this->Grns->SerialNumbers->query();
							$query->update()
								->set(['transaction_date' => $grn->transaction_date])
								->where(['grn_id' => $grn->id,'company_id'=>$st_company_id])
								->execute();
						
						
						//Insert in Item Ledger//
							$itemLedger = $this->Grns->ItemLedgers->newEntity();
							$itemLedger->item_id = $grn_row->item_id;
							$itemLedger->quantity = $grn_row->quantity;
							$itemLedger->company_id = $grn->company_id;
							$itemLedger->source_model = 'Grns';
							$itemLedger->source_id = $grn->id;
							$itemLedger->in_out = 'In';
							$itemLedger->processed_on = $grn->transaction_date;
							$itemLedger->source_row_id = $grn_row->id;
							$this->Grns->ItemLedgers->save($itemLedger);
					}
					
					$this->Flash->success(__('The grn has been saved.'));
					return $this->redirect(['action' => 'index']);
				} else {
					$this->Flash->error(__('The grn could not be saved. Please, try again.'));
				}
		}
		
		foreach($grn->grn_rows as $grn_row)
		{
			$serialNoDetail = $this->Grns->SerialNumbers->find()
									 ->where(['grn_id'=>$grn->id,'grn_row_id'=>$grn_row->id,'company_id'=>$st_company_id]); 
			if($serialNoDetail->count()>0)
			{ 
				foreach($serialNoDetail as $svalue)
				{
					$serialNoparentIdExist = $this->Grns->SerialNumbers->find()
									 ->where(['parent_id'=>$svalue->id,'company_id'=>$st_company_id]);
					if($serialNoparentIdExist->count()>0)
					{
						$parentSerialNo[$svalue->id] = $svalue->id;
					}
				}
			}
		}
		//pr($serialNoDetail->toArray());exit;
		
		$grnDetail = $this->Grns->get($id, [
			'contain' => [
					'PurchaseOrders'=>['PurchaseOrderRows','Grns'=>['GrnRows'=>['InvoiceBookingRows']]],'GrnRows'=>['PurchaseOrderRows']
				]
		]);
		//pr($grnDetail);exit;
		$minQty=[];$totalQty=[];
		if(!empty($grnDetail->purchase_order->grns))
		{
			foreach($grnDetail->purchase_order->grns as $grn)
			{
				if(!empty($grn->grn_rows))
				{
					foreach($grn->grn_rows as $grn_row)
					{
						//@$totalQty[@$grn_row->purchase_order_row_id] +=$grn_row->quantity;
						if(!empty($grn_row->invoice_booking_rows))
						{
							foreach($grn_row->invoice_booking_rows as $invoice_booking_row)
							{
								@$minQty[@$grn_row->purchase_order_row_id] +=$invoice_booking_row->quantity;
							}
						}
					}
				}
			}
		}
		
		$Qty=[];$actuleQty=[];
				$POItemQty=[];$maxQty=[];
				if(!empty($grnDetail->purchase_order->grns))
				{
					foreach($grnDetail->purchase_order->grns as $grn)
					{
						if(!empty($grn->grn_rows))
						{	
							foreach($grn->grn_rows as $grn_row)
							{
								@$Qty[$grn_row->purchase_order_row_id] +=@$grn_row->quantity;
							}
						}
					}
				}
				
				if(!empty($grnDetail->purchase_order->purchase_order_rows))
				{
					foreach($grnDetail->purchase_order->purchase_order_rows as $purchase_order_row)
					{
						$POItemQty[@$purchase_order_row->id] =$purchase_order_row->quantity;
					}
				}
				
				foreach($grnDetail->grn_rows as $grn_row)
				{ 
					$maxQty[@$grn_row->purchase_order_row_id] =$POItemQty[@$grn_row->purchase_order_row_id]-@$Qty[$grn_row->purchase_order_row_id]+$grn_row->quantity;
					
				}
				if(!empty($grnDetail->purchase_order->purchase_order_rows))
				{
					foreach($grnDetail->purchase_order->purchase_order_rows as $purchase_order_row)
					{
						if(empty($maxQty[@$purchase_order_row->id]))
						{
							$maxQty[@$purchase_order_row->id] =$POItemQty[@$purchase_order_row->id]-@$Qty[$purchase_order_row->id];
							$actuleQty[@$purchase_order_row->id] =$POItemQty[@$purchase_order_row->id]-@$Qty[$purchase_order_row->id];
						}
					}
				}
				
				
				foreach($grnDetail->grn_rows as $grn_row)
				{
					@$actuleQty[@$grn_row->purchase_order_row_id] =$maxQty[@$grn_row->purchase_order_row_id]+$grn_row->quantity;
				}
				
			/* pr($Qty);
			pr($POItemQty);
			pr($maxQty);
			pr($actuleQty);
			
			exit; */
		$grn = $this->Grns->get($id, [
				'contain' => [
						'Companies','SerialNumbers','Vendors','PurchaseOrders'=>['PurchaseOrderRows'=>['GrnRows'=>['SerialNumbers'],'Items' => ['ItemCompanies' =>function($q) use($st_company_id){
									return $q->where(['company_id'=>$st_company_id]);
								}]],'Grns'=>['GrnRows'=>['SerialNumbers']]],'GrnRows'=>['SerialNumbers','PurchaseOrderRows','Items' => ['ItemCompanies' =>function($q) use($st_company_id){
									return $q->where(['company_id'=>$st_company_id]);
								}]]
					]
			]);

        $purchaseOrders = $this->Grns->PurchaseOrders->find('list');
        $companies = $this->Grns->Companies->find('list');
        $this->set(compact('grn','minQty','parentSerialNo', 'purchaseOrders', 'companies','chkdate','actuleQty','financial_year','financial_month_first','financial_month_last','maxQty'));
        $this->set('_serialize', ['grn']);
    }
    /**
     * Delete method
     *
     * @param string|null $id Grn id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $grn = $this->Grns->get($id);
        if ($this->Grns->delete($grn)) {
            $this->Flash->success(__('The grn has been deleted.'));
        } else {
            $this->Flash->error(__('The grn could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	public function DeleteSerialNumbers($id=null,$item_id=null,$grn_id=null,$grn_row_id=null)
	{
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$ItemLedger=$this->Grns->ItemLedgers->find()->where(['item_id'=>$item_id,'source_model'=>'Grns','source_row_id'=>$grn_row_id])->first();
		
		//pr($ItemLedger);exit;
		
		$GrnRow=$this->Grns->GrnRows->find()->where(['item_id'=>$item_id,'grn_id'=>$grn_id])->first();
		
		$GRN=$this->Grns->get($grn_id);
		$PO=$this->Grns->PurchaseOrders->get($GRN->purchase_order_id);
		$PurchaseOrderRow=$this->Grns->PurchaseOrderRows->find()->where(['item_id'=>$item_id,'purchase_order_id'=>$GRN->purchase_order_id])->first();
		

		$SerialNumber = $this->Grns->SerialNumbers->get($id);

		
		if($SerialNumber->status=='In'){  
			$query = $this->Grns->ItemLedgers->query();
			$query->update()
				->set(['quantity' => $ItemLedger->quantity-1])
				->where(['item_id' => $item_id,'company_id'=>$st_company_id,'source_model'=>'Grns','source_row_id'=>$grn_row_id])
				->execute();
			$query1 = $this->Grns->GrnRows->query();
			$query1->update()
				->set(['quantity' => $GrnRow->quantity-1])
				->where(['item_id'=>$item_id,'grn_id'=>$grn_id,'id'=>$grn_row_id])
				->execute();

						

			$this->Grns->SerialNumbers->delete($SerialNumber);
			$this->Flash->success(__('The Serial Number has been deleted.'));
		}
		
		return $this->redirect(['action' => 'EditNew/'.$grn_id]);
	}
	
	public function grnData(){
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$grns=$this->Grns->find()->where(['company_id'=>$st_company_id])->contain(['GrnRows']);
		$grns->count();
		?>
		<table border="1">
			<tr>
				<th>ID</th>
				<th>No</th>
				<th>Transaction Date</th>
				<th>itemledgers</th>
				<th>grn rows count</th>
				<th>itemLedger count</th>
			</tr>
			<?php foreach($grns as $grn){
				$itemledgers=$this->Grns->ItemLedgers->find()->where(['source_model LIKE'=>'%grns%','source_id'=>$grn->id]);
			?>
			<tr>
				<td><?php echo $grn->id; ?></td>
				<td><?= h(str_pad($grn->grn2, 3, '0', STR_PAD_LEFT)) ?></td>
				<td><?php echo strtotime($grn->transaction_date); ?></td>
				<td>
					<?php 
					$q=0;
					foreach($itemledgers as $itemledger){ 
						$q+=strtotime($itemledger->processed_on);
					}
					echo $q/sizeof($itemledgers->toArray());
					?>
				</td>
				<td><?php echo sizeof($grn->grn_rows); ?></td>
				<td><?php echo sizeof($itemledgers->toArray()); ?></td>
			</tr>
			<?php } ?>
		</table>
		<?php
	}
}
