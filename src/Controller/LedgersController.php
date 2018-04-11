<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Mailer\Email;

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
	 
	 /* public function DataMigrate()
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$OpeningBalances = $this->Ledgers->OpeningBalances->find()->where(['voucher_source'=>'Opening Balance'])->toArray();
		//pr($OpeningBalances); exit;
		$i=0;
		foreach($OpeningBalances as $OpeningBalance){ $i++;
			$ledger = $this->Ledgers->newEntity();
			$ledger->ledger_account_id = $OpeningBalance->ledger_account_id;
			$ledger->credit = $OpeningBalance->credit;
			$ledger->debit = $OpeningBalance->debit;
			$ledger->voucher_id = 0;
			$ledger->voucher_source = 'Opening Balance';
			$ledger->company_id = $OpeningBalance->company_id;
			$ledger->transaction_date = $OpeningBalance->transaction_date;
			$this->Ledgers->save($ledger); 
		}
		
		echo $i; 
		echo "done"; 
		 exit;
	}
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
		$url=parse_url($url,PHP_URL_QUERY);
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
		$this->set(compact('ledger_account_id'));
		//$status=$this->request->query('status');
		$to = date("Y-m-d",strtotime($SessionCheckDate->date_to));
	
		//exit;
		$ReferenceBalance_transaction_date=[];
		$ReferenceBalance_due_date=[];
		if($ledger_account_id)
		{
			
		$query = $this->Ledgers->ReferenceDetails->find()->where(['ReferenceDetails.transaction_date <='=>$to]);
		//pr($query->toArray()); exit;
		$query
		->where(['ReferenceDetails.ledger_account_id'=>$ledger_account_id])
		->autoFields(true);
		$referenceDetails=$query->order(['transaction_date' => 'ASC']);
		$DueReferenceBalances=[];
		$refInvoiceNo=[];
		$refInvoiceBookingNo=[];
		$ReferenceBalances=[];
		$on_dr=0;
		$on_cr=0;
		$Voucher_data=[];
	
			//pr($referenceDetails->toArray()); exit; int
		$Ledger_Account_data = $this->Ledgers->LedgerAccounts->get($ledger_account_id, [
        'contain' => ['AccountSecondSubgroups'=>['AccountFirstSubgroups'=>['AccountGroups'=>['AccountCategories']]]] ]);
		$customer_data=0;
		if($Ledger_Account_data->source_model=='Customers'){ //pr($referenceDetails->toArray()); exit;
			$customer_data = $this->Ledgers->LedgerAccounts->Customers->get($Ledger_Account_data->source_id);
			foreach($referenceDetails as $referenceDetail){
			if($referenceDetail->debit > 0){ 
				if($referenceDetail->invoice_id > 0){
					$Invoice_data[$referenceDetail->invoice_id] = $this->Ledgers->Invoices->get($referenceDetail->invoice_id);
					$refInvoiceNo[$referenceDetail->reference_no]=$referenceDetail->invoice_id;
					$Invoice_data1 = $this->Ledgers->Invoices->get($referenceDetail->invoice_id);
					$Voucher_data[$referenceDetail->reference_no] = @$Invoice_data1->in1.'/IN-'.str_pad(@$Invoice_data1->in2, 3, '0', STR_PAD_LEFT).'/'.@$Invoice_data1->in3.'/'.@$Invoice_data1->in4;
				}
				if($referenceDetail->receipt_id > 0){  //pr($referenceDetail->reference_no); exit; 
					$Invoice_booking_data = $this->Ledgers->Receipts->get($referenceDetail->receipt_id);
					$Voucher_data[$referenceDetail->reference_no]= h('#'.str_pad($Invoice_booking_data->voucher_no,4,'0',STR_PAD_LEFT)); 
					
				}
				if($referenceDetail->journal_voucher_id > 0){  //pr($referenceDetail->reference_no); exit; 
					$Invoice_booking_data = $this->Ledgers->JournalVouchers->get($referenceDetail->journal_voucher_id);
					$Voucher_data[$referenceDetail->reference_no]= h('#'.str_pad($Invoice_booking_data->voucher_no,4,'0',STR_PAD_LEFT)); 
					
				}
				if($referenceDetail->payment_id > 0){  //pr($referenceDetail->reference_no); exit; 
					$Invoice_booking_data = $this->Ledgers->Payments->get($referenceDetail->payment_id);
					$Voucher_data[$referenceDetail->reference_no]= h('#'.str_pad($Invoice_booking_data->voucher_no,4,'0',STR_PAD_LEFT)); 
					
				}
				if($referenceDetail->opening_balance == "Yes"){  //pr($referenceDetail->reference_no); 
					$d="Opening balance"; 
					$Voucher_data[$referenceDetail->reference_no]= $d;
					 
				}
				
			}
			if($referenceDetail->debit!=$referenceDetail->credit){ 
				$x=(float)@$referenceDetail->debit;
				$y=(float)@$referenceDetail->credit;
				//pr($y); exit;
				@$DueReferenceBalances[@$referenceDetail->reference_no]=@$DueReferenceBalances[@$referenceDetail->reference_no]+($x-$y);
				/* if($referenceDetail->reference_no == 'Op.')
				{
				echo $DueReferenceBalances[@$referenceDetail->reference_no].' += '.abs($referenceDetail->debit).'-'.abs($referenceDetail->credit); echo '<br/>';
				} */
				
				$ReferenceBalances[$referenceDetail->reference_no]=['reference_no' =>$referenceDetail->reference_no, 'transaction_date' => $referenceDetail->transaction_date,'due_date' =>$referenceDetail->transaction_date, 'debit' => $referenceDetail->debit,'credit' =>$referenceDetail->credit,'reference_type'=>$referenceDetail->reference_type,'opening_balance'=>$referenceDetail->opening_balance,'invoice_id'=>$referenceDetail->invoice_id];
				//pr($referenceDetail->debit);
				//pr($referenceDetail->credit);
				//pr($DueReferenceBalances['Op.']);
				//pr($DueReferenceBalances);
			}
			
			if($referenceDetail->reference_type=="On_account"){ 
				if($referenceDetail->debit > $referenceDetail->credit){
					$on_dr+=$referenceDetail->debit;
				}else{
					$on_cr+=$referenceDetail->credit;
				}
				
			}
		}
			
		}  
	//exit;
		if($Ledger_Account_data->source_model=='Vendors'){ //pr($referenceDetails->toArray()); exit;
			$customer_data = $this->Ledgers->Vendors->get($Ledger_Account_data->source_id);
			
			foreach($referenceDetails as $referenceDetail){ 
			if($referenceDetail->credit > 0){
				if($referenceDetail->invoice_booking_id > 0){  //pr($referenceDetail->reference_no); exit; 
					$refInvoiceBookingNo[$referenceDetail->reference_no] = $this->Ledgers->InvoiceBookings->get($referenceDetail->invoice_booking_id, [
									'contain' => ['Grns'=>['PurchaseOrders']]
								]);
					$Invoice_booking_data = $this->Ledgers->InvoiceBookings->get($referenceDetail->invoice_booking_id);
					//pr($Invoice_booking_data); exit;
					$Voucher_data[$referenceDetail->reference_no]=@$Invoice_booking_data->invoice_no;
					
				}
				if($referenceDetail->receipt_id > 0){  //pr($referenceDetail->reference_no); exit; 
					$Invoice_booking_data = $this->Ledgers->Receipts->get($referenceDetail->receipt_id);
					
					$Voucher_data[$referenceDetail->reference_no]= h('#'.str_pad($Invoice_booking_data->voucher_no,4,'0',STR_PAD_LEFT)); 
					
				}
				if($referenceDetail->journal_voucher_id > 0){  //pr($referenceDetail->reference_no); exit; 
					$Invoice_booking_data = $this->Ledgers->JournalVouchers->get($referenceDetail->journal_voucher_id);
					$Voucher_data[$referenceDetail->reference_no]= h('#'.str_pad($Invoice_booking_data->voucher_no,4,'0',STR_PAD_LEFT)); 
					
				}
				if($referenceDetail->payment_id > 0){  //pr($referenceDetail->reference_no); exit; 
					$Invoice_booking_data = $this->Ledgers->Payments->get($referenceDetail->payment_id);
					$Voucher_data[$referenceDetail->reference_no]= h('#'.str_pad($Invoice_booking_data->voucher_no,4,'0',STR_PAD_LEFT)); 
					
				}
				if($referenceDetail->opening_balance == "Yes"){  //pr($referenceDetail->reference_no); 
					//$Invoice_booking_data = $this->Ledgers->Receipts->get($referenceDetail->receipt_id);
					$d="Opening balance"; 
					$Voucher_data[$referenceDetail->reference_no]= $d;
					 
				}
				
			}
			if($referenceDetail->debit!=$referenceDetail->credit){ //pr($referenceDetail);
				@$DueReferenceBalances[@$referenceDetail->reference_no]+=@$referenceDetail->debit-@$referenceDetail->credit;
				$ReferenceBalances[$referenceDetail->reference_no]=['reference_no' =>$referenceDetail->reference_no, 'transaction_date' => $referenceDetail->transaction_date,'due_date' =>$referenceDetail->transaction_date, 'debit' => $referenceDetail->debit,'credit' =>$referenceDetail->credit,'reference_type'=>$referenceDetail->reference_type,'opening_balance'=>$referenceDetail->opening_balance];
			}
			
			if($referenceDetail->reference_type=="On_account"){ 
				if($referenceDetail->debit > $referenceDetail->credit){
					$on_dr+=$referenceDetail->debit;
				}else{
					$on_cr+=$referenceDetail->credit;
				}
				
			}
		}
		}
		//pr($on_cr); exit;

	
	
	//	pr($ReferenceBalances); exit;
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
		
		$this->set(compact('Ledgers','ledger','financial_year','ReferenceBalances','Ledger_Account_data','ref_amt','ledger_amt','url','customer_data','on_dr','on_cr','Invoice_data','DueReferenceBalances','Voucher_data','refInvoiceNo','refInvoiceBookingNo'));
	}
	
	public function excelExportAccountRef(){
		
		$this->viewBuilder()->layout('');
		$ledger_account_id=$this->request->query('ledgerid');
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
			
		$this->set(compact('Ledgers','ledger','financial_year','ReferenceBalances','Ledger_Account_data','ref_amt','ledger_amt','url','customer_data','on_dr','on_cr'));
		}
	
	}
	
	public function excelBs(){ 
		$this->viewBuilder()->layout('');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY); 
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
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first();
							@$groupForPrint[$account_group->id]['name']=@$account_group->name;
							@$groupForPrint[$account_group->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
						}
					}
				}
			}
		}
		//pr(@$groupForPrint); exit;
		$GrossProfit= $this->GrossProfit($from_date,$to_date);
		$closingValue= $this->StockValuationWithDate2($to_date);
		$differenceInOpeningBalance= $this->differenceInOpeningBalance();
		
		$this->set(compact('from_date','to_date', 'groupForPrint', 'GrossProfit', 'closingValue', 'differenceInOpeningBalance','url'));
	}
	
	public function excelPnl (){ 
		$this->viewBuilder()->layout('');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY); 
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
        $st_year_id = $session->read('st_year_id');
		$ledger_account_id=$this->request->query('ledger_account_id');
		$financial_year = $this->Ledgers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$company = $this->Companies->get($st_company_id);
		$from_date = $this->request->query('from_date');
		$to_date   = $this->request->query('to_date');
		
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
	//	pr($from_date); exit;
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
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date >='=>$from_date, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first();
							@$groupForPrint[$account_group->id]['name']=@$account_group->name;
							@$groupForPrint[$account_group->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
						}
					}
				}
			}
		}
		
		$openingValue= $this->StockValuationWithDate($from_date);
		$closingValue= $this->StockValuationWithDate2($to_date);
		
		//$closingValue= $this->StockValuation();
		$this->set(compact('from_date','to_date', 'groupForPrint', 'closingValue', 'openingValue','url','st_year_id'));
	}
	
	public function excelTb (){ 
		$this->viewBuilder()->layout('');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
        $st_year_id = $session->read('st_year_id');
		$ledger_account_id=$this->request->query('ledger_account_id');
		$financial_year = $this->Ledgers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$company = $this->Companies->get($st_company_id);
		$from_date = $this->request->query('From');
		$to_date   = $this->request->query('To');
		
		if($from_date){ 
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date   = date("Y-m-d",strtotime($to_date));
		$AccountCategories=$this->Ledgers->LedgerAccounts->AccountSecondSubgroups->AccountFirstSubgroups->AccountGroups->AccountCategories->find()
		->where(['AccountCategories.id In'=>[1,2,3,4]])
		->contain(['AccountGroups.AccountFirstSubgroups.AccountSecondSubgroups.LedgerAccounts']);
		
		$TransactionDr=[]; $TransactionCr=[]; $OpeningBalanceForPrint=[]; $ClosingBalanceForPrint=[];
		foreach($AccountCategories as $AccountCategory){
			foreach($AccountCategory->account_groups as $account_group){
				foreach($account_group->account_first_subgroups as $account_first_subgroup){
					foreach($account_first_subgroup->account_second_subgroups as $account_second_subgroup){
						foreach($account_second_subgroup->ledger_accounts as $ledger_account){
							$query=$this->Ledgers->find();
							$query->select(['ledger_account_id','totalDebit' => $query->func()->sum('Ledgers.debit'),'totalCredit' => $query->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first(); //pr($query->first());
							@$ClosingBalanceForPrint[$account_group->id]['name']=@$account_group->name;
							@$ClosingBalanceForPrint[$account_group->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
							
							// pr($ClosingBalanceForPrint);exit;
							$query2=$this->Ledgers->find();
							$query2->select(['ledger_account_id','totalDebit' => $query2->func()->sum('Ledgers.debit'),'totalCredit' => $query2->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date >'=>$from_date, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first(); 

							@$TransactionDr[@$account_group->id]['balance']+=@$query2->first()->totalDebit;
							@$TransactionCr[@$account_group->id]['balance']+=@$query2->first()->totalCredit;

							$query1=$this->Ledgers->find();
							$query1->select(['ledger_account_id','totalDebit' => $query1->func()->sum('Ledgers.debit'),'totalCredit' => $query1->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date <='=>$from_date,'Ledgers.company_id'=>$st_company_id])->first();
							$OpeningBalanceForPrint[$account_group->id]['name']=@$account_group->name;
							@$OpeningBalanceForPrint[$account_group->id]['balance']+=@$query1->first()->totalDebit-@$query1->first()->totalCredit;
							/* pr($TransactionDr);
							pr($TransactionCr);
								exit; */
						}
					}
				}
			}
		} 
	}
		//pr($OpeningBalanceForPrint); exit;
		$ItemLedgers = $this->Ledgers->ItemLedgers->find()->where(['ItemLedgers.source_model'=>'Items','ItemLedgers.company_id'=>$st_company_id]);
		$itemOpeningBalance=0;
		foreach($ItemLedgers as $ItemLedger){ 
			$itemOpeningBalance+=$ItemLedger->quantity*$ItemLedger->rate;
		}
		if(empty($from_date) || empty($to_date))
		{ 
			$from_date = date("Y-m-d",strtotime($financial_year->date_from));
			@$to_date   = date("Y-m-d",strtotime($financial_year->date_to));
		} 
		$differenceInOpeningBalance= $this->differenceInOpeningBalance();
		$this->set(compact('url','TrialBalances','financial_year','OpeningBalanceDebit','OpeningBalanceCredit','TransactionsDebit','TransactionsCredit','LedgerAccounts','from_date','to_date','itemOpeningBalance','differenceInOpeningBalance','groupForPrint','ClosingBalanceForPrint','TransactionDr','TransactionCr','OpeningBalanceForPrint'));
	}
	
	public function TrailBalance (){
		$this->viewBuilder()->layout('index_layout');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
        $st_year_id = $session->read('st_year_id');
		$ledger_account_id=$this->request->query('ledger_account_id');
		$financial_year = $this->Ledgers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		
		$from_date = $this->request->query('From');
		 $to_date   = $this->request->query('To');
		 
		 $company = $this->Companies->get($st_company_id);
			//pr($company->accounting_book_date);exit;
		  
		/* if($from_date){ 
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date   = date("Y-m-d",strtotime($to_date));
		$LedgerAccounts = $this->Ledgers->LedgerAccounts->find()->where(['LedgerAccounts.company_id'=>$st_company_id]);
		$OpeningBalanceCredit=[];
		$OpeningBalanceDebit=[];
		$TransactionsDebit=[];
		$TransactionsCredit=[];
			foreach($LedgerAccounts as $LedgerAccount){ 
				$company->accounting_book_date = date("Y-m-d",strtotime($company->accounting_book_date));
				//pr(@$from_date);
				//pr(@$company->accounting_book_date); exit;
				if($company->accounting_book_date == $from_date){
					$Ledgers = $this->Ledgers->find()->where(['Ledgers.ledger_account_id'=>$LedgerAccount->id]);
					
						foreach($Ledgers as $Ledger){ 
							$Ledger->transaction_date = date("Y-m-d",strtotime($Ledger->transaction_date));
							
							if(($Ledger->transaction_date == $from_date) && ($Ledger->debit > 0) && ($Ledger->voucher_source == 'Opening Balance')){ //pr($from_date);  pr($Ledger->transaction_date); 
								@$OpeningBalanceDebit[@$LedgerAccount->id]+=@$Ledger->debit;
							}
							if(($Ledger->transaction_date == $from_date) && ($Ledger->credit > 0) && ($Ledger->voucher_source =='Opening Balance')){
								@$OpeningBalanceCredit[@$LedgerAccount->id]+=@$Ledger->credit;
							}
						if(($Ledger->transaction_date > $from_date) || ($Ledger->transaction_date <= $to_date) && ($Ledger->debit > 0) && ($Ledger->voucher_source != 'Opening Balance')){
								@$TransactionsDebit[@$LedgerAccount->id]+=@$Ledger->debit;
							}
							if(($Ledger->transaction_date > $from_date) || ($Ledger->transaction_date <= $to_date) && ($Ledger->credit > 0) && ($Ledger->voucher_source != 'Opening Balance')){
								@$TransactionsCredit[@$LedgerAccount->id]+=@$Ledger->credit;
							}
							
						}
				}else{ 
					$Ledgers = $this->Ledgers->find()->where(['Ledgers.ledger_account_id'=>$LedgerAccount->id]);
						foreach($Ledgers as $Ledger){ 
							$Ledger->transaction_date = date("Y-m-d",strtotime($Ledger->transaction_date));
							
							if($Ledger->transaction_date < $from_date && $Ledger->debit > 0){ //pr($from_date);  pr($Ledger->transaction_date); 
								@$OpeningBalanceDebit[@$LedgerAccount->id]+=@$Ledger->debit;
							}
							if($Ledger->transaction_date < $from_date && $Ledger->credit > 0){ 
								@$OpeningBalanceCredit[@$LedgerAccount->id]+=@$Ledger->credit;
							}
							if(($Ledger->transaction_date >= $from_date && $Ledger->transaction_date <= $to_date) && $Ledger->debit > 0){
								@$TransactionsDebit[@$LedgerAccount->id]+=@$Ledger->debit;
							}
							if(($Ledger->transaction_date >= $from_date && $Ledger->transaction_date <= $to_date) && $Ledger->credit > 0){
								@$TransactionsCredit[@$LedgerAccount->id]+=@$Ledger->credit;
							}
						}
					}
				
			}
		
		} */
		if($from_date){ 
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date   = date("Y-m-d",strtotime($to_date)); 
		$AccountCategories=$this->Ledgers->LedgerAccounts->AccountSecondSubgroups->AccountFirstSubgroups->AccountGroups->AccountCategories->find()
		->where(['AccountCategories.id In'=>[1,2,3,4]])
		->contain(['AccountGroups.AccountFirstSubgroups.AccountSecondSubgroups.LedgerAccounts']);
		
		$TransactionDr=[]; $TransactionCr=[]; $OpeningBalanceForPrint=[]; $ClosingBalanceForPrint=[];
		foreach($AccountCategories as $AccountCategory){
			foreach($AccountCategory->account_groups as $account_group){
				foreach($account_group->account_first_subgroups as $account_first_subgroup){
					foreach($account_first_subgroup->account_second_subgroups as $account_second_subgroup){
						foreach($account_second_subgroup->ledger_accounts as $ledger_account){
							$query=$this->Ledgers->find();
							$query->select(['ledger_account_id','totalDebit' => $query->func()->sum('Ledgers.debit'),'totalCredit' => $query->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first(); //pr($query->first());
							@$ClosingBalanceForPrint[$account_group->id]['name']=@$account_group->name;
							@$ClosingBalanceForPrint[$account_group->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
							

							$query2=$this->Ledgers->find();
							$query2->select(['ledger_account_id','totalDebit' => $query2->func()->sum('Ledgers.debit'),'totalCredit' => $query2->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date >'=>$from_date, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first(); 

							@$TransactionDr[@$account_group->id]['balance']+=@$query2->first()->totalDebit;
							@$TransactionCr[@$account_group->id]['balance']+=@$query2->first()->totalCredit;

							$query1=$this->Ledgers->find();
							$query1->select(['ledger_account_id','totalDebit' => $query1->func()->sum('Ledgers.debit'),'totalCredit' => $query1->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date <='=>$from_date,'Ledgers.company_id'=>$st_company_id])->first();
							$OpeningBalanceForPrint[$account_group->id]['name']=@$account_group->name;
							@$OpeningBalanceForPrint[$account_group->id]['balance']+=@$query1->first()->totalDebit-@$query1->first()->totalCredit;
							/* pr($TransactionDr);
							pr($TransactionCr);
								exit; */
						}
					}
				}
			}
		/* pr($OpeningBalanceForPrint);
		pr($TransactionDr);
		pr($TransactionCr);
		pr($ClosingBalanceForPrint); exit; */
		} 
}
		//pr($groupForPrint); exit;
		$ItemLedgers = $this->Ledgers->ItemLedgers->find()->where(['ItemLedgers.source_model'=>'Items','ItemLedgers.company_id'=>$st_company_id]);
		$itemOpeningBalance=0;
		foreach($ItemLedgers as $ItemLedger){ 
			$itemOpeningBalance+=$ItemLedger->quantity*$ItemLedger->rate;
		}
		//pr($itemOpeningBalance);  exit;
		if(empty($from_date) || empty($to_date))
		{ 
			$from_date = date("Y-m-d",strtotime($financial_year->date_from));
			@$to_date   = date("Y-m-d",strtotime($financial_year->date_to));
		} 
		$differenceInOpeningBalance= $this->differenceInOpeningBalance();
		//pr(@$OpeningBalanceDebit);
		//ssspr(@$OpeningBalanceCredit); exit;
		//					
		//$differenceInOpeningBalance= $this->differenceInOpeningBalance();
		//pr(@$differenceInOpeningBalance); exit;
		$this->set(compact('url','TrialBalances','financial_year','OpeningBalanceDebit','OpeningBalanceCredit','TransactionsDebit','TransactionsCredit','LedgerAccounts','from_date','to_date','itemOpeningBalance','differenceInOpeningBalance','groupForPrint','ClosingBalanceForPrint','TransactionDr','TransactionCr','OpeningBalanceForPrint'));
		
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
				})->order(['transaction_date' => 'ASC']);
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
				}else if($ledger->voucher_source=="Sale Return"){
					$url_link[$ledger->id]=$this->Ledgers->SaleReturns->get($ledger->voucher_id);
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
		
		$OpeningBalanceViews = $this->Ledgers->find()
		->contain(['LedgerAccounts'=>function($q) use($ledger_name){
			return $q->where(['LedgerAccounts.name LIKE'=>'%'.$ledger_name.'%']);
		}])
		->where(['Ledgers.company_id'=>$st_company_id,'Ledgers.voucher_source'=>'Opening Balance']);
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
				->where(['ledger_account_id'=>$ledger_account_id,'company_id'=>$st_company_id,'voucher_source NOT IN'=>'Opening Balance', 'transaction_date <='=>$transaction_to_date
				])
				->order('transaction_date','ASC');	
		}
		
		//pr($Bank_Ledgers);exit;
		
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
				
			////Start New Concept of Bank Reconcilation Report 7Dec2017	
				$balanceAsPerBooks = $this->Ledgers->find()->where(['ledger_account_id'=>$ledger_account_id,'transaction_date  <='=>$transaction_to_date]);
						
				$balance_as_per_book_amt=[];
				foreach($balanceAsPerBooks as $Ledger)
					{
						@$balance_as_per_book_amt['debit']+=$Ledger->debit;
						@$balance_as_per_book_amt['credit']+=$Ledger->credit;
					}
					
				$Ledgers_Banks = $this->Ledgers->find()
				->where(['ledger_account_id'=>$ledger_account_id,'company_id'=>$st_company_id])
				->where(['transaction_date <=' => $transaction_to_date])
				->where(function($exp) use($transaction_from_date,$transaction_to_date){
					$between = clone $exp;
					return $exp
					->not($between->between('reconciliation_date', $transaction_from_date, $transaction_to_date, 'date'));
				})->order('transaction_date','ASC');		
			////End New Concept of Bank Reconcilation Report 7Dec2017	
			
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
		$this->set(compact('bankReconciliationAdd','banks','Bank_Ledgers','ledger_account_id','bank_ledger_data','Ledgers','ledger','ledger_account_id','Ledger_Account_data','url_link','transaction_from_date','transaction_to_date','financial_year','from','To','opening_balance_ar','Ledgers_Banks','balance_as_per_book_amt'));
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
		$this->set(compact('bankReconciliationAdd','banks','Bank_Ledgers','ledger_account_id','bank_ledger_data','To','financial_year','from'));
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
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY); 
        $session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
        $st_year_id = $session->read('st_year_id');
		$from_date=$this->request->query('from_date');
		$to_date=$this->request->query('to_date');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		//pr($s_year_from); exit;
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
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date >='=>$from_date, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first();
							@$groupForPrint[$account_group->id]['name']=@$account_group->name;
							@$groupForPrint[$account_group->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
						}
					}
				}
			}
		}
		
		$openingValue= $this->StockValuationWithDate($from_date);
		$closingValue= $this->StockValuationWithDate2($to_date);
		
		//$closingValue= $this->StockValuation();
		$this->set(compact('from_date','to_date', 'groupForPrint', 'closingValue', 'openingValue','url','st_year_id'));
		
    }
	
	public function BalanceSheet()
    {
		$this->viewBuilder()->layout('index_layout');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
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
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first();
							@$groupForPrint[$account_group->id]['name']=@$account_group->name;
							@$groupForPrint[$account_group->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
						}
					}
				}
			}
		} 
		
		$GrossProfit= $this->GrossProfit($from_date,$to_date);
		$closingValue= $this->StockValuationWithDate2($to_date);
		$differenceInOpeningBalance= $this->differenceInOpeningBalance();
		
		$this->set(compact('from_date','to_date', 'groupForPrint', 'GrossProfit', 'closingValue', 'differenceInOpeningBalance','url'));
	}
	
	public function firstSubGroupsPnl($group_id,$from_date,$to_date)
	{ 
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		//$from_date=$this->request->query('from_date');
		//$to_date=$this->request->query('to_date');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		//pr($from_date); exit;
		$AccountGroups=$this->Ledgers->LedgerAccounts->AccountSecondSubgroups->AccountFirstSubgroups->AccountGroups->find()
		->where(['AccountGroups.id '=>$group_id])
		->contain(['AccountFirstSubgroups.AccountSecondSubgroups.LedgerAccounts']);
		//pr($AccountGroups->toArray()); exit;
		$groupForPrint=[];
		
			foreach($AccountGroups as $account_group){  
				foreach($account_group->account_first_subgroups as $account_first_subgroup){
					foreach($account_first_subgroup->account_second_subgroups as $account_second_subgroup){  
						foreach($account_second_subgroup->ledger_accounts as $ledger_account){ 
							
							$query=$this->Ledgers->find();
							$query->select(['ledger_account_id','totalDebit' => $query->func()->sum('Ledgers.debit'),'totalCredit' => $query->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date >='=>$from_date, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first();
							//pr($query->first()); exit;
							@$groupForPrint[$account_first_subgroup->id]['name']=@$account_first_subgroup->name;
							@$groupForPrint[$account_first_subgroup->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
						}
					}
				}
			}

		$this->set(compact('from_date','to_date', 'groupForPrint', 'closingValue', 'openingValue'));
	}

	public function secondSubGroupsPnl($group_id,$from_date,$to_date)
	{ 
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		//$from_date=$this->request->query('from_date');
		//$to_date=$this->request->query('to_date');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		//pr($from_date); exit;
		$AccountFirstSubgroups=$this->Ledgers->LedgerAccounts->AccountSecondSubgroups->AccountFirstSubgroups->find()
		->where(['AccountFirstSubgroups.id '=>$group_id])
		->contain(['AccountSecondSubgroups.LedgerAccounts'=>function($q) use ($st_company_id){
				return $q->where(['company_id'=>$st_company_id]);
			}]);
		//pr($AccountGroups->toArray()); exit;
		$groupForPrint=[];
		
			  
				foreach($AccountFirstSubgroups as $account_first_subgroup){
					foreach($account_first_subgroup->account_second_subgroups as $account_second_subgroup){  
						foreach($account_second_subgroup->ledger_accounts as $ledger_account){ 
							
							$query=$this->Ledgers->find();
							$query->select(['ledger_account_id','totalDebit' => $query->func()->sum('Ledgers.debit'),'totalCredit' => $query->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date >='=>$from_date, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first();
							//pr($query->first()); exit;
							@$groupForPrint[$account_second_subgroup->id]['name']=@$account_second_subgroup->name;
							@$groupForPrint[$account_second_subgroup->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
						}
					}
				}
	$this->set(compact('from_date','to_date', 'groupForPrint', 'closingValue', 'openingValue'));
	}

	public function ledgerAccountDataPnl($group_id,$from_date,$to_date)
	{ 
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		//$from_date=$this->request->query('from_date');
		//$to_date=$this->request->query('to_date');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		//pr($from_date); exit;
		$AccountSecondSubgroups=$this->Ledgers->LedgerAccounts->AccountSecondSubgroups->find()
		->where(['AccountSecondSubgroups.id '=>$group_id])
		->contain(['LedgerAccounts'=>function($q) use ($st_company_id){
				return $q->where(['company_id'=>$st_company_id]);
			}]);
		//pr($AccountGroups->toArray()); exit;
		$groupForPrint=[];
		
			  foreach($AccountSecondSubgroups as $account_second_subgroup){  
						foreach($account_second_subgroup->ledger_accounts as $ledger_account){ 
							//pr($ledger_account); exit;
							$query=$this->Ledgers->find();
							$query->select(['ledger_account_id','totalDebit' => $query->func()->sum('Ledgers.debit'),'totalCredit' => $query->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date >='=>$from_date, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first();
							//pr($query->first()); exit;
							@$groupForPrint[$ledger_account->id]['name']=@$ledger_account->name;
							@$groupForPrint[$ledger_account->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
						}
					}//pr($groupForPrint); exit;
				
	$this->set(compact('from_date','to_date', 'groupForPrint', 'closingValue', 'openingValue'));
	}

	public function firstSubGroupsTb($group_id,$from_date,$to_date)
	{ 
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		$AccountGroups=$this->Ledgers->LedgerAccounts->AccountSecondSubgroups->AccountFirstSubgroups->AccountGroups->find()
		->where(['AccountGroups.id '=>$group_id])
		->contain(['AccountFirstSubgroups.AccountSecondSubgroups.LedgerAccounts']);
		//pr($AccountGroups->toArray()); exit;
		$TransactionDr=[]; $TransactionCr=[]; $OpeningBalanceForPrint=[]; $ClosingBalanceForPrint=[];
			foreach($AccountGroups as $account_group){  
				foreach($account_group->account_first_subgroups as $account_first_subgroup){
					foreach($account_first_subgroup->account_second_subgroups as $account_second_subgroup){  
						foreach($account_second_subgroup->ledger_accounts as $ledger_account){ 
							
							$query=$this->Ledgers->find();
							$query->select(['ledger_account_id','totalDebit' => $query->func()->sum('Ledgers.debit'),'totalCredit' => $query->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first(); //pr($query->first());
							@$ClosingBalanceForPrint[$account_first_subgroup->id]['name']=@$account_first_subgroup->name;
							@$ClosingBalanceForPrint[$account_first_subgroup->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
							

							$query2=$this->Ledgers->find();
							$query2->select(['ledger_account_id','totalDebit' => $query2->func()->sum('Ledgers.debit'),'totalCredit' => $query2->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date >'=>$from_date, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first(); 

							@$TransactionDr[@$account_first_subgroup->id]['balance']+=@$query2->first()->totalDebit;
							@$TransactionCr[@$account_first_subgroup->id]['balance']+=@$query2->first()->totalCredit;

							$query1=$this->Ledgers->find();
							$query1->select(['ledger_account_id','totalDebit' => $query1->func()->sum('Ledgers.debit'),'totalCredit' => $query1->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date <='=>$from_date,'Ledgers.company_id'=>$st_company_id])->first();
							$OpeningBalanceForPrint[$account_first_subgroup->id]['name']=@$account_first_subgroup->name;
							@$OpeningBalanceForPrint[$account_first_subgroup->id]['balance']+=@$query1->first()->totalDebit-@$query1->first()->totalCredit;
						}
					}
				}
			}

		$this->set(compact('from_date','to_date', 'groupForPrint', 'closingValue', 'openingValue','ClosingBalanceForPrint','TransactionDr','TransactionCr','OpeningBalanceForPrint'));
	}

	public function secondSubGroupsTb($group_id,$from_date,$to_date)
	{ 
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		//$from_date=$this->request->query('from_date');
		//$to_date=$this->request->query('to_date');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		//pr($from_date); exit;
		$AccountFirstSubgroups=$this->Ledgers->LedgerAccounts->AccountSecondSubgroups->AccountFirstSubgroups->find()
		->where(['AccountFirstSubgroups.id '=>$group_id])
		->contain(['AccountSecondSubgroups.LedgerAccounts'=>function($q) use ($st_company_id){
				return $q->where(['company_id'=>$st_company_id]);
			}]);
		$TransactionDr=[]; $TransactionCr=[]; $OpeningBalanceForPrint=[]; $ClosingBalanceForPrint=[];
				foreach($AccountFirstSubgroups as $account_first_subgroup){
					foreach($account_first_subgroup->account_second_subgroups as $account_second_subgroup){  
						foreach($account_second_subgroup->ledger_accounts as $ledger_account){ 
							
							$query=$this->Ledgers->find();
							$query->select(['ledger_account_id','totalDebit' => $query->func()->sum('Ledgers.debit'),'totalCredit' => $query->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first(); //pr($query->first());
							@$ClosingBalanceForPrint[$account_second_subgroup->id]['name']=@$account_second_subgroup->name;
							@$ClosingBalanceForPrint[$account_second_subgroup->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
							

							$query2=$this->Ledgers->find();
							$query2->select(['ledger_account_id','totalDebit' => $query2->func()->sum('Ledgers.debit'),'totalCredit' => $query2->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date >'=>$from_date, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first(); 

							@$TransactionDr[@$account_second_subgroup->id]['balance']+=@$query2->first()->totalDebit;
							@$TransactionCr[@$account_second_subgroup->id]['balance']+=@$query2->first()->totalCredit;

							$query1=$this->Ledgers->find();
							$query1->select(['ledger_account_id','totalDebit' => $query1->func()->sum('Ledgers.debit'),'totalCredit' => $query1->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date <='=>$from_date,'Ledgers.company_id'=>$st_company_id])->first();
							$OpeningBalanceForPrint[$account_second_subgroup->id]['name']=@$account_second_subgroup->name;
							@$OpeningBalanceForPrint[$account_second_subgroup->id]['balance']+=@$query1->first()->totalDebit-@$query1->first()->totalCredit;
						}
					}
				}
	$this->set(compact('from_date','to_date', 'groupForPrint', 'closingValue',
'ClosingBalanceForPrint','TransactionDr','TransactionCr','OpeningBalanceForPrint'));
	}
	
public function ledgerAccountDataTb($group_id,$from_date,$to_date)
	{ 
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		//$from_date=$this->request->query('from_date');
		//$to_date=$this->request->query('to_date');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		//pr($from_date); exit;
		$AccountSecondSubgroups=$this->Ledgers->LedgerAccounts->AccountSecondSubgroups->find()
		->where(['AccountSecondSubgroups.id '=>$group_id])
		->contain(['LedgerAccounts'=>function($q) use ($st_company_id){
				return $q->where(['company_id'=>$st_company_id]);
			}]);
		//pr($AccountGroups->toArray()); exit;
		$TransactionDr=[]; $TransactionCr=[]; $OpeningBalanceForPrint=[]; $ClosingBalanceForPrint=[];
			  foreach($AccountSecondSubgroups as $account_second_subgroup){  
						foreach($account_second_subgroup->ledger_accounts as $ledger_account){ 
							$query=$this->Ledgers->find();
							$query->select(['ledger_account_id','totalDebit' => $query->func()->sum('Ledgers.debit'),'totalCredit' => $query->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first(); //pr($query->first());
							@$ClosingBalanceForPrint[$ledger_account->id]['name']=@$ledger_account->name;
							@$ClosingBalanceForPrint[$ledger_account->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
							

							$query2=$this->Ledgers->find();
							$query2->select(['ledger_account_id','totalDebit' => $query2->func()->sum('Ledgers.debit'),'totalCredit' => $query2->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date >'=>$from_date, 'Ledgers.transaction_date <='=>$to_date,'Ledgers.company_id'=>$st_company_id])->first(); 

							@$TransactionDr[@$ledger_account->id]['balance']+=@$query2->first()->totalDebit;
							@$TransactionCr[@$ledger_account->id]['balance']+=@$query2->first()->totalCredit;

							$query1=$this->Ledgers->find();
							$query1->select(['ledger_account_id','totalDebit' => $query1->func()->sum('Ledgers.debit'),'totalCredit' => $query1->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id, 'Ledgers.transaction_date <='=>$from_date,'Ledgers.company_id'=>$st_company_id])->first();
							$OpeningBalanceForPrint[$ledger_account->id]['name']=@$ledger_account->name;
							@$OpeningBalanceForPrint[$ledger_account->id]['balance']+=@$query1->first()->totalDebit-@$query1->first()->totalCredit;
						}
					}//pr($groupForPrint); exit;
				
	$this->set(compact('from_date','to_date', 'groupForPrint', 'closingValue',
'ClosingBalanceForPrint','TransactionDr','TransactionCr','OpeningBalanceForPrint'));
	}
	
	public function sendMail(){
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$ledger_account_id=$this->request->query('id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$empData=$this->Ledgers->Customers->Employees->get($s_employee_id);
		$from=date('Y-m-d',strtotime($this->request->query('from')));
		$To=date('Y-m-d',strtotime($this->request->query('to')));
		$email = new Email('default');
		$email->transport('gmail');
		
		$data=$this->Ledgers->LedgerAccounts->get($ledger_account_id);
		if($data->source_model=="Customers"){
			$customerData=$this->Ledgers->Customers->get($data->source_id,[
				'contain'=>['CustomerContacts']
			]);
			$email_to=$customerData->customer_contacts[0]->email;
		}else{
			$VendorsData=$this->Ledgers->Vendors->get($data->source_id,[
				'contain'=>['VendorContactPersons']
			]);
			$email_to=$VendorsData->vendor_contact_persons[0]->email;
		}
		//pr($email_to); exit;
		$company_data=$this->Ledgers->Customers->Companies->get($st_company_id);
		$from_name=$company_data->alias;
		if($ledger_account_id){
			$transaction_from_date= date('Y-m-d', strtotime($from));
			$transaction_to_date= date('Y-m-d', strtotime($To));
			$company = $this->Companies->get($st_company_id);
			//pr($transaction_from_date);pr($from);exit;
			if($from == date("Y-m-d",strtotime($company->accounting_book_date))){ 
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
		//pr($transaction_from_date); exit;
		//pr($OB->toArray()); exit;
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
		//		pr($opening_balance_ar); exit;
		
		}
		
		$transaction_from_date=date("d-m-Y",strtotime($transaction_from_date));
		$transaction_to_date=date("d-m-Y",strtotime($transaction_to_date));
		//pr($transaction_from_date); exit;
			
			$message_web='<table border="1">
				<tr>
					
					'; $message_web.='<td colspan="2">'.h($company_data->name).  ' </td>';
					$message_web.='<td colspan="2">'.h($transaction_from_date).  ' To'.h($transaction_to_date).'</td>';
						
						
				$message_web.='
				<tr>
					<td colspan="2" align="right">Opening Balance</td>
					'; 
						if($opening_balance_ar['credit'] > $opening_balance_ar['debit']){
							$t=$opening_balance_ar['credit'] - $opening_balance_ar['debit'];
							$message_web.='<td colspan="2" align="right">'.h($t).  ' Cr.</td>';
							
						}else{ 
							$t=$opening_balance_ar['credit'] - $opening_balance_ar['debit'];
							$message_web.='<td colspan="2" align="right">'.h(abs($t)).  ' Dr.</td>';
						}
						
				$message_web.='</tr>
					<tr>
						<th>Transaction Date</th>
						<th>Source</th>
						
						<th style="text-align:right;">Dr</th>
						<th style="text-align:right;">Cr</th>
					</tr>';
					$total_debit=0;
					$total_credit=0;
					foreach($Ledgers as $ledger){ 
					if($ledger->voucher_source != "Opening Balance"){
						$total_debit+=$ledger->debit;
						$total_credit+=$ledger->credit;
					@$message_web.= '
						<tr>
						<td>'.h( date("d-m-Y",strtotime($ledger->transaction_date))). '</td>
						<td>'.h( $ledger->voucher_source). '</td>
						<td>'.h( $ledger->debit). '</td>
						<td>'.h( $ledger->credit). '</td>
						</tr>';
					
					}}
					@$message_web.= '
						<tr>
						<td colspan="2" align="right">Total</td>
						<td align="right">'.h( $total_debit). '</td>
						<td align="right">'.h( $total_credit). '</td>
						</tr>';
					$closeDr=0;
					$closeCr=0;
					if($t < 0){
						$closeDr=(abs($t)+$total_debit);
						//pr($closeDr);
						$closeCr=$total_credit;
					}else{
						$closeCr=(abs($t)+$total_credit);
						$closeDr=$total_debit;
					}
					@$message_web.= '
						<tr>
						<td colspan="2" align="right">Closing Balance</td>
						'; 
						if($closeDr > $closeCr){
							$p=abs($closeDr)-abs($closeCr);
							$message_web.='<td colspan="2" align="right">'.h(abs($p)).  ' Dr.</td>';
							
						}else{ 
							$p=abs($closeCr)-abs($closeDr);
							$message_web.='<td colspan="2" align="right">'.h(abs($p)).  ' Cr.</td>';
						}
						
				$message_web.='</tr></table>';
		
		
	//	pr($message_web); exit;
		
		
		$email_to=$customerData->customer_contacts[0]->email;
		//$cc_mail=$empData->email;
		$sub="Account Statement";
		
		//$data="<table><tr><td>Table with single row has single data.</td></tr></table>";
		file_put_contents('excel.xls', $message_web);
		$attachments[]='excel.xls';
		//$to=$this->request->query('to');
	//	pr($attachments); exit;
		//$email_to="gopalkrishanp3@gmail.com";
		//$cc_mail="gopalkrishanp3@gmail.com";
		
		$cc_mail=$empData->email;
	//	pr($email_to);
		//pr($cc_mail); exit;
		$heading="PFA";
		$member_name="Gopal";
		$email->from(['dispatch@mogragroup.com' => $from_name])
					->to($email_to)
					->cc($cc_mail)
					->replyTo('dispatch@mogragroup.com')
					->subject($sub)
					->template('notice_send_email')
					->emailFormat('html')
					->viewVars(['content'=>$heading,'member_name'=>$member_name])
					->attachments($attachments);; 
					$email->send($heading);
		echo "Email Send successfully ";
		exit;
	}
	
}
