<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\View\Helper\NumberHelper;
use Cake\View\Helper\HtmlHelper;
use Cake\Utility\Text;
use Cake\Mailer\Email;
use Cake\View\Helper\TextHelper;
/**
 * Invoices Controller
 *
 * @property \App\Model\Table\InvoicesTable $Invoices
 */
class InvoicesController extends AppController
{

    public function index($status=null)
    {
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		
		$this->viewBuilder()->layout('index_layout');
		$inventory_voucher=$this->request->query('inventory_voucher');
		$sales_return=$this->request->query('sales_return');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$where=[];
		$invoice_no=$this->request->query('invoice_no');
		$file=$this->request->query('file');
		$customer=$this->request->query('customer');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$total_From=$this->request->query('total_From');
		$page=$this->request->query('page');
		$items=$this->request->query('items');
		$SessionCheckDate = $this->FinancialYears->get($st_year_id);
		$fromdate1 = date("Y-m-d",strtotime($SessionCheckDate->date_from));   
		$todate1 = date("Y-m-d",strtotime($SessionCheckDate->date_to)); 
		
		$this->set(compact('customer','total_From','From','To','page','invoice_no','file','items'));
		
		if(!empty($invoice_no)){
			$where['Invoices.in2 LIKE']=$invoice_no;
		}
		if(!empty($file)){
			$where['Invoices.in3 LIKE']='%'.$file.'%';
		}
		if(!empty($customer)){
			$where['Customers.customer_name LIKE']='%'.$customer.'%';
		}
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Invoices.date_created >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Invoices.date_created <=']=$To;
		}
		if(!empty($total_From)){
			$where['Invoices.total_after_pnf']=$total_From;
		}
		$styear=[1,3,2];
			if(in_array($st_year_id,$styear)){ 
				$wheree['Invoices.financial_year_id'] = $st_year_id;
			}else{
				$wheree=[];
			}
	
        $this->paginate = [
            'contain' => ['Customers', 'Companies']
        ];
		
		/* if($inventory_voucher=='true'){
			$where['Invoices.inventory_voucher_status']='Pending';
			
		}else{
			if($status=='Pending' || $status==''){
				$where['status']='';
			}
			elseif($status=='Cancel'){
				$where['status']='Cancel';
			}	
		}
		 */
		  $current_rows=[];
		if(!empty($items))
		{ 
			$InvoiceRows = $this->Invoices->InvoiceRows->find();
				$invoices = $this->Invoices->find();
				$invoices->select(['id','total_sales'=>$InvoiceRows->func()->sum('InvoiceRows.quantity')])
				->innerJoinWith('InvoiceRows')
				->group(['Invoices.id'])
				->matching('InvoiceRows.Items', function ($q) use($items,$st_company_id) {
											return $q->where(['Items.id' =>$items,'company_id'=>$st_company_id]);
							})
				->contain(['Customers','SalesOrders','InvoiceRows'=>['Items']])
				->autoFields(true)
				->where(['Invoices.company_id'=>$st_company_id])
				->where($where)
				->where($wheree);
		}
		else if($inventory_voucher=='true'){
			$invoices=[];
			$invoice1=$this->Invoices->find()->where($where)->contain(['Customers','SalesOrders','InvoiceRows'=>['Items'=>function ($q) {
				return $q->where(['source !='=>'Purchessed']);
				},'SalesOrderRows'=>function ($q) {
				return $q->where(['SalesOrderRows.source_type !='=>'Purchessed']);
				}
				]])
				->where(['Invoices.company_id'=>$st_company_id])->where($wheree)
				->order(['Invoices.id' => 'DESC']);
				
				foreach($invoice1 as $invoice){
					$AccountGroupsexists = $this->Invoices->Ivs->exists(['Ivs.invoice_id' => $invoice->id]);
					if(!$AccountGroupsexists){ // pr($invoice);
						$invoices[]=$invoice;
					}
				} 
		
		//$last_in_no=$this->Invoices->find()->select(['in2'])->where(['company_id' => $sales_order->company_id,'date_created >='=>$fromdate1,'date_created <='=>$todate1])->order(['in2' => 'DESC'])->first();		
				
			//pr($invoices);exit;
		}else if($sales_return=='true'){
			$invoices = $this->Invoices->find()->contain(['Customers','SalesOrders','SendEmails','InvoiceRows'=>['Items']])->where($where)->where($wheree)->where(['Invoices.company_id'=>$st_company_id])->order(['Invoices.id' => 'DESC']);
		} else{ 
			$invoices =$this->Invoices->find()->contain(['Customers','SalesOrders','SendEmails','InvoiceRows'=>['Items']])->where($where)->where($wheree)->where(['Invoices.company_id'=>$st_company_id,'Invoices.financial_year_id'=>$st_year_id])->order(['Invoices.in2' => 'DESC']);
		} 
		//pr($invoices->toArray());exit;
		$Items = $this->Invoices->InvoiceRows->Items->find('list')->order(['Items.name' => 'ASC']);
		$this->set(compact('invoices','status','inventory_voucher','sales_return','InvoiceRows','Items','url','current_rows'));
		
        $this->set('_serialize', ['invoices']);
		$this->set(compact('url'));
    }
	
	
	public function SalesReturnIndex($status=null){
		$this->viewBuilder()->layout('index_layout');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$sales_return=$this->request->query('sales_return');
		$status=$this->request->query('status');
		@$invoice_no=$this->request->query('invoice_no');	
		$where=[];
		$status = 0 ;
			if(!empty($invoice_no)){
			$invoice_no=$this->request->query('invoice_no');	
			if(!empty($invoice_no)){
				$where['Invoices.in2 LIKE']=$invoice_no;
			}
			$invoice_detail = $this->Invoices->find()->contain(['InvoiceRows'=> function ($p){
				return $p->where(['inventory_voucher_applicable'=>'Yes']);
			}])->where($where)->where(['Invoices.company_id'=>$st_company_id])->first();
			
			
				$invoices = $this->Invoices->find()->contain(['Customers','SalesOrders','InvoiceRows'=>['Items']])->where($where)->where(['Invoices.company_id'=>$st_company_id,'invoice_type !='=>'GST']);
				$status=1;
			
		}
		//pr($status);exit;
		$this->set(compact('invoices','status','sales_return','InvoiceRows','invoice_no'));
        $this->set('_serialize', ['invoices']);
		$this->set(compact('url'));
	}
	
	public function gstSalesReturn($status=null){
		$this->viewBuilder()->layout('index_layout');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$sales_return=$this->request->query('sales_return');
		$status=$this->request->query('status');
		@$invoice_no=$this->request->query('invoice_no');	
		$where=[];
		$status = 0 ;
		$styear=[1,3,2];
		if(in_array($st_year_id,$styear)){ 
			$wheree['InvoiceBookings.financial_year_id'] = $st_year_id;
		}else{
			$wheree=[];
		}
			if(!empty($invoice_no)){
			$invoice_no=$this->request->query('invoice_no');	
			if(!empty($invoice_no)){
				$where['Invoices.in2 LIKE']=$invoice_no;
			}
			$invoices = $this->Invoices->find()->contain(['Customers','SalesOrders','InvoiceRows'=>['Items']])->where($where)->where($wheree)->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST'])->toArray();
			$status=1;
		}
		$InvoiceExist="No";
		if(!empty($invoices)){
			$SalesReturnexists = $this->Invoices->SaleReturns->exists(['SaleReturns.invoice_id' => $invoices[0]->id]);
			if($SalesReturnexists==1){
				$SalesReturn=$this->Invoices->SaleReturns->find()->where(['SaleReturns.invoice_id' => $invoices[0]->id,'SaleReturns.company_id'=>$st_company_id])->first();
				$SalesReturnId=$SalesReturn->id;
				$InvoiceExist="Yes";
			}
		}
		 //pr($InvoiceExist); exit;
		
		$this->set(compact('invoices','status','sales_return','InvoiceRows','invoice_no','InvoiceExist','SalesReturnId'));
        $this->set('_serialize', ['invoices']);
		$this->set(compact('url'));
	}
	
	public function LedgerEntry()
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$Invoices=$this->Invoices->find()->contain(['InvoiceRows']);
		foreach($Invoices as $invoice){
			//pr($invoice);exit;
			if($invoice->invoice_type=='GST'){
			$c_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$invoice->company_id,'source_model'=>'Customers','source_id'=>$invoice->customer_id])->first();
				//$ledger_grand=$invoice->grand_total;
				//pr($c_LedgerAccount->id);
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $c_LedgerAccount->id;
				$ledger->debit = $invoice->grand_total;
				$ledger->credit = 0;
				$ledger->voucher_id = $invoice->id;
				$ledger->voucher_source = 'Invoice';
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$this->Invoices->Ledgers->save($ledger); 
				
				foreach($invoice->invoice_rows as $invoice_row){ 
					if($invoice_row->cgst_amount > 0){
						$cg_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$invoice->company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice_row->cgst_percentage])->first();
						$ledger = $this->Invoices->Ledgers->newEntity();
						$ledger->ledger_account_id = $cg_LedgerAccount->id;
						$ledger->credit = $invoice_row->cgst_amount;
						$ledger->debit = 0;
						$ledger->voucher_id = $invoice->id;
						$ledger->voucher_source = 'Invoice';
						$ledger->company_id = $invoice->company_id;
						$ledger->transaction_date = $invoice->date_created;
						$this->Invoices->Ledgers->save($ledger); 
					}
					if($invoice_row->sgst_amount > 0){
						$s_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$invoice->company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice_row->sgst_percentage])->first();
						$ledger = $this->Invoices->Ledgers->newEntity();
						$ledger->ledger_account_id = $s_LedgerAccount->id;
						$ledger->credit = $invoice_row->sgst_amount;
						$ledger->debit = 0;
						$ledger->voucher_id = $invoice->id;
						$ledger->voucher_source = 'Invoice';
						$ledger->company_id = $invoice->company_id;
						$ledger->transaction_date = $invoice->date_created;
						$this->Invoices->Ledgers->save($ledger); 
					}
					if($invoice_row->igst_amount > 0){
						$i_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$invoice->company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice_row->igst_percentage])->first();  //pr($i_LedgerAccount);exit;
						$ledger = $this->Invoices->Ledgers->newEntity();
						$ledger->ledger_account_id = $i_LedgerAccount->id;
						$ledger->credit = $invoice_row->igst_amount;
						$ledger->debit = 0;
						$ledger->voucher_id = $invoice->id;
						$ledger->voucher_source = 'Invoice';
						$ledger->company_id = $invoice->company_id;
						$ledger->transaction_date = $invoice->date_created;
						$this->Invoices->Ledgers->save($ledger); 
					}
					//pr($invoice->date_created);exit;
						$itemLedger = $this->Invoices->ItemLedgers->newEntity();
						$itemLedger->item_id = $invoice_row->item_id;
						$itemLedger->quantity = $invoice_row->quantity;
						$itemLedger->source_model = 'Invoices';
						$itemLedger->source_id = $invoice->id;
						$itemLedger->in_out = 'Out';
						$itemLedger->rate = $invoice_row->taxable_value/$invoice_row->quantity;
						$itemLedger->company_id = $invoice->company_id;
						$itemLedger->source_row_id = $invoice_row->id;
						$itemLedger->processed_on =$invoice->date_created;
						$this->Invoices->ItemLedgers->save($itemLedger);
						
						
						
				}
				
				$ledger_fright=@(float)$invoice->fright_amount;
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $invoice->sales_ledger_account;
				$ledger->debit = 0;
				$ledger->credit = $invoice->total+$ledger_fright;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$ledger->voucher_source = 'Invoice';
				$this->Invoices->Ledgers->save($ledger); 
				
				if($invoice->fright_cgst_amount > 0){ 
					$cg_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$invoice->company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice->fright_cgst_percent])->first(); //pr($invoice->fright_cgst_amount);exit;
					$ledger = $this->Invoices->Ledgers->newEntity();
					$ledger->ledger_account_id = $cg_LedgerAccount->id;
					$ledger->credit = $invoice->fright_cgst_amount;
					$ledger->debit = 0;
					$ledger->voucher_id = $invoice->id;
					$ledger->voucher_source = 'Invoice';
					$ledger->company_id = $invoice->company_id;
					$ledger->transaction_date = $invoice->date_created;
					$this->Invoices->Ledgers->save($ledger); 
				}
				if($invoice->fright_sgst_amount > 0){
					$s_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$invoice->company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice->fright_sgst_percent])->first();
					$ledger = $this->Invoices->Ledgers->newEntity();
					$ledger->ledger_account_id = $s_LedgerAccount->id;
					$ledger->credit = $invoice->fright_sgst_amount;
					$ledger->debit = 0;
					$ledger->voucher_id = $invoice->id;
					$ledger->voucher_source = 'Invoice';
					$ledger->company_id = $invoice->company_id;
					$ledger->transaction_date = $invoice->date_created;
					$this->Invoices->Ledgers->save($ledger); 
				}
				if($invoice->fright_igst_amount > 0){
					$i_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$invoice->company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice->fright_igst_percent])->first();
					$ledger = $this->Invoices->Ledgers->newEntity();
					$ledger->ledger_account_id = $i_LedgerAccount->id;
					$ledger->credit = $invoice->fright_igst_amount;
					$ledger->debit = 0;
					$ledger->voucher_id = $invoice->id;
					$ledger->voucher_source = 'Invoice';
					$ledger->company_id = $invoice->company_id;
					$ledger->transaction_date = $invoice->date_created;
					$this->Invoices->Ledgers->save($ledger); 
				}
			}else{
				
				//GET CUSTOMER LEDGER-ACCOUNT-ID
				$c_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$invoice->company_id,'source_model'=>'Customers','source_id'=>$invoice->customer_id])->first();
				//pr($invoice->grand_total); exit;
				$ledger_grand=$invoice->grand_total;
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $c_LedgerAccount->id;
				$ledger->debit = $invoice->grand_total;
				$ledger->credit = 0;
				$ledger->voucher_id = $invoice->id;
				$ledger->voucher_source = 'Invoice';
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				
				if($ledger_grand>0)
				{
					$this->Invoices->Ledgers->save($ledger); 
				}
				
				//Ledger posting for Account Reference
				$ledger_pnf=$invoice->total_after_pnf;
				//$accountReferences=$this->Invoices->AccountReferences->get(1);
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $invoice->sales_ledger_account;
				$ledger->debit = 0;
				$ledger->credit = $invoice->total_after_pnf;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$ledger->voucher_source = 'Invoice';
				
				if($ledger_pnf>0)
				{
					$this->Invoices->Ledgers->save($ledger); 
				}
				
				//Ledger posting for Sale Tax
				
				
				
				$ledger_saletax=$invoice->sale_tax_amount;
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $invoice->st_ledger_account_id;
				$ledger->debit = 0;
				$ledger->credit = $invoice->sale_tax_amount;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$ledger->voucher_source = 'Invoice';
				
				if($ledger_saletax>0)
				{
					$this->Invoices->Ledgers->save($ledger); 
				}
				
				
				//Ledger posting for Fright Amount
				
				$ledger_fright= $invoice->fright_amount;
				//$accountReferences=$this->Invoices->AccountReferences->get(3);
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $invoice->fright_ledger_account;
				$ledger->debit = 0;
				$ledger->credit = $invoice->fright_amount;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$ledger->voucher_source = 'Invoice';
				
				if($ledger_fright>0)
				{
					$this->Invoices->Ledgers->save($ledger); 
				}
				
				
			}
		} 
		echo "Done";
		exit;
	}
	
	public function OldRefBal()
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$Invoices=$this->Invoices->find()->toArray();
		foreach($Invoices as $Invoice){ 
			$old_datas=$this->Invoices->OldReferenceDetails->find()->where(['invoice_id'=>$Invoice->id])->toArray();
			
			if($old_datas){
				foreach($old_datas as $old_data){
					$ReferenceDetail = $this->Invoices->ReferenceDetails->newEntity();
					$ReferenceDetail->company_id=$Invoice->company_id;
					$ReferenceDetail->invoice_id=$old_data->invoice_id;
					$ReferenceDetail->reference_no=$old_data->reference_no;
					$ReferenceDetail->reference_type=$old_data->reference_type;
					$ReferenceDetail->ledger_account_id = $old_data->ledger_account_id;
					$ReferenceDetail->credit = $old_data->credit;
					$ReferenceDetail->debit = $old_data->debit;
					$ReferenceDetail->transaction_date =$Invoice->date_created;  
					$this->Invoices->ReferenceDetails->save($ReferenceDetail);
				}
			}
			
		}
		
		
		echo "Done";
		exit;
	}
	
	public function ItemLedgerEntry()
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$Invoices=$this->Invoices->find()->contain(['InvoiceRows']);
		foreach($Invoices as $invoice){
			
			if($invoice->invoice_type=='GST'){
				
				foreach($invoice->invoice_rows as $invoice_row){
						$itemLedger = $this->Invoices->ItemLedgers->newEntity();
						$itemLedger->item_id = $invoice_row->item_id;
						$itemLedger->quantity = $invoice_row->quantity;
						$itemLedger->source_model = 'Invoices';
						$itemLedger->source_id = $invoice->id;
						$itemLedger->in_out = 'Out';
						$itemLedger->rate = $invoice_row->taxable_value/$invoice_row->quantity;
						$itemLedger->company_id = $invoice->company_id;
						$itemLedger->source_row_id = $invoice_row->id;
						$itemLedger->processed_on = $invoice->date_created;
						//pr($itemLedger); exit;
						$this->Invoices->ItemLedgers->save($itemLedger);
						
						
					@$NewSerialNumbers=$this->Invoices->ItemLedgers->NewSerialNumbers->find()->where(['invoice_id'=>$invoice->id,'item_id'=>$invoice_row->item_id])->toArray();
					if($NewSerialNumbers){
						foreach(@$NewSerialNumbers as $NewSerialNumber){
							$SerialNumberData = $this->Invoices->ItemLedgers->SerialNumbers->find()->where(['item_id'=>$NewSerialNumber->item_id,'name'=>$NewSerialNumber->serial_no,'status'=>'In','company_id'=>$invoice->company_id])->first();
							
							if($SerialNumberData){
							//pr($SerialNumberData); exit;
							$SerialNumber = $this->Invoices->ItemLedgers->SerialNumbers->newEntity();
							$SerialNumber->item_id = $SerialNumberData->item_id;
							$SerialNumber->name = $SerialNumberData->name;
							$SerialNumber->status = 'Out';
							$SerialNumber->company_id = $NewSerialNumber->company_id;
							$SerialNumber->parent_id = $SerialNumberData->id;
							$SerialNumber->invoice_id = $invoice->id;
							$SerialNumber->invoice_row_id = $invoice_row->id;
							$SerialNumber->transaction_date =$invoice->date_created; 
							$this->Invoices->ItemLedgers->SerialNumbers->save($SerialNumber);
							}else{
								pr($NewSerialNumber->serial_no);
							}
						}
					}
				}
				
			}else{
				$discount=$invoice->discount;
				$pf=$invoice->pnf;
				$exciseDuty=$invoice->exceise_duty;
				$sale_tax=$invoice->sale_tax_amount;
				$fright=$invoice->fright_amount;
				//pr($discount); exit;
				$amt=0;
				$total_amt=0;
				foreach($invoice->invoice_rows as $invoice_row){
					@$NewSerialNumbers=$this->Invoices->ItemLedgers->NewSerialNumbers->find()->where(['invoice_id'=>$invoice->id,'item_id'=>$invoice_row->item_id])->toArray();
					if($NewSerialNumbers){
						foreach(@$NewSerialNumbers as $NewSerialNumber){
							$SerialNumberData = $this->Invoices->ItemLedgers->SerialNumbers->find()->where(['item_id'=>$NewSerialNumber->item_id,'name'=>$NewSerialNumber->serial_no,'status'=>'In','company_id'=>$invoice->company_id])->first(); 
							if($SerialNumberData){
							$SerialNumber = $this->Invoices->ItemLedgers->SerialNumbers->newEntity();
							$SerialNumber->item_id = $SerialNumberData->item_id;
							$SerialNumber->name = $SerialNumberData->name;
							$SerialNumber->status = 'Out';
							$SerialNumber->company_id = $NewSerialNumber->company_id;
							$SerialNumber->parent_id = $SerialNumberData->id;
							$SerialNumber->invoice_id = $invoice->id;
							$SerialNumber->invoice_row_id = $invoice_row->id;
							$SerialNumber->transaction_date =$invoice->date_created;  
							$this->Invoices->ItemLedgers->SerialNumbers->save($SerialNumber);
							}else{
								pr($NewSerialNumber->serial_no); 
							}
						}
					}
					
					$amt=$invoice_row->amount;
					$total_amt=$total_amt+$amt;
				}
				
				foreach($invoice->invoice_rows as $invoice_row){ 
						$item_id=$invoice_row->item_id;
						$qty=$invoice_row->quantity;
						$rate=$invoice_row->rate;
						$amount=$invoice_row->amount;
						$item_id=$invoice_row->item_id;
						
						$dis=$discount*$amount/$total_amt;
						$item_discount=$dis/$qty;
						$pnf=$pf*$amount/$total_amt;
						$item_pf=$pnf/$qty;
						$excise=$exciseDuty*$amount/$total_amt;
						$item_excise=$excise/$qty;
						$saletax=$sale_tax*$amount/$total_amt;
						$item_saletax=$saletax/$qty;
						$fr_amount=$fright*$amount/$total_amt;
						$item_fright=$fr_amount/$qty;
						
						
						//Insert in Item Ledger//
						$itemLedger = $this->Invoices->ItemLedgers->newEntity();
						$itemLedger->item_id = $item_id;
						$itemLedger->quantity = $qty;
						$itemLedger->source_model = 'Invoices';
						$itemLedger->source_id = $invoice->id;
						$itemLedger->in_out = 'Out';
						$itemLedger->rate = $rate-$item_discount+$item_excise+$item_pf;
						$itemLedger->company_id = $invoice->company_id;
						$itemLedger->processed_on = $invoice->date_created;
						$itemLedger->source_row_id = $invoice_row->id;
						$this->Invoices->ItemLedgers->save($itemLedger);
				}
			}
		}
		echo "Done";
		exit;
	}
 	
	public function DataMigrate()
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$SalesOrders=$this->Invoices->SalesOrders->SalesOrderRows->find();
		
		foreach($SalesOrders as $SalesOrder){
			$Invoices=$this->Invoices->find()->contain(['InvoiceRows'])->where(['Invoices.sales_order_id'=>$SalesOrder->sales_order_id])->toArray();
		//	pr($Invoices); exit;
			if($Invoices){
				if(sizeof($Invoices) > 0){ //echo "exist"; echo "<br>";
					foreach($Invoices as $Invoice){
						foreach($Invoice->invoice_rows as $invoice_row){ //pr($invoice_row->item_id); exit;
							$query = $this->Invoices->InvoiceRows->query();
							$query->update()
								->set(['sales_order_row_id' => $SalesOrder->id])
								->where(['item_id' => $SalesOrder->item_id,'invoice_id'=>$Invoice->id])
								->execute();
							}
					}
				}
			}
		}
		echo "done"; exit;
	} 
	
	
	
	public function DueInvoices($customer_id=null)
    {
		$this->viewBuilder()->layout('index_layout');
		$Customer=$this->Invoices->Customers->get($customer_id);
        $this->paginate = [
            'contain' => []
        ];
        $invoices = $this->paginate($this->Invoices->find()->where(['customer_id'=>$customer_id,'due_payment !='=>0])->order(['date_created' => 'ASC']));
		
        $this->set(compact('invoices','Customer'));
        $this->set('_serialize', ['invoices']);
		$this->set(compact('url'));
    }
	
	


	public function excelExport($status=null)
	{
		$this->viewBuilder()->layout('');
		$inventory_voucher=$this->request->query('inventory_voucher');
		$sales_return=$this->request->query('sales_return');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$where=[];
		$invoice_no=$this->request->query('invoice_no');
		$file=$this->request->query('file');
		$customer=$this->request->query('customer');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$total_From=$this->request->query('total_From');
		$page=$this->request->query('page');
		$items=$this->request->query('items');
		$this->set(compact('customer','total_From','From','To','page','invoice_no','file','items'));
		$styear=[1,3,2];
			if(in_array($st_year_id,$styear)){ 
				$wheree['Invoices.financial_year_id'] = $st_year_id;
			}else{
				$wheree=[];
			}
		if(!empty($invoice_no)){
			$where['Invoices.in2 LIKE']=$invoice_no;
		}
		if(!empty($file)){
			$where['Invoices.in3 LIKE']='%'.$file.'%';
		}
		if(!empty($customer)){
			$where['Customers.customer_name LIKE']='%'.$customer.'%';
		}
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Invoices.date_created >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Invoices.date_created <=']=$To;
		}
		if(!empty($total_From)){
			$where['Invoices.total_after_pnf']=$total_From;
		}
		
	
        $this->paginate = [
            'contain' => ['Customers', 'Companies']
        ];
		
		/* if($inventory_voucher=='true'){
			$where['Invoices.inventory_voucher_status']='Pending';
			
		}else{
			if($status=='Pending' || $status==''){
				$where['status']='';
			}
			elseif($status=='Cancel'){
				$where['status']='Cancel';
			}	
		}
		 */
		  $current_rows=[];
		if(!empty($items))
		{ 
			$InvoiceRows = $this->Invoices->InvoiceRows->find();
				$invoices = $this->Invoices->find();
				$invoices->select(['id','total_sales'=>$InvoiceRows->func()->sum('InvoiceRows.quantity')])
				->innerJoinWith('InvoiceRows')
				->group(['Invoices.id'])
				->matching('InvoiceRows.Items', function ($q) use($items,$st_company_id) {
											return $q->where(['Items.id' =>$items,'company_id'=>$st_company_id]);
							})
				->contain(['Customers','SalesOrders','InvoiceRows'=>['Items']])
				->autoFields(true)
				->where(['Invoices.company_id'=>$st_company_id])
				->where($where)
				->where($wheree);
		}
		else if($inventory_voucher=='true'){
			$invoices=[];
			$invoice1=$this->Invoices->find()->where($where)->contain(['Customers','SalesOrders','InvoiceRows'=>['Items'=>function ($q) {
				return $q->where(['source !='=>'Purchessed']);
				},'SalesOrderRows'=>function ($q) {
				return $q->where(['SalesOrderRows.source_type !='=>'Purchessed']);
				}
				]])
				->where(['Invoices.company_id'=>$st_company_id])->where($wheree)
				->order(['Invoices.id' => 'DESC']);
				
				foreach($invoice1 as $invoice){
					$AccountGroupsexists = $this->Invoices->Ivs->exists(['Ivs.invoice_id' => $invoice->id]);
					if(!$AccountGroupsexists){ // pr($invoice);
						$invoices[]=$invoice;
					}
				} 
				
				
			//pr($invoices);exit;
		}else if($sales_return=='true'){
			$invoices = $this->Invoices->find()->contain(['Customers','SalesOrders','InvoiceRows'=>['Items']])->where($where)->where(['Invoices.company_id'=>$st_company_id])->where($wheree)->order(['Invoices.id' => 'DESC']);
		} else{ 
			$invoices =$this->Invoices->find()->contain(['Customers','SalesOrders','InvoiceRows'=>['Items']])->where($where)->where(['Invoices.company_id'=>$st_company_id])->where($wheree)->order(['Invoices.in2' => 'DESC']);
		} 
		//pr($invoices->toArray());exit;
		$Items = $this->Invoices->InvoiceRows->Items->find('list')->order(['Items.name' => 'ASC']);
		$this->set(compact('invoices','status','inventory_voucher','sales_return','InvoiceRows','Items','url','current_rows'));
	}
	
	 /**
     * View method
     *
     * @param string|null $id Invoice id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    
	public function view($id = null)
    {
		$this->viewBuilder()->layout('');
        $invoice = $this->Invoices->get($id, [
            'contain' => ['Customers', 'Companies', 'InvoiceRows' => ['Items']]
        ]);
	    $this->set('invoice', $invoice);
        $this->set('_serialize', ['invoice']);
    }
	
	public function PendingItemForInventoryVoucher()
    {
		$this->viewBuilder()->layout('index_layout');
		$invoices=$this->paginate(
				$this->Invoices->InvoiceRows->find()->contain(['Invoices',
				'Items'=> function ($q) {
				return $q->where(['source !='=>'Purchessed']);
				}])
				->where(['inventory_voucher'=>'Pending'])
				->order(['InvoiceRows.id' => 'DESC'])
			);
        $this->set('invoices', $invoices);
        $this->set('_serialize', ['invoice']);
    }
	
	public function pdf($id = null)
    {
		$this->viewBuilder()->layout('');
		$id = $this->EncryptingDecrypting->decryptData($id);
        $invoice = $this->Invoices->get($id, [
		'contain' => ['SaleTaxes',
					'Customers',
					'Employees','Transporters','Creator'=>['Designations'],
					'Companies'=> ['CompanyBanks'=> function ($q) {
						return $q
						->where(['CompanyBanks.default_bank' => 1]);}], 
					'InvoiceRows' => ['Items'=>['Units']]]
			]);
		//pr($invoice); exit;
        $this->set('invoice', $invoice);
		
        $this->set('_serialize', ['invoice']);
    }
	
	
	public function confirm($id = null)
    {
		$this->viewBuilder()->layout('pdf_layout');
		 $id = $this->EncryptingDecrypting->decryptData($id);
		$invoice = $this->Invoices->get($id, [
            'contain' => ['InvoiceRows']
			]);
		
		if ($this->request->is(['patch', 'post', 'put'])) {
			
			if(!empty($this->request->data['pdf_font_size'])){
				$pdf_font_size=$this->request->data['pdf_font_size'];
				$query = $this->Invoices->query();
					$query->update()
						->set(['pdf_font_size' => $pdf_font_size])
						->where(['id' => $id])
						->execute();
			}
			
			if(!empty($this->request->data['invoice_rows'])){
				foreach($this->request->data['invoice_rows'] as $invoice_row_id=>$value){
					$invoiceRow=$this->Invoices->InvoiceRows->get($invoice_row_id);
					$invoiceRow->height=$value["height"];
					$this->Invoices->InvoiceRows->save($invoiceRow);
				}
			}
			
			if(!empty($this->request->data['pdf_to_print'])){
				$pdf_to_print=$this->request->data['pdf_to_print'];
				//pr($pdf_to_print); exit;
				$query = $this->Invoices->query();
					$query->update()
						->set(['pdf_to_print' => $pdf_to_print])
						->where(['id' => $id])
						->execute();
			}
			$id = $this->EncryptingDecrypting->encryptData($id);
			return $this->redirect(['action' => 'confirm',$id]);
        }
		$this->set(compact('invoice','id'));
    }
	
	public function fetchReferenceNo($ledger_account_id=null)
    {
		$this->viewBuilder()->layout('ajax_layout');
	
		$ReferenceBalances=$this->Invoices->ReferenceBalances->find()->where(['ledger_account_id' => $ledger_account_id])->toArray();
		
		$this->set(compact(['ReferenceBalances']));
	}
	
	public function deleteReceiptRow($reference_type=null,$old_amount=null,$ledger_account_id=null,$invoice_id=null,$reference_no=null)
    {
		$reference_type=$this->request->query('reference_type');
		$old_amount=$this->request->query('old_amount');
		$ledger_account_id=(int)$this->request->query('ledger_account_id');
		$invoice_id=$this->request->query('invoice_id');
		$reference_no=$this->request->query('reference_no');
		
		
		$query1 = $this->Invoices->ReferenceDetails->query();
		$query1->delete()
		->where([
			'ledger_account_id' => $ledger_account_id,
			'invoice_id' => $invoice_id,
			'reference_no' => $reference_no,
			'reference_type' => $reference_type
		])
		->execute();
		
		if($reference_type=='Against Reference')
		{
			$res=$this->Invoices->ReferenceBalances->find()->where(['reference_no' => $reference_no,'ledger_account_id' => $ledger_account_id])->first();
			
			$q=$res->credit-$old_amount;
			
			$query2 = $this->Invoices->ReferenceBalances->query();
			$query2->update()
				->set(['credit' => $q])
				->where(['reference_no' => $reference_no,'ledger_account_id' => $ledger_account_id])
				->execute();
		}
		else
		{ 
			$query2 = $this->Invoices->ReferenceBalances->query();
			$query2->delete()
			->where([
				'reference_no' => $reference_no,
				'ledger_account_id' => $ledger_account_id
			])
			->execute();
			
		}
		echo 'Deleted';
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
		$s_employee_id=$this->viewVars['s_employee_id'];
		
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$sales_order_id=@(int)$this->request->query('sales-order'); 
		$sales_order=array(); $process_status='New';
		if(!empty($sales_order_id)){
			
				$sales_order = $this->Invoices->SalesOrders->get($sales_order_id,[
				'contain' => ['SalesOrderRows.Items' => function ($q) use($st_company_id) 
						{
						   return $q
								->contain(['SerialNumbers'=>function($q) use($st_company_id){
									return $q->where(['SerialNumbers.status' => 'In','SerialNumbers.company_id' => $st_company_id]); 
								},
								'ItemCompanies'=>function($q) use($st_company_id){
									return $q->where(['ItemCompanies.company_id' => $st_company_id]);
								}]);
						},'SalesOrderRows.SaleTaxes','Companies','Customers','Employees','SalesOrderRows'=>['InvoiceRows' => function($q) {
						return $q->select(['id','sales_order_row_id','total_qty' => $q->func()->sum('InvoiceRows.quantity')])->group('InvoiceRows.sales_order_row_id');
			}]]
				]);
				 /* $sales_order->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
				->innerJoinWith('SalesOrderRows')
				->group(['SalesOrders.id'])
				->where(['SalesOrders.company_id'=>$st_company_id]); */
			
			
			
			//pr($sales_order); exit;
			$c_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Customers','source_id'=>$sales_order->customer->id])->first();
			//pr($sales_order); exit;
			
			$process_status='Pulled From Sales-Order';
			
			$sale_tax_ledger_accounts=[];
			foreach($sales_order->sales_order_rows as $sales_order_row){
				
				$st_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['source_id'=>$sales_order_row->sale_tax->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id])->first();
				$sale_tax_ledger_accounts[$sales_order_row->sale_tax->id]=$st_LedgerAccount->id;
				//pr(['source_id'=>$sales_order_row->sale_tax->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id]); exit;
			}
		}

		$session = $this->request->session();
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




		$this->set(compact('sales_order','process_status','sales_order_id','chkdate'));
		
        $invoice = $this->Invoices->newEntity();
        if ($this->request->is('post')) {
		
			$invoice = $this->Invoices->patchEntity($invoice, $this->request->data);
			
			foreach($invoice->invoice_rows as $invoice_row){
				if($invoice_row->item_serial_numbers){
					$item_serial_no=implode(",",$invoice_row->item_serial_numbers );
					$invoice_row->item_serial_number=$item_serial_no;
				}
			}			
			
			$last_in_no=$this->Invoices->find()->select(['in2'])->where(['company_id' => $sales_order->company_id])->order(['in2' => 'DESC'])->first();
			if($last_in_no){
				$invoice->in2=$last_in_no->in2+1;
			}else{
				$invoice->in2=1;
			}
			$invoice->in3=$sales_order->so3;
			$invoice->created_by=$s_employee_id;
			$invoice->company_id=$sales_order->company_id;
			$invoice->employee_id=$sales_order->employee_id;
			$invoice->customer_id=$sales_order->customer_id;
			$invoice->customer_po_no=$sales_order->customer_po_no;
			$invoice->po_date=date("Y-m-d",strtotime($sales_order->po_date)); 
			$invoice->date_created=date("Y-m-d");
			
			if($invoice->payment_mode=='New_ref'){
			$invoice->due_payment=$invoice->grand_total;
			}else{
				$invoice->due_payment=$invoice->grand_total-$invoice->total_amount_agst;
			}
			//pr($invoice->ref_rows); exit;
			$ref_rows=$invoice->ref_rows;
			 // pr($invoice);exit;
            if ($this->Invoices->save($invoice)) 
			{
				foreach($invoice->invoice_rows as $invoice_row)
				{
					$SalesOrderRow=$this->Invoices->SalesOrderRows->find()->where(['sales_order_id'=>$invoice->sales_order_id,'item_id'=>$invoice_row->item_id])->first();
					$items_source=$this->Invoices->Items->get($invoice_row->item_id);
						if($items_source->source=='Purchessed/Manufactured'){ 
							if($SalesOrderRow->source_type=="Manufactured")
							{
								$query = $this->Invoices->query();
								$query->update()
									->set(['inventory_voucher_create' => 'Yes'])
									->where(['id' => $invoice->id])
									->execute();
							}
						}
						elseif($items_source->source=='Assembled' or $items_source->source=='Manufactured'){
							$query = $this->Invoices->query();
							$query->update()
								->set(['inventory_voucher_create' => 'Yes'])
								->where(['id' => $invoice->id])
								->execute();
						}
				} 
				
				//GET CUSTOMER LEDGER-ACCOUNT-ID
				$c_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Customers','source_id'=>$sales_order->customer->id])->first();
				
				$ledger_grand=$invoice->grand_total;
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $c_LedgerAccount->id;
				$ledger->debit = $invoice->grand_total;
				$ledger->credit = 0;
				$ledger->voucher_id = $invoice->id;
				$ledger->voucher_source = 'Invoice';
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				
				if($ledger_grand>0)
				{
					$this->Invoices->Ledgers->save($ledger); 
				}
				
				//Ledger posting for Account Reference
				$ledger_pnf=$invoice->total_after_pnf;
				//$accountReferences=$this->Invoices->AccountReferences->get(1);
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $invoice->sales_ledger_account;
				$ledger->debit = 0;
				$ledger->credit = $invoice->total_after_pnf;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$ledger->voucher_source = 'Invoice';
				if($ledger_pnf>0)
				{
					$this->Invoices->Ledgers->save($ledger); 
				}
				
				//Ledger posting for Sale Tax
				
				
				
				$ledger_saletax=$invoice->sale_tax_amount;
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $invoice->st_ledger_account_id;
				$ledger->debit = 0;
				$ledger->credit = $invoice->sale_tax_amount;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$ledger->voucher_source = 'Invoice';
				if($ledger_saletax>0)
				{
					$this->Invoices->Ledgers->save($ledger); 
				}
				
				
				//Ledger posting for Fright Amount
				
				$ledger_fright= $invoice->fright_amount;
				//$accountReferences=$this->Invoices->AccountReferences->get(3);
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $invoice->fright_ledger_account;
				$ledger->debit = 0;
				$ledger->credit = $invoice->fright_amount;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$ledger->voucher_source = 'Invoice';
				if($ledger_fright>0)
				{
					$this->Invoices->Ledgers->save($ledger); 
				}
				
				$discount=$invoice->discount;
				 $pf=$invoice->pnf;
				 $exciseDuty=$invoice->exceise_duty;
				 $sale_tax=$invoice->sale_tax_amount;
				 $fright=$invoice->fright_amount;
				 $total_amt=0;
				 
			//////start serial Number database changes Oct17	  
				foreach($invoice->invoice_rows as $invoice_row){
					$amt=$invoice_row->amount;
					$total_amt=$total_amt+$amt;
					if(!empty($invoice_row->serial_numbers)){
						$item_serial_no=$invoice_row->serial_numbers;
						$serial_nos=implode(",", $item_serial_no); 
						$query = $this->Invoices->InvoiceRows->query();
									 
						foreach($item_serial_no as $serial){
						////////
						 $query = $this->Invoices->InvoiceRows->SerialNumbers->query();
						 $serial_data=$this->Invoices->InvoiceRows->SerialNumbers->get($serial);
									$query->insert(['name', 'item_id', 'status', 'invoice_id','invoice_row_id','transaction_date','company_id','parent_id'])
									->values([
									'name' => $serial_data->name,
									'item_id' => $invoice_row->item_id,
									'status' => 'Out',
									'invoice_id' => $invoice->id,
									'invoice_row_id' => $invoice_row->id,
									'transaction_date' => $invoice->date_created,
									'company_id'=>$st_company_id,
									'parent_id'=>$serial

									]);
								$query->execute();  
						
						}
					}	
				}
			//////End serial Number database changes Oct17	
				
				if(!empty($sales_order_id)){
					$invoice->check=array_filter($invoice->check);
					$i=0; 
					foreach($invoice->check as $sales_order_row_id){
						$item_id=$invoice->invoice_rows[$i]['item_id'];
						$qty=$invoice->invoice_rows[$i]['quantity'];
						$rate=$invoice->invoice_rows[$i]['rate'];
						$amount=$invoice->invoice_rows[$i]['amount'];
						$dis=$discount*$amount/$total_amt;
						$item_discount=$dis/$qty;
						$pnf=$pf*$amount/$total_amt;
						$item_pf=$pnf/$qty;
						$excise=$exciseDuty*$amount/$total_amt;
						$item_excise=$excise/$qty;
						$saletax=$sale_tax*$amount/$total_amt;
						$item_saletax=$saletax/$qty;
						$fr_amount=$fright*$amount/$total_amt;
						$item_fright=$fr_amount/$qty;
						
						$i++;
						//Insert in Item Ledger//
						$itemLedger = $this->Invoices->ItemLedgers->newEntity();
						$itemLedger->item_id = $item_id;
						$itemLedger->quantity = $qty;
						$itemLedger->source_model = 'Invoices';
						$itemLedger->source_id = $invoice->id;
						$itemLedger->in_out = 'Out';
						$itemLedger->rate = $rate-$item_discount+$item_excise+$item_pf;
						$itemLedger->company_id = $invoice->company_id;
						$itemLedger->processed_on = date("Y-m-d");
						$this->Invoices->ItemLedgers->save($itemLedger);
					}
				}
				
				//Reference Number coding
					if(sizeof(@$ref_rows)>0){
						
						foreach($ref_rows as $ref_row){ 
							$ref_row=(object)$ref_row;
							
							$ReferenceDetail = $this->Invoices->ReferenceDetails->newEntity();
							$ReferenceDetail->company_id=$st_company_id;
							$ReferenceDetail->reference_type=$ref_row->ref_type;
							$ReferenceDetail->reference_no=$ref_row->ref_no;
							$ReferenceDetail->ledger_account_id = $c_LedgerAccount->id;
							if($ref_row->ref_cr_dr=="Dr"){
								$ReferenceDetail->debit = $ref_row->ref_amount;
								$ReferenceDetail->credit = 0;
							}else{
								$ReferenceDetail->credit = $ref_row->ref_amount;
								$ReferenceDetail->debit = 0;
							}
							$ReferenceDetail->invoice_id = $invoice->id;
							$ReferenceDetail->transaction_date = $invoice->date_created;
							
							$this->Invoices->ReferenceDetails->save($ReferenceDetail);
							
						}
						$ReferenceDetail = $this->Invoices->ReferenceDetails->newEntity();
							$ReferenceDetail->company_id=$st_company_id;
							$ReferenceDetail->reference_type="On_account";
							$ReferenceDetail->ledger_account_id = $c_LedgerAccount->id;
							if($invoice->on_acc_cr_dr=="Dr"){
								$ReferenceDetail->debit = $invoice->on_account;
								$ReferenceDetail->credit = 0;
							}else{
								$ReferenceDetail->credit = $invoice->on_account;
								$ReferenceDetail->debit = 0;
							}
							$ReferenceDetail->invoice_id = $invoice->id;
							$ReferenceDetail->transaction_date = $invoice->date_created;
							if($invoice->on_account > 0){
								$this->Invoices->ReferenceDetails->save($ReferenceDetail);
							}
					}
				
                $this->Flash->success(__('The invoice has been saved.'));

                return $this->redirect(['action' => 'confirm/'.$invoice->id]);
            } else {  
                $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
            }
        }
		
		$items = $this->Invoices->Items->find('list');
		$transporters = $this->Invoices->Transporters->find('list', ['limit' => 200])->order(['Transporters.transporter_name' => 'ASC']);
		$termsConditions = $this->Invoices->TermsConditions->find('all',['limit' => 200]);
		$SaleTaxes = $this->Invoices->SaleTaxes->find('all')->where(['freeze'=>0]);
		
		if(!empty($sales_order->customer_id)){
			
		$customer_ledger = $this->Invoices->LedgerAccounts->find()->where(['LedgerAccounts.source_id'=>$sales_order->customer_id,'LedgerAccounts.source_model'=>'Customers'])->toArray();
		
		
		$query = $this->Invoices->Ledgers->find();
		$query->select([
				'debit_total'  => $query->func()->sum('debit'),
				'credit_total' => $query->func()->sum('credit')
			])
			->where(['Ledgers.ledger_account_id'=>$customer_ledger[0]->id]);
		$ledgers = $query->toArray();
		$old_due_payment=$ledgers[0]->debit_total-$ledgers[0]->credit_total;

		}
		//pr($old_due_payment); exit;	
		$AccountReference_for_sale= $this->Invoices->AccountReferences->get(1);
		$account_first_subgroup_id=$AccountReference_for_sale->account_first_subgroup_id;
		$AccountReference_for_fright= $this->Invoices->AccountReferences->get(3);
		$account_first_subgroup_id_for_fright=$AccountReference_for_fright->account_first_subgroup_id;
		//$ac_first_grp_id=$AccountReference->account_first_subgroup_id;
		//pr($AccountReference_for_sale); exit;
		
		$ledger_account_details = $this->Invoices->LedgerAccounts->find('list')->contain(['AccountSecondSubgroups'=>['AccountFirstSubgroups' => function($q) use($account_first_subgroup_id){
			return $q->where(['AccountFirstSubgroups.id'=>$account_first_subgroup_id]);
		}]])->order(['LedgerAccounts.name' => 'ASC'])->where(['LedgerAccounts.company_id'=>$st_company_id]);
		
		$ledger_account_details_for_fright = $this->Invoices->LedgerAccounts->find('list')->contain(['AccountSecondSubgroups'=>['AccountFirstSubgroups' => function($q) use($account_first_subgroup_id_for_fright){
			return $q->where(['AccountFirstSubgroups.id'=>$account_first_subgroup_id_for_fright]);
		}]])->where(['LedgerAccounts.company_id'=>$st_company_id])->order(['LedgerAccounts.name' => 'ASC']);
		
		////
			$SalesOrders = $this->Invoices->SalesOrders->get($sales_order_id, [
            'contain' => (['Invoices'=>['InvoiceRows' => function($q) {
				return $q->select(['invoice_id','sales_order_row_id','item_id','total_qty' => $q->func()->sum('InvoiceRows.quantity')])->group('InvoiceRows.sales_order_row_id');
			}],'SalesOrderRows'])
        ]);
			
		$sales_orders_qty=[];
			foreach($SalesOrders->invoices as $invoices){ 
				foreach($invoices->invoice_rows as $invoice_row){ 
					$sales_orders_qty[@$invoice_row->sales_order_row_id]=@$sales_orders_qty[$invoice_row->sales_order_row_id]+$invoice_row->total_qty;
					
				}
			}	
		
		
		$item_serial_no=$this->Invoices->SerialNumbers->find('list');
		$employees = $this->Invoices->Employees->find('list');
        $this->set(compact('invoice', 'customers', 'companies', 'salesOrders','items','transporters','termsConditions','serviceTaxs','exciseDuty','SaleTaxes','employees','dueInvoicespay','creditlimit','old_due_payment','item_serial_no','ledger_account_details','ledger_account_details_for_fright','sale_tax_ledger_accounts','c_LedgerAccount','sales_orders_qty'));
        $this->set('_serialize', ['invoice']);
    }
	
	public function pullFromSalesOrder(){
		if ($this->request->is('post')) 
		{
			$sales_order_id=$this->request->data["sales_order_id"];
            return $this->redirect(['action' => 'add?sales-order='.$sales_order_id]);
        }
	}

    /**
     * Edit method
     *
     * @param string|null $id Invoice id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		 
		$this->viewBuilder()->layout('index_layout');
		
		$id = $this->EncryptingDecrypting->decryptData($id);
		$invoice = $this->Invoices->get($id, [
            'contain' => ['ReferenceDetails','SerialNumbers','InvoiceRows','SalesOrders' => ['SalesOrderRows' => ['Items'=>['SerialNumbers','ItemCompanies'=>function($q) use($st_company_id){
									return $q->where(['ItemCompanies.company_id' => $st_company_id]);
								}],'SaleTaxes']],'Companies','Customers'=>['CustomerAddress'=> function ($q) {
						return $q
						->where(['CustomerAddress.default_address' => 1]);}],'Employees','SaleTaxes']
        ]);
		
		
		$edited_by=$invoice->edited_by;
		$edited_on=$invoice->edited_on;
		
		$closed_month=$this->viewVars['closed_month'];
		if(!in_array(date("m-Y",strtotime($invoice->date_created)),$closed_month))
		{
		//pr(['company_id'=>$st_company_id,'source_model'=>'Customers','source_id'=>$invoice->customer->id]); exit;
		$c_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Customers','source_id'=>$invoice->customer->id])->first();
		
		$ReferenceDetails=$this->Invoices->ReferenceDetails->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'invoice_id'=>$invoice->id]);
		
		
		 $Em = new FinancialYearsController;
	     $financial_year_data = $Em->checkFinancialYear($invoice->date_created);
		$invoice_id=$id;
		//pr(['ledger_account_id'=>$c_LedgerAccount->id,'invoice_id'=>$invoice_id]); exit;
		$ReferenceDetails = $this->Invoices->ReferenceDetails->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'invoice_id'=>$invoice_id])->toArray();
		
		if(!empty($ReferenceDetails))
		{
			foreach($ReferenceDetails as $ReferenceDetail)
			{
				/* $ReferenceBalances[] = $this->Invoices->ReferenceBalances->find()->where(['ledger_account_id'=>$ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])->toArray(); */
			}
		}
		else{
			//$ReferenceBalances='';
		}
		

        if ($this->request->is(['patch', 'post', 'put'])){ 
			 $ref_rows=@$this->request->data['ref_rows'];
			
            $invoice = $this->Invoices->patchEntity($invoice, $this->request->data);
			$invoice->date_created=date("Y-m-d",strtotime($invoice->date_created));
			$invoice->company_id=$invoice->company_id;
			$invoice->employee_id=$invoice->employee_id;
			$invoice->customer_id=$invoice->customer_id;
			$invoice->customer_po_no=$invoice->customer_po_no;
			$invoice->po_date=date("Y-m-d",strtotime($invoice->po_date)); 
			$invoice->in3=$invoice->in3;
			$invoice->due_payment=$invoice->grand_total;
			
			//$invoice->edited_on = $edited_on; 
			//$invoice->edited_by = $edited_by; 
			$invoice->edited_on = date("Y-m-d"); 
			$invoice->edited_by=$this->viewVars['s_employee_id'];

			foreach($invoice->invoice_rows as $invoice_row){
				if($invoice_row->serial_numbers){
					$item_serial_no=implode(",",$invoice_row->serial_numbers );
					$invoice_row->serial_number=$item_serial_no;
				}
			}
			
			if ($this->Invoices->save($invoice)) {
				
				//////start serial Number database changes Oct17	  
				foreach($invoice->invoice_rows as $invoice_row){
					if(!empty($invoice_row->serial_numbers)){
					$item_serial_no=$invoice_row->serial_numbers;
					$serial_nos=implode(",", $item_serial_no); 
					$query = $this->Invoices->InvoiceRows->query();
									$query->update()
										->set(['serial_number' => $serial_nos])
										->where(['id' => $invoice_row->id])
										->execute(); 
				//for delete serial number in table					
					$this->Invoices->InvoiceRows->SerialNumbers->deleteAll(['SerialNumbers.invoice_id'=>$invoice->id,'SerialNumbers.invoice_row_id' => $invoice_row->id,'SerialNumbers.company_id'=>$st_company_id,'status'=>'Out']);					
					 foreach($item_serial_no as $serial){

					 $query = $this->Invoices->InvoiceRows->SerialNumbers->query();
					 $serial_data=$this->Invoices->InvoiceRows->SerialNumbers->get($serial);
							$query->insert(['name', 'item_id', 'status', 'invoice_id','invoice_row_id','transaction_date','company_id','parent_id'])
							->values([
							'name' => $serial_data->name,
							'item_id' => $invoice_row->item_id,
							'status' => 'Out',
							'invoice_id' => $invoice->id,
							'invoice_row_id' => $invoice_row->id,
							'transaction_date' => $invoice->date_created,
							'company_id'=>$st_company_id,
							'parent_id'=>$serial

							]);
							$query->execute();  
							
						}
					}	
				}
			//////End serial Number database changes Oct17	
				
				
				
				$flag=0;
				foreach($invoice->invoice_rows as $invoice_row){
					$SalesOrderRow=$this->Invoices->SalesOrderRows->find()->where(['sales_order_id'=>$invoice->sales_order_id,'item_id'=>$invoice_row->item_id])->first();
					
					$items_source=$this->Invoices->Items->get($invoice_row->item_id);
					
						if($items_source->source=='Purchessed/Manufactured'){ 
						
							if($SalesOrderRow->source_type=="Manufactured" || $SalesOrderRow->source_type==""){
								$query = $this->Invoices->query();
								$query->update()
									->set(['inventory_voucher_create' => 'Yes'])
									->where(['id' => $invoice->id])
									->execute();
									$flag=1;
							}
						}
						elseif($items_source->source=='Assembled' or $items_source->source=='Manufactured'){
							$query = $this->Invoices->query();
							$query->update()
								->set(['inventory_voucher_create' => 'Yes'])
								->where(['id' => $invoice->id])
								->execute();
								  $flag=1;
						}
						
				} //pr($flag); exit;
				if($flag==0){
					$query = $this->Invoices->query();
					$query->update()
						->set(['inventory_voucher_create' => 'No'])
						->where(['id' => $invoice->id])
						->execute();
				}
				
				if($invoice->invoice_breakups){
					foreach($invoice->invoice_breakups as $invoice_breakup){
						$rec_id=$invoice_breakup->receipt_voucher_id;
						$receipt_amt =$invoice_breakup->receipt_amount-$invoice_breakup->amount;
						 
						$query = $this->Invoices->ReceiptVouchers->query();
						$query->update()
							->set(['advance_amount' => $receipt_amt])
							->where(['id' => $rec_id])
							->execute();
					}
				}
			
				$this->Invoices->Ledgers->deleteAll(['voucher_id' => $invoice->id, 'voucher_source' => 'Invoice']);
				
				if($invoice->inventory_voucher_status == 'Converted'){
				
				$InventoryVoucher = $this->Invoices->InventoryVouchers->find()->where(['invoice_id' => $invoice->id])->first();
				
				$this->Invoices->InventoryVouchers->ItemLedgers->deleteAll(['ItemLedgers.source_id' => $InventoryVoucher->id,'source_model'=>'Inventory Voucher']);
				$this->Invoices->InventoryVouchers->InventoryVoucherRows->deleteAll(['InventoryVoucherRows.inventory_voucher_id' => $InventoryVoucher->id]);
				$this->Invoices->InventoryVouchers->delete($InventoryVoucher);
				}
				
				$query = $this->Invoices->query();
					$query->update()
						->set(['inventory_voucher_status' => 'Pending'])
						->where(['id' => $id])
						->execute();
				//$customer_ledger=$this->Invoices->Customers->get($invoice->customer_id);
				$c_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Customers','source_id'=>$invoice->customer_id])->first();
				$ledger_grand=$invoice->grand_total;
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $c_LedgerAccount->id;
				$ledger->debit = $invoice->grand_total;
				$ledger->credit = 0;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->voucher_source = 'Invoice';
				$ledger->transaction_date = $invoice->date_created;
				
				if($ledger_grand>0)
				{
					$this->Invoices->Ledgers->save($ledger); 
				} 
				//Ledger posting for Account Reference
				$ledger_pnf=$invoice->total_after_pnf;
				//$accountReferences=$this->Invoices->AccountReferences->get(1);
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $invoice->sales_ledger_account;
				$ledger->debit = 0;
				$ledger->credit = $invoice->total_after_pnf;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$ledger->voucher_source = 'Invoice';
				if($ledger_pnf>0)
				{
					
					$this->Invoices->Ledgers->save($ledger); 
				}
				
				//Ledger posting for Sale Tax
				
				
				
				$ledger_saletax=$invoice->sale_tax_amount;
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $invoice->st_ledger_account_id;
				$ledger->debit = 0;
				$ledger->credit = $invoice->sale_tax_amount;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$ledger->voucher_source = 'Invoice';
				if($ledger_saletax > 0)
				{
					$this->Invoices->Ledgers->save($ledger); 
				}
				
				//Ledger posting for Fright Amount
				$ledger_fright= $invoice->fright_amount;
				//$accountReferences=$this->Invoices->AccountReferences->get(3);
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $invoice->fright_ledger_account;
				$ledger->debit = 0;
				$ledger->credit = $invoice->fright_amount;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$ledger->voucher_source = 'Invoice';
				
				if($ledger_fright>0)
				{
					$this->Invoices->Ledgers->save($ledger); 
				}
				$this->Invoices->ItemLedgers->deleteAll(['source_id' => $invoice->id, 'source_model'=> 'Invoices']);
				$this->Invoices->ReferenceDetails->deleteAll(['invoice_id' => $invoice->id]);
				
				
				//$this->Invoices->ItemLedgers->deleteAll(['source_id' => $invoice->id, 'source_model'=> 'Invoices']);
				
				 $discount=$invoice->discount;
				 $pf=$invoice->pnf;
				 $exciseDuty=$invoice->exceise_duty;
				 $sale_tax=$invoice->sale_tax_amount;
				 $fright=$invoice->fright_amount;
				 $total_amt=0;
				foreach($invoice->invoice_rows as $invoice_row){
					$amt=$invoice_row->amount;
					$total_amt=$total_amt+$amt;
				
					$item_serial_no=$invoice_row->item_serial_number;
					$serial_no=explode(",",$item_serial_no);
					foreach($serial_no as $serial){
					$query = $this->Invoices->InvoiceRows->SerialNumbers->query();
						$query->update()
							->set(['status' => 'Out','invoice_id' => $invoice->id])
							->where(['id' => $serial])
							->execute();
					}
				}
				$i=0; foreach($invoice->invoice_rows as $invoice_rows){
					
						$item_id=$invoice->invoice_rows[$i]['item_id'];
						$qty=$invoice->invoice_rows[$i]['quantity'];
						$rate=$invoice->invoice_rows[$i]['rate'];
						$amount=$invoice->invoice_rows[$i]['amount'];
						$dis=$discount*$amount/$total_amt;
						$item_discount=$dis/$qty;
						$pnf=$pf*$amount/$total_amt;
						$item_pf=$pnf/$qty;
						$excise=$exciseDuty*$amount/$total_amt;
						$item_excise=$excise/$qty;
						$saletax=$sale_tax*$amount/$total_amt;
						$item_saletax=$saletax/$qty;
						$fr_amount=$fright*$amount/$total_amt;
						$item_fright=$fr_amount/$qty;
						
						
						$itemLedger = $this->Invoices->ItemLedgers->newEntity();
						$itemLedger->item_id = $item_id;
						$itemLedger->quantity = $qty;
						$itemLedger->source_model = 'Invoices';
						$itemLedger->source_id = $invoice->id;
						$itemLedger->in_out = 'Out';
						$itemLedger->rate = $rate-$item_discount+$item_excise+$item_pf;
						$itemLedger->company_id = $invoice->company_id;
						$itemLedger->processed_on = $invoice->date_created;
						
						$this->Invoices->ItemLedgers->save($itemLedger);
						$i++;

				}
				
				
				//Reference Number coding 
					if(sizeof(@$ref_rows)>0){
						foreach($ref_rows as $ref_row){
							$ref_row=(object)$ref_row;
							$ReferenceDetail = $this->Invoices->ReferenceDetails->newEntity();
							$ReferenceDetail->company_id=$st_company_id;
							$ReferenceDetail->reference_type=$ref_row->ref_type;
							$ReferenceDetail->reference_no=$ref_row->ref_no;
							$ReferenceDetail->ledger_account_id = $c_LedgerAccount->id;
							if($ref_row->ref_cr_dr=="Dr"){
								$ReferenceDetail->debit = $ref_row->ref_amount;
								$ReferenceDetail->credit = 0;
							}else{
								$ReferenceDetail->credit = $ref_row->ref_amount;
								$ReferenceDetail->debit = 0;
							}
							$ReferenceDetail->invoice_id = $invoice->id;
							$ReferenceDetail->transaction_date = $invoice->date_created;
							
							$tt=$this->Invoices->ReferenceDetails->save($ReferenceDetail);
							
							
						}
						$ReferenceDetail = $this->Invoices->ReferenceDetails->newEntity();
							$ReferenceDetail->company_id=$st_company_id;
							$ReferenceDetail->reference_type="On_account";
							$ReferenceDetail->ledger_account_id = $c_LedgerAccount->id;
							if($invoice->on_acc_cr_dr=="Dr"){
								$ReferenceDetail->debit = $invoice->on_account;
								$ReferenceDetail->credit = 0;
							}else{
								$ReferenceDetail->credit = $invoice->on_account;
								$ReferenceDetail->debit = 0;
							}
							$ReferenceDetail->invoice_id = $invoice->id;
							$ReferenceDetail->transaction_date = $invoice->date_created;
							if($invoice->on_account > 0){
								$this->Invoices->ReferenceDetails->save($ReferenceDetail);
							}
					}
				
				
                $this->Flash->success(__('The invoice has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else { 
                $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
            }
        }
       $customers = $this->Invoices->Customers->find('all');
       $companies = $this->Invoices->Companies->find('all', ['limit' => 200]);
	   
		$invoice_old_data = $this->Invoices->get($id,['contain' =>(['InvoiceRows'])]);
		
		 
		//start array declaration for unique validation and proceed quantity
		$invoice_qty = $this->Invoices->get($id, [
            'contain' => ['SerialNumbers','InvoiceRows','SalesOrders' => ['Invoices'=>['InvoiceRows'],'SalesOrderRows' => ['Items'=>['SerialNumbers','ItemCompanies'=>function($q) use($st_company_id){
									return $q->where(['ItemCompanies.company_id' => $st_company_id]);
								}],'SaleTaxes']],'Companies','Customers'=>['CustomerAddress'=> function ($q) {
						return $q
						->where(['CustomerAddress.default_address' => 1]);}],'Employees','SaleTaxes']
        ]);
		
		
		$sales_order_id = $invoice_old_data->sales_order_id;
		 
		$sales_qty = $this->Invoices->SalesOrders->get($sales_order_id, [
            'contain' => (['SalesOrderRows' => function ($q) {
					$q->select(['SalesOrderRows.sales_order_id','SalesOrderRows.id','total_sales_qty' => $q->func()->sum('SalesOrderRows.quantity')])->group(['SalesOrderRows.id']);
					return $q;
				}])
        ]);
		
		$sales_order_qty=[];$existing_invoice_rows=[]; $current_invoice_rows=[];$invoice_row_id=[];
		
		foreach($invoice_qty->sales_order->invoices as $all_invoice){
			foreach($all_invoice->invoice_rows as $all_invoice_row){
				if($all_invoice_row->sales_order_row_id != 0){
					@$existing_invoice_rows[$all_invoice_row->sales_order_row_id]+=@$all_invoice_row->quantity;
				}
			}
		}
		
		foreach($invoice_qty->invoice_rows as $current_invoice_row){
			@$current_invoice_rows[$current_invoice_row->sales_order_row_id]+=@$current_invoice_row->quantity;
			@$invoice_row_id[$current_invoice_row->sales_order_row_id]=@$current_invoice_row->id;
		}
		/* pr($current_invoice_rows);
		pr($invoice);
		exit; */
		foreach($sales_qty->sales_order_rows as $sales_order_row){ 
			@$sales_order_qty[@$sales_order_row->id]+=@$sales_order_row->total_sales_qty;
		}
				
		
		//end array declaration for unique validation and proceed quantity		
		$customer_ledger = $this->Invoices->LedgerAccounts->find()->where(['LedgerAccounts.source_id'=>$invoice->customer_id,'LedgerAccounts.source_model'=>'Customers'])->toArray();
		
		/* $customer_reference_details = $this->Invoices->ReferenceDetails->find()->where(['ReferenceDetails.ledger_account_id'=>$customer_ledger[0]->id])->toArray();
		//pr()
		$total_credit=0;
		$total_debit=0;
		$old_due_payment=0;
		foreach($customer_reference_details as $customer_reference_detail)
		{
			if($customer_reference_detail->debit==0)
			{
				$total_credit=$total_credit+$customer_reference_detail->credit;
			}
			else
			{
				$total_debit=$total_debit+$customer_reference_detail->debit;
			}
		} */
		$query = $this->Invoices->Ledgers->find();
		$query->select([
				'debit_total'  => $query->func()->sum('debit'),
				'credit_total' => $query->func()->sum('credit')
			])
			->where(['Ledgers.ledger_account_id'=>$customer_ledger[0]->id]);
		$ledgers = $query->toArray();
		$temp_due_payment=$ledgers[0]->debit_total-$ledgers[0]->credit_total;

				//$session = $this->request->session();
				$st_year_id = $session->read('st_year_id');
		
			   $SessionCheckDate = $this->FinancialYears->get($st_year_id);
			   $fromdate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_from));   
			   $todate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_to)); 
			   $tody1 = DATE("Y-m-d");

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


		
		//$temp_due_payment=$total_credit-$total_debit;
		$old_due_payment=$temp_due_payment-$invoice->grand_total;
		

		$AccountReference_for_sale= $this->Invoices->AccountReferences->get(1);
		$account_first_subgroup_id=$AccountReference_for_sale->account_first_subgroup_id;
		
		$AccountReference_for_fright= $this->Invoices->AccountReferences->get(3);
		$account_first_subgroup_id_for_fright=$AccountReference_for_fright->account_first_subgroup_id;
		$ledger_account_details = $this->Invoices->LedgerAccounts->find('list')->contain(['AccountSecondSubgroups'=>['AccountFirstSubgroups' => function($q) use($account_first_subgroup_id){
			return $q->where(['AccountFirstSubgroups.id'=>$account_first_subgroup_id]);
		}]])->order(['LedgerAccounts.name' => 'ASC'])->where(['LedgerAccounts.company_id'=>$st_company_id]);
		
		$ledger_account_details_for_fright = $this->Invoices->LedgerAccounts->find('list')->contain(['AccountSecondSubgroups'=>['AccountFirstSubgroups' => function($q) use($account_first_subgroup_id_for_fright){
			return $q->where(['AccountFirstSubgroups.id'=>$account_first_subgroup_id_for_fright]);
		}]])->order(['LedgerAccounts.name' => 'ASC'])->where(['LedgerAccounts.company_id'=>$st_company_id]);
		
		$items = $this->Invoices->Items->find('list');
		$transporters = $this->Invoices->Transporters->find('list');
		$termsConditions = $this->Invoices->TermsConditions->find('all');
		$SaleTaxes = $this->Invoices->SaleTaxes->find('all')->where(['freeze'=>0]);
		$employees = $this->Invoices->Employees->find('list');
        $this->set(compact('invoice_id','ReferenceDetails','invoice', 'customers', 'companies', 'salesOrders','old_due_payment','items','transporters','termsConditions','serviceTaxs','exciseDuty','SaleTaxes','employees','dueInvoices','serial_no','ItemSerialNumber','SelectItemSerialNumber','ItemSerialNumber2','financial_year_data','ledger_account_details','ledger_account_details_for_fright','sale_tax_ledger_accounts','c_LedgerAccount','chkdate','existing_invoice_rows','sales_order_qty','current_invoice_rows','invoice_row_id','id'));
        $this->set('_serialize', ['invoice']);
		}
		else
		{
			$this->Flash->error(__('This month is locked.'));
			return $this->redirect(['action' => 'index']);
		}
    }

    /**
     * Delete method
     *
     * @param string|null $id Invoice id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $invoice = $this->Invoices->get($id);
        if ($this->Invoices->delete($invoice)) {
            $this->Flash->success(__('The invoice has been deleted.'));
        } else {
            $this->Flash->error(__('The invoice could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	function getMinSellingFactor($item_id=null){
			$this->viewBuilder()->layout('index_layout');
			$session = $this->request->session();
			$st_company_id = $session->read('st_company_id');
			
			$Items = $this->Invoices->Items->get($item_id, [
				'contain' => ['ItemCompanies'=>function($q) use($st_company_id){
					return $q->where(['company_id'=>$st_company_id]);
				}]
			]);
			
			
			$stock=[];  $sumValue=0; $qtySum=0;
			
			$StockLedgers=$this->Invoices->ItemLedgers->find()->where(['ItemLedgers.item_id'=>$item_id,'ItemLedgers.company_id'=>$st_company_id])->order(['ItemLedgers.processed_on'=>'ASC']);
			
			$divid=0;
			foreach($StockLedgers as $StockLedger){
				if($StockLedger->in_out=='In'){
					if(($StockLedger->source_model=='Grns' and $StockLedger->rate_updated=='Yes') or ($StockLedger->source_model!='Grns')){
						$divid++;
						@$rate+=$StockLedger->rate;
					}
				}
			}
			$rate=$rate/$divid;
				
			
			if(empty($Items->item_companies[0]->minimum_selling_price_factor)){
				$rate=0;
				$minimum_selling_price_factor=0;
			}else{
				$minimum_selling_price_factor=$Items->item_companies[0]->minimum_selling_price_factor;
			}
			$minimumSellingPrice=$rate*$minimum_selling_price_factor;
				
			
			$Number = new NumberHelper(new \Cake\View\View());
			echo $minimumSellingPrice;
			exit;
	}
	
	function RecentRecords($item_id=null,$customer_id=null){
		$this->viewBuilder()->layout('');
		if(!empty($item_id) and !empty($customer_id)){
			
			$session = $this->request->session();
			$st_company_id = $session->read('st_company_id');
			
			
			
			$item = $this->Invoices->Items->get($item_id, [
				'contain' => ['ItemCompanies'=>function($q) use($st_company_id){
					return $q->where(['company_id'=>$st_company_id]);
				}]
			]);
			
			$StockLedgers=$this->Invoices->ItemLedgers->find()->where(['ItemLedgers.item_id'=>$item_id,'ItemLedgers.company_id'=>$st_company_id])->order(['ItemLedgers.processed_on'=>'ASC']);
			$divid=0;
			foreach($StockLedgers as $StockLedger){
				if($StockLedger->in_out=='In'){
					if(($StockLedger->source_model=='Grns' and $StockLedger->rate_updated=='Yes') or ($StockLedger->source_model!='Grns')){
						$divid++;
						@$rate+=$StockLedger->rate;
					}
				}
			}
			if($divid>0){
				$rate=$rate/$divid;
			}else{
				$rate=0;
			}
			
				
			
			if(empty($item->item_companies[0]->minimum_selling_price_factor)){
				$rate=0;
				$minimum_selling_price_factor=0;
			}else{
				$minimum_selling_price_factor=$item->item_companies[0]->minimum_selling_price_factor;
			}
			$minimumSellingPrice=$rate*$minimum_selling_price_factor;
				
			
			$Number = new NumberHelper(new \Cake\View\View());
			
			$customerIds=[]; $customer_text='';
			$customer=$this->Invoices->Customers->get($customer_id);
			if($customer->customer_group_id!=0){
				$customerGroup=$this->Invoices->CustomerGroups->get($customer->customer_group_id);
				$customer_text='Past Records of customer group - <b>'.$customerGroup->name.'</b>';
				$customers=$this->Invoices->Customers->find()->select(['id'])->where(['customer_group_id'=>$customer->customer_group_id]);
				foreach($customers as $data){
					$customerIds[]=$data->id;
				}
			}else{
				$customerIds=array($customer_id);
				 $customer_text='Past Records of customer - <b>'.$customer->customer_name.'</b>';
			}
			$Invoices=$this->Invoices->find()->where(['customer_id IN' => $customerIds,'company_id'=>$st_company_id])->matching(
					'InvoiceRows', function ($q) use($item_id) {
						return $q->where(['InvoiceRows.item_id' => $item_id]);
					}
				);
			
		
			$Number = new NumberHelper(new \Cake\View\View());
			$Html = new HtmlHelper(new \Cake\View\View());
			if($minimumSellingPrice > 0){
				$html='<span style="font-size: 14px;">Minimum Selling Rate for Item <b>"'.$item->name.'"</b> : '. $Number->format($minimumSellingPrice,[ 'places' => 2]).'</span><br/><br/>
				<div style="font-size: 14px;">'.$customer_text.'</div>
				<table class="table">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Invoice No.</th>
							<th>Invoice Date</th>
							<th>Last Selling Rate</th>
						</tr>
					</thead>
					<tbody>';
					$i=0; $link=""; foreach($Invoices as $invoice):
					if($invoice->invoice_type == 'Non-GST	'){
						$link = '/Invoices/confirm/';
					}else if($invoice->invoice_type == 'GST'){
						$link = '/Invoices/gst-confirm/';
					}
					$html.='<tr>
							<td>'.h(++$i).'</td>
							<td>'.$Html->link(($invoice->in1.'/IN'.str_pad($invoice->id, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4),$link.$invoice->id,array('target'=>'_blank')).'</td>
							<td>'.h(date('d-m-Y',strtotime($invoice->date_created))).'</td>
							<td>'.$Number->format($invoice->_matchingData['InvoiceRows']->rate,[ 'places' => 2]).'</td>
						</tr>';
					endforeach;
					$html.='</tbody>
				</table>';
				die(json_encode(array("html"=>$html,"minimum_selling_price"=>$minimumSellingPrice)));
			}else{
				$html='<span style="font-size: 14px;">Minimum Selling Rate for Item <b>"'.$item->name.'"</b> : '. $Number->format(0,[ 'places' => 2]).'</span><br/><br/>
				<div style="font-size: 14px;">'.$customer_text.'</div>
				<table class="table">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Invoice No.</th>
							<th>Invoice Date</th>
							<th>Last Selling Rate</th>
						</tr>
					</thead>
					<tbody>';
					$i=0; foreach($Invoices as $invoice):
					if($invoice->invoice_type == 'Non-GST	'){
						$link = '/Invoices/confirm/';
					}else if($invoice->invoice_type == 'GST'){
						$link = '/Invoices/gst-confirm/';
					}
					$html.='<tr>
							<td>'.h(++$i).'</td>
							<td>'.$Html->link(($invoice->in1.'/IN'.str_pad($invoice->id, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4),$link.$invoice->id,array('target'=>'_blank')).'</td>
							<td>'.h(date('d-m-Y',strtotime($invoice->date_created))).'</td>
							<td>'.$Number->format($invoice->_matchingData['InvoiceRows']->rate,[ 'places' => 2]).'</td>
						</tr>';
					endforeach;
					$html.='</tbody>
				</table>';
				die(json_encode(array("html"=>$html,"minimum_selling_price"=>0)));
			}
			
		}
	}
	
	function DueInvoicesForReceipt($received_from_id=null){
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$Customer=$this->Invoices->Customers->find()->where(['ledger_account_id'=>$received_from_id])->first();
		if(!$Customer){ echo 'Select received from.'; exit; }
		$Invoices = $this->Invoices->find()->where(['company_id'=>$st_company_id,'customer_id'=>$Customer->id,'due_payment >'=>0]);
		 $this->set(compact('Invoices','Customer'));
	}
	
	function Cancel($id = null)
    {
        $invoice = $this->Invoices->get($id);
		$invoice->status='Cancel';
		$sales_order_id=$invoice->sales_order_id;
		$this->Invoices->ItemLedgers->deleteAll(['ItemLedgers.source_id' => $id,'source_model' => 'Invoices']);
		 if ($this->Invoices->save($invoice)) {
            $this->Flash->success(__('The invoice has been Cancel.'));
        } else {
            $this->Flash->error(__('The invoice could not be Cancel. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
	
	function AgstRefForPaymentEdit($in_id=null,$customer_id=null){
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		//$Customer=$this->Invoices->Customers->find()->where(['Customers.id'=>$customer_id])->first();
		$invoiceBreakups=$this->Invoices->InvoiceBreakups->find()->where(['InvoiceBreakups.invoice_id'=>$in_id])->toArray();
		
		//pr($Customer); 	//$ReceiptVoucher=$this->Invoices->ReceiptVouchers->find()->where(['received_from_id'=>$Customer->ledger_account_id,'advance_amount > '=>0.00])->toArray();
		//pr($ReceiptVoucher); exit;
		if(!$invoiceBreakups){ echo 'Select paid to.'; exit; }
		$this->set(compact('invoiceBreakups'));
	}
	
	public function fetchRefNumbers($ledger_account_id){
		$this->viewBuilder()->layout('');
		$ReferenceBalances=$this->Invoices->ReferenceBalances->find()->where(['ledger_account_id'=>$ledger_account_id]);
		$this->set(compact('ReferenceBalances','cr_dr'));
	}
	
	function checkRefNumberUnique($received_from_id,$i){
		$reference_no=$this->request->query['ref_rows'][$i]['ref_no'];
		$ReferenceBalances=$this->Invoices->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id,'reference_no'=>$reference_no]);
		if($ReferenceBalances->count()==0){
			echo 'true';
		}else{
			echo 'false';
		}
		exit;
	}
	
	public function fetchRefNumbersEdit($received_from_id=null,$reference_no=null,$debit=null){
		$this->viewBuilder()->layout('');
		$ReferenceBalances=$this->Invoices->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id]);
		$this->set(compact('ReferenceBalances', 'reference_no', 'debit'));
	}
	
	function deleteOneRefNumbers(){
		$old_received_from_id=$this->request->query['old_received_from_id'];
		$invoice_id=$this->request->query['invoice_id'];
		$old_ref=$this->request->query['old_ref'];
		$old_ref_type=$this->request->query['old_ref_type'];
		
		if($old_ref_type=="New Reference" || $old_ref_type=="Advance Reference"){
			$this->Invoices->ReferenceBalances->deleteAll(['ledger_account_id'=>$old_received_from_id,'reference_no'=>$old_ref]);
			$this->Invoices->ReferenceDetails->deleteAll(['ledger_account_id'=>$old_received_from_id,'reference_no'=>$old_ref]);
		}elseif($old_ref_type=="Against Reference"){
			$ReferenceDetail=$this->Invoices->ReferenceDetails->find()->where(['ledger_account_id'=>$old_received_from_id,'invoice_id'=>$invoice_id,'reference_no'=>$old_ref])->first();
			if(!empty($ReferenceDetail->credit)){
				$ReferenceBalance=$this->Invoices->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
				$ReferenceBalance=$this->Invoices->ReferenceBalances->get($ReferenceBalance->id);
				$ReferenceBalance->credit=$ReferenceBalance->credit-$ReferenceDetail->credit;
				$this->Invoices->ReferenceBalances->save($ReferenceBalance);
			}elseif(!empty($ReferenceDetail->debit)){
				$ReferenceBalance=$this->Invoices->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
				$ReferenceBalance=$this->Invoices->ReferenceBalances->get($ReferenceBalance->id);
				$ReferenceBalance->debit=$ReferenceBalance->debit-$ReferenceDetail->debit;
				$this->Invoices->ReferenceBalances->save($ReferenceBalance);
			}
			$RDetail=$this->Invoices->ReferenceDetails->get($ReferenceDetail->id);
			$this->Invoices->ReferenceDetails->delete($RDetail);
		}
		
		exit;
	}
	
	public function exportSaleExcel(){
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$salesman_id=$this->request->query('salesman_name');
		
		$where=[];
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Invoices.date_created >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Invoices.date_created <=']=$To;
		}
		if(!empty($salesman_id)){ 
			
			$where['Invoices.employee_id']=$salesman_id;
			$Employees = $this->Invoices->Employees->find()->where(['Employees.id' => $salesman_id])->first();
			
		}
		$this->set(compact('From','To','salesman_id'));
		
			$SalesMans = $this->Invoices->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])
			->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.company_id' => $st_company_id,'EmployeeCompanies.freeze' => 0]);
					}
				)
			->matching(
					'Departments', function ($q) {
						return $q->where(['Departments.id' =>1]);
					}
				); 
				
		$invoices = $this->Invoices->find()->where($where)->contain(['InvoiceRows','Customers'])->order(['Invoices.id' => 'DESC'])->where(['Invoices.company_id'=>$st_company_id,'Invoices.invoice_type'=>'Non-GST	']);
		//pr($invoices->toArray()); exit;
		$this->set(compact('invoices','SalesMans','Employees'));
	}
	public function salesReport(){
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$salesman_id=$this->request->query('salesman_name');
		
		$where=[];
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Invoices.date_created >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Invoices.date_created <=']=$To;
		}
		if(!empty($salesman_id)){ 
			
			$where['Invoices.employee_id']=$salesman_id;
		}
		$this->set(compact('From','To','salesman_id'));
		
		//pr($where); exit;
		
		/*  $SalesMans = $this->Invoices->Employees->find('list')->matching(
					'Departments', function ($q) {
						return $q->where(['Departments.id' =>1]);
					}
				); */
			$SalesMans = $this->Invoices->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])
			->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.company_id' => $st_company_id,'EmployeeCompanies.freeze' => 0]);
					}
				)
			->matching(
					'Departments', function ($q) {
						return $q->where(['Departments.id' =>1]);
					}
				); 
				//pr($SalesMans); exit;
		$invoices = $this->Invoices->find()->where($where)->contain(['InvoiceRows','Customers'])->order(['Invoices.id' => 'DESC'])->where(['Invoices.company_id'=>$st_company_id,'Invoices.invoice_type'=>'Non-GST	']);
		
		$this->set(compact('invoices','SalesMans','url'));
	}
	
	
	 public function gstAdd()
    { 
		$this->viewBuilder()->layout('index_layout');
		$s_employee_id=$this->viewVars['s_employee_id'];
		
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		//$st_year_id = $session->read('st_year_id');
		//pr($session->read()); exit;
		$sales_order_id=$this->request->query('sales-order');
		$sales_order_id = $this->EncryptingDecrypting->decryptData($sales_order_id);
		$sales_order=array(); $process_status='New';
		if(!empty($sales_order_id)){
			$sales_order = $this->Invoices->SalesOrders->get($sales_order_id, [
				'contain' => ['SalesOrderRows.Items' => function ($q) use($st_company_id) {
						   return $q
								->contain(['SerialNumbers'=>function($q) use($st_company_id){
									return $q->where(['SerialNumbers.status' => 'In','SerialNumbers.company_id' => $st_company_id]); 
								},
								'ItemCompanies'=>function($q) use($st_company_id){
									return $q->where(['ItemCompanies.company_id' => $st_company_id]);
								}]);
						},'Companies','Customers'=>['Districts'],'Employees'
					]
			]);
			//pr($sales_order->customer->district->state); exit;
			$c_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Customers','source_id'=>$sales_order->customer->id])->first();
			$process_status='Pulled From Sales-Order';
		
		}

		$session = $this->request->session();
		$st_year_id = $session->read('st_year_id');
		
			   $SessionCheckDate = $this->FinancialYears->get($st_year_id);
			 //  pr($SessionCheckDate); exit;
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

		//$invoice = $this->Invoices->newEntity();
		$invoice = $this->Invoices->newEntity();
        if ($this->request->is('post')) {
			$invoice = $this->Invoices->patchEntity($invoice, $this->request->data);
			
			////start code updated serial number add Oct17changes 
			foreach($invoice->invoice_rows as $invoice_row){
				if($invoice_row->serial_numbers){
					$sr_nos=implode(",",$invoice_row->serial_numbers);
					$invoice_row->serial_number=$sr_nos;
				}
			}			
			////end code updated serial number add Oct17changes 
			$last_in_no=$this->Invoices->find()->select(['in2'])->where(['company_id' => $sales_order->company_id,'financial_year_id'=>$st_year_id])->order(['in2' => 'DESC'])->first();
			if($last_in_no){
				$invoice->in2=$last_in_no->in2+1;
			}else{
				$invoice->in2=1;
			}
			//pr($invoice->in2); exit;
			$invoice->in3=$sales_order->so3;
			$invoice->created_by=$s_employee_id;
			$invoice->financial_year_id=$st_year_id;
			$invoice->company_id=$sales_order->company_id;
			$invoice->employee_id=$sales_order->employee_id;
			$invoice->customer_id=$sales_order->customer_id;
			$invoice->customer_po_no=$sales_order->customer_po_no;
			$invoice->po_date=date("Y-m-d",strtotime($sales_order->po_date)); 
			$invoice->date_created=date("Y-m-d");
			$invoice->invoice_type='GST';
			$invoice->total_after_pnf=$invoice->total_taxable_value;
			$invoice->sales_ledger_account=$invoice->sales_ledger_account;
			if(!empty(@$this->request->data['e_way_bill_no'])){
				$invoice->e_way_bill_no=@$this->request->data['e_way_bill_no'];
			}
			
			/* if($invoice->payment_mode=='New_ref'){
			$invoice->due_payment=$invoice->grand_total;
			}else{
				$invoice->due_payment=$invoice->grand_total-$invoice->total_amount_agst;
			} */

			$ref_rows=@$invoice->ref_rows;
			//pr($invoice); exit;
            if ($this->Invoices->save($invoice)) {
				foreach($invoice->invoice_rows as $invoice_row){
					$SalesOrderRow=$this->Invoices->SalesOrderRows->find()->where(['sales_order_id'=>$invoice->sales_order_id,'item_id'=>$invoice_row->item_id])->first();
					$items_source=$this->Invoices->Items->get($invoice_row->item_id);
						if($items_source->source=='Purchessed/Manufactured'){ 
							if($SalesOrderRow->source_type=="Manufactured"){
								$query = $this->Invoices->query();
								$query->update()
									->set(['inventory_voucher_create' => 'Yes'])
									->where(['id' => $invoice->id])
									->execute();
							}
						}
						elseif($items_source->source=='Assembled' or $items_source->source=='Manufactured'){
							$query = $this->Invoices->query();
							$query->update()
								->set(['inventory_voucher_create' => 'Yes'])
								->where(['id' => $invoice->id])
								->execute();
						}
						
					//////start new serial number add code oct17
					if(!empty($invoice_row->serial_numbers)){
						$item_serial_no=$invoice_row->serial_numbers;
					} 
					if(!empty($item_serial_no)){
						foreach($item_serial_no as $key=>$serial){ 
							$serial_data=$this->Invoices->InvoiceRows->SerialNumbers->get($serial);
							
							 $query  = $this->Invoices->InvoiceRows->SerialNumbers->query();
										$query->insert(['name', 'item_id', 'status', 'invoice_id','invoice_row_id','company_id','transaction_date','parent_id'])
										->values([
										'name' => $serial_data->name,
										'item_id' => $invoice_row->item_id,
										'status' => 'Out',
										'invoice_id' => $invoice->id,
										'invoice_row_id' => $invoice_row->id,
										'company_id'=>$st_company_id,
										'transaction_date'=>$invoice->date_created,
										'parent_id'=>$serial
										]);
									$query->execute();  
						}		
					}	
					//////end new serial number add code oct17	
				} 
				
				//GET CUSTOMER LEDGER-ACCOUNT-ID
				$c_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Customers','source_id'=>$sales_order->customer->id])->first();
				
				$ledger_grand=$invoice->grand_total;
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $c_LedgerAccount->id;
				$ledger->debit = $invoice->grand_total;
				$ledger->credit = 0;
				$ledger->voucher_id = $invoice->id;
				$ledger->voucher_source = 'Invoice';
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$this->Invoices->Ledgers->save($ledger); 
				
				
				foreach($invoice->invoice_rows as $invoice_row){
				if($invoice_row->cgst_amount > 0){
					$cg_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice_row->cgst_percentage])->first();
					$ledger = $this->Invoices->Ledgers->newEntity();
					$ledger->ledger_account_id = $cg_LedgerAccount->id;
					$ledger->credit = $invoice_row->cgst_amount;
					$ledger->debit = 0;
					$ledger->voucher_id = $invoice->id;
					$ledger->voucher_source = 'Invoice';
					$ledger->company_id = $invoice->company_id;
					$ledger->transaction_date = $invoice->date_created;
					$this->Invoices->Ledgers->save($ledger); 
				}
				if($invoice_row->sgst_amount > 0){
					$s_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice_row->sgst_percentage])->first();
					$ledger = $this->Invoices->Ledgers->newEntity();
					$ledger->ledger_account_id = $s_LedgerAccount->id;
					$ledger->credit = $invoice_row->sgst_amount;
					$ledger->debit = 0;
					$ledger->voucher_id = $invoice->id;
					$ledger->voucher_source = 'Invoice';
					$ledger->company_id = $invoice->company_id;
					$ledger->transaction_date = $invoice->date_created;
					$this->Invoices->Ledgers->save($ledger); 
				}
				if($invoice_row->igst_amount > 0){
					$i_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice_row->igst_percentage])->first();
					$ledger = $this->Invoices->Ledgers->newEntity();
					$ledger->ledger_account_id = $i_LedgerAccount->id;
					$ledger->credit = $invoice_row->igst_amount;
					$ledger->debit = 0;
					$ledger->voucher_id = $invoice->id;
					$ledger->voucher_source = 'Invoice';
					$ledger->company_id = $invoice->company_id;
					$ledger->transaction_date = $invoice->date_created;
					$this->Invoices->Ledgers->save($ledger); 
				}
			}
				
				//Ledger posting for Account Reference
				//$ledger_pnf=$invoice->total_after_pnf;
				//$accountReferences=$this->Invoices->AccountReferences->get(1);
				$ledger_fright=@(float)$invoice->fright_amount;
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $invoice->sales_ledger_account;
				$ledger->debit = 0;
				$ledger->credit = $invoice->total+$ledger_fright;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$ledger->voucher_source = 'Invoice';
				$this->Invoices->Ledgers->save($ledger); 
				
				
				$discount=$invoice->discount;
				 $pf=$invoice->pnf;
				 $exciseDuty=$invoice->exceise_duty;
				 $sale_tax=$invoice->sale_tax_amount;
				 $fright=$invoice->fright_amount;
				 $total_amt=0;
				foreach($invoice->invoice_rows as $invoice_row){
						$itemLedger = $this->Invoices->ItemLedgers->newEntity();
						$itemLedger->item_id = $invoice_row->item_id;
						$itemLedger->quantity = $invoice_row->quantity;
						$itemLedger->source_model = 'Invoices';
						$itemLedger->source_id = $invoice->id;
						$itemLedger->in_out = 'Out';
						$itemLedger->rate = $invoice_row->taxable_value/$invoice_row->quantity;
						$itemLedger->company_id = $invoice->company_id;
						$itemLedger->source_row_id = $invoice_row->id;
						$itemLedger->processed_on = date("Y-m-d");
						$this->Invoices->ItemLedgers->save($itemLedger);
				}
				
				
/* 				if(!empty($sales_order_id)){
					$invoice->check=array_filter($invoice->check);
					$i=0; 
					foreach($invoice->check as $sales_order_row_id){ 
						$item_id=$invoice->invoice_rows[$i]['item_id'];
						$qty=$invoice->invoice_rows[$i]['quantity'];
						$rate=$invoice->invoice_rows[$i]['rate'];
						$amount=$invoice->invoice_rows[$i]['amount'];
						$dis=$discount*$amount/$total_amt;
						$item_discount=$dis/$qty;
						$pnf=$pf*$amount/$total_amt;
						$item_pf=$pnf/$qty;
						$excise=$exciseDuty*$amount/$total_amt;
						$item_excise=$excise/$qty;
						$saletax=$sale_tax*$amount/$total_amt;
						$item_saletax=$saletax/$qty;
						$fr_amount=$fright*$amount/$total_amt;
						$item_fright=$fr_amount/$qty;
						
						$i++;
						//Insert in Item Ledger//
						$itemLedger = $this->Invoices->ItemLedgers->newEntity();
						$itemLedger->item_id = $item_id;
						$itemLedger->quantity = $qty;
						$itemLedger->source_model = 'Invoices';
						$itemLedger->source_id = $invoice->id;
						$itemLedger->in_out = 'Out';
						$itemLedger->rate = $rate-$item_discount+$item_excise+$item_pf;
						$itemLedger->company_id = $invoice->company_id;
						$itemLedger->processed_on = date("Y-m-d");
						$this->Invoices->ItemLedgers->save($itemLedger);
					}
				} */
				
				if($invoice->fright_cgst_amount > 0){
					$cg_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice->fright_cgst_percent])->first();
					$ledger = $this->Invoices->Ledgers->newEntity();
					$ledger->ledger_account_id = $cg_LedgerAccount->id;
					$ledger->credit = $invoice->fright_cgst_amount;
					$ledger->debit = 0;
					$ledger->voucher_id = $invoice->id;
					$ledger->voucher_source = 'Invoice';
					$ledger->company_id = $invoice->company_id;
					$ledger->transaction_date = $invoice->date_created;
					$this->Invoices->Ledgers->save($ledger); 
				}
				if($invoice->fright_sgst_amount > 0){
					$s_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice->fright_sgst_percent])->first();
					$ledger = $this->Invoices->Ledgers->newEntity();
					$ledger->ledger_account_id = $s_LedgerAccount->id;
					$ledger->credit = $invoice->fright_sgst_amount;
					$ledger->debit = 0;
					$ledger->voucher_id = $invoice->id;
					$ledger->voucher_source = 'Invoice';
					$ledger->company_id = $invoice->company_id;
					$ledger->transaction_date = $invoice->date_created;
					$this->Invoices->Ledgers->save($ledger); 
				}
				if($invoice->fright_igst_amount > 0){
					$i_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice->fright_igst_percent])->first();
					$ledger = $this->Invoices->Ledgers->newEntity();
					$ledger->ledger_account_id = $i_LedgerAccount->id;
					$ledger->credit = $invoice->fright_igst_amount;
					$ledger->debit = 0;
					$ledger->voucher_id = $invoice->id;
					$ledger->voucher_source = 'Invoice';
					$ledger->company_id = $invoice->company_id;
					$ledger->transaction_date = $invoice->date_created;
					$this->Invoices->Ledgers->save($ledger); 
				}
				//Reference Number coding
					if(sizeof(@$ref_rows)== 0){
						
						$query = $this->Invoices->ReferenceDetails->query();
							$query->insert(['ledger_account_id', 'invoice_id', 'reference_no', 'credit', 'debit', 'reference_type','transaction_date'])
							->values([
								'ledger_account_id' => $c_LedgerAccount->id,
								'invoice_id' => $invoice->id,
								'reference_no' => 'i'.$invoice->in2.'('.$invoice->in4.')',
								'credit' => 0,
								'debit' => $invoice->grand_total,
								'reference_type' => 'New Reference',
								'transaction_date' => $invoice->date_created
							]);
							
							$query->execute();
						
					}else if(sizeof(@$ref_rows)>0){ 
			
						foreach($ref_rows as $ref_row){  	
							$ref_row=(object)$ref_row; 
								$ReferenceDetail = $this->Invoices->ReferenceDetails->newEntity();
								$ReferenceDetail->company_id=$st_company_id;
								$ReferenceDetail->reference_type=$ref_row->ref_type;
								$ReferenceDetail->reference_no=$ref_row->ref_no;
								$ReferenceDetail->ledger_account_id = $c_LedgerAccount->id;
								if($ref_row->ref_cr_dr=="Dr"){
									$ReferenceDetail->debit = $ref_row->ref_amount;
									$ReferenceDetail->credit = 0;
								}else{
									$ReferenceDetail->credit = $ref_row->ref_amount;
									$ReferenceDetail->debit = 0;
								}
								$ReferenceDetail->invoice_id = $invoice->id;
								$ReferenceDetail->transaction_date = $invoice->date_created;
								
								$tt=$this->Invoices->ReferenceDetails->save($ReferenceDetail);
								
							}
							$ReferenceDetail = $this->Invoices->ReferenceDetails->newEntity();
							$ReferenceDetail->company_id=$st_company_id;
							$ReferenceDetail->reference_type="On_account";
							$ReferenceDetail->ledger_account_id = $c_LedgerAccount->id;
							if($invoice->on_acc_cr_dr=="Dr"){
								$ReferenceDetail->debit = $invoice->on_account;
								$ReferenceDetail->credit = 0;
							}else{
								$ReferenceDetail->credit = $invoice->on_account;
								$ReferenceDetail->debit = 0;
							}
							$ReferenceDetail->invoice_id = $invoice->id;
							$ReferenceDetail->transaction_date = $invoice->date_created;
							if($invoice->on_account > 0){
								$this->Invoices->ReferenceDetails->save($ReferenceDetail);
							}
						}
						
					
				
                $this->Flash->success(__('The invoice has been saved.'));
				$invoice->id = $this->EncryptingDecrypting->encryptData($invoice->id);
                return $this->redirect(['action' => 'GstConfirm',$invoice->id]);
            } else { //pr($invoice); exit;

                $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
            }
        }
		
		$items = $this->Invoices->Items->find('list');
		$transporters = $this->Invoices->Transporters->find('list', ['limit' => 200])->order(['Transporters.transporter_name' => 'ASC']);
		$termsConditions = $this->Invoices->TermsConditions->find('all',['limit' => 200]);
		//$SaleTaxes = $this->Invoices->SaleTaxes->find('all')->where(['freeze'=>0]);
		
		if(!empty($sales_order->customer_id))
		{
			$customer_ledger = $this->Invoices->LedgerAccounts->find()->where(['LedgerAccounts.source_id'=>$sales_order->customer_id,'LedgerAccounts.source_model'=>'Customers'])->toArray();
			
			$query = $this->Invoices->Ledgers->find();
			$query->select([
					'debit_total'  => $query->func()->sum('debit'),
					'credit_total' => $query->func()->sum('credit')
				])
				->where(['Ledgers.ledger_account_id'=>$customer_ledger[0]->id]);
			$ledgers = $query->toArray();
			$old_due_payment=$ledgers[0]->debit_total-$ledgers[0]->credit_total;
		}
		//pr($old_due_payment); exit;	
		$AccountReference_for_sale= $this->Invoices->AccountReferences->get(1);
		$account_first_subgroup_id=$AccountReference_for_sale->account_first_subgroup_id;
		$AccountReference_for_fright= $this->Invoices->AccountReferences->get(3);
		$account_first_subgroup_id_for_fright=$AccountReference_for_fright->account_first_subgroup_id;
		//$ac_first_grp_id=$AccountReference->account_first_subgroup_id;
		//pr($AccountReference_for_sale); exit;
		
		$ledger_account_details = $this->Invoices->LedgerAccounts->find('list')->contain(['AccountSecondSubgroups'=>['AccountFirstSubgroups' => function($q) use($account_first_subgroup_id){
			return $q->where(['AccountFirstSubgroups.id'=>$account_first_subgroup_id]);
		}]])->order(['LedgerAccounts.name' => 'ASC'])->where(['LedgerAccounts.company_id'=>$st_company_id]);
		
		$ledger_account_details_for_fright = $this->Invoices->LedgerAccounts->find('list')->contain(['AccountSecondSubgroups'=>['AccountFirstSubgroups' => function($q) use($account_first_subgroup_id_for_fright){
			return $q->where(['AccountFirstSubgroups.id'=>$account_first_subgroup_id_for_fright]);
		}]])->where(['LedgerAccounts.company_id'=>$st_company_id])->order(['LedgerAccounts.name' => 'ASC']);
		
		$GstTaxes = $this->Invoices->SaleTaxes->find()->where(['SaleTaxes.account_second_subgroup_id'=>5])->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				);
				
		/////
			$SalesOrders = $this->Invoices->SalesOrders->get($sales_order_id, [
            'contain' => (['Invoices'=>['InvoiceRows' => function($q) {
				return $q->select(['invoice_id','sales_order_row_id','item_id','total_qty' => $q->func()->sum('InvoiceRows.quantity')])->group('InvoiceRows.sales_order_row_id');
			}],'SalesOrderRows'=>['Items']])
        ]);
			
		$sales_orders_qty=[];
			foreach($SalesOrders->invoices as $invoices){ 
				foreach($invoices->invoice_rows as $invoice_row){ 
					$sales_orders_qty[@$invoice_row->sales_order_row_id]=@$sales_orders_qty[$invoice_row->sales_order_row_id]+$invoice_row->total_qty;
				}
			}	
		/////	
		$employees = $this->Invoices->Employees->find('list');
        $this->set(compact('invoice', 'customers', 'companies', 'salesOrders','items','transporters','termsConditions','serviceTaxs','exciseDuty','SaleTaxes','employees','dueInvoicespay','creditlimit','old_due_payment','item_serial_no','ledger_account_details','ledger_account_details_for_fright','sale_tax_ledger_accounts','c_LedgerAccount','GstTaxes','sales_orders_qty'));
        $this->set('_serialize', ['invoice']);
		$this->set(compact('sales_order','process_status','sales_order_id','chkdate'));
	}
	
	
	
	public function gstEdit($id = null)
    {
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$id = $this->EncryptingDecrypting->decryptData($id); 
		$this->viewBuilder()->layout('index_layout');
		$invoice = $this->Invoices->get($id, [
            'contain' => ['ReferenceDetails','SerialNumbers','InvoiceRows','SalesOrders' => ['SalesOrderRows' => ['Items'=>['SerialNumbers','ItemCompanies'=>function($q) use($st_company_id){
									return $q->where(['ItemCompanies.company_id' => $st_company_id]);
								}]]],'Companies','Customers'=>['Districts','CustomerAddress'=> function ($q) {
						return $q
						->where(['CustomerAddress.default_address' => 1]);}],'Employees']
        ]);
		//pr($invoice); exit;
		$invoice_old_data = $this->Invoices->get($id, ['contain' => ['InvoiceRows']]);
		
		 $edited_by=$invoice->edited_by;
		 $edited_on=$invoice->edited_on;
		$closed_month=$this->viewVars['closed_month'];
		if(!in_array(date("m-Y",strtotime($invoice->date_created)),$closed_month))
		{
		$c_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Customers','source_id'=>$invoice->customer->id])->first();
		
		//$ReferenceDetails=$this->Invoices->ReferenceDetails->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'invoice_id'=>$invoice->id]);

		$Em = new FinancialYearsController;
	     $financial_year_data = $Em->checkFinancialYear($invoice->date_created);
		$invoice_id=$id;
		//pr(['ledger_account_id'=>$c_LedgerAccount->id,'invoice_id'=>$invoice_id]); exit;
		$ReferenceDetails = $this->Invoices->ReferenceDetails->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'invoice_id'=>$invoice_id])->toArray();
		
		$SaleReturns = $this->Invoices->SaleReturns->find()->contain(['SaleReturnRows'=>['InvoiceRows']])->where(['SaleReturns.company_id'=>$st_company_id]);
	
	
		//pr($MaterialIndents->toArray()); exit;
		$sr_qty=[];
		$in_qty=[];
			foreach($SaleReturns as $SaleReturn){ $sales_qty=[]; //pr($SaleReturn); exit;
				foreach($SaleReturn->sale_return_rows as $sale_return_row){ //pr($sale_return_row->invoice_row); exit; 
					
							@$in_qty[@$sale_return_row->invoice_row->id]+=$sale_return_row->invoice_row['quantity'];
					
					
				}
				foreach(@$SaleReturn->sale_return_rows as $sale_return_row){ // pr($sale_return_row); exit; 
					@$sr_qty[$sale_return_row['invoice_row_id']]+=$sale_return_row['quantity'];
					@$sales_qty[$sale_return_row['invoice_row_id']]+=$sale_return_row['quantity'];
				}
				
			}
//pr($sr_qty);
//pr($in_qty); exit;
		
		 if ($this->request->is(['patch', 'post', 'put'])){ 
			 $ref_rows=@$this->request->data['ref_rows'];
			
            $invoice = $this->Invoices->patchEntity($invoice, $this->request->data);
			$invoice->date_created=date("Y-m-d",strtotime($invoice->date_created));
			$invoice->company_id=$invoice->company_id;
			$invoice->employee_id=$invoice->employee_id;
			$invoice->customer_id=$invoice->customer_id;
			$invoice->customer_po_no=$invoice->customer_po_no;
			$invoice->po_date=date("Y-m-d",strtotime($invoice->po_date)); 
			$invoice->in3=$invoice->in3;
			$invoice->due_payment=$invoice->grand_total;
			//pr($invoice->total_taxable_value); exit;
			$invoice->total_after_pnf=$invoice->total_taxable_value;
			$invoice->sales_ledger_account=$invoice->sales_ledger_account;
			if(!empty(@$this->request->data['e_way_bill_no'])){
				$invoice->e_way_bill_no=@$this->request->data['e_way_bill_no'];
			}
			
			//$invoice->edited_on =$edited_on; 
			//$invoice->edited_by=$edited_by;
			$invoice->edited_on = date("Y-m-d"); 
			$invoice->edited_by=$this->viewVars['s_employee_id'];
			
			//pr($invoice);exit;
			if ($this->Invoices->save($invoice)) {
				
				
				$this->Invoices->InvoiceRows->SerialNumbers->deleteAll(['SerialNumbers.invoice_id'=>$invoice->id,'SerialNumbers.company_id'=>$st_company_id,'status'=>'Out']);	
				
				$flag=0;
				foreach($invoice->invoice_rows as $invoice_row){
					$SalesOrderRow=$this->Invoices->SalesOrderRows->find()->where(['sales_order_id'=>$invoice->sales_order_id,'item_id'=>$invoice_row->item_id])->first();
					
					$items_source=$this->Invoices->Items->get($invoice_row->item_id);
					
						if($items_source->source=='Purchessed/Manufactured'){ 
						
							if($SalesOrderRow->source_type=="Manufactured" || $SalesOrderRow->source_type==""){
								$query = $this->Invoices->query();
								$query->update()
									->set(['inventory_voucher_create' => 'Yes'])
									->where(['id' => $invoice->id])
									->execute();
									$flag=1;
							}
						}
						elseif($items_source->source=='Assembled' or $items_source->source=='Manufactured'){
							$query = $this->Invoices->query();
							$query->update()
								->set(['inventory_voucher_create' => 'Yes'])
								->where(['id' => $invoice->id])
								->execute();
								  $flag=1;
						}
						
				} //pr($flag); exit;
				
				//////start serial Number database changes Oct17	  
				foreach($invoice->invoice_rows as $invoice_row){
					if(!empty($invoice_row->serial_numbers))
					{
						$item_serial_no=$invoice_row->serial_numbers;
						$serial_nos=implode(",", $item_serial_no); 
						$query = $this->Invoices->InvoiceRows->query();
										$query->update()
											->set(['serial_number' => $serial_nos])
											->where(['id' => $invoice_row->id])
											->execute(); 
					/////for delete serial number in table					
										
					 foreach($item_serial_no as $serial){
							$serial_data=$this->Invoices->InvoiceRows->SerialNumbers->get($serial);
							 $query  = $this->Invoices->InvoiceRows->SerialNumbers->query();
										$query->insert(['name', 'item_id', 'status', 'invoice_id','invoice_row_id','company_id','transaction_date','parent_id'])
										->values([
										'name' => $serial_data->name,
										'item_id' => $invoice_row->item_id,
										'status' => 'Out',
										'invoice_id' => $invoice->id,
										'invoice_row_id' => $invoice_row->id,
										'company_id'=>$st_company_id,
										'transaction_date'=>$invoice->date_created,
										'parent_id'=>$serial
										]);
									$query->execute(); 
							
						}
					}
				}
			//////End serial Number database changes Oct17
				
				
				
				if($flag==0){
					$query = $this->Invoices->query();
					$query->update()
						->set(['inventory_voucher_create' => 'No'])
						->where(['id' => $invoice->id])
						->execute();
				}
				
				if($invoice->invoice_breakups){
					foreach($invoice->invoice_breakups as $invoice_breakup){
						$rec_id=$invoice_breakup->receipt_voucher_id;
						$receipt_amt =$invoice_breakup->receipt_amount-$invoice_breakup->amount;
						 
						$query = $this->Invoices->ReceiptVouchers->query();
						$query->update()
							->set(['advance_amount' => $receipt_amt])
							->where(['id' => $rec_id])
							->execute();
					}
				}
			
				$this->Invoices->Ledgers->deleteAll(['voucher_id' => $invoice->id, 'voucher_source' => 'Invoice']);
				
				
				
				$query = $this->Invoices->query();
					$query->update()
						->set(['inventory_voucher_status' => 'Pending'])
						->where(['id' => $id])
						->execute();
				//$customer_ledger=$this->Invoices->Customers->get($invoice->customer_id);
				$c_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Customers','source_id'=>$invoice->customer_id])->first();
				$ledger_grand=$invoice->grand_total;
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $c_LedgerAccount->id;
				$ledger->debit = $invoice->grand_total;
				$ledger->credit = 0;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->voucher_source = 'Invoice';
				$ledger->transaction_date = $invoice->date_created;
				$this->Invoices->Ledgers->save($ledger); 
				
				//pr($invoice->taxable_value); exit;
				//Ledger posting for Account Reference
				//pr($invoice->total); 
				
				$ledger_fright=@(float)$invoice->fright_amount;
				//pr($ledger_fright); 
				//exit;
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $invoice->sales_ledger_account;
				$ledger->debit = 0;
				$ledger->credit = $invoice->total+$ledger_fright;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$ledger->voucher_source = 'Invoice';
				$this->Invoices->Ledgers->save($ledger); 
				
				//GST Ledger Posting
				
				foreach($invoice->invoice_rows as $invoice_row){
					if($invoice_row->cgst_amount > 0){
						$cg_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice_row->cgst_percentage])->first();
						$ledger = $this->Invoices->Ledgers->newEntity();
						$ledger->ledger_account_id = $cg_LedgerAccount->id;
						$ledger->credit = $invoice_row->cgst_amount;
						$ledger->debit = 0;
						$ledger->voucher_id = $invoice->id;
						$ledger->voucher_source = 'Invoice';
						$ledger->company_id = $invoice->company_id;
						$ledger->transaction_date = $invoice->date_created;
						$this->Invoices->Ledgers->save($ledger); 
					}
					if($invoice_row->sgst_amount > 0){
						$s_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice_row->sgst_percentage])->first();
						$ledger = $this->Invoices->Ledgers->newEntity();
						$ledger->ledger_account_id = $s_LedgerAccount->id;
						$ledger->credit = $invoice_row->sgst_amount;
						$ledger->debit = 0;
						$ledger->voucher_id = $invoice->id;
						$ledger->voucher_source = 'Invoice';
						$ledger->company_id = $invoice->company_id;
						$ledger->transaction_date = $invoice->date_created;
						$this->Invoices->Ledgers->save($ledger); 
					}
					if($invoice_row->igst_amount > 0){
						$i_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice_row->igst_percentage])->first();
						$ledger = $this->Invoices->Ledgers->newEntity();
						$ledger->ledger_account_id = $i_LedgerAccount->id;
						$ledger->credit = $invoice_row->igst_amount;
						$ledger->debit = 0;
						$ledger->voucher_id = $invoice->id;
						$ledger->voucher_source = 'Invoice';
						$ledger->company_id = $invoice->company_id;
						$ledger->transaction_date = $invoice->date_created;
						$this->Invoices->Ledgers->save($ledger); 
					}
				}
				 
				
				if($invoice->fright_cgst_amount > 0){
					$cg_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice->fright_cgst_percent])->first();
					$ledger = $this->Invoices->Ledgers->newEntity();
					$ledger->ledger_account_id = $cg_LedgerAccount->id;
					$ledger->credit = $invoice->fright_cgst_amount;
					$ledger->debit = 0;
					$ledger->voucher_id = $invoice->id;
					$ledger->voucher_source = 'Invoice';
					$ledger->company_id = $invoice->company_id;
					$ledger->transaction_date = $invoice->date_created;
					$this->Invoices->Ledgers->save($ledger); 
				}
				if($invoice->fright_sgst_amount > 0){
					$s_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice->fright_sgst_percent])->first();
					$ledger = $this->Invoices->Ledgers->newEntity();
					$ledger->ledger_account_id = $s_LedgerAccount->id;
					$ledger->credit = $invoice->fright_sgst_amount;
					$ledger->debit = 0;
					$ledger->voucher_id = $invoice->id;
					$ledger->voucher_source = 'Invoice';
					$ledger->company_id = $invoice->company_id;
					$ledger->transaction_date = $invoice->date_created;
					$this->Invoices->Ledgers->save($ledger); 
				}
				if($invoice->fright_igst_amount > 0){
					$i_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$invoice->fright_igst_percent])->first();
					$ledger = $this->Invoices->Ledgers->newEntity();
					$ledger->ledger_account_id = $i_LedgerAccount->id;
					$ledger->credit = $invoice->fright_igst_amount;
					$ledger->debit = 0;
					$ledger->voucher_id = $invoice->id;
					$ledger->voucher_source = 'Invoice';
					$ledger->company_id = $invoice->company_id;
					$ledger->transaction_date = $invoice->date_created;
					$this->Invoices->Ledgers->save($ledger); 
				}
				
				
				$this->Invoices->ItemLedgers->deleteAll(['source_id' => $invoice->id, 'source_model'=>'Invoices']);
				
				$qq=0; foreach($invoice->invoice_rows as $invoice_rows){
					$salesorderrow=$this->Invoices->SalesOrderRows->find()->where(['sales_order_id'=>$invoice->sales_order_id,'item_id'=>$invoice_rows->item_id])->first();
					$salesorderrow->processed_quantity=$salesorderrow->processed_quantity+$invoice_rows->quantity;
					$this->Invoices->SalesOrderRows->save($salesorderrow);
					$qq++; 
				}
				$this->Invoices->ReferenceDetails->deleteAll(['invoice_id' => $invoice->id]);
				
				
				 $discount=$invoice->discount;
				 $pf=$invoice->pnf;
				 $exciseDuty=$invoice->exceise_duty;
				 $sale_tax=$invoice->sale_tax_amount;
				 $fright=$invoice->fright_amount;
				 $total_amt=0;
				foreach($invoice->invoice_rows as $invoice_row){
					$amt=$invoice_row->amount;
					$total_amt=$total_amt+$amt;
				
					$item_serial_no=$invoice_row->item_serial_number;
					$serial_no=explode(",",$item_serial_no);
					foreach($serial_no as $serial){
					$query = $this->Invoices->InvoiceRows->SerialNumbers->query();
						$query->update()
							->set(['status' => 'Out','invoice_id' => $invoice->id])
							->where(['id' => $serial])
							->execute();
					}
				}
				$i=0; foreach($invoice->invoice_rows as $invoice_rows){
					
						$item_id=$invoice->invoice_rows[$i]['item_id'];
						$qty=$invoice->invoice_rows[$i]['quantity'];
						$rate=$invoice->invoice_rows[$i]['rate'];
						$amount=$invoice->invoice_rows[$i]['amount'];
						$dis=$discount*$amount/$total_amt;
						$item_discount=$dis/$qty;
						$pnf=$pf*$amount/$total_amt;
						$item_pf=$pnf/$qty;
						$excise=$exciseDuty*$amount/$total_amt;
						$item_excise=$excise/$qty;
						$saletax=$sale_tax*$amount/$total_amt;
						$item_saletax=$saletax/$qty;
						$fr_amount=$fright*$amount/$total_amt;
						$item_fright=$fr_amount/$qty;
						
						
						$itemLedger = $this->Invoices->ItemLedgers->newEntity();
						$itemLedger->item_id = $item_id;
						$itemLedger->quantity = $qty;
						$itemLedger->source_model = 'Invoices';
						$itemLedger->source_id = $invoice->id;
						$itemLedger->in_out = 'Out';
						$itemLedger->rate = $rate-$item_discount+$item_excise+$item_pf;
						$itemLedger->company_id = $invoice->company_id;
						$itemLedger->source_row_id = $invoice_rows->id;
						$itemLedger->processed_on = $invoice->date_created;
						
						$this->Invoices->ItemLedgers->save($itemLedger);
						$i++;

				}
				
				
				//Reference Number coding 
					if(sizeof(@$ref_rows)== 0){
						
						$query = $this->Invoices->ReferenceDetails->query();
							$query->insert(['ledger_account_id', 'invoice_id', 'reference_no', 'credit', 'debit','transaction_date', 'reference_type'])
							->values([
								'ledger_account_id' => $c_LedgerAccount->id,
								'invoice_id' => $invoice->id,
								'reference_no' => 'i'.$invoice->in2,
								'credit' => 0,
								'debit' => $invoice->grand_total,
								'transaction_date' => $invoice->date_created,
								'reference_type' => 'New Reference'
							]);
							
							$query->execute();
						
					}else if(sizeof(@$ref_rows)>0){ 
			
						foreach($ref_rows as $ref_row){  	
							$ref_row=(object)$ref_row; 
								$ReferenceDetail = $this->Invoices->ReferenceDetails->newEntity();
								$ReferenceDetail->company_id=$st_company_id;
								$ReferenceDetail->reference_type=$ref_row->ref_type;
								$ReferenceDetail->reference_no=$ref_row->ref_no;
								$ReferenceDetail->ledger_account_id = $c_LedgerAccount->id;
								if($ref_row->ref_cr_dr=="Dr"){
									$ReferenceDetail->debit = $ref_row->ref_amount;
									$ReferenceDetail->credit = 0;
								}else{
									$ReferenceDetail->credit = $ref_row->ref_amount;
									$ReferenceDetail->debit = 0;
								}
								$ReferenceDetail->invoice_id = $invoice->id;
								$ReferenceDetail->transaction_date = $invoice->date_created;
								
								$this->Invoices->ReferenceDetails->save($ReferenceDetail);
								
							}
							$ReferenceDetail = $this->Invoices->ReferenceDetails->newEntity();
							$ReferenceDetail->company_id=$st_company_id;
							$ReferenceDetail->reference_type="On_account";
							$ReferenceDetail->ledger_account_id = $c_LedgerAccount->id;
							if($invoice->on_acc_cr_dr=="Dr"){
								$ReferenceDetail->debit = $invoice->on_account;
								$ReferenceDetail->credit = 0;
							}else{
								$ReferenceDetail->credit = $invoice->on_account;
								$ReferenceDetail->debit = 0;
							}
							$ReferenceDetail->invoice_id = $invoice->id;
							$ReferenceDetail->transaction_date = $invoice->date_created;
							if($invoice->on_account > 0){
								$this->Invoices->ReferenceDetails->save($ReferenceDetail);
							}
						}
				
				
                $this->Flash->success(__('The invoice has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {  pr($invoice); exit;
                $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
            }
        }
       
		
		

		$customers = $this->Invoices->Customers->find('all');
       $companies = $this->Invoices->Companies->find('all', ['limit' => 200]);
	   
	   //start array declaration for unique validation and proceed quantity
		$invoice_qty = $this->Invoices->get($id, [
            'contain' => ['SerialNumbers','InvoiceRows','SalesOrders' => ['Invoices'=>['InvoiceRows'],'SalesOrderRows' => ['Items'=>['SerialNumbers','ItemCompanies'=>function($q) use($st_company_id){
									return $q->where(['ItemCompanies.company_id' => $st_company_id]);
								}],'SaleTaxes']],'Companies','Customers'=>['CustomerAddress'=> function ($q) {
						return $q
						->where(['CustomerAddress.default_address' => 1]);}]]
        ]);
		
		
		$sales_order_id = $invoice_old_data->sales_order_id;
		 
		$sales_qty = $this->Invoices->SalesOrders->get($sales_order_id, [
            'contain' => (['SalesOrderRows' => function ($q) {
					$q->select(['SalesOrderRows.sales_order_id','SalesOrderRows.id','total_sales_qty' => $q->func()->sum('SalesOrderRows.quantity')])->group(['SalesOrderRows.id']);
					return $q;
				}])
        ]);
		
		$sales_order_qty=[];$existing_invoice_rows=[]; $current_invoice_rows=[];$invoice_row_id=[];$cur_invoice_id=[];
		
		foreach($invoice_qty->sales_order->invoices as $all_invoice){
			foreach($all_invoice->invoice_rows as $all_invoice_row){
				if($all_invoice_row->sales_order_row_id != 0){
					@$existing_invoice_rows[$all_invoice_row->sales_order_row_id]+=@$all_invoice_row->quantity;
				}
			}
		}
		
		foreach($invoice_qty->invoice_rows as $current_invoice_row){
			@$current_invoice_rows[$current_invoice_row->sales_order_row_id]+=@$current_invoice_row->quantity;
			@$invoice_row_id[$current_invoice_row->sales_order_row_id]=@$current_invoice_row->id;
			@$cur_invoice_id[$current_invoice_row->sales_order_row_id]=@$current_invoice_row->invoice_id;
		}
		// pr($current_invoice_rows);
		//pr($invoice);
		//exit; 
		foreach($sales_qty->sales_order_rows as $sales_order_row){ 
			@$sales_order_qty[@$sales_order_row->id]+=@$sales_order_row->total_sales_qty;
		}
				
		
		//end array declaration for unique validation and proceed quantity	
	   
		$customer_ledger = $this->Invoices->LedgerAccounts->find()->where(['LedgerAccounts.source_id'=>$invoice->customer_id,'LedgerAccounts.source_model'=>'Customers'])->toArray();
		
		/* $customer_reference_details = $this->Invoices->ReferenceDetails->find()->where(['ReferenceDetails.ledger_account_id'=>$customer_ledger[0]->id])->toArray();
		//pr()
		$total_credit=0;
		$total_debit=0;
		$old_due_payment=0;
		foreach($customer_reference_details as $customer_reference_detail){
			if($customer_reference_detail->debit==0){
				$total_credit=$total_credit+$customer_reference_detail->credit;
			}
			else{
				$total_debit=$total_debit+$customer_reference_detail->debit;
			}
		}
 */
		$query = $this->Invoices->Ledgers->find();
			$query->select([
					'debit_total'  => $query->func()->sum('debit'),
					'credit_total' => $query->func()->sum('credit')
				])
				->where(['Ledgers.ledger_account_id'=>$customer_ledger[0]->id]);
			$ledgers = $query->toArray();
			$temp_due_payment=$ledgers[0]->debit_total-$ledgers[0]->credit_total;
			//pr($temp_due_payment);exit;
				//$session = $this->request->session();
				$st_year_id = $session->read('st_year_id');
		
			   $SessionCheckDate = $this->FinancialYears->get($st_year_id);
			   $fromdate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_from));   
			   $todate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_to)); 
			   $tody1 = DATE("Y-m-d");

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


		
		//$temp_due_payment=$total_credit-$total_debit;
		 $old_due_payment=$temp_due_payment-$invoice->grand_total;
		

		$AccountReference_for_sale= $this->Invoices->AccountReferences->get(1);
		$account_first_subgroup_id=$AccountReference_for_sale->account_first_subgroup_id;
		
		$AccountReference_for_fright= $this->Invoices->AccountReferences->get(3);
		$account_first_subgroup_id_for_fright=$AccountReference_for_fright->account_first_subgroup_id;
		$ledger_account_details = $this->Invoices->LedgerAccounts->find('list')->contain(['AccountSecondSubgroups'=>['AccountFirstSubgroups' => function($q) use($account_first_subgroup_id){
			return $q->where(['AccountFirstSubgroups.id'=>$account_first_subgroup_id]);
		}]])->order(['LedgerAccounts.name' => 'ASC'])->where(['LedgerAccounts.company_id'=>$st_company_id]);
		
		$ledger_account_details_for_fright = $this->Invoices->LedgerAccounts->find('list')->contain(['AccountSecondSubgroups'=>['AccountFirstSubgroups' => function($q) use($account_first_subgroup_id_for_fright){
			return $q->where(['AccountFirstSubgroups.id'=>$account_first_subgroup_id_for_fright]);
		}]])->order(['LedgerAccounts.name' => 'ASC'])->where(['LedgerAccounts.company_id'=>$st_company_id]);
		
		$items = $this->Invoices->Items->find('list');
		$transporters = $this->Invoices->Transporters->find('list');
		$termsConditions = $this->Invoices->TermsConditions->find('all');
		$GstTaxes = $this->Invoices->SaleTaxes->find()->where(['SaleTaxes.account_second_subgroup_id'=>5])->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				);
		//	pr($sr_qty)	; exit;
		$employees = $this->Invoices->Employees->find('list');
        $this->set(compact('invoice_id','ReferenceDetails','ReferenceBalances','invoice', 'customers', 'companies', 'salesOrders','old_due_payment','items','transporters','termsConditions','serviceTaxs','exciseDuty','SaleTaxes','employees','dueInvoices','serial_no','ItemSerialNumber','SelectItemSerialNumber','ItemSerialNumber2','financial_year_data','ledger_account_details','ledger_account_details_for_fright','sale_tax_ledger_accounts','c_LedgerAccount','chkdate','GstTaxes','current_invoice_rows','sales_order_qty','existing_invoice_rows','invoice_row_id','cur_invoice_id','sr_qty','in_qty'));
        $this->set('_serialize', ['invoice']);
		
		
		
		}
		else
		{
			$this->Flash->error(__('This month is locked.'));
			return $this->redirect(['action' => 'index']);
		}
	}
	
	
	public function GstPdf($id = null)
    {
		$this->viewBuilder()->layout('');
		$id = $this->EncryptingDecrypting->decryptData($id);
		 $invoice = $this->Invoices->get($id, [
			'contain' => ['Customers'=>['Districts'=>['States']],
							'Employees',
							'Transporters',
							'Creator'=>['Designations'],
							'Companies'=> ['CompanyBanks'=> function ($q) {
								return $q
								->where(['CompanyBanks.default_bank' => 1]);
								}],
							'InvoiceRows' => ['Items'=>['Units']]
						]
		]);
		
		
		$cgst_per=[];
		$sgst_per=[];
		$igst_per=[];
		foreach($invoice->invoice_rows as $invoice_row){
			if($invoice_row->cgst_percentage > 0){
				$cgst_per[$invoice_row->id]=$this->Invoices->SaleTaxes->get(@$invoice_row->cgst_percentage);
			}
			if($invoice_row->sgst_percentage > 0){
				$sgst_per[$invoice_row->id]=$this->Invoices->SaleTaxes->get(@$invoice_row->sgst_percentage);
			}
			if($invoice_row->igst_percentage > 0){
				$igst_per[$invoice_row->id]=$this->Invoices->SaleTaxes->get(@$invoice_row->igst_percentage);
			}
		}
		
		
		if($invoice->fright_amount > 0){
			if($invoice->fright_cgst_percent > 0){
					$fright_ledger_cgst=$this->Invoices->SaleTaxes->get(@$invoice->fright_cgst_percent);
				}
				if($invoice->fright_sgst_percent > 0){
					$fright_ledger_sgst=$this->Invoices->SaleTaxes->get(@$invoice->fright_sgst_percent);
				}
				if($invoice->fright_igst_percent > 0){
					$fright_ledger_igst=$this->Invoices->SaleTaxes->get(@$invoice->fright_igst_percent);
				}
			
		}
		//pr($fright_ledger_igst); exit;
	//pr($invoice); exit;
        //$this->set('invoice', $invoice);
		$this->set(compact('invoice','cgst_per','sgst_per','igst_per','fright_ledger_cgst','fright_ledger_sgst','fright_ledger_igst','fright_ledger_account'));
       // $this->set('_serialize', ['invoice','cgst_per','sgst_per','igst_per']);
    }
	
	public function DispatchDownload($id = null)
    {
		$this->viewBuilder()->layout('pdf_layout');
		$id = $this->EncryptingDecrypting->decryptData($id);
		$invoice = $this->Invoices->get($id, [
            'contain' => ['InvoiceRows']
			]);
			
			if ($this->request->is(['patch', 'post', 'put'])) {
			
			if(!empty($this->request->data['dispatch_font_size'])){
				$dispatch_font_size=$this->request->data['dispatch_font_size'];
				$query = $this->Invoices->query();
					$query->update()
						->set(['dispatch_font_size' => $dispatch_font_size])
						->where(['id' => $id])
						->execute();
				}
				return $this->redirect(['action' => 'DispatchDownload/'.$id]);
			}
		
		
		$this->set(compact('id','invoice'));
       // $this->set('_serialize', ['invoice','cgst_per','sgst_per','igst_per']);
    }
	public function GstDispatchPdf($id = null)
    {
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$company_data=$this->Invoices->Companies->get($st_company_id);
		 $invoice = $this->Invoices->SendEmails->find()->contain(['Invoices'=>['Customers','SalesOrders','Creator'=>['Designations'],
							'Companies'=> ['CompanyBanks'=> function ($q) {
								return $q
								->where(['CompanyBanks.default_bank' => 1]);
								}]]])->where(['SendEmails.invoice_id' => $id
								])->order(['SendEmails.id'=>'DESC'])->first();

		$this->set(compact('invoice','company_data'));
    }
	public function GstConfirm($id = null)
    {
		$this->viewBuilder()->layout('pdf_layout');
		 $id = $this->EncryptingDecrypting->decryptData($id);
		// echo $id;exit;
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$company_data=$this->Invoices->Companies->get($st_company_id);
		
		$invoice = $this->Invoices->get($id, [
            'contain' => [
							'Companies'=> ['CompanyBanks'=> function ($q) {
								return $q
								->where(['CompanyBanks.default_bank' => 1]);
								}],'InvoiceRows']
			]);
		
		//pr($invoice); exit;
		if ($this->request->is(['patch', 'post', 'put'])) {
			
			if(!empty($this->request->data['pdf_font_size'])){
				$pdf_font_size=$this->request->data['pdf_font_size'];
				$query = $this->Invoices->query();
					$query->update()
						->set(['pdf_font_size' => $pdf_font_size])
						->where(['id' => $id])
						->execute();
			}
			
			if(!empty($this->request->data['invoice_rows'])){
				foreach($this->request->data['invoice_rows'] as $invoice_row_id=>$value){
					$invoiceRow=$this->Invoices->InvoiceRows->get($invoice_row_id);
					$invoiceRow->height=$value["height"];
					$this->Invoices->InvoiceRows->save($invoiceRow);
				}
			}
			if(!empty($this->request->data['pdf_to_print'])){
				$pdf_to_print=$this->request->data['pdf_to_print'];
				//pr($pdf_to_print); exit;
				$query = $this->Invoices->query();
					$query->update()
						->set(['pdf_to_print' => $pdf_to_print])
						->where(['id' => $id])
						->execute();
			}
			$id = $this->EncryptingDecrypting->encryptData($id);
			return $this->redirect(['action' => 'GstConfirm',$id]);
        }
		$termsConditions = $this->Invoices->DispatchDocuments->find('all',['limit' => 200]);
		$this->set(compact('invoice','id','termsConditions','company_data','st_year_id'));
    }
	
	public function gstSalesReport(){
		
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$salesman_id=$this->request->query('salesman_name');
		$item_name=$this->request->query('item_name');
		$item_category=$this->request->query('item_category');
		$item_group=$this->request->query('item_group_id');
		$item_sub_group=$this->request->query('item_sub_group_id');
		
		$where=[];
		$where1=[];
		$where2=[];
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Invoices.date_created >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Invoices.date_created <=']=$To;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where1['InvoiceBookings.supplier_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where1['InvoiceBookings.supplier_date <=']=$To;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where3['Ledgers.transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where3['Ledgers.transaction_date <=']=$To;
		}
		
		
		if(!empty($item_name)){ 
			$invoices = $this->Invoices->find()
						->contain(['Customers','InvoiceRows'])
						->matching('InvoiceRows.Items', function ($q) use($item_name) { 
								return $q->where(['InvoiceRows.item_id' => $item_name]);
								}
						)
						->order(['Invoices.id' => 'DESC'])
						->where($where)
						->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount = '=>0,'invoice_type'=>'GST']);
			
			$invoiceGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes']);
				
			$interStateInvoice = $this->Invoices->find()->where($where)
					->contain(['Customers','InvoiceRows'])
					->matching('InvoiceRows.Items', function ($q) use($item_name) { 
								return $q->where(['InvoiceRows.item_id' => $item_name]);
								}
						)
			->order(['Invoices.id' => 'DESC'])->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount > '=>0,'invoice_type'=>'GST']);
			
			$invoiceIGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'igst'=>'Yes']);
			
			$invoiceBookings = $this->Invoices->InvoiceBookings->find()->where($where1)
			->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
					return $q->where(['InvoiceBookingRows.igst = '=>0]);
			}])
			->matching('InvoiceBookingRows.Items', function ($q) use($item_name) { 
								return $q->where(['InvoiceBookingRows.item_id' => $item_name]);
								}
						)
			->order(['InvoiceBookings.id' => 'DESC'])->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
			
			$invoiceBookingsGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes']);
			
			$invoiceBookingsInterState = $this->Invoices->InvoiceBookings->find()->where($where1)->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
											return $q->where(['InvoiceBookingRows.igst != '=>0]);
			}])
			->matching('InvoiceBookingRows.Items', function ($q) use($item_name) { 
								return $q->where(['InvoiceBookingRows.item_id' => $item_name]);
								}
						)
			->order(['InvoiceBookings.id' => 'DESC'])->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
			
			$PurchaseIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
			
			$SaleTaxeIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				//pr($SaleTaxeIgst->toArray()); exit;
				
				$voucherLedgerDetailIgst=[];
				$voucherSourceIgst=[];
				$LedgerAccountDetailIgst=[];
				foreach($SaleTaxeIgst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ 
								$voucherLedgerDetailIgst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceIgst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
						
						$LedgerAccountDetailIgst[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				} 
				$SaleTaxeGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes'])->orWhere(['account_second_subgroup_id'=>6,'sgst'=>'Yes']);
				$voucherLedgerDetailsGst=[];
				$voucherSourceGst=[];
				$LedgerAccountDetails=[];
			//	pr($SaleTaxeGst->toArray()); 
				foreach($SaleTaxeGst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{ 
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{
								$voucherLedgerDetailsGst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceGst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
					$LedgerAccountDetails[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				}
				
		}
		else {
			if(!empty($item_category) && empty($item_group) && empty($item_sub_group)){  
			$invoices = $this->Invoices->find()
						->contain(['Customers','InvoiceRows'=>['Items']])
						->matching(
							'InvoiceRows.Items', function ($q) use($item_category) { 
								return $q->where(['Items.item_category_id' => $item_category]);
								}
						)
						->order(['Invoices.id' => 'DESC'])
						->where($where)
						->group('Invoices.id')
						->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount = '=>0,'invoice_type'=>'GST']);
						
			$invoiceGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes']);			
					
					//$invoices=array_unique($invoices);
			$interStateInvoice = $this->Invoices->find()->where($where)
					->contain(['Customers','InvoiceRows'])
					->matching('InvoiceRows.Items', function ($q) use($item_category) { 
								return $q->where(['Items.item_category_id' => $item_category]);
								}
						)
					->order(['Invoices.id' => 'DESC'])
					->group('Invoices.id')
					->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount > '=>0,'invoice_type'=>'GST']);
					
			$invoiceIGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'igst'=>'Yes']);		
					
					
			$invoiceBookings = $this->Invoices->InvoiceBookings->find()->where($where1)
						->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
								return $q->where(['InvoiceBookingRows.igst = '=>0]);
						}])
						->matching('InvoiceBookingRows.Items', function ($q) use($item_category) { 
											return $q->where(['Items.item_category_id' => $item_category]);
											}
									)
						->order(['InvoiceBookings.id' => 'DESC'])
						->group('InvoiceBookings.id')
						->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
			
			$invoiceBookingsGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes']);
			
			$invoiceBookingsInterState = $this->Invoices->InvoiceBookings->find()->where($where1)->			contain(['Vendors','InvoiceBookingRows'=>function($q){ 
											return $q->where(['InvoiceBookingRows.igst != '=>0]);
					}])
					->matching('InvoiceBookingRows.Items', function ($q) use($item_category) { 
										return $q->where(['Items.item_category_id' => $item_category]);
										}
								)
					->order(['InvoiceBookings.id' => 'DESC'])
					->group('InvoiceBookings.id')
					->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
			
			$PurchaseIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				
				
			$SaleTaxeIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				//pr($SaleTaxeIgst->toArray()); exit;
				
				$voucherLedgerDetailIgst=[];
				$voucherSourceIgst=[];
				$LedgerAccountDetailIgst=[];
				foreach($SaleTaxeIgst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ 
								$voucherLedgerDetailIgst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceIgst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
						
						$LedgerAccountDetailIgst[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				} 
				$SaleTaxeGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes'])->orWhere(['account_second_subgroup_id'=>6,'sgst'=>'Yes']);
				$voucherLedgerDetailsGst=[];
				$voucherSourceGst=[];
				$LedgerAccountDetails=[];
			//	pr($SaleTaxeGst->toArray()); 
				foreach($SaleTaxeGst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{ 
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{
								$voucherLedgerDetailsGst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceGst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
					$LedgerAccountDetails[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				}
			}
		else if(!empty($item_group) && empty($item_sub_group) && empty($item_category)){ 
			$invoices = $this->Invoices->find()
						->contain(['Customers','InvoiceRows'=>['Items']])
						->matching(
							'InvoiceRows.Items', function ($q) use($item_group) { 
								return $q->where(['Items.item_group_id' => $item_group]);
								}
						)
						->order(['Invoices.id' => 'DESC'])
						->where($where)
						->group('Invoices.id')
						->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount = '=>0,'invoice_type'=>'GST']);
						
			$invoiceGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes']);			
			
			$interStateInvoice = $this->Invoices->find()->where($where)
					->contain(['Customers','InvoiceRows'])
					->matching('InvoiceRows.Items', function ($q) use($item_group) { 
								return $q->where(['Items.item_group_id' => $item_group]);
								}
						)
					->order(['Invoices.id' => 'DESC'])
					->group('Invoices.id')
					->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount > '=>0,'invoice_type'=>'GST']);
					
			$invoiceIGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'igst'=>'Yes']);		
			
			$invoiceBookings = $this->Invoices->InvoiceBookings->find()->where($where1)
			->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
					return $q->where(['InvoiceBookingRows.igst = '=>0]);
			}])
			->matching('InvoiceBookingRows.Items', function ($q) use($item_group) { 
								return $q->where(['Items.item_group_id' => $item_group]);
								}
						)
			->order(['InvoiceBookings.id' => 'DESC'])
			->group('InvoiceBookings.id')
			->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
			
			$invoiceBookingsGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes']);
				
			$invoiceBookingsInterState = $this->Invoices->InvoiceBookings->find()->where($where1)->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
											return $q->where(['InvoiceBookingRows.igst != '=>0]);
			}])
			->matching('InvoiceBookingRows.Items', function ($q) use($item_group) { 
								return $q->where(['Items.item_group_id' => $item_group]);
								}
						)
			->order(['InvoiceBookings.id' => 'DESC'])
			->group('InvoiceBookings.id')
			->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
			
			$SaleTaxeIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				//pr($SaleTaxeIgst->toArray()); exit;
				
				$voucherLedgerDetailIgst=[];
				$voucherSourceIgst=[];
				$LedgerAccountDetailIgst=[];
				foreach($SaleTaxeIgst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ 
								$voucherLedgerDetailIgst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceIgst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
						
						$LedgerAccountDetailIgst[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				} 
				$SaleTaxeGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes'])->orWhere(['account_second_subgroup_id'=>6,'sgst'=>'Yes']);
				$voucherLedgerDetailsGst=[];
				$voucherSourceGst=[];
				$LedgerAccountDetails=[];
			//	pr($SaleTaxeGst->toArray()); 
				foreach($SaleTaxeGst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{ 
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{
								$voucherLedgerDetailsGst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceGst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
					$LedgerAccountDetails[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				}
				$PurchaseIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				
				
		}
		else if(!empty($item_sub_group && empty($item_group) && empty($item_category))){
			$invoices = $this->Invoices->find()
						->contain(['Customers','InvoiceRows'=>['Items']])
						->matching(
							'InvoiceRows.Items', function ($q) use($item_sub_group) { 
								return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
								}
						)
						->order(['Invoices.id' => 'DESC'])
						->group('Invoices.id')
						->where($where)
						->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount = '=>0,'invoice_type'=>'GST']);
			
			$invoiceGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes']);	
						
			$interStateInvoice = $this->Invoices->find()->where($where)
								->contain(['Customers','InvoiceRows'])
								->matching('InvoiceRows.Items', function ($q) use($item_sub_group) { 
											return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
											}
									)
								->order(['Invoices.id' => 'DESC'])
								->group('Invoices.id')
								->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount > '=>0,'invoice_type'=>'GST']);
			
			$invoiceIGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'igst'=>'Yes']);	
			
			$invoiceBookings = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
								return $q->where(['InvoiceBookingRows.igst = '=>0]);
							}])
							->matching('InvoiceBookingRows.Items', function ($q) use($item_sub_group) { 
											return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
											}
								)
							->order(['InvoiceBookings.id' => 'DESC'])
							->group('InvoiceBookings.id')
							->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
			
			$invoiceBookingsGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes']);	
			
			$invoiceBookingsInterState = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
												return $q->where(['InvoiceBookingRows.igst != '=>0]);
							}])
							->matching('InvoiceBookingRows.Items', function ($q) use($item_sub_group) { 
											return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
											}
										)
							->order(['InvoiceBookings.id' => 'DESC'])
							->group('InvoiceBookings.id')
							->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
							
			$SaleTaxeIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				//pr($SaleTaxeIgst->toArray()); exit;
				
				$voucherLedgerDetailIgst=[];
				$voucherSourceIgst=[];
				$LedgerAccountDetailIgst=[];
				foreach($SaleTaxeIgst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ 
								$voucherLedgerDetailIgst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceIgst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
						
						$LedgerAccountDetailIgst[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				} 
				$SaleTaxeGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes'])->orWhere(['account_second_subgroup_id'=>6,'sgst'=>'Yes']);
				$voucherLedgerDetailsGst=[];
				$voucherSourceGst=[];
				$LedgerAccountDetails=[];
			//	pr($SaleTaxeGst->toArray()); 
				foreach($SaleTaxeGst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{ 
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{
								$voucherLedgerDetailsGst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceGst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
					$LedgerAccountDetails[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				}
				$PurchaseIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				
				
		}else if(!empty($item_category) && !empty($item_group) && !empty($item_sub_group)){  
				$invoices = $this->Invoices->find()
						->contain(['Customers','InvoiceRows'=>['Items']])
						->matching(
							'InvoiceRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
								return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
								}
						)
						->order(['Invoices.id' => 'DESC'])
						->group('Invoices.id')
						->where($where)
						->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount = '=>0,'invoice_type'=>'GST']);
				
				$invoiceGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes']);
				
				$interStateInvoice = $this->Invoices->find()->where($where)
								->contain(['Customers','InvoiceRows'])
								->matching('InvoiceRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
										}
									)
								->order(['Invoices.id' => 'DESC'])
								->group('Invoices.id')
								->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount > '=>0,'invoice_type'=>'GST']);
				
					$invoiceIGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'igst'=>'Yes']);
			
				$invoiceBookings = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
								return $q->where(['InvoiceBookingRows.igst = '=>0]);
							}])
							->matching('InvoiceBookingRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
										}
								)
							->order(['InvoiceBookings.id' => 'DESC'])
							->group('InvoiceBookings.id')
							->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
							
				$invoiceBookingsGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes']);			
			
				$invoiceBookingsInterState = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
												return $q->where(['InvoiceBookingRows.igst != '=>0]);
							}])
							->matching('InvoiceBookingRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
								}
										)
							->order(['InvoiceBookings.id' => 'DESC'])
							->group('InvoiceBookings.id')
							->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
				$SaleTaxeIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				//pr($SaleTaxeIgst->toArray()); exit;
				
				$voucherLedgerDetailIgst=[];
				$voucherSourceIgst=[];
				$LedgerAccountDetailIgst=[];
				foreach($SaleTaxeIgst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ 
								$voucherLedgerDetailIgst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceIgst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
						
						$LedgerAccountDetailIgst[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				} 
				$SaleTaxeGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes'])->orWhere(['account_second_subgroup_id'=>6,'sgst'=>'Yes']);
				$voucherLedgerDetailsGst=[];
				$voucherSourceGst=[];
				$LedgerAccountDetails=[];
			//	pr($SaleTaxeGst->toArray()); 
				foreach($SaleTaxeGst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{ 
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{
								$voucherLedgerDetailsGst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceGst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
					$LedgerAccountDetails[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				}
				$PurchaseIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				
			}else if(!empty($item_category) && !empty($item_group) && empty($item_sub_group)){  
				
				$invoices = $this->Invoices->find()
							->contain(['Customers','InvoiceRows'=>['Items']])
							->matching(
								'InvoiceRows.Items', function ($q) use($item_category,$item_group) { 
									return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
									}
							)
							->order(['Invoices.id' => 'DESC'])
							->group('Invoices.id')
							->where($where)
							->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount = '=>0,'invoice_type'=>'GST']);
							
				$invoiceGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes']);
				
				$interStateInvoice = $this->Invoices->find()->where($where)
									->contain(['Customers','InvoiceRows'])
									->matching('InvoiceRows.Items', function ($q) use($item_category,$item_group) { 
												return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
											}
										)
									->order(['Invoices.id' => 'DESC'])
									->group('Invoices.id')
									->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount > '=>0,'invoice_type'=>'GST']);
			
				$invoiceIGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'igst'=>'Yes']);
			
				$invoiceBookings = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
								return $q->where(['InvoiceBookingRows.igst = '=>0]);
							}])
							->matching('InvoiceBookingRows.Items', function ($q) use($item_category,$item_group) { 
									return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
									}
								)
							->order(['InvoiceBookings.id' => 'DESC'])
							->group('InvoiceBookings.id')
							->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
							
				$invoiceBookingsGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes']);			
			
				$invoiceBookingsInterState = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
												return $q->where(['InvoiceBookingRows.igst != '=>0]);
							}])
							->matching('InvoiceBookingRows.Items', function ($q) use($item_category,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
									}
										)
							->order(['InvoiceBookings.id' => 'DESC'])
							->group('InvoiceBookings.id')
							->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
							
				$PurchaseIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				
				
				
				$SaleTaxeIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				//pr($SaleTaxeIgst->toArray()); exit;
				
				$voucherLedgerDetailIgst=[];
				$voucherSourceIgst=[];
				$LedgerAccountDetailIgst=[];
				foreach($SaleTaxeIgst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ 
								$voucherLedgerDetailIgst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceIgst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
						
						$LedgerAccountDetailIgst[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				} 
				$SaleTaxeGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes'])->orWhere(['account_second_subgroup_id'=>6,'sgst'=>'Yes']);
				$voucherLedgerDetailsGst=[];
				$voucherSourceGst=[];
				$LedgerAccountDetails=[];
			//	pr($SaleTaxeGst->toArray()); 
				foreach($SaleTaxeGst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{ 
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{
								$voucherLedgerDetailsGst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceGst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
					$LedgerAccountDetails[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				}
			}
			else{ 
				$invoices = $this->Invoices->find()
						->contain(['Customers','InvoiceRows'=>['Items']])
						->order(['Invoices.id' => 'DESC'])
						->where($where)
						->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount = '=>0,'invoice_type'=>'GST']);
						
				$invoiceGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes']);
				
						
				$interStateInvoice = $this->Invoices->find()->where($where)
									->contain(['Customers','InvoiceRows'])
									->order(['Invoices.id' => 'DESC'])->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount > '=>0,'invoice_type'=>'GST']);
			
				$invoiceIGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'igst'=>'Yes']);
				
				
				
				$invoiceBookings = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
								return $q->where(['InvoiceBookingRows.igst = '=>0]);
							}])
							->order(['InvoiceBookings.id' => 'DESC'])->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
				//pr($invoiceBookings->toArray()); exit;
				
				$invoiceBookingsGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes']);
				//pr($invoiceBookingsGst->toArray()); exit;
				$PurchaseIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				
				
			//	pr($PurchaseIgst->toArray()); exit;
				$invoiceBookingsInterState = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
												return $q->where(['InvoiceBookingRows.igst != '=>0]);
							}])
							->order(['InvoiceBookings.id' => 'DESC'])->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
							
							
				$SaleTaxeIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				//pr($SaleTaxeIgst->toArray()); exit;
				
				$voucherLedgerDetailIgst=[];
				$voucherSourceIgst=[];
				$LedgerAccountDetailIgst=[];
				foreach($SaleTaxeIgst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ 
								$voucherLedgerDetailIgst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceIgst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
						
						$LedgerAccountDetailIgst[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				} 
				
				$SaleTaxeGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes'])->orWhere(['account_second_subgroup_id'=>6,'sgst'=>'Yes']);
				$voucherLedgerDetailsGst=[];
				$voucherSourceGst=[];
				$LedgerAccountDetails=[];
	
				foreach($SaleTaxeGst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{   
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ //pr($voucherLedgerData);
								$voucherLedgerDetailsGst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceGst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
					$LedgerAccountDetails[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				}
			}
		}
		
		
		$this->set(compact('From','To','salesman_id','item_category','item_group','item_sub_group','item_name'));
		//pr($invoices->toArray()); exit;
		
		$ItemCategories = $this->Invoices->Items->ItemCategories->find('list')->order(['ItemCategories.name' => 'ASC']);
		$ItemGroups = $this->Invoices->Items->ItemGroups->find('list')->order(['ItemGroups.name' => 'ASC']);
		$ItemSubGroups = $this->Invoices->Items->ItemSubGroups->find('list')->order(['ItemSubGroups.name' => 'ASC']);
		$Items = $this->Invoices->Items->find('list')->order(['Items.name' => 'ASC']);
		//pr($invoiceBookingsInterState->toArray()); exit;
		$this->set(compact('invoices','SalesMans','SalesOrders','interStateInvoice','invoiceBookings','invoiceBookingsInterState','Items','ItemGroups','ItemCategories','ItemSubGroups','voucherLedgerDetails','voucherSource','voucherLedgerDetailsGst','voucherSourceGst','voucherLedgerDetailIgst','voucherSourceIgst','SaleTaxeGst','LedgerAccountDetails','LedgerAccountDetailIgst','PurchaseIgst','PurchaseCgst','invoiceIGst','invoiceGst','invoiceBookingsGst','url'));
	}
	
	public function gstSalesExcelExport(){
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$salesman_id=$this->request->query('salesman_name');
		$item_name=$this->request->query('item_name');
		$item_category=$this->request->query('item_category');
		$item_group=$this->request->query('item_group_id');
		$item_sub_group=$this->request->query('item_sub_group_id');
		
		$where=[];
		$where1=[];
		$where2=[];
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Invoices.date_created >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Invoices.date_created <=']=$To;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where1['InvoiceBookings.supplier_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where1['InvoiceBookings.supplier_date <=']=$To;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where3['Ledgers.transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where3['Ledgers.transaction_date <=']=$To;
		}
		
		
		if(!empty($item_name)){ 
			$invoices = $this->Invoices->find()
						->contain(['Customers','InvoiceRows'])
						->matching('InvoiceRows.Items', function ($q) use($item_name) { 
								return $q->where(['InvoiceRows.item_id' => $item_name]);
								}
						)
						->order(['Invoices.id' => 'DESC'])
						->where($where)
						->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount = '=>0,'invoice_type'=>'GST']);
			
			$invoiceGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes']);
				
			$interStateInvoice = $this->Invoices->find()->where($where)
					->contain(['Customers','InvoiceRows'])
					->matching('InvoiceRows.Items', function ($q) use($item_name) { 
								return $q->where(['InvoiceRows.item_id' => $item_name]);
								}
						)
			->order(['Invoices.id' => 'DESC'])->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount > '=>0,'invoice_type'=>'GST']);
			
			$invoiceIGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'igst'=>'Yes']);
			
			$invoiceBookings = $this->Invoices->InvoiceBookings->find()->where($where1)
			->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
					return $q->where(['InvoiceBookingRows.igst = '=>0]);
			}])
			->matching('InvoiceBookingRows.Items', function ($q) use($item_name) { 
								return $q->where(['InvoiceBookingRows.item_id' => $item_name]);
								}
						)
			->order(['InvoiceBookings.id' => 'DESC'])->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
			
			$invoiceBookingsGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes']);
			
			$invoiceBookingsInterState = $this->Invoices->InvoiceBookings->find()->where($where1)->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
											return $q->where(['InvoiceBookingRows.igst != '=>0]);
			}])
			->matching('InvoiceBookingRows.Items', function ($q) use($item_name) { 
								return $q->where(['InvoiceBookingRows.item_id' => $item_name]);
								}
						)
			->order(['InvoiceBookings.id' => 'DESC'])->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
			
			$PurchaseIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
			
			$SaleTaxeIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				//pr($SaleTaxeIgst->toArray()); exit;
				
				$voucherLedgerDetailIgst=[];
				$voucherSourceIgst=[];
				$LedgerAccountDetailIgst=[];
				foreach($SaleTaxeIgst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ 
								$voucherLedgerDetailIgst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceIgst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
						
						$LedgerAccountDetailIgst[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				} 
				$SaleTaxeGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes'])->orWhere(['account_second_subgroup_id'=>6,'sgst'=>'Yes']);
				$voucherLedgerDetailsGst=[];
				$voucherSourceGst=[];
				$LedgerAccountDetails=[];
			//	pr($SaleTaxeGst->toArray()); 
				foreach($SaleTaxeGst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{ 
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{
								$voucherLedgerDetailsGst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceGst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
					$LedgerAccountDetails[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				}
				
		}
		else {
			if(!empty($item_category) && empty($item_group) && empty($item_sub_group)){  
			$invoices = $this->Invoices->find()
						->contain(['Customers','InvoiceRows'=>['Items']])
						->matching(
							'InvoiceRows.Items', function ($q) use($item_category) { 
								return $q->where(['Items.item_category_id' => $item_category]);
								}
						)
						->order(['Invoices.id' => 'DESC'])
						->where($where)
						->group('Invoices.id')
						->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount = '=>0,'invoice_type'=>'GST']);
						
			$invoiceGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes']);			
					
					//$invoices=array_unique($invoices);
			$interStateInvoice = $this->Invoices->find()->where($where)
					->contain(['Customers','InvoiceRows'])
					->matching('InvoiceRows.Items', function ($q) use($item_category) { 
								return $q->where(['Items.item_category_id' => $item_category]);
								}
						)
					->order(['Invoices.id' => 'DESC'])
					->group('Invoices.id')
					->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount > '=>0,'invoice_type'=>'GST']);
					
			$invoiceIGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'igst'=>'Yes']);		
					
					
			$invoiceBookings = $this->Invoices->InvoiceBookings->find()->where($where1)
						->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
								return $q->where(['InvoiceBookingRows.igst = '=>0]);
						}])
						->matching('InvoiceBookingRows.Items', function ($q) use($item_category) { 
											return $q->where(['Items.item_category_id' => $item_category]);
											}
									)
						->order(['InvoiceBookings.id' => 'DESC'])
						->group('InvoiceBookings.id')
						->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
			
			$invoiceBookingsGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes']);
			
			$invoiceBookingsInterState = $this->Invoices->InvoiceBookings->find()->where($where1)->			contain(['Vendors','InvoiceBookingRows'=>function($q){ 
											return $q->where(['InvoiceBookingRows.igst != '=>0]);
					}])
					->matching('InvoiceBookingRows.Items', function ($q) use($item_category) { 
										return $q->where(['Items.item_category_id' => $item_category]);
										}
								)
					->order(['InvoiceBookings.id' => 'DESC'])
					->group('InvoiceBookings.id')
					->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
			
			$PurchaseIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				
				
			$SaleTaxeIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				//pr($SaleTaxeIgst->toArray()); exit;
				
				$voucherLedgerDetailIgst=[];
				$voucherSourceIgst=[];
				$LedgerAccountDetailIgst=[];
				foreach($SaleTaxeIgst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ 
								$voucherLedgerDetailIgst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceIgst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
						
						$LedgerAccountDetailIgst[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				} 
				$SaleTaxeGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes'])->orWhere(['account_second_subgroup_id'=>6,'sgst'=>'Yes']);
				$voucherLedgerDetailsGst=[];
				$voucherSourceGst=[];
				$LedgerAccountDetails=[];
			//	pr($SaleTaxeGst->toArray()); 
				foreach($SaleTaxeGst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{ 
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{
								$voucherLedgerDetailsGst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceGst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
					$LedgerAccountDetails[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				}
			}
		else if(!empty($item_group) && empty($item_sub_group) && empty($item_category)){ 
			$invoices = $this->Invoices->find()
						->contain(['Customers','InvoiceRows'=>['Items']])
						->matching(
							'InvoiceRows.Items', function ($q) use($item_group) { 
								return $q->where(['Items.item_group_id' => $item_group]);
								}
						)
						->order(['Invoices.id' => 'DESC'])
						->where($where)
						->group('Invoices.id')
						->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount = '=>0,'invoice_type'=>'GST']);
						
			$invoiceGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes']);			
			
			$interStateInvoice = $this->Invoices->find()->where($where)
					->contain(['Customers','InvoiceRows'])
					->matching('InvoiceRows.Items', function ($q) use($item_group) { 
								return $q->where(['Items.item_group_id' => $item_group]);
								}
						)
					->order(['Invoices.id' => 'DESC'])
					->group('Invoices.id')
					->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount > '=>0,'invoice_type'=>'GST']);
					
			$invoiceIGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'igst'=>'Yes']);		
			
			$invoiceBookings = $this->Invoices->InvoiceBookings->find()->where($where1)
			->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
					return $q->where(['InvoiceBookingRows.igst = '=>0]);
			}])
			->matching('InvoiceBookingRows.Items', function ($q) use($item_group) { 
								return $q->where(['Items.item_group_id' => $item_group]);
								}
						)
			->order(['InvoiceBookings.id' => 'DESC'])
			->group('InvoiceBookings.id')
			->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
			
			$invoiceBookingsGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes']);
				
			$invoiceBookingsInterState = $this->Invoices->InvoiceBookings->find()->where($where1)->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
											return $q->where(['InvoiceBookingRows.igst != '=>0]);
			}])
			->matching('InvoiceBookingRows.Items', function ($q) use($item_group) { 
								return $q->where(['Items.item_group_id' => $item_group]);
								}
						)
			->order(['InvoiceBookings.id' => 'DESC'])
			->group('InvoiceBookings.id')
			->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
			
			$SaleTaxeIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				//pr($SaleTaxeIgst->toArray()); exit;
				
				$voucherLedgerDetailIgst=[];
				$voucherSourceIgst=[];
				$LedgerAccountDetailIgst=[];
				foreach($SaleTaxeIgst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ 
								$voucherLedgerDetailIgst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceIgst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
						
						$LedgerAccountDetailIgst[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				} 
				$SaleTaxeGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes'])->orWhere(['account_second_subgroup_id'=>6,'sgst'=>'Yes']);
				$voucherLedgerDetailsGst=[];
				$voucherSourceGst=[];
				$LedgerAccountDetails=[];
			//	pr($SaleTaxeGst->toArray()); 
				foreach($SaleTaxeGst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{ 
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{
								$voucherLedgerDetailsGst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceGst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
					$LedgerAccountDetails[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				}
				$PurchaseIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				
				
		}
		else if(!empty($item_sub_group && empty($item_group) && empty($item_category))){
			$invoices = $this->Invoices->find()
						->contain(['Customers','InvoiceRows'=>['Items']])
						->matching(
							'InvoiceRows.Items', function ($q) use($item_sub_group) { 
								return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
								}
						)
						->order(['Invoices.id' => 'DESC'])
						->group('Invoices.id')
						->where($where)
						->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount = '=>0,'invoice_type'=>'GST']);
			
			$invoiceGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes']);	
						
			$interStateInvoice = $this->Invoices->find()->where($where)
								->contain(['Customers','InvoiceRows'])
								->matching('InvoiceRows.Items', function ($q) use($item_sub_group) { 
											return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
											}
									)
								->order(['Invoices.id' => 'DESC'])
								->group('Invoices.id')
								->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount > '=>0,'invoice_type'=>'GST']);
			
			$invoiceIGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'igst'=>'Yes']);	
			
			$invoiceBookings = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
								return $q->where(['InvoiceBookingRows.igst = '=>0]);
							}])
							->matching('InvoiceBookingRows.Items', function ($q) use($item_sub_group) { 
											return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
											}
								)
							->order(['InvoiceBookings.id' => 'DESC'])
							->group('InvoiceBookings.id')
							->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
			
			$invoiceBookingsGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes']);	
			
			$invoiceBookingsInterState = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
												return $q->where(['InvoiceBookingRows.igst != '=>0]);
							}])
							->matching('InvoiceBookingRows.Items', function ($q) use($item_sub_group) { 
											return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
											}
										)
							->order(['InvoiceBookings.id' => 'DESC'])
							->group('InvoiceBookings.id')
							->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
							
			$SaleTaxeIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				//pr($SaleTaxeIgst->toArray()); exit;
				
				$voucherLedgerDetailIgst=[];
				$voucherSourceIgst=[];
				$LedgerAccountDetailIgst=[];
				foreach($SaleTaxeIgst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ 
								$voucherLedgerDetailIgst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceIgst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
						
						$LedgerAccountDetailIgst[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				} 
				$SaleTaxeGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes'])->orWhere(['account_second_subgroup_id'=>6,'sgst'=>'Yes']);
				$voucherLedgerDetailsGst=[];
				$voucherSourceGst=[];
				$LedgerAccountDetails=[];
			//	pr($SaleTaxeGst->toArray()); 
				foreach($SaleTaxeGst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{ 
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{
								$voucherLedgerDetailsGst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceGst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
					$LedgerAccountDetails[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				}
				$PurchaseIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				
				
		}else if(!empty($item_category) && !empty($item_group) && !empty($item_sub_group)){  
				$invoices = $this->Invoices->find()
						->contain(['Customers','InvoiceRows'=>['Items']])
						->matching(
							'InvoiceRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
								return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
								}
						)
						->order(['Invoices.id' => 'DESC'])
						->group('Invoices.id')
						->where($where)
						->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount = '=>0,'invoice_type'=>'GST']);
				
				$invoiceGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes']);
				
				$interStateInvoice = $this->Invoices->find()->where($where)
								->contain(['Customers','InvoiceRows'])
								->matching('InvoiceRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
										}
									)
								->order(['Invoices.id' => 'DESC'])
								->group('Invoices.id')
								->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount > '=>0,'invoice_type'=>'GST']);
				
					$invoiceIGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'igst'=>'Yes']);
			
				$invoiceBookings = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
								return $q->where(['InvoiceBookingRows.igst = '=>0]);
							}])
							->matching('InvoiceBookingRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
										}
								)
							->order(['InvoiceBookings.id' => 'DESC'])
							->group('InvoiceBookings.id')
							->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
							
				$invoiceBookingsGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes']);			
			
				$invoiceBookingsInterState = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
												return $q->where(['InvoiceBookingRows.igst != '=>0]);
							}])
							->matching('InvoiceBookingRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
								}
										)
							->order(['InvoiceBookings.id' => 'DESC'])
							->group('InvoiceBookings.id')
							->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
				$SaleTaxeIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				//pr($SaleTaxeIgst->toArray()); exit;
				
				$voucherLedgerDetailIgst=[];
				$voucherSourceIgst=[];
				$LedgerAccountDetailIgst=[];
				foreach($SaleTaxeIgst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ 
								$voucherLedgerDetailIgst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceIgst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
						
						$LedgerAccountDetailIgst[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				} 
				$SaleTaxeGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes'])->orWhere(['account_second_subgroup_id'=>6,'sgst'=>'Yes']);
				$voucherLedgerDetailsGst=[];
				$voucherSourceGst=[];
				$LedgerAccountDetails=[];
			//	pr($SaleTaxeGst->toArray()); 
				foreach($SaleTaxeGst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{ 
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{
								$voucherLedgerDetailsGst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceGst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
					$LedgerAccountDetails[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				}
				$PurchaseIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				
			}else if(!empty($item_category) && !empty($item_group) && empty($item_sub_group)){  
				
				$invoices = $this->Invoices->find()
							->contain(['Customers','InvoiceRows'=>['Items']])
							->matching(
								'InvoiceRows.Items', function ($q) use($item_category,$item_group) { 
									return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
									}
							)
							->order(['Invoices.id' => 'DESC'])
							->group('Invoices.id')
							->where($where)
							->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount = '=>0,'invoice_type'=>'GST']);
							
				$invoiceGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes']);
				
				$interStateInvoice = $this->Invoices->find()->where($where)
									->contain(['Customers','InvoiceRows'])
									->matching('InvoiceRows.Items', function ($q) use($item_category,$item_group) { 
												return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
											}
										)
									->order(['Invoices.id' => 'DESC'])
									->group('Invoices.id')
									->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount > '=>0,'invoice_type'=>'GST']);
			
				$invoiceIGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'igst'=>'Yes']);
			
				$invoiceBookings = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
								return $q->where(['InvoiceBookingRows.igst = '=>0]);
							}])
							->matching('InvoiceBookingRows.Items', function ($q) use($item_category,$item_group) { 
									return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
									}
								)
							->order(['InvoiceBookings.id' => 'DESC'])
							->group('InvoiceBookings.id')
							->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
							
				$invoiceBookingsGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes']);			
			
				$invoiceBookingsInterState = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
												return $q->where(['InvoiceBookingRows.igst != '=>0]);
							}])
							->matching('InvoiceBookingRows.Items', function ($q) use($item_category,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
									}
										)
							->order(['InvoiceBookings.id' => 'DESC'])
							->group('InvoiceBookings.id')
							->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
							
				$PurchaseIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				
				
				
				$SaleTaxeIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				//pr($SaleTaxeIgst->toArray()); exit;
				
				$voucherLedgerDetailIgst=[];
				$voucherSourceIgst=[];
				$LedgerAccountDetailIgst=[];
				foreach($SaleTaxeIgst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ 
								$voucherLedgerDetailIgst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceIgst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
						
						$LedgerAccountDetailIgst[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				} 
				$SaleTaxeGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes'])->orWhere(['account_second_subgroup_id'=>6,'sgst'=>'Yes']);
				$voucherLedgerDetailsGst=[];
				$voucherSourceGst=[];
				$LedgerAccountDetails=[];
			//	pr($SaleTaxeGst->toArray()); 
				foreach($SaleTaxeGst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{ 
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{
								$voucherLedgerDetailsGst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceGst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
					$LedgerAccountDetails[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				}
			}
			else{ 
				$invoices = $this->Invoices->find()
						->contain(['Customers','InvoiceRows'=>['Items']])
						->order(['Invoices.id' => 'DESC'])
						->where($where)
						->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount = '=>0,'invoice_type'=>'GST']);
						
				$invoiceGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes']);
				
						
				$interStateInvoice = $this->Invoices->find()->where($where)
									->contain(['Customers','InvoiceRows'])
									->order(['Invoices.id' => 'DESC'])->where(['Invoices.company_id'=>$st_company_id,'total_igst_amount > '=>0,'invoice_type'=>'GST']);
			
				$invoiceIGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>5,'igst'=>'Yes']);
				
				
				
				$invoiceBookings = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
								return $q->where(['InvoiceBookingRows.igst = '=>0]);
							}])
							->order(['InvoiceBookings.id' => 'DESC'])->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
				//pr($invoiceBookings->toArray()); exit;
				
				$invoiceBookingsGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes']);
				
				$PurchaseIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				
				
				//pr($PurchaseIgst->toArray()); exit;
				$invoiceBookingsInterState = $this->Invoices->InvoiceBookings->find()->where($where1)
							->contain(['Vendors','InvoiceBookingRows'=>function($q){ 
												return $q->where(['InvoiceBookingRows.igst != '=>0]);
							}])
							->order(['InvoiceBookings.id' => 'DESC'])->where(['InvoiceBookings.company_id'=>$st_company_id,'gst'=>'yes']);
							
							
				$SaleTaxeIgst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'igst'=>'Yes']);
				//pr($SaleTaxeIgst->toArray()); exit;
				
				$voucherLedgerDetailIgst=[];
				$voucherSourceIgst=[];
				$LedgerAccountDetailIgst=[];
				foreach($SaleTaxeIgst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ 
								$voucherLedgerDetailIgst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceIgst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
						
						$LedgerAccountDetailIgst[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				} 
				
				$SaleTaxeGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)->where(['account_second_subgroup_id'=>6,'cgst'=>'Yes'])->orWhere(['account_second_subgroup_id'=>6,'sgst'=>'Yes']);
				$voucherLedgerDetailsGst=[];
				$voucherSourceGst=[];
				$LedgerAccountDetails=[];
	
				foreach($SaleTaxeGst as $SaleTaxe)
				{ 
					$LedgerAccount = $this->Invoices->LedgerAccounts->find()->where(['source_id'=>$SaleTaxe->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id,'account_second_subgroup_id'=>6])->first();
					
					$voucherLedgerDatas = $this->Invoices->Ledgers->find()->where($where3)->where(['Ledgers.ledger_account_id'=>$LedgerAccount['id'],'Ledgers.company_id'=>$st_company_id])->contain(['LedgerAccounts'])->toArray();
					
						foreach($voucherLedgerDatas as $voucherLedgerData)
						{   
							if($voucherLedgerData->voucher_source=="Payment Voucher" ||$voucherLedgerData->voucher_source=="Contra Voucher" ||$voucherLedgerData->voucher_source=="Journal Voucher" ||$voucherLedgerData->voucher_source=="Non Print Payment Voucher" ||$voucherLedgerData->voucher_source=="Petty Cash Payment Voucher" ||$voucherLedgerData->voucher_source=="Receipt Voucher" )
							{ //pr($voucherLedgerData);
								$voucherLedgerDetailsGst[$voucherLedgerData->id]=$voucherLedgerData;
								$voucherSourceGst[$voucherLedgerData->id]=$voucherLedgerData->voucher_source;
							}
						} 
					$LedgerAccountDetails[$LedgerAccount->id]=$SaleTaxe->invoice_description;
				}
			}
		}
		
		
		$this->set(compact('From','To','salesman_id','item_category','item_group','item_sub_group','item_name'));
		$this->set(compact('invoices','SalesMans','SalesOrders','interStateInvoice','invoiceBookings','invoiceBookingsInterState','Items','ItemGroups','ItemCategories','ItemSubGroups','voucherLedgerDetails','voucherSource','voucherLedgerDetailsGst','voucherSourceGst','voucherLedgerDetailIgst','voucherSourceIgst','SaleTaxeGst','LedgerAccountDetails','LedgerAccountDetailIgst','PurchaseIgst','PurchaseCgst','invoiceIGst','invoiceGst','invoiceBookingsGst','url'));
	}
	
	public function salesManReport(){
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$salesman_id=$this->request->query('salesman_name');
		$Districts_id=$this->request->query('Districts_id');
		$item_name=$this->request->query('item_name');
		$item_category=$this->request->query('item_category');
		$item_group=$this->request->query('item_group_id');
		$item_sub_group=$this->request->query('item_sub_group_id');
		$where=[];
		$where1=[];
		$where2=[];
		$where3=[];
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Invoices.date_created >=']=$From;
			$where1['SalesOrders.created_on >=']=$From;
			$where2['Quotations.employee_id']=$From;
			$where3['Quotations.created_on >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Invoices.date_created <=']=$To;
			$where1['SalesOrders.created_on <=']=$To;
			$where2['Quotations.employee_id']=$To;
			$where3['Quotations.created_on <=']=$To;
		}
		if(!empty($salesman_id)){
			$where['Invoices.created_by']=$salesman_id;
			$where1['SalesOrders.employee_id']=$salesman_id;
			$where2['Quotations.employee_id']=$salesman_id;
			$where3['Quotations.employee_id']=$salesman_id;
		} 
		
		
		
		//$this->set(compact('From','To','salesman_id'));
		
		if(!empty($item_name)){ 
			$invoices = $this->Invoices->find()
						->contain(['Customers','InvoiceRows'])
						->matching('InvoiceRows.Items', function ($q) use($item_name) { 
								return $q->where(['InvoiceRows.item_id' => $item_name]);
								}
						)
						->order(['Invoices.id' => 'DESC'])
						->where($where)
						->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);
						
			
			$invoicesGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);	
						
			$SalesOrders = $this->Invoices->SalesOrders->find()
								->contain(['Customers','SalesOrderRows'])
								->matching('SalesOrderRows.Items', function ($q) use($item_name) { 
									return $q->where(['SalesOrderRows.item_id' => $item_name]);
									})		
								->order(['SalesOrders.id' => 'DESC'])
								->where($where1)
								->where(['SalesOrders.company_id'=>$st_company_id,'gst'=>'yes']);			
			

			$OpenQuotations =$this->Invoices->SalesOrders->Quotations->find()
								  ->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
								  ->matching('QuotationRows.Items', function ($q) use($item_name) { 
									return $q->where(['QuotationRows.item_id' => $item_name]);
									})
								  ->order(['Quotations.created_on' => 'DESC'])
								  ->where($where2)
								  ->where(['Quotations.status IN'=>'Pending','company_id'=>$st_company_id]);
								  
			$ClosedQuotations =$this->Invoices->SalesOrders->Quotations->find()
									->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
									 ->matching('QuotationRows.Items', function ($q) use($item_name) { 
									return $q->where(['QuotationRows.item_id' => $item_name]);
									})
									->order(['Quotations.created_on' => 'DESC'])
									->where($where3)
									->where(['Quotations.status IN'=>'Closed','company_id'=>$st_company_id]);
		}else { 
			if(!empty($item_category) && empty($item_group) && empty($item_sub_group)){  
				$invoices = 	$this->Invoices->find()
									->contain(['Customers','InvoiceRows'])
									->matching(
										'InvoiceRows.Items', function ($q) use($item_category) { 
											return $q->where(['Items.item_category_id' => $item_category]);
											}
									)
									->order(['Invoices.id' => 'DESC'])
									->group('Invoices.id')
									->where($where)
									->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);
									
				$invoicesGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);					
					
				$SalesOrders = $this->Invoices->SalesOrders->find()
									->contain(['Customers','SalesOrderRows'])
									->matching('SalesOrderRows.Items', function ($q) use($item_category) { 
										return $q->where(['Items.item_category_id' => $item_category]);
										})		
									->order(['SalesOrders.id' => 'DESC'])
									->group('SalesOrders.id')
									->where($where1)
									->where(['SalesOrders.company_id'=>$st_company_id,'gst'=>'yes']);			
				

				$OpenQuotations =$this->Invoices->SalesOrders->Quotations->find()
									  ->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
									  ->matching('QuotationRows.Items', function ($q) use($item_category) { 
										return $q->where(['ItemCategories.id' => $item_category]);
										})
									  ->order(['Quotations.created_on' => 'DESC'])
									  ->group('Quotations.id')
									  ->where($where2)
									 ->where(['Quotations.status'=>'Pending','company_id'=>$st_company_id]);
									 
				$ClosedQuotations =$this->Invoices->SalesOrders->Quotations->find()
										->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
										 ->matching('QuotationRows.Items', function ($q) use($item_category) { 
										return $q->where(['Items.item_category_id' => $item_category]);
										})
										->order(['Quotations.created_on' => 'DESC'])
										->group('Quotations.id')
										->where($where3)
										->where(['Quotations.status'=>'Closed','company_id'=>$st_company_id]);
			}
		else if(!empty($item_group) && empty($item_sub_group) && empty($item_category)){ 
				$invoices = 	$this->Invoices->find()
									->contain(['Customers','InvoiceRows'])
									->matching(
										'InvoiceRows.Items', function ($q) use($item_group) { 
											return $q->where(['Items.item_group_id' => $item_group]);
											}
									)
									->order(['Invoices.id' => 'DESC'])
									->group('Invoices.id')
									->where($where)
									->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);
									
				$invoicesGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);					
						
				$SalesOrders = $this->Invoices->SalesOrders->find()
									->contain(['Customers','SalesOrderRows'])
									->matching('SalesOrderRows.Items', function ($q) use($item_group) { 
										return $q->where(['Items.item_group_id' => $item_group]);
										})		
									->order(['SalesOrders.id' => 'DESC'])
									->group('SalesOrders.id')
									->where($where1)
									->where(['SalesOrders.company_id'=>$st_company_id,'gst'=>'yes']);			
				

				$OpenQuotations =$this->Invoices->SalesOrders->Quotations->find()
									  ->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
									  ->matching('QuotationRows.Items', function ($q) use($item_group) { 
										return $q->where(['Items.item_group_id' => $item_group]);
										})
									  ->order(['Quotations.created_on' => 'DESC'])
									  ->group('Quotations.id')
									  ->where($where2)
									  ->where(['Quotations.status'=>'Pending','company_id'=>$st_company_id]);
									  
				$ClosedQuotations =$this->Invoices->SalesOrders->Quotations->find()
										->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
										 ->matching('QuotationRows.Items', function ($q) use($item_group) { 
										return $q->where(['Items.item_group_id' => $item_group]);
										})
										->order(['Quotations.created_on' => 'DESC'])
										->group('Quotations.id')
										->where($where3)
										->where(['Quotations.status'=>'Closed','company_id'=>$st_company_id]);
		}
		else if(!empty($item_sub_group && empty($item_group) && empty($item_category))){
			
				$invoices = 	$this->Invoices->find()
									->contain(['Customers','InvoiceRows'])
									->matching(
										'InvoiceRows.Items', function ($q) use($item_sub_group) { 
											return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
											}
									)
									->order(['Invoices.id' => 'DESC'])
									->group('Invoices.id')
									->where($where)
									->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);
									
				$invoicesGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);					
						
				$SalesOrders = $this->Invoices->SalesOrders->find()
									->contain(['Customers','SalesOrderRows'])
									->matching('SalesOrderRows.Items', function ($q) use($item_sub_group) { 
										return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
										})		
									->order(['SalesOrders.id' => 'DESC'])
									->group('SalesOrders.id')
									->where($where1)
									->where(['SalesOrders.company_id'=>$st_company_id,'gst'=>'yes']);			
				

				$OpenQuotations =$this->Invoices->SalesOrders->Quotations->find()
									  ->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
									  ->matching('QuotationRows.Items', function ($q) use($item_sub_group) { 
										return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
										})
									  ->order(['Quotations.created_on' => 'DESC'])
									  ->group('Quotations.id')
									  ->where($where2)
									  ->where(['Quotations.status'=>'Pending','company_id'=>$st_company_id]);
									  
				$ClosedQuotations =$this->Invoices->SalesOrders->Quotations->find()
										->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
										 ->matching('QuotationRows.Items', function ($q) use($item_sub_group) { 
										return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
										})
										->order(['Quotations.created_on' => 'DESC'])
										->group('Quotations.id')
										->where($where3)
										->where(['Quotations.status'=>'Closed','company_id'=>$st_company_id]);
			
			
		}else if(!empty($item_category) && !empty($item_group) && !empty($item_sub_group)){  
				
				$invoices = 	$this->Invoices->find()
									->contain(['Customers','InvoiceRows'])
									->matching(
										'InvoiceRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
											return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
											}
									)
									->order(['Invoices.id' => 'DESC'])
									->group('Invoices.id')
									->where($where)
									->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);
									
				$invoicesGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);					
						
				$SalesOrders = $this->Invoices->SalesOrders->find()
									->contain(['Customers','SalesOrderRows'])
									->matching('SalesOrderRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
										})		
									->order(['SalesOrders.id' => 'DESC'])
									->group('SalesOrders.id')
									->where($where1)
									->where(['SalesOrders.company_id'=>$st_company_id,'gst'=>'yes']);			
				

				$OpenQuotations =$this->Invoices->SalesOrders->Quotations->find()
									  ->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
									  ->matching('QuotationRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
										})
									  ->order(['Quotations.created_on' => 'DESC'])
									  ->group('Quotations.id')
									  ->where($where2)
									  ->where(['Quotations.status'=>'Pending','company_id'=>$st_company_id]);
									  
				$ClosedQuotations =$this->Invoices->SalesOrders->Quotations->find()
										->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
										 ->matching('QuotationRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
										})
										->order(['Quotations.created_on' => 'DESC'])
										->group('Quotations.id')
										->where($where3)
										->where(['Quotations.status'=>'Closed','company_id'=>$st_company_id]);
		
			}else if(!empty($item_category) && !empty($item_group) && empty($item_sub_group)){  
			
				$invoices = 	$this->Invoices->find()
									->contain(['Customers','InvoiceRows'])
									->matching(
										'InvoiceRows.Items', function ($q) use($item_category,$item_group) { 
											return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
											}
									)
									->order(['Invoices.id' => 'DESC'])
									->group('Invoices.id')
									->where($where)
									->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);
				
				$invoicesGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);	
						
				$SalesOrders = $this->Invoices->SalesOrders->find()
									->contain(['Customers','SalesOrderRows'])
									->matching('SalesOrderRows.Items', function ($q) use($item_category,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
										})		
									->order(['SalesOrders.id' => 'DESC'])
									->group('SalesOrders.id')
									->where($where1)
									->where(['SalesOrders.company_id'=>$st_company_id,'gst'=>'yes']);			
				

				$OpenQuotations =$this->Invoices->SalesOrders->Quotations->find()
									  ->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
									  ->matching('QuotationRows.Items', function ($q) use($item_category,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
										})
									  ->order(['Quotations.created_on' => 'DESC'])
									  ->group('Quotations.id')
									  ->where($where2)
									  ->where(['Quotations.status'=>'Pending','company_id'=>$st_company_id]);
									  
				$ClosedQuotations =$this->Invoices->SalesOrders->Quotations->find()
										->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
										 ->matching('QuotationRows.Items', function ($q) use($item_category,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
										})
										->order(['Quotations.created_on' => 'DESC'])
										 ->group('Quotations.id')
										->where($where3)
										->where(['Quotations.status'=>'Closed','company_id'=>$st_company_id]);
			}
			else{
			
				$invoices = $this->Invoices->find()->where($where)->contain(['Customers','InvoiceRows'])->order(['Invoices.id' => 'DESC'])->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);

				$invoicesGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);	
				
			//pr($invoicesGst->toArray());exit;	
			
				$SalesOrders = $this->Invoices->SalesOrders->find()->contain(['Customers','SalesOrderRows'])->order(['SalesOrders.id' => 'DESC'])->where($where1)->where(['SalesOrders.company_id'=>$st_company_id,'gst'=>'yes']);

				//Opened Quotation code start here 
					$OpenQuotations =$this->Invoices->SalesOrders->Quotations->find()
										  ->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
										  ->order(['Quotations.created_on' => 'DESC'])
										  ->where($where2)
										  ->where(['Quotations.status'=>'Pending','company_id'=>$st_company_id]);
				//closed Quotation code start here 
					$ClosedQuotations =$this->Invoices->SalesOrders->Quotations->find()
											->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
											->order(['Quotations.created_on' => 'DESC'])
											->where($where3)
											->where(['Quotations.status'=>'Closed','company_id'=>$st_company_id]);
				
			}
		}
		$this->set(compact('From','To','salesman_id','item_category','item_group','item_sub_group','item_name'));
		//pr($SalesOrders->toArray()); exit;
		$SalesMans = $this->Invoices->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])
			
			->matching(
					'Departments', function ($q) {
						return $q->where(['Departments.id' =>1]);
					}
		); 
		$ItemCategories = $this->Invoices->Items->ItemCategories->find('list')->order(['ItemCategories.name' => 'ASC']);
		$ItemGroups = $this->Invoices->Items->ItemGroups->find('list')->order(['ItemGroups.name' => 'ASC']);
		$ItemSubGroups = $this->Invoices->Items->ItemSubGroups->find('list')->order(['ItemSubGroups.name' => 'ASC']);
		$CustomerSegments = $this->Invoices->Customers->CustomerSegs->find('list')->order(['CustomerSegs.name' => 'ASC']);
		$CustomerGroups = $this->Invoices->Customers->CustomerGroups->find('list')->order(['CustomerGroups.name' => 'ASC']);
		$GstTaxes = $this->Invoices->SaleTaxes->find()->where(['SaleTaxes.account_second_subgroup_id'=>5,'cgst'=>'yes'])->orwhere(['SaleTaxes.account_second_subgroup_id'=>5,'igst'=>'yes'])->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				);
		//pr($CustomerSegments->toArray());exit;		
		$Items = $this->Invoices->Items->find('list')->order(['Items.name' => 'ASC']);
		$States = $this->Invoices->Customers->Districts->States->find('list')->order(['States.name' => 'ASC']);
		$Districts = $this->Invoices->Customers->Districts->find('list')->order(['Districts.district' => 'ASC']);
		//pr($States->toArray());exit;		
		$this->set(compact('invoices','SalesMans','SalesOrders','OpenQuotations','ClosedQuotations','ItemCategories','ItemGroups','ItemSubGroups','Items','GstTaxes','invoicesGst','url','States','CustomerGroups','Districts','CustomerSegments'));
	}
	
	
	public function newSalesReport(){
	
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$Customer_segment_id=$this->request->query('Customer_segment_id');
		$Customer_group_id=$this->request->query('Customer_group_id');
		$States_id=$this->request->query('States_id');
		$Districts_id=$this->request->query('Districts_id');

		$where=[];
		$where1=[];
		$where2=[];
		$where3=[];
		$District=[];
		$States=[];
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Invoices.date_created >=']=$From;
			$where1['SalesOrders.created_on >=']=$From;
			$where2['Quotations.employee_id']=$From;
			$where3['Quotations.created_on >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Invoices.date_created <=']=$To;
			$where1['SalesOrders.created_on <=']=$To;
			$where2['Quotations.employee_id']=$To;
			$where3['Quotations.created_on <=']=$To;

		}
		if(!empty($Districts_id)){
			$District['Customers.district_id']=$Districts_id;
		}
		if(!empty($Customer_segment_id)){
			$District['Customers.customer_seg_id']=$Customer_segment_id;
		}
		if(!empty($Customer_group_id)){
			$District['Customers.customer_group_id']=$Customer_group_id;
		}
		if(!empty($States_id)){
			$States['Districts.state_id']=$States_id;
		}
	//	pr($District); exit;
		$invoices = $this->Invoices->find()->where($where)->contain(['Customers'=>['Districts'=>['States']],'InvoiceRows'])->order(['Invoices.id' => 'DESC'])->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);
		$invoicesGst = $this->Invoices->SaleTaxes->find()
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);	
		
		if($States_id){ 
			$invoices->matching(
						'Customers.Districts.States', function ($q) use($States) {
							return $q->where($States);
						} 
					);
			$invoices->matching(
						'Customers.Districts', function ($q) use($District) {
							return $q->where($District);
						} 
					);
					//pr($invoices->toArray()); exit;
			$invoicesGst->matching(
						'SaleTaxCompanies', function ($q) use($st_company_id) {
							return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
						} 
					);
		}else{
			$invoices->matching(
						'Customers.Districts', function ($q) use($District) {
							return $q->where($District);
						} 
					);
			$invoicesGst->matching(
						'SaleTaxCompanies', function ($q) use($st_company_id) {
							return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
						} 
					);
		}
		//pr($invoicesGst->toArray());exit;		
		$CustomerSegments = $this->Invoices->Customers->CustomerSegs->find('list')->order(['CustomerSegs.name' => 'ASC']);
		$CustomerGroups = $this->Invoices->Customers->CustomerGroups->find('list')->order(['CustomerGroups.name' => 'ASC']);
		$States = $this->Invoices->Customers->Districts->States->find('list')->order(['States.name' => 'ASC']);
		$Districts = $this->Invoices->Customers->Districts->find('list')->order(['Districts.district' => 'ASC']);
		//pr($States->toArray());exit;		
		$this->set(compact('States','Districts','CustomerSegments','CustomerGroups','States','Districts','invoices','invoicesGst','From','To','url','Districts_id','Customer_segment_id','States_id','Customer_group_id'));
	}
	public function SelectDistrict($state_id=null){
		$this->viewBuilder()->layout('');
		$Districts = $this->Invoices->Customers->Districts->find('list')->where(['Districts.state_id'=>$state_id])->order(['Districts.district' => 'ASC']);
		//pr($Districts->toArray()); exit;
		$this->set(compact('Districts'));
		//pr($States_id); exit;
	}
	public function salesManExcelExport(){
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$salesman_id=$this->request->query('salesman_name');
		
		$item_name=$this->request->query('item_name');
		$item_category=$this->request->query('item_category');
		$item_group=$this->request->query('item_group_id');
		$item_sub_group=$this->request->query('item_sub_group_id');
		$where=[];
		$where1=[];
		$where2=[];
		$where3=[];
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Invoices.date_created >=']=$From;
			$where1['SalesOrders.created_on >=']=$From;
			$where3['Quotations.created_on >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Invoices.date_created <=']=$To;
			$where1['SalesOrders.created_on <=']=$To;
			$where3['Quotations.created_on <=']=$To;
		}
		if(!empty($salesman_id)){
			$where['Invoices.employee_id']=$salesman_id;
			$where1['SalesOrders.employee_id']=$salesman_id;
			$where2['Quotations.employee_id']=$salesman_id;
			$where3['Quotations.employee_id']=$salesman_id;
		}
		
		/* pr($where);exit; */
		//$this->set(compact('From','To','salesman_id'));
		
		if(!empty($item_name)){ 
			$invoices = $this->Invoices->find()
						->contain(['Customers','InvoiceRows'])
						->matching('InvoiceRows.Items', function ($q) use($item_name) { 
								return $q->where(['InvoiceRows.item_id' => $item_name]);
								}
						)
						->order(['Invoices.id' => 'DESC'])
						->where($where)
						->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);
			
			$invoicesGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);	
						
			$SalesOrders = $this->Invoices->SalesOrders->find()
								->contain(['Customers','SalesOrderRows'])
								->matching('SalesOrderRows.Items', function ($q) use($item_name) { 
									return $q->where(['SalesOrderRows.item_id' => $item_name]);
									})		
								->order(['SalesOrders.id' => 'DESC'])
								->where($where1)
								->where(['SalesOrders.company_id'=>$st_company_id,'gst'=>'yes']);			
			

			$OpenQuotations =$this->Invoices->SalesOrders->Quotations->find()
								  ->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
								  ->matching('QuotationRows.Items', function ($q) use($item_name) { 
									return $q->where(['QuotationRows.item_id' => $item_name]);
									})
								  ->order(['Quotations.created_on' => 'DESC'])
								  ->where($where2)
								  ->where(['Quotations.status IN'=>'Pending','company_id'=>$st_company_id]);
								  
			$ClosedQuotations =$this->Invoices->SalesOrders->Quotations->find()
									->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
									 ->matching('QuotationRows.Items', function ($q) use($item_name) { 
									return $q->where(['QuotationRows.item_id' => $item_name]);
									})
									->order(['Quotations.created_on' => 'DESC'])
									->where($where3)
									->where(['Quotations.status IN'=>'Closed','company_id'=>$st_company_id]);
		}else {
			if(!empty($item_category) && empty($item_group) && empty($item_sub_group)){  
			
				$invoices = 	$this->Invoices->find()
									->contain(['Customers','InvoiceRows'])
									->matching(
										'InvoiceRows.Items', function ($q) use($item_category) { 
											return $q->where(['Items.item_category_id' => $item_category]);
											}
									)
									->order(['Invoices.id' => 'DESC'])
									->group('Invoices.id')
									->where($where)
									->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);
									
				$invoicesGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);					
					
				$SalesOrders = $this->Invoices->SalesOrders->find()
									->contain(['Customers','SalesOrderRows'])
									->matching('SalesOrderRows.Items', function ($q) use($item_category) { 
										return $q->where(['Items.item_category_id' => $item_category]);
										})		
									->order(['SalesOrders.id' => 'DESC'])
									->group('SalesOrders.id')
									->where($where1)
									->where(['SalesOrders.company_id'=>$st_company_id,'gst'=>'yes']);			
				

				$OpenQuotations =$this->Invoices->SalesOrders->Quotations->find()
									  ->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
									  ->matching('QuotationRows.Items', function ($q) use($item_category) { 
										return $q->where(['ItemCategories.id' => $item_category]);
										})
									  ->order(['Quotations.created_on' => 'DESC'])
									  ->group('Quotations.id')
									  ->where($where2)
									 ->where(['Quotations.status'=>'Pending','company_id'=>$st_company_id]);
									 
				$ClosedQuotations =$this->Invoices->SalesOrders->Quotations->find()
										->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
										 ->matching('QuotationRows.Items', function ($q) use($item_category) { 
										return $q->where(['Items.item_category_id' => $item_category]);
										})
										->order(['Quotations.created_on' => 'DESC'])
										->group('Quotations.id')
										->where($where3)
										->where(['Quotations.status'=>'Closed','company_id'=>$st_company_id]);
			}
		else if(!empty($item_group) && empty($item_sub_group) && empty($item_category)){ 
				$invoices = 	$this->Invoices->find()
									->contain(['Customers','InvoiceRows'])
									->matching(
										'InvoiceRows.Items', function ($q) use($item_group) { 
											return $q->where(['Items.item_group_id' => $item_group]);
											}
									)
									->order(['Invoices.id' => 'DESC'])
									->group('Invoices.id')
									->where($where)
									->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);
									
				$invoicesGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);					
						
				$SalesOrders = $this->Invoices->SalesOrders->find()
									->contain(['Customers','SalesOrderRows'])
									->matching('SalesOrderRows.Items', function ($q) use($item_group) { 
										return $q->where(['Items.item_group_id' => $item_group]);
										})		
									->order(['SalesOrders.id' => 'DESC'])
									->group('SalesOrders.id')
									->where($where1)
									->where(['SalesOrders.company_id'=>$st_company_id,'gst'=>'yes']);			
				

				$OpenQuotations =$this->Invoices->SalesOrders->Quotations->find()
									  ->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
									  ->matching('QuotationRows.Items', function ($q) use($item_group) { 
										return $q->where(['Items.item_group_id' => $item_group]);
										})
									  ->order(['Quotations.created_on' => 'DESC'])
									  ->group('Quotations.id')
									  ->where($where2)
									  ->where(['Quotations.status'=>'Pending','company_id'=>$st_company_id]);
									  
				$ClosedQuotations =$this->Invoices->SalesOrders->Quotations->find()
										->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
										 ->matching('QuotationRows.Items', function ($q) use($item_group) { 
										return $q->where(['Items.item_group_id' => $item_group]);
										})
										->order(['Quotations.created_on' => 'DESC'])
										->group('Quotations.id')
										->where($where3)
										->where(['Quotations.status'=>'Closed','company_id'=>$st_company_id]);
		}
		else if(!empty($item_sub_group && empty($item_group) && empty($item_category))){
			
				$invoices = 	$this->Invoices->find()
									->contain(['Customers','InvoiceRows'])
									->matching(
										'InvoiceRows.Items', function ($q) use($item_sub_group) { 
											return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
											}
									)
									->order(['Invoices.id' => 'DESC'])
									->group('Invoices.id')
									->where($where)
									->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);
									
				$invoicesGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);					
						
				$SalesOrders = $this->Invoices->SalesOrders->find()
									->contain(['Customers','SalesOrderRows'])
									->matching('SalesOrderRows.Items', function ($q) use($item_sub_group) { 
										return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
										})		
									->order(['SalesOrders.id' => 'DESC'])
									->group('SalesOrders.id')
									->where($where1)
									->where(['SalesOrders.company_id'=>$st_company_id,'gst'=>'yes']);			
				

				$OpenQuotations =$this->Invoices->SalesOrders->Quotations->find()
									  ->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
									  ->matching('QuotationRows.Items', function ($q) use($item_sub_group) { 
										return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
										})
									  ->order(['Quotations.created_on' => 'DESC'])
									  ->group('Quotations.id')
									  ->where($where2)
									  ->where(['Quotations.status'=>'Pending','company_id'=>$st_company_id]);
									  
				$ClosedQuotations =$this->Invoices->SalesOrders->Quotations->find()
										->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
										 ->matching('QuotationRows.Items', function ($q) use($item_sub_group) { 
										return $q->where(['Items.item_sub_group_id' => $item_sub_group]);
										})
										->order(['Quotations.created_on' => 'DESC'])
										->group('Quotations.id')
										->where($where3)
										->where(['Quotations.status'=>'Closed','company_id'=>$st_company_id]);
			
			
		}else if(!empty($item_category) && !empty($item_group) && !empty($item_sub_group)){  
				
				$invoices = 	$this->Invoices->find()
									->contain(['Customers','InvoiceRows'])
									->matching(
										'InvoiceRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
											return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
											}
									)
									->order(['Invoices.id' => 'DESC'])
									->group('Invoices.id')
									->where($where)
									->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);
									
				$invoicesGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);					
						
				$SalesOrders = $this->Invoices->SalesOrders->find()
									->contain(['Customers','SalesOrderRows'])
									->matching('SalesOrderRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
										})		
									->order(['SalesOrders.id' => 'DESC'])
									->group('SalesOrders.id')
									->where($where1)
									->where(['SalesOrders.company_id'=>$st_company_id,'gst'=>'yes']);			
				

				$OpenQuotations =$this->Invoices->SalesOrders->Quotations->find()
									  ->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
									  ->matching('QuotationRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
										})
									  ->order(['Quotations.created_on' => 'DESC'])
									  ->group('Quotations.id')
									  ->where($where2)
									  ->where(['Quotations.status'=>'Pending','company_id'=>$st_company_id]);
									  
				$ClosedQuotations =$this->Invoices->SalesOrders->Quotations->find()
										->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
										 ->matching('QuotationRows.Items', function ($q) use($item_category,$item_sub_group,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category,'Items.item_sub_group_id' => $item_sub_group]);
										})
										->order(['Quotations.created_on' => 'DESC'])
										->group('Quotations.id')
										->where($where3)
										->where(['Quotations.status'=>'Closed','company_id'=>$st_company_id]);
		
			}else if(!empty($item_category) && !empty($item_group) && empty($item_sub_group)){  
			
				$invoices = 	$this->Invoices->find()
									->contain(['Customers','InvoiceRows'])
									->matching(
										'InvoiceRows.Items', function ($q) use($item_category,$item_group) { 
											return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
											}
									)
									->order(['Invoices.id' => 'DESC'])
									->group('Invoices.id')
									->where($where)
									->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);
				
				$invoicesGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);	
						
				$SalesOrders = $this->Invoices->SalesOrders->find()
									->contain(['Customers','SalesOrderRows'])
									->matching('SalesOrderRows.Items', function ($q) use($item_category,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
										})		
									->order(['SalesOrders.id' => 'DESC'])
									->group('SalesOrders.id')
									->where($where1)
									->where(['SalesOrders.company_id'=>$st_company_id,'gst'=>'yes']);			
				

				$OpenQuotations =$this->Invoices->SalesOrders->Quotations->find()
									  ->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
									  ->matching('QuotationRows.Items', function ($q) use($item_category,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
										})
									  ->order(['Quotations.created_on' => 'DESC'])
									  ->group('Quotations.id')
									  ->where($where2)
									  ->where(['Quotations.status'=>'Pending','company_id'=>$st_company_id]);
									  
				$ClosedQuotations =$this->Invoices->SalesOrders->Quotations->find()
										->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
										 ->matching('QuotationRows.Items', function ($q) use($item_category,$item_group) { 
										return $q->where(['Items.item_group_id' => $item_group,'Items.item_category_id' => $item_category]);
										})
										->order(['Quotations.created_on' => 'DESC'])
										 ->group('Quotations.id')
										->where($where3)
										->where(['Quotations.status'=>'Closed','company_id'=>$st_company_id]);
			}
			else{
			
				$invoices = $this->Invoices->find()->where($where)->contain(['Customers','InvoiceRows'])->order(['Invoices.id' => 'DESC'])->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);

				$invoicesGst = $this->Invoices->SaleTaxes->find()->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				)
				->where(['account_second_subgroup_id'=>5,'cgst'=>'Yes'])
				->orWhere(['account_second_subgroup_id'=>5,'igst'=>'Yes']);	
				
			//pr($invoicesGst->toArray());exit;	
			
				$SalesOrders = $this->Invoices->SalesOrders->find()->contain(['Customers','SalesOrderRows'])->order(['SalesOrders.id' => 'DESC'])->where($where1)->where(['SalesOrders.company_id'=>$st_company_id,'gst'=>'yes']);

				//Opened Quotation code start here 
					$OpenQuotations =$this->Invoices->SalesOrders->Quotations->find()
										  ->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
										  ->order(['Quotations.created_on' => 'DESC'])
										  ->where($where2)
										  ->where(['Quotations.status'=>'Pending','company_id'=>$st_company_id]);
				//closed Quotation code start here 
					$ClosedQuotations =$this->Invoices->SalesOrders->Quotations->find()
											->contain(['Customers','QuotationRows'=>['Items'=>['ItemCategories']]])
											->order(['Quotations.created_on' => 'DESC'])
											->where($where3)
											->where(['Quotations.status'=>'Closed','company_id'=>$st_company_id]);
				
			}
		}
		$this->set(compact('From','To','salesman_id','item_category','item_group','item_sub_group','item_name'));
		
		$this->set(compact('invoices','SalesMans','SalesOrders','OpenQuotations','ClosedQuotations','ItemCategories','ItemGroups','ItemSubGroups','Items','GstTaxes','invoicesGst'));
	}
	
	public function itemSerialMismatch()
    {
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$invoices=$this->Invoices->find()->contain(['InvoiceRows'=>['Items'=>['SerialNumbers','ItemCompanies'=>function($q) use($st_company_id){
									return $q->where(['ItemCompanies.company_id' => $st_company_id]);
		}]]])->where(['Invoices.company_id' => $st_company_id]);
		$ItemSerials=[];
		foreach($invoices as $invoice){
			foreach($invoice->invoice_rows as $invoice_row){
				if(!empty($invoice_row->item->item_companies[0]['serial_number_enable'])){
					//pr(['invoice_id'=>$invoice->id,'item_id'=>$invoice_row->item_id]);
					$ItemSerialNumbers=$this->Invoices->Items->SerialNumbers->find()->where(['invoice_id'=>$invoice->id,'item_id'=>$invoice_row->item_id]);
					$ct=$ItemSerialNumbers->count(); //pr($invoice->in2);
					if($ct != $invoice_row->quantity){ 
					$ItemSerials[$invoice->in2]=$invoice_row->item->name;	
					}
				}
			}
		}?>
		
		<table border="1">
			<tr>
				<th>ID</th>
				<th>Invoice No</th>
				<th>Item Name</th>
				
			</tr>
			<?php $i=1; foreach($ItemSerials as $key=>$ItemSerial){
				
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $key; ?></td>
				<td><?php echo $ItemSerial; ?></td>
				
				
			</tr>
			<?php $i++; } ?>
		</table>
		
		
		
		<?php exit;
	}
	
	public function Fileitems($file_id=null){  
	
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$Filename=$this->Invoices->Filenames->find()->where(['id' =>$file_id])->first();
		
		$merge=$Filename->file1.'-'.$Filename->file2;
		$Invoices=$this->Invoices->find()->where(['Invoices.in3' => $merge])
						->contain(['InvoiceRows.Items'=>function($p){
									return $p->group('item_id');
					}])->toArray();
		$showitem=[];		
		foreach($Invoices as $invoice){
			foreach($invoice->invoice_rows as $invoice_row){
			$showitem[]=$invoice_row->item['name'];
			}
		}
		$showitem=array_unique($showitem); 
		//pr($showitem); exit;
		
		//pr($showitem);exit;
		$this->set(compact('Invoice','showitem','merge'));
		
		
	}
	public function getInvoiceData(){
		
		$salesOrders =$this->Invoices->SalesOrders->find()->contain(['SalesOrderRows' =>function($q){
			return $q->where(['SalesOrderRows.quantity > SalesOrderRows.processed_quantity']);
		}]);
		
		$datas=[];
		foreach($salesOrders as $salesOrder){
			//pr(sizeof($salesOrder->sales_order_rows));
			if(sizeof($salesOrder->sales_order_rows) > 0){
				$datas[]=$salesOrder->id;
				//$data[]
			}
		}
		$data1=[];
		foreach($datas as $key=>$data){
			$AccountGroupsexists = $this->Invoices->exists(['sales_order_id' => $data]);
			if($AccountGroupsexists){
			$data1[]=$data;
				
			}
		}
		
		
		/* $salesOrders = $this->Invoices->SalesOrders->find()
				->leftJoinWith('SalesOrderRows', function ($q) {
					return $q->where(['SalesOrderRows.quantity > SalesOrderRows.processed_quantity']);
				})
				->group(['SalesOrders.id'])
				->autoFields(true)
				; */
	//pr($salesOrders->toArray());
	pr($data1);
	exit;
	
	}

	public function HsnWiseReport(){
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');

		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$this->set(compact('From','To'));
		
		$where=[];
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Invoices.date_created >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Invoices.date_created <=']=$To;
		}
	//exit;
		$Invoices =$this->Invoices->find()->contain(['InvoiceRows'=>['Items'=>['Units','ItemCategories']]])->where($where)->where(['company_id'=>$st_company_id,'invoice_type'=>'GST']);
	//	pr($Invoices->toArray()); exit;
		$hsn=[];
		$quantity=[];
		$taxable_value=[];
		$item_category=[];
		$total_value=[];
		$unit=[];
		$cgst=[];
		$sgst=[];
		$igst=[];
		foreach($Invoices as $Invoice){ //pr($Invoice);
			foreach($Invoice->invoice_rows as $invoice_row){  //pr($invoice_row->item); exit;
				$hsn[$invoice_row->item->hsn_code]=$invoice_row->item->hsn_code;
				$item_category[$invoice_row->item->hsn_code]=$invoice_row->item->item_category->name;
				$unit[$invoice_row->item->hsn_code]=$invoice_row->item->unit->name;
				@$quantity[@$invoice_row->item->hsn_code]+=@$invoice_row->quantity;
				@$total_value[@$invoice_row->item->hsn_code]+=@$invoice_row->row_total;
				@$taxable_value[@$invoice_row->item->hsn_code]+=@$invoice_row->taxable_value;
				@$cgst[@$invoice_row->item->hsn_code]+=@$invoice_row->cgst_amount;
				@$sgst[@$invoice_row->item->hsn_code]+=@$invoice_row->sgst_amount;
				@$igst[@$invoice_row->item->hsn_code]+=@$invoice_row->igst_amount;
			}
		}
	$this->set(compact('hsn','item_category','quantity','total_value','taxable_value','cgst','sgst','igst','unit'));	
//pr($cgst);
// exit;
	}
	
	
	public function InvoiceHsnWise(){
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$this->set(compact('From','To'));
		$this->set(compact('From','To'));
		$where=[];
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Invoices.date_created >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Invoices.date_created <=']=$To;
		}

		$Invoices =$this->Invoices->find()->contain(['InvoiceRows'=>['Items'=>['Units'],'IvRows'=>['IvRowItems'=>['Items'=>['Units'],'ItemLedgers']]]])->where($where)->where(['Invoices.company_id'=>$st_company_id])->toArray();
		//pr($Invoices);exit;
		$this->set(compact('Invoices','url'));
	}
	
	
	public function InvoiceHsnWiseExcel(){
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$this->set(compact('From','To'));
		$this->set(compact('From','To'));
		$where=[];
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Invoices.date_created >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Invoices.date_created <=']=$To;
		}

		$Invoices =$this->Invoices->find()->contain(['InvoiceRows'=>['Items'=>['Units'],'IvRows'=>['IvRowItems'=>['Items'=>['Units'],'ItemLedgers']]]])->where($where)->where(['Invoices.company_id'=>$st_company_id])->toArray();
		//pr($Invoices);exit;
		$this->set(compact('Invoices','url'));
	}
	
	
	public function invoiceReceivableReport(){
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$this->viewBuilder()->layout('index_layout');
		$st_year_id = $session->read('st_year_id');
		
		
		$cust_name  = $this->request->query('cust_name');
		$From  = $this->request->query('From');
		$To  = $this->request->query('To');
		$reciept  = $this->request->query('reciept');
		
		$where=[];
		if(!empty($cust_name)){
			$where['Customers.customer_name LIKE']='%'.$cust_name.'%';
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Invoices.date_created >=']=$From;
		}
		
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Invoices.date_created <=']=$To;
		}
		
		
		
		
		$Invoices =$this->Invoices->find()->contain(['ReferenceDetails','Customers'])->where(['company_id'=>$st_company_id,'financial_year_id'=>$st_year_id])->where($where)->order(['Invoices.date_created'=>'DESC']);
		
		$Receiptdatas=[];
		$Receiptdatas=[];
		foreach($Invoices as $invoice){ 
			foreach($invoice->reference_details as $reference_detail){
				$References =$this->Invoices->ReferenceDetails->find()->contain(['Receipts'])->where(['ledger_account_id'=>$reference_detail->ledger_account_id,'reference_type'=>'Against Reference','reference_no'=>$reference_detail->reference_no,'receipt_id >'=>0]);
				$Receiptdatas[$invoice->id]=$References->toArray();
			}
		}
		
		$this->set(compact('Invoices','url','Receiptdatas','cust_name','From','To','reciept'));
	}
	
	/* public function InvoiceList()
		{
			$session = $this->request->session();
			$st_company_id = $session->read('st_company_id');
			$ReferenceDetails =$this->Invoices->ReferenceDetails->find()->where(['reference_type'=>'On_account','invoice_id >'=>0])->contain(['Invoices'])->toArray();?>
			<table  valign="center" width="10%" border="1">
				<tr>
					<th>Voucher No</th>
					<th>company id</th>
				</tr>
				<?php foreach($ReferenceDetails as $data){ ?>
				<tr>
					<td align="center"><?php echo $data->invoice->in2; ?></td>
					<td align="center"><?php echo $data->invoice->company_id; ?></td>
				</tr>
				<?php  }?>
			</table>
			<?php
		
			//pr($ReferenceDetails);
			exit;
		} */
	
	public function sendMail()
		{ 
		
		$data=$this->request->query('data');
		$otherData=$this->request->query('otherData');
		$id=$this->request->query('id');
		$data=json_decode($data);
		$t=sizeof($data); 
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$company_data=$this->Invoices->Companies->get($st_company_id);
		//pr($company_data->alias); exit;
		
		
		$Number = new NumberHelper(new \Cake\View\View());
		$Html = new HtmlHelper(new \Cake\View\View());
		$Text = new TextHelper(new \Cake\View\View());
		
		
		
		$invoice = $this->Invoices->get($id, [
			'contain' => ['SalesOrders','Customers'=>['Employees','CustomerContacts','Districts'=>['States']],
							'Employees',
							'Transporters',
							'Creator'=>['Designations'],
							'Companies'=> ['CompanyBanks'=> function ($q) {
								return $q
								->where(['CompanyBanks.default_bank' => 1]);
								}],
							'InvoiceRows' => ['Items'=>['ItemGroups','Units']]
						]
		]);
		//$email_to=$invoice->customer->customer_contacts[0]->email;
		//
		//pr($email_to); 
		@$email_to[]=$invoice->sales_order->dispatch_email; //pr($email_to); exit;
		if(!empty($invoice->sales_order->dispatch_email2)){
			$email_to[]=$invoice->sales_order->dispatch_email2;
		}
		if(!empty($invoice->sales_order->dispatch_email3)){
			$email_to[]=$invoice->sales_order->dispatch_email3;
		}
		//pr(@$email_to);exit;
		//pr($invoice->invoice_rows[0]->item->item_group->name); exit;
		$d=urlencode($invoice->company->name);
		$sub='Dispatch Intimation - "'. h($d) .'"';
		$sub=(urldecode($sub));
		//pr($sub); exit;
		$message_web = '
			<table  valign="center" width="62%" >
				<tr>
					<td align="left" style="font-size: 28px;font-weight: bold;color: #0685a8;">'. h($invoice->company->name) .'
					</td>
				</tr>
				<tr>
					<td  style="width: 1em; font-family:Palatino Linotype; font-size:'. h(($invoice->pdf_font_size)) .';"><br>
					'. h($invoice->customer->customer_name).'
					
					</td>
				</tr>
				<tr>
					<td  style="width: 1em; word-wrap: break-word; font-family:Palatino Linotype; font-size:'. h(($invoice->pdf_font_size)) .';">
					<p style="margin-top: 0px !important;width: 10em; word-wrap: break-word;">'. h($invoice->customer_address) .'</p>
					</td>
				</tr>
				<tr>
					<td width="50%" style=" font-family:Palatino Linotype; font-size:'. h(($invoice->pdf_font_size)) .';">Attn
					<span><b>:</b></span>
					<span>'. h($invoice->sales_order->dispatch_name) .'</span><br/>
					</td>
				</tr>
				<tr>
					<td width="50%" style=" font-family:Palatino Linotype; font-size:'. h(($invoice->pdf_font_size)) .';"><br/>Sub
					<span><b>:</b></span>
					<span style="font-family:Palatino Linotype;">Dispatch Intimation</span><br/>
					</td>
				</tr>
				<tr>
					<td width="50%" style=" font-family:Palatino Linotype; font-size:'. h(($invoice->pdf_font_size)) .';"><br/>Ref
					<span><b>:</b></span>
					<span>Your Purchase Order No.'. h($invoice->customer_po_no) .' dated '. h(date("d-m-Y",strtotime($invoice->po_date))) .'</span><br/>
					</td>
				</tr>
				<tr>
					<td style=" font-family:Palatino Linotype; font-size:'. h(($invoice->pdf_font_size)) .';"><br/>Dear Sir,</td>
				</tr>
				<tr>
					<td style=" font-family:Palatino Linotype; font-size:'. h(($invoice->pdf_font_size)) .';"><br/>With reference to above, please find herewith following dispatch documents:</td>
				</tr>
				<tr>
					<td style=" font-family:Palatino Linotype; font-size:'. h(($invoice->pdf_font_size)) .';"><br/>1. Invoice No. '. h(($invoice->in1."/"."IN-".str_pad($invoice->in2, 3, "0", STR_PAD_LEFT)."/".$invoice->in3."/".$invoice->in4)) .' dated '. h(date("d-m-Y",strtotime($invoice->date_created))).' For Rs.'. h(number_format($invoice->grand_total,2)).'/- in duplicate.</td>
				</tr>
				
				'; $message_web1=""; $p=1; if($t > 0){ 
					foreach($data as $d){
						//$terms = $this->Invoices->DispatchDocuments->get($d);
						@$message_web2.= '
						<tr>
							<td colspan="2" style=" text-align:justify !important;font-family:Palatino Linotype; font-size:'. h(($invoice->pdf_font_size)) .';"><br/>'.h((++$p)).'. '. h(($d)).'</td>
						</tr>
						';
						
						$message_web.=$message_web2;
						$message_web1.=$message_web2;
						$message_web2="";
						
					}
				
					//pr($message_web1); exit;
				} //exit;
				//pr($message_web1); exit;
				$message_web3="";
				if($otherData){
						$message_web3.= '
						<tr>
							<td style="text-align:justify !important; font-family:Palatino Linotype; font-size:' . h(($invoice->pdf_font_size)) .';"><br/>'. h(($otherData)) .'</td>
						</tr>
					'; 
					$message_web.=$message_web3;
					$message_web1.=$message_web3;
				}
				//pr($message_web1);  exit;
				//pr($message_web); exit;
				$message_web.= '
				<tr>
					
				</tr>
				<tr><td></td></tr>
				<tr><td></td></tr>
				
				<tr>
					<td style=" font-family:Palatino Linotype; font-size:'.h(($invoice->pdf_font_size)) .';"><b>Regards,</b></td>
				</tr>
				<tr>
					<td style=" font-family:Palatino Linotype; font-size:'. h(($invoice->pdf_font_size)) .';"></br>'.h($invoice->creator->name).'
					<br><span>'.h($invoice->creator->designation->name).'</span>
					</td>
				</tr>
				<tr>
					<td width="50%" style=" font-family:Palatino Linotype; font-size:'. h(($invoice->pdf_font_size)) .';"><br>Email
					<span><b>:</b></span>
					<span><b>dispatch@mogragroup.com</b></span><br/>
					<span>Website</span>
					<span><b>:</b></span>
					<span><a target="_blank" href="http://www.mogragroup.com"><span><b> www.mogragroup.com</b></span></a></span><br/>
					</td>
				</tr>
				
				
			</table>
	   
	';
		 //pr($email_to);exit;
		$email = new Email('default');
		$email->transport('gmail');
		$from_name=$company_data->alias;
		
		//$email_to="harkawat.priyanka0@gmail.com";
		
		//$cc_mail="priyankajinger143@gmail.com";
		
		$name='Invoice-'.h(($invoice->in1.'_IN'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'_'.$invoice->in3.'_'.$invoice->in4)); 
		$attachments='';
		$attachments[]='Invoice_email/'.$name.'.pdf';
		//$attachments[]="Invoice_email/Invoice-STL_IN515_BE-3421_17-18.pdf";
		//$email_to="gopalkrishanp3@gmail.com";
		//$cc_mail="harkawat.priyanka0@gmail.com";
		$cc_mail=[];
		$cc_mail[]=$invoice->creator->email;
		$cc_mail[]=$invoice->customer->employee->email;
		//$email_to="gopalkrishanp3@gmail.com";
		//$cc_mail="harkawat.priyanka0@gmail.com";
		/* $email->from(['dispatch@mogragroup.com' => $from_name])
					->to($email_to)
					->cc($cc_mail)
					->replyTo('dispatch@mogragroup.com')
					->subject($sub)
					->template('notice_send_email')
					->emailFormat('html')
					->viewVars(['content'=>$message_web])
					->attachments($attachments);
					$email->send($message_web);
					//pr($message_web); exit;
				$this->Invoices->SendEmails->deleteAll(['invoice_id' => $id]);
				$SendEmail = $this->Invoices->SendEmails->newEntity();	
				$SendEmail->send_data=$message_web1;
				$SendEmail->invoice_id=$id;
				$this->Invoices->SendEmails->save($SendEmail); */ 
				//$email_to = "dimpaljain892@gmail.com";
				if(!empty($email_to)){		
					try { 
							$email->from(['dispatch@mogragroup.com' => $from_name])
							->to($email_to)
							->cc($cc_mail)
							->replyTo('dispatch@mogragroup.com')
							->subject($sub)
							->template('notice_send_email')
							->emailFormat('html')
							->viewVars(['content'=>$message_web])
							->attachments($attachments);
					} catch (Exception $e) {
							echo 'Exception : ',  $e->getMessage(), "\n";
						} 
					if($email->send()){
						$this->Invoices->SendEmails->deleteAll(['invoice_id' => $id]);
						$SendEmail = $this->Invoices->SendEmails->newEntity();	
						$SendEmail->send_data=$message_web1;
						$SendEmail->invoice_id=$id;
						$this->Invoices->SendEmails->save($SendEmail);
					}else{
						$this->Flash->error(__('The Mail has not been Sent.'));
						return $this->redirect(['action' => 'GstConfirm/'.$id]);
					}	
				}
		//$this->Flash->success(__('The Mail has been Sent.'));
		$id = $EncryptingDecrypting->encryptData($id);
		return $this->redirect(['action' => 'GstConfirm/'.$id]);
		
// pr($id);exit;
exit;
	
	} 

}
