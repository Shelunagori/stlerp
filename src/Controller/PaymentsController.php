<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Mailer\Email;

/**
 * Payments Controller
 *
 * @property \App\Model\Table\PaymentsTable $Payments
 */
class PaymentsController extends AppController
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
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Payments->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->Payments->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->Payments->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		
		$where = [];
		$vouch_no = $this->request->query('vouch_no');
		$From = $this->request->query('From');
		$To = $this->request->query('To');
		
		$this->set(compact('vouch_no','From','To'));
		
		if(!empty($vouch_no)){
			$where['Payments.voucher_no LIKE']=$vouch_no;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Payments.transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Payments.transaction_date <=']=$To;
		}
		
		
		
        $this->paginate = [
            'contain' => []
        ];
		
		
		$payments = $this->paginate($this->Payments->find()->where($where)->where(['company_id'=>$st_company_id,'Payments.financial_year_id'=>$st_year_id])->contain(['PaymentRows'=>function($q){
			$PaymentRows = $this->Payments->PaymentRows->find();
			$totalCrCase = $PaymentRows->newExpr()
				->addCase(
					$PaymentRows->newExpr()->add(['cr_dr' => 'Cr']),
					$PaymentRows->newExpr()->add(['amount']),
					'integer'
				);
			$totalDrCase = $PaymentRows->newExpr()
				->addCase(
					$PaymentRows->newExpr()->add(['cr_dr' => 'Dr']),
					$PaymentRows->newExpr()->add(['amount']),
					'integer'
				);
			return $PaymentRows->select([
					'total_cr' => $PaymentRows->func()->sum($totalCrCase),
					'total_dr' => $PaymentRows->func()->sum($totalDrCase),'payment_id'
				])
				->group('payment_id')
				
				->autoFields(true);
			
		}])->order(['voucher_no'=>'DESC']));
		

        $this->set(compact('payments','url','financial_month_first','financial_month_last'));
        $this->set('_serialize', ['payments']);
    }
	
	
	/* public function DataMigrate()
	{
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id'); 
		$Payments = $this->Payments->find()->contain(['PaymentRows'])->toArray();
		//pr($Payments); exit;
		foreach($Payments as $payment){
			$total_dr=0;
			$total_cr=0;
			$bankAmt=0;
				foreach($payment->payment_rows as $payment_row){
					$OldReferenceDetails = $this->Payments->ReferenceDetails->OldReferenceDetails->find()->where(['payment_id'=>$payment->id,'ledger_account_id'=>$payment_row->received_from_id])->toArray();
					if($OldReferenceDetails){
						foreach($OldReferenceDetails as $old_data){ //pr($old_data); exit;
							$ReferenceDetail = $this->Payments->ReferenceDetails->newEntity();
							$ReferenceDetail->company_id=$payment->company_id;
							$ReferenceDetail->ledger_account_id=$old_data->ledger_account_id;
							$ReferenceDetail->reference_type=$old_data->reference_type;
							$ReferenceDetail->reference_no=$old_data->reference_no;
							$ReferenceDetail->debit = $old_data->debit;
							$ReferenceDetail->credit = $old_data->credit;
							$ReferenceDetail->payment_id = $payment->id;
							$ReferenceDetail->payment_row_id = $payment_row->id;
							$ReferenceDetail->transaction_date = $payment->transaction_date; //pr($ReferenceDetail); exit;
							$this->Payments->ReferenceDetails->save($ReferenceDetail);
							}
						
					}
					
					$ledger = $this->Payments->Ledgers->newEntity();
					$ledger->company_id=$payment->company_id;
					$ledger->ledger_account_id = $payment_row->received_from_id;
					if($payment_row->cr_dr=="Cr"){
					$ledger->credit = $payment_row->amount;
					$ledger->debit = 0;
					$total_cr=$total_cr+$payment_row->amount;
					}else{
					$ledger->credit = 0;
					$ledger->debit = $payment_row->amount;
					$total_dr=$total_dr+$payment_row->amount;
					}
					$ledger->voucher_id = $payment->id;
					$ledger->voucher_source = 'Payment Voucher';
					$ledger->transaction_date = $payment->transaction_date; //pr($total_cr);pr($total_dr); exit;
					$this->Payments->Ledgers->save($ledger);
				//pr($payment_row); exit;
				}
				$bankAmt=$total_dr-$total_cr;
				$ledger = $this->Payments->Ledgers->newEntity();
				$ledger->company_id=$payment->company_id;
				$ledger->ledger_account_id = $payment->bank_cash_id;
				if($bankAmt > 0){
					$ledger->credit = $bankAmt;
					$ledger->debit = 0;
				}else{
					$ledger->debit = abs($bankAmt);
					$ledger->credit = 0;
				}
				
				$ledger->voucher_id = $payment->id;
				$ledger->voucher_source = 'Payment Voucher';
				$ledger->transaction_date = $payment->transaction_date; //pr($ledger); exit;
				if($bankAmt != 0){
					$this->Payments->Ledgers->save($ledger);
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
			$where['Payments.voucher_no LIKE']=$vouch_no;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['Payments.transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['Payments.transaction_date <=']=$To;
		}
		
		
		$payments = $this->Payments->find()->where($where)->where(['company_id'=>$st_company_id])->contain(['PaymentRows'=>function($q){
			$PaymentRows = $this->Payments->PaymentRows->find();
			$totalCrCase = $PaymentRows->newExpr()
				->addCase(
					$PaymentRows->newExpr()->add(['cr_dr' => 'Cr']),
					$PaymentRows->newExpr()->add(['amount']),
					'integer'
				);
			$totalDrCase = $PaymentRows->newExpr()
				->addCase(
					$PaymentRows->newExpr()->add(['cr_dr' => 'Dr']),
					$PaymentRows->newExpr()->add(['amount']),
					'integer'
				);
			return $PaymentRows->select([
					'total_cr' => $PaymentRows->func()->sum($totalCrCase),
					'total_dr' => $PaymentRows->func()->sum($totalDrCase)
				])
				->group('payment_id')
				
				->autoFields(true);
			
		}])->order(['voucher_no'=>'DESC']);
		

        $this->set(compact('payments','From','To'));
        $this->set('_serialize', ['payments']);
	}
    /**
     * View method
     *
     * @param string|null $id Payment id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $payment = $this->Payments->get($id, [
            'contain' => ['BankCashes', 'Companies', 'PaymentRows' => ['ReferenceDetails','ReceivedFroms'], 'Creator']
        ]);
		$petty_cash_voucher_row_data=[];
		$petty_cash_grn_data=[];
		$petty_cash_invoice_data=[];
		$aval=0;
		foreach($payment->payment_rows as $petty_cash_voucher_row){
			if(!empty($petty_cash_voucher_row->grn_ids)){
			$petty_cash_voucher_row_data = explode(',',trim(@$petty_cash_voucher_row->grn_ids,','));
			$i=0;
				foreach($petty_cash_voucher_row_data as $petty_cash_voucher_row_data){
				$Grn= $this->Payments->Grns->get($petty_cash_voucher_row_data);
				//echo $petty_cash_voucher_row->id;
				$petty_cash_grn_data[$petty_cash_voucher_row->id][$i]=$Grn;
				$i++;
				$aval=1;
				}
			}	
			if(!empty($petty_cash_voucher_row->invoice_ids)){
			$petty_cash_voucher_row_data = explode(',',trim(@$petty_cash_voucher_row->invoice_ids,','));
			$j=0;
				foreach($petty_cash_voucher_row_data as $petty_cash_voucher_row_data){
				$Invoice= $this->Payments->Invoices->get($petty_cash_voucher_row_data);
				$petty_cash_invoice_data[$petty_cash_voucher_row->id][$j]=$Invoice;
				$j++;
				$aval=1;
				}
			}
	    }
		
		$ref_details=[];
		foreach($payment->payment_rows as $payment_row){
			$ReferenceDetails=$this->Payments->ReferenceDetails->find()->where(['ledger_account_id'=>$payment_row->received_from_id,'payment_id'=>$payment->id]);
			$ref_details[$payment_row->received_from_id]=$ReferenceDetails->toArray();
		}
		
		
		$this->set(compact('ref_details','petty_cash_invoice_data','petty_cash_grn_data','aval'));
        $this->set('payment', $payment);
        $this->set('_serialize', ['payment']);
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
		$company_data=$this->Payments->Companies->get($st_company_id);
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Payments->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->Payments->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->Payments->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		//pr($financial_month_first->month); 
		//pr($financial_month_last->month); exit;

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
	
		
        $payment = $this->Payments->newEntity();
		
        if ($this->request->is('post')) {  
		$grnIds=[];$invoiceIds=[];
		
		
		foreach( $this->request->data['payment_rows'] as $key =>  $pr)
		{
			$grnstring="";$invoiceString="";
			if(!empty($pr['grn_ids']))
			{
				foreach( $pr['grn_ids'] as  $dr)
				{
						$grnstring .=$dr.',';
				}
				$grnIds[$key] =$grnstring;
			}
			if(!empty($pr['invoice_ids']))
			{
				foreach( $pr['invoice_ids'] as  $dr)
				{
						$invoiceString .=$dr.',';
				}
				$invoiceIds[$key] =$invoiceString;
			}
		}
            $payment = $this->Payments->patchEntity($payment, $this->request->data);
			$payment->company_id=$st_company_id;
			//Voucher Number Increment
			$last_voucher_no=$this->Payments->find()->select(['voucher_no'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['voucher_no' => 'DESC'])->first();
			if($last_voucher_no){
				$payment->voucher_no=$last_voucher_no->voucher_no+1;
			}else{
				$payment->voucher_no=1;
			}
			
			$payment->created_on=date("Y-m-d");
			$payment->financial_year_id=$st_year_id;
			$payment->created_by=$s_employee_id;
			$payment->transaction_date=date("Y-m-d",strtotime($payment->transaction_date));
			foreach($payment->payment_rows as $key => $payment_row)
			{
				$payment_row->grn_ids = @$grnIds[$key];
				$payment_row->invoice_ids =@$invoiceIds[$key];
			}
		if ($this->Payments->save($payment)) {
				
				$total_cr=0; $total_dr=0;
				foreach($payment->payment_rows as $key => $payment_row)
				{
					if(count($grnIds)>0)
					{
						$grnArrays = explode(',',@$grnIds[$key]);
						foreach($grnArrays as $grnArray)
						{ 
							$grn = $this->Payments->Grns->query();
							$grn->update()
							->set(['purchase_thela_bhada_status' => 'yes','payment_id' => $payment->id])
							->where(['id' => $grnArray])
							->execute();
						}
					}
					if(count($invoiceIds)>0)
					{
						$invoiceArrays = explode(',',@$invoiceIds[$key]);
						foreach($invoiceArrays as $invoiceArray)
						{ 
							$grn = $this->Payments->Invoices->query();
							$grn->update()
							->set(['sales_thela_bhada_status' => 'yes','payment_id' => $payment->id])
							->where(['id' => $invoiceArray])
							->execute();
						}
					}
					

					
				}
				$total_cr=0; $total_dr=0;
				foreach($payment->payment_rows as $payment_row){ 
					$ledger = $this->Payments->Ledgers->newEntity();
					$ledger->company_id=$st_company_id;
					$ledger->ledger_account_id = $payment_row->received_from_id;
					if($payment_row->cr_dr=="Cr"){
					$ledger->credit = $payment_row->amount;
					$ledger->debit = 0;
					$total_cr=$total_cr+$payment_row->amount;
					}else{
					$ledger->credit = 0;
					$ledger->debit = $payment_row->amount;
					$total_dr=$total_dr+$payment_row->amount;
					}
					$ledger->voucher_id = $payment->id;
					$ledger->voucher_source = 'Payment Voucher';
					$ledger->transaction_date = $payment->transaction_date;
					$this->Payments->Ledgers->save($ledger);
					if(!empty($payment_row->ref_rows))
					{
					foreach($payment_row->ref_rows as $ref_rows){ 
						$ReferenceDetail = $this->Payments->ReferenceDetails->newEntity();
						$ReferenceDetail->company_id=$st_company_id;
						$ReferenceDetail->reference_type=$ref_rows['ref_type'];
						$ReferenceDetail->reference_no=$ref_rows['ref_no'];
						$ReferenceDetail->ledger_account_id = $payment_row->received_from_id;
						if($ref_rows['ref_cr_dr']=="Dr"){
							$ReferenceDetail->debit = $ref_rows['ref_amount'];
							$ReferenceDetail->credit = 0;
						}else{
							$ReferenceDetail->credit = $ref_rows['ref_amount'];
							$ReferenceDetail->debit = 0;
						}
						$ReferenceDetail->payment_id = $payment->id;
						$ReferenceDetail->payment_row_id = $payment_row->id;
						$ReferenceDetail->transaction_date = $payment->transaction_date;
						$this->Payments->ReferenceDetails->save($ReferenceDetail);
					} 
					
						$ReferenceDetail = $this->Payments->ReferenceDetails->newEntity();
						$ReferenceDetail->company_id=$st_company_id;
						$ReferenceDetail->reference_type="On_account";
						$ReferenceDetail->ledger_account_id = $payment_row->received_from_id;
						if($payment_row->on_acc_cr_dr=="Dr"){
							$ReferenceDetail->debit = $payment_row->on_acc;
							$ReferenceDetail->credit = 0;
						}else{
							$ReferenceDetail->credit = $payment_row->on_acc;
							$ReferenceDetail->debit = 0;
						}
						$ReferenceDetail->payment_id = $payment->id;
						$ReferenceDetail->payment_row_id = $payment_row->id;
						$ReferenceDetail->transaction_date = $payment->transaction_date;
						if($payment_row->on_acc > 0){ 
							$this->Payments->ReferenceDetails->save($ReferenceDetail);
						}
					}
						
					
				}
				
				foreach($payment->payment_rows as $payment_row){
					$LedgerAccount=$this->Payments->PaymentRows->LedgerAccounts->get($payment_row->received_from_id);
					if($LedgerAccount->source_model=="Vendors"){
						$id=$payment->id;
						 $paymentData = $this->Payments->PaymentRows->get($payment_row->id, [
							'contain' => ['ReferenceDetails','ReceivedFroms']
						]);
						$Vendor=$this->Payments->Vendors->get($LedgerAccount->source_id, ['contain' =>['VendorContactPersons']]);
						//pr($payment->creator->email); exit;
						$email = new Email('default');
						$email->transport('gmail');
						$email_to=$Vendor->vendor_contact_persons[0]->email;
						//ss$cc_mail=$payment->creator->email;
						//pr($email_to);
						//pr($cc_mail); exit;
						$email_to="gopalkrishanp3@gmail.com";
						$cc_mail="gopal@phppoets.in";
						$member_name="Gopal";
						$from_name=$company_data->alias;
						$sub="Payment advice";
						
						
						$email->from(['dispatch@mogragroup.com' => $from_name])
						->to($email_to)
						->cc($cc_mail)
						->replyTo('dispatch@mogragroup.com')
						->subject($sub)
						->template('send_payment_voucher')
						->emailFormat('html')
						->viewVars(['payment'=>$paymentData,'member_name'=>$paymentData->ReceivedFrom->name,'company'=>$company_data->name,'vendorAddress'=>$Vendor->address]);  //pr($company_data->name); exit;
						$email->send(); 
						
					}
				}
				
				$bankAmt=$total_dr-$total_cr;
				//pr($bankAmt); exit;

				//Ledger posting for bankcash
				$ledger = $this->Payments->Ledgers->newEntity();
				$ledger->company_id=$st_company_id;
				$ledger->ledger_account_id = $payment->bank_cash_id;
				if($bankAmt > 0){
					$ledger->credit = $bankAmt;
					$ledger->debit = 0;
				}else{
					$ledger->debit = abs($bankAmt);
					$ledger->credit = 0;
				}
				
				$ledger->voucher_id = $payment->id;
				$ledger->voucher_source = 'Payment Voucher';
				$ledger->transaction_date = $payment->transaction_date;
				if($bankAmt != 0){
					$this->Payments->Ledgers->save($ledger);
				}
				
                $this->Flash->success(__('The payment has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The payment could not be saved. Please, try again.'));
            }
        }
        $vr=$this->Payments->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Payment Voucher','sub_entity'=>'Cash/Bank'])->first();
		$ReceiptVouchersCashBank=$vr->id;
		$vouchersReferences = $this->Payments->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		$BankCashes_selected='yes';
		if(sizeof($where)>0){
			$bankCashes = $this->Payments->BankCashes->find('list',
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
		//pr($bankCashes->toArray())	; exit;
		
		$vr=$this->Payments->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Payment Voucher','sub_entity'=>'Paid To'])->first();
		$ReceiptVouchersReceivedFrom=$vr->id;
		$vouchersReferences = $this->Payments->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		$ReceivedFroms_selected='yes';
		if(sizeof($where)>0){
			$receivedFroms = $this->Payments->ReceivedFroms->find('list',
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
        $this->set(compact('payment', 'bankCashes', 'receivedFroms', 'financial_year', 'BankCashes_selected', 'ReceivedFroms_selected','chkdate','financial_month_first','financial_month_last'));
        $this->set('_serialize', ['payment']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Payment id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function SendMail($id = null)
    {
		 $payment = $this->Payments->get($id, [
            'contain' => ['BankCashes', 'Companies', 'PaymentRows' => ['ReferenceDetails','ReceivedFroms'], 'Creator']
        ]);
		?>
		
		
		<?php 
	pr($payment); exit;
	}
	
	public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		
		$s_employee_id=$this->viewVars['s_employee_id'];
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Payments->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		 
		
		$financial_month_first = $this->Payments->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->Payments->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		
		
		
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

		
        $payment = $this->Payments->get($id, [
            'contain' => ['PaymentRows'=>['LedgerAccounts','ReferenceDetails']]
        ]);
		//pr($payment);exit;
		$old_ref_rows=[];
		$old_received_from_ids=[];
		$old_reference_numbers=[];
		
		foreach($payment->payment_rows as $payment_row){
			$ReferenceDetails=$this->Payments->ReferenceDetails->find()->where(['ledger_account_id'=>$payment_row->received_from_id,'payment_id'=>$payment->id]);
			foreach($ReferenceDetails as $ReferenceDetail){
				$old_reference_numbers[$payment_row->received_from_id][]=$ReferenceDetail->reference_no;
			}
			$old_ref_rows[$payment_row->received_from_id]=$ReferenceDetails->toArray();
			$old_received_from_ids[]=$payment_row->received_from_id;
		}
		//pr($old_ref_rows); exit;
        if ($this->request->is(['patch', 'post', 'put'])) {
			$grnIds=[];$invoiceIds=[];
			foreach( $this->request->data['payment_rows'] as $key =>  $pr)
			{
				$grnstring="";$invoiceString="";	
				if(!empty($pr['grn_ids']))
				{
					foreach( $pr['grn_ids'] as  $dr)
					{
						$grnstring .=$dr.',';
					}
						$grnIds[$key] =$grnstring;
						$payment_row->grn_ids =$grnIds[$key];
				}
				if(!empty($pr['invoice_ids']))
				{
					foreach( $pr['invoice_ids'] as  $dr)
					{
						$invoiceString .=$dr.',';
					}
						$invoiceIds[$key] =$invoiceString;
						$payment_row->invoice_ids =$invoiceIds[$key];
				}
			}
            $payment = $this->Payments->patchEntity($payment, $this->request->data);
			$payment->company_id=$st_company_id;
			
			
			$payment->edited_on=date("Y-m-d");
			$payment->edited_by=$s_employee_id;
			$payment->transaction_date=date("Y-m-d",strtotime($payment->transaction_date));
				
			//Save receipt
			$grn    = $this->Payments->Grns->query();
				    $grn->update()
				    ->set(['purchase_thela_bhada_status' => 'no','payment_id' => ''])
				    ->where(['payment_id' => $payment->id])
				    ->execute();
		   $invoice = $this->Payments->Invoices->query();
					  $invoice->update()
					  ->set(['sales_thela_bhada_status' => 'no','payment_id' => ''])
					  ->where(['payment_id' => $payment->id])
					  ->execute();
			foreach($payment->payment_rows as $key => $payment_row)
			{
				if(count($grnIds)>0)
				{
					$payment_row->grn_ids =@$grnIds[$key];
				}
				if(count($invoiceIds)>0)
				{
					$payment_row->invoice_ids =@$invoiceIds[$key];
				}

			}
			
			//pr($payment);exit;
			if ($this->Payments->save($payment)) {
				//pr($payment);exit;
				foreach($payment->payment_rows as $key => $payment_row)
				{
					if(count($grnIds)>0)
					{          
						$grnArrays = explode(',',@$grnIds[$key]);
						foreach($grnArrays as $grnArray)
						{ 
							$grn = $this->Payments->Grns->query();
							$grn->update()
							->set(['purchase_thela_bhada_status' => 'yes','payment_id' => $payment->id])
							->where(['id' => $grnArray])
							->execute();
					   }
					}
					if(count($invoiceIds)>0)
					{          
						$invoiceArrays = explode(',',@$invoiceIds[$key]);
						foreach($invoiceArrays as $invoiceArray)
						{ 
							$invoice = $this->Payments->Invoices->query();
							$invoice->update()
							->set(['sales_thela_bhada_status' => 'yes','payment_id' => $payment->id])
							->where(['id' => $invoiceArray])
							->execute();
					   }
					}
				}
				$old_ledger_data=$this->Payments->Ledgers->find()->where(['voucher_id' => $payment->id, 'voucher_source' => 'Payment Voucher','ledger_account_id'=>$payment->bank_cash_id])->first();
				$this->Payments->Ledgers->deleteAll(['voucher_id' => $payment->id, 'voucher_source' => 'Payment Voucher']);
				$this->Payments->ReferenceDetails->deleteAll(['payment_id' => $payment->id]);
				$total_cr=0; $total_dr=0;
				foreach($payment->payment_rows as $payment_row){ 
					$ledger = $this->Payments->Ledgers->newEntity();
					$ledger->company_id=$st_company_id;
					$ledger->ledger_account_id = $payment_row->received_from_id;
					if($payment_row->cr_dr=="Cr"){
					$ledger->credit = $payment_row->amount;
					$ledger->debit = 0;
					$total_cr=$total_cr+$payment_row->amount;
					}else{
					$ledger->credit = 0;
					$ledger->debit = $payment_row->amount;
					$total_dr=$total_dr+$payment_row->amount;
					}
					$ledger->voucher_id = $payment->id;
					$ledger->voucher_source = 'Payment Voucher';
					$ledger->transaction_date = $payment->transaction_date;
					$this->Payments->Ledgers->save($ledger);
					if(!empty($payment_row->ref_rows))
					{
					foreach($payment_row->ref_rows as $ref_rows){ 
						$ReferenceDetail = $this->Payments->ReferenceDetails->newEntity();
						$ReferenceDetail->company_id=$st_company_id;
						$ReferenceDetail->reference_type=$ref_rows['ref_type'];
						$ReferenceDetail->reference_no=$ref_rows['ref_no'];
						$ReferenceDetail->ledger_account_id = $payment_row->received_from_id;
						if($ref_rows['ref_cr_dr']=="Dr"){
							$ReferenceDetail->debit = $ref_rows['ref_amount'];
							$ReferenceDetail->credit = 0;
						}else{
							$ReferenceDetail->credit = $ref_rows['ref_amount'];
							$ReferenceDetail->debit = 0;
						}
						$ReferenceDetail->payment_id = $payment->id;
						$ReferenceDetail->payment_row_id = $payment_row->id;
						$ReferenceDetail->transaction_date = $payment->transaction_date;
						$this->Payments->ReferenceDetails->save($ReferenceDetail);
					} 
					
						$ReferenceDetail = $this->Payments->ReferenceDetails->newEntity();
						$ReferenceDetail->company_id=$st_company_id;
						$ReferenceDetail->reference_type="On_account";
						$ReferenceDetail->ledger_account_id = $payment_row->received_from_id; 
						if($payment_row->on_acc_cr_dr=="Dr"){
							$ReferenceDetail->debit = $payment_row->on_acc;
							$ReferenceDetail->credit = 0;
						}else{
							$ReferenceDetail->credit = $payment_row->on_acc;
							$ReferenceDetail->debit = 0;
						}
						$ReferenceDetail->payment_id = $payment->id;
						$ReferenceDetail->payment_row_id = $payment_row->id;
						$ReferenceDetail->transaction_date = $payment->transaction_date;
						if($payment_row->on_acc > 0){ 
							$this->Payments->ReferenceDetails->save($ReferenceDetail);
						}
					}
						
					
				}
				$bankAmt=$total_dr-$total_cr;
				//pr($bankAmt); exit;

				//Ledger posting for bankcash
				$ledger = $this->Payments->Ledgers->newEntity();
				$ledger->company_id=$st_company_id;
				$ledger->ledger_account_id = $payment->bank_cash_id;
				if($bankAmt > 0){
					$ledger->credit = $bankAmt;
					$ledger->debit = 0;
				}else{
					$ledger->debit = abs($bankAmt);
					$ledger->credit = 0;
				}
				if($old_ledger_data){
					$ledger->reconciliation_date = $old_ledger_data->reconciliation_date;
				}else{
					$ledger->reconciliation_date = "0000-00-00";
				}
				$ledger->voucher_id = $payment->id;
				$ledger->voucher_source = 'Payment Voucher';
				$ledger->transaction_date = $payment->transaction_date;
				
				if($bankAmt != 0){
					$this->Payments->Ledgers->save($ledger);
				}
                $this->Flash->success(__('The receipt has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The receipt could not be saved. Please, try again.'));
            }
        }
		
		$vr=$this->Payments->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Payment Voucher','sub_entity'=>'Cash/Bank'])->first();
		$ReceiptVouchersCashBank=$vr->id;
		$vouchersReferences = $this->Payments->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		$BankCashes_selected='yes';
		if(sizeof($where)>0){
			$bankCashes = $this->Payments->BankCashes->find('list',
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
		
		$vr=$this->Payments->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Payment Voucher','sub_entity'=>'Paid To'])->first();
		$ReceiptVouchersReceivedFrom=$vr->id;
		$vouchersReferences = $this->Payments->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
		$where=[];
		foreach($vouchersReferences->voucher_ledger_accounts as $data){
			$where[]=$data->ledger_account_id;
		}
		/* $ReceivedFroms_selected='yes';
		if(sizeof($where)>0){
			$receivedFroms = $this->Payments->ReceivedFroms->find('list',
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
		} */
		
		$receivedFroms=[];
		
		$ReceivedFroms_selected='yes';
		if(sizeof($where)>0){
			$receivedDatas = $this->Payments->ReceivedFroms->find()->where(['ReceivedFroms.id IN' => $where]);
			foreach($receivedDatas as $receivedData){
				$receivedFroms[$receivedData->id]=['text'=>$receivedData->name,'value'=>$receivedData->id,'thelatype'=>$receivedData->grn_invoice];
			}
		}else{
			$ReceivedFroms_selected='no';
		}
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$grn=$this->Payments->Grns->find()->where(['company_id' => $st_company_id]);
		$invoice=$this->Payments->Invoices->find()->where(['company_id' => $st_company_id]);
		
		$this->set(compact('payment', 'bankCashes', 'receivedFroms', 'financial_year', 'BankCashes_selected', 'ReceivedFroms_selected', 'old_ref_rows','chkdate','grn','invoice','financial_month_first','financial_month_last'));
        $this->set('_serialize', ['payment']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Payment id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $payment = $this->Payments->get($id);
        if ($this->Payments->delete($payment)) {
            $this->Flash->success(__('The payment has been deleted.'));
        } else {
            $this->Flash->error(__('The payment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function fetchRefNumbers($received_from_id=null,$cr_dr=null){
		$this->viewBuilder()->layout('');
		$ReferenceBalances=$this->Payments->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id]);
		
		$this->set(compact('ReferenceBalances','cr_dr'));
	}
	
	public function fetchRefNumbersEdit($received_from_id=null,$cr_dr=null,$reference_no=null,$debit=null,$credit=null){
		$this->viewBuilder()->layout('');
		
		$received_from_id=$this->request->query['received_from_id'];
		$cr_dr=$this->request->query['cr_dr'];
		$reference_no=$this->request->query['reference_no'];
		$debit=$this->request->query['debit'];
		$credit=$this->request->query['credit'];
		$ReferenceBalances=$this->Payments->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id]);
		$this->set(compact('ReferenceBalances', 'reference_no', 'credit', 'debit', 'cr_dr','received_from_id'));
	}
	
	function checkRefNumberUnique($received_from_id,$i){
		$reference_no=$this->request->query['ref_rows'][$received_from_id][$i]['ref_no'];
		$ReferenceBalances=$this->Payments->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id,'reference_no'=>$reference_no]);
		if($ReferenceBalances->count()==0){
			echo 'true';
		}else{
			echo 'false';
		}
	exit;
	}
	
	
	
	function checkRefNumberUniqueEdit($received_from_id,$i,$is_old){
		$reference_no=$this->request->query['ref_rows'][$received_from_id][$i]['ref_no'];
		$ReferenceBalances=$this->Payments->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id,'reference_no'=>$reference_no]);
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
		$ReferenceDetails=$this->Payments->ReferenceDetails->find()->where(['ledger_account_id'=>$old_received_from_id,'receipt_id'=>$receipt_id]);
		foreach($ReferenceDetails as $ReferenceDetail){
			if($ReferenceDetail->reference_type=="New Reference" || $ReferenceDetail->reference_type=="Advance Reference"){
				$this->Payments->ReferenceBalances->deleteAll(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no]);
				
				$RDetail=$this->Payments->ReferenceDetails->get($ReferenceDetail->id);
				$this->Payments->ReferenceDetails->delete($RDetail);
			}elseif($ReferenceDetail->reference_type=="Against Reference"){
				if(!empty($ReferenceDetail->credit)){
					$ReferenceBalance=$this->Payments->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
					$ReferenceBalance=$this->Payments->ReferenceBalances->get($ReferenceBalance->id);
					$ReferenceBalance->credit=$ReferenceBalance->credit-$ReferenceDetail->credit;
					$this->Payments->ReferenceBalances->save($ReferenceBalance);
				}elseif(!empty($ReferenceDetail->debit)){
					$ReferenceBalance=$this->Payments->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
					$ReferenceBalance=$this->Payments->ReferenceBalances->get($ReferenceBalance->id);
					$ReferenceBalance->debit=$ReferenceBalance->debit-$ReferenceDetail->debit;
					$this->Payments->ReferenceBalances->save($ReferenceBalance);
				}
				$RDetail=$this->Payments->ReferenceDetails->get($ReferenceDetail->id);
				$this->Payments->ReferenceDetails->delete($RDetail);
			}
		}		exit;
	}
	
	function deleteOneRefNumbers(){
		$old_received_from_id=$this->request->query['old_received_from_id'];
		$payment_id=$this->request->query['payment_id'];
		$old_ref=$this->request->query['old_ref'];
		$old_ref_type=$this->request->query['old_ref_type'];
		
		if($old_ref_type=="New Reference" || $old_ref_type=="Advance Reference"){
			$this->Payments->ReferenceBalances->deleteAll(['ledger_account_id'=>$old_received_from_id,'reference_no'=>$old_ref]);
			$this->Payments->ReferenceDetails->deleteAll(['ledger_account_id'=>$old_received_from_id,'reference_no'=>$old_ref]);
		}elseif($old_ref_type=="Against Reference"){
			$ReferenceDetail=$this->Payments->ReferenceDetails->find()->where(['ledger_account_id'=>$old_received_from_id,'payment_id'=>$payment_id,'reference_no'=>$old_ref])->first();
			
			if(!empty($ReferenceDetail->credit)){
				$ReferenceBalance=$this->Payments->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
				$ReferenceBalance=$this->Payments->ReferenceBalances->get($ReferenceBalance->id);
				$ReferenceBalance->credit=$ReferenceBalance->credit-$ReferenceDetail->credit;
				$this->Payments->ReferenceBalances->save($ReferenceBalance);
			}elseif(!empty($ReferenceDetail->debit)){
				$ReferenceBalance=$this->Payments->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
				$ReferenceBalance=$this->Payments->ReferenceBalances->get($ReferenceBalance->id);
				$ReferenceBalance->debit=$ReferenceBalance->debit-$ReferenceDetail->debit;
				$this->Payments->ReferenceBalances->save($ReferenceBalance);
			}
			$RDetail=$this->Payments->ReferenceDetails->get($ReferenceDetail->id);
			$this->Payments->ReferenceDetails->delete($RDetail);
		}
		
		exit;
	}
}
