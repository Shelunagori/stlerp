<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CreditNotes Controller
 *
 * @property \App\Model\Table\CreditNotesTable $CreditNotes
 */
class CreditNotesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
		$this->viewBuilder()->layout('index_layout');
        $status='';
        $session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
        $this->paginate = [
            'contain' => []
        ];
        
		$creditNotes = $this->paginate($this->CreditNotes->find()->where(['company_id'=>$st_company_id,'cancle_status'=>'No'])->order(['voucher_no'=>'DESC']));
        
		//pr($creditNotes->toArray());exit;
		
		$this->set(compact('creditNotes','status'));
        $this->set('_serialize', ['creditNotes']);		
    }

	public function closed()
    { 
		$this->viewBuilder()->layout('index_layout');
        $status='';
        $session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
        $this->paginate = [
            'contain' => []
        ];
        
		$creditNotes = $this->paginate($this->CreditNotes->find()->where(['company_id'=>$st_company_id,'cancle_status'=>'Yes'])->order(['voucher_no'=>'DESC']));
        
		//pr($creditNotes->toArray());exit;
		
		$this->set(compact('creditNotes','status'));
        $this->set('_serialize', ['creditNotes']);		
    }
    /**
     * View method
     *
     * @param string|null $id Credit Note id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$id = $this->EncryptingDecrypting->decryptData($id);
        $creditNotes = $this->CreditNotes->get($id, [
            'contain' => ['Creator','FinancialYears','Companies','CreditNotesRows'=>['ReceivedFroms'],'Heads']
        ]);
		if($creditNotes->head->source_model=="Customers"){
			$partyData = $this->CreditNotes->Ledgers->Customers->get($creditNotes->head->source_id, [
            'contain' => ['CustomerAddress']]);

		}else if($creditNotes->head->source_model=="Vendors"){
			$partyData = $this->CreditNotes->Vendors->get($creditNotes->head->source_id);
			//pr($partyData); exit;
		}
//spr($creditNotes->financial_year->date_from); exit;
		$cgst_per=[];
		$sgst_per=[];
		$igst_per=[];
		foreach($creditNotes->credit_notes_rows as $credit_notes_row){
			if($credit_notes_row->cgst_percentage > 0){
				$cgst_per[$credit_notes_row->id]=$this->CreditNotes->SaleTaxes->get(@$credit_notes_row->cgst_percentage);
			}
			if($credit_notes_row->sgst_percentage > 0){
				$sgst_per[$credit_notes_row->id]=$this->CreditNotes->SaleTaxes->get(@$credit_notes_row->sgst_percentage);
			}
			if($credit_notes_row->igst_percentage > 0){
				$igst_per[$credit_notes_row->id]=$this->CreditNotes->SaleTaxes->get(@$credit_notes_row->igst_percentage);
			}
		}
		
		
		//$ReferenceDetails=$this->CreditNotes->ReferenceDetails->find()->where(['ReferenceDetails.credit_note_id' => $id])->toArray();
		
		$this->set('creditNotes', $creditNotes);
		 $this->set(compact('ReferenceDetails','cgst_per','sgst_per','igst_per','partyData'));
        $this->set('_serialize', ['creditNotes']);
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
		$financial_year = $this->CreditNotes->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->CreditNotes->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->CreditNotes->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		
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
				
			$last_voucher_no_sr=$this->CreditNotes->SaleReturns->find()->select(['sr2'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['sr2' => 'DESC'])->first();
			$last_voucher_no_credit_note=$this->CreditNotes->find()->select(['voucher_no'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['voucher_no' => 'DESC'])->first();
			
			if(@$last_voucher_no_credit_note->voucher_no > @$last_voucher_no_sr->sr2){
				$last_voucher_no=$last_voucher_no_credit_note->voucher_no;
			}else{
				$last_voucher_no=$last_voucher_no_sr->sr2;
			}
			
			if($last_voucher_no){
				$voucher_no=$last_voucher_no+1;
			}else{
				$voucher_no=1;
			}
		//pr($voucher_no); exit;
        $creditNote = $this->CreditNotes->newEntity();
		
		if ($this->request->is('post')) {
			$creditNote = $this->CreditNotes->patchEntity($creditNote, $this->request->data);
			$creditNote->created_on=date("Y-m-d");
			$creditNote->transaction_date=date("Y-m-d");
			$creditNote->company_id=$st_company_id;
			$creditNote->created_by=$s_employee_id;
			$creditNote->financial_year_id=$st_year_id;
			
			$last_voucher_no_sr=$this->CreditNotes->SaleReturns->find()->select(['sr2'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['sr2' => 'DESC'])->first();
			
			$last_voucher_no_credit_note=$this->CreditNotes->find()->select(['voucher_no'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['voucher_no' => 'DESC'])->first();
			
			if(@$last_voucher_no_credit_note->voucher_no > @$last_voucher_no_sr->sr2){
				$last_voucher_no=$last_voucher_no_credit_note->voucher_no;
			}else{
				$last_voucher_no=$last_voucher_no_sr->sr2;
			}
			
			if($last_voucher_no){
				$voucher_no1=$last_voucher_no+1;
			}else{
				$voucher_no1=1;
			}
			
			$creditNote->voucher_no=$voucher_no1;
			//pr($creditNote);exit;
           if ($this->CreditNotes->save($creditNote)) {
				if($creditNote->cr_dr=="Dr"){
				$ledger = $this->CreditNotes->Ledgers->newEntity();
				$ledger->ledger_account_id = $creditNote->customer_suppiler_id;
				$ledger->debit = $creditNote->grand_total;
				$ledger->credit = 0; 
				$ledger->voucher_id = $creditNote->id;
				$ledger->voucher_source = 'Credit Notes';
				$ledger->company_id = $st_company_id; 
				$ledger->transaction_date = $creditNote->transaction_date;
				$this->CreditNotes->Ledgers->save($ledger); 
				
				foreach($creditNote->ref_rows as $ref_row){ 
					$ReferenceDetail = $this->CreditNotes->ReferenceDetails->newEntity();
					$ReferenceDetail->ledger_account_id = $creditNote->customer_suppiler_id;
					$ReferenceDetail->company_id=$st_company_id;
					$ReferenceDetail->reference_type=$ref_row['ref_type'];
					$ReferenceDetail->reference_no=$ref_row['ref_no'];
					$ReferenceDetail->debit = $ref_row['ref_amount'];
					$ReferenceDetail->credit = 0;
					$ReferenceDetail->credit_note_id = $creditNote->id;
					$ReferenceDetail->transaction_date = $creditNote->transaction_date;
					$this->CreditNotes->ReferenceDetails->save($ReferenceDetail); 
				}
				
				foreach($creditNote->credit_notes_rows as $credit_notes_row){
					$ledger = $this->CreditNotes->Ledgers->newEntity();
					$ledger->ledger_account_id = $credit_notes_row->received_from_id;
					$ledger->credit = $credit_notes_row->amount;
					$ledger->debit = 0; 
					$ledger->voucher_id = $creditNote->id;
					$ledger->voucher_source = 'Credit Notes';
					$ledger->company_id = $st_company_id; 
					$ledger->transaction_date = $creditNote->transaction_date;
					$this->CreditNotes->Ledgers->save($ledger); 
					
					if($credit_notes_row->cgst_amount > 0){
						$cg_LedgerAccount=$this->CreditNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$credit_notes_row->cgst_percentage])->first();
					
						$ledger = $this->CreditNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $cg_LedgerAccount->id;
						$ledger->credit = $credit_notes_row->cgst_amount;
						$ledger->debit = 0; 
						$ledger->voucher_id = $creditNote->id;
						$ledger->voucher_source = 'Credit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $creditNote->transaction_date; 
						$this->CreditNotes->Ledgers->save($ledger); 
					}
					
					if($credit_notes_row->sgst_amount > 0){
						$sg_LedgerAccount=$this->CreditNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$credit_notes_row->sgst_percentage])->first();
						$ledger = $this->CreditNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $sg_LedgerAccount->id;
						$ledger->credit = $credit_notes_row->sgst_amount;
						$ledger->debit = 0; 
						$ledger->voucher_id = $creditNote->id;
						$ledger->voucher_source = 'Credit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $creditNote->transaction_date; 
						$this->CreditNotes->Ledgers->save($ledger); 
					}
					
					if($credit_notes_row->igst_amount > 0){
						$ig_LedgerAccount=$this->CreditNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$credit_notes_row->igst_percentage])->first();
						$ledger = $this->CreditNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $ig_LedgerAccount->id;
						$ledger->credit = $credit_notes_row->igst_amount;
						$ledger->debit = 0; 
						$ledger->voucher_id = $creditNote->id;
						$ledger->voucher_source = 'Credit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $creditNote->transaction_date; 
						$this->CreditNotes->Ledgers->save($ledger); 
					}
				}
			}else{
				$ledger = $this->CreditNotes->Ledgers->newEntity();
				$ledger->ledger_account_id = $creditNote->customer_suppiler_id;
				$ledger->credit = $creditNote->grand_total;
				$ledger->debit = 0; 
				$ledger->voucher_id = $creditNote->id;
				$ledger->voucher_source = 'Credit Notes';
				$ledger->company_id = $st_company_id; 
				$ledger->transaction_date = $creditNote->transaction_date;
				$this->CreditNotes->Ledgers->save($ledger); 
				
				foreach($creditNote->ref_rows as $ref_row){ 
					$ReferenceDetail = $this->CreditNotes->ReferenceDetails->newEntity();
					$ReferenceDetail->ledger_account_id = $creditNote->customer_suppiler_id;
					$ReferenceDetail->company_id=$st_company_id;
					$ReferenceDetail->reference_type=$ref_row['ref_type'];
					$ReferenceDetail->reference_no=$ref_row['ref_no'];
					$ReferenceDetail->credit = $ref_row['ref_amount'];
					$ReferenceDetail->debit = 0;
					$ReferenceDetail->credit_note_id = $creditNote->id;
					$ReferenceDetail->transaction_date = $creditNote->transaction_date;
					$this->CreditNotes->ReferenceDetails->save($ReferenceDetail); 
				}

				foreach($creditNote->credit_notes_rows as $credit_notes_row){
					$ledger = $this->CreditNotes->Ledgers->newEntity();
					$ledger->ledger_account_id = $credit_notes_row->received_from_id;
					$ledger->debit = $credit_notes_row->amount;
					$ledger->credit = 0; 
					$ledger->voucher_id = $creditNote->id;
					$ledger->voucher_source = 'Credit Notes';
					$ledger->company_id = $st_company_id; 
					$ledger->transaction_date = $creditNote->transaction_date;
					$this->CreditNotes->Ledgers->save($ledger); 
					
					if($credit_notes_row->cgst_amount > 0){
						$cg_LedgerAccount=$this->CreditNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$credit_notes_row->cgst_percentage])->first();
					
						$ledger = $this->CreditNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $cg_LedgerAccount->id;
						$ledger->debit = $credit_notes_row->cgst_amount;
						$ledger->credit = 0; 
						$ledger->voucher_id = $creditNote->id;
						$ledger->voucher_source = 'Credit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $creditNote->transaction_date; 
						$this->CreditNotes->Ledgers->save($ledger); 
					}
					
					if($credit_notes_row->sgst_amount > 0){
						$sg_LedgerAccount=$this->CreditNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$credit_notes_row->sgst_percentage])->first();
						$ledger = $this->CreditNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $sg_LedgerAccount->id;
						$ledger->debit = $credit_notes_row->sgst_amount;
						$ledger->credit = 0; 
						$ledger->voucher_id = $creditNote->id;
						$ledger->voucher_source = 'Credit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $creditNote->transaction_date; 
						$this->CreditNotes->Ledgers->save($ledger); 
					}
					
					if($credit_notes_row->igst_amount > 0){
						$ig_LedgerAccount=$this->CreditNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$credit_notes_row->igst_percentage])->first();
						$ledger = $this->CreditNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $ig_LedgerAccount->id;
						$ledger->debit = $credit_notes_row->igst_amount;
						$ledger->credit = 0; 
						$ledger->voucher_id = $creditNote->id;
						$ledger->voucher_source = 'Credit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $creditNote->transaction_date; 
						$this->CreditNotes->Ledgers->save($ledger); 
					}
					
				}
				}
				
				 $this->Flash->success(__('The Credit Note has been saved.'));
                return $this->redirect(['action' => 'Index']);
			}
			
			//pr($creditNote); exit;
		}

		
		 $vr=$this->CreditNotes->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Credit Notes','sub_entity'=>'Purchase Account'])->first();
		
		$ReceiptVouchersCashBank=$vr->id;
		$vouchersReferences = $this->CreditNotes->VouchersReferences->get($vr->id, [
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
		$bankCashesDatas = $this->CreditNotes->BankCashes->find();
		foreach($bankCashesDatas as $bankCashesData){
			$merge=$bankCashesData->name;
			if($bankCashesData->alias){ 
			$merge=$bankCashesData->name.'('.$bankCashesData->alias.')'; 
			}

			if($bankCashesData->source_model=="Customers"){ 
				$Customers = $this->CreditNotes->ReceivedFroms->Customers->get($bankCashesData->source_id,[
						'contain'=>['Districts']]);
				$bankCashes[$bankCashesData->id]=['value'=>$bankCashesData->id,'text'=>$merge,'bill_to_bill_account'=>$bankCashesData->bill_to_bill_account,'state_id'=>$Customers->district->state_id];
			}else if($bankCashesData->source_model=="Vendors"){	
			$Vendors = $this->CreditNotes->Vendors->get($bankCashesData->source_id,[
						'contain'=>['Districts']]);
						
			$bankCashes[$bankCashesData->id]=['value'=>$bankCashesData->id,'text'=>$merge,'bill_to_bill_account'=>$bankCashesData->bill_to_bill_account,'state_id'=>@$Vendors->district->state_id];
			} 
			
			//$bankCashes[$bankCashesData->id]=['value'=>$bankCashesData->id,'text'=>$merge,'bill_to_bill_account'=>$bankCashesData->bill_to_bill_account];
			
		} //pr($bankCashes);exit;
			//pr($bankCashes); exit;
			
		$vr=$this->CreditNotes->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Credit Notes','sub_entity'=>'Party'])->first();  
		$ReceiptVouchersReceivedFrom=$vr->id; 
		$vouchersReferences = $this->CreditNotes->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]); 
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		$ReceivedFroms_selected='yes';
		if(sizeof($where)>0){
			$receivedFroms = $this->CreditNotes->ReceivedFroms->find('list',
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
		
		$GstTaxes = $this->CreditNotes->SaleTaxes->find()->where(['SaleTaxes.account_second_subgroup_id IN'=>$gst_type])->matching(
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
			

			$companies = $this->CreditNotes->Companies->find('all');
			$this->set(compact('creditNote', 'customer_suppiler_id', 'receivedFroms', 'companies','ErrorsalesAccs','Errorparties','financial_year','CreditNotesParty','CreditNotesSalesAccount','chkdate','financial_month_first','financial_month_last','bankCashes','cgst_options','sgst_options','igst_options','voucher_no'));
			$this->set('_serialize', ['creditNote']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Credit Note id.
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
		$creditNote = $this->CreditNotes->get($id, [
            'contain' => ['Creator','Companies','CreditNotesRows'=>['ReceivedFroms'],'Heads']
        ]);
		
		$ReferenceDetail = $this->CreditNotes->ReferenceDetails->find()->where(['credit_note_id'=>$id]);
		//pr($ReferenceDetail->toArray()); exit;
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->CreditNotes->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->CreditNotes->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->CreditNotes->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		
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
					$creditNote = $this->CreditNotes->patchEntity($creditNote, $this->request->data);
					$creditNote->created_on=date("Y-m-d");
					$creditNote->transaction_date=date("Y-m-d");
					$creditNote->company_id=$st_company_id;
					$creditNote->created_by=$s_employee_id;
					
			//pr($creditNote); exit;
           if ($this->CreditNotes->save($creditNote)) {
			   $this->CreditNotes->Ledgers->deleteAll(['voucher_id' => $creditNote->id, 'voucher_source' => 'Credit Notes']);
			   $this->CreditNotes->ReferenceDetails->deleteAll(['credit_note_id' => $creditNote->id]);
			   if($creditNote->cr_dr=="Dr"){
				$ledger = $this->CreditNotes->Ledgers->newEntity();
				$ledger->ledger_account_id = $creditNote->customer_suppiler_id;
				$ledger->debit = $creditNote->grand_total;
				$ledger->credit = 0; 
				$ledger->voucher_id = $creditNote->id;
				$ledger->voucher_source = 'Credit Notes';
				$ledger->company_id = $st_company_id; 
				$ledger->transaction_date = $creditNote->transaction_date;
				$this->CreditNotes->Ledgers->save($ledger); 
				if(!empty($creditNote->ref_rows)){
						foreach($creditNote->ref_rows as $ref_row){ 
						$ReferenceDetail = $this->CreditNotes->ReferenceDetails->newEntity();
						$ReferenceDetail->ledger_account_id = $creditNote->customer_suppiler_id;
						$ReferenceDetail->company_id=$st_company_id;
						$ReferenceDetail->reference_type=$ref_row['ref_type'];
						$ReferenceDetail->reference_no=$ref_row['ref_no'];
						$ReferenceDetail->debit = $ref_row['ref_amount'];
						$ReferenceDetail->credit = 0;
						$ReferenceDetail->credit_note_id = $creditNote->id;
						$ReferenceDetail->transaction_date = $creditNote->transaction_date;
						$this->CreditNotes->ReferenceDetails->save($ReferenceDetail); 
					}
				}
				
				
				foreach($creditNote->credit_notes_rows as $credit_notes_row){
					$ledger = $this->CreditNotes->Ledgers->newEntity();
					$ledger->ledger_account_id = $credit_notes_row->received_from_id;
					$ledger->credit = $credit_notes_row->amount;
					$ledger->debit = 0; 
					$ledger->voucher_id = $creditNote->id;
					$ledger->voucher_source = 'Credit Notes';
					$ledger->company_id = $st_company_id; 
					$ledger->transaction_date = $creditNote->transaction_date;
					$this->CreditNotes->Ledgers->save($ledger); 
					
					if($credit_notes_row->cgst_amount > 0){
						$cg_LedgerAccount=$this->CreditNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$credit_notes_row->cgst_percentage])->first();
					
						$ledger = $this->CreditNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $cg_LedgerAccount->id;
						$ledger->credit = $credit_notes_row->cgst_amount;
						$ledger->debit = 0; 
						$ledger->voucher_id = $creditNote->id;
						$ledger->voucher_source = 'Credit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $creditNote->transaction_date; 
						$this->CreditNotes->Ledgers->save($ledger); 
					}
					
					if($credit_notes_row->sgst_amount > 0){
						$sg_LedgerAccount=$this->CreditNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$credit_notes_row->sgst_percentage])->first();
						$ledger = $this->CreditNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $sg_LedgerAccount->id;
						$ledger->credit = $credit_notes_row->sgst_amount;
						$ledger->debit = 0; 
						$ledger->voucher_id = $creditNote->id;
						$ledger->voucher_source = 'Credit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $creditNote->transaction_date; 
						$this->CreditNotes->Ledgers->save($ledger); 
					}
					
					if($credit_notes_row->igst_amount > 0){
						$ig_LedgerAccount=$this->CreditNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$credit_notes_row->igst_percentage])->first();
						$ledger = $this->CreditNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $ig_LedgerAccount->id;
						$ledger->credit = $credit_notes_row->igst_amount;
						$ledger->debit = 0; 
						$ledger->voucher_id = $creditNote->id;
						$ledger->voucher_source = 'Credit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $creditNote->transaction_date; 
						$this->CreditNotes->Ledgers->save($ledger); 
					}
				}
			}else{
				$ledger = $this->CreditNotes->Ledgers->newEntity();
				$ledger->ledger_account_id = $creditNote->customer_suppiler_id;
				$ledger->credit = $creditNote->grand_total;
				$ledger->debit = 0; 
				$ledger->voucher_id = $creditNote->id;
				$ledger->voucher_source = 'Credit Notes';
				$ledger->company_id = $st_company_id; 
				$ledger->transaction_date = $creditNote->transaction_date;
				$this->CreditNotes->Ledgers->save($ledger); 
				
				foreach($creditNote->ref_rows as $ref_row){ 
					$ReferenceDetail = $this->CreditNotes->ReferenceDetails->newEntity();
					$ReferenceDetail->ledger_account_id = $creditNote->customer_suppiler_id;
					$ReferenceDetail->company_id=$st_company_id;
					$ReferenceDetail->reference_type=$ref_row['ref_type'];
					$ReferenceDetail->reference_no=$ref_row['ref_no'];
					$ReferenceDetail->credit = $ref_row['ref_amount'];
					$ReferenceDetail->debit = 0;
					$ReferenceDetail->credit_note_id = $creditNote->id;
					$ReferenceDetail->transaction_date = $creditNote->transaction_date;
					$this->CreditNotes->ReferenceDetails->save($ReferenceDetail); 
				}

				foreach($creditNote->credit_notes_rows as $credit_notes_row){
					$ledger = $this->CreditNotes->Ledgers->newEntity();
					$ledger->ledger_account_id = $credit_notes_row->received_from_id;
					$ledger->debit = $credit_notes_row->amount;
					$ledger->credit = 0; 
					$ledger->voucher_id = $creditNote->id;
					$ledger->voucher_source = 'Credit Notes';
					$ledger->company_id = $st_company_id; 
					$ledger->transaction_date = $creditNote->transaction_date;
					$this->CreditNotes->Ledgers->save($ledger); 
					
					if($credit_notes_row->cgst_amount > 0){
						$cg_LedgerAccount=$this->CreditNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$credit_notes_row->cgst_percentage])->first();
					
						$ledger = $this->CreditNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $cg_LedgerAccount->id;
						$ledger->debit = $credit_notes_row->cgst_amount;
						$ledger->credit = 0; 
						$ledger->voucher_id = $creditNote->id;
						$ledger->voucher_source = 'Credit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $creditNote->transaction_date; 
						$this->CreditNotes->Ledgers->save($ledger); 
					}
					
					if($credit_notes_row->sgst_amount > 0){
						$sg_LedgerAccount=$this->CreditNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$credit_notes_row->sgst_percentage])->first();
						$ledger = $this->CreditNotes->Ledgers->newEntity();
						$ledger->ledger_account_id = $sg_LedgerAccount->id;
						$ledger->debit = $credit_notes_row->sgst_amount;
						$ledger->credit = 0; 
						$ledger->voucher_id = $creditNote->id;
						$ledger->voucher_source = 'Credit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $creditNote->transaction_date; 
						$this->CreditNotes->Ledgers->save($ledger); 
					}
					
					if($credit_notes_row->igst_amount > 0){
						$ig_LedgerAccount=$this->CreditNotes->Ledgers->LedgerAccounts->find()->where(['company_id'=>$st_company_id,'source_model'=>'SaleTaxes','source_id'=>$credit_notes_row->igst_percentage])->first();
						$ledger = $this->CreditNotes->Ledgers->newEntity(); //pr($credit_notes_row->igst_percentage); exit;
						$ledger->ledger_account_id = $ig_LedgerAccount->id;
						$ledger->debit = $credit_notes_row->igst_amount;
						$ledger->credit = 0; 
						$ledger->voucher_id = $creditNote->id;
						$ledger->voucher_source = 'Credit Notes';
						$ledger->company_id = $st_company_id; 
						$ledger->transaction_date = $creditNote->transaction_date; 
						$this->CreditNotes->Ledgers->save($ledger); 
					}
					
				}
				}
				$this->Flash->success(__('The Credit Note has been saved.'));
                return $this->redirect(['action' => 'Index']);
				}
			}

			 $vr=$this->CreditNotes->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Credit Notes','sub_entity'=>'Purchase Account'])->first();
		
		$ReceiptVouchersCashBank=$vr->id;
		$vouchersReferences = $this->CreditNotes->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		
		$bankCashes=[]; $merge='';
		$bankCashesDatas = $this->CreditNotes->BankCashes->find();
		foreach($bankCashesDatas as $bankCashesData){
			$merge=$bankCashesData->name;
			if($bankCashesData->alias){ 
			$merge=$bankCashesData->name.'('.$bankCashesData->alias.')'; 
			}

			if($bankCashesData->source_model=="Customers"){ 
				$Customers = $this->CreditNotes->ReceivedFroms->Customers->get($bankCashesData->source_id,[
						'contain'=>['Districts']]);
				$bankCashes[$bankCashesData->id]=['value'=>$bankCashesData->id,'text'=>$merge,'bill_to_bill_account'=>$bankCashesData->bill_to_bill_account,'state_id'=>$Customers->district->state_id];
			}else if($bankCashesData->source_model=="Vendors"){	
			$bankCashes[$bankCashesData->id]=['value'=>$bankCashesData->id,'text'=>$merge,'bill_to_bill_account'=>$bankCashesData->bill_to_bill_account,'state_id'=>"8"];
			}
			
			//$bankCashes[$bankCashesData->id]=['value'=>$bankCashesData->id,'text'=>$merge,'bill_to_bill_account'=>$bankCashesData->bill_to_bill_account];
			
		}
		
			
			
		$vr=$this->CreditNotes->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Credit Notes','sub_entity'=>'Party'])->first();
		$ReceiptVouchersReceivedFrom=$vr->id;
		$vouchersReferences = $this->CreditNotes->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		$ReceivedFroms_selected='yes';
		if(sizeof($where)>0){
			$receivedFroms = $this->CreditNotes->ReceivedFroms->find('list',
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
		
		$GstTaxes = $this->CreditNotes->SaleTaxes->find()->where(['SaleTaxes.account_second_subgroup_id IN'=>$gst_type])->matching(
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


        $customerSuppilers = $this->CreditNotes->CustomerSuppilers->find('list', ['limit' => 200]);
        $companies = $this->CreditNotes->Companies->find('list', ['limit' => 200]);
        $this->set(compact('creditNote', 'customerSuppilers', 'companies','heads','customer_suppiler_id','ReferenceDetails','financial_year','chkdate','financial_month_first','financial_month_last','voucher_no','receivedFroms','bankCashes','cgst_options','sgst_options','igst_options','ReferenceDetail'));
        $this->set('_serialize', ['creditNote']);			

	}	

	
	public function fetchRefNumbers($ledger_account_id){
		$this->viewBuilder()->layout('');
		$ReferenceBalances=$this->CreditNotes->ReferenceBalances->find()->where(['ledger_account_id'=>$ledger_account_id]);
		$this->set(compact('ReferenceBalances','cr_dr'));
	}		

	function checkRefNumberUnique($received_from_id,$i){
		$reference_no=$this->request->query['ref_rows'][$i]['ref_no'];
		$ReferenceBalances=$this->CreditNotes->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id,'reference_no'=>$reference_no]);
		if($ReferenceBalances->count()==0){
			echo 'true';
		}else{
			echo 'false';
		}
		exit;
	}		
	
	
    /**
     * Delete method
     *
     * @param string|null $id Credit Note id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

	public function fetchRefNumbersEdit($received_from_id=null,$reference_no=null,$credit=null){
		$this->viewBuilder()->layout('');
		$ReferenceBalances=$this->CreditNotes->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id]);
	    $this->set(compact('ReferenceBalances', 'reference_no', 'credit'));
	}	

	function deleteOneRefNumbers(){
		$old_received_from_id=$this->request->query['old_received_from_id'];
		$creditNote_id=$this->request->query['creditNote_id'];
		$old_ref=$this->request->query['old_ref'];
		$old_ref_type=$this->request->query['old_ref_type'];
		
		if($old_ref_type=="New Reference" || $old_ref_type=="Advance Reference"){
			$this->CreditNotes->ReferenceBalances->deleteAll(['ledger_account_id'=>$old_received_from_id,'reference_no'=>$old_ref]);
			$this->CreditNotes->ReferenceDetails->deleteAll(['ledger_account_id'=>$old_received_from_id,'reference_no'=>$old_ref]);
		}elseif($old_ref_type=="Against Reference"){
			$ReferenceDetail=$this->CreditNotes->ReferenceDetails->find()->where(['ledger_account_id'=>$old_received_from_id,'credit_note_id'=>$creditNote_id,'reference_no'=>$old_ref])->first();
			if(!empty($ReferenceDetail->credit)){
				$ReferenceBalance=$this->CreditNotes->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
				$ReferenceBalance=$this->CreditNotes->ReferenceBalances->get($ReferenceBalance->id);
				$ReferenceBalance->credit=$ReferenceBalance->credit-$ReferenceDetail->credit;
				$this->CreditNotes->ReferenceBalances->save($ReferenceBalance);
			}elseif(!empty($ReferenceDetail->debit)){
				$ReferenceBalance=$this->CreditNotes->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
				$ReferenceBalance=$this->CreditNotes->ReferenceBalances->get($ReferenceBalance->id);
				$ReferenceBalance->debit=$ReferenceBalance->debit-$ReferenceDetail->debit;
				$this->CreditNotes->ReferenceBalances->save($ReferenceBalance);
			}
			$RDetail=$this->CreditNotes->ReferenceDetails->get($ReferenceDetail->id);
			$this->CreditNotes->ReferenceDetails->delete($RDetail);
		}
		
		exit;
	}	


	public function cancleCreditNote($id = null)
    {
		$this->CreditNotes->Ledgers->deleteAll(['voucher_id' => $id, 'voucher_source' => 'Credit Notes']);
		$this->CreditNotes->ReferenceDetails->deleteAll(['credit_note_id' => $id]);
		
		$query1 = $this->CreditNotes->query();
			$query1->update()
				->set(['cancle_status' => 'Yes'])
				->where(['id'=>$id])
				->execute();
		
		return $this->redirect(['action' => 'index']);
		//pr($id);exit;
	}
	public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $creditNote = $this->CreditNotes->get($id);
        if ($this->CreditNotes->delete($creditNote)) {
            $this->Flash->success(__('The credit note has been deleted.'));
        } else {
            $this->Flash->error(__('The credit note could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
