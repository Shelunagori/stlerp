<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 */
class EventsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
	public function beforeFilter(Event $event)
	{
	$this->eventManager()->off($this->Csrf);
	} 
    public function index($id=null)
    {
		$this->viewBuilder()->layout('index_layout');
		if($id){ 
			$event = $this->Events->get($id, [
            'contain' => []
        ]);
		}else{
			$event = $this->Events->newEntity();

		}
		  if ($this->request->is(['patch', 'post', 'put'])) {
			 $event = $this->Events->patchEntity($event, $this->request->data);
			 $event->event_start_date = date('Y-m-d',strtotime($event->event_start_date));
			 $event->event_end_date = date('Y-m-d',strtotime($event->event_end_date));
			 //pr($event);exit;
			  if ($this->Events->save($event)) {
                $this->Flash->success(__('The event has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The event could not be saved. Please, try again.'));
            }
		 }
		 
		$where=[];
		$event_name=$this->request->query('event_name');
		$from_date=$this->request->query('from_date');
		$end_date=$this->request->query('end_date');
		 
		$this->set(compact('from_date','end_date','event_name'));
		
		if(!empty($event_name)){
			$where['Events.event_name LIKE']='%'.$event_name.'%';
		}
		
		if(!empty($from_date)){
			$from_date=date('Y-m-d',strtotime($from_date));
			$where['Events.event_start_date >=']=$from_date;
		}
		
		if(!empty($end_date)){
			$end_date=date('Y-m-d',strtotime($end_date));
			$where['Events.event_end_date <=']=$end_date;
		} 
        $events = $this->paginate($this->Events->find()->where($where)->order(['Events.id' => 'DESC']));

        $this->set(compact('events','event'));
        $this->set('_serialize', ['events','event']);
    }

    /**
     * View method
     *
     * @param string|null $id Event id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $event = $this->Events->get($id, [
            'contain' => []
        ]);

        $this->set('event', $event);
        $this->set('_serialize', ['event']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $event = $this->Events->newEntity();
        if ($this->request->is('post')) {
            $event = $this->Events->patchEntity($event, $this->request->data);
            if ($this->Events->save($event)) {
                $this->Flash->success(__('The event has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The event could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('event'));
        $this->set('_serialize', ['event']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Event id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $event = $this->Events->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $event = $this->Events->patchEntity($event, $this->request->data);
            if ($this->Events->save($event)) {
                $this->Flash->success(__('The event has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The event could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('event'));
        $this->set('_serialize', ['event']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Event id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $event = $this->Events->get($id);
        if ($this->Events->delete($event)) {
            $this->Flash->success(__('The event has been deleted.'));
        } else {
            $this->Flash->error(__('The event could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function getEvents(){
		$this->viewBuilder()->layout('');
		$events = $this->Events->find();
		$eventlist=array();
		foreach($events as $event){
			$start = date('Y-m-d',strtotime($event->event_start_date));
			if(date('Y-m-d',strtotime($event->event_end_date)) == "1970-01-01"){
				$end="";
			}else{
				$end = date('Y-m-d',strtotime($event->event_end_date));

			}
			$eventlist[]=['title'=>$event->event_name,'start'=>$start,'end'=>$end,'allDay'=>true];
		}
		$this->set(compact('eventlist'));
		$this->set('_serialize', ['eventlist']);
		
	}
}
