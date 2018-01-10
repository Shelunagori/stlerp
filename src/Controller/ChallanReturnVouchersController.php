<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ChallanReturnVouchers Controller
 *
 * @property \App\Model\Table\ChallanReturnVouchersTable $ChallanReturnVouchers
 */
class ChallanReturnVouchersController extends AppController
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
        $this->paginate = [
            'contain' => ['Companies', 'Challans'=>['Customers']]
        ];
        $challanReturnVouchers = $this->paginate($this->ChallanReturnVouchers);

        $this->set(compact('challanReturnVouchers'));
        $this->set('_serialize', ['challanReturnVouchers']);
    }

    /**
     * View method
     *
     * @param string|null $id Challan Return Voucher id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $challanReturnVoucher = $this->ChallanReturnVouchers->get($id, [
            'contain' => ['Companies', 'Challans', 'ChallanReturnVoucherRows']
        ]);

        $this->set('challanReturnVoucher', $challanReturnVoucher);
        $this->set('_serialize', ['challanReturnVoucher']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($id=null)
    { 
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$s_employee_id=$this->viewVars['s_employee_id'];
		/*$challan= $this->ChallanReturnVouchers->Challans->get($id,[
		'contain' =>['Customers', 'Companies',  'Transporters','Vendors','ChallanRows'=>['Items','ChallanReturnVoucherRows']]]);*/
		
		$challan = $this->ChallanReturnVouchers->Challans->get($id, [
            'contain' => (['ChallanReturnVouchers'=>['ChallanReturnVoucherRows' => function($q) {
				return $q->select(['challan_return_voucher_id','challan_row_id','item_id','total_qty' => $q->func()->sum('ChallanReturnVoucherRows.quantity')])->group('ChallanReturnVoucherRows.challan_row_id');
			}],'Customers', 'Companies',  'Transporters','Vendors','ChallanRows'=>['Items']])
        ]);
		
		/* $sales_orders_qty=[];
			foreach($SalesOrders->invoices as $invoices){ 
				foreach($invoices->invoice_rows as $invoice_row){ 
					$sales_orders_qty[@$invoice_row->sales_order_row_id]=@$sales_orders_qty[$invoice_row->sales_order_row_id]+$invoice_row->total_qty;
				}
			}	 */
		
		$return_qty=[];
		foreach($challan->challan_return_vouchers as $challan_return_voucher){ //pr($challan_return_voucher); exit;
			foreach($challan_return_voucher->challan_return_voucher_rows as $challan_return_voucher_row){
				@$return_qty[@$challan_return_voucher_row->challan_row_id]+=$challan_return_voucher_row->total_qty;
				
			} 
		} 
		//pr($return_qty); exit;
        $challanReturnVoucher = $this->ChallanReturnVouchers->newEntity();
        if ($this->request->is('post')) {
            $challanReturnVoucher = $this->ChallanReturnVouchers->patchEntity($challanReturnVoucher, $this->request->data);
			
			$last_voucher_no=$this->ChallanReturnVouchers->find()->select(['voucher_no'])->where(['company_id' => $st_company_id])->order(['voucher_no' => 'DESC'])->first();
			
            if($last_voucher_no){
                $challanReturnVoucher->voucher_no=$last_voucher_no->voucher_no+1;
            }else{
                $challanReturnVoucher->voucher_no=1;
            }
			//pr($challanReturnVoucher->voucher_no); exit;
			//pr(date("Y-m-d")); exit;
			$challanReturnVoucher->voucher_no=1;
			$challanReturnVoucher->created_on=date("Y-m-d");
			$challanReturnVoucher->created_by=$s_employee_id;
			$challanReturnVoucher->company_id=$st_company_id;
			$challanReturnVoucher->challan_id=$id;
			$challanReturnVoucher->transaction_date=date("Y-m-d");
			//pr($challanReturnVoucher); exit;
            if ($this->ChallanReturnVouchers->save($challanReturnVoucher)) { pr($challanReturnVoucher); exit;
                $this->Flash->success(__('The challan return voucher has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The challan return voucher could not be saved. Please, try again.'));
            }
        }
       
        $this->set(compact('challanReturnVoucher', 'companies', 'challan','return_qty'));
        $this->set('_serialize', ['challanReturnVoucher']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Challan Return Voucher id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $challanReturnVoucher = $this->ChallanReturnVouchers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $challanReturnVoucher = $this->ChallanReturnVouchers->patchEntity($challanReturnVoucher, $this->request->data);
            if ($this->ChallanReturnVouchers->save($challanReturnVoucher)) {
                $this->Flash->success(__('The challan return voucher has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The challan return voucher could not be saved. Please, try again.'));
            }
        }
        $companies = $this->ChallanReturnVouchers->Companies->find('list', ['limit' => 200]);
        $challans = $this->ChallanReturnVouchers->Challans->find('list', ['limit' => 200]);
        $this->set(compact('challanReturnVoucher', 'companies', 'challans'));
        $this->set('_serialize', ['challanReturnVoucher']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Challan Return Voucher id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $challanReturnVoucher = $this->ChallanReturnVouchers->get($id);
        if ($this->ChallanReturnVouchers->delete($challanReturnVoucher)) {
            $this->Flash->success(__('The challan return voucher has been deleted.'));
        } else {
            $this->Flash->error(__('The challan return voucher could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
