<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReferenceDetails Controller
 *
 * @property \App\Model\Table\ReferenceDetailsTable $ReferenceDetails
 */
class ReferenceDetailsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['LedgerAccounts', 'ReceiptVouchers', 'PaymentVouchers', 'Invoices', 'InvoiceBookings', 'CreditNotes']
        ];
        $referenceDetails = $this->paginate($this->ReferenceDetails);

        $this->set(compact('referenceDetails'));
        $this->set('_serialize', ['referenceDetails']);
    }

    /**
     * View method
     *
     * @param string|null $id Reference Detail id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $referenceDetail = $this->ReferenceDetails->get($id, [
            'contain' => ['LedgerAccounts', 'ReceiptVouchers', 'PaymentVouchers', 'Invoices', 'InvoiceBookings', 'CreditNotes']
        ]);

        $this->set('referenceDetail', $referenceDetail);
        $this->set('_serialize', ['referenceDetail']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $referenceDetail = $this->ReferenceDetails->newEntity();
        if ($this->request->is('post')) {
            $referenceDetail = $this->ReferenceDetails->patchEntity($referenceDetail, $this->request->data);
            if ($this->ReferenceDetails->save($referenceDetail)) {
                $this->Flash->success(__('The reference detail has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The reference detail could not be saved. Please, try again.'));
            }
        }
        $ledgerAccounts = $this->ReferenceDetails->LedgerAccounts->find('list', ['limit' => 200]);
        $receiptVouchers = $this->ReferenceDetails->ReceiptVouchers->find('list', ['limit' => 200]);
        $paymentVouchers = $this->ReferenceDetails->PaymentVouchers->find('list', ['limit' => 200]);
        $invoices = $this->ReferenceDetails->Invoices->find('list', ['limit' => 200]);
        $invoiceBookings = $this->ReferenceDetails->InvoiceBookings->find('list', ['limit' => 200]);
        $creditNotes = $this->ReferenceDetails->CreditNotes->find('list', ['limit' => 200]);
        $this->set(compact('referenceDetail', 'ledgerAccounts', 'receiptVouchers', 'paymentVouchers', 'invoices', 'invoiceBookings', 'creditNotes'));
        $this->set('_serialize', ['referenceDetail']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Reference Detail id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $referenceDetail = $this->ReferenceDetails->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $referenceDetail = $this->ReferenceDetails->patchEntity($referenceDetail, $this->request->data);
            if ($this->ReferenceDetails->save($referenceDetail)) {
                $this->Flash->success(__('The reference detail has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The reference detail could not be saved. Please, try again.'));
            }
        }
        $ledgerAccounts = $this->ReferenceDetails->LedgerAccounts->find('list', ['limit' => 200]);
        $receiptVouchers = $this->ReferenceDetails->ReceiptVouchers->find('list', ['limit' => 200]);
        $paymentVouchers = $this->ReferenceDetails->PaymentVouchers->find('list', ['limit' => 200]);
        $invoices = $this->ReferenceDetails->Invoices->find('list', ['limit' => 200]);
        $invoiceBookings = $this->ReferenceDetails->InvoiceBookings->find('list', ['limit' => 200]);
        $creditNotes = $this->ReferenceDetails->CreditNotes->find('list', ['limit' => 200]);
        $this->set(compact('referenceDetail', 'ledgerAccounts', 'receiptVouchers', 'paymentVouchers', 'invoices', 'invoiceBookings', 'creditNotes'));
        $this->set('_serialize', ['referenceDetail']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Reference Detail id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $referenceDetail = $this->ReferenceDetails->get($id);
        if ($this->ReferenceDetails->delete($referenceDetail)) {
            $this->Flash->success(__('The reference detail has been deleted.'));
        } else {
            $this->Flash->error(__('The reference detail could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function listRef($ledger_id=null)
    {
		$this->viewBuilder()->layout('');
        $query = $this->ReferenceDetails->find();
		$query->select(['total_debit' => $query->func()->sum('ReferenceDetails.debit'),'total_credit' => $query->func()->sum('ReferenceDetails.credit')])
		->where(['ReferenceDetails.ledger_account_id'=>$ledger_id,'ReferenceDetails.reference_type !='=>'On_account'])
		->group(['ReferenceDetails.reference_no'])
		->autoFields(true);
		$referenceDetails=$query;
		$option=[];
		foreach($referenceDetails as $referenceDetail){
			$remider=$referenceDetail->total_debit-$referenceDetail->total_credit;
			if($remider>0){
				$bal=abs($remider).' Dr';
			}else if($remider<0){
				$bal=abs($remider).' Cr';
			}
			$bal= round($bal,2);
			if($referenceDetail->total_debit!=$referenceDetail->total_credit){
				$option[]=['text' =>$referenceDetail->reference_no.' ('.$bal.')', 'value' => $referenceDetail->reference_no, 'amt' => abs($remider)];
			}
		}
		//pr($option); exit;
		
        $this->set(compact('option'));
        $this->set('_serialize', ['referenceDetails']);
    }
	
	public function listRefEdit()
    {
		$ledger_id=$this->request->query('ledger_account_id');
		$ref_name=$this->request->query('ref_name');
		
		$this->viewBuilder()->layout('');
        $query = $this->ReferenceDetails->find();
		$query->select(['total_debit' => $query->func()->sum('ReferenceDetails.debit'),'total_credit' => $query->func()->sum('ReferenceDetails.credit')])
		->where(['ReferenceDetails.ledger_account_id'=>$ledger_id,'ReferenceDetails.reference_type !='=>'On_account'])
		->group(['ReferenceDetails.reference_no'])
		->autoFields(true);
		$referenceDetails=$query;
		$option=[];
		foreach($referenceDetails as $referenceDetail){
			$remider=$referenceDetail->total_debit-$referenceDetail->total_credit;
			if($remider>0){
				$bal=abs($remider).' Dr';
			}else if($remider<0){
				$bal=abs($remider).' Cr';
			}
			else{
				$bal=0;
			}
			$bal= round($bal,2);
			if($referenceDetail->total_debit!=$referenceDetail->total_credit OR $referenceDetail->reference_no==$ref_name)
			{
				$option[]=['text' =>$referenceDetail->reference_no.' ('.$bal.')', 'value' => $referenceDetail->reference_no, 'amt' => abs($remider)];
			 } 
		}
		//pr($option); exit;
		
        $this->set(compact('option', 'ref_name'));
        $this->set('_serialize', ['referenceDetails']);
    }
	
	
}
