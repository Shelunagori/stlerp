<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Receipts Controller
 *
 * @property \App\Model\Table\ReceiptsTable $Receipts
 */
class ReceiptsController extends AppController
{

// Start CSV function 

		public function ReceiptVoucherExport()
		{	
			$session = $this->request->session();
			$st_company_id = $session->read('st_company_id');
			$where = [];
			$receipts = $this->Receipts->find()->where($where)->where(['company_id'=>$st_company_id])->contain(['ReceiptRows'=>function($q){
				$ReceiptRows = $this->Receipts->ReceiptRows->find();
				$totalCrCase = $ReceiptRows->newExpr()
					->addCase(
						$ReceiptRows->newExpr()->add(['cr_dr' => 'Cr']),
						$ReceiptRows->newExpr()->add(['amount']),
						'integer'
					);
				$totalDrCase = $ReceiptRows->newExpr()
					->addCase(
						$ReceiptRows->newExpr()->add(['cr_dr' => 'Dr']),
						$ReceiptRows->newExpr()->add(['amount']),
						'integer'
					);
				return $ReceiptRows->select([
						'total_cr' => $ReceiptRows->func()->sum($totalCrCase),
						'total_dr' => $ReceiptRows->func()->sum($totalDrCase)
					])
					->group('receipt_id')
					->autoFields(true);
				
			}]);

			$i=0; foreach ($receipts as $receipt){ $i++; 

				$data[] = [
					$i,date("d-m-Y",strtotime($receipt->transaction_date)),'#'.str_pad($receipt->voucher_no, 4, '0', STR_PAD_LEFT),$receipt->receipt_rows[0]->total_cr-$receipt->receipt_rows[0]->total_dr
				];			
				
			}
			
			$_serialize = 'data';
			$_header = ['Sr. No.', 'Transaction Date', 'Vocher No', 'Amount'];
			

			$this->response->download('Receipt_Voucher.csv');
			$this->viewBuilder()->className('CsvView.Csv');
			$this->set(compact('data', '_serialize', '_header', '_footer'));
		}	
// End CSV function 	


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
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Receipts->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->Receipts->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->Receipts->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		
		$where = [];
		$vouch_no = $this->request->query('vouch_no');
		$From = $this->request->query('From');
		$To = $this->request->query('To');
		
		$this->set(compact('vouch_no','From','To'));
		
		if(!empty($vouch_no)){
			$where['Receipts.voucher_no LIKE']=$vouch_no;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Receipts.transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Receipts.transaction_date <=']=$To;
		}
		
		
        $this->paginate = [
            'contain' => []
        ];
        $receipts = $this->paginate($this->Receipts->find()->where($where)->where(['company_id'=>$st_company_id,'Receipts.financial_year_id'=>$st_year_id])->contain(['ReceiptRows'=>function($q){
			$ReceiptRows = $this->Receipts->ReceiptRows->find();
			$totalCrCase = $ReceiptRows->newExpr()
				->addCase(
					$ReceiptRows->newExpr()->add(['cr_dr' => 'Cr']),
					$ReceiptRows->newExpr()->add(['amount']),
					'integer'
				);
			$totalDrCase = $ReceiptRows->newExpr()
				->addCase(
					$ReceiptRows->newExpr()->add(['cr_dr' => 'Dr']),
					$ReceiptRows->newExpr()->add(['amount']),
					'integer'
				);
			return $ReceiptRows->select([
					'total_cr' => $ReceiptRows->func()->sum($totalCrCase),
					'total_dr' => $ReceiptRows->func()->sum($totalDrCase),'receipt_id'
				])
				->group('ReceiptRows.receipt_id')
				->autoFields(true);
			
		}])->order(['transaction_date' => 'DESC']));
		
		
        $this->set(compact('receipts','url','financial_month_first','financial_month_last'));
        $this->set('_serialize', ['receipts']);
    }
	
	/* public function DataMigrate()
	{
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id'); 
		$Receipts = $this->Receipts->find()->contain(['ReceiptRows'])->toArray();
		
		foreach($Receipts as $Receipt){
			$total_dr=0;
			$total_cr=0;
			$bankAmt=0;
				foreach($Receipt->receipt_rows as $receipt_row){
					$OldReferenceDetails = $this->Receipts->ReferenceDetails->OldReferenceDetails->find()->where(['receipt_id'=>$Receipt->id,'ledger_account_id'=>$receipt_row->received_from_id])->toArray();
					 
					if($OldReferenceDetails){
						foreach($OldReferenceDetails as $old_data){ 
							$ReferenceDetail = $this->Receipts->ReferenceDetails->newEntity();
							$ReferenceDetail->company_id=$Receipt->company_id;
							$ReferenceDetail->ledger_account_id=$old_data->ledger_account_id;
							$ReferenceDetail->reference_type=$old_data->reference_type;
							$ReferenceDetail->reference_no=$old_data->reference_no;
							$ReferenceDetail->debit = $old_data->debit;
							$ReferenceDetail->credit = $old_data->credit;
							$ReferenceDetail->receipt_id = $Receipt->id;
							$ReferenceDetail->receipt_row_id = $receipt_row->id;
							$ReferenceDetail->transaction_date = $Receipt->transaction_date; 
							$this->Receipts->ReferenceDetails->save($ReferenceDetail);
							}
						
					}
					
					$ledger = $this->Receipts->Ledgers->newEntity();
					$ledger->company_id=$Receipt->company_id;
					$ledger->ledger_account_id = $receipt_row->received_from_id;
					if($receipt_row->cr_dr=="Cr"){
					$ledger->credit = $receipt_row->amount;
					$ledger->debit = 0;
					$total_cr=$total_cr+$receipt_row->amount;
					}else{
					$ledger->credit = 0;
					$ledger->debit = $receipt_row->amount;
					$total_dr=$total_dr+$receipt_row->amount;
					}
					$ledger->voucher_id = $Receipt->id;
					$ledger->voucher_source = 'Receipt Voucher';
					$ledger->transaction_date = $Receipt->transaction_date; 
					$this->Receipts->Ledgers->save($ledger);
				//pr($payment_row); exit;
				}
				$bankAmt=$total_dr-$total_cr;
				$ledger = $this->Receipts->Ledgers->newEntity();
				$ledger->company_id=$Receipt->company_id;
				$ledger->ledger_account_id = $Receipt->bank_cash_id;
				if($bankAmt > 0){
					$ledger->credit = $bankAmt;
					$ledger->debit = 0;
				}else{
					$ledger->debit = abs($bankAmt);
					$ledger->credit = 0;
				}
				
				$ledger->voucher_id = $Receipt->id;
				$ledger->voucher_source = 'Receipt Voucher';
				$ledger->transaction_date = $Receipt->transaction_date; //pr($ledger); exit;
				if($bankAmt != 0){
					$this->Receipts->Ledgers->save($ledger);
				}
			
			
		}
		
		echo "Done";
		exit;
	} */

	public function excelExport(){
		$this->viewBuilder()->layout('');
		
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$where = [];
		$vouch_no = $this->request->query('vouch_no');
		$From = $this->request->query('From');
		$To = $this->request->query('To');
		
		$this->set(compact('vouch_no','From','To'));
		
		if(!empty($vouch_no)){
			$where['Receipts.voucher_no LIKE']=$vouch_no;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Receipts.transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Receipts.transaction_date <=']=$To;
		}
		
        $receipts = $this->Receipts->find()->where($where)->where(['company_id'=>$st_company_id])->contain(['ReceiptRows'=>function($q){
			$ReceiptRows = $this->Receipts->ReceiptRows->find();
			$totalCrCase = $ReceiptRows->newExpr()
				->addCase(
					$ReceiptRows->newExpr()->add(['cr_dr' => 'Cr']),
					$ReceiptRows->newExpr()->add(['amount']),
					'integer'
				);
			$totalDrCase = $ReceiptRows->newExpr()
				->addCase(
					$ReceiptRows->newExpr()->add(['cr_dr' => 'Dr']),
					$ReceiptRows->newExpr()->add(['amount']),
					'integer'
				);
			return $ReceiptRows->select([
					'total_cr' => $ReceiptRows->func()->sum($totalCrCase),
					'total_dr' => $ReceiptRows->func()->sum($totalDrCase),'receipt_id'
				])
				->group('ReceiptRows.receipt_id')
				->autoFields(true);
			
		}])->order(['transaction_date' => 'DESC']);
		
		
        $this->set(compact('receipts','From','To'));
        $this->set('_serialize', ['receipts']);
	}
    /**
     * View method
     *
     * @param string|null $id Receipt id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$id = $this->EncryptingDecrypting->decryptData($id);
        $receipt = $this->Receipts->get($id, [
            'contain' => ['BankCashes','FinancialYears'=>['Companies'], 'Companies', 'ReceiptRows' => ['ReferenceDetails','ReceivedFroms'], 'Creator']
        ]);
		
		
		$ref_bal=[];
		foreach($receipt->receipt_rows as $receipt_rows){
			$ReferenceBalancess=$this->Receipts->ReferenceDetails->find()->where(['ledger_account_id'=>$receipt_rows->received_from_id,'receipt_id'=>$receipt->id]);
			$ref_bal[$receipt_rows->received_from_id]=$ReferenceBalancess->toArray();
		}
		
										
		$this->set(compact('ref_bal'));

        $this->set('receipt', $receipt);
        $this->set('_serialize', ['receipt']);
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
		$financial_year = $this->Receipts->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->Receipts->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->Receipts->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();

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
		
        $receipt = $this->Receipts->newEntity();

		


        if ($this->request->is('post')) {
			
            $receipt = $this->Receipts->patchEntity($receipt, $this->request->data);
			$receipt->company_id=$st_company_id;
			//Voucher Number Increment
			$last_voucher_no=$this->Receipts->find()->select(['voucher_no'])->where(['company_id' => $st_company_id,'Receipts.financial_year_id'=>$st_year_id])->order(['voucher_no' => 'DESC'])->first();
			if($last_voucher_no){
				$receipt->voucher_no=$last_voucher_no->voucher_no+1;
			}else{
				$receipt->voucher_no=1;
			}
			$receipt->financial_year_id=$st_year_id;
			$receipt->created_on=date("Y-m-d");
			$receipt->created_by=$s_employee_id;
			$receipt->transaction_date=date("Y-m-d",strtotime($receipt->transaction_date));
			
			//pr($receipt); exit;
			//Save receipt
            if ($this->Receipts->save($receipt)) {
				$total_cr=0; $total_dr=0;
				foreach($receipt->receipt_rows as $receipt_row){
					$ledger = $this->Receipts->Ledgers->newEntity();
					$ledger->company_id=$st_company_id;
					$ledger->ledger_account_id = $receipt_row->received_from_id;
					if($receipt_row->cr_dr=="Cr"){
					$ledger->credit = $receipt_row->amount;
					$ledger->debit = 0;
					$total_cr=$total_cr+$receipt_row->amount;
					}else{
					$ledger->credit = 0;
					$ledger->debit = $receipt_row->amount;
					$total_dr=$total_dr+$receipt_row->amount;
					}
					$ledger->voucher_id = $receipt->id;
					$ledger->voucher_source = 'Receipt Voucher';
					$ledger->transaction_date = $receipt->transaction_date;
					$this->Receipts->Ledgers->save($ledger);
					
					if(!empty($receipt_row->ref_rows))
					{
					foreach($receipt_row->ref_rows as $ref_rows){
						$ReferenceDetail = $this->Receipts->ReferenceDetails->newEntity();
						$ReferenceDetail->company_id=$st_company_id;
						$ReferenceDetail->reference_type=$ref_rows['ref_type'];
						$ReferenceDetail->reference_no=$ref_rows['ref_no'];
						$ReferenceDetail->ledger_account_id = $receipt_row->received_from_id;
						if($ref_rows['ref_cr_dr']=="Dr"){
							$ReferenceDetail->debit = $ref_rows['ref_amount'];
							$ReferenceDetail->credit = 0;
						}else{
							$ReferenceDetail->credit = $ref_rows['ref_amount'];
							$ReferenceDetail->debit = 0;
						}
						$ReferenceDetail->receipt_id = $receipt->id;
						$ReferenceDetail->receipt_row_id = $receipt_row->id;
						$ReferenceDetail->transaction_date = $receipt->transaction_date;
						$this->Receipts->ReferenceDetails->save($ReferenceDetail);
					} 
					
						$ReferenceDetail = $this->Receipts->ReferenceDetails->newEntity();
						$ReferenceDetail->company_id=$st_company_id;
						$ReferenceDetail->reference_type="On_account";
						$ReferenceDetail->ledger_account_id = $receipt_row->received_from_id;
						if($receipt_row->on_acc_dr_cr=="Dr"){
							$ReferenceDetail->debit = $receipt_row->on_acc;
							$ReferenceDetail->credit = 0;
						}else{
							$ReferenceDetail->credit = $receipt_row->on_acc;
							$ReferenceDetail->debit = 0;
						}
						$ReferenceDetail->receipt_id = $receipt->id;
						$ReferenceDetail->receipt_row_id = $receipt_row->id;
						$ReferenceDetail->transaction_date = $receipt->transaction_date;
						if($receipt_row->on_acc > 0){ 
							$this->Receipts->ReferenceDetails->save($ReferenceDetail);
						}
					}	
				}
				$bankAmt=$total_dr-$total_cr;
				$ledger = $this->Receipts->Ledgers->newEntity();
				$ledger->company_id=$st_company_id;
				$ledger->ledger_account_id = $receipt->bank_cash_id;
				if($bankAmt > 0){
					$ledger->credit = $bankAmt;
					$ledger->debit = 0;
				}else{
					$ledger->debit = abs($bankAmt);
					$ledger->credit = 0;
				}
				
				$ledger->voucher_id = $receipt->id;
				$ledger->voucher_source = 'Receipt Voucher';
				$ledger->transaction_date = $receipt->transaction_date;
				if($bankAmt != 0){
					$this->Receipts->Ledgers->save($ledger);
				}
				$this->Flash->success(__('The receipt has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The receipt could not be saved. Please, try again.'));
            }
        }
		
		$vr=$this->Receipts->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Receipt Voucher','sub_entity'=>'Cash/Bank'])->first();
		$ReceiptVouchersCashBank=$vr->id;
		$vouchersReferences = $this->Receipts->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		$BankCashes_selected='yes';
		if(sizeof($where)>0){
			$bankCashes = $this->Receipts->BankCashes->find('list',
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
		}
			
		
		$vr=$this->Receipts->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Receipt Voucher','sub_entity'=>'Received From'])->first();
		$ReceiptVouchersReceivedFrom=$vr->id;
		$vouchersReferences = $this->Receipts->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		$ReceivedFroms_selected='yes';
		if(sizeof($where)>0){
			$receivedFroms = $this->Receipts->ReceivedFroms->find('list',
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
        $this->set(compact('receipt', 'bankCashes', 'receivedFroms', 'financial_year', 'BankCashes_selected', 'ReceivedFroms_selected','chkdate','financial_month_first','financial_month_last','fromdate1','todate1'));
        $this->set('_serialize', ['receipt']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Receipt id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$id = $this->EncryptingDecrypting->decryptData($id);
		$s_employee_id=$this->viewVars['s_employee_id'];
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Receipts->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->Receipts->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->Receipts->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		
        $receipt = $this->Receipts->get($id, [
            'contain' => ['ReceiptRows'=>['ReferenceDetails','ReceivedFroms']]
        ]);
		//pr($receipt); exit;

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
		

		$old_ref_rows=[];
		
		
        if ($this->request->is(['patch', 'post', 'put'])) {
            $receipt = $this->Receipts->patchEntity($receipt, $this->request->data);
			$receipt->company_id=$st_company_id;
			
			
			$receipt->edited_on=date("Y-m-d");
			$receipt->edited_by=$s_employee_id;
			$receipt->transaction_date=date("Y-m-d",strtotime($receipt->transaction_date));
				
			//Save receipt
			//pr($receipt->bank_cash_id); exit;
            if ($this->Receipts->save($receipt)) {
				$old_ledger_data=$this->Receipts->Ledgers->find()->where(['voucher_id' => $receipt->id, 'voucher_source' => 'Receipt Voucher','ledger_account_id'=>$receipt->bank_cash_id])->first();
				//pr($old_ledger_data); exit;
				$this->Receipts->Ledgers->deleteAll(['voucher_id' => $receipt->id, 'voucher_source' => 'Receipt Voucher']);
				$this->Receipts->ReferenceDetails->deleteAll(['receipt_id' => $receipt->id]);
				$total_cr=0; $total_dr=0;
				foreach($receipt->receipt_rows as $receipt_row){
					$ledger = $this->Receipts->Ledgers->newEntity();
					$ledger->company_id=$st_company_id;
					$ledger->ledger_account_id = $receipt_row->received_from_id;
					if($receipt_row->cr_dr=="Cr"){
					$ledger->credit = $receipt_row->amount;
					$ledger->debit = 0;
					$total_cr=$total_cr+$receipt_row->amount;
					}else{
					$ledger->credit = 0;
					$ledger->debit = $receipt_row->amount;
					$total_dr=$total_dr+$receipt_row->amount;
					}
					$ledger->voucher_id = $receipt->id;
					$ledger->voucher_source = 'Receipt Voucher';
					$ledger->transaction_date = $receipt->transaction_date;
					$this->Receipts->Ledgers->save($ledger);
					
					if(!empty($receipt_row->ref_rows))
					{ 
					foreach($receipt_row->ref_rows as $ref_rows){ 
						$ReferenceDetail = $this->Receipts->ReferenceDetails->newEntity();
						$ReferenceDetail->company_id=$st_company_id;
						$ReferenceDetail->reference_type=$ref_rows['ref_type'];
						$ReferenceDetail->reference_no=$ref_rows['ref_no'];
						$ReferenceDetail->ledger_account_id = $receipt_row->received_from_id;
						if($ref_rows['ref_cr_dr']=="Dr"){
							$ReferenceDetail->debit = $ref_rows['ref_amount'];
							$ReferenceDetail->credit = 0;
						}else{
							$ReferenceDetail->credit = $ref_rows['ref_amount'];
							$ReferenceDetail->debit = 0;
						}
						$ReferenceDetail->receipt_id = $receipt->id;
						$ReferenceDetail->receipt_row_id = $receipt_row->id;
						$ReferenceDetail->transaction_date = $receipt->transaction_date;
						$this->Receipts->ReferenceDetails->save($ReferenceDetail);
					} 
					
						$ReferenceDetail = $this->Receipts->ReferenceDetails->newEntity();
						$ReferenceDetail->company_id=$st_company_id;
						$ReferenceDetail->reference_type="On_account";
						$ReferenceDetail->ledger_account_id = $receipt_row->received_from_id;
						if($receipt_row->on_acc_dr_cr=="Dr"){
							$ReferenceDetail->debit = $receipt_row->on_acc;
							$ReferenceDetail->credit = 0;
						}else{
							$ReferenceDetail->credit = $receipt_row->on_acc;
							$ReferenceDetail->debit = 0;
						}
						$ReferenceDetail->receipt_id = $receipt->id;
						$ReferenceDetail->receipt_row_id = $receipt_row->id;
						$ReferenceDetail->transaction_date = $receipt->transaction_date;
						if($receipt_row->on_acc > 0){ 
							$this->Receipts->ReferenceDetails->save($ReferenceDetail);
						}
					}
				}
				$bankAmt=$total_dr-$total_cr;
				$ledger = $this->Receipts->Ledgers->newEntity();
				$ledger->company_id=$st_company_id;
				$ledger->ledger_account_id = $receipt->bank_cash_id;
				if($bankAmt > 0){
					$ledger->credit = $bankAmt;
					$ledger->debit = 0;
				}else{
					$ledger->debit = abs($bankAmt);
					$ledger->credit = 0;
				}
				
				$ledger->voucher_id = $receipt->id;
				$ledger->voucher_source = 'Receipt Voucher';
				$ledger->transaction_date = $receipt->transaction_date;
				
				if(!empty($old_ledger_data)){
					$ledger->reconciliation_date = $old_ledger_data->reconciliation_date;
				}else{
					$ledger->reconciliation_date = "0000-00-00";
				}
				//pr($ledger); exit;
				if($bankAmt != 0){
					
					$this->Receipts->Ledgers->save($ledger);
				}
	
				
                $this->Flash->success(__('The receipt has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The receipt could not be saved. Please, try again.'));
            }
        }
		
		$vr=$this->Receipts->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Receipt Voucher','sub_entity'=>'Cash/Bank'])->first();
		$ReceiptVouchersCashBank=$vr->id;
		$vouchersReferences = $this->Receipts->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		$BankCashes_selected='yes';
		if(sizeof($where)>0){
			$bankCashes = $this->Receipts->BankCashes->find('list',
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
		}
			
		
		$vr=$this->Receipts->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Receipt Voucher','sub_entity'=>'Received From'])->first();
		$ReceiptVouchersReceivedFrom=$vr->id;
		$vouchersReferences = $this->Receipts->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		$ReceivedFroms_selected='yes';
		if(sizeof($where)>0){
			$receivedFroms = $this->Receipts->ReceivedFroms->find('list',
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
		
        $this->set(compact('receipt', 'bankCashes', 'receivedFroms', 'financial_year', 'BankCashes_selected', 'ReceivedFroms_selected','chkdate','financial_month_first','financial_month_last'));
        $this->set('_serialize', ['receipt']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Receipt id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $receipt = $this->Receipts->get($id);
        if ($this->Receipts->delete($receipt)) {
            $this->Flash->success(__('The receipt has been deleted.'));
        } else {
            $this->Flash->error(__('The receipt could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	
	
	public function fetchRefNumbers($received_from_id=null,$cr_dr=null){
		$this->viewBuilder()->layout('');
		$ReferenceBalances=$this->Receipts->ReferenceDetails->find()->where(['ledger_account_id'=>$received_from_id]);
		$this->set(compact('ReferenceBalances','cr_dr'));
	}
	
	public function fetchRefNumbersEdit($received_frfetchRefNumbersEditom_id=null,$reference_no=null,$credit=null,$debit=null,$cr_dr=null){
		$this->viewBuilder()->layout('');
		$received_from_id=$this->request->query['received_from_id'];
		$cr_dr=$this->request->query['cr_dr'];
		$reference_no=$this->request->query['reference_no'];
		$debit=$this->request->query['debit'];
		$credit=$this->request->query['credit'];
		$ReferenceBalances=$this->Receipts->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id]);
		$this->set(compact('ReferenceBalances', 'reference_no', 'credit', 'debit', 'cr_dr','received_from_id'));
	}
	
	function checkRefNumberUnique($received_from_id,$i){
		$reference_no=$this->request->query['ref_rows'][$received_from_id][$i]['ref_no'];
		$ReferenceBalances=$this->Receipts->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id,'reference_no'=>$reference_no]);
		if($ReferenceBalances->count()==0){
			echo 'true';
		}else{
			echo 'false';
		}
		exit;
	}
	
	function checkRefNumberUniqueEdit($received_from_id,$i,$is_old){
		$reference_no=$this->request->query['ref_rows'][$received_from_id][$i]['ref_no'];
		$ReferenceBalances=$this->Receipts->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id,'reference_no'=>$reference_no]);
		if($ReferenceBalances->count()==1 && $is_old=="yes"){
			echo 'true';
		}elseif($ReferenceBalances->count()==0){
			echo 'true';
		}else{
			echo 'false';
		}
		exit;
	}
	
	function deleteAllRefNumbers($old_received_from_id,$receipt_id){
		$ReferenceDetails=$this->Receipts->ReferenceDetails->find()->where(['ledger_account_id'=>$old_received_from_id,'receipt_id'=>$receipt_id]);
		foreach($ReferenceDetails as $ReferenceDetail){
			if($ReferenceDetail->reference_type=="New Reference" || $ReferenceDetail->reference_type=="Advance Reference"){
				$this->Receipts->ReferenceBalances->deleteAll(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no]);
				
				$RDetail=$this->Receipts->ReferenceDetails->get($ReferenceDetail->id);
				$this->Receipts->ReferenceDetails->delete($RDetail);
			}elseif($ReferenceDetail->reference_type=="Against Reference"){
				if(!empty($ReferenceDetail->credit)){
					$ReferenceBalance=$this->Receipts->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
					$ReferenceBalance=$this->Receipts->ReferenceBalances->get($ReferenceBalance->id);
					$ReferenceBalance->credit=$ReferenceBalance->credit-$ReferenceDetail->credit;
					$this->Receipts->ReferenceBalances->save($ReferenceBalance);
				}elseif(!empty($ReferenceDetail->debit)){
					$ReferenceBalance=$this->Receipts->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
					$ReferenceBalance=$this->Receipts->ReferenceBalances->get($ReferenceBalance->id);
					$ReferenceBalance->debit=$ReferenceBalance->debit-$ReferenceDetail->debit;
					$this->Receipts->ReferenceBalances->save($ReferenceBalance);
				}
				$RDetail=$this->Receipts->ReferenceDetails->get($ReferenceDetail->id);
				$this->Receipts->ReferenceDetails->delete($RDetail);
			}
		}		exit;
	}
	
	function deleteOneRefNumbers(){
		$old_received_from_id=$this->request->query['old_received_from_id'];
		$receipt_id=$this->request->query['receipt_id'];
		$old_ref=$this->request->query['old_ref'];
		$old_ref_type=$this->request->query['old_ref_type'];
		
		if($old_ref_type=="New Reference" || $old_ref_type=="Advance Reference"){
			$this->Receipts->ReferenceBalances->deleteAll(['ledger_account_id'=>$old_received_from_id,'reference_no'=>$old_ref]);
			$this->Receipts->ReferenceDetails->deleteAll(['ledger_account_id'=>$old_received_from_id,'reference_no'=>$old_ref]);
		}elseif($old_ref_type=="Against Reference"){
			$ReferenceDetail=$this->Receipts->ReferenceDetails->find()->where(['ledger_account_id'=>$old_received_from_id,'receipt_id'=>$receipt_id,'reference_no'=>$old_ref])->first();
			if(!empty($ReferenceDetail->credit)){
				$ReferenceBalance=$this->Receipts->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
				$ReferenceBalance=$this->Receipts->ReferenceBalances->get($ReferenceBalance->id);
				$ReferenceBalance->credit=$ReferenceBalance->credit-$ReferenceDetail->credit;
				$this->Receipts->ReferenceBalances->save($ReferenceBalance);
			}elseif(!empty($ReferenceDetail->debit)){
				$ReferenceBalance=$this->Receipts->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
				$ReferenceBalance=$this->Receipts->ReferenceBalances->get($ReferenceBalance->id);
				$ReferenceBalance->debit=$ReferenceBalance->debit-$ReferenceDetail->debit;
				$this->Receipts->ReferenceBalances->save($ReferenceBalance);
			}
			$RDetail=$this->Receipts->ReferenceDetails->get($ReferenceDetail->id);
			$this->Receipts->ReferenceDetails->delete($RDetail);
		}
		
		exit;
	}
	

	
	
	
}
