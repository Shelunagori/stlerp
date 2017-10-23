<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PurchaseReturns Controller
 *
 * @property \App\Model\Table\PurchaseReturnsTable $PurchaseReturns
 */
class PurchaseReturnsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$where = [];
		
		$vouch_no = $this->request->query('vouch_no');
		$From    = $this->request->query('From');
		$To    = $this->request->query('To');
		
		$this->set(compact('vouch_no','From','To'));
		
		if(!empty($vouch_no)){
			$where['PurchaseReturns.voucher_no Like']=$vouch_no;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['PurchaseReturns.created_on >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['PurchaseReturns.created_on <=']=$To;
		}
		
		
        $this->paginate = [
            'contain' => ['InvoiceBookings', 'Companies']
        ];
        $purchaseReturns = $this->paginate($this->PurchaseReturns->find()->where($where)->where(['PurchaseReturns.company_id'=>$st_company_id])->order(['PurchaseReturns.id' => 'DESC']));

        $this->set(compact('purchaseReturns','url'));
        $this->set('_serialize', ['purchaseReturns']);
    }

	
	public function exportExcel(){
		
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$where = [];
		
		$vouch_no = $this->request->query('vouch_no');
		$From    = $this->request->query('From');
		$To    = $this->request->query('To');
		
		$this->set(compact('vouch_no','From','To'));
		
		if(!empty($vouch_no)){
			$where['PurchaseReturns.voucher_no Like']=$vouch_no;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['PurchaseReturns.created_on >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['PurchaseReturns.created_on <=']=$To;
		}
		
		
        $this->paginate = [
            'contain' => ['InvoiceBookings', 'Companies']
        ];
        $purchaseReturns = $this->PurchaseReturns->find()->where($where)->where(['PurchaseReturns.company_id'=>$st_company_id])->order(['PurchaseReturns.id' => 'DESC']);

        $this->set(compact('purchaseReturns'));
        $this->set('_serialize', ['purchaseReturns']);
	}
    /**
     * View method
     *
     * @param string|null $id Purchase Return id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$this->viewBuilder()->layout('index_layout');
		
        $purchaseReturn = $this->PurchaseReturns->get($id, [
            'contain' => ['InvoiceBookings','Creator','Companies','PurchaseReturnRows'=>['Items'=>['ItemLedgers'=>function ($q) use($id) {
				return $q->where(['source_model'=>'Purchase Return','in_out'=>'Out','source_id'=>$id]);
				}]]]
        ]);
		
		if($purchaseReturn->invoice_booking->ledger_account_for_vat>0){
			$LedgerAccount=$this->PurchaseReturns->InvoiceBookings->LedgerAccounts->get($purchaseReturn->invoice_booking->ledger_account_for_vat);
		}
		
		$c_LedgerAccount=$this->PurchaseReturns->InvoiceBookings->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Vendors','source_id'=>$purchaseReturn->invoice_booking->vendor_id])->first();
		
		
		$ReferenceDetails=$this->PurchaseReturns->InvoiceBookings->ReferenceDetails->find()->where(['ledger_account_id'=>$c_LedgerAccount->id,'purchase_return_id'=>$purchaseReturn->id]);
		
		//pr($LedgerAccount );
		//pr($purchaseReturn );exit;

        $this->set('purchaseReturn', $purchaseReturn);
		$this->set(compact('LedgerAccount','ReferenceDetails'));
        $this->set('_serialize', ['purchaseReturn']);
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
		$s_employee_id=$this->viewVars['s_employee_id'];
        $purchaseReturn = $this->PurchaseReturns->newEntity();
		$invoice_booking_id=@(int)$this->request->query('invoiceBooking');
		$invoiceBooking = $this->PurchaseReturns->InvoiceBookings->get($invoice_booking_id, [
            'contain' => ['InvoiceBookingRows' => ['Items'],'Grns'=>['Companies','Vendors','GrnRows'=>['Items'],'PurchaseOrders'=>['PurchaseOrderRows']]]
        ]);

			   $st_year_id = $session->read('st_year_id');
		$financial_year = $this->PurchaseReturns->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		 
		
		$financial_month_first = $this->PurchaseReturns->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->PurchaseReturns->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();

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


		//pr($invoiceBooking['grn_id']); exit;
		$v_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Vendors','source_id'=>$invoiceBooking->vendor_id])->first();
		
		 if ($this->request->is('post')) {
			$ref_rows=@$this->request->data['ref_rows'];
			$purchaseReturn = $this->PurchaseReturns->patchEntity($purchaseReturn, $this->request->data);
			$purchaseReturn->company_id=$st_company_id;
			$purchaseReturn->created_on= date("Y-m-d");
			$purchaseReturn->created_by=$s_employee_id;
			$purchaseReturn->created_by=$s_employee_id;
			$purchaseReturn->purchase_ledger_account=$invoiceBooking->purchase_ledger_account;
			$purchaseReturn->vendor_id=$invoiceBooking->vendor_id;
			$last_pr_no=$this->PurchaseReturns->find()->select(['voucher_no'])->where(['company_id' => $st_company_id])->order(['voucher_no' => 'DESC'])->first();
			if($last_pr_no){
				$purchaseReturn->voucher_no=$last_pr_no->voucher_no+1;
			}else{
				$purchaseReturn->voucher_no=1;
			}
			$purchaseReturn->transaction_date = date("Y-m-d",strtotime($this->request->data['transaction_date']));
			 if ($this->PurchaseReturns->save($purchaseReturn)) {   
				foreach($purchaseReturn->purchase_return_rows as $purchase_return_row){
					$results=$this->PurchaseReturns->ItemLedgers->find()->where(['ItemLedgers.item_id' => $purchase_return_row->item_id,'ItemLedgers.in_out' => 'In','rate_updated' => 'Yes','company_id' => $st_company_id,'source_model'=>'Grns','source_id'=>$invoiceBooking['grn_id']])->first();
					$itemLedger = $this->PurchaseReturns->ItemLedgers->newEntity();
					$itemLedger->item_id = $purchase_return_row->item_id;
					$itemLedger->quantity = $purchase_return_row->quantity;
					$itemLedger->source_model = 'Purchase Return';
					$itemLedger->source_id = $purchaseReturn->id;
					$itemLedger->in_out = 'Out';
					$itemLedger->rate = $results->rate;
					$itemLedger->company_id = $st_company_id;
					$itemLedger->processed_on = $purchaseReturn->transaction_date;
					$this->PurchaseReturns->ItemLedgers->save($itemLedger);
				}
				$vat_amounts=[]; $total_amounts=[];
				foreach($invoiceBooking->invoice_booking_rows as $invoice_booking_row){
					$amount=$invoice_booking_row->unit_rate_from_po*$invoice_booking_row->quantity;
					$amount=$amount+$invoice_booking_row->misc;
					if($invoice_booking_row->discount_per==1){
						$amount=$amount*((100-$invoice_booking_row->discount)/100);
					}else{
						$amount=$amount-$invoice_booking_row->discount;
					}
					if($invoice_booking_row->pnf_per==1){
						$amount=$amount*((100+$invoice_booking_row->pnf)/100);
					}else{
						$amount=$amount+$invoice_booking_row->pnf;
					}
					$amount=$amount*((100+	$invoice_booking_row->excise_duty)/100);
					$amountofVAT=($amount*$invoice_booking_row->sale_tax)/100;
					$amount=$amount*((100+$invoice_booking_row->sale_tax)/100);

					$vat_amounts[$invoice_booking_row->item_id]=$amountofVAT/$invoice_booking_row->quantity;
					$amount=$amount+$invoice_booking_row->other_charges;
					$total_amounts[$invoice_booking_row->item_id]=$amount/$invoice_booking_row->quantity;
				}
				$total_vat_item=0;
				$total_amounts_item=0;
				foreach($purchaseReturn->purchase_return_rows as $purchase_return_row){
					$total_vat=$vat_amounts[$purchase_return_row->item_id]*$purchase_return_row->quantity;
					$total_vat_item=$total_vat_item+$total_vat;
					$total_amt=$total_amounts[$purchase_return_row->item_id]*$purchase_return_row->quantity;
					$total_amounts_item=$total_amounts_item+$total_amt;
					
					$query = $this->PurchaseReturns->PurchaseReturnRows->query();
						$query->update()
							->set(['vat_per_item'=>$vat_amounts[$purchase_return_row->item_id],])
							->where(['purchase_return_id' => $purchaseReturn->id,'item_id'=>$purchase_return_row->item_id])
							->execute();

				}

				foreach($purchaseReturn->purchase_return_rows as $purchase_return_row){
						$query = $this->PurchaseReturns->InvoiceBookings->InvoiceBookingRows->query();
						$query->update()
							->set(['purchase_return_quantity'=>$purchase_return_row->quantity])
							->where(['invoice_booking_id' => $invoiceBooking->id,'item_id'=>$purchase_return_row->item_id])
							->execute();
					}
						
						$query = $this->PurchaseReturns->InvoiceBookings->query();
						$query->update()
						->set(['purchase_return_status' => 'Yes','purchase_return_id'=>$purchaseReturn->id])
						->where(['id' => $invoiceBooking->id])
						->execute();

						$query = $this->PurchaseReturns->query();
						$query->update()
						->set(['invoice_booking_id'=>$invoiceBooking->id])
						->where(['id' => $purchaseReturn->id])
						->execute();
					$cst_purchase=0;
					if($st_company_id=='25'){
						$cst_purchase=35;
					}else if($st_company_id=='26'){
						$cst_purchase=161;
					}else if($st_company_id=='27'){
						$cst_purchase=309;
					}
				
				if($invoiceBooking->purchase_ledger_account==$cst_purchase){
					//ledger posting for PURCHASE ACCOUNT
					$ledger = $this->PurchaseReturns->Ledgers->newEntity();
					$ledger->ledger_account_id = $invoiceBooking->purchase_ledger_account;
					$ledger->debit = 0;
					$ledger->credit = $total_amounts_item;
					$ledger->voucher_id = $purchaseReturn->id;
					$ledger->company_id = $st_company_id;
					$ledger->voucher_source = 'Purchase Return';
					$ledger->transaction_date = $purchaseReturn->transaction_date;
					$this->PurchaseReturns->Ledgers->save($ledger);
				}else{
					//ledger posting for PURCHASE ACCOUNT
					$ledger = $this->PurchaseReturns->Ledgers->newEntity();
					$ledger->ledger_account_id = $invoiceBooking->purchase_ledger_account;
					$ledger->debit = 0;
					$ledger->credit = $total_amounts_item-$total_vat_item;
					$ledger->voucher_id = $purchaseReturn->id;
					$ledger->company_id = $st_company_id;
					$ledger->voucher_source = 'Purchase Return';
					$ledger->transaction_date = $purchaseReturn->transaction_date;
					$this->PurchaseReturns->Ledgers->save($ledger);
					
					//ledger posting for PURCHASE ACCOUNT
					$ledger = $this->PurchaseReturns->Ledgers->newEntity();
					$ledger->ledger_account_id = $invoiceBooking->ledger_account_for_vat;
					$ledger->debit = 0;
					$ledger->credit = $total_vat_item;
					$ledger->voucher_id = $purchaseReturn->id;
					$ledger->company_id = $st_company_id;
					$ledger->voucher_source = 'Purchase Return';
					$ledger->transaction_date = $purchaseReturn->transaction_date;
					$this->PurchaseReturns->Ledgers->save($ledger);
				}

				$v_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Vendors','source_id'=>$invoiceBooking->vendor_id])->first();
				$ledger = $this->PurchaseReturns->Ledgers->newEntity();
				$ledger->ledger_account_id = $v_LedgerAccount->id;
				$ledger->debit = $total_amounts_item;
				$ledger->credit =0;
				$ledger->voucher_id = $purchaseReturn->id;
				$ledger->company_id = $invoiceBooking->company_id;
				$ledger->transaction_date =$purchaseReturn->transaction_date;
				$ledger->voucher_source = 'Purchase Return';
				
				$this->PurchaseReturns->Ledgers->save($ledger);

					if(sizeof(@$ref_rows)>0){
						
						foreach($ref_rows as $ref_row){ 
							$ref_row=(object)$ref_row;
							if($ref_row->ref_type=='New Reference' or $ref_row->ref_type=='Advance Reference'){
								$query = $this->PurchaseReturns->ReferenceBalances->query();
								
								$query->insert(['ledger_account_id', 'reference_no', 'credit', 'debit'])
								->values([
									'ledger_account_id' => $v_LedgerAccount->id,
									'reference_no' => $ref_row->ref_no,
									'credit' => 0,
									'debit' => $ref_row->ref_amount
								]);
								$query->execute();
							}else{
								$ReferenceBalance=$this->PurchaseReturns->ReferenceBalances->find()->where(['ledger_account_id'=>$v_LedgerAccount->id,'reference_no'=>$ref_row->ref_no])->first();
								$ReferenceBalance=$this->PurchaseReturns->ReferenceBalances->get($ReferenceBalance->id);
								$ReferenceBalance->debit=$ReferenceBalance->debit+$ref_row->ref_amount;
								
								$this->PurchaseReturns->ReferenceBalances->save($ReferenceBalance);
							}
							
							$query = $this->PurchaseReturns->ReferenceDetails->query();
							$query->insert(['ledger_account_id', 'purchase_return_id', 'reference_no', 'credit', 'debit', 'reference_type'])
							->values([
								'ledger_account_id' => $v_LedgerAccount->id,
								'purchase_return_id' => $purchaseReturn->id,
								'reference_no' => $ref_row->ref_no,
								'credit' =>  0,
								'debit' =>$ref_row->ref_amount,
								'reference_type' => $ref_row->ref_type
							]);
						
							$query->execute();
						}
					}
				
				$this->Flash->success(__('The purchase return has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {// pr($purchaseReturn); exit;
                $this->Flash->error(__('The purchase return could not be saved. Please, try again.'));
            }
        }
       // $invoiceBookings = $this->PurchaseReturns->InvoiceBookings->find('list', ['limit' => 200]);
		$ledger_account_details = $this->PurchaseReturns->LedgerAccounts->get($invoiceBooking->purchase_ledger_account);
		$ledger_account_vat = $this->PurchaseReturns->LedgerAccounts->get($invoiceBooking->ledger_account_for_vat);
		//pr($ledger_account_details->name); exit;
		$Em = new FinancialYearsController;
	    $financial_year_data = $Em->checkFinancialYear($invoiceBooking->created_on);
        $companies = $this->PurchaseReturns->Companies->find('list', ['limit' => 200]);
        $this->set(compact('purchaseReturn', 'invoiceBooking', 'companies','financial_year_data','v_LedgerAccount','ledger_account_details','ledger_account_vat','chkdate','st_company_id','financial_month_first','financial_month_last'));
        $this->set('_serialize', ['purchaseReturn']);
    }

	public function GstEdit($id=null){
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
       
		$purchase_return_id=@(int)$this->request->query('purchase-return');
		$purchaseReturn = $this->PurchaseReturns->get($purchase_return_id);
		
		$invoiceBooking = $this->PurchaseReturns->InvoiceBookings->get($purchaseReturn->invoice_booking_id, [
            'contain' => ['InvoiceBookingRows' => ['Items'],'Grns'=>['Companies','Vendors','GrnRows'=>['Items'],'PurchaseOrders'=>['PurchaseOrderRows']]]
        ]);
		
		
		
		$v_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Vendors','source_id'=>$invoiceBooking->vendor_id])->first();	
		$vendor_ledger_acc_id=$v_LedgerAccount->id;
			
		
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->PurchaseReturns->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		 
		$financial_month_first = $this->PurchaseReturns->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->PurchaseReturns->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();

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
		///save GST Purchase Return Start	
		//$purchaseReturn = $this->PurchaseReturns->newEntity();
		if ($this->request->is(['patch', 'post', 'put'])) {
			$ref_rows=@$this->request->data['ref_rows'];
			$purchaseReturn = $this->PurchaseReturns->patchEntity($purchaseReturn, $this->request->data);
			$purchaseReturn->company_id=$st_company_id;
			$purchaseReturn->invoice_booking_id=$invoiceBooking->id;
			$purchaseReturn->created_on= date("Y-m-d");
			$purchaseReturn->created_by=$s_employee_id;
			$purchaseReturn->transaction_date = date("Y-m-d",strtotime($purchaseReturn->transaction_date));
			$purchaseReturn->purchase_ledger_account=$invoiceBooking->purchase_ledger_account;
			$purchaseReturn->vendor_id=$invoiceBooking->vendor_id;	
			
				
				
			//pr($purchaseReturn); exit;
			if ($this->PurchaseReturns->save($purchaseReturn)) {
				
				$this->PurchaseReturns->Ledgers->deleteAll(['voucher_id' => $purchaseReturn->id, 'voucher_source' => 'Purchase Return','company_id'=>$st_company_id]);

				$this->PurchaseReturns->ItemLedgers->deleteAll(['source_id' => $purchaseReturn->id, 'source_model' => 'Purchase Return','company_id'=>$st_company_id]);
				
				$query = $this->PurchaseReturns->InvoiceBookings->InvoiceBookingRows->query();
						$query->update()
							->set(['purchase_return_quantity'=> 0 ])
							->where(['invoice_booking_id' => $invoiceBooking->id])
							->execute();
				
				//Ledger posting for SUPPLIER
				$c_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Vendors','source_id'=>$invoiceBooking->vendor_id])->first();
				$ledger = $this->PurchaseReturns->Ledgers->newEntity();
				$ledger->ledger_account_id = $c_LedgerAccount->id;
				$ledger->debit = $purchaseReturn->total;
				$ledger->credit =0;
				$ledger->voucher_id = $purchaseReturn->id;
				$ledger->company_id = $purchaseReturn->company_id;
				$ledger->transaction_date = $purchaseReturn->transaction_date;
				$ledger->voucher_source = 'Purchase Return';
				$this->PurchaseReturns->Ledgers->save($ledger);
				
				
				$ledger = $this->PurchaseReturns->Ledgers->newEntity();
				$ledger->ledger_account_id = $purchaseReturn->purchase_ledger_account;
				$ledger->debit = 0;
				$ledger->credit = $purchaseReturn->taxable_value;
				$ledger->voucher_id = $purchaseReturn->id;
				$ledger->company_id = $purchaseReturn->company_id;
				$ledger->voucher_source = 'Purchase Return';
				$ledger->transaction_date = $purchaseReturn->transaction_date;
				$this->PurchaseReturns->Ledgers->save($ledger);
				
				$ledger_account_for_discount=$this->PurchaseReturns->LedgerAccounts->find()->where(['invoice_booking_other_charge_post'=>1,'name'=>'Discount','company_id'=>$st_company_id])->first();
				$ledger = $this->PurchaseReturns->Ledgers->newEntity();
				$ledger->ledger_account_id = $ledger_account_for_discount['id'];
				
				if($purchaseReturn->total_other_charge < 0){
					$ledger->debit = abs($purchaseReturn->total_other_charge);
					$ledger->credit = 0;	
				}else if($purchaseReturn->total_other_charge > 0){ 
					$ledger->credit = abs($purchaseReturn->total_other_charge);
					$ledger->debit = 0;
				}
				$ledger->voucher_id = $purchaseReturn->id;
				$ledger->company_id = $purchaseReturn->company_id;
				$ledger->voucher_source = 'Purchase Return';
				$ledger->transaction_date = $purchaseReturn->transaction_date;
				if($purchaseReturn->total_other_charge != 0){
					$this->PurchaseReturns->Ledgers->save($ledger);
				}
			
				foreach($purchaseReturn->purchase_return_rows as $purchase_return_row)
				{
					if($purchase_return_row->cgst > 0){
					$cg_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$purchase_return_row->cgst_per])->first();
					$ledger = $this->PurchaseReturns->Ledgers->newEntity();
					$ledger->ledger_account_id = $cg_LedgerAccount->id;
					$ledger->debit = 0;
					$ledger->credit = $purchase_return_row->cgst;
					$ledger->voucher_id = $purchaseReturn->id;
					$ledger->voucher_source = 'Purchase Return';
					$ledger->company_id = $purchaseReturn->company_id;
					$ledger->transaction_date = $purchaseReturn->supplier_date; 
					$this->PurchaseReturns->Ledgers->save($ledger); 
					}
					if($purchase_return_row->sgst > 0){
						$s_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$purchase_return_row->sgst_per])->first();
						$ledger = $this->PurchaseReturns->Ledgers->newEntity();
						$ledger->ledger_account_id = $s_LedgerAccount->id;
						$ledger->debit = 0;
						$ledger->credit = $purchase_return_row->sgst;
						$ledger->voucher_id = $purchaseReturn->id;
						$ledger->voucher_source = 'Purchase Return';
						$ledger->company_id = $purchaseReturn->company_id;
						$ledger->transaction_date = $purchaseReturn->supplier_date;
						$this->PurchaseReturns->Ledgers->save($ledger); 
					}
					if($purchase_return_row->igst > 0){
						$i_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$purchase_return_row->igst_per])->first();
						$ledger = $this->PurchaseReturns->Ledgers->newEntity();
						$ledger->ledger_account_id = $i_LedgerAccount->id;
						$ledger->debit = 0;
						$ledger->credit = $purchase_return_row->igst;
						$ledger->voucher_id = $purchaseReturn->id;
						$ledger->voucher_source = 'Purchase Return';
						$ledger->company_id = $purchaseReturn->company_id;
						$ledger->transaction_date = $purchaseReturn->supplier_date;
						$this->PurchaseReturns->Ledgers->save($ledger); 
					}
								
				}
				
			$check_row=[]; $i=0;
				$purchaseReturn->check=array_filter($purchaseReturn->check);
				foreach($purchaseReturn->check as $invoice_booking_row_id){
					$check_row[$i]=$invoice_booking_row_id;
					$i++;
				}
				$i=0;
			foreach($purchaseReturn->purchase_return_rows as $purchase_return_row)
				{
						$item_id=$purchase_return_row['item_id'];
						$qty=$purchase_return_row['quantity'];
						$itemLedger_data = $this->PurchaseReturns->ItemLedgers->find()->where(['item_id'=>$item_id,'in_out'=>'In','company_id' => $st_company_id,'source_model'=>'Grns','source_id'=>$invoiceBooking->grn_id])->first();
						$InvoiceBookingRows = $this->PurchaseReturns->InvoiceBookings->InvoiceBookingRows->get($check_row[$i]);
						$InvoiceBookingRows->purchase_return_quantity=$qty;
						$this->PurchaseReturns->InvoiceBookings->InvoiceBookingRows->save($InvoiceBookingRows);

						//Insert in Item Ledger//
						$itemLedger = $this->PurchaseReturns->ItemLedgers->newEntity();
						$itemLedger->item_id = $item_id;
						$itemLedger->quantity = $qty;
						$itemLedger->source_model = 'Purchase Return';
						$itemLedger->source_id = $purchaseReturn->id;
						$itemLedger->in_out = 'Out';
						$itemLedger->rate = $itemLedger_data['rate'];
						$itemLedger->company_id = $purchaseReturn->company_id;
						$itemLedger->processed_on =$purchaseReturn->transaction_date;   
						$this->PurchaseReturns->ItemLedgers->save($itemLedger);
						$i++;
				}
				
					
				//Reference Number coding
					if(sizeof(@$ref_rows)>0){
						foreach($ref_rows as $ref_row){ 
							$ref_row=(object)$ref_row;
							if($ref_row->ref_type=='New Reference' or $ref_row->ref_type=='Advance Reference'){
								$query = $this->PurchaseReturns->ReferenceBalances->query();
								$query->insert(['ledger_account_id', 'reference_no', 'credit', 'debit'])
								->values([
									'ledger_account_id' => $v_LedgerAccount->id,
									'reference_no' => $ref_row->ref_no,
									'credit' => 0,
									'debit' => $ref_row->ref_amount
								]);
								$query->execute();
							}else{
								$ReferenceBalance=$this->PurchaseReturns->ReferenceBalances->find()->where(['ledger_account_id'=>$v_LedgerAccount->id,'reference_no'=>$ref_row->ref_no])->first();
								$ReferenceBalance=$this->PurchaseReturns->ReferenceBalances->get($ReferenceBalance->id);
								$ReferenceBalance->debit=$ReferenceBalance->debit+$ref_row->ref_amount;
								
								$this->PurchaseReturns->ReferenceBalances->save($ReferenceBalance);
							}
							
							$query = $this->PurchaseReturns->ReferenceDetails->query();
							$query->insert(['ledger_account_id', 'purchase_return_id', 'reference_no', 'credit', 'debit', 'reference_type'])
							->values([
								'ledger_account_id' => $v_LedgerAccount->id,
								'purchase_return_id' => $purchaseReturn->id,
								'reference_no' => $ref_row->ref_no,
								'credit' =>  0,
								'debit' =>$ref_row->ref_amount,
								'reference_type' => $ref_row->ref_type
							]);
							$query->execute();
						}
					}
					$this->Flash->success(__('The Purchase Return has been saved.'));
					return $this->redirect(['action' => 'index']);
				}
				
				else{
					pr($purchaseReturn); exit;
				}
			}
				//}
			///save GST Purchase Return End	

			
			
			$ledger_account_details = $this->PurchaseReturns->LedgerAccounts->get($invoiceBooking->purchase_ledger_account);
			
			
			
			
			$ReferenceDetails = $this->PurchaseReturns->ReferenceDetails->find()->where(['ledger_account_id'=>$v_LedgerAccount->id,'purchase_return_id'=>$purchase_return_id])->toArray();
		
		
		//pr($ReferenceDetails);exit;
			if(!empty($ReferenceDetails))
			{
				foreach($ReferenceDetails as $ReferenceDetail)
				{  //pr($ReferenceDetail->ledger_account_id); exit;
					$ReferenceBalances[] = $this->PurchaseReturns->InvoiceBookings->ReferenceBalances->find()->where(['ledger_account_id'=>$ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])->toArray();
				}
			}
			else{
				$ReferenceBalances='';
			}
			
			
			$Em = new FinancialYearsController;
			$financial_year_data = $Em->checkFinancialYear($invoiceBooking->created_on);
			$companies = $this->PurchaseReturns->Companies->find('list', ['limit' => 200]);
			$GstTaxes = $this->PurchaseReturns->InvoiceBookings->SaleTaxes->find()->where(['SaleTaxes.account_second_subgroup_id'=>6])->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				);
			$this->set(compact('purchaseReturn', 'invoiceBooking', 'companies','financial_year_data','v_LedgerAccount','ledger_account_details','ledger_account_vat','chkdate','st_company_id','financial_month_first','financial_month_last','GstTaxes','ReferenceDetails','ReferenceBalances'));
			$this->set('_serialize', ['purchaseReturn']);
	}
	
	
	public function GstAdd(){
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
       
		$invoice_booking_id=@(int)$this->request->query('invoiceBooking');
		$invoiceBooking = $this->PurchaseReturns->InvoiceBookings->get($invoice_booking_id, [
            'contain' => ['InvoiceBookingRows' => ['Items'],'Grns'=>['Companies','Vendors','GrnRows'=>['Items'],'PurchaseOrders'=>['PurchaseOrderRows']]]
        ]);
		
		$v_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Vendors','source_id'=>$invoiceBooking->vendor_id])->first();	
			$vendor_ledger_acc_id=$v_LedgerAccount->id;
		//pr($invoiceBooking);exit;
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->PurchaseReturns->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		 
		$financial_month_first = $this->PurchaseReturns->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->PurchaseReturns->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();

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
		///save GST Purchase Return Start	
		$purchaseReturn = $this->PurchaseReturns->newEntity();
		if ($this->request->is('post')) {
			$ref_rows=@$this->request->data['ref_rows'];
			$purchaseReturn = $this->PurchaseReturns->patchEntity($purchaseReturn, $this->request->data);
			$purchaseReturn->company_id=$st_company_id;
			$purchaseReturn->invoice_booking_id=$invoice_booking_id;
			$purchaseReturn->created_on= date("Y-m-d");
			$purchaseReturn->created_by=$s_employee_id;
			$purchaseReturn->transaction_date = date("Y-m-d",strtotime($purchaseReturn->transaction_date));
			$purchaseReturn->purchase_ledger_account=$invoiceBooking->purchase_ledger_account;
			$purchaseReturn->vendor_id=$invoiceBooking->vendor_id;	
			$last_pr_no=$this->PurchaseReturns->find()->select(['voucher_no'])->where(['company_id' => $st_company_id])->order(['voucher_no' => 'DESC'])->first();
			if($last_pr_no){
				$purchaseReturn->voucher_no=$last_pr_no->voucher_no+1;
			}else{
				$purchaseReturn->voucher_no=1;
			}
			
			
			if ($this->PurchaseReturns->save($purchaseReturn)) {
				//Ledger posting for SUPPLIER
				$c_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Vendors','source_id'=>$invoiceBooking->vendor_id])->first();
				$ledger = $this->PurchaseReturns->Ledgers->newEntity();
				$ledger->ledger_account_id = $c_LedgerAccount->id;
				$ledger->debit = $purchaseReturn->total;
				$ledger->credit =0;
				$ledger->voucher_id = $purchaseReturn->id;
				$ledger->company_id = $purchaseReturn->company_id;
				$ledger->transaction_date = $purchaseReturn->transaction_date;
				$ledger->voucher_source = 'Purchase Return';
				$this->PurchaseReturns->Ledgers->save($ledger);
				
				
				$ledger = $this->PurchaseReturns->Ledgers->newEntity();
				$ledger->ledger_account_id = $purchaseReturn->purchase_ledger_account;
				$ledger->debit = 0;
				$ledger->credit = $purchaseReturn->taxable_value;
				$ledger->voucher_id = $purchaseReturn->id;
				$ledger->company_id = $purchaseReturn->company_id;
				$ledger->voucher_source = 'Purchase Return';
				$ledger->transaction_date = $purchaseReturn->transaction_date;
				$this->PurchaseReturns->Ledgers->save($ledger);
				
				$ledger_account_for_discount=$this->PurchaseReturns->LedgerAccounts->find()->where(['invoice_booking_other_charge_post'=>1,'name'=>'Discount','company_id'=>$st_company_id])->first();
				$ledger = $this->PurchaseReturns->Ledgers->newEntity();
				$ledger->ledger_account_id = $ledger_account_for_discount['id'];
				
				if($purchaseReturn->total_other_charge < 0){
					$ledger->debit = abs($purchaseReturn->total_other_charge);
					$ledger->credit = 0;	
				}else if($purchaseReturn->total_other_charge > 0){ 
					$ledger->credit = abs($purchaseReturn->total_other_charge);
					$ledger->debit = 0;
				}
				$ledger->voucher_id = $purchaseReturn->id;
				$ledger->company_id = $purchaseReturn->company_id;
				$ledger->voucher_source = 'Purchase Return';
				$ledger->transaction_date = $purchaseReturn->transaction_date;
				if($purchaseReturn->total_other_charge != 0){
					$this->PurchaseReturns->Ledgers->save($ledger);
				}
			
				foreach($purchaseReturn->purchase_return_rows as $purchase_return_row)
				{
					if($purchase_return_row->cgst > 0){
					$cg_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$purchase_return_row->cgst_per])->first();
					$ledger = $this->PurchaseReturns->Ledgers->newEntity();
					$ledger->ledger_account_id = $cg_LedgerAccount->id;
					$ledger->debit = 0;
					$ledger->credit = $purchase_return_row->cgst;
					$ledger->voucher_id = $purchaseReturn->id;
					$ledger->voucher_source = 'Purchase Return';
					$ledger->company_id = $purchaseReturn->company_id;
					$ledger->transaction_date = $purchaseReturn->supplier_date; 
					$this->PurchaseReturns->Ledgers->save($ledger); 
					}
					if($purchase_return_row->sgst > 0){
						$s_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$purchase_return_row->sgst_per])->first();
						$ledger = $this->PurchaseReturns->Ledgers->newEntity();
						$ledger->ledger_account_id = $s_LedgerAccount->id;
						$ledger->debit = 0;
						$ledger->credit = $purchase_return_row->sgst;
						$ledger->voucher_id = $purchaseReturn->id;
						$ledger->voucher_source = 'Purchase Return';
						$ledger->company_id = $purchaseReturn->company_id;
						$ledger->transaction_date = $purchaseReturn->supplier_date;
						$this->PurchaseReturns->Ledgers->save($ledger); 
					}
					if($purchase_return_row->igst > 0){
						$i_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$purchase_return_row->igst_per])->first();
						$ledger = $this->PurchaseReturns->Ledgers->newEntity();
						$ledger->ledger_account_id = $i_LedgerAccount->id;
						$ledger->debit = 0;
						$ledger->credit = $purchase_return_row->igst;
						$ledger->voucher_id = $purchaseReturn->id;
						$ledger->voucher_source = 'Purchase Return';
						$ledger->company_id = $purchaseReturn->company_id;
						$ledger->transaction_date = $purchaseReturn->supplier_date;
						$this->PurchaseReturns->Ledgers->save($ledger); 
					}
								
				}
				
			$check_row=[]; $i=0;
				$purchaseReturn->check=array_filter($purchaseReturn->check);
				foreach($purchaseReturn->check as $invoice_booking_row_id){
					$check_row[$i]=$invoice_booking_row_id;
					$i++;
				}
				$i=0;
			foreach($purchaseReturn->purchase_return_rows as $purchase_return_row)
				{
						$item_id=$purchase_return_row['item_id'];
						$qty=$purchase_return_row['quantity'];
						$itemLedger_data = $this->PurchaseReturns->ItemLedgers->find()->where(['item_id'=>$item_id,'in_out'=>'In','company_id' => $st_company_id,'source_model'=>'Grns','source_id'=>$invoiceBooking->grn_id])->first();
						$InvoiceBookingRows = $this->PurchaseReturns->InvoiceBookings->InvoiceBookingRows->get($check_row[$i]);
						$InvoiceBookingRows->purchase_return_quantity=$qty;
						$this->PurchaseReturns->InvoiceBookings->InvoiceBookingRows->save($InvoiceBookingRows);

						//Insert in Item Ledger//
						$itemLedger = $this->PurchaseReturns->ItemLedgers->newEntity();
						$itemLedger->item_id = $item_id;
						$itemLedger->quantity = $qty;
						$itemLedger->source_model = 'Purchase Return';
						$itemLedger->source_id = $purchaseReturn->id;
						$itemLedger->in_out = 'Out';
						$itemLedger->rate = $itemLedger_data['rate'];
						$itemLedger->company_id = $purchaseReturn->company_id;
						$itemLedger->processed_on =$purchaseReturn->transaction_date;   
						$this->PurchaseReturns->ItemLedgers->save($itemLedger);
						$i++;
				}
				
				$query = $this->PurchaseReturns->InvoiceBookings->query();
						$query->update()
							->set(['purchase_return_status'=>'Yes'])
							->where(['id' => $invoiceBooking->id])
							->execute();
					
				//Reference Number coding
				//pr($purchaseReturn->ref_rows); exit;
					if(sizeof(@$ref_rows) > 0){
						foreach($ref_rows as $ref_row){ 
						
							$ref_row=(object)$ref_row;
							if($ref_row->ref_type=='New Reference' or $ref_row->ref_type=='Advance Reference'){
								$query = $this->PurchaseReturns->ReferenceBalances->query();
								$query->insert(['ledger_account_id', 'reference_no', 'credit', 'debit'])
								->values([
									'ledger_account_id' => $v_LedgerAccount->id,
									'reference_no' => $ref_row->ref_no,
									'credit' => 0,
									'debit' => $ref_row->ref_amount
								]);
								$query->execute();
							}else{
								$ReferenceBalance=$this->PurchaseReturns->ReferenceBalances->find()->where(['ledger_account_id'=>$v_LedgerAccount->id,'reference_no'=>$ref_row->ref_no])->first();
								$ReferenceBalance=$this->PurchaseReturns->ReferenceBalances->get($ReferenceBalance->id);
								$ReferenceBalance->debit=$ReferenceBalance->debit+$ref_row->ref_amount;
								
								$this->PurchaseReturns->ReferenceBalances->save($ReferenceBalance);
							}
							
							$query = $this->PurchaseReturns->ReferenceDetails->query();
							$query->insert(['ledger_account_id', 'purchase_return_id', 'reference_no', 'credit', 'debit', 'reference_type'])
							->values([
								'ledger_account_id' => $v_LedgerAccount->id,
								'purchase_return_id' => $purchaseReturn->id,
								'reference_no' => $ref_row->ref_no,
								'credit' =>  0,
								'debit' =>$ref_row->ref_amount,
								'reference_type' => $ref_row->ref_type
							]);
							$query->execute();
						}
					}
					$this->Flash->success(__('The Purchase Return has been saved.'));
					return $this->redirect(['action' => 'index']);
				}
				
				else{
					pr($purchaseReturn); exit;
				}
			}
				//}
			///save GST Purchase Return End	

			
			
			$ledger_account_details = $this->PurchaseReturns->LedgerAccounts->get($invoiceBooking->purchase_ledger_account);
			
			
			
			
			$ReferenceDetails = $this->PurchaseReturns->InvoiceBookings->ReferenceDetails->find()->where(['ledger_account_id'=>$vendor_ledger_acc_id,'invoice_booking_id'=>$invoiceBooking->id])->toArray();
		
			if(!empty($ReferenceDetails))
			{
				foreach($ReferenceDetails as $ReferenceDetail)
				{  //pr($ReferenceDetail->ledger_account_id); exit;
					$ReferenceBalances[] = $this->PurchaseReturns->InvoiceBookings->ReferenceBalances->find()->where(['ledger_account_id'=>$ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])->toArray();
				}
			}
			else{
				$ReferenceBalances='';
			}
			
			$Em = new FinancialYearsController;
			$financial_year_data = $Em->checkFinancialYear($invoiceBooking->created_on);
			$companies = $this->PurchaseReturns->Companies->find('list', ['limit' => 200]);
			$GstTaxes = $this->PurchaseReturns->InvoiceBookings->SaleTaxes->find()->where(['SaleTaxes.account_second_subgroup_id'=>6])->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				);
			$this->set(compact('purchaseReturn', 'invoiceBooking', 'companies','financial_year_data','v_LedgerAccount','ledger_account_details','ledger_account_vat','chkdate','st_company_id','financial_month_first','financial_month_last','GstTaxes','ReferenceDetails','ReferenceBalances'));
        $this->set('_serialize', ['purchaseReturn']);
	}
	
	public function gstView($id=null){
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		 
		$purchaseReturn = $this->PurchaseReturns->get($id,[
		'contain'=>['Vendors','Creator','Companies','PurchaseReturnRows'=>['Items'],'InvoiceBookings'=>['Creator']]
		]);
		//pr($purchaseReturn); exit;
		$purchase_acc='';
		if($purchaseReturn->purchase_ledger_account > 0){
			$purchase_acc=$this->PurchaseReturns->LedgerAccounts->get($purchaseReturn->purchase_ledger_account);
		}
		
		$v_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Vendors','source_id'=>$purchaseReturn->vendor_id])->first();
		$ReferenceDetails=$this->PurchaseReturns->ReferenceDetails->find()->where(['ledger_account_id'=>$v_LedgerAccount->id,'purchase_return_id'=>$purchaseReturn->id]);
		
		$this->set(compact('purchaseReturn','purchase_acc','v_LedgerAccount','ReferenceDetails'));
		
	}
	
    /**
     * Edit method
     *
     * @param string|null $id Purchase Return id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {

		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
        $purchaseReturn = $this->PurchaseReturns->newEntity();
		$invoice_booking_id=@(int)$this->request->query('invoice-booking');
		$invoiceBooking = $this->PurchaseReturns->InvoiceBookings->get($invoice_booking_id, [
            'contain' => ['InvoiceBookingRows' => ['Items'],'Grns'=>['Companies','Vendors','GrnRows'=>['Items'],'PurchaseOrders'=>['PurchaseOrderRows']]]
        ]);
		//pr($invoiceBooking); exit;
		$v_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Vendors','source_id'=>$invoiceBooking->vendor_id])->first();
		
		$purchase_return_id=$invoiceBooking->purchase_return_id;
		$ReferenceDetails=$this->PurchaseReturns->ReferenceDetails->find()->where(['ledger_account_id'=>$v_LedgerAccount->id,'purchase_return_id'=>$purchase_return_id]);
//pr($purchase_return_id); exit;
		$purchaseReturn = $this->PurchaseReturns->get($purchase_return_id, [
            'contain' => ['PurchaseReturnRows']
        ]);

			  	$st_year_id = $session->read('st_year_id');
		$financial_year = $this->PurchaseReturns->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		 
		
		$financial_month_first = $this->PurchaseReturns->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->PurchaseReturns->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();

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

		
		//pr($ReferenceDetails->toArray()); exit;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $purchaseReturn = $this->PurchaseReturns->patchEntity($purchaseReturn, $this->request->data);
			$ref_rows=@$this->request->data['ref_rows'];
			$purchaseReturn->purchase_ledger_account=$invoiceBooking->purchase_ledger_account;
			$purchaseReturn->vendor_id=$invoiceBooking->vendor_id;
			$purchaseReturn->transaction_date = date("Y-m-d",strtotime($this->request->data['transaction_date']));
			$purchaseReturn->edited_on = date("Y-m-d"); 
			$purchaseReturn->edited_by=$this->viewVars['s_employee_id'];
			//pr($invoiceBooking['grn_id']); exit;
			
            if ($this->PurchaseReturns->save($purchaseReturn)) {
				$this->PurchaseReturns->Ledgers->deleteAll(['voucher_id' => $purchaseReturn->id, 'voucher_source' => 'Purchase Return']);
				$this->PurchaseReturns->ItemLedgers->deleteAll(['source_id' => $purchaseReturn->id, 'source_model' => 'Purchase Return','company_id'=>$st_company_id]);
			
				foreach($purchaseReturn->purchase_return_rows as $purchase_return_row){
					
					
					$results=$this->PurchaseReturns->ItemLedgers->find()->where(['ItemLedgers.item_id' => $purchase_return_row->item_id,'ItemLedgers.in_out' => 'In','rate_updated' => 'Yes','company_id' => $st_company_id,'source_model'=>'Grns','source_id'=>$invoiceBooking['grn_id']])->first();
					
					$itemLedger = $this->PurchaseReturns->ItemLedgers->newEntity();
					$itemLedger->item_id = $purchase_return_row->item_id;
					$itemLedger->quantity = $purchase_return_row->quantity;
					$itemLedger->source_model = 'Purchase Return';
					$itemLedger->source_id = $purchaseReturn->id;
					$itemLedger->in_out = 'Out';
					$itemLedger->rate = $results->rate;
					$itemLedger->company_id = $st_company_id;
					$itemLedger->processed_on = $purchaseReturn->transaction_date;
					$this->PurchaseReturns->ItemLedgers->save($itemLedger);
				}
				$vat_amounts=[]; $total_amounts=[];
				foreach($invoiceBooking->invoice_booking_rows as $invoice_booking_row){
					$amount=$invoice_booking_row->unit_rate_from_po*$invoice_booking_row->quantity;

					$amount=$amount+$invoice_booking_row->misc;
					if($invoice_booking_row->discount_per==1){
						$amount=$amount*((100-$invoice_booking_row->discount)/100);
					}else{
						$amount=$amount-$invoice_booking_row->discount;
					}

					if($invoice_booking_row->pnf_per==1){
						$amount=$amount*((100+$invoice_booking_row->pnf)/100);
					}else{
						$amount=$amount+$invoice_booking_row->pnf;
					}

					$amount=$amount*((100+	$invoice_booking_row->excise_duty)/100);
					$amountofVAT=($amount*$invoice_booking_row->sale_tax)/100;
					$amount=$amount*((100+$invoice_booking_row->sale_tax)/100);

					$vat_amounts[$invoice_booking_row->item_id]=$amountofVAT/$invoice_booking_row->quantity;
					$amount=$amount+$invoice_booking_row->other_charges;
					$total_amounts[$invoice_booking_row->item_id]=$amount/$invoice_booking_row->quantity;
				}
				$total_vat_item=0;
				$total_amounts_item=0;
				foreach($purchaseReturn->purchase_return_rows as $purchase_return_row){
					$total_vat=$vat_amounts[$purchase_return_row->item_id]*$purchase_return_row->quantity;
					$total_vat_item=$total_vat_item+$total_vat;
					$total_amt=$total_amounts[$purchase_return_row->item_id]*$purchase_return_row->quantity;
					//pr($total_amt); exit;
					$total_amounts_item=$total_amounts_item+$total_amt;
					$query = $this->PurchaseReturns->PurchaseReturnRows->query();
						$query->update()
							->set(['vat_per_item'=>$vat_amounts[$purchase_return_row->item_id],])
							->where(['purchase_return_id' => $purchaseReturn->id,'item_id'=>$purchase_return_row->item_id])
							->execute();

				}

				foreach($purchaseReturn->purchase_return_rows as $purchase_return_row){
						$query = $this->PurchaseReturns->InvoiceBookings->InvoiceBookingRows->query();
						$query->update()
							->set(['purchase_return_quantity'=>$purchase_return_row->quantity])
							->where(['invoice_booking_id' => $invoiceBooking->id,'item_id'=>$purchase_return_row->item_id])
							->execute();
				}
						
					$query = $this->PurchaseReturns->InvoiceBookings->query();
					$query->update()
					->set(['purchase_return_status' => 'Yes','purchase_return_id'=>$purchaseReturn->id])
					->where(['id' => $invoiceBooking->id])
					->execute();

					$query = $this->PurchaseReturns->query();
					$query->update()
					->set(['invoice_booking_id'=>$invoiceBooking->id])
					->where(['id' => $purchaseReturn->id])
					->execute();
					$cst_purchase=0;
					if($st_company_id=='25'){
						$cst_purchase=35;
					}else if($st_company_id=='26'){
						$cst_purchase=161;
					}else if($st_company_id=='27'){
						$cst_purchase=309;
					}
				
				if($invoiceBooking->purchase_ledger_account==$cst_purchase){
					//ledger posting for PURCHASE ACCOUNT
					$ledger = $this->PurchaseReturns->Ledgers->newEntity();
					$ledger->ledger_account_id = $invoiceBooking->purchase_ledger_account;
					$ledger->debit = 0;
					$ledger->credit = $total_amounts_item;
					$ledger->voucher_id = $purchaseReturn->id;
					$ledger->company_id = $st_company_id;
					$ledger->voucher_source = 'Purchase Return';
					$ledger->transaction_date =$purchaseReturn->transaction_date;
					$this->PurchaseReturns->Ledgers->save($ledger);
				}else{
					//ledger posting for PURCHASE ACCOUNT
					$ledger = $this->PurchaseReturns->Ledgers->newEntity();
					$ledger->ledger_account_id = $invoiceBooking->purchase_ledger_account;
					$ledger->debit = 0;
					$ledger->credit = $total_amounts_item-$total_vat_item;
					$ledger->voucher_id = $purchaseReturn->id;
					$ledger->company_id = $st_company_id;
					$ledger->voucher_source = 'Purchase Return';
					$ledger->transaction_date =$purchaseReturn->transaction_date;
					$this->PurchaseReturns->Ledgers->save($ledger);
					
					//ledger posting for PURCHASE ACCOUNT
					$ledger = $this->PurchaseReturns->Ledgers->newEntity();
					$ledger->ledger_account_id = $invoiceBooking->ledger_account_for_vat;
					$ledger->debit = 0;
					$ledger->credit = $total_vat_item;
					$ledger->voucher_id = $purchaseReturn->id;
					$ledger->company_id = $st_company_id;
					$ledger->voucher_source = 'Purchase Return';
					$ledger->transaction_date = $purchaseReturn->transaction_date;
					$this->PurchaseReturns->Ledgers->save($ledger);
				}

				$v_LedgerAccount=$this->PurchaseReturns->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'Vendors','source_id'=>$invoiceBooking->vendor_id])->first();
				$ledger = $this->PurchaseReturns->Ledgers->newEntity();
				$ledger->ledger_account_id = $v_LedgerAccount->id;
				$ledger->debit = $total_amounts_item;
				$ledger->credit =0;
				$ledger->voucher_id = $purchaseReturn->id;
				$ledger->company_id = $invoiceBooking->company_id;
				$ledger->transaction_date = $purchaseReturn->transaction_date;
				$ledger->voucher_source = 'Purchase Return';
				$this->PurchaseReturns->Ledgers->save($ledger);
				
				if(sizeof(@$ref_rows)>0){
					//pr($ref_rows); exit;
					foreach($ref_rows as $ref_row){
							$ref_row=(object)$ref_row;
							//pr($ref_row); exit;
							$ReferenceDetail=$this->PurchaseReturns->ReferenceDetails->find()->where(['ledger_account_id'=>$v_LedgerAccount->id,'reference_no'=>$ref_row->ref_no,'purchase_return_id'=>$purchaseReturn->id])->first();
							
							if($ReferenceDetail){ //pr($ref_row->ref_old_amount); exit;
								$ReferenceBalance=$this->PurchaseReturns->ReferenceBalances->find()->where(['ledger_account_id'=>$v_LedgerAccount->id,'reference_no'=>$ref_row->ref_no])->first();
								$ReferenceBalance=$this->PurchaseReturns->ReferenceBalances->get($ReferenceBalance->id);
								$ReferenceBalance->debit=$ReferenceBalance->debit-@$ref_row->ref_old_amount+$ref_row->ref_amount;
								$this->PurchaseReturns->ReferenceBalances->save($ReferenceBalance);
								
								$ReferenceDetail=$this->PurchaseReturns->ReferenceDetails->find()->where(['ledger_account_id'=>$v_LedgerAccount->id,'reference_no'=>$ref_row->ref_no,'purchase_return_id'=>$purchaseReturn->id])->first();
								
								$ReferenceDetail=$this->PurchaseReturns->ReferenceDetails->get($ReferenceDetail->id);
								$ReferenceDetail->debit=$ReferenceDetail->debit-$ref_row->ref_old_amount+$ref_row->ref_amount;
								$this->PurchaseReturns->ReferenceDetails->save($ReferenceDetail);
							}else{
								if($ref_row->ref_type=='New Reference' or $ref_row->ref_type=='Advance Reference'){
									$query = $this->PurchaseReturns->ReferenceBalances->query();
									$query->insert(['ledger_account_id', 'reference_no', 'credit', 'debit'])
									->values([
										'ledger_account_id' => $v_LedgerAccount->id,
										'reference_no' => $ref_row->ref_no,
										'credit' => 0,
										'debit' => $ref_row->ref_amount
									])
									->execute();
									
								}else{
									$ReferenceBalance=$this->PurchaseReturns->ReferenceBalances->find()->where(['ledger_account_id'=>$v_LedgerAccount->id,'reference_no'=>$ref_row->ref_no])->first();
									$ReferenceBalance=$this->PurchaseReturns->ReferenceBalances->get($ReferenceBalance->id);
									$ReferenceBalance->debit=$ReferenceBalance->debit+$ref_row->ref_amount;
									
									$this->PurchaseReturns->ReferenceBalances->save($ReferenceBalance);
								}
								

								$query = $this->PurchaseReturns->ReferenceDetails->query();
								$query->insert(['ledger_account_id', 'purchase_return_id', 'reference_no', 'credit', 'debit', 'reference_type'])
								->values([
									'ledger_account_id' => $v_LedgerAccount->id,
									'purchase_return_id' => $purchaseReturn->id,
									'reference_no' => $ref_row->ref_no,
									'credit' => 0,
									'debit' => $ref_row->ref_amount,
									'reference_type' => $ref_row->ref_type
								])
								->execute();
								
							}
						}
					}

                $this->Flash->success(__('The purchase return has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The purchase return could not be saved. Please, try again.'));
            }
        }
		$ledger_account_details = $this->PurchaseReturns->LedgerAccounts->get($invoiceBooking->purchase_ledger_account);
		$ledger_account_vat = $this->PurchaseReturns->LedgerAccounts->get($invoiceBooking->ledger_account_for_vat);
		$Em = new FinancialYearsController;
	    $financial_year_data = $Em->checkFinancialYear($invoiceBooking->created_on);			
        $invoiceBookings = $this->PurchaseReturns->InvoiceBookings->find('list', ['limit' => 200]);
        $companies = $this->PurchaseReturns->Companies->find('list', ['limit' => 200]);
        $this->set(compact('purchaseReturn', 'invoiceBookings', 'companies','invoiceBooking','v_LedgerAccount','financial_year_data','ReferenceDetails','ledger_account_details','ledger_account_vat','chkdate','st_company_id','financial_month_first','financial_month_last'));
        $this->set('_serialize', ['purchaseReturn']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Purchase Return id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $purchaseReturn = $this->PurchaseReturns->get($id);
        if ($this->PurchaseReturns->delete($purchaseReturn)) {
            $this->Flash->success(__('The purchase return has been deleted.'));
        } else {
            $this->Flash->error(__('The purchase return could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	public function fetchRefNumbers($ledger_account_id){
		$this->viewBuilder()->layout('');
		$ReferenceBalances=$this->PurchaseReturns->ReferenceBalances->find()->where(['ledger_account_id'=>$ledger_account_id]);
		$this->set(compact('ReferenceBalances','cr_dr'));
	}
	function checkRefNumberUnique($received_from_id,$i){
		$reference_no=$this->request->query['ref_rows'][$i]['ref_no'];
		$ReferenceBalances=$this->PurchaseReturns->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id,'reference_no'=>$reference_no]);
		if($ReferenceBalances->count()==0){
			echo 'true';
		}else{
			echo 'false';
		}
		exit;
	}
	function checkRefNumberUniqueEdit($received_from_id,$i,$is_old){
		$reference_no=$this->request->query['ref_rows'][$i]['ref_no'];
		$ReferenceBalances=$this->PurchaseReturns->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id,'reference_no'=>$reference_no]);
		if($ReferenceBalances->count()==1 && $is_old=="yes"){
			echo 'true';
		}elseif($ReferenceBalances->count()==0){
			echo 'true';
		}else{
			echo 'false';
		}
		exit;
	}
	public function fetchRefNumbersEdit($received_from_id=null,$reference_no=null,$debit=null){
		$this->viewBuilder()->layout('');
		$ReferenceBalances=$this->PurchaseReturns->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id]);
		$this->set(compact('ReferenceBalances', 'reference_no', 'debit'));
	}

	function deleteOneRefNumbers(){
		$old_received_from_id=$this->request->query['old_received_from_id'];
		$purchase_return_id=$this->request->query['purchase_return_id'];
		$old_ref=$this->request->query['old_ref'];
		$old_ref_type=$this->request->query['old_ref_type'];
		//pr($old_ref_type); exit;
		if($old_ref_type=="New Reference" || $old_ref_type=="Advance Reference"){
			$this->PurchaseReturns->ReferenceBalances->deleteAll(['ledger_account_id'=>$old_received_from_id,'reference_no'=>$old_ref]);
			$this->PurchaseReturns->ReferenceDetails->deleteAll(['ledger_account_id'=>$old_received_from_id,'reference_no'=>$old_ref]);
		}elseif($old_ref_type=="Against Reference"){
			$ReferenceDetail=$this->PurchaseReturns->ReferenceDetails->find()->where(['ledger_account_id'=>$old_received_from_id,'purchase_return_id'=>$purchase_return_id,'reference_no'=>$old_ref])->first();
			if(!empty($ReferenceDetail->credit)){
				$ReferenceBalance=$this->PurchaseReturns->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
				$ReferenceBalance=$this->PurchaseReturns->ReferenceBalances->get($ReferenceBalance->id);
				$ReferenceBalance->credit=$ReferenceBalance->credit-$ReferenceDetail->credit;
				$this->PurchaseReturns->ReferenceBalances->save($ReferenceBalance);
			}elseif(!empty($ReferenceDetail->debit)){
				$ReferenceBalance=$this->PurchaseReturns->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
				$ReferenceBalance=$this->PurchaseReturns->ReferenceBalances->get($ReferenceBalance->id);
				$ReferenceBalance->debit=$ReferenceBalance->debit-$ReferenceDetail->debit;
				$this->PurchaseReturns->ReferenceBalances->save($ReferenceBalance);
			}
			$RDetail=$this->PurchaseReturns->ReferenceDetails->get($ReferenceDetail->id);
			$this->PurchaseReturns->ReferenceDetails->delete($RDetail);
		}
		
		exit;
	}
	
	public function exportSaleExcel(){
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$this->set(compact('From','To'));
		$where=[];
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['transaction_date <=']=$To;
		}
		
		$this->viewBuilder()->layout('');
		$PurchaseReturns = $this->PurchaseReturns->find()->contain(['InvoiceBookings'=>['InvoiceBookingRows'],'PurchaseReturnRows','Vendors'])->order(['PurchaseReturns.id' => 'DESC'])->where(['PurchaseReturns.company_id'=>$st_company_id]);
		//$InvoiceBookings=$this->PurchaseReturns->InvoiceBookings->find()->contain(['InvoiceBookingRows','Vendors']);
		/* foreach($PurchaseReturns->invoice_booking_rows as $invoice_booking_row ) {
			
		} */
		//pr($PurchaseReturns->toArray()); exit;
		$this->set(compact('PurchaseReturns'));
	}
	
	public function purchaseReturnReport(){
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$this->set(compact('From','To'));
		$where=[];
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['PurchaseReturns.transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['PurchaseReturns.transaction_date <=']=$To;
		}
		
		$this->viewBuilder()->layout('index_layout');
		$PurchaseReturns = $this->PurchaseReturns->find()->contain(['InvoiceBookings'=>['InvoiceBookingRows'],'PurchaseReturnRows','Vendors'])->order(['PurchaseReturns.id' => 'DESC'])->where(['PurchaseReturns.company_id'=>$st_company_id]);
		//$InvoiceBookings=$this->PurchaseReturns->InvoiceBookings->find()->contain(['InvoiceBookingRows','Vendors']);
		/* foreach($PurchaseReturns->invoice_booking_rows as $invoice_booking_row ) {
			
		} */
		//pr($PurchaseReturns->toArray()); exit;
		$this->set(compact('PurchaseReturns','url'));
	}
}
