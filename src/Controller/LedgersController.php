<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Ledgers Controller
 *
 * @property \App\Model\Table\LedgersTable $Ledgers
 */
class LedgersController extends AppController
{
	public $helpers = [
         'Paginator' => ['templates' => 'paginator-templates']
         ];

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($status=null)
    {
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$this->viewBuilder()->layout('index_layout');
		$where=[];
		$ledger=$this->request->query('ledger');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$this->set(compact('ledger','From','To'));
		if(!empty($ledger)){
			$where['ledger_account_id']=$ledger;
		}
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['transaction_date <=']=$To;
		}
		$where['Ledgers.company_id']=$st_company_id;
        $this->paginate = [
            'contain' => ['LedgerAccounts']
        ];
        $ledgers = $this->paginate($this->Ledgers->find()->where($where)->order(['Ledgers.transaction_date' => 'DESC','Ledgers.voucher_id'=>'DESC','Ledgers.voucher_source'=>'DESC']));
		
					$url_link=[];
			foreach($ledgers as $ledger){
				if($ledger->voucher_source=="Journal Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->JournalVouchers->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Payment Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->Payments->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Petty Cash Payment Voucher"){
					$url_link[$ledger->id]="/petty-cash-vouchers/view/".$ledger->voucher_id;
				}else if($ledger->voucher_source=="Contra Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->ContraVouchers->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Receipt Voucher"){
				$url_link[$ledger->id]=$this->Ledgers->Receipts->get($ledger->voucher_id); 
				}else if($ledger->voucher_source=="Invoice"){
					$url_link[$ledger->id]=$this->Ledgers->Invoices->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Invoice Booking"){
					$url_link[$ledger->id]=$this->Ledgers->InvoiceBookings->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Non Print Payment Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->Nppayments->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Debit Note"){
					$url_link[$ledger->id]=$this->Ledgers->DebitNotes->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Credit Note"){
					$url_link[$ledger->id]=$this->Ledgers->CreditNotes->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Purchase Return"){
					$url_link[$ledger->id]=$this->Ledgers->PurchaseReturns->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Sale Return"){
					$url_link[$ledger->id]=$this->Ledgers->SaleReturns->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Inventory Return"){
					$url_link[$ledger->id]=$this->Ledgers->Rivs->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Inventory Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->InventoryVouchers->get($ledger->voucher_id);
				}
			}
		
		
        $ledgerAccounts = $this->Ledgers->LedgerAccounts->find('list');
		//pr($ledgers);exit;
        $this->set(compact('ledgers','ledgerAccounts','url_link'));
        $this->set('_serialize', ['ledgers']);
		$this->set(compact('url'));
    }

    /**
     * View method
     *
     * @param string|null $id Ledger id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
	 
	 public function exportOb()
    {
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
        $st_year_id = $session->read('st_year_id');
		$ledger_account_id=$this->request->query('ledger_account_id');
		$financial_year = $this->Ledgers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$SessionCheckDate = $this->FinancialYears->get($st_year_id);
		$from = date("Y-m-d",strtotime($SessionCheckDate->date_from));   
		$To = date("Y-m-d"); 
		
		$this->set(compact('ledger','From','To'));
		if(!empty($ledger)){
			$where['ledger_account_id']=$ledger;
		}
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['transaction_date <=']=$To;
		}
		
		if($ledger_account_id)
		{
			$Ledger_Account_data = $this->Ledgers->LedgerAccounts->get($ledger_account_id, [
            'contain' => ['AccountSecondSubgroups'=>['AccountFirstSubgroups'=>['AccountGroups'=>['AccountCategories']]]]
        ]);
			$from = $this->request->query['From'];
			$To = $this->request->query['To'];
			$transaction_from_date= date('Y-m-d', strtotime($from));
			$transaction_to_date= date('Y-m-d', strtotime($To));
			$this->set(compact('from','To','transaction_from_date','transaction_to_date'));
		
			if($from == '01-04-2017'){
				$OB = $this->Ledgers->find()->where(['ledger_account_id'=>$ledger_account_id,'transaction_date  '=>$transaction_from_date]);
				$opening_balance_ar=[];
			foreach($OB as $Ledger)
				{
					if($Ledger->voucher_source== "Opening Balance"){
						@$opening_balance_ar['debit']+=$Ledger->debit;
						@$opening_balance_ar['credit']+=$Ledger->credit;
					}
				}	
			}else{
				$OB = $this->Ledgers->find()->where(['ledger_account_id'=>$ledger_account_id,'transaction_date  <'=>$transaction_from_date]);
				$opening_balance_ar=[];
				foreach($OB as $Ledger)
					{
						
							@$opening_balance_ar['debit']+=$Ledger->debit;
							@$opening_balance_ar['credit']+=$Ledger->credit;
					}	
			}
				//pr($opening_balance_ar); exit;
		$where['Ledgers.company_id']=$st_company_id;
       
		$Ledgers = $this->Ledgers->find()
				->where(['ledger_account_id'=>$ledger_account_id,'company_id'=>$st_company_id])
				->where(function($exp) use($transaction_from_date,$transaction_to_date){
					return $exp->between('transaction_date', $transaction_from_date, $transaction_to_date, 'date');
				})->order(['transaction_date' => 'DESC']);
				
		//$ledgers = $this->Ledgers->find()->contain(['LedgerAccounts'])->where($where)->where(['voucher_source != '=>'Opening Balance'])->order(['transaction_date'=>'DESC']);
		
		$url_link=[];
			foreach($Ledgers as $ledger){
				if($ledger->voucher_source=="Journal Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->JournalVouchers->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Payment Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->Payments->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Petty Cash Payment Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->PettyCashVouchers->get($ledger->voucher_id);
					//pr($url_link[$ledger->id]); exit;
				}else if($ledger->voucher_source=="Contra Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->ContraVouchers->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Receipt Voucher"){
				$url_link[$ledger->id]=$this->Ledgers->Receipts->get($ledger->voucher_id); 
				}else if($ledger->voucher_source=="Invoice"){ 
					$inq=$this->Ledgers->Invoices->get($ledger->voucher_id);
					if($inq->sale_tax_id==0){
						$url_link[$ledger->id]=$this->Ledgers->Invoices->get($ledger->voucher_id, [
							'contain' => ['Customers']
						]);
					}else{
						$url_link[$ledger->id]=$this->Ledgers->Invoices->get($ledger->voucher_id, [
							'contain' => ['Customers','SaleTaxes']
						]);
					}
					
					
				}else if($ledger->voucher_source=="Invoice Booking"){
					$ib=$this->Ledgers->InvoiceBookings->get($ledger->voucher_id);
					if($ib->cst_vat=='vat'){
						$url_link[$ledger->id]=$this->Ledgers->InvoiceBookings->get($ledger->voucher_id, [
							'contain' => ['Vendors','InvoiceBookingRows']
						]);
					}else{
						$url_link[$ledger->id]=$this->Ledgers->InvoiceBookings->get($ledger->voucher_id, [
							'contain' => ['Vendors','InvoiceBookingRows']
						]);
					}
				}else if($ledger->voucher_source=="Non Print Payment Voucher"){ 
						
					$url_link[$ledger->id]=$this->Ledgers->Nppayments->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Debit Note"){
					$url_link[$ledger->id]=$this->Ledgers->DebitNotes->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Credit Note"){
					$url_link[$ledger->id]=$this->Ledgers->CreditNotes->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Purchase Return"){
					$url_link[$ledger->id]=$this->Ledgers->PurchaseReturns->get($ledger->voucher_id);
				}
			}
		}			
		//pr($url_link); exit;
		//$ledger_acc = $this->Ledgers->find()->contain(['LedgerAccounts'])->where($where)->where(['voucher_source != '=>'Opening Balance'])->first();
		
		$ledger=$this->Ledgers->LedgerAccounts->find('list',
				['keyField' => function ($row) {
					return $row['id'];
				},
				'valueField' => function ($row) {
					if(!empty($row['alias'])){
						return  $row['name'] . ' (' . $row['alias'] . ')';
					}else{
						return $row['name'];
					}
					
				}])->where(['company_id'=>$st_company_id]);
		
		//$ledger_acc_name=$ledger_acc->ledger_account->name;
		//$ledger_acc_alias=$ledger_acc->ledger_account->alias;
		
        //$ledgerAccounts = $this->Ledgers->LedgerAccounts->find('list');
        $this->set(compact('Ledgers','ledger','ledger_account_id','Ledger_Account_data','url_link','transaction_from_date','transaction_to_date','financial_year','from','To','opening_balance_ar'));
        $this->set('_serialize', ['Ledgers']);
    }
	 public function exportExcel()
    {
		$this->viewBuilder()->layout('');
		$where=[];
		$ledger=$this->request->query('ledger');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$this->set(compact('ledger','From','To'));
		if(!empty($ledger)){
			$where['ledger_account_id']=$ledger;
		}
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['transaction_date <=']=$To;
		}
		
		$where['Ledgers.company_id']=$st_company_id;
       
		 $this->paginate = [
            'contain' => ['LedgerAccounts']
        ];
		
		$ledgers = $this->Ledgers->find()->contain(['LedgerAccounts'])->where($where)->order(['transaction_date'=>'DESC']);
		
		
		
		//pr($ledgers->toArray());exit;
			$url_link=[];
			foreach($ledgers as $ledger){
				if($ledger->voucher_source=="Journal Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->JournalVouchers->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Payment Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->Payments->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Petty Cash Payment Voucher"){
					$url_link[$ledger->id]="/petty-cash-vouchers/view/".$ledger->voucher_id;
				}else if($ledger->voucher_source=="Contra Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->ContraVouchers->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Receipt Voucher"){
				$url_link[$ledger->id]=$this->Ledgers->Receipts->get($ledger->voucher_id); 
				}else if($ledger->voucher_source=="Invoice"){
					$url_link[$ledger->id]=$this->Ledgers->Invoices->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Invoice Booking"){
					$url_link[$ledger->id]=$this->Ledgers->InvoiceBookings->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Non Print Payment Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->Nppayments->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Debit Note"){
					$url_link[$ledger->id]=$this->Ledgers->DebitNotes->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Credit Note"){
					$url_link[$ledger->id]=$this->Ledgers->CreditNotes->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Purchase Return"){
					$url_link[$ledger->id]=$this->Ledgers->PurchaseReturns->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Sale Return"){
					$url_link[$ledger->id]=$this->Ledgers->SaleReturns->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Inventory Return"){
					$url_link[$ledger->id]=$this->Ledgers->Rivs->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Inventory Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->InventoryVouchers->get($ledger->voucher_id);
				}
			}
		
		
        $ledgerAccounts = $this->Ledgers->LedgerAccounts->find('list');
                 $this->set(compact('ledgers','ledgerAccounts','url_link','From','To'));

        $this->set('_serialize', ['ledgers']);
    }
	
	 
	 
    public function view($id = null)
    {
        $ledger = $this->Ledgers->get($id, [
            'contain' => ['LedgerAccounts']
        ]);

        $this->set('ledger', $ledger);
        $this->set('_serialize', ['ledger']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		
        $ledger = $this->Ledgers->newEntity();
        if ($this->request->is('post')) {
            $ledger = $this->Ledgers->patchEntity($ledger, $this->request->data);
			
            if ($this->Ledgers->save($ledger)) {
                $this->Flash->success(__('The ledger has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ledger could not be saved. Please, try again.'));
            }
        }
        $ledgerAccounts = $this->Ledgers->LedgerAccounts->find('list', ['limit' => 200]);
        $this->set(compact('ledger', 'ledgerAccounts'));
        $this->set('_serialize', ['ledger']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ledger id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $ledger = $this->Ledgers->newEntity();
		
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
        $ledger = $this->Ledgers->get($id, [
            'contain' => ['LedgerAccounts']
        ]);
		
		$ledger_details= $this->Ledgers->ReferenceDetails->find()->where(['ledger_account_id'=>$ledger->ledger_account_id,'opening_balance'=>'Yes']);
		
	if ($this->request->is(['post','put'])) {
			
			//$total_dr=0; $total_cr=0;
			
			$this->Ledgers->deleteAll(['id' =>$id,'company_id'=>$st_company_id, 'voucher_source' => 'Opening Balance']);
			
			$this->Ledgers->ReferenceDetails->deleteAll(['ledger_account_id'=>$ledger->ledger_account_id,'opening_balance'=>'Yes']);
			
			$ledger = $this->Ledgers->newEntity();
			$ledger->company_id=$st_company_id;
			$ledger->id=$id;
			$ledger->ledger_account_id = $this->request->data['ledger_account_id'];
			if($this->request->data['type_cr_dr']=="Cr"){
			$ledger->credit = $this->request->data['amount'];
			$ledger->debit = 0;
			//$total_cr=$total_cr+$this->request->data['amount'];
			}else{
			$ledger->credit = 0;
			$ledger->debit = $this->request->data['amount'];
			//$total_dr=$total_dr+$this->request->data['amount'];
			}
			$ledger->voucher_source = 'Opening Balance';
			$ledger->transaction_date=date("Y-m-d",strtotime($this->request->data['transaction_date']));
			$this->Ledgers->save($ledger);
			
			@$ref_rows=@$this->request->data['ref_rows'];
			if(sizeof(@$ref_rows)>0){
						
				foreach($ref_rows as $ref_row){ 
					$ref_row=(object)$ref_row;
					
					$ReferenceDetail = $this->Ledgers->ReferenceDetails->newEntity();
					//$ReferenceDetail->company_id=$st_company_id;
					$ReferenceDetail->reference_type=$ref_row->ref_type;
					$ReferenceDetail->reference_no=$ref_row->ref_no;
					$ReferenceDetail->opening_balance="Yes";
					$ReferenceDetail->ledger_account_id = $this->request->data['ledger_account_id'];
					if($ref_row->ref_cr_dr=="Dr"){
						$ReferenceDetail->debit = $ref_row->ref_amount;
						$ReferenceDetail->credit = 0;
					}else{
						$ReferenceDetail->credit = $ref_row->ref_amount;
						$ReferenceDetail->debit = 0;
					}
					//$ReferenceDetail->invoice_booking_id = $invoiceBooking->id;
					$ReferenceDetail->transaction_date = date("Y-m-d",strtotime($this->request->data['transaction_date']));
					
					$this->Ledgers->ReferenceDetails->save($ReferenceDetail);
					
				}
				$ReferenceDetail = $this->Ledgers->ReferenceDetails->newEntity();
				//$ReferenceDetail->company_id=$st_company_id;
				$ReferenceDetail->reference_type="On_account";
				$ReferenceDetail->opening_balance="Yes";
				$ReferenceDetail->ledger_account_id = $this->request->data['ledger_account_id'];
				if($this->request->data['on_ac_cr_dr']=="Dr"){
					$ReferenceDetail->debit = $this->request->data['on_account'];
					$ReferenceDetail->credit = 0;
				}else{
					$ReferenceDetail->credit = $this->request->data['on_account'];
					$ReferenceDetail->debit = 0;
				}
				//$ReferenceDetail->invoice_booking_id = $invoiceBooking->id;
				$ReferenceDetail->transaction_date = date("Y-m-d",strtotime($this->request->data['transaction_date']));
				if($this->request->data['on_account'] > 0){
					$this->Ledgers->ReferenceDetails->save($ReferenceDetail);
				}
			}
		   return $this->redirect(['action' => 'Opening_balance']);
        }
		//pr($ledger); exit;
        $ledgerAccounts = $this->Ledgers->LedgerAccounts->find('list', ['limit' => 200]);
        $this->set(compact('ledger', 'ledgerAccounts','allow','ledger_details'));
        $this->set('_serialize', ['ledger']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ledger id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ledger = $this->Ledgers->get($id);
        if ($this->Ledgers->delete($ledger)) {
            $this->Flash->success(__('The ledger has been deleted.'));
        } else {
            $this->Flash->error(__('The ledger could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function openingBalance()
    {
		$this->viewBuilder()->layout('index_layout');
        $ledger = $this->Ledgers->newEntity();
		
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Ledgers->FinancialYears->find()->where(['id'=>$st_year_id])->first();	
				
		if ($this->request->is('post')) {
			
			//$total_row=sizeof($this->request->data['reference_no']);
			$Ledgersexists = $this->Ledgers->exists(['ledger_account_id' => $this->request->data['ledger_account_id'],'company_id'=>$st_company_id,'voucher_source'=>'Opening Balance']);
			if($Ledgersexists){
				$this->Flash->error(__('Opening Balance already exists'));
				return $this->redirect(['action' => 'openingBalance']);
			}
			//$total_dr=0; $total_cr=0;
			$ledger->company_id=$st_company_id;
			$ledger->ledger_account_id = $this->request->data['ledger_account_id'];
			if($this->request->data['type_cr_dr']=="Cr"){
			$ledger->credit = $this->request->data['amount'];
			$ledger->debit = 0;
			//$total_cr=$total_cr+$this->request->data['amount'];
			}else{
			$ledger->credit = 0;
			$ledger->debit = $this->request->data['amount'];
			//$total_dr=$total_dr+$this->request->data['amount'];
			}
			$ledger->voucher_source = 'Opening Balance';
			$ledger->transaction_date=date("Y-m-d",strtotime($this->request->data['transaction_date']));
			$this->Ledgers->save($ledger);
			
			@$ref_rows=@$this->request->data['ref_rows'];
			if(sizeof(@$ref_rows)>0){
						
				foreach($ref_rows as $ref_row){ 
					$ref_row=(object)$ref_row;
					
					$ReferenceDetail = $this->Ledgers->ReferenceDetails->newEntity();
					$ReferenceDetail->company_id=$st_company_id;
					$ReferenceDetail->reference_type=$ref_row->ref_type;
					$ReferenceDetail->reference_no=$ref_row->ref_no;
					$ReferenceDetail->opening_balance="Yes";
					$ReferenceDetail->ledger_account_id = $this->request->data['ledger_account_id'];
					if($ref_row->ref_cr_dr=="Dr"){
						$ReferenceDetail->debit = $ref_row->ref_amount;
						$ReferenceDetail->credit = 0;
					}else{
						$ReferenceDetail->credit = $ref_row->ref_amount;
						$ReferenceDetail->debit = 0;
					}
					//$ReferenceDetail->invoice_booking_id = $invoiceBooking->id;
					$ReferenceDetail->transaction_date = date("Y-m-d",strtotime($this->request->data['transaction_date']));
					
					$this->Ledgers->ReferenceDetails->save($ReferenceDetail);
					
				}
				$ReferenceDetail = $this->Ledgers->ReferenceDetails->newEntity();
				$ReferenceDetail->company_id=$st_company_id;
				$ReferenceDetail->reference_type="On_account";
				$ReferenceDetail->opening_balance="Yes";
				$ReferenceDetail->ledger_account_id = $this->request->data['ledger_account_id'];
				if($this->request->data['on_ac_cr_dr']=="Dr"){
					$ReferenceDetail->debit = $this->request->data['on_account'];
					$ReferenceDetail->credit = 0;
				}else{
					$ReferenceDetail->credit = $this->request->data['on_account'];
					$ReferenceDetail->debit = 0;
				}
				//$ReferenceDetail->invoice_booking_id = $invoiceBooking->id;
				$ReferenceDetail->transaction_date = date("Y-m-d",strtotime($this->request->data['transaction_date']));
				if($this->request->data['on_account'] > 0){
					$this->Ledgers->ReferenceDetails->save($ReferenceDetail);
				}
			}
		   return $this->redirect(['action' => 'Opening_balance']);
        }
		
		
        $ledgerAccounts = $this->Ledgers->LedgerAccounts->find('list',
			['keyField' => function ($row) {
				return $row['id'];
			},
			'valueField' => function ($row) {
				if(!empty($row['alias'])){
					return  $row['name'] . ' (' . $row['alias'] . ')';
				}else{
					return $row['name'];
				}
				
			}])->where(['company_id'=>$st_company_id])->contain(['AccountSecondSubgroups'=>['AccountFirstSubgroups'=>['AccountGroups'=>['AccountCategories'=>function($q){
				return $q->where(['AccountCategories.id IN'=>[1,2]]);
			}]]]]);
        $this->set(compact('ledger', 'ledgerAccounts','financial_year'));
        $this->set('_serialize', ['ledger']);
    }
	
	
	
	public function checkReferenceNo()
    {
		$reference_no=$this->request->query['reference_no'][0];
		$ledger_account_id=$this->request->query['ledger_account_id'];
		
		$ReferenceDetails=$this->Ledgers->ReferenceBalances->find()
		->where(['reference_no' => $reference_no,'ledger_account_id' => $ledger_account_id])
		->count();
		
		if(empty($ReferenceDetails))
		{
			$output="true";
		}
		else
		{
			$output="false";
		}
		
		$this->response->body($output);
		return $this->response;
	}
	public function AccountStatementRefrence (){
		 
		$this->viewBuilder()->layout('index_layout');
		$url=$this->request->here(); //pr($url); exit;
		//$url=parse_url($url,PHP_URL_QUERY);
		//pr($url); exit;
		$status=$this->request->query('status');
		$ledger_account_id=$this->request->query('ledgerid');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
        $st_year_id = $session->read('st_year_id');
		$ledger_account_id=$this->request->query('ledgerid');
		$financial_year = $this->Ledgers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$SessionCheckDate = $this->FinancialYears->get($st_year_id);
		$from = date("Y-m-d",strtotime($SessionCheckDate->date_from));   
		$To = date("Y-m-d"); 
		//$this->set(compact('ledger_account_id'));
		$status=$this->request->query('status');
		//pr($ledger_account_id); exit;
		
		
		//exit;
		$ReferenceBalance_transaction_date=[];
		$ReferenceBalance_due_date=[];
		if($ledger_account_id)
		{
		$Ledger_Account_data = $this->Ledgers->LedgerAccounts->get($ledger_account_id, [
        'contain' => ['AccountSecondSubgroups'=>['AccountFirstSubgroups'=>['AccountGroups'=>['AccountCategories']]]] ]);
		
		if($Ledger_Account_data->source_model=='Customers'){
			$customer_data = $this->Ledgers->LedgerAccounts->Customers->get($Ledger_Account_data->source_id);
			//pr($customer_data); exit;
		}
		if($Ledger_Account_data->source_model=='Vendors'){
			$customer_data = $this->Ledgers->Vendors->get($Ledger_Account_data->source_id);
			//pr($customer_data); exit;
		}
		
		$query = $this->Ledgers->ReferenceDetails->find();
		$query->select(['total_debit' => $query->func()->sum('ReferenceDetails.debit'),'total_credit' => $query->func()->sum('ReferenceDetails.credit')])
		->where(['ReferenceDetails.ledger_account_id'=>$ledger_account_id])
		->group(['ReferenceDetails.reference_no'])
		->autoFields(true);
		$referenceDetails=$query;
		$ReferenceBalances=[];
		$on_dr=0;
		$on_cr=0;
		//pr($referenceDetails->toArray());
		foreach($referenceDetails as $referenceDetail){
			if($referenceDetail->total_debit!=$referenceDetail->total_credit){ //pr($referenceDetail);
				$ReferenceBalances[]=['reference_no' =>$referenceDetail->reference_no, 'transaction_date' => $referenceDetail->transaction_date,'due_date' =>$referenceDetail->transaction_date, 'debit' => $referenceDetail->total_debit,'credit' =>$referenceDetail->total_credit,'reference_type'=>$referenceDetail->reference_type];
			}
			
			if($referenceDetail->reference_type=="On_account"){  
				if($referenceDetail->total_debit > $referenceDetail->total_credit){
					$on_dr+=$referenceDetail->total_debit-$referenceDetail->total_credit;
				}else{
					$on_cr+=$referenceDetail->total_credit-$referenceDetail->total_debit;
				}
				
			}
		}
		$ledger=$this->Ledgers->LedgerAccounts->find('list',
			['keyField' => function ($row) {
				return $row['id'];
			},
			'valueField' => function ($row) {
				if(!empty($row['alias'])){
					return  $row['name'] . ' (' . $row['alias'] . ')';
				}else{
					return $row['name'];
				}
				
			}])->where(['company_id'=>$st_company_id]);
			//pr($ledger); exit;
			
		}else{
			$ledger=$this->Ledgers->LedgerAccounts->find('list',
			['keyField' => function ($row) {
				return $row['id'];
			},
			'valueField' => function ($row) {
				if(!empty($row['alias'])){
					return  $row['name'] . ' (' . $row['alias'] . ')';
				}else{
					return $row['name'];
				}
				
			}])->where(['company_id'=>$st_company_id]);
		}
		
		$this->set(compact('Ledgers','ledger','financial_year','ReferenceBalances','Ledger_Account_data','ref_amt','ledger_amt','url','customer_data','on_dr','on_cr'));
	}
	
	public function excelExportAccountRef(){
		$status=$this->request->query('status');
		$ledger_account_id=$this->request->query('ledgerid');
		
		if($ledger_account_id > 0 && $status=='Pending'){  
		$this->redirect(['controller'=>'Ledgers','action' => 'findDate/'.$ledger_account_id]);
		}else{ 
		$this->viewBuilder()->layout('');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
        $st_year_id = $session->read('st_year_id');
		$ledger_account_id=$this->request->query('ledgerid');
		$financial_year = $this->Ledgers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$SessionCheckDate = $this->FinancialYears->get($st_year_id);
		$from = date("Y-m-d",strtotime($SessionCheckDate->date_from));   
		$To = date("Y-m-d"); 
		$this->set(compact('ledger_account_id'));
		$status=$this->request->query('status');
		//pr($ledger_account_id); exit;
		
		
		//exit;
		$ReferenceBalance_transaction_date=[];
		$ReferenceBalance_due_date=[];
		if($ledger_account_id)
		{
		$Ledger_Account_data = $this->Ledgers->LedgerAccounts->get($ledger_account_id, [
        'contain' => ['AccountSecondSubgroups'=>['AccountFirstSubgroups'=>['AccountGroups'=>['AccountCategories']]]] ]);
		
		if($Ledger_Account_data->source_model=='Customers'){
			$customer_data = $this->Ledgers->LedgerAccounts->Customers->get($Ledger_Account_data->source_id);
			$customer_ledger_data = $this->Ledgers->find()->where(['Ledgers.ledger_account_id'=>$ledger_account_id]);
			//pr($customer_ledger_data->toArray()); exit;
		}
		
		$Ledgers = $this->Ledgers->find()->where(['Ledgers.ledger_account_id'=>$ledger_account_id]);
		
		$ledger_amt = $this->Ledgers->find()->where(['Ledgers.ledger_account_id'=>$ledger_account_id]);
		$ledger_amt->select([
			'Debit' => $ledger_amt->func()->sum('Debit'),
			'Credit' => $ledger_amt->func()->sum('Credit')
		]);
		
		$ledger_amt=@$ledger_amt->first();
		//$ledger_amt=@$ledger_amt->first();
		
		
		$ReferenceBalances = $this->Ledgers->ReferenceBalances->find()->where(['ReferenceBalances.ledger_account_id'=>$ledger_account_id]);
		//pr($ReferenceBalances->toArray()); exit;
		
		$ref_amt = $this->Ledgers->ReferenceDetails->find()->where(['ReferenceDetails.ledger_account_id'=>$ledger_account_id]);
		$ref_amt->select([
			'debit' => $ref_amt->func()->sum('debit'),
			'credit' => $ref_amt->func()->sum('credit')
		]);
		
		$ref_amt=$ref_amt->first(); 
		
		}
		
		$ledger=$this->Ledgers->LedgerAccounts->find('list',
			['keyField' => function ($row) {
				return $row['id'];
			},
			'valueField' => function ($row) {
				if(!empty($row['alias'])){
					return  $row['name'] . ' (' . $row['alias'] . ')';
				}else{
					return $row['name'];
				}
				
			}])->where(['company_id'=>$st_company_id]);
			
			$this->set(compact('Ledgers','ledger','financial_year','ReferenceBalances','Ledger_Account_data','ref_amt','ledger_amt'));
		}
	}
	
	public function AccountStatement (){
		$this->viewBuilder()->layout('index_layout');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
        $st_year_id = $session->read('st_year_id');
		$ledger_account_id=$this->request->query('ledger_account_id');
		$financial_year = $this->Ledgers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$SessionCheckDate = $this->FinancialYears->get($st_year_id);
		$from = date("Y-m-d",strtotime($SessionCheckDate->date_from));   
		$To = date("Y-m-d"); 
		if($ledger_account_id)
		{
			$Ledger_Account_data = $this->Ledgers->LedgerAccounts->get($ledger_account_id, [
            'contain' => ['AccountSecondSubgroups'=>['AccountFirstSubgroups'=>['AccountGroups'=>['AccountCategories']]]]
        ]);
			
			$from = $this->request->query['From'];
			$To = $this->request->query['To'];
			$transaction_from_date= date('Y-m-d', strtotime($from));
			$transaction_to_date= date('Y-m-d', strtotime($To));
			$this->set(compact('from','To','transaction_from_date','transaction_to_date'));
			$company = $this->Companies->get($st_company_id);
		//	pr($Ledger_Account_data);exit;
			if($from == date("d-m-Y",strtotime($company->accounting_book_date))){
				$OB = $this->Ledgers->find()->where(['ledger_account_id'=>$ledger_account_id,'transaction_date  '=>$transaction_from_date]);
				$opening_balance_ar=[];
			foreach($OB as $Ledger)
				{
					if($Ledger->voucher_source== "Opening Balance"){
						@$opening_balance_ar['debit']+=$Ledger->debit;
						@$opening_balance_ar['credit']+=$Ledger->credit;
					}
				}	
			}else{
				$OB = $this->Ledgers->find()->where(['ledger_account_id'=>$ledger_account_id,'transaction_date  <'=>$transaction_from_date]);
		
				$opening_balance_ar=[];
				foreach($OB as $Ledger)
					{
						
							@$opening_balance_ar['debit']+=$Ledger->debit;
							@$opening_balance_ar['credit']+=$Ledger->credit;
					}	
			}
			
			$Ledgers = $this->Ledgers->find()
				->where(['ledger_account_id'=>$ledger_account_id,'company_id'=>$st_company_id])
				->where(function($exp) use($transaction_from_date,$transaction_to_date){
					return $exp->between('transaction_date', $transaction_from_date, $transaction_to_date, 'date');
				})->order(['transaction_date' => 'DESC']);
				//pr($opening_balance_ar); exit;
		
			$url_link=[];
			foreach($Ledgers as $ledger){
				if($ledger->voucher_source=="Journal Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->JournalVouchers->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Payment Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->Payments->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Petty Cash Payment Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->PettyCashVouchers->get($ledger->voucher_id);
					//pr($url_link[$ledger->id]); exit;
				}else if($ledger->voucher_source=="Contra Voucher"){
					$url_link[$ledger->id]=$this->Ledgers->ContraVouchers->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Receipt Voucher"){
				$url_link[$ledger->id]=$this->Ledgers->Receipts->get($ledger->voucher_id); 
				}else if($ledger->voucher_source=="Invoice"){  //pr($ledger->voucher_source); exit;
					$inq=$this->Ledgers->Invoices->get($ledger->voucher_id);
					if($inq->sale_tax_id==0){
						$url_link[$ledger->id]=$this->Ledgers->Invoices->get($ledger->voucher_id, [
							'contain' => ['Customers']
						]);
					}else{
						$url_link[$ledger->id]=$this->Ledgers->Invoices->get($ledger->voucher_id, [
							'contain' => ['Customers','SaleTaxes']
						]);
					}
				}else if($ledger->voucher_source=="Invoice Booking"){
					$ib=$this->Ledgers->InvoiceBookings->get($ledger->voucher_id);
					if($ib->cst_vat=='vat'){
						$url_link[$ledger->id]=$this->Ledgers->InvoiceBookings->get($ledger->voucher_id, [
							'contain' => ['Vendors','InvoiceBookingRows']
						]);
					}else{
						$url_link[$ledger->id]=$this->Ledgers->InvoiceBookings->get($ledger->voucher_id, [
							'contain' => ['Vendors','InvoiceBookingRows']
						]);
					}
				}else if($ledger->voucher_source=="Non Print Payment Voucher"){ 
						
					$url_link[$ledger->id]=$this->Ledgers->Nppayments->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Debit Note"){
					$url_link[$ledger->id]=$this->Ledgers->DebitNotes->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Credit Note"){
					$url_link[$ledger->id]=$this->Ledgers->CreditNotes->get($ledger->voucher_id);
				}else if($ledger->voucher_source=="Purchase Return"){
					$url_link[$ledger->id]=$this->Ledgers->PurchaseReturns->get($ledger->voucher_id);
				}
			}
		}			
			//pr($url_link); exit;
			$ledger=$this->Ledgers->LedgerAccounts->find('list',
				['keyField' => function ($row) {
					return $row['id'];
				},
				'valueField' => function ($row) {
					if(!empty($row['alias'])){
						return  $row['name'] . ' (' . $row['alias'] . ')';
					}else{
						return $row['name'];
					}
					
				}])->where(['company_id'=>$st_company_id]);
		//pr($opening_balance_ar); exit;
			$this->set(compact('Ledgers','ledger','ledger_account_id','Ledger_Account_data','url_link','transaction_from_date','transaction_to_date','financial_year','from','To','opening_balance_ar'));	
			$this->set(compact('url'));			
		
	}
	

	
	public function openingBalanceView (){
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$ledger_name=$this->request->query('ledger_name');
		
		$OpeningBalanceViews = $this->paginate($this->Ledgers->find()
		->contain(['LedgerAccounts'=>function($q) use($ledger_name){
			return $q->where(['LedgerAccounts.name LIKE'=>'%'.$ledger_name.'%']);
		}])
		->where(['Ledgers.company_id'=>$st_company_id,'Ledgers.voucher_source'=>'Opening Balance']));
		//pr($OpeningBalanceViews->toArray()); exit;
		$this->set(compact('OpeningBalanceViews', 'ledger_name'));
	}
	
	public function bankReconciliationAdd () {
		
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
        $st_year_id = $session->read('st_year_id');
		$ledger_account_id=$this->request->query('ledger_account_id');
		$financial_year = $this->Ledgers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$SessionCheckDate = $this->FinancialYears->get($st_year_id);
		$from=$this->request->query['From'];
		$To=$this->request->query['To'];
		$bankReconciliationAdd = $this->Ledgers->newEntity();
		if(@$ledger_account_id)
		{
			$transaction_from_date= date('Y-m-d', strtotime($this->request->query['From']));
			$transaction_to_date= date('Y-m-d', strtotime($this->request->query['To']));
			$Bank_Ledgers = $this->Ledgers->find()
				->where(['ledger_account_id'=>$ledger_account_id,'company_id'=>$st_company_id,'voucher_source NOT IN'=>'Opening Balance'
				])
				->where(function($exp) use($transaction_from_date,$transaction_to_date){
					$between = clone $exp;
					return $exp->between('transaction_date', $transaction_from_date, $transaction_to_date, 'date')
					->not($between->between('reconciliation_date', $transaction_from_date, $transaction_to_date, 'date'));
				})->order('transaction_date','ASC');
		}
		
		
		if($ledger_account_id)
		{
			$Ledger_Account_data = $this->Ledgers->LedgerAccounts->get($ledger_account_id, [
            'contain' => ['AccountSecondSubgroups'=>['AccountFirstSubgroups'=>['AccountGroups'=>['AccountCategories']]]]
        ]);
			
			$from = $this->request->query['From'];
			
			$To = $this->request->query['To'];
			
			$transaction_from_date= date('Y-m-d', strtotime($this->request->query['From']));
			$transaction_to_date= date('Y-m-d', strtotime($this->request->query['To']));
			
			$this->set(compact('from','To','transaction_from_date','transaction_to_date'));
			
			
			$OB = $this->Ledgers->find()->where(['ledger_account_id'=>$ledger_account_id,'transaction_date  <='=>$transaction_to_date]);
					
				$opening_balance_ar=[];
			foreach($OB as $Ledger)
				{
					
						@$opening_balance_ar['debit']+=$Ledger->debit;
						@$opening_balance_ar['credit']+=$Ledger->credit;
					
				}	
				//pr($opening_balance_ar);exit;
				//pr($opening_balance_ar);exit;
			//pr($from); exit;
			/* if($from == '01-04-2017'){
				$OB = $this->Ledgers->find()->where(['ledger_account_id'=>$ledger_account_id,'transaction_date  '=>$transaction_from_date]);
				$opening_balance_ar=[];
			foreach($OB as $Ledger)
				{
					if($Ledger->voucher_source== "Opening Balance"){
						@$opening_balance_ar['debit']+=$Ledger->debit;
						@$opening_balance_ar['credit']+=$Ledger->credit;
					}
				}	
			}else{
				$OB = $this->Ledgers->find()->where(['ledger_account_id'=>$ledger_account_id,'transaction_date  <'=>$transaction_from_date]);
		
				$opening_balance_ar=[];
				foreach($OB as $Ledger)
					{
						
							@$opening_balance_ar['debit']+=$Ledger->debit;
							@$opening_balance_ar['credit']+=$Ledger->credit;
					}	
			} */
			
			$Ledgers = $this->Ledgers->find()
				->where(['ledger_account_id'=>$ledger_account_id,'company_id'=>$st_company_id])
				->where(function($exp) use($transaction_from_date,$transaction_to_date){
					$between = clone $exp;
					return $exp->between('transaction_date', $transaction_from_date, $transaction_to_date, 'date')
					->not($between->between('reconciliation_date', $transaction_from_date, $transaction_to_date, 'date'));
				})->order('transaction_date','ASC');
				//pr($Ledgers->toArray()); exit;
		
			
		}	
		
		
		$vr=$this->Ledgers->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Bank Reconciliation Add','sub_entity'=>'Bank'])->first();
		$bankReconciliation=$vr->id;
		$vouchersReferences = $this->Ledgers->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		$BankCashes_selected='yes';
		//pr(sizeof($where)); exit;
		if(sizeof($where)>0){
			$banks = $this->Ledgers->LedgerAccounts->find('list',
				['keyField' => function ($row) {
					return $row['id'];
				},
				'valueField' => function ($row) {
					if(!empty($row['alias'])){
						return  $row['name'] . ' (' . $row['alias'] . ')';
					}else{
						return $row['name'];
					}
					
				}])->where(['LedgerAccounts.id IN' => $where]);
		}else{
			$BankCashes_selected='no';
		}
		if(@$ledger_account_id)
		{
		$bank_ledger_data=$this->Ledgers->LedgerAccounts->get($ledger_account_id);
		}
		$this->set(compact('bankReconciliationAdd','banks','Bank_Ledgers','ledger_account_id','bank_ledger_data','Ledgers','ledger','ledger_account_id','Ledger_Account_data','url_link','transaction_from_date','transaction_to_date','financial_year','from','To','opening_balance_ar'));
	}
	public function dateUpdate($ledger_id=null,$reconciliation_date=null){
		$this->viewBuilder()->layout('');
		//$ledger = $this->Ledgers->get($id);
		if($reconciliation_date=="yes"){
		$reconciliation_date="0000-00-00";
		}else{
		$reconciliation_date=date("Y-m-d",strtotime($reconciliation_date));
		}
	//	pr($reconciliation_date)
		$query = $this->Ledgers->query();
		$query->update()
		->set(['reconciliation_date' => $reconciliation_date])
		->where(['id' => $ledger_id])
		->execute();
		$this->set(compact('reconciliation_date','ledger_id','reconciliation_date'));
	}
	
	public function bankReconciliationView () {
		
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
        $st_year_id = $session->read('st_year_id');
		$ledger_account_id=$this->request->query('ledger_account_id');
		$financial_year = $this->Ledgers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		
		$from =$this->request->query['From'];
		$To =$this->request->query['To'];
		$bankReconciliationAdd = $this->Ledgers->newEntity();
		if($ledger_account_id)
		{
			$transaction_from_date= date('Y-m-d', strtotime($this->request->query['From']));
			$transaction_to_date= date('Y-m-d', strtotime($this->request->query['To']));

			$Bank_Ledgers = $this->Ledgers->find()
				->where(['ledger_account_id'=>$ledger_account_id,'company_id'=>$st_company_id,'reconciliation_date >'=>'0000-00-00','voucher_source NOT IN'=>'Opening Balance'])
				->where(function($exp) use($transaction_from_date,$transaction_to_date){
					return $exp->between('transaction_date', $transaction_from_date, $transaction_to_date, 'date');
				})->order('transaction_date','ASC');
				//pr($Bank_Ledgers->toArray()); exit;
		}

		$vr=$this->Ledgers->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Bank Reconciliation Add','sub_entity'=>'Bank'])->first();
		$bankReconciliation=$vr->id;
		$vouchersReferences = $this->Ledgers->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		$BankCashes_selected='yes';
		
		if(sizeof($where)>0){
			$banks = $this->Ledgers->LedgerAccounts->find('list',
				['keyField' => function ($row) {
					return $row['id'];
				},
				'valueField' => function ($row) {
					if(!empty($row['alias'])){
						return  $row['name'] . ' (' . $row['alias'] . ')';
					}else{
						return $row['name'];
					}
					
				}])->where(['LedgerAccounts.id IN' => $where]);
		}else{
			$BankCashes_selected='no';
		}
		if(@$ledger_account_id)
		{
		$bank_ledger_data=$this->Ledgers->LedgerAccounts->get($ledger_account_id);
		}
		$this->set(compact('bankReconciliationAdd','banks','Bank_Ledgers','ledger_account_id','bank_ledger_data','To','financial_year'));
	}
	public function findDate($ledger_account_id=null){ 
	
		$ReferenceDetails =$this->Ledgers->ReferenceDetails->find()->where(['ledger_account_id'=>$ledger_account_id]);
		
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Ledgers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		foreach($ReferenceDetails as $ReferenceDetail){ 
			 if($ReferenceDetail->invoice_id !=0){  //pr($ReferenceDetails->toArray()); exit;
				$Receipt =$this->Ledgers->Invoices->get($ReferenceDetail->invoice_id);
				$Customer =$this->Ledgers->Customers->get($Receipt->customer_id);
				$date = date("Y-m-d", strtotime($Receipt->date_created));
				$due_date= date("Y-m-d",strtotime("+".$Customer->payment_terms."  day", strtotime($date)));
				$query = $this->Ledgers->ReferenceDetails->query();
				$query->update()
						->set(['due_date'=>$due_date])
						->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])
						->execute();
			}else if($ReferenceDetail->receipt_id !=0){  
				$Receipt =$this->Ledgers->Receipts->get($ReferenceDetail->receipt_id);
				
				$LedgerAccount =$this->Ledgers->LedgerAccounts->get($ReferenceDetail->ledger_account_id);
				if($LedgerAccount->source_model=='Customers'){
				$Customer =$this->Ledgers->Customers->get($LedgerAccount->source_id);
				$date = date("Y-m-d", strtotime($Receipt->created_on));
				$due_date= date("Y-m-d",strtotime("+".$Customer->payment_terms."  day", strtotime($date)));
				$query = $this->Ledgers->ReferenceDetails->query();
				$query->update()
						->set(['due_date'=>$due_date])
						->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])
						->execute();
				}
			}else if($ReferenceDetail->payment_id !=0){ 
				$Receipt =$this->Ledgers->Payments->get($ReferenceDetail->payment_id);
				$LedgerAccount =$this->Ledgers->LedgerAccounts->get($ReferenceDetail->ledger_account_id);
				if($LedgerAccount->source_model=='Customers'){
				$Customer =$this->Ledgers->Customers->get($LedgerAccount->source_id);
				$date = date("Y-m-d", strtotime($Receipt->created_on));
				$due_date= date("Y-m-d",strtotime("+".$Customer->payment_terms."  day", strtotime($date)));
				$query = $this->Ledgers->ReferenceDetails->query();
				$query->update()
						->set(['due_date'=>$due_date])
						->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])
						->execute();
				}
			}
			else if($ReferenceDetail->journal_voucher_id !=0){ 
				$Receipt =$this->Ledgers->JournalVouchers->get($ReferenceDetail->journal_voucher_id);
				$LedgerAccount =$this->Ledgers->LedgerAccounts->get($ReferenceDetail->ledger_account_id);
				if($LedgerAccount->source_model=='Customers'){
					$Customer =$this->Ledgers->Customers->get($LedgerAccount->source_id);
					$date = date("Y-m-d", strtotime($Receipt->created_on));
					$due_date= date("Y-m-d",strtotime("+".$Customer->payment_terms."  day", strtotime($date)));
					$query = $this->Ledgers->ReferenceDetails->query();
					$query->update()
							->set(['due_date'=>$due_date])
							->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])
							->execute();
				}
			}
			else if($ReferenceDetail->sale_return_id !=0){ 
				$Receipt =$this->Ledgers->SaleReturns->get($ReferenceDetail->sale_return_id);
				$LedgerAccount =$this->Ledgers->LedgerAccounts->get($ReferenceDetail->ledger_account_id);
				if($LedgerAccount->source_model=='Customers'){
					$Customer =$this->Ledgers->Customers->get($LedgerAccount->source_id);
					$date = date("Y-m-d", strtotime($Receipt->date_created));
					$due_date= date("Y-m-d",strtotime("+".$Customer->payment_terms."  day", strtotime($date)));
					$query = $this->Ledgers->ReferenceDetails->query();
					$query->update()
							->set(['due_date'=>$due_date])
							->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])
							->execute();
				}
			}
			else if($ReferenceDetail->petty_cash_voucher_id !=0){ 
				$Receipt =$this->Ledgers->PettyCashVouchers->get($ReferenceDetail->petty_cash_voucher_id);
				$LedgerAccount =$this->Ledgers->LedgerAccounts->get($ReferenceDetail->ledger_account_id);
				if($LedgerAccount->source_model=='Customers'){
					$Customer =$this->Ledgers->Customers->get($LedgerAccount->source_id);
					$date = date("Y-m-d", strtotime($Receipt->created_on));
					$due_date= date("Y-m-d",strtotime("+".$Customer->payment_terms."  day", strtotime($date)));
					$query = $this->Ledgers->ReferenceBalances->query();
					$query->update()
							->set(['transaction_date' =>$date,'due_date'=>$due_date])
							->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])
							->execute();
				}
			}else if($ReferenceDetail->nppayment_id !=0){ 
				$Receipt =$this->Ledgers->Nppayments->get($ReferenceDetail->nppayment_id);
				$LedgerAccount =$this->Ledgers->LedgerAccounts->get($ReferenceDetail->ledger_account_id);
				if($LedgerAccount->source_model=='Customers'){
					$Customer =$this->Ledgers->Customers->get($LedgerAccount->source_id);
					$date = date("Y-m-d", strtotime($Receipt->created_on));
					$due_date= date("Y-m-d",strtotime("+".$Customer->payment_terms."  day", strtotime($date)));
					$query = $this->Ledgers->ReferenceBalances->query();
					$query->update()
							->set(['transaction_date' =>$date,'due_date'=>$due_date])
							->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])
							->execute();
				}
			}else if($ReferenceDetail->contra_voucher_id !=0){ 
				$Receipt =$this->Ledgers->ContraVouchers->get($ReferenceDetail->contra_voucher_id);
				$LedgerAccount =$this->Ledgers->LedgerAccounts->get($ReferenceDetail->ledger_account_id);
				if($LedgerAccount->source_model=='Customers'){
					$Customer =$this->Ledgers->Customers->get($LedgerAccount->source_id);
					$date = date("Y-m-d", strtotime($Receipt->created_on));
					$due_date= date("Y-m-d",strtotime("+".$Customer->payment_terms."  day", strtotime($date)));
					$query = $this->Ledgers->ReferenceBalances->query();
					$query->update()
							->set(['transaction_date' =>$date,'due_date'=>$due_date])
							->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])
							->execute();
				}
			}else if($ReferenceDetail->debit_note_id !=0){ 
				$Receipt =$this->Ledgers->DebitNotes->get($ReferenceDetail->debit_note_id);
				$LedgerAccount =$this->Ledgers->LedgerAccounts->get($ReferenceDetail->ledger_account_id);
				if($LedgerAccount->source_model=='Customers'){
					$Customer =$this->Ledgers->Customers->get($LedgerAccount->source_id);
					$date = date("Y-m-d", strtotime($Receipt->created_on));
					$due_date= date("Y-m-d",strtotime("+".$Customer->payment_terms."  day", strtotime($date)));
					$query = $this->Ledgers->ReferenceBalances->query();
					$query->update()
							->set(['transaction_date' =>$date,'due_date'=>$due_date])
							->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])
							->execute();
				}
			}else if($ReferenceDetail->credit_note_id !=0){ 
				$Receipt =$this->Ledgers->CreditNotes->get($ReferenceDetail->credit_note_id);
				$LedgerAccount =$this->Ledgers->LedgerAccounts->get($ReferenceDetail->ledger_account_id);
				if($LedgerAccount->source_model=='Customers'){
					$Customer =$this->Ledgers->Customers->get($LedgerAccount->source_id);
					$date = date("Y-m-d", strtotime($Receipt->created_on));
					$due_date= date("Y-m-d",strtotime("+".$Customer->payment_terms."  day", strtotime($date)));
					$query = $this->Ledgers->ReferenceBalances->query();
					$query->update()
							->set(['transaction_date' =>$date,'due_date'=>$due_date])
							->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])
							->execute();
				}
			}else if($ReferenceDetail->invoice_booking_id !=0){ 
				$Receipt =$this->Ledgers->InvoiceBookings->get($ReferenceDetail->invoice_booking_id);
				$LedgerAccount =$this->Ledgers->LedgerAccounts->get($ReferenceDetail->ledger_account_id);
				if($LedgerAccount->source_model=='Vendors'){
					$Customer =$this->Ledgers->Vendors->get($LedgerAccount->source_id);
					$date = date("Y-m-d", strtotime($Receipt->created_on));
					$due_date= date("Y-m-d",strtotime("+".$Customer->payment_terms."  day", strtotime($date)));
					$query = $this->Ledgers->ReferenceBalances->query();
					$query->update()
							->set(['transaction_date' =>$date,'due_date'=>$due_date])
							->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])
							->execute();
				}
			}else {  
				@$LedgerAccount =$this->Ledgers->LedgerAccounts->get(@$ReferenceDetail->ledger_account_id); 
				if($LedgerAccount->source_model=='Customers' && $LedgerAccount->source_id !=0){
					$Customer =$this->Ledgers->Customers->get(@$LedgerAccount->source_id);
					$date = $financial_year->date_from;
					$due_date= $financial_year->date_from;
					//pr($due_date); exit;
					$query = $this->Ledgers->ReferenceBalances->query();
					$query->update()
							->set(['transaction_date' =>$date,'due_date'=>$due_date])
							->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id,'reference_no'=>$ReferenceDetail->reference_no])
							->execute();
				}
			}
		}
		$status="Completed";
		$this->redirect(['controller'=>'Ledgers','action' => 'AccountStatementRefrence?status='.$status.'&ledgerid='.$ledger_account_id]);
		
   }
	
	public function getAllLedgers(){
		$Ledgers25 = $this->Ledgers->find()->where(['Ledgers.company_id'=>'25','voucher_source'=>'Opening Balance'])->contain(['LedgerAccounts']);
		$Ledgers26 = $this->Ledgers->find()->where(['Ledgers.company_id'=>'26','voucher_source'=>'Opening Balance'])->contain(['LedgerAccounts']);
		$Ledgers27 = $this->Ledgers->find()->where(['Ledgers.company_id'=>'27','voucher_source'=>'Opening Balance'])->contain(['LedgerAccounts']);
		$this->set(compact('Ledgers25','Ledgers26','Ledgers27'));
	}
	
	public function ProfitLossStatement()
    {
		$this->viewBuilder()->layout('index_layout');
        $session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$from_date=$this->request->query('from_date');
		$to_date=$this->request->query('to_date');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		
		$AccountCategories=$this->Ledgers->LedgerAccounts->AccountSecondSubgroups->AccountFirstSubgroups->AccountGroups->AccountCategories->find()
		->where(['AccountCategories.id In'=>[3,4]])
		->contain(['AccountGroups.AccountFirstSubgroups.AccountSecondSubgroups.LedgerAccounts']);
		
		$groupForPrint=[];
		foreach($AccountCategories as $AccountCategory){
			foreach($AccountCategory->account_groups as $account_group){
				foreach($account_group->account_first_subgroups as $account_first_subgroup){
					foreach($account_first_subgroup->account_second_subgroups as $account_second_subgroup){
						foreach($account_second_subgroup->ledger_accounts as $ledger_account){
							$query=$this->Ledgers->find();
							$query->select(['ledger_account_id','totalDebit' => $query->func()->sum('Ledgers.debit'),'totalCredit' => $query->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id])->first();
							@$groupForPrint[$account_group->id]['name']=@$account_group->name;
							@$groupForPrint[$account_group->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
						}
					}
				}
			}
		}
		
		$openingValue= 0;//$this->StockValuationWithDate($from_date);
		$closingValue= 0;//$this->StockValuation();
		$this->set(compact('from_date','to_date', 'groupForPrint', 'closingValue', 'openingValue'));
		
    }
	
	public function BalanceSheet()
    {
		$this->viewBuilder()->layout('index_layout');
        $session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$from_date=$this->request->query('from_date');
		$to_date=$this->request->query('to_date');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		
		$AccountCategories=$this->Ledgers->LedgerAccounts->AccountSecondSubgroups->AccountFirstSubgroups->AccountGroups->AccountCategories->find()
		->where(['AccountCategories.id In'=>[1,2]])
		->contain(['AccountGroups.AccountFirstSubgroups.AccountSecondSubgroups.LedgerAccounts']);
		
		$groupForPrint=[];
		foreach($AccountCategories as $AccountCategory){
			foreach($AccountCategory->account_groups as $account_group){
				foreach($account_group->account_first_subgroups as $account_first_subgroup){
					foreach($account_first_subgroup->account_second_subgroups as $account_second_subgroup){
						foreach($account_second_subgroup->ledger_accounts as $ledger_account){
							$query=$this->Ledgers->find();
							$query->select(['ledger_account_id','totalDebit' => $query->func()->sum('Ledgers.debit'),'totalCredit' => $query->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id])->first();
							@$groupForPrint[$account_group->id]['name']=@$account_group->name;
							@$groupForPrint[$account_group->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
						}
					}
				}
			}
		}
		
		$GrossProfit= 0; //$this->GrossProfit($from_date,$to_date);
		$closingValue= 0; //$this->StockValuation();
		$differenceInOpeningBalance= $this->differenceInOpeningBalance();
		
		$this->set(compact('from_date','to_date', 'groupForPrint', 'GrossProfit', 'closingValue', 'differenceInOpeningBalance'));
	}
}
