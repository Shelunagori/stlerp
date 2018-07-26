<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * JournalVoucherRows Controller
 *
 * @property \App\Model\Table\JournalVoucherRowsTable $JournalVoucherRows
 */
class JournalVoucherRowsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['JournalVouchers', 'LedgerAccounts']
        ];
        $journalVoucherRows = $this->paginate($this->JournalVoucherRows);

        $this->set(compact('journalVoucherRows'));
        $this->set('_serialize', ['journalVoucherRows']);
    }

    /**
     * View method
     *
     * @param string|null $id Journal Voucher Row id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $journalVoucherRow = $this->JournalVoucherRows->get($id, [
            'contain' => ['JournalVouchers', 'LedgerAccounts']
        ]);

        $this->set('journalVoucherRow', $journalVoucherRow);
        $this->set('_serialize', ['journalVoucherRow']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $journalVoucherRow = $this->JournalVoucherRows->newEntity();
        if ($this->request->is('post')) {
            $journalVoucherRow = $this->JournalVoucherRows->patchEntity($journalVoucherRow, $this->request->data);
            if ($this->JournalVoucherRows->save($journalVoucherRow)) {
                $this->Flash->success(__('The journal voucher row has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The journal voucher row could not be saved. Please, try again.'));
            }
        }
        $journalVouchers = $this->JournalVoucherRows->JournalVouchers->find('list', ['limit' => 200]);
        $ledgerAccounts = $this->JournalVoucherRows->LedgerAccounts->find('list', ['limit' => 200]);
        $this->set(compact('journalVoucherRow', 'journalVouchers', 'ledgerAccounts'));
        $this->set('_serialize', ['journalVoucherRow']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Journal Voucher Row id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $journalVoucherRow = $this->JournalVoucherRows->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $journalVoucherRow = $this->JournalVoucherRows->patchEntity($journalVoucherRow, $this->request->data);
            if ($this->JournalVoucherRows->save($journalVoucherRow)) {
                $this->Flash->success(__('The journal voucher row has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The journal voucher row could not be saved. Please, try again.'));
            }
        }
        $journalVouchers = $this->JournalVoucherRows->JournalVouchers->find('list', ['limit' => 200]);
        $ledgerAccounts = $this->JournalVoucherRows->LedgerAccounts->find('list', ['limit' => 200]);
        $this->set(compact('journalVoucherRow', 'journalVouchers', 'ledgerAccounts'));
        $this->set('_serialize', ['journalVoucherRow']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Journal Voucher Row id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $journalVoucherRow = $this->JournalVoucherRows->get($id);
        if ($this->JournalVoucherRows->delete($journalVoucherRow)) {
            $this->Flash->success(__('The journal voucher row has been deleted.'));
        } else {
            $this->Flash->error(__('The journal voucher row could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
