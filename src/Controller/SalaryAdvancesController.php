<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SalaryAdvances Controller
 *
 * @property \App\Model\Table\SalaryAdvancesTable $SalaryAdvances
 */
class SalaryAdvancesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
		$this->viewBuilder()->layout('index_layout');
        $salaryAdvances = $this->paginate($this->SalaryAdvances);

        $this->set(compact('salaryAdvances'));
        $this->set('_serialize', ['salaryAdvances']);
    }

    /**
     * View method
     *
     * @param string|null $id Salary Advance id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $salaryAdvance = $this->SalaryAdvances->get($id, [
            'contain' => []
        ]);

        $this->set('salaryAdvance', $salaryAdvance);
        $this->set('_serialize', ['salaryAdvance']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
        $salaryAdvance = $this->SalaryAdvances->newEntity();
        if ($this->request->is('post')) {
            $salaryAdvance = $this->SalaryAdvances->patchEntity($salaryAdvance, $this->request->data);
            if ($this->SalaryAdvances->save($salaryAdvance)) {
                $this->Flash->success(__('The salary advance has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The salary advance could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('salaryAdvance'));
        $this->set('_serialize', ['salaryAdvance']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Salary Advance id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $salaryAdvance = $this->SalaryAdvances->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $salaryAdvance = $this->SalaryAdvances->patchEntity($salaryAdvance, $this->request->data);
            if ($this->SalaryAdvances->save($salaryAdvance)) {
                $this->Flash->success(__('The salary advance has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The salary advance could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('salaryAdvance'));
        $this->set('_serialize', ['salaryAdvance']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Salary Advance id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $salaryAdvance = $this->SalaryAdvances->get($id);
        if ($this->SalaryAdvances->delete($salaryAdvance)) {
            $this->Flash->success(__('The salary advance has been deleted.'));
        } else {
            $this->Flash->error(__('The salary advance could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
