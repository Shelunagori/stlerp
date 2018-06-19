<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * DebitNotes Controller
 *
 * @property \App\Model\Table\DebitNotesTable $DebitNotes
 */
class DebitNotesController extends AppController
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
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		
		$st_year_id = $session->read('st_year_id');
		$where = [];
        $vendor_id = $this->request->query('vendor_id');
        $customers_id = $this->request->query('customers_id');
        $vouch_no = $this->request->query('vouch_no');
		$From = $this->request->query('From');
		$To = $this->request->query('To');
		
		$this->set(compact('vouch_no','From','To','customers_id','vendor_id'));
		
		if(!empty($vouch_no)){
			$where['DebitNotes.voucher_no LIKE']=$vouch_no;
		}
		if(!empty($customers_id)){
			$where['DebitNotes.customer_id']=$customers_id;
		}
		if(!empty($vendor_id)){
			$where['DebitNotes.vendor_id']=$vendor_id;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['DebitNotes.transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['DebitNotes.transaction_date <=']=$To;
		}
        $this->paginate = [
            'contain' => ['CustomerSuppilers','Creator','Companies','DebitNotesRows'=>['ReceivedFroms'],'Heads']
        ];
        
		$debitNotes = $this->paginate($this->DebitNotes->find()->where(['DebitNotes.company_id'=>$st_company_id])->where($where)->order(['voucher_no'=>'DESC']));
         
		//pr($debitNotes->toArray());exit;
		
		$this->set(compact('debitNotes','url'));
        $this->set('_serialize', ['debitNotes']);

	}
	
	public function excelExport(){
		$this->viewBuilder()->layout('');
        
        $session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		
		
		$st_year_id = $session->read('st_year_id');
		$where = [];
        $vendor_id = $this->request->query('vendor_id');
        $customers_id = $this->request->query('customers_id');
        $vouch_no = $this->request->query('vouch_no');
		$From = $this->request->query('From');
		$To = $this->request->query('To');
		
		$this->set(compact('vouch_no','From','To','customers_id','vendor_id'));
		
		if(!empty($vouch_no)){
			$where['DebitNotes.voucher_no LIKE']=$vouch_no;
		}
		if(!empty($customers_id)){
			$where['DebitNotes.customer_id']=$customers_id;
		}
		if(!empty($vendor_id)){
			$where['DebitNotes.vendor_id']=$vendor_id;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['DebitNotes.transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['DebitNotes.transaction_date <=']=$To;
		}
        
		$debitNotes =$this->DebitNotes->find()->where(['DebitNotes.company_id'=>$st_company_id])->where($where)->contain(['CustomerSuppilers','Creator','Companies','DebitNotesRows'=>['ReceivedFroms'],'Heads'])->order(['voucher_no'=>'DESC']);
         
		//pr($debitNotes->toArray());exit;
		
		$this->set(compact('debitNotes','url'));
        $this->set('_serialize', ['debitNotes']);
	}

    /**
     * View method
     *
     * @param string|null $id Debit Note id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$id = $this->EncryptingDecrypting->decryptData($id);
        $debitNote = $this->DebitNotes->get($id, [
            'contain' => ['CustomerSuppilers','Creator','Companies','DebitNotesRows'=>['ReceivedFroms'],'Heads']
        ]);
		
		//pr($debitNote);exit;
		$ReferenceDetails=$this->DebitNotes->ReferenceDetails->find()->where(['ReferenceDetails.debit_note_id' => $id])->toArray();
		
		$this->set('debitNote', $debitNote);
		 $this->set(compact('ReferenceDetails'));
        $this->set('_serialize', ['debitNote']);
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
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->DebitNotes->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->DebitNotes->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->DebitNotes->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		
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
				
			$last_voucher_no_sr=$this->DebitNotes->PurchaseReturns->find()->select(['voucher_no'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['voucher_no' => 'DESC'])->first();
			$last_voucher_no_credit_note=$this->DebitNotes->find()->select(['voucher_no'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['voucher_no' => 'DESC'])->first();
			
			if(@$last_voucher_no_credit_note->voucher_no > @$last_voucher_no_sr->voucher_no){
				$last_voucher_no=$last_voucher_no_credit_note->voucher_no;
			}else{
				$last_voucher_no=$last_voucher_no_sr->voucher_no;
			}
			
			if($last_voucher_no){
				$voucher_no=$last_voucher_no+1;
			}else{
				$voucher_no=1;
			}
		//pr($voucher_no); exit;
        $debitNote = $this->DebitNotes->newEntity();
		
		if ($this->request->is('post')) {
			$debitNote = $this->DebitNotes->patchEntity($debitNote, $this->request->data);
			$debitNote->created_on=date("Y-m-d");
			$debitNote->transaction_date=date("Y-m-d",strtotime($this->request->data['transaction_date']));
			$debitNote->company_id=$st_company_id;
			$debitNote->created_by=$s_employee_id;
			$debitNote->financial_year_id=$st_year_id;
			
			$last_voucher_no_sr=$this->DebitNotes->PurchaseReturns->find()->select(['voucher_no'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['voucher_no' => 'DESC'])->first();
			
			$last_voucher_no_credit_note=$this->DebitNotes->find()->select(['voucher_no'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['voucher_no' => 'DESC'])->first();
			
			if(@$last_voucher_no_credit_note->voucher_no > @$last_voucher_no_sr->voucher_no){
				$last_voucher_no=$last_voucher_no_credit_note->voucher_no;
			}else{
				$last_voucher_no=$last_voucher_no_sr->voucher_no;
			}
			
			if($last_voucher_no){
				$voucher_no1=$last_voucher_no+1;
			}else{
				$voucher_no1=1;
			}
			
			$debitNote->voucher_no=$voucher_no1;
			//pr($creditNote);exit;
           if ($this->DebitNotes->save($debitNote)) {
				if($debitNote->cr_dr=="Dr"){
				$ledger = $this->DebitNotes->Ledgers->newEntity();
				$ledger->ledger_account_id = $debitNote->customer_suppiler_id;
				$ledger->debit = $debitNote->grand_total;
				$ledger->credit = 0; 
				$ledger->voucher_id = $debitNote->id;
				$ledger->voucher_source = 'Debit Notes';
				$ledger->company_id = $st_company_id; 
				$ledger->transaction_date = $debitNote->transaction_date;
				$this->DebitNotes->Ledgers->save($ledger); 
				
				foreach($debitNote->ref_rows as $ref_row){ 
					$ReferenceDetail = $this->DebitNotes->ReferenceDetails->newEntity();
					$ReferenceDetail->ledger_account_id = $debitNote->customer_suppiler_id;
					$ReferenceDetail->company_id=$st_company_id;
					$ReferenceDetail->reference_type=$ref_row['ref_type'];
					$ReferenceDetail->reference_no=$ref_row['ref_no'];
					$ReferenceDetail->debit = $ref_row['ref_amount'];
					$ReferenceDetail->credit = 0;
					$ReferenceDetail->debit_note_id = $debitNote->id;
					$ReferenceDetail->transaction_date = $debitNote->transaction_date;
					$this->DebitNotes->ReferenceDetails->save($ReferenceDetail); 
				}
				//pr($debitNote);exit;
				foreach($debitNote->debit_notes_rows as $debit_notes_row){
					$ledger = $this->DebitNotes->Ledgers->newEntity();
					$ledger->ledger_account_id = $debit_notes_row->received_from_id;
					$ledger->credit = $debit_notes_row->amount;
					$ledger->debit = 0; 
					$ledger->voucher_id = $debitNote->id;
					$ledger->voucher_source = 'Debit Notes';
					$ledger->company_id = $st_company_id; 
					$ledger->transaction_date = $debitNote->transaction_date;
					$this->DebitNotes->Ledgers->save($ledger); 
					
					if($debit_notes_row->cgst_amount > 0){
						$cg_LedgerAccount=$this->DebitNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$debit_notes_row->cgst_percentage])->first();
					
						$ledger = $this->DebitNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $cg_LedgerAccount->id;
						$ledger->credit = $debit_notes_row->cgst_amount;
						$ledger->debit = 0; 
						$ledger->voucher_id = $debitNote->id;
						$ledger->voucher_source = 'Debit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $debitNote->transaction_date; 
						$this->DebitNotes->Ledgers->save($ledger); 
					}
					
					if($debit_notes_row->sgst_amount > 0){
						$sg_LedgerAccount=$this->DebitNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$debit_notes_row->sgst_percentage])->first();
						$ledger = $this->DebitNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $sg_LedgerAccount->id;
						$ledger->credit = $debit_notes_row->sgst_amount;
						$ledger->debit = 0; 
						$ledger->voucher_id = $debitNote->id;
						$ledger->voucher_source = 'Debit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $debitNote->transaction_date; 
						$this->DebitNotes->Ledgers->save($ledger); 
					}
					
					if($debit_notes_row->igst_amount > 0){
						$ig_LedgerAccount=$this->DebitNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$debit_notes_row->igst_percentage])->first();
						$ledger = $this->DebitNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $ig_LedgerAccount->id;
						$ledger->credit = $debit_notes_row->igst_amount;
						$ledger->debit = 0; 
						$ledger->voucher_id = $debitNote->id;
						$ledger->voucher_source = 'Debit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $debitNote->transaction_date; 
						$this->DebitNotes->Ledgers->save($ledger); 
					}
				}
			}else{
				$ledger = $this->DebitNotes->Ledgers->newEntity();
				$ledger->ledger_account_id = $debitNote->customer_suppiler_id;
				$ledger->credit = $debitNote->grand_total;
				$ledger->debit = 0; 
				$ledger->voucher_id = $debitNote->id;
				$ledger->voucher_source = 'Debit Notes';
				$ledger->company_id = $st_company_id; 
				$ledger->transaction_date = $debitNote->transaction_date;
				$this->DebitNotes->Ledgers->save($ledger); 
				
				foreach($debitNote->ref_rows as $ref_row){ 
					$ReferenceDetail = $this->DebitNotes->ReferenceDetails->newEntity();
					$ReferenceDetail->ledger_account_id = $debitNote->customer_suppiler_id;
					$ReferenceDetail->company_id=$st_company_id;
					$ReferenceDetail->reference_type=$ref_row['ref_type'];
					$ReferenceDetail->reference_no=$ref_row['ref_no'];
					$ReferenceDetail->credit = $ref_row['ref_amount'];
					$ReferenceDetail->debit = 0;
					$ReferenceDetail->debit_note_id = $debitNote->id;
					$ReferenceDetail->transaction_date = $debitNote->transaction_date;
					$this->DebitNotes->ReferenceDetails->save($ReferenceDetail); 
				}

				foreach($debitNote->debit_notes_rows as $debit_notes_row){
					$ledger = $this->DebitNotes->Ledgers->newEntity();
					$ledger->ledger_account_id = $debit_notes_row->received_from_id;
					$ledger->debit = $debit_notes_row->amount;
					$ledger->credit = 0; 
					$ledger->voucher_id = $debitNote->id;
					$ledger->voucher_source = 'Debit Notes';
					$ledger->company_id = $st_company_id; 
					$ledger->transaction_date = $debitNote->transaction_date;
					$this->DebitNotes->Ledgers->save($ledger); 
					
					if($debit_notes_row->cgst_amount > 0){
						$cg_LedgerAccount=$this->DebitNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$debit_notes_row->cgst_percentage])->first();
					
						$ledger = $this->DebitNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $cg_LedgerAccount->id;
						$ledger->debit = $debit_notes_row->cgst_amount;
						$ledger->credit = 0; 
						$ledger->voucher_id = $debitNote->id;
						$ledger->voucher_source = 'Debit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $debitNote->transaction_date; 
						$this->DebitNotes->Ledgers->save($ledger); 
					}
					
					if($debit_notes_row->sgst_amount > 0){
						$sg_LedgerAccount=$this->DebitNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$debit_notes_row->sgst_percentage])->first();
						$ledger = $this->DebitNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $sg_LedgerAccount->id;
						$ledger->debit = $debit_notes_row->sgst_amount;
						$ledger->credit = 0; 
						$ledger->voucher_id = $debitNote->id;
						$ledger->voucher_source = 'Debit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $debitNote->transaction_date; 
						$this->DebitNotes->Ledgers->save($ledger); 
					}
					
					if($debit_notes_row->igst_amount > 0){
						$ig_LedgerAccount=$this->DebitNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$debit_notes_row->igst_percentage])->first();
						$ledger = $this->DebitNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $ig_LedgerAccount->id;
						$ledger->debit = $debit_notes_row->igst_amount;
						$ledger->credit = 0; 
						$ledger->voucher_id = $debitNote->id;
						$ledger->voucher_source = 'Debit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $debitNote->transaction_date; 
						$this->DebitNotes->Ledgers->save($ledger); 
					}
					
				}
				}
				
				 $this->Flash->success(__('The Debit Note has been saved.'));
                return $this->redirect(['action' => 'Index']);
			}
			
			//pr($creditNote); exit;
		}

		
		 $vr=$this->DebitNotes->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Debit Notes','sub_entity'=>'Customer-Suppiler'])->first();
		
		$ReceiptVouchersCashBank=$vr->id;
		$vouchersReferences = $this->DebitNotes->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		
		/* $BankCashes_selected='yes';
		if(sizeof($where)>0){ 
			$bankCashes = $this->CreditNotes->BankCashes->find('list',
				['keyField' => function ($row) {
					return $row['id'];
				},
				'valueField' => function ($row) {
					if(!empty($row['alias'])){
						return  $row['name'] . ' (' . $row['alias'] . ')';
					}else{
						return $row['name'];
					}
					
				}])->where(['BankCashes.id IN' => $where]);
		}else{
			$BankCashes_selected='no';
		} */
		
		$bankCashes=[]; $merge='';
		$bankCashesDatas = $this->DebitNotes->BankCashes->find();
		foreach($bankCashesDatas as $bankCashesData){
			$merge=$bankCashesData->name;
			if($bankCashesData->alias){ 
			$merge=$bankCashesData->name.'('.$bankCashesData->alias.')'; 
			}

			if($bankCashesData->source_model=="Customers"){ 
				$Customers = $this->DebitNotes->ReceivedFroms->Customers->get($bankCashesData->source_id,[
						'contain'=>['Districts']]);
				$bankCashes[$bankCashesData->id]=['value'=>$bankCashesData->id,'text'=>$merge,'bill_to_bill_account'=>$bankCashesData->bill_to_bill_account,'state_id'=>$Customers->district->state_id];
			}else if($bankCashesData->source_model=="Vendors"){	
			$Vendors = $this->DebitNotes->Vendors->get($bankCashesData->source_id,[
						'contain'=>['Districts']]);
						
			$bankCashes[$bankCashesData->id]=['value'=>$bankCashesData->id,'text'=>$merge,'bill_to_bill_account'=>$bankCashesData->bill_to_bill_account,'state_id'=>@$Vendors->district->state_id];
			} 
			
			//$bankCashes[$bankCashesData->id]=['value'=>$bankCashesData->id,'text'=>$merge,'bill_to_bill_account'=>$bankCashesData->bill_to_bill_account];
			
		} //pr($bankCashes);exit;
			//pr($bankCashes); exit;
			
		$vr=$this->DebitNotes->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Debit Notes','sub_entity'=>'Heads'])->first();  
		$ReceiptVouchersReceivedFrom=$vr->id; 
		$vouchersReferences = $this->DebitNotes->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]); 
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		$ReceivedFroms_selected='yes';
		if(sizeof($where)>0){
			$receivedFroms = $this->DebitNotes->ReceivedFroms->find('list',
				['keyField' => function ($row) {
					return $row['id'];
				},
				'valueField' => function ($row) {
					if(!empty($row['alias'])){
						return  $row['name'] . ' (' . $row['alias'] . ')';
					}else{
						return $row['name'];
					}
					
				}])->where(['ReceivedFroms.id IN' => $where]);
		}else{
			$ReceivedFroms_selected='no';
		} 
		
		/* $receivedFroms=[];
		$bankCashesDatas = $this->CreditNotes->ReceivedFroms->find();
		foreach($vouchersReferences as $bankCashesData){ 
			if($bankCashesData->source_model=="Customers"){ 
				$Customers = $this->CreditNotes->ReceivedFroms->Customers->get($bankCashesData->source_id,[
						'contain'=>['Districts']]);
				$receivedFroms[$bankCashesData->id]=['value'=>$bankCashesData->id,'text'=>$bankCashesData->name,'bill_to_bill_account'=>$bankCashesData->bill_to_bill_account,'state_id'=>$Customers->district->state_id];
			}else if($bankCashesData->source_model=="Vendors"){	
			$receivedFroms[$bankCashesData->id]=['value'=>$bankCashesData->id,'text'=>$bankCashesData->name,'bill_to_bill_account'=>$bankCashesData->bill_to_bill_account,'state_id'=>"8"];
			
			}  
		} ; */
		//pr($vouchersReferences); exit;
		
		$gst_type=[6,5];
		
		$GstTaxes = $this->DebitNotes->SaleTaxes->find()->where(['SaleTaxes.account_second_subgroup_id IN'=>$gst_type])->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				);
				//pr($GstTaxes->toArray()); exit;
		$cgst_options=array();
					$sgst_options=array();
					$igst_options=array();
					foreach($GstTaxes as $GstTaxe){
						if($GstTaxe->cgst=="Yes"){
							$merge_cgst=$GstTaxe->tax_figure.' ('.$GstTaxe->invoice_description.')';
							$cgst_options[]=['text' =>$merge_cgst, 'value' => $GstTaxe->id,'percentage'=>$GstTaxe->tax_figure];
						}else if($GstTaxe->sgst=="Yes"){
							$merge_sgst=$GstTaxe->tax_figure.' ('.$GstTaxe->invoice_description.')';
							$sgst_options[]=['text' =>$merge_sgst, 'value' => $GstTaxe->id,'percentage'=>$GstTaxe->tax_figure];
						}else if($GstTaxe->igst=="Yes"){
							$merge_igst=$GstTaxe->tax_figure.' ('.$GstTaxe->invoice_description.')';
							$igst_options[]=['text' =>$merge_igst, 'value' => $GstTaxe->id,'percentage'=>$GstTaxe->tax_figure];
						}
						
					}
			

			$companies = $this->DebitNotes->Companies->find('all');
			$this->set(compact('debitNote', 'customer_suppiler_id', 'receivedFroms', 'companies','ErrorsalesAccs','Errorparties','financial_year','CreditNotesParty','CreditNotesSalesAccount','chkdate','financial_month_first','financial_month_last','bankCashes','cgst_options','sgst_options','igst_options','voucher_no'));
			$this->set('_serialize', ['debitNote']);
    }

	public function fetchRefNumbers($ledger_account_id){
		$this->viewBuilder()->layout('');
		$ReferenceBalances=$this->DebitNotes->ReferenceBalances->find()->where(['ledger_account_id'=>$ledger_account_id]);
		$this->set(compact('ReferenceBalances','cr_dr'));
	}		

	function checkRefNumberUnique($received_from_id,$i){
		$reference_no=$this->request->query['ref_rows'][$i]['ref_no'];
		$ReferenceBalances=$this->DebitNotes->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id,'reference_no'=>$reference_no]);
		if($ReferenceBalances->count()==0){
			echo 'true';
		}else{
			echo 'false';
		}
		exit;
	}	

    /**
     * Edit method
     *
     * @param string|null $id Debit Note id.
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
		$id = $this->EncryptingDecrypting->decryptData($id);
		$debitNote = $this->DebitNotes->get($id, [
            'contain' => ['Creator','Companies','DebitNotesRows'=>['ReceivedFroms'],'Heads']
        ]);
		
		$ReferenceDetail = $this->DebitNotes->ReferenceDetails->find()->where(['debit_note_id'=>$id]);
		//pr($ReferenceDetail->toArray()); exit;
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->DebitNotes->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->DebitNotes->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->DebitNotes->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		
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
				
		

	//pr($creditNote->credit_notes_rows); exit;
	
        if ($this->request->is(['patch', 'post', 'put'])) { 
					$debitNote = $this->DebitNotes->patchEntity($debitNote, $this->request->data);
					$debitNote->created_on=date("Y-m-d");
					$debitNote->transaction_date=date("Y-m-d",strtotime($this->request->data['transaction_date']));
					$debitNote->company_id=$st_company_id;
					$debitNote->created_by=$s_employee_id;
					
			
           if ($this->DebitNotes->save($debitNote)) { 
			   $this->DebitNotes->Ledgers->deleteAll(['voucher_id' => $debitNote->id, 'voucher_source' => 'Debit Notes']);
			   $this->DebitNotes->ReferenceDetails->deleteAll(['debit_note_id' => $debitNote->id]);
			  
			   if($debitNote->cr_dr=="Dr"){
				$ledger = $this->DebitNotes->Ledgers->newEntity();
				$ledger->ledger_account_id = $debitNote->customer_suppiler_id;
				$ledger->debit = $debitNote->grand_total;
				$ledger->credit = 0; 
				$ledger->voucher_id = $debitNote->id;
				$ledger->voucher_source = 'Debit Notes';
				$ledger->company_id = $st_company_id; 
				$ledger->transaction_date = $debitNote->transaction_date;
				$this->DebitNotes->Ledgers->save($ledger); 
				if(!empty($debitNote->ref_rows)){
						foreach($debitNote->ref_rows as $ref_row){ 
						$ReferenceDetail = $this->DebitNotes->ReferenceDetails->newEntity();
						$ReferenceDetail->ledger_account_id = $debitNote->customer_suppiler_id;
						$ReferenceDetail->company_id=$st_company_id;
						$ReferenceDetail->reference_type=$ref_row['ref_type'];
						$ReferenceDetail->reference_no=$ref_row['ref_no'];
						$ReferenceDetail->debit = $ref_row['ref_amount'];
						$ReferenceDetail->credit = 0;
						$ReferenceDetail->debit_note_id = $debitNote->id;
						$ReferenceDetail->transaction_date = $debitNote->transaction_date;
						$this->DebitNotes->ReferenceDetails->save($ReferenceDetail); 
					}
				}
				
				
				foreach($debitNote->debit_notes_rows as $debit_notes_row){
					$ledger = $this->DebitNotes->Ledgers->newEntity();
					$ledger->ledger_account_id = $debit_notes_row->received_from_id;
					$ledger->credit = $debit_notes_row->amount;
					$ledger->debit = 0; 
					$ledger->voucher_id = $debitNote->id;
					$ledger->voucher_source = 'Debit Notes';
					$ledger->company_id = $st_company_id; 
					$ledger->transaction_date = $debitNote->transaction_date;
					$this->DebitNotes->Ledgers->save($ledger); 
					
					if($debit_notes_row->cgst_amount > 0){
						$cg_LedgerAccount=$this->DebitNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$debit_notes_row->cgst_percentage])->first();
					
						$ledger = $this->DebitNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $cg_LedgerAccount->id;
						$ledger->credit = $debit_notes_row->cgst_amount;
						$ledger->debit = 0; 
						$ledger->voucher_id = $debitNote->id;
						$ledger->voucher_source = 'Debit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $debitNote->transaction_date; 
						$this->DebitNotes->Ledgers->save($ledger); 
					}
					
					if($debit_notes_row->sgst_amount > 0){
						$sg_LedgerAccount=$this->DebitNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$debit_notes_row->sgst_percentage])->first();
						$ledger = $this->DebitNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $sg_LedgerAccount->id;
						$ledger->credit = $debit_notes_row->sgst_amount;
						$ledger->debit = 0; 
						$ledger->voucher_id = $debitNote->id;
						$ledger->voucher_source = 'Debit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $debitNote->transaction_date; 
						$this->DebitNotes->Ledgers->save($ledger); 
					}
					
					if($debit_notes_row->igst_amount > 0){
						$ig_LedgerAccount=$this->DebitNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$debit_notes_row->igst_percentage])->first();
						$ledger = $this->DebitNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $ig_LedgerAccount->id;
						$ledger->credit = $debit_notes_row->igst_amount;
						$ledger->debit = 0; 
						$ledger->voucher_id = $debitNote->id;
						$ledger->voucher_source = 'Debit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $debitNote->transaction_date; 
						$this->DebitNotes->Ledgers->save($ledger); 
					}
				}
			}else{
				$ledger = $this->DebitNotes->Ledgers->newEntity();
				$ledger->ledger_account_id = $debitNote->customer_suppiler_id;
				$ledger->credit = $debitNote->grand_total;
				$ledger->debit = 0; 
				$ledger->voucher_id = $debitNote->id;
				$ledger->voucher_source = 'Debit Notes';
				$ledger->company_id = $st_company_id; 
				$ledger->transaction_date = $debitNote->transaction_date;
				$this->DebitNotes->Ledgers->save($ledger); 
				
				foreach($debitNote->ref_rows as $ref_row){ 
					$ReferenceDetail = $this->DebitNotes->ReferenceDetails->newEntity();
					$ReferenceDetail->ledger_account_id = $debitNote->customer_suppiler_id;
					$ReferenceDetail->company_id=$st_company_id;
					$ReferenceDetail->reference_type=$ref_row['ref_type'];
					$ReferenceDetail->reference_no=$ref_row['ref_no'];
					$ReferenceDetail->credit = $ref_row['ref_amount'];
					$ReferenceDetail->debit = 0;
					$ReferenceDetail->debit_note_id = $debitNote->id;
					$ReferenceDetail->transaction_date = $debitNote->transaction_date;
					$this->DebitNotes->ReferenceDetails->save($ReferenceDetail); 
				}

				foreach($debitNote->debit_notes_rows as $debit_notes_row){
					$ledger = $this->DebitNotes->Ledgers->newEntity();
					$ledger->ledger_account_id = $debit_notes_row->received_from_id;
					$ledger->debit = $debit_notes_row->amount;
					$ledger->credit = 0; 
					$ledger->voucher_id = $debitNote->id;
					$ledger->voucher_source = 'Debit Notes';
					$ledger->company_id = $st_company_id; 
					$ledger->transaction_date = $debitNote->transaction_date;
					$this->DebitNotes->Ledgers->save($ledger); 
					
					if($debit_notes_row->cgst_amount > 0){
						$cg_LedgerAccount=$this->DebitNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$debit_notes_row->cgst_percentage])->first();
					
						$ledger = $this->DebitNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $cg_LedgerAccount->id;
						$ledger->debit = $debit_notes_row->cgst_amount;
						$ledger->credit = 0; 
						$ledger->voucher_id = $debitNote->id;
						$ledger->voucher_source = 'Debit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $debitNote->transaction_date; 
						$this->DebitNotes->Ledgers->save($ledger); 
					}
					
					if($debit_notes_row->sgst_amount > 0){
						$sg_LedgerAccount=$this->DebitNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$debit_notes_row->sgst_percentage])->first();
						$ledger = $this->DebitNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $sg_LedgerAccount->id;
						$ledger->debit = $debit_notes_row->sgst_amount;
						$ledger->credit = 0; 
						$ledger->voucher_id = $debitNote->id;
						$ledger->voucher_source = 'Debit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $debitNote->transaction_date; 
						$this->DebitNotes->Ledgers->save($ledger); 
					}
					
					if($debit_notes_row->igst_amount > 0){
						$ig_LedgerAccount=$this->DebitNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$debit_notes_row->igst_percentage])->first();
						$ledger = $this->DebitNotes->Ledgers->newEntity(); //pr($credit_notes_row->igst_percentage); exit;
						$ledger->ledger_account_id = $ig_LedgerAccount->id;
						$ledger->debit = $debit_notes_row->igst_amount;
						$ledger->credit = 0; 
						$ledger->voucher_id = $debitNote->id;
						$ledger->voucher_source = 'Debit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $debitNote->transaction_date; 
						$this->DebitNotes->Ledgers->save($ledger); 
					}
					
				}
				}
				$this->Flash->success(__('The Debit Note has been saved.'));
                return $this->redirect(['action' => 'Index']);
				}
			}

			 $vr=$this->DebitNotes->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Debit Notes','sub_entity'=>'Customer-Suppiler'])->first();
		
		$ReceiptVouchersCashBank=$vr->id;
		$vouchersReferences = $this->DebitNotes->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		
		$bankCashes=[]; $merge='';
		$bankCashesDatas = $this->DebitNotes->BankCashes->find();
		foreach($bankCashesDatas as $bankCashesData){
			$merge=$bankCashesData->name;
			if($bankCashesData->alias){ 
			$merge=$bankCashesData->name.'('.$bankCashesData->alias.')'; 
			}

			if($bankCashesData->source_model=="Customers"){ 
				$Customers = $this->DebitNotes->ReceivedFroms->Customers->get($bankCashesData->source_id,[
						'contain'=>['Districts']]);
				$bankCashes[$bankCashesData->id]=['value'=>$bankCashesData->id,'text'=>$merge,'bill_to_bill_account'=>$bankCashesData->bill_to_bill_account,'state_id'=>$Customers->district->state_id];
			}else if($bankCashesData->source_model=="Vendors"){	
			$bankCashes[$bankCashesData->id]=['value'=>$bankCashesData->id,'text'=>$merge,'bill_to_bill_account'=>$bankCashesData->bill_to_bill_account,'state_id'=>"8"];
			}
			
			//$bankCashes[$bankCashesData->id]=['value'=>$bankCashesData->id,'text'=>$merge,'bill_to_bill_account'=>$bankCashesData->bill_to_bill_account];
			
		}
		
			
			
		$vr=$this->DebitNotes->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Debit Notes','sub_entity'=>'Heads'])->first();
		$ReceiptVouchersReceivedFrom=$vr->id;
		$vouchersReferences = $this->DebitNotes->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		$ReceivedFroms_selected='yes';
		if(sizeof($where)>0){
			$receivedFroms = $this->DebitNotes->ReceivedFroms->find('list',
				['keyField' => function ($row) {
					return $row['id'];
				},
				'valueField' => function ($row) {
					if(!empty($row['alias'])){
						return  $row['name'] . ' (' . $row['alias'] . ')';
					}else{
						return $row['name'];
					}
					
				}])->where(['ReceivedFroms.id IN' => $where]);
		}else{
			$ReceivedFroms_selected='no';
		}
		
		//pr($receivedFroms);exit;
		$gst_type=[6,5];
		
		$GstTaxes = $this->DebitNotes->SaleTaxes->find()->where(['SaleTaxes.account_second_subgroup_id IN'=>$gst_type])->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				);
							//pr($GstTaxes->toArray()); exit;
					$cgst_options=array();
					$sgst_options=array();
					$igst_options=array();
					foreach($GstTaxes as $GstTaxe){
						if($GstTaxe->cgst=="Yes"){
							$merge_cgst=$GstTaxe->tax_figure.' ('.$GstTaxe->invoice_description.')';
							$cgst_options[]=['text' =>$merge_cgst, 'value' => $GstTaxe->id,'percentage'=>$GstTaxe->tax_figure];
						}else if($GstTaxe->sgst=="Yes"){
							$merge_sgst=$GstTaxe->tax_figure.' ('.$GstTaxe->invoice_description.')';
							$sgst_options[]=['text' =>$merge_sgst, 'value' => $GstTaxe->id,'percentage'=>$GstTaxe->tax_figure];
						}else if($GstTaxe->igst=="Yes"){
							$merge_igst=$GstTaxe->tax_figure.' ('.$GstTaxe->invoice_description.')';
							$igst_options[]=['text' =>$merge_igst, 'value' => $GstTaxe->id,'percentage'=>$GstTaxe->tax_figure];
						}
						
					}


        $customerSuppilers = $this->DebitNotes->CustomerSuppilers->find('list', ['limit' => 200]);
        $companies = $this->DebitNotes->Companies->find('list', ['limit' => 200]);
        $this->set(compact('debitNote', 'customerSuppilers', 'companies','heads','customer_suppiler_id','ReferenceDetails','financial_year','chkdate','financial_month_first','financial_month_last','voucher_no','receivedFroms','bankCashes','cgst_options','sgst_options','igst_options','ReferenceDetail'));
        $this->set('_serialize', ['debitNote']);	
    }

	public function fetchRefNumbersEdit($received_from_id=null,$reference_no=null,$debit=null){
		$this->viewBuilder()->layout('');
		$ReferenceBalances=$this->DebitNotes->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id]);
	    $this->set(compact('ReferenceBalances', 'reference_no', 'debit'));
	}	

	function deleteOneRefNumbers(){
		$old_received_from_id=$this->request->query['old_received_from_id'];
		$debitNote_id=$this->request->query['debitNote_id'];
		$old_ref=$this->request->query['old_ref'];
		$old_ref_type=$this->request->query['old_ref_type'];
		
		if($old_ref_type=="New Reference" || $old_ref_type=="Advance Reference"){
			$this->DebitNotes->ReferenceBalances->deleteAll(['ledger_account_id'=>$old_received_from_id,'reference_no'=>$old_ref]);
			$this->DebitNotes->ReferenceDetails->deleteAll(['ledger_account_id'=>$old_received_from_id,'reference_no'=>$old_ref]);
		}elseif($old_ref_type=="Against Reference"){
			$ReferenceDetail=$this->DebitNotes->ReferenceDetails->find()->where(['ledger_account_id'=>$old_received_from_id,'debit_note_id'=>$debitNote_id,'reference_no'=>$old_ref])->first();
			if(!empty($ReferenceDetail->credit)){
				$ReferenceBalance=$this->DebitNotes->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
				$ReferenceBalance=$this->DebitNotes->ReferenceBalances->get($ReferenceBalance->id);
				$ReferenceBalance->credit=$ReferenceBalance->credit-$ReferenceDetail->credit;
				$this->DebitNotes->ReferenceBalances->save($ReferenceBalance);
			}elseif(!empty($ReferenceDetail->debit)){
				$ReferenceBalance=$this->DebitNotes->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
				$ReferenceBalance=$this->DebitNotes->ReferenceBalances->get($ReferenceBalance->id);
				$ReferenceBalance->debit=$ReferenceBalance->debit-$ReferenceDetail->debit;
				$this->DebitNotes->ReferenceBalances->save($ReferenceBalance);
			}
			$RDetail=$this->DebitNotes->ReferenceDetails->get($ReferenceDetail->id);
			$this->DebitNotes->ReferenceDetails->delete($RDetail);
		}
		
		exit;
	}	
	
	
    /**
     * Delete method
     *
     * @param string|null $id Debit Note id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $debitNote = $this->DebitNotes->get($id);
        if ($this->DebitNotes->delete($debitNote)) {
            $this->Flash->success(__('The debit note has been deleted.'));
        } else {
            $this->Flash->error(__('The debit note could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
