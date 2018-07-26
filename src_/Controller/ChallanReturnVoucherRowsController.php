<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ChallanReturnVoucherRows Controller
 *
 * @property \App\Model\Table\ChallanReturnVoucherRowsTable $ChallanReturnVoucherRows
 */
class ChallanReturnVoucherRowsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ChallanReturnVouchers', 'Items', 'ChallanRows']
        ];
        $challanReturnVoucherRows = $this->paginate($this->ChallanReturnVoucherRows);

        $this->set(compact('challanReturnVoucherRows'));
        $this->set('_serialize', ['challanReturnVoucherRows']);
    }

    /**
     * View method
     *
     * @param string|null $id Challan Return Voucher Row id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $challanReturnVoucherRow = $this->ChallanReturnVoucherRows->get($id, [
            'contain' => ['ChallanReturnVouchers', 'Items', 'ChallanRows']
        ]);

        $this->set('challanReturnVoucherRow', $challanReturnVoucherRow);
        $this->set('_serialize', ['challanReturnVoucherRow']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $challanReturnVoucherRow = $this->ChallanReturnVoucherRows->newEntity();
        if ($this->request->is('post')) {
            $challanReturnVoucherRow = $this->ChallanReturnVoucherRows->patchEntity($challanReturnVoucherRow, $this->request->data);
            if ($this->ChallanReturnVoucherRows->save($challanReturnVoucherRow)) {
                $this->Flash->success(__('The challan return voucher row has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The challan return voucher row could not be saved. Please, try again.'));
            }
        }
        $challanReturnVouchers = $this->ChallanReturnVoucherRows->ChallanReturnVouchers->find('list', ['limit' => 200]);
        $items = $this->ChallanReturnVoucherRows->Items->find('list', ['limit' => 200]);
        $challanRows = $this->ChallanReturnVoucherRows->ChallanRows->find('list', ['limit' => 200]);
        $this->set(compact('challanReturnVoucherRow', 'challanReturnVouchers', 'items', 'challanRows'));
        $this->set('_serialize', ['challanReturnVoucherRow']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Challan Return Voucher Row id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $challanReturnVoucherRow = $this->ChallanReturnVoucherRows->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $challanReturnVoucherRow = $this->ChallanReturnVoucherRows->patchEntity($challanReturnVoucherRow, $this->request->data);
            if ($this->ChallanReturnVoucherRows->save($challanReturnVoucherRow)) {
                $this->Flash->success(__('The challan return voucher row has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The challan return voucher row could not be saved. Please, try again.'));
            }
        }
        $challanReturnVouchers = $this->ChallanReturnVoucherRows->ChallanReturnVouchers->find('list', ['limit' => 200]);
        $items = $this->ChallanReturnVoucherRows->Items->find('list', ['limit' => 200]);
        $challanRows = $this->ChallanReturnVoucherRows->ChallanRows->find('list', ['limit' => 200]);
        $this->set(compact('challanReturnVoucherRow', 'challanReturnVouchers', 'items', 'challanRows'));
        $this->set('_serialize', ['challanReturnVoucherRow']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Challan Return Voucher Row id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $challanReturnVoucherRow = $this->ChallanReturnVoucherRows->get($id);
        if ($this->ChallanReturnVoucherRows->delete($challanReturnVoucherRow)) {
            $this->Flash->success(__('The challan return voucher row has been deleted.'));
        } else {
            $this->Flash->error(__('The challan return voucher row could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
