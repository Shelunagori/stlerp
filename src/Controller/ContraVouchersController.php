<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ContraVouchers Controller
 *
 * @property \App\Model\Table\ContraVouchersTable $contraVoucher
 */
class ContraVouchersController extends AppController
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
		$financial_year = $this->ContraVouchers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->ContraVouchers->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->ContraVouchers->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		
		$where = [];
		$vouch_no = $this->request->query('vouch_no');
		$From = $this->request->query('From');
		$To = $this->request->query('To');
		
		$this->set(compact('vouch_no','From','To'));
		
		if(!empty($vouch_no)){
			$where['ContraVouchers.voucher_no LIKE']=$vouch_no;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['ContraVouchers.transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['ContraVouchers.transaction_date <=']=$To;
		}
		
		
        $this->paginate = [
            'contain' => []
        ];
        
        
        $contravouchers = $this->paginate($this->ContraVouchers->find()->where($where)->where(['company_id'=>$st_company_id])->contain(['ContraVoucherRows'=>function($q){
            $ContraVoucherRows = $this->ContraVouchers->ContraVoucherRows->find();
            $totalCrCase = $ContraVoucherRows->newExpr()
                ->addCase(
                    $ContraVoucherRows->newExpr()->add(['cr_dr' => 'Cr']),
                    $ContraVoucherRows->newExpr()->add(['amount']),
                    'integer'
                );
            $totalDrCase = $ContraVoucherRows->newExpr()
                ->addCase(
                    $ContraVoucherRows->newExpr()->add(['cr_dr' => 'Dr']),
                    $ContraVoucherRows->newExpr()->add(['amount']),
                    'integer'
                );
            return $ContraVoucherRows->select([
                    'total_cr' => $ContraVoucherRows->func()->sum($totalCrCase),
                    'total_dr' => $ContraVoucherRows->func()->sum($totalDrCase)
                ])
                ->group('contra_voucher_id')
                
                ->autoFields(true);
            
        }])->order(['voucher_no'=>'DESC']));
        

        $this->set(compact('contravouchers','url','financial_month_first','financial_month_last'));
        $this->set('_serialize', ['contravouchers']);
    }
	
	public function exportExcel(){
		$this->viewBuilder()->layout('');
        
        $session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
        
        $where = [];
		$vouch_no = $this->request->query('vouch_no');
		$From = $this->request->query('From');
		$To = $this->request->query('To');
		
		$this->set(compact('vouch_no','From','To'));
		
		if(!empty($vouch_no)){
			$where['ContraVouchers.voucher_no LIKE']=$vouch_no;
		}
		
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['ContraVouchers.transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['ContraVouchers.transaction_date <=']=$To;
		}
        
        $contravouchers = $this->ContraVouchers->find()->where($where)->where(['company_id'=>$st_company_id])->contain(['ContraVoucherRows'=>function($q){
            $ContraVoucherRows = $this->ContraVouchers->ContraVoucherRows->find();
            $totalCrCase = $ContraVoucherRows->newExpr()
                ->addCase(
                    $ContraVoucherRows->newExpr()->add(['cr_dr' => 'Cr']),
                    $ContraVoucherRows->newExpr()->add(['amount']),
                    'integer'
                );
            $totalDrCase = $ContraVoucherRows->newExpr()
                ->addCase(
                    $ContraVoucherRows->newExpr()->add(['cr_dr' => 'Dr']),
                    $ContraVoucherRows->newExpr()->add(['amount']),
                    'integer'
                );
            return $ContraVoucherRows->select([
                    'total_cr' => $ContraVoucherRows->func()->sum($totalCrCase),
                    'total_dr' => $ContraVoucherRows->func()->sum($totalDrCase)
                ])
                ->group('contra_voucher_id')
                
                ->autoFields(true);
            
        }])->order(['voucher_no'=>'DESC']);
        

        $this->set(compact('contravouchers','url'));
        $this->set('_serialize', ['contravouchers']);
	}

    /**
     * View method
     *
     * @param string|null $id Contra Voucher id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->viewBuilder()->layout('index_layout');
        $contravoucher = $this->ContraVouchers->get($id, [
            'contain' => ['BankCashes', 'Companies', 'ContraVoucherRows' => ['ReferenceDetails','ReceivedFroms'], 'Creator']
        ]);
		//pr($contravoucher);exit;
		$ref_bal=[];
		foreach($contravoucher->contra_voucher_rows as $contra_voucher_row){
			$ReferenceBalancess=$this->ContraVouchers->ReferenceDetails->find()->where(['ledger_account_id'=>$contra_voucher_row->received_from_id,'contra_voucher_id'=>$contra_voucher_row->id]);
			$ref_bal[$contra_voucher_row->received_from_id]=$ReferenceBalancess->toArray();
		}
		//pr($ref_bal);exit;
										
		$this->set(compact('ref_bal'));
        $this->set('contravoucher', $contravoucher);
        $this->set('_serialize', ['contravoucher']);
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
		$financial_year = $this->ContraVouchers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->ContraVouchers->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->ContraVouchers->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();

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
				 {   $chkdate = 'Found'; }
				 else
				 {   $chkdate = 'Not Found'; }

			   }
			   else
				{
					 $chkdate = 'Not Found';	
				}
       
        $contravoucher = $this->ContraVouchers->newEntity();
        
        if ($this->request->is('post')) {
            $contravoucher = $this->ContraVouchers->patchEntity($contravoucher, $this->request->data);
            

			
			$contravoucher->company_id=$st_company_id;
            //Voucher Number Increment
            $last_voucher_no=$this->ContraVouchers->find()->select(['voucher_no'])->where(['company_id' => $st_company_id])->order(['voucher_no' => 'DESC'])->first();
            if($last_voucher_no){
                $contravoucher->voucher_no=$last_voucher_no->voucher_no+1;
            }else{
                $contravoucher->voucher_no=1;
            }
            
            $contravoucher->created_on=date("Y-m-d");
            $contravoucher->created_by=$s_employee_id;
            $contravoucher->transaction_date=date("Y-m-d",strtotime($contravoucher->transaction_date));
            
            if ($this->ContraVouchers->save($contravoucher)) {
                $total_cr=0; $total_dr=0;
               
                foreach($contravoucher->contra_voucher_rows as $contra_voucher_row){
                    
 					$ledger = $this->ContraVouchers->Ledgers->newEntity();
					$ledger->company_id=$st_company_id;
					$ledger->ledger_account_id = $contra_voucher_row->received_from_id;
					if($contra_voucher_row->cr_dr=="Cr"){
					$ledger->credit = $contra_voucher_row->amount;
					$ledger->debit = 0;
					$total_cr=$total_cr+$contra_voucher_row->amount;
					}else{
					$ledger->credit = 0;
					$ledger->debit = $contra_voucher_row->amount;
					$total_dr=$total_dr+$contra_voucher_row->amount;
					}
					$ledger->voucher_id = $contravoucher->id;
					$ledger->voucher_source = 'Contra Voucher';
					$ledger->transaction_date = $contravoucher->transaction_date;
					$this->ContraVouchers->Ledgers->save($ledger);
					if(!empty($contra_voucher_row->ref_rows))
					{
					foreach($contra_voucher_row->ref_rows as $ref_rows){
						$ReferenceDetail = $this->ContraVouchers->ReferenceDetails->newEntity();
						$ReferenceDetail->company_id=$st_company_id;
						$ReferenceDetail->reference_type=$ref_rows['ref_type'];
						$ReferenceDetail->reference_no=$ref_rows['ref_no'];
						$ReferenceDetail->ledger_account_id = $contra_voucher_row->received_from_id;
						if($ref_rows['ref_cr_dr']=="Dr"){
							$ReferenceDetail->debit = $ref_rows['ref_amount'];
							$ReferenceDetail->credit = 0;
						}else{
							$ReferenceDetail->credit = $ref_rows['ref_amount'];
							$ReferenceDetail->debit = 0;
						}
						$ReferenceDetail->contra_voucher_id = $contravoucher->id;
						$ReferenceDetail->contra_voucher_row_id = $contra_voucher_row->id;
						$ReferenceDetail->transaction_date = $contravoucher->transaction_date;
						$this->ContraVouchers->ReferenceDetails->save($ReferenceDetail);
					} 
					
						$ReferenceDetail = $this->ContraVouchers->ReferenceDetails->newEntity();
						$ReferenceDetail->company_id=$st_company_id;
						$ReferenceDetail->reference_type="On_account";
						$ReferenceDetail->ledger_account_id = $contra_voucher_row->received_from_id;
						if($contra_voucher_row->on_acc_dr_cr=="Dr"){
							$ReferenceDetail->debit = $contra_voucher_row->on_acc;
							$ReferenceDetail->credit = 0;
						}else{
							$ReferenceDetail->credit = $contra_voucher_row->on_acc;
							$ReferenceDetail->debit = 0;
						}
						$ReferenceDetail->contra_voucher_id = $contravoucher->id;
						$ReferenceDetail->contra_voucher_row_id = $contra_voucher_row->id;
						$ReferenceDetail->transaction_date = $contravoucher->transaction_date;
						if($contra_voucher_row->on_acc > 0){ 
							$this->ContraVouchers->ReferenceDetails->save($ReferenceDetail);
						} 
					}
                }
                
                //Ledger posting for bankcash
					$bankAmt=$total_dr-$total_cr;
					//pr($bankAmt); exit;
					//Ledger posting for bankcash
					$ledger = $this->ContraVouchers->Ledgers->newEntity();
					$ledger->company_id=$st_company_id;
					$ledger->ledger_account_id = $contravoucher->bank_cash_id;
					if($bankAmt > 0){
						$ledger->credit = $bankAmt;
						$ledger->debit = 0;
					}else{
						$ledger->debit = abs($bankAmt);
						$ledger->credit = 0;
					}
					$ledger->voucher_id = $contravoucher->id;
					$ledger->voucher_source = 'Contra Voucher';
					$ledger->transaction_date = $contravoucher->transaction_date;
					if($bankAmt != 0){
						$this->ContraVouchers->Ledgers->save($ledger);
					}      
                $this->Flash->success(__('The contra voucher has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The contra voucher could not be saved. Please, try again.'));
            }
        }
        $vr=$this->ContraVouchers->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Contra Voucher','sub_entity'=>'Cash/Bank'])->first();
        $ReceiptVouchersCashBank=$vr->id;
        $vouchersReferences = $this->ContraVouchers->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
        $where=[];
        foreach($vouchersReferences->voucher_ledger_accounts as $data){
            $where[]=$data->ledger_account_id;
        }
        $BankCashes_selected='yes';
        if(sizeof($where)>0){
            $bankCashes = $this->ContraVouchers->BankCashes->find('list',
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
        //pr($bankCashes->toArray())    ; exit;
        
        $vr=$this->ContraVouchers->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Contra Voucher','sub_entity'=>'Paid To'])->first();
        $ReceiptVouchersReceivedFrom=$vr->id;
        $vouchersReferences = $this->ContraVouchers->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
        $where=[];
        foreach($vouchersReferences->voucher_ledger_accounts as $data){
            $where[]=$data->ledger_account_id;
        }
        $ReceivedFroms_selected='yes';
        if(sizeof($where)>0){
            $receivedFroms = $this->ContraVouchers->ReceivedFroms->find('list',
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
        $this->set(compact('contravoucher', 'bankCashes', 'receivedFroms', 'financial_year', 'BankCashes_selected', 'ReceivedFroms_selected','chkdate','financial_month_first','financial_month_last'));
        $this->set('_serialize', ['contravoucher']);

    }

    /**
     * Edit method
     *
     * @param string|null $id Contra Voucher id.
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
		$financial_year = $this->ContraVouchers->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->ContraVouchers->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->ContraVouchers->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		
        
        $contravoucher = $this->ContraVouchers->get($id, [
            'contain' => ['ContraVoucherRows'=>['ReferenceDetails','ReceivedFroms']]
        ]);
		//pr($contravoucher); exit;
        $old_ref_rows=[];
        $old_received_from_ids=[];
        $old_reference_numbers=[];

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

        if ($this->request->is(['patch', 'post', 'put'])) {
            $contravoucher = $this->ContraVouchers->patchEntity($contravoucher, $this->request->data);
            $contravoucher->company_id=$st_company_id;
            $contravoucher->edited_on=date("Y-m-d");
            $contravoucher->edited_by=$s_employee_id;
            $contravoucher->transaction_date=date("Y-m-d",strtotime($contravoucher->transaction_date));
          
			//Save receipt
            if ($this->ContraVouchers->save($contravoucher)) {
                $this->ContraVouchers->Ledgers->deleteAll(['voucher_id' => $contravoucher->id, 'voucher_source' => 'Contra Voucher']);
                $this->ContraVouchers->ReferenceDetails->deleteAll(['contra_voucher_id' => $contravoucher->id]);
                $total_cr=0; $total_dr=0;
                foreach($contravoucher->contra_voucher_rows as $contra_voucher_row){
 					$ledger = $this->ContraVouchers->Ledgers->newEntity();
					$ledger->company_id=$st_company_id;
					$ledger->ledger_account_id = $contra_voucher_row->received_from_id;
					if($contra_voucher_row->cr_dr=="Cr"){
					$ledger->credit = $contra_voucher_row->amount;
					$ledger->debit = 0;
					$total_cr=$total_cr+$contra_voucher_row->amount;
					}else{
					$ledger->credit = 0;
					$ledger->debit = $contra_voucher_row->amount;
					$total_dr=$total_dr+$contra_voucher_row->amount;
					}
					$ledger->voucher_id = $contravoucher->id;
					$ledger->voucher_source = 'Contra Voucher';
					$ledger->transaction_date = $contravoucher->transaction_date;
					$this->ContraVouchers->Ledgers->save($ledger);
					if(!empty($contra_voucher_row->ref_rows))
					{
					foreach($contra_voucher_row->ref_rows as $ref_rows){  // pr($ref_rows); 
						$ReferenceDetail = $this->ContraVouchers->ReferenceDetails->newEntity();
						$ReferenceDetail->company_id=$st_company_id;
						$ReferenceDetail->reference_type=$ref_rows['ref_type'];
						$ReferenceDetail->reference_no=$ref_rows['ref_no'];
						$ReferenceDetail->ledger_account_id = $contra_voucher_row->received_from_id;
						if($ref_rows['ref_cr_dr']=="Dr"){
							$ReferenceDetail->debit = $ref_rows['ref_amount'];
							$ReferenceDetail->credit = 0;
						}else{
							$ReferenceDetail->credit = $ref_rows['ref_amount'];
							$ReferenceDetail->debit = 0;
						}
						$ReferenceDetail->contra_voucher_id = $contravoucher->id;
						$ReferenceDetail->contra_voucher_row_id = $contra_voucher_row->id;
						$ReferenceDetail->transaction_date = $contravoucher->transaction_date;
						$this->ContraVouchers->ReferenceDetails->save($ReferenceDetail);
					} 
					
						$ReferenceDetail = $this->ContraVouchers->ReferenceDetails->newEntity();
						$ReferenceDetail->company_id=$st_company_id;
						$ReferenceDetail->reference_type="On_account";
						$ReferenceDetail->ledger_account_id = $contra_voucher_row->received_from_id;
						if($contra_voucher_row->on_acc_dr_cr=="Dr"){
							$ReferenceDetail->debit = $contra_voucher_row->on_acc;
							$ReferenceDetail->credit = 0;
						}else{
							$ReferenceDetail->credit = $contra_voucher_row->on_acc;
							$ReferenceDetail->debit = 0;
						}
						$ReferenceDetail->contra_voucher_id = $contravoucher->id;
						$ReferenceDetail->contra_voucher_row_id = $contra_voucher_row->id;
						$ReferenceDetail->transaction_date = $contravoucher->transaction_date;
						if($contra_voucher_row->on_acc > 0){ 
							$this->ContraVouchers->ReferenceDetails->save($ReferenceDetail);
						} 
					}
                }
                
                //Ledger posting for bankcash
					$bankAmt=$total_dr-$total_cr;
					//pr($bankAmt); exit;
					//Ledger posting for bankcash
					$ledger = $this->ContraVouchers->Ledgers->newEntity();
					$ledger->company_id=$st_company_id;
					$ledger->ledger_account_id = $contravoucher->bank_cash_id;
					if($bankAmt > 0){
						$ledger->credit = $bankAmt;
						$ledger->debit = 0;
					}else{
						$ledger->debit = abs($bankAmt);
						$ledger->credit = 0;
					}
					$ledger->voucher_id = $contravoucher->id;
					$ledger->voucher_source = 'Contra Voucher';
					$ledger->transaction_date = $contravoucher->transaction_date;
					if($bankAmt != 0){
						$this->ContraVouchers->Ledgers->save($ledger);
					} 
					
                $this->Flash->success(__('The receipt has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The receipt could not be saved. Please, try again.'));
            }
        }
        
        $vr=$this->ContraVouchers->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Contra Voucher','sub_entity'=>'Cash/Bank'])->first();
        $ReceiptVouchersCashBank=$vr->id;
        $vouchersReferences = $this->ContraVouchers->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
        $where=[];
        foreach($vouchersReferences->voucher_ledger_accounts as $data){
            $where[]=$data->ledger_account_id;
        }
        $BankCashes_selected='yes';
        if(sizeof($where)>0){
            $bankCashes = $this->ContraVouchers->BankCashes->find('list',
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
        
        $vr=$this->ContraVouchers->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Contra Voucher','sub_entity'=>'Paid To'])->first();
        $ReceiptVouchersReceivedFrom=$vr->id;
        $vouchersReferences = $this->ContraVouchers->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
        $where=[];
        foreach($vouchersReferences->voucher_ledger_accounts as $data){
            $where[]=$data->ledger_account_id;
        }
        $ReceivedFroms_selected='yes';
        if(sizeof($where)>0){
            $receivedFroms = $this->ContraVouchers->ReceivedFroms->find('list',
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
        
        $this->set(compact('contravoucher', 'bankCashes', 'receivedFroms', 'financial_year', 'BankCashes_selected', 'ReceivedFroms_selected', 'old_ref_rows','chkdate','financial_month_first','financial_month_last'));
        $this->set('_serialize', ['contravoucher']);

    }

    /**
     * Delete method
     *
     * @param string|null $id Contra Voucher id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */


    public function fetchRefNumbers($received_from_id=null,$cr_dr=null){
        $this->viewBuilder()->layout('');
        $ReferenceBalances=$this->ContraVouchers->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id]);
        $this->set(compact('ReferenceBalances','cr_dr'));
    }
    
   public function fetchRefNumbersEdit($received_from_id=null,$cr_dr=null,$reference_no=null,$debit=null,$credit=null){
        $this->viewBuilder()->layout('');
		$received_from_id=$this->request->query['received_from_id'];
		$cr_dr=$this->request->query['cr_dr'];
		$reference_no=$this->request->query['reference_no'];
		$debit=$this->request->query['debit'];
		$credit=$this->request->query['credit'];
        $ReferenceBalances=$this->ContraVouchers->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id]);
        $this->set(compact('ReferenceBalances', 'reference_no', 'credit', 'debit', 'cr_dr'));
    }
    
    function checkRefNumberUnique($received_from_id,$i){
        $reference_no=$this->request->query['ref_rows'][$received_from_id][$i]['ref_no'];
        $ReferenceBalances=$this->ContraVouchers->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id,'reference_no'=>$reference_no]);
        if($ReferenceBalances->count()==0){
            echo 'true';
        }else{
            echo 'false';
        }
        exit;
    }
    
    function checkRefNumberUniqueEdit($received_from_id,$i,$is_old){
        $reference_no=$this->request->query['ref_rows'][$received_from_id][$i]['ref_no'];
        $ReferenceBalances=$this->ContraVouchers->ReferenceBalances->find()->where(['ledger_account_id'=>$received_from_id,'reference_no'=>$reference_no]);
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
        $ReferenceDetails=$this->ContraVouchers->ReferenceDetails->find()->where(['ledger_account_id'=>$old_received_from_id,'receipt_id'=>$receipt_id]);
        foreach($ReferenceDetails as $ReferenceDetail){
            if($ReferenceDetail->reference_type=="New Reference" || $ReferenceDetail->reference_type=="Advance Reference"){
                $this->ContraVouchers->ReferenceBalances->deleteAll(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no]);
                
                $RDetail=$this->ContraVouchers->ReferenceDetails->get($ReferenceDetail->id);
                $this->ContraVouchers->ReferenceDetails->delete($RDetail);
            }elseif($ReferenceDetail->reference_type=="Against Reference"){
                if(!empty($ReferenceDetail->credit)){
                    $ReferenceBalance=$this->ContraVouchers->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
                    $ReferenceBalance=$this->ContraVouchers->ReferenceBalances->get($ReferenceBalance->id);
                    $ReferenceBalance->credit=$ReferenceBalance->credit-$ReferenceDetail->credit;
                    $this->ContraVouchers->ReferenceBalances->save($ReferenceBalance);
                }elseif(!empty($ReferenceDetail->debit)){
                    $ReferenceBalance=$this->ContraVouchers->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
                    $ReferenceBalance=$this->ContraVouchers->ReferenceBalances->get($ReferenceBalance->id);
                    $ReferenceBalance->debit=$ReferenceBalance->debit-$ReferenceDetail->debit;
                    $this->ContraVouchers->ReferenceBalances->save($ReferenceBalance);
                }
                $RDetail=$this->ContraVouchers->ReferenceDetails->get($ReferenceDetail->id);
                $this->ContraVouchers->ReferenceDetails->delete($RDetail);
            }
        }       exit;
    }
    
    function deleteOneRefNumbers(){
        $old_received_from_id=$this->request->query['old_received_from_id'];
        $payment_id=$this->request->query['payment_id'];
        $old_ref=$this->request->query['old_ref'];
        $old_ref_type=$this->request->query['old_ref_type'];
        
        if($old_ref_type=="New Reference" || $old_ref_type=="Advance Reference"){
            $this->ContraVouchers->ReferenceBalances->deleteAll(['ledger_account_id'=>$old_received_from_id,'reference_no'=>$old_ref]);
            $this->ContraVouchers->ReferenceDetails->deleteAll(['ledger_account_id'=>$old_received_from_id,'reference_no'=>$old_ref]);
        }elseif($old_ref_type=="Against Reference"){
            $ReferenceDetail=$this->ContraVouchers->ReferenceDetails->find()->where(['ledger_account_id'=>$old_received_from_id,'payment_id'=>$payment_id,'reference_no'=>$old_ref])->first();
            
            if(!empty($ReferenceDetail->credit)){
                $ReferenceBalance=$this->ContraVouchers->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
                $ReferenceBalance=$this->ContraVouchers->ReferenceBalances->get($ReferenceBalance->id);
                $ReferenceBalance->credit=$ReferenceBalance->credit-$ReferenceDetail->credit;
                $this->ContraVouchers->ReferenceBalances->save($ReferenceBalance);
            }elseif(!empty($ReferenceDetail->debit)){
                $ReferenceBalance=$this->ContraVouchers->ReferenceBalances->find()->where(['ledger_account_id' => $ReferenceDetail->ledger_account_id, 'reference_no' => $ReferenceDetail->reference_no])->first();
                $ReferenceBalance=$this->ContraVouchers->ReferenceBalances->get($ReferenceBalance->id);
                $ReferenceBalance->debit=$ReferenceBalance->debit-$ReferenceDetail->debit;
                $this->ContraVouchers->ReferenceBalances->save($ReferenceBalance);
            }
            $RDetail=$this->ContraVouchers->ReferenceDetails->get($ReferenceDetail->id);
            $this->ContraVouchers->ReferenceDetails->delete($RDetail);
        }
        
        exit;
    }




    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $contraVoucher = $this->ContraVouchers->get($id);
        if ($this->ContraVouchers->delete($contraVoucher)) {
            $this->Flash->success(__('The Contra Vouchers has been deleted.'));
        } else {
            $this->Flash->error(__('The Contra Vouchers could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
?>