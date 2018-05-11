<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
/**
 * TravelRequests Controller
 *
 * @property \App\Model\Table\TravelRequestsTable $TravelRequests
 */
class TravelRequestsController extends AppController
{
	public function beforeFilter(Event $event) {
		 $this->eventManager()->off($this->Csrf);
	}

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
		$st_year_id = $session->read('st_year_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$empData=$this->TravelRequests->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
		
		if($empData->department->name=='HR & Administration' || $empData->designation->name=='Director'){ 
		$travelRequests = $this->paginate($this->TravelRequests->find()->contain(['Employees']));
		}else{ 
		$travelRequests = $this->paginate($this->TravelRequests->find()->contain(['Employees'])->where(['employee_id'=>$s_employee_id]));
		}
		
       // $travelRequests = $this->paginate($this->TravelRequests->find()->contain(['Employees'])->where(['employee_id'=>$s_employee_id]));

        $this->set(compact('travelRequests'));
        $this->set('_serialize', ['travelRequests']);
    }
	
	public function pending()
    {
		$this->viewBuilder()->layout('');	
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
        $travelRequests = $this->TravelRequests->find()->contain(['Employees'])->where(['employee_id'=>$s_employee_id,'TravelRequests.status'=>'pending']);

        $this->set(compact('travelRequests'));
        $this->set('_serialize', ['travelRequests']);
    }
	
	public function approve($id)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
        $travelRequest = $this->TravelRequests->get($id, [
            'contain' => ['TravelRequestRows','Employees']
        ]);

		if ($this->request->is('post')) {
			$bank_id=$this->request->data()['bank_id'];
			$trans_date=date('Y-m-d',strtotime($this->request->data()['trans_date']));
			$travelRequest->status="approve";
			$this->TravelRequests->save($travelRequest);
			
			if($travelRequest->advance_amt>0){
				$nppayment = $this->TravelRequests->Nppayments->newEntity();
				$nppayment->financial_year_id=$st_year_id;
				$last_voucher_no=$this->TravelRequests->Nppayments->find()->select(['voucher_no'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['voucher_no' => 'DESC'])->first();
				if($last_voucher_no){
					$nppayment->voucher_no=$last_voucher_no->voucher_no+1;
				}else{
					$nppayment->voucher_no=1;
				}
				$nppayment->bank_cash_id=$bank_id;
				$nppayment->created_on=date("Y-m-d");
				$nppayment->created_by=$s_employee_id;
				$nppayment->payment_mode='NEFT/RTGS';
				$nppayment->company_id=$st_company_id;
				$nppayment->transaction_date=$trans_date;
				$nppayment->cheque_no='';
				$nppayment->travel_request_id=$travelRequest->id;
				$this->TravelRequests->Nppayments->save($nppayment);
			
				$ledger_account=$this->TravelRequests->LedgerAccounts->find()->where(['source_model'=>'Employees','source_id'=>$travelRequest->employee_id,'company_id'=>$st_company_id])->first();
				
				$NppaymentRow = $this->TravelRequests->Nppayments->NppaymentRows->newEntity();
				$NppaymentRow->nppayment_id=$nppayment->id;
				$NppaymentRow->received_from_id=$ledger_account->id;
				$NppaymentRow->amount=$travelRequest->advance_amt;
				$NppaymentRow->cr_dr='Dr';
				$NppaymentRow->narration='Advance Payment for Travel Request';
				$this->TravelRequests->Nppayments->NppaymentRows->save($NppaymentRow);
				
				$ledger = $this->TravelRequests->Nppayments->Ledgers->newEntity();
				$ledger->company_id=$st_company_id;
				$ledger->ledger_account_id = $bank_id;
				$ledger->credit = $travelRequest->advance_amt;
				$ledger->debit = 0;
				$ledger->voucher_id = $nppayment->id;
				$ledger->voucher_source = 'Non Print Payment Voucher';
				$ledger->transaction_date = $trans_date;
				$this->TravelRequests->Nppayments->Ledgers->save($ledger);
				
				$ledger = $this->TravelRequests->Nppayments->Ledgers->newEntity();
				$ledger->company_id=$st_company_id;
				$ledger->ledger_account_id = $ledger_account->id;
				$ledger->credit = 0;
				$ledger->debit = $travelRequest->advance_amt;
				$ledger->voucher_id = $nppayment->id;
				$ledger->voucher_source = 'Non Print Payment Voucher';
				$ledger->transaction_date = $trans_date;
				$this->TravelRequests->Nppayments->Ledgers->save($ledger);
			}
			return $this->redirect(['controller' =>'Logins' ,'action' => 'dashbord']);
		}
		
		$vr=$this->TravelRequests->Nppayments->VouchersReferences->find()->where(['company_id'=>$st_company_id,'module'=>'Non Print Payment Voucher','sub_entity'=>'Cash/Bank'])->first();
        $vouchersReferences = $this->TravelRequests->Nppayments->VouchersReferences->get($vr->id, [
            'contain' => ['VoucherLedgerAccounts']
        ]);
        $where=[];
        foreach($vouchersReferences->voucher_ledger_accounts as $data){
            $where[]=$data->ledger_account_id;
        }
        if(sizeof($where)>0){
            $bankCashes = $this->TravelRequests->Nppayments->BankCashes->find('list',
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
        }
		$this->set(compact('bankCashes'));
        $this->set('travelRequest', $travelRequest);
        $this->set('_serialize', ['travelRequest']);
	}

    /**
     * View method
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $travelRequest = $this->TravelRequests->get($id, [
            'contain' => ['TravelRequestRows','Employees']
        ]);

        $this->set('travelRequest', $travelRequest);
        $this->set('_serialize', ['travelRequest']);
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
		 $st_year_id = $session->read('st_year_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		$empData=$this->TravelRequests->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
		//pr($empData);
        $travelRequest = $this->TravelRequests->newEntity();
        if ($this->request->is('post')) {
            $travelRequest = $this->TravelRequests->patchEntity($travelRequest, $this->request->data);
			
			//$travelRequest->employee_id=$s_employee_id;
			
			$travelRequest->travel_from_date=date('Y-m-d',strtotime($travelRequest->travel_mode_from_date));
			$travelRequest->travel_to_date=date('Y-m-d',strtotime($travelRequest->travel_mode_to_date));
			
			$dates=$this->date_range($travelRequest->travel_from_date, $travelRequest->travel_to_date, $step = '+1 day', $output_format = 'Y-m-d' );
			foreach($dates  as $date){
				$c=$this->TravelRequests->find()->where(['travel_mode_from_date <='=>$date])->andWhere(['travel_mode_to_date >='=>$date])->where(['employee_id'=>$travelRequest->employee_id])->count();
				if($c>0){
					$this->Flash->error(__('Travel request cannot be fullfilled with duplicate dates.'));
					goto a;
				}
			}
			foreach($dates  as $date){
				$c=$this->TravelRequests->LeaveApplications->find()->where(['approve_leave_from <='=>$date])->andWhere([])->where(['employee_id'=>$travelRequest->employee_id])->count();
				if($c>0){
					$this->Flash->error(__('Leave application apllied in between same dates.'));
					goto a;
				}
			}
			
            if ($this->TravelRequests->save($travelRequest)) {
				
                $this->Flash->success(__('The travel request has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
				pr($travelRequest); exit;
                $this->Flash->error(__('The travel request could not be saved. Please, try again.'));
            }
        }
		
		a:
		$employees = $this->TravelRequests->Employees->find('list')->where(['id !='=>23,'salary_company_id'=>$st_company_id])->matching(
					'EmployeeCompanies', function ($q)  {
						return $q->where(['EmployeeCompanies.freeze' =>0]);
					}
				);  
        $this->set(compact('travelRequest','empData','employees','s_employee_id'));
        $this->set('_serialize', ['travelRequest']);
    }
	
	function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {
		$dates = array();
		$current = strtotime($first);
		$last = strtotime($last);

		while( $current <= $last ) {

			$dates[] = date($output_format, $current);
			$current = strtotime($step, $current);
		}

		return $dates;
	}

    /**
     * Edit method
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		 $st_year_id = $session->read('st_year_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		//$empData=$this->TravelRequests->Employees->get($s_employee_id,['contain'=>['Designations']]);
		$s_employee_id=$this->viewVars['s_employee_id'];
		$empData=$this->TravelRequests->Employees->get($s_employee_id,['contain'=>['Designations','Departments']]);
        $travelRequest = $this->TravelRequests->get($id, [
            'contain' => ['TravelRequestRows','Employees'=>['Designations']]
        ]); //pr($travelRequest);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $travelRequest = $this->TravelRequests->patchEntity($travelRequest, $this->request->data);
			$travelRequest->employee_id=$s_employee_id;
			
			$travelRequest->travel_from_date=date('Y-m-d',strtotime($travelRequest->travel_mode_from_date));
			$travelRequest->travel_to_date=date('Y-m-d',strtotime($travelRequest->travel_mode_to_date)); 
			
			$dates=$this->date_range($travelRequest->travel_from_date, $travelRequest->travel_to_date, $step = '+1 day', $output_format = 'Y-m-d' );
			foreach($dates  as $date){
				$c=$this->TravelRequests->find()->where(['travel_mode_from_date <='=>$date])->andWhere(['travel_mode_to_date >='=>$date])->where(['employee_id'=>$travelRequest->employee_id])->count();
				if($c>0){
					$this->Flash->error(__('Travel request cannot be fullfilled with duplicate dates.'));
					goto a;
				}
			}
			foreach($dates  as $date){
				$c=$this->TravelRequests->LeaveApplications->find()->where(['approve_leave_from <='=>$date])->andWhere([])->where(['employee_id'=>$travelRequest->employee_id])->count();
				if($c>0){
					$this->Flash->error(__('Leave application apllied in between same dates.'));
					goto a;
				}
			}
			
            if ($this->TravelRequests->save($travelRequest)) { 
                $this->Flash->success(__('The travel request has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The travel request could not be saved. Please, try again.'));
            }
        }

		a:
        $this->set(compact('travelRequest'));

		$employees = $this->TravelRequests->Employees->find('list')->where(['id !='=>23,'salary_company_id'=>$st_company_id])->matching(
					'EmployeeCompanies', function ($q)  {
						return $q->where(['EmployeeCompanies.freeze' =>0]);
					}
				);  
        $this->set(compact('travelRequest','empData','employees'));

        $this->set('_serialize', ['travelRequest']);
    }
	
	public function approved($id = null)
    {
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
        $leaveApplication = $this->TravelRequests->get($id);
		
		$EmployeeHierarchies=$this->TravelRequests->EmployeeHierarchies->find()->contain(['ParentAccountingGroups'])->where(['EmployeeHierarchies.employee_id'=>$leaveApplication->parent_employee_id])->first();
		if($EmployeeHierarchies->parent_id != null){ 
			$query = $this->TravelRequests->query();
					$query->update()
						->set(['parent_employee_id' => $EmployeeHierarchies->parent_accounting_group->employee_id])
						->where(['id' => $id])
						->execute();
			
		}else{
			$approve_date=date('Y-m-d');
			$query = $this->TravelRequests->query();
					$query->update()
						->set(['status' =>'approved','approve_date'=>$approve_date])
						->where(['id' => $id])
						->execute();
		}
		return $this->redirect(['controller'=>'Logins','action' => 'dashbord']);
    }

	public function cancle($id = null)
    {
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
        $leaveApplication = $this->TravelRequests->get($id);
		
		$EmployeeHierarchies=$this->TravelRequests->EmployeeHierarchies->find()->contain(['ParentAccountingGroups'])->where(['EmployeeHierarchies.employee_id'=>$leaveApplication->parent_employee_id])->first();

			
			$query = $this->TravelRequests->query();
					$query->update()
						->set(['leave_status' =>'cancle'])
						->where(['id' => $id])
						->execute();
	
		return $this->redirect(['controller'=>'Logins','action' => 'dashbord']);
    }
    /**
     * Delete method
     *
     * @param string|null $id Travel Request id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $travelRequest = $this->TravelRequests->get($id);
        if ($this->TravelRequests->delete($travelRequest)) {
            $this->Flash->success(__('The travel request has been deleted.'));
        } else {
            $this->Flash->error(__('The travel request could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
