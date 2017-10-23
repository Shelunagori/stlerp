<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\View\Helper\NumberHelper;
use Cake\View\Helper\HtmlHelper;
use Cake\Utility\Text;
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
		//pr($sales_return); exit;
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
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
		
		if($inventory_voucher=='true'){
			$where['Invoices.inventory_voucher_status']='Pending';
			
		}else{
			if($status=='Pending' || $status==''){
				$where['status']='';
			}
			elseif($status=='Cancel'){
				$where['status']='Cancel';
			}	
		}
		
		if(!empty($items)){ 
		
			$invoices=$this->paginate($this->Invoices->find()
			->contain(['SalesOrders','InvoiceRows'=>['Items']])
			->matching(
					'InvoiceRows.Items', function ($q) use($items,$where) {
						return $q->where(['Items.id' =>$items])->where($where);
					}
				)
			->where(['Invoices.company_id'=>$st_company_id])	
			);
			
		}
		
		else if($inventory_voucher=='true'){  
			$invoices=[]; 
			$invoices=$this->paginate($this->Invoices->find()->where($where)->contain(['SalesOrders','InvoiceRows'=>['Items'=>function ($q) {
				return $q->where(['source !='=>'Purchessed']);
				}]])->where(['Invoices.company_id'=>$st_company_id,'Invoices.inventory_voucher_status'=>'Pending','Invoices.inventory_voucher_create'=>'Yes'])->order(['Invoices.id' => 'DESC']));
				//pr($invoices); exit;
		}else if($sales_return=='true'){
			
			$invoices = $this->paginate($this->Invoices->find()->contain(['SalesOrders','InvoiceRows'=>['Items']])->where($where)->where(['Invoices.company_id'=>$st_company_id])->order(['Invoices.id' => 'DESC']));
		} else{ 
			$invoices = $this->paginate($this->Invoices->find()->contain(['SalesOrders','InvoiceRows'=>['Items']])->where($where)->where(['Invoices.company_id'=>$st_company_id])->order(['Invoices.in2' => 'DESC']));
		} 
		//pr($invoices); exit;
		$Items = $this->Invoices->InvoiceRows->Items->find('list')->order(['Items.name' => 'ASC']);
		$this->set(compact('invoices','status','inventory_voucher','sales_return','InvoiceRows','Items','url'));
		
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
		
			$invoices = $this->Invoices->find()->contain(['Customers','SalesOrders','InvoiceRows'=>['Items']])->where($where)->where(['Invoices.company_id'=>$st_company_id,'invoice_type'=>'GST']);
			$status=1;
			
		}
		$this->set(compact('invoices','status','sales_return','InvoiceRows','invoice_no'));
        $this->set('_serialize', ['invoices']);
		$this->set(compact('url'));
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
		//pr($sales_return); exit;
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
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
		
		if($inventory_voucher=='true'){
			$where['Invoices.inventory_voucher_status']='Pending';
			
		}else{
			if($status=='Pending' || $status==''){
				$where['status']='';
			}
			elseif($status=='Cancel'){
				$where['status']='Cancel';
			}	
		}
		
		if($inventory_voucher=='true'){  
			$invoices=[]; 
			$invoices=$this->Invoices->find()->where($where)->contain(['Customers', 'Companies','SalesOrders','InvoiceRows'=>['Items'=>function ($q) {
				return $q->where(['source !='=>'Purchessed']);
				}]])->where(['Invoices.company_id'=>$st_company_id,'Invoices.inventory_voucher_status'=>'Pending','Invoices.inventory_voucher_create'=>'Yes'])->order(['Invoices.id' => 'DESC']);
				//pr($invoices); exit;
		}else if($sales_return=='true'){
			
			$invoices = $this->Invoices->find()->contain(['Customers', 'Companies','SalesOrders','InvoiceRows'=>['Items']])->where($where)->where(['Invoices.company_id'=>$st_company_id])->order(['Invoices.id' => 'DESC']);
		} else{ 
			$invoices = $this->Invoices->find()->contain(['SalesOrders','InvoiceRows'=>['Items'],'Customers', 'Companies'])->where($where)->where(['Invoices.company_id'=>$st_company_id])->order(['Invoices.in2' => 'DESC']);
		} 
		
		//$invoices = $this->paginate($this->Invoices->find()->where($where)->order(['Invoices.id' => 'DESC']));
		//pr($invoices);exit;
		$this->set(compact('invoices','From','To'));
		$this->set('_serialize', ['invoices']);
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
			return $this->redirect(['action' => 'confirm/'.$id]);
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
			$sales_order = $this->Invoices->SalesOrders->get($sales_order_id, [
				'contain' => ['SalesOrderRows.Items' => function ($q) use($st_company_id) {
						   return $q
								->where(['SalesOrderRows.quantity > SalesOrderRows.processed_quantity'])
								->contain(['ItemSerialNumbers'=>function($q) use($st_company_id){
									return $q->where(['ItemSerialNumbers.status' => 'In','ItemSerialNumbers.company_id' => $st_company_id]); 
								},
								'ItemCompanies'=>function($q) use($st_company_id){
									return $q->where(['ItemCompanies.company_id' => $st_company_id]);
								}]);
						},'SalesOrderRows.SaleTaxes','Companies','Customers','Employees'
					]
			]);
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
				foreach($invoice->invoice_rows as $invoice_row){
					$amt=$invoice_row->amount;
					$total_amt=$total_amt+$amt;
					$item_serial_no=$invoice_row->item_serial_number;
					$serial_no=explode(",",$item_serial_no);
					foreach($serial_no as $serial){
					$query = $this->Invoices->InvoiceRows->ItemSerialNumbers->query();
						$query->update()
							->set(['status' => 'Out','invoice_id' => $invoice->id])
							->where(['id' => $serial])
							->execute();
					}
				}
				
				
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
						$SalesOrderRow = $this->Invoices->SalesOrderRows->get($sales_order_row_id);
						$SalesOrderRow->processed_quantity=$SalesOrderRow->processed_quantity+$qty;
						$this->Invoices->SalesOrderRows->save($SalesOrderRow);
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
							if($ref_row->ref_type=='New Reference' or $ref_row->ref_type=='Advance Reference'){
								$query = $this->Invoices->ReferenceBalances->query();
								
								$query->insert(['ledger_account_id', 'reference_no', 'credit', 'debit'])
								->values([
									'ledger_account_id' => $c_LedgerAccount->id,
									'reference_no' => $ref_row->ref_no,
									'credit' => 0,
									'debit' => $ref_row->ref_amount
								]);
								$query->execute();
							}else{
								$ReferenceBalance=$this->Invoices->ReferenceBalances->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'reference_no'=>$ref_row->ref_no])->first();
								$ReferenceBalance=$this->Invoices->ReferenceBalances->get($ReferenceBalance->id);
								$ReferenceBalance->debit=$ReferenceBalance->debit+$ref_row->ref_amount;
								
								$this->Invoices->ReferenceBalances->save($ReferenceBalance);
							}
							
							$query = $this->Invoices->ReferenceDetails->query();
							$query->insert(['ledger_account_id', 'invoice_id', 'reference_no', 'credit', 'debit', 'reference_type'])
							->values([
								'ledger_account_id' => $c_LedgerAccount->id,
								'invoice_id' => $invoice->id,
								'reference_no' => $ref_row->ref_no,
								'credit' => 0,
								'debit' => $ref_row->ref_amount,
								'reference_type' => $ref_row->ref_type
							]);
						
							$query->execute();
						}
					}
				
                $this->Flash->success(__('The invoice has been saved.'));

                return $this->redirect(['action' => 'confirm/'.$invoice->id]);
            } else { //pr($invoice); exit;
                $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
            }
        }
		
		
		$salesOrders = $this->Invoices->SalesOrders->find()->select(['total_rows' => 
		$this->Invoices->SalesOrders->find()->func()->count('SalesOrderRows.id')])
		->leftJoinWith('SalesOrderRows', function ($q) {
		return $q->where(['SalesOrderRows.quantity > SalesOrderRows.processed_quantity']);
		})
		->group(['SalesOrders.id'])
		->autoFields(true)
		->having(['total_rows >' => 0]);
		
		$items = $this->Invoices->Items->find('list');
		$transporters = $this->Invoices->Transporters->find('list', ['limit' => 200])->order(['Transporters.transporter_name' => 'ASC']);
		$termsConditions = $this->Invoices->TermsConditions->find('all',['limit' => 200]);
		$SaleTaxes = $this->Invoices->SaleTaxes->find('all')->where(['freeze'=>0]);
		
		if(!empty($sales_order->customer_id)){
			
		$customer_ledger = $this->Invoices->LedgerAccounts->find()->where(['LedgerAccounts.source_id'=>$sales_order->customer_id,'LedgerAccounts.source_model'=>'Customers'])->toArray();
		
		$customer_reference_details = $this->Invoices->ReferenceDetails->find()->where(['ReferenceDetails.ledger_account_id'=>$customer_ledger[0]->id])->toArray();
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
				$old_due_payment=$total_credit-$total_debit;

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
		
		$item_serial_no=$this->Invoices->ItemSerialNumbers->find('list');
		$employees = $this->Invoices->Employees->find('list');
        $this->set(compact('invoice', 'customers', 'companies', 'salesOrders','items','transporters','termsConditions','serviceTaxs','exciseDuty','SaleTaxes','employees','dueInvoicespay','creditlimit','old_due_payment','item_serial_no','ledger_account_details','ledger_account_details_for_fright','sale_tax_ledger_accounts','c_LedgerAccount'));
        $this->set('_serialize', ['invoice']);
    }
	
	public function pullFromSalesOrder(){
		if ($this->request->is('post')) {
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
		$invoice = $this->Invoices->get($id, [
            'contain' => ['ItemSerialNumbers','InvoiceRows','SalesOrders' => ['Invoices'=>['InvoiceRows'],'SalesOrderRows' => ['Items'=>['ItemSerialNumbers','ItemCompanies'=>function($q) use($st_company_id){
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
		
		
			
		foreach($invoice->sales_order->sales_order_rows as $sales_order_row){
			foreach($sales_order_row->item->item_serial_numbers as $item_serial_number){
				$ItemSerialNumber2[$item_serial_number->item_id]=$this->Invoices->ItemSerialNumbers->find()->where(['item_id'=>$item_serial_number->item_id,'status'=>'In'])->toArray();
			}
		}
		
		/* $sale_tax_ledger_accounts=[];
			foreach($invoice->sales_order->sales_order_rows as $sales_order_row){
				$st_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['source_id'=>$sales_order_row->sale_tax->id,'source_model'=>'SaleTaxes','company_id'=>$st_company_id])->first();
				
				$sale_tax_ledger_accounts[$sales_order_row->sale_tax->id]=$st_LedgerAccount->id;
			}	 */
		
		foreach($invoice->invoice_rows as $invoice_row){
			if($invoice_row->item_serial_number){
			@$ItemSerialNumber_In[$invoice_row->item_id]= explode(",",$invoice_row->item_serial_number);
			$ItemSerialNumber[$invoice_row->item_id]=$this->Invoices->ItemSerialNumbers->find()->where(['item_id'=>$invoice_row->item_id,'status'=>'In','company_id'=>$st_company_id])->orWhere(['ItemSerialNumbers.invoice_id'=>$invoice->id,'item_id'=>$invoice_row->item_id,'status'=>'Out','company_id'=>$st_company_id])->toArray();
			}
		}
		
		 $Em = new FinancialYearsController;
	     $financial_year_data = $Em->checkFinancialYear($invoice->date_created);
		$invoice_id=$id;
		//pr(['ledger_account_id'=>$c_LedgerAccount->id,'invoice_id'=>$invoice_id]); exit;
		$ReferenceDetails = $this->Invoices->ReferenceDetails->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'invoice_id'=>$invoice_id])->toArray();
		
		if(!empty($ReferenceDetails))
		{
			foreach($ReferenceDetails as $ReferenceDetail)
			{
				$ReferenceBalances[] = $this->Invoices->ReferenceBalances->find()->where(['ledger_account_id'=>$ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])->toArray();
			}
		}
		else{
			$ReferenceBalances='';
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

			if(@$ItemSerialNumber_In){
				foreach(@$ItemSerialNumber_In as $key=>$serial_no){
					
					foreach($serial_no as $data){
						$query = $this->Invoices->InvoiceRows->ItemSerialNumbers->query();
						$query->update()
							->set(['status' => 'In','invoice_id' => 0])
							->where(['id' => $data])
							->execute(); 
					}
				}
			}
			foreach($invoice->invoice_rows as $invoice_row){
				if($invoice_row->item_serial_numbers){
					$item_serial_no=implode(",",$invoice_row->item_serial_numbers );
					$invoice_row->item_serial_number=$item_serial_no;
				}
			}
			if ($this->Invoices->save($invoice)) {
				
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
				
				$qq=0; foreach($invoice->invoice_rows as $invoice_rows){
					$salesorderrow=$this->Invoices->SalesOrderRows->find()->where(['sales_order_id'=>$invoice->sales_order_id,'item_id'=>$invoice_rows->item_id])->first();
					
					
					$salesorderrow->processed_quantity=$salesorderrow->processed_quantity-@$invoice->getOriginal('invoice_rows')[$qq]->quantity+$invoice_rows->quantity;
					
					//pr($salesorderrow->processed_quantity); exit;
					$this->Invoices->SalesOrderRows->save($salesorderrow);
					$qq++; 
				}
				
				$this->Invoices->ItemLedgers->deleteAll(['source_id' => $invoice->id, 'source_model'=> 'Invoices']);
				
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
					$query = $this->Invoices->InvoiceRows->ItemSerialNumbers->query();
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
							$ReferenceDetail=$this->Invoices->ReferenceDetails->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'reference_no'=>$ref_row->ref_no,'invoice_id'=>$invoice->id])->first();
							
							if($ReferenceDetail){
								$ReferenceBalance=$this->Invoices->ReferenceBalances->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'reference_no'=>$ref_row->ref_no])->first();
								$ReferenceBalance=$this->Invoices->ReferenceBalances->get($ReferenceBalance->id);
								$ReferenceBalance->debit=$ReferenceBalance->debit-$ref_row->ref_old_amount+$ref_row->ref_amount;
								
								$this->Invoices->ReferenceBalances->save($ReferenceBalance);
								
								$ReferenceDetail=$this->Invoices->ReferenceDetails->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'reference_no'=>$ref_row->ref_no,'invoice_id'=>$invoice->id])->first();
								$ReferenceDetail=$this->Invoices->ReferenceDetails->get($ReferenceDetail->id);
								$ReferenceDetail->debit=$ReferenceDetail->debit-$ref_row->ref_old_amount+$ref_row->ref_amount;
								$this->Invoices->ReferenceDetails->save($ReferenceDetail);
							}else{
								if($ref_row->ref_type=='New Reference' or $ref_row->ref_type=='Advance Reference'){
									$query = $this->Invoices->ReferenceBalances->query();
									$query->insert(['ledger_account_id', 'reference_no', 'credit', 'debit'])
									->values([
										'ledger_account_id' => $c_LedgerAccount->id,
										'reference_no' => $ref_row->ref_no,
										'credit' => 0,
										'debit' => $ref_row->ref_amount
									])
									->execute();
									
								}else{
									$ReferenceBalance=$this->Invoices->ReferenceBalances->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'reference_no'=>$ref_row->ref_no])->first();
									$ReferenceBalance=$this->Invoices->ReferenceBalances->get($ReferenceBalance->id);
									$ReferenceBalance->debit=$ReferenceBalance->debit+$ref_row->ref_amount;
									
									$this->Invoices->ReferenceBalances->save($ReferenceBalance);
								}
								
								$query = $this->Invoices->ReferenceDetails->query();
								$query->insert(['ledger_account_id', 'invoice_id', 'reference_no', 'credit', 'debit', 'reference_type'])
								->values([
									'ledger_account_id' => $c_LedgerAccount->id,
									'invoice_id' => $invoice->id,
									'reference_no' => $ref_row->ref_no,
									'credit' => 0,
									'debit' => $ref_row->ref_amount,
									'reference_type' => $ref_row->ref_type
								])
								->execute();
								
							}
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
	   
		
		$salesOrders = $this->Invoices->SalesOrders->find()->select(['total_rows' => 
				$this->Invoices->SalesOrders->find()->func()->count('SalesOrderRows.id')])
				->leftJoinWith('SalesOrderRows', function ($q) {
					return $q->where(['SalesOrderRows.quantity > SalesOrderRows.processed_quantity']);
				})
				->group(['SalesOrders.id'])
				->autoFields(true)
				->having(['total_rows >' => 0]);
				
		$customer_ledger = $this->Invoices->LedgerAccounts->find()->where(['LedgerAccounts.source_id'=>$invoice->customer_id,'LedgerAccounts.source_model'=>'Customers'])->toArray();
		
		$customer_reference_details = $this->Invoices->ReferenceDetails->find()->where(['ReferenceDetails.ledger_account_id'=>$customer_ledger[0]->id])->toArray();
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


		
		$temp_due_payment=$total_credit-$total_debit;
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
        $this->set(compact('invoice_id','ReferenceDetails','ReferenceBalances','invoice', 'customers', 'companies', 'salesOrders','old_due_payment','items','transporters','termsConditions','serviceTaxs','exciseDuty','SaleTaxes','employees','dueInvoices','serial_no','ItemSerialNumber','SelectItemSerialNumber','ItemSerialNumber2','financial_year_data','ledger_account_details','ledger_account_details_for_fright','sale_tax_ledger_accounts','c_LedgerAccount','chkdate'));
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
			
			
			if($Items->item_companies[0]->serial_number_enable == '0'){  
				$stock=[];  $sumValue=0; $qtySum=0;
				
					$StockLedgers=$this->Invoices->ItemLedgers->find()->where(['ItemLedgers.item_id'=>$item_id,'ItemLedgers.company_id'=>$st_company_id])->order(['ItemLedgers.processed_on'=>'ASC']);
						
					
					foreach($StockLedgers as $StockLedger){ 
						if($StockLedger->in_out=='In'){
							if(($StockLedger->source_model=='Grns' and $StockLedger->rate_updated=='Yes') or ($StockLedger->source_model!='Grns')){
								for($inc=0;$inc<$StockLedger->quantity;$inc++){
									$stock[$item_id][]=$StockLedger->rate;
								}
							}
						}
					}
						foreach($StockLedgers as $StockLedger){
						if($StockLedger->in_out=='Out'){
							if(sizeof(@$stock[$item_id])>0){
								$stock[$item_id] = array_slice($stock[$item_id], $StockLedger->quantity); 
							}
						}
					}
					
					if(sizeof(@$stock[$item_id]) > 0){ 
						foreach(@$stock[$item_id] as $stockRate){ 
							@$sumValue=@$sumValue+@$stockRate;
							$qtySum++;
							
						}
					}
					
				$minimumSellingPrice=0;
				if(empty($Items->item_companies[0]->minimum_selling_price_factor)){
					$rate=0;
				}else{
					@$rate=$sumValue/$qtySum;
					$minimumSellingPrice=$rate*$Items->item_companies[0]->minimum_selling_price_factor;
				}
				
				
			}else{ 
				$ItemSerialNumbers =$this->Invoices->ItemLedgers->Items->ItemSerialNumbers->find()->where(['ItemSerialNumbers.item_id'=>$item_id,'ItemSerialNumbers.company_id' => $st_company_id,'ItemSerialNumbers.status'=>"In"]);
				
				$itemSerialRate=0; $itemSerialQuantity=0; $i=1;
				foreach($ItemSerialNumbers as $ItemSerialNumber){
					if(@$ItemSerialNumber->grn_id > 0){
						$ItemLedgerData =$this->Invoices->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->grn_id,'source_model'=>"Grns",'item_id'=>$ItemSerialNumber->item_id])->first();
						@$itemSerialQuantity=@$itemSerialQuantity+1;
						@$itemSerialRate+=@$ItemLedgerData['rate'];
					}
					else if(@$ItemSerialNumber->master_item_id > 0){
						$ItemLedgerData =$this->Invoices->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->item_id,'source_model'=>"Items",'item_id'=>$ItemSerialNumber->item_id])->first();
						@$itemSerialRate+=@$ItemLedgerData['rate'];
						@$itemSerialQuantity=@$itemSerialQuantity+1;
					}else if(@$ItemSerialNumber->sale_return_id > 0){
						$ItemLedgerData =$this->Invoices->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->sale_return_id,'source_model'=>"Sale Return",'item_id'=>$ItemSerialNumber->item_id])->first();
						@$itemSerialRate+=@$ItemLedgerData['rate'];
						@$itemSerialQuantity=@$itemSerialQuantity+1;
					}else if(@$ItemSerialNumber->inventory_transfer_voucher_id > 0){
						$ItemLedgerData =$this->Invoices->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->inventory_transfer_voucher_id,'source_model'=>"Inventory Transfer Voucher",'item_id'=>$ItemSerialNumber->item_id])->first();
						@$itemSerialRate+=@$ItemLedgerData['rate'];
						@$itemSerialQuantity=@$itemSerialQuantity+1;
					}
				}
				
				$minimumSellingPrice=0;
				if(empty($Items->item_companies[0]->minimum_selling_price_factor)){ 
					$rate=0;
				}else{
					@$rate=$itemSerialRate/$itemSerialQuantity;
					$minimumSellingPrice=$rate*$Items->item_companies[0]->minimum_selling_price_factor;
				}
			}
			$Number = new NumberHelper(new \Cake\View\View());
			echo $Number->format($minimumSellingPrice,[ 'places' => 2]);
			 //$this->NumberFormat($minimumSellingPrice,['places'=>2]);
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
			$Invoices=$this->Invoices->find()->where(['customer_id IN' => $customerIds])->matching(
					'InvoiceRows', function ($q) use($item_id) {
						return $q->where(['InvoiceRows.item_id' => $item_id]);
					}
				);
			////////start
			
			if($item->item_companies[0]->serial_number_enable == '0'){  
				$stock=[];  $sumValue=0; $qtySum=0;
				
					$StockLedgers=$this->Invoices->ItemLedgers->find()->where(['ItemLedgers.item_id'=>$item_id,'ItemLedgers.company_id'=>$st_company_id])->order(['ItemLedgers.processed_on'=>'ASC']);
						
					
					foreach($StockLedgers as $StockLedger){ 
						if($StockLedger->in_out=='In'){
							if(($StockLedger->source_model=='Grns' and $StockLedger->rate_updated=='Yes') or ($StockLedger->source_model!='Grns')){
								for($inc=0;$inc<$StockLedger->quantity;$inc++){
									$stock[$item_id][]=$StockLedger->rate;
								}
							}
						}
					}
						foreach($StockLedgers as $StockLedger){
						if($StockLedger->in_out=='Out'){
							if(sizeof(@$stock[$item_id])>0){
								$stock[$item_id] = array_slice($stock[$item_id], $StockLedger->quantity); 
							}
						}
					}
					
					if(sizeof(@$stock[$item_id]) > 0){ 
						foreach(@$stock[$item_id] as $stockRate){ 
							@$sumValue=@$sumValue+@$stockRate;
							$qtySum++;
							
						}
					}
				
				$minimumSellingPrice=0;
				if(empty($item->item_companies[0]->minimum_selling_price_factor)){
					$rate=0;
				}else{
					@$rate=$sumValue/$qtySum;
					$minimumSellingPrice=$rate*$item->item_companies[0]->minimum_selling_price_factor;
				}
					
				
			}else{
				$ItemSerialNumbers =$this->Invoices->ItemLedgers->Items->ItemSerialNumbers->find()->where(['ItemSerialNumbers.item_id'=>$item_id,'ItemSerialNumbers.company_id' => $st_company_id,'ItemSerialNumbers.status'=>"In"]);
				
				$itemSerialRate=0; $itemSerialQuantity=0; $i=1;
				foreach($ItemSerialNumbers as $ItemSerialNumber){
					if(@$ItemSerialNumber->grn_id > 0){  
						$ItemLedgerData =$this->Invoices->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->grn_id,'source_model'=>"Grns",'item_id'=>$ItemSerialNumber->item_id])->first();
						@$itemSerialQuantity=@$itemSerialQuantity+1;
						@$itemSerialRate+=@$ItemLedgerData['rate'];
						
					}
					else if(@$ItemSerialNumber->master_item_id > 0){
						$ItemLedgerData =$this->Invoices->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->item_id,'source_model'=>"Items",'item_id'=>$ItemSerialNumber->item_id])->first();
						@$itemSerialRate+=@$ItemLedgerData['rate'];
						@$itemSerialQuantity=@$itemSerialQuantity+1;
					}else if(@$ItemSerialNumber->sale_return_id > 0){
						$ItemLedgerData =$this->Invoices->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->sale_return_id,'source_model'=>"Sale Return",'item_id'=>$ItemSerialNumber->item_id])->first();
						@$itemSerialRate+=@$ItemLedgerData['rate'];
						@$itemSerialQuantity=@$itemSerialQuantity+1;
					}else if(@$ItemSerialNumber->inventory_transfer_voucher_id > 0){
						$ItemLedgerData =$this->Invoices->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->inventory_transfer_voucher_id,'source_model'=>"Inventory Transfer Voucher",'item_id'=>$ItemSerialNumber->item_id])->first();
						@$itemSerialRate+=@$ItemLedgerData['rate'];
						@$itemSerialQuantity=@$itemSerialQuantity+1;
					}
				}
				
				$minimumSellingPrice=0;
				if(empty($item->item_companies[0]->minimum_selling_price_factor)){  
					$rate=0;
				}else{
					@$rate=$itemSerialRate/$itemSerialQuantity;
					
					$minimumSellingPrice=$rate*$item->item_companies[0]->minimum_selling_price_factor;
					
				}
			}
			////////end
		
			//$this->set(compact('Invoices','customer_text','item'));
			$Number = new NumberHelper(new \Cake\View\View());
			$Html = new HtmlHelper(new \Cake\View\View());
			
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
				$i=0; foreach($Invoices as $invoice):
				$html.='<tr>
						<td>'.h(++$i).'</td>
						<td>'.$Html->link(($invoice->in1.'/IN'.str_pad($invoice->id, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4),'/Invoices/confirm/'.$invoice->id,array('target'=>'_blank')).'</td>
						<td>'.h(date('d-m-Y',strtotime($invoice->date_created))).'</td>
						<td>'.$Number->format($invoice->_matchingData['InvoiceRows']->rate,[ 'places' => 2]).'</td>
					</tr>';
				endforeach;
				$html.='</tbody>
			</table>';
			die(json_encode(array("html"=>$html,"minimum_selling_price"=>$minimumSellingPrice)));
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
		
		$sales_order_id=@(int)$this->request->query('sales-order');
		$sales_order=array(); $process_status='New';
		if(!empty($sales_order_id)){
			$sales_order = $this->Invoices->SalesOrders->get($sales_order_id, [
				'contain' => ['SalesOrderRows.Items' => function ($q) use($st_company_id) {
						   return $q
								->where(['SalesOrderRows.quantity > SalesOrderRows.processed_quantity'])
								->contain(['ItemSerialNumbers'=>function($q) use($st_company_id){
									return $q->where(['ItemSerialNumbers.status' => 'In','ItemSerialNumbers.company_id' => $st_company_id]); 
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
			$invoice->invoice_type='GST';
			$invoice->total_after_pnf=$invoice->total_taxable_value;
			$invoice->sales_ledger_account=$invoice->sales_ledger_account;
			
			/* if($invoice->payment_mode=='New_ref'){
			$invoice->due_payment=$invoice->grand_total;
			}else{
				$invoice->due_payment=$invoice->grand_total-$invoice->total_amount_agst;
			} */

			$ref_rows=@$invoice->ref_rows;
			
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
				$ledger_fright=@(int)$invoice->fright_amount;
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $invoice->sales_ledger_account;
				$ledger->debit = 0;
				$ledger->credit = $invoice->total+$ledger_fright;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$ledger->voucher_source = 'Invoice';
				$this->Invoices->Ledgers->save($ledger); 
				
				/* $ledger_fright=@(int)$invoice->fright_amount;
				$ledger = $this->Invoices->Ledgers->newEntity();
				$ledger->ledger_account_id = $invoice->fright_ledger_account;
				$ledger->debit = 0;
				$ledger->credit = $invoice->fright_amount;
				$ledger->voucher_id = $invoice->id;
				$ledger->company_id = $invoice->company_id;
				$ledger->transaction_date = $invoice->date_created;
				$ledger->voucher_source = 'Invoice';
				if($ledger_fright > 0)
				{
					$this->Invoices->Ledgers->save($ledger); 
				} */
				
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
					$query = $this->Invoices->InvoiceRows->ItemSerialNumbers->query();
						$query->update()
							->set(['status' => 'Out','invoice_id' => $invoice->id])
							->where(['id' => $serial])
							->execute();
					}
				}
				
				
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
						$SalesOrderRow = $this->Invoices->SalesOrderRows->get($sales_order_row_id);
						$SalesOrderRow->processed_quantity=$SalesOrderRow->processed_quantity+$qty;
						$this->Invoices->SalesOrderRows->save($SalesOrderRow);
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
						//$ref_row->ref_no='i'.$invoice->in2;
						$query = $this->Invoices->ReferenceBalances->query();
								$query->insert(['ledger_account_id', 'reference_no', 'credit', 'debit'])
								->values([
									'ledger_account_id' => $c_LedgerAccount->id,
									'reference_no' => 'i'.$invoice->in2,
									'credit' => 0,
									'debit' => $invoice->grand_total
								]);
								$query->execute();
								
						$query = $this->Invoices->ReferenceDetails->query();
							$query->insert(['ledger_account_id', 'invoice_id', 'reference_no', 'credit', 'debit', 'reference_type'])
							->values([
								'ledger_account_id' => $c_LedgerAccount->id,
								'invoice_id' => $invoice->id,
								'reference_no' => 'i'.$invoice->in2,
								'credit' => 0,
								'debit' => $invoice->grand_total,
								'reference_type' => 'New Reference'
							]);
							
								$query->execute();
						
					}else if(sizeof(@$ref_rows)>0){ 
			
						foreach($ref_rows as $ref_row){  	
							$ref_row=(object)$ref_row; 
							
							if($ref_row->ref_type=='New Reference' or $ref_row->ref_type=='Advance Reference'){
								$query = $this->Invoices->ReferenceBalances->query();
								
								$query->insert(['ledger_account_id', 'reference_no', 'credit', 'debit'])
								->values([
									'ledger_account_id' => $c_LedgerAccount->id,
									'reference_no' => $ref_row->ref_no,
									'credit' => 0,
									'debit' => $ref_row->ref_amount
								]);
								$query->execute();
								
							}else{
								$ReferenceBalance=$this->Invoices->ReferenceBalances->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'reference_no'=>$ref_row->ref_no])->first();
								$ReferenceBalance=$this->Invoices->ReferenceBalances->get($ReferenceBalance->id);
								$ReferenceBalance->debit=$ReferenceBalance->debit+$ref_row->ref_amount;
								
								$this->Invoices->ReferenceBalances->save($ReferenceBalance);
							}
							
							$query = $this->Invoices->ReferenceDetails->query();
							$query->insert(['ledger_account_id', 'invoice_id', 'reference_no', 'credit', 'debit', 'reference_type'])
							->values([
								'ledger_account_id' => $c_LedgerAccount->id,
								'invoice_id' => $invoice->id,
								'reference_no' => $ref_row->ref_no,
								'credit' => 0,
								'debit' => $ref_row->ref_amount,
								'reference_type' => $ref_row->ref_type
							]);
							
								$query->execute();
						} 
					}
				
                $this->Flash->success(__('The invoice has been saved.'));

                return $this->redirect(['action' => 'GstConfirm/'.$invoice->id]);
            } else { 
                $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
            }
        }
				
				
				

		$salesOrders = $this->Invoices->SalesOrders->find()->select(['total_rows' => 
		$this->Invoices->SalesOrders->find()->func()->count('SalesOrderRows.id')])
		->leftJoinWith('SalesOrderRows', function ($q) {
		return $q->where(['SalesOrderRows.quantity > SalesOrderRows.processed_quantity']);
		})
		->group(['SalesOrders.id'])
		->autoFields(true)
		->having(['total_rows >' => 0]);
		
		$items = $this->Invoices->Items->find('list');
		$transporters = $this->Invoices->Transporters->find('list', ['limit' => 200])->order(['Transporters.transporter_name' => 'ASC']);
		$termsConditions = $this->Invoices->TermsConditions->find('all',['limit' => 200]);
		//$SaleTaxes = $this->Invoices->SaleTaxes->find('all')->where(['freeze'=>0]);
		
		if(!empty($sales_order->customer_id)){
			
		$customer_ledger = $this->Invoices->LedgerAccounts->find()->where(['LedgerAccounts.source_id'=>$sales_order->customer_id,'LedgerAccounts.source_model'=>'Customers'])->toArray();
		
		$customer_reference_details = $this->Invoices->ReferenceDetails->find()->where(['ReferenceDetails.ledger_account_id'=>$customer_ledger[0]->id])->toArray();
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
				$old_due_payment=$total_credit-$total_debit;

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
		//pr($SaleTaxes->toArray());exit;
		$item_serial_no=$this->Invoices->ItemSerialNumbers->find('list');
		$employees = $this->Invoices->Employees->find('list');
        $this->set(compact('invoice', 'customers', 'companies', 'salesOrders','items','transporters','termsConditions','serviceTaxs','exciseDuty','SaleTaxes','employees','dueInvoicespay','creditlimit','old_due_payment','item_serial_no','ledger_account_details','ledger_account_details_for_fright','sale_tax_ledger_accounts','c_LedgerAccount','GstTaxes'));
        $this->set('_serialize', ['invoice']);

		$this->set(compact('sales_order','process_status','sales_order_id','chkdate'));
		
		
	}
	
	
	
	 public function gstEdit($id = null)
    {
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		 
		$this->viewBuilder()->layout('index_layout');
		$invoice = $this->Invoices->get($id, [
            'contain' => ['ItemSerialNumbers','InvoiceRows','SalesOrders' => ['Invoices'=>['InvoiceRows'],'SalesOrderRows' => ['Items'=>['ItemSerialNumbers','ItemCompanies'=>function($q) use($st_company_id){
									return $q->where(['ItemCompanies.company_id' => $st_company_id]);
								}]]],'Companies','Customers'=>['Districts','CustomerAddress'=> function ($q) {
						return $q
						->where(['CustomerAddress.default_address' => 1]);}],'Employees']
        ]);
		
		$invoice_old_data = $this->Invoices->get($id, ['contain' => ['InvoiceRows']]);
		// pr($invoice_old_data); exit;
		 $edited_by=$invoice->edited_by;
		 $edited_on=$invoice->edited_on;
		$closed_month=$this->viewVars['closed_month'];
		if(!in_array(date("m-Y",strtotime($invoice->date_created)),$closed_month))
		{
		$c_LedgerAccount=$this->Invoices->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Customers','source_id'=>$invoice->customer->id])->first();
		
		$ReferenceDetails=$this->Invoices->ReferenceDetails->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'invoice_id'=>$invoice->id]);

		foreach($invoice->sales_order->sales_order_rows as $sales_order_row){
			foreach($sales_order_row->item->item_serial_numbers as $item_serial_number){
				$ItemSerialNumber2[$item_serial_number->item_id]=$this->Invoices->ItemSerialNumbers->find()->where(['item_id'=>$item_serial_number->item_id,'status'=>'In'])->toArray();
			}
		}
		
		
		foreach($invoice->invoice_rows as $invoice_row){
			if($invoice_row->item_serial_number){
			@$ItemSerialNumber_In[$invoice_row->item_id]= explode(",",$invoice_row->item_serial_number);
			$ItemSerialNumber[$invoice_row->item_id]=$this->Invoices->ItemSerialNumbers->find()->where(['item_id'=>$invoice_row->item_id,'status'=>'In','company_id'=>$st_company_id])->orWhere(['ItemSerialNumbers.invoice_id'=>$invoice->id,'item_id'=>$invoice_row->item_id,'status'=>'Out','company_id'=>$st_company_id])->toArray();
			}
		}
		
		 $Em = new FinancialYearsController;
	     $financial_year_data = $Em->checkFinancialYear($invoice->date_created);
		$invoice_id=$id;
		//pr(['ledger_account_id'=>$c_LedgerAccount->id,'invoice_id'=>$invoice_id]); exit;
		$ReferenceDetails = $this->Invoices->ReferenceDetails->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'invoice_id'=>$invoice_id])->toArray();
		
		if(!empty($ReferenceDetails))
		{
			foreach($ReferenceDetails as $ReferenceDetail)
			{
				$ReferenceBalances[] = $this->Invoices->ReferenceBalances->find()->where(['ledger_account_id'=>$ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])->toArray();
			}
		}
		else{
			$ReferenceBalances='';
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
			//pr($invoice->total_taxable_value); exit;
			$invoice->total_after_pnf=$invoice->total_taxable_value;
			$invoice->sales_ledger_account=$invoice->sales_ledger_account;
			//$invoice->edited_on =$edited_on; 
			//$invoice->edited_by=$edited_by;
			$invoice->edited_on = date("Y-m-d"); 
			$invoice->edited_by=$this->viewVars['s_employee_id'];
			if(@$ItemSerialNumber_In){
				foreach(@$ItemSerialNumber_In as $key=>$serial_no){
					
					foreach($serial_no as $data){
						$query = $this->Invoices->InvoiceRows->ItemSerialNumbers->query();
						$query->update()
							->set(['status' => 'In','invoice_id' => 0])
							->where(['id' => $data])
							->execute(); 
					}
				}
			}
			foreach($invoice->invoice_rows as $invoice_row){
				if($invoice_row->item_serial_numbers){
					$item_serial_no=implode(",",$invoice_row->item_serial_numbers );
					$invoice_row->item_serial_number=$item_serial_no;
				}
			}
			
			
			foreach($invoice_old_data->invoice_rows as $invoice_row){
				//pr($invoice_row->quantity);
				$salesorderrowupdate=$this->Invoices->SalesOrderRows->find()->where(['sales_order_id'=>$invoice->sales_order_id,'item_id'=>$invoice_row->item_id])->first();
				$salesorderrowupdate->processed_quantity=$salesorderrowupdate->processed_quantity-$invoice_row->quantity;
				$this->Invoices->SalesOrderRows->save($salesorderrowupdate);
				//pr($salesorderrowupdate->processed_quantity); exit;
			} 
			//exit;
			
			if ($this->Invoices->save($invoice)) {
				
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
				$this->Invoices->Ledgers->save($ledger); 
				
				//pr($invoice->taxable_value); exit;
				//Ledger posting for Account Reference
				$ledger_fright=@(int)$invoice->fright_amount;
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
				
				$this->Invoices->ItemLedgers->deleteAll(['source_id' => $invoice->id, 'source_model'=> 'Invoices']);
				
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
					$query = $this->Invoices->InvoiceRows->ItemSerialNumbers->query();
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
							$ReferenceDetail=$this->Invoices->ReferenceDetails->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'reference_no'=>$ref_row->ref_no,'invoice_id'=>$invoice->id])->first();
							
							if($ReferenceDetail){
								$ReferenceBalance=$this->Invoices->ReferenceBalances->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'reference_no'=>$ref_row->ref_no])->first();
								$ReferenceBalance=$this->Invoices->ReferenceBalances->get($ReferenceBalance->id);
								$ReferenceBalance->debit=$ReferenceBalance->debit-$ref_row->ref_old_amount+$ref_row->ref_amount;
								
								$this->Invoices->ReferenceBalances->save($ReferenceBalance);
								
								$ReferenceDetail=$this->Invoices->ReferenceDetails->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'reference_no'=>$ref_row->ref_no,'invoice_id'=>$invoice->id])->first();
								$ReferenceDetail=$this->Invoices->ReferenceDetails->get($ReferenceDetail->id);
								$ReferenceDetail->debit=$ReferenceDetail->debit-$ref_row->ref_old_amount+$ref_row->ref_amount;
								$this->Invoices->ReferenceDetails->save($ReferenceDetail);
							}else{
								if($ref_row->ref_type=='New Reference' or $ref_row->ref_type=='Advance Reference'){
									$query = $this->Invoices->ReferenceBalances->query();
									$query->insert(['ledger_account_id', 'reference_no', 'credit', 'debit'])
									->values([
										'ledger_account_id' => $c_LedgerAccount->id,
										'reference_no' => $ref_row->ref_no,
										'credit' => 0,
										'debit' => $ref_row->ref_amount
									])
									->execute();
									
								}else{
									$ReferenceBalance=$this->Invoices->ReferenceBalances->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'reference_no'=>$ref_row->ref_no])->first();
									$ReferenceBalance=$this->Invoices->ReferenceBalances->get($ReferenceBalance->id);
									$ReferenceBalance->debit=$ReferenceBalance->debit+$ref_row->ref_amount;
									
									$this->Invoices->ReferenceBalances->save($ReferenceBalance);
								}
								
								$query = $this->Invoices->ReferenceDetails->query();
								$query->insert(['ledger_account_id', 'invoice_id', 'reference_no', 'credit', 'debit', 'reference_type'])
								->values([
									'ledger_account_id' => $c_LedgerAccount->id,
									'invoice_id' => $invoice->id,
									'reference_no' => $ref_row->ref_no,
									'credit' => 0,
									'debit' => $ref_row->ref_amount,
									'reference_type' => $ref_row->ref_type
								])
								->execute();
								
							}
						}
					}
				
				
                $this->Flash->success(__('The invoice has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {  //pr($invoice); exit;
                $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
            }
        }
       
		
		
		$customers = $this->Invoices->Customers->find('all');
       $companies = $this->Invoices->Companies->find('all', ['limit' => 200]);
	   
		
		$salesOrders = $this->Invoices->SalesOrders->find()->select(['total_rows' => 
				$this->Invoices->SalesOrders->find()->func()->count('SalesOrderRows.id')])
				->leftJoinWith('SalesOrderRows', function ($q) {
					return $q->where(['SalesOrderRows.quantity > SalesOrderRows.processed_quantity']);
				})
				->group(['SalesOrders.id'])
				->autoFields(true)
				->having(['total_rows >' => 0]);
				
		$customer_ledger = $this->Invoices->LedgerAccounts->find()->where(['LedgerAccounts.source_id'=>$invoice->customer_id,'LedgerAccounts.source_model'=>'Customers'])->toArray();
		
		$customer_reference_details = $this->Invoices->ReferenceDetails->find()->where(['ReferenceDetails.ledger_account_id'=>$customer_ledger[0]->id])->toArray();
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


		
		$temp_due_payment=$total_credit-$total_debit;
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
				
		$employees = $this->Invoices->Employees->find('list');
        $this->set(compact('invoice_id','ReferenceDetails','ReferenceBalances','invoice', 'customers', 'companies', 'salesOrders','old_due_payment','items','transporters','termsConditions','serviceTaxs','exciseDuty','SaleTaxes','employees','dueInvoices','serial_no','ItemSerialNumber','SelectItemSerialNumber','ItemSerialNumber2','financial_year_data','ledger_account_details','ledger_account_details_for_fright','sale_tax_ledger_accounts','c_LedgerAccount','chkdate','GstTaxes'));
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
	
	
	public function GstConfirm($id = null)
    {
		$this->viewBuilder()->layout('pdf_layout');
		$invoice = $this->Invoices->get($id, [
            'contain' => ['InvoiceRows']
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
			return $this->redirect(['action' => 'GstConfirm/'.$id]);
        }
		$this->set(compact('invoice','id'));
    }
	
	public function gstSalesReport(){
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
		//pr($invoices->toArray()); exit;
		
		$ItemCategories = $this->Invoices->Items->ItemCategories->find('list')->order(['ItemCategories.name' => 'ASC']);
		$ItemGroups = $this->Invoices->Items->ItemGroups->find('list')->order(['ItemGroups.name' => 'ASC']);
		$ItemSubGroups = $this->Invoices->Items->ItemSubGroups->find('list')->order(['ItemSubGroups.name' => 'ASC']);
		$Items = $this->Invoices->Items->find('list')->order(['Items.name' => 'ASC']);
		//pr($invoiceBookingsInterState->toArray()); exit;
		$this->set(compact('invoices','SalesMans','SalesOrders','interStateInvoice','invoiceBookings','invoiceBookingsInterState','Items','ItemGroups','ItemCategories','ItemSubGroups','voucherLedgerDetails','voucherSource','voucherLedgerDetailsGst','voucherSourceGst','voucherLedgerDetailIgst','voucherSourceIgst','SaleTaxeGst','LedgerAccountDetails','LedgerAccountDetailIgst','PurchaseIgst','PurchaseCgst','invoiceIGst','invoiceGst','invoiceBookingsGst'));
	}
	
	public function salesManReport(){
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
		$GstTaxes = $this->Invoices->SaleTaxes->find()->where(['SaleTaxes.account_second_subgroup_id'=>5,'cgst'=>'yes'])->orwhere(['SaleTaxes.account_second_subgroup_id'=>5,'igst'=>'yes'])->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				);
		//pr($GstTaxes->toArray());exit;		
		$Items = $this->Invoices->Items->find('list')->order(['Items.name' => 'ASC']);
		$this->set(compact('invoices','SalesMans','SalesOrders','OpenQuotations','ClosedQuotations','ItemCategories','ItemGroups','ItemSubGroups','Items','GstTaxes','invoicesGst'));
	}
	
	public function itemSerialMismatch()
    {
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$invoices=$this->Invoices->find()->contain(['InvoiceRows'=>['Items'=>['ItemSerialNumbers','ItemCompanies'=>function($q) use($st_company_id){
									return $q->where(['ItemCompanies.company_id' => $st_company_id]);
		}]]])->where(['Invoices.company_id' => $st_company_id]);
		$ItemSerials=[];
		foreach($invoices as $invoice){
			foreach($invoice->invoice_rows as $invoice_row){
				if(!empty($invoice_row->item->item_companies[0]['serial_number_enable'])){
					//pr(['invoice_id'=>$invoice->id,'item_id'=>$invoice_row->item_id]);
					$ItemSerialNumbers=$this->Invoices->Items->ItemSerialNumbers->find()->where(['invoice_id'=>$invoice->id,'item_id'=>$invoice_row->item_id]);
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
}
