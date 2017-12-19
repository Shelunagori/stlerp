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
			$where['Ivs.voucher_no LIKE']=$iv_no;
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
		
		$ivs = $this->paginate($this->Ivs->find()->contain(['Invoices'])->where($where)->where(['Ivs.company_id'=>$st_company_id])->order(['Ivs.id' => 'DESC']));
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
	public function excelExport(){
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
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
			$where['Ivs.voucher_no LIKE']=$iv_no;
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
        
		$ivs = $this->Ivs->find()->contain(['Invoices'=>['Customers'],'IvRows','Companies'])->where($where)->where(['Ivs.company_id'=>$st_company_id])->order(['Ivs.id' => 'DESC']);
      
        $this->set(compact('ivs','url','From','To'));
	} 
	 
	 
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$iv = $this->Ivs->get($id, [
            'contain' => ['Invoices'=>['Customers'],'IvRows'=>['Items'=>['SerialNumbers','ItemCompanies'],'IvRowItems'=>['Items'=>['SerialNumbers','ItemCompanies' =>function($q) use($st_company_id){
									return $q->where(['company_id'=>$st_company_id]);
								}]]],'Creator','Companies']
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
		$s_employee_id=$this->viewVars['s_employee_id'];
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		
		$financial_year = $this->Ivs->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->Ivs->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->Ivs->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();

		/////
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
		/////
		
		$Invoice=$this->Ivs->Invoices->get($invoice_id, [
			'contain' => ['InvoiceRows'=>['Items'=>function($p) use($st_company_id){
				return $p->where(['Items.source IN'=>['Assembled','Manufactured','Purchessed/Manufactured']])->contain(['ItemCompanies']);
			}]],
			'conditions' => ['Invoices.company_id'=>$st_company_id]
		]);
		
		$item_display=[];
		$jobcardrows=[];
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
			
			$jobcardrows[$invoice_row->id]=$this->Ivs->JobCards->JobCardRows->find()
							->where(['JobCardRows.sales_order_row_id'=>$so_row_id]);
							
		}
		
        $iv = $this->Ivs->newEntity();
        if ($this->request->is('post')) {
			
            $iv = $this->Ivs->patchEntity($iv, $this->request->data, [
								'associated' => ['IvRows', 'IvRows.IvRowItems']
							]);
			
			$iv->invoice_id=$invoice_id;
			$iv->company_id=$st_company_id	;
			$transaction_date=$iv->transaction_date	;
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
					if(!empty($iv_row->serial_numbers)){
						
						$serial_numbers_iv_row = array_filter(@$iv_row->serial_numbers);
						
						 if(!empty($serial_numbers_iv_row)){
							 
							 foreach($serial_numbers_iv_row as $sr_nos){
								 
							$query = $this->Ivs->IvRows->SerialNumbers->query();
										$query->insert(['name', 'item_id', 'status', 'iv_row_id','company_id','transaction_date'])
										->values([
										'name' => $sr_nos,
										'item_id' => $iv_row->item_id,
										'status' => 'In',
										'iv_row_id' => $iv_row->id,
										'company_id'=>$st_company_id,
										'transaction_date'=>$transaction_date
										]);
									$query->execute(); 
							} 
						} 
					}
					$unit_rate_In=0;$unit_rate=0;
					
				 	foreach($iv_row->iv_row_items as $iv_row_item){
						//// For Out
						 

						// $UnitRateSerialItem1=0;
						@$serial_numbers_iv_row_item = @$iv_row_item->serial_numbers;
						if(!empty($serial_numbers_iv_row_item)){
						$UnitRateSerialItem1=0;
						 foreach($serial_numbers_iv_row_item as $sr_nos_out){ 
							$UnitRateSerialItem = $this->weightedAvgCostForSerialWise($sr_nos_out); 
							 $serial_data=$this->Ivs->IvRows->SerialNumbers->get($sr_nos_out);
							 
							 $query = $this->Ivs->IvRows->SerialNumbers->query();
										$query->insert(['name', 'item_id', 'status', 'iv_row_items','company_id','parent_id','transaction_date'])
										->values([
										'name' => $serial_data->name,
										'item_id' => $iv_row_item->item_id,
										'status' => 'Out',
										'iv_row_items' => $iv_row_item->id,
										'company_id'=>$st_company_id,
										'parent_id'=>$sr_nos_out,
										'transaction_date'=>$transaction_date
										]);
									$query->execute(); 
									
								$UnitRateSerialItem1+=$UnitRateSerialItem;
									$unit_rate=$UnitRateSerialItem1;
							}
							$unit_rate = round($unit_rate,2)/@$iv_row_item->quantity;
						}else{
							$unit_rate = $this->weightedAvgCostIvs($iv_row_item->item_id); 
						}
						
						$unit_rate = round($unit_rate,2);
						 $out_rate=$iv_row_item->quantity*$unit_rate;
						 $unit_rate_In+=$out_rate; 
						
						$itemledgers = $this->Ivs->ItemLedgers->newEntity();
						$itemledgers->item_id = $iv_row_item->item_id;
						$itemledgers->quantity=$iv_row_item->quantity;
						$itemledgers->source_model='Inventory Vouchers';
						$itemledgers->source_id=$iv->id;
						$itemledgers->in_out='Out';
						$itemledgers->rate=$unit_rate;
						$itemledgers->processed_on=$iv->transaction_date;
						$itemledgers->company_id=$st_company_id;
						$itemledgers->iv_row_item_id=$iv_row_item->id;
						$this->Ivs->ItemLedgers->save($itemledgers);				
					} 
					
					$unit_rate_item_in = $unit_rate_In/$iv_row->quantity; 
					
					$itemledgersIN = $this->Ivs->ItemLedgers->newEntity();
										
						$itemledgersIN->item_id= $iv_row->item_id;
						$itemledgersIN->quantity= $iv_row->quantity;
						$itemledgersIN->rate= round($unit_rate_item_in,3);
						$itemledgersIN->source_model= 'Inventory Vouchers';
						$itemledgersIN->source_id=$iv->id;
						$itemledgersIN->in_out='In';
						$itemledgersIN->processed_on=$iv->transaction_date;
						$itemledgersIN->company_id=$st_company_id;
						$itemledgersIN->iv_row_id=$iv_row->id;
						$this->Ivs->ItemLedgers->save($itemledgersIN);				
					
				}
                $this->Flash->success(__('The iv has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The iv could not be saved. Please, try again.'));
            }
        }
		
		$Items=$this->Ivs->IvRows->Items->find()->order(['Items.name' => 'ASC'])->matching(
						'ItemCompanies', function ($q) use($st_company_id) {
							return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
						}
					);	
		$ItemsOptions=[];
		foreach($Items as $item){ 
					$ItemsOptions[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
		}
		
        $this->set(compact('iv', 'Invoice', 'ItemsOptions','item_display','invoice_id','job_card_status','jobcardrows','chkdate','financial_month_first','financial_month_last'));
        $this->set('_serialize', ['iv']);
    }

	public function weightedAvgCostForSerialWise($sr_no_out_id=null){ 
	
	
	$this->viewBuilder()->layout('');
			$session = $this->request->session();
			$st_company_id = $session->read('st_company_id');
			
			$ItemData=$this->Ivs->ItemLedgers->SerialNumbers->get($sr_no_out_id);
			$Items = $this->Ivs->ItemLedgers->Items->get($ItemData->item_id, [
				'contain' => ['ItemCompanies'=>function($q) use($st_company_id){
					return $q->where(['company_id'=>$st_company_id]);
				}]
			]);
			
			$to_date = date('Y-m-d');
			$unit_rate=0;
	
	
	
		if($Items->item_companies[0]->serial_number_enable == '1'){
				$ItemSerialNumber=$this->Ivs->ItemLedgers->SerialNumbers->get($sr_no_out_id);
				
				$itemSerialRate=0; $itemSerialQuantity=0; $i=1;
							
				if(@$ItemSerialNumber->grn_id > 0){ 
							$outExist = $this->Ivs->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id,'SerialNumbers.transaction_date <=' => $to_date]);
					if($outExist == 0){
						$ItemLedgerData =$this->Ivs->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->grn_id,'source_model'=>"Grns",'source_row_id'=>$ItemSerialNumber->grn_row_id])->first();
					//	pr($ItemLedgerData); 
						if($ItemLedgerData){
							@$itemSerialQuantity=$itemSerialQuantity+1;
							@$itemSerialRate+=@$ItemLedgerData['rate'];
						}
					}
				}
				if(@$ItemSerialNumber->sale_return_id > 0){ 
				$outExist = $this->Ivs->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id,'SerialNumbers.transaction_date <=' => $to_date]);
					if($outExist == 0){
						$ItemLedgerData =$this->Ivs->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->sale_return_id,'source_model'=>"Sale Return",'source_row_id'=>$ItemSerialNumber->sales_return_row_id])->where($where1)->first();
					//	pr($ItemLedgerData); 
						if($ItemLedgerData){
							@$itemSerialQuantity=$itemSerialQuantity+1;
							@$itemSerialRate+=@$ItemLedgerData['rate'];
						}
					}
				}
				
				if(@$ItemSerialNumber->itv_id > 0){
				$outExist = $this->Ivs->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id,'SerialNumbers.transaction_date <=' => $to_date]); 
					if($outExist == 0){  
						$ItemLedgerData =$this->Ivs->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->itv_id,'source_model'=>"Inventory Transfer Voucher",'source_row_id'=>$ItemSerialNumber->itv_row_id])->first();
						//pr($ItemLedgerData); 
						if($ItemLedgerData){
							@$itemSerialQuantity=$itemSerialQuantity+1;
							@$itemSerialRate+=@$ItemLedgerData['rate'];
						}
					}
				}
				
				if(@$ItemSerialNumber->iv_row_id > 0){
					$outExist = $this->Ivs->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id]); 
						if($outExist == 0){  
							$ItemLedgerData =$this->Ivs->ItemLedgers->find()->where(['source_model'=>"Inventory Vouchers",'iv_row_id'=>$ItemSerialNumber->iv_row_id])->first();
							//pr($ItemLedgerData); 
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
				if(@$ItemSerialNumber->is_opening_balance == "Yes"){  
				 
						$ItemLedgerData =$this->Ivs->ItemLedgers->find()->where(['ItemLedgers.source_model'=>"Items",'ItemLedgers.company_id'=>$st_company_id,'ItemLedgers.item_id' => $ItemSerialNumber->item_id])->first();
						//pr($ItemLedgerData); 
						if($ItemLedgerData){
						@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
						@$itemSerialRate+=@$ItemLedgerData['rate'];
						}
					
				}
				
			//pr($itemSerialRate); exit;
			
			
				return $itemSerialRate; 
		}	
		
	}
	public function weightedAvgCostIvs($item_id=null){ 
			$this->viewBuilder()->layout('');
			$session = $this->request->session();
			$st_company_id = $session->read('st_company_id');
			
			$Items = $this->Ivs->ItemLedgers->Items->get($item_id, [
				'contain' => ['ItemCompanies'=>function($q) use($st_company_id){
					return $q->where(['company_id'=>$st_company_id]);
				}]
			]);
			$to_date = date('Y-m-d');
			$unit_rate=0;
			
			if($Items->item_companies[0]->serial_number_enable == '0'){   
				$stock=[];  $sumValue=0;
					$StockLedgers=$this->Ivs->ItemLedgers->find()->where(['ItemLedgers.item_id'=>$Items->id,'ItemLedgers.company_id'=>$st_company_id])->order(['ItemLedgers.processed_on'=>'ASC']);
					
					foreach($StockLedgers as $StockLedger){  
						if($StockLedger->in_out=='In'){ 
							if(($StockLedger->source_model=='Grns' and $StockLedger->rate_updated=='Yes') or ($StockLedger->source_model!='Grns')){
								for($inc=0.01;$inc<$StockLedger->quantity;$inc+=0.01){
									$stock[]=$StockLedger->rate;
								}
							}
						}
					}
				
					foreach($StockLedgers as $StockLedger){
						if($StockLedger->in_out=='Out'){ 
							if(sizeof(@$stock) > 0){// pr($stock); 
								//$stock[$Item->id] = array_slice($stock[$Item->id], $StockLedger->quantity*100); 
								$stock= array_slice($stock, $StockLedger->quantity*100); 	
							}
						}
					}
					$total_stock=0;
					$total_amt=0;
					if(sizeof(@$stock) > 0){ 
						foreach($stock as $data){
							$total_amt+=$data/100;
							++$total_stock;
						}
					}
				$total_stock=$total_stock/100;
				
				if($total_amt > 0 && $total_stock > 0){
					 $unit_rate = $total_amt/$total_stock; 
				}
				
				
			}
				
				// pr($unit_rate); exit;
			return $unit_rate; 
		//exit;	
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
		$s_employee_id=$this->viewVars['s_employee_id'];
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		
		$financial_year = $this->Ivs->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->Ivs->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->Ivs->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();

		/////
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
        $iv = $this->Ivs->get($id, [
            'contain' => ['IvRows'=>['Items'=>['ItemCompanies'],'IvRowItems'=>['Items'=>['ItemCompanies']]],'Invoices'=>['InvoiceRows']]
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {

		$iv = $this->Ivs->patchEntity($iv, $this->request->data,[
			'associated' => ['IvRows', 'IvRows.IvRowItems']
			]);
			$iv->created_by=$s_employee_id;
			$iv->st_company_id=$st_company_id;
			$transaction_date=$iv->transaction_date	;
            if ($this->Ivs->save($iv)) {  
			
			$this->Ivs->ItemLedgers->deleteAll(['ItemLedgers.source_id' => $id,'ItemLedgers.company_id'=>$st_company_id,'ItemLedgers.source_model'=>'Inventory Vouchers','ItemLedgers.in_out'=>'Out']);
				
				foreach($iv->iv_rows as $iv_row){ 
					$query = $this->Ivs->IvRows->SerialNumbers->query();
					$query->update()
						->set(['transaction_date' => $transaction_date])
						->where(['iv_row_id' => $iv_row->id,'company_id'=>$st_company_id,'status'=>'In'])
						->execute();
						
					$unit_rate_In=0;$unit_rate=0;	
				 	foreach($iv_row->iv_row_items as $iv_row_item){
					if(!empty($iv_row_item['id'])){
						$this->Ivs->IvRows->SerialNumbers->deleteAll(['SerialNumbers.iv_row_items' => $iv_row_item['id'],'SerialNumbers.company_id'=>$st_company_id,'status'=>'Out']);
					}
					
					
						$serial_numbers_iv_row_item = @$iv_row_item['serial_numbers'];
						if(!empty($serial_numbers_iv_row_item)){
						$UnitRateSerialItem1=0;
						 foreach($serial_numbers_iv_row_item as $sr_nos_out){ 
							$UnitRateSerialItem= $this->weightedAvgCostForSerialWise($sr_nos_out); 
							 $serial_data=$this->Ivs->IvRows->SerialNumbers->get($sr_nos_out);
							 
							 $query = $this->Ivs->IvRows->SerialNumbers->query();
											$query->insert(['name', 'item_id', 'status', 'iv_row_items','company_id','parent_id','transaction_date'])
											->values([
											'name' => $serial_data->name,
											'item_id' => $iv_row_item['item_id'],
											'status' => 'Out',
											'iv_row_items' => $iv_row_item['id'],
											'company_id'=>$st_company_id,
											'parent_id'=>$sr_nos_out,
											'transaction_date'=>$transaction_date
											]);
										$query->execute();  
									$UnitRateSerialItem1+=$UnitRateSerialItem;
									$unit_rate=$UnitRateSerialItem1;
							}
							$unit_rate = round($unit_rate,2)/@$iv_row_item->quantity;
						}else{
							$unit_rate = $this->weightedAvgCostIvs($iv_row_item->item_id); 
						}
						
						$unit_rate = round($unit_rate,2);
						 $out_rate=$iv_row_item['quantity']*$unit_rate;
						 $unit_rate_In+=$out_rate;
						
						//item_ledger entry
						$itemledgers = $this->Ivs->ItemLedgers->newEntity();
						$itemledgers->item_id = $iv_row_item['item_id'];
						$itemledgers->quantity=$iv_row_item['quantity'];
						$itemledgers->source_model='Inventory Vouchers';
						$itemledgers->source_id=$iv->id;
						$itemledgers->iv_row_item_id=$iv_row_item['id'];
						$itemledgers->in_out='Out';
						$itemledgers->rate=$unit_rate;
						$itemledgers->processed_on=$iv->transaction_date;
						$itemledgers->company_id=$st_company_id;
						//$itemledgers->iv_row_item_id=$iv_row_item['id'];
						$this->Ivs->ItemLedgers->save($itemledgers);	
					} 

					$unit_rate_item_in = $unit_rate_In/$iv_row->quantity;					
					$query = $this->Ivs->ItemLedgers->query();
					$query->update()
						->set(['rate' => $unit_rate_item_in])
						->where(['iv_row_id' => $iv_row->id,'company_id'=>$st_company_id,'in_out'=>'In'])
						->execute();
					
				}
				
				
                $this->Flash->success(__('The iv has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The iv could not be saved. Please, try again.'));
            }
        }
		
		$Itemss = $this->Ivs->IvRows->Items->find()->order(['Items.name' => 'ASC'])->matching(
						'ItemCompanies', function ($q) use($st_company_id) {
							return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0])->orWhere(['ItemCompanies.company_id'=>$st_company_id,'ItemCompanies.freeze' => 1]);
						}
					);		
					
					
			
				
			$ItemsOptionss=[];
			foreach($Itemss as $item){ 
						$ItemsOptionss[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
			}
		
		
		
		$Items=$this->Ivs->IvRows->Items->find()->order(['Items.name' => 'ASC'])->matching(
						'ItemCompanies', function ($q) use($st_company_id) {
							return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
						}
					);	
		$ItemsOptions=[];
		foreach($Items as $item){ 
					$ItemsOptions[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
		}			
        $this->set(compact('iv', 'invoices', 'ItemsOptions','ItemsOptionss','chkdate','financial_month_first','financial_month_last'));
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
