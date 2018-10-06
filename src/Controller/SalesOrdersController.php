<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Dompdf\Dompdf;
use Dompdf\Options;
use Cake\View\Helper\TextHelper;
use Cake\View\Helper\NumberHelper;
use Cake\View\Helper\HtmlHelper;

/**
 * SalesOrders Controller
 *
 * @property \App\Model\Table\SalesOrdersTable $SalesOrders
 */
class SalesOrdersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($status=null)
    {
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$this->viewBuilder()->layout('index_layout');
		
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->SalesOrders->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$copy_request=$this->request->query('copy-request');
		$gst_copy_request=$this->request->query('gst-copy-request');
		$job_card=$this->request->query('job-card');
		
		$Actionstatus="";
		$where=[];
		$where1=[];
		//$where1['Items.company_id']=$st_company_id;
		//$company_alise=$this->request->query('company_alise');
		$gst=$this->request->query('gst');
		$sales_order_no=$this->request->query('sales_order_no');
		$file=$this->request->query('file');
		$customer=$this->request->query('customer');
		$po_no=$this->request->query('po_no');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$items=$this->request->query('items');
		$item_category=$this->request->query('item_category');
		$item_group=$this->request->query('item_group');
		$item_subgroup=$this->request->query('item_subgroup');
		$salesman_name=$this->request->query('salesman_name');
		$pull_request=$this->request->query('pull-request');
		$gst=$this->request->query('gst');
		$Actionstatus=$this->request->query('Actionstatus');
		$s_employee_id = $this->viewVars['s_employee_id'];
		$allowed_acc = $this->viewVars['allowed_acc'];

		$this->set(compact('sales_order_no','customer','po_no','product','From','To','file','pull_request','items','gst'));
		/* if(!empty($company_alise)){
			$where['SalesOrders.so1 LIKE']='%'.$company_alise.'%';
		} */
		$EMP_ID =[23,16,17];
		if(in_array($s_employee_id,$EMP_ID)){
			$wherre=[];
		}else{
			$wherre['SalesOrders.employee_id']=$s_employee_id;
		}
		if(!empty($salesman_name)){
			$where['SalesOrders.employee_id']=$salesman_name;
		}
		if(!empty($sales_order_no)){
			$where['SalesOrders.so2 LIKE']=$sales_order_no;
		}
		if(!empty($file)){
			$where['SalesOrders.so3 LIKE']='%'.$file.'%';
		}
		if(!empty($customer)){
			$where['Customers.customer_name LIKE']='%'.$customer.'%';
		}
		if(!empty($po_no)){
			$where['SalesOrders.customer_po_no LIKE']='%'.$po_no.'%';
		}
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['SalesOrders.created_on >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['SalesOrders.created_on <=']=$To;
		}if(!empty($items)){
			$where['SalesOrderRows.item_id =']=$items;
		}
		if(!empty($item_category)){
			$where1['Items.item_category_id']=$item_category;
		}
		if(!empty($item_group)){
			$where1['Items.item_group_id']=$item_group;
		}
		
		if(!empty($item_subgroup)){
			$where1['Items.item_sub_group_id']=$item_subgroup;
		}
		if(!empty($items)){
			$where1['Items.id']=$items;
		}
        $this->paginate = [
            'contain' => ['Customers','Employees','Categories', 'Companies']
        ];
		
        $this->paginate = [
            'contain' => ['Customers']
        ];
		//
		//pr($salesman_name);exit;
		$styear=[1,3,2];
			if(in_array($st_year_id,$styear)){ 
				if(!empty($items) && empty($Actionstatus) || !empty($item_group) || !empty($item_subgroup)){ 
				//$where1['Items.id']=$items;
				//pr($where1); exit;
				$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
				$salesOrders = $this->SalesOrders->find()->where(['SalesOrders.sales_order_status !='=>"Close"]);

				$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
				->innerJoinWith('SalesOrderRows')
				->group(['SalesOrders.id'])
				->matching('SalesOrderRows.Items', function ($q) use($where1,$st_company_id) {
											return $q->where($where1)->contain(['ItemCompanies'=>function ($e) use($st_company_id,$st_year_id){
												return $e->where(['ItemCompanies.company_id'=>$st_company_id,'ItemCompanies.financial_year_id'=>$st_year_id]);
											}]);
							})
				->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
				->autoFields(true)
				->where(['SalesOrders.company_id'=>$st_company_id,'SalesOrders.financial_year_id'=>$st_year_id])
				->where($where);
		//pr($salesOrders->toArray());exit;
		}else{
				if($gst=="true" || $Actionstatus=="GstInvoice"){
					$tdate=date('Y-m-d',strtotime($financial_year->date_to)); 
					//pr($tdate); exit;
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find();
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id])
					->where($where)
					->where(['SalesOrders.gst'=>'yes','SalesOrders.created_on <= '=>$tdate])
					->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="GstInvoice";
				}else if($pull_request=="true" || $Actionstatus=="NonGstInvoice"){ 
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find()->where(['SalesOrders.sales_order_status !='=>"Close"]);
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id,'SalesOrders.financial_year_id'=>$st_year_id])
					->where($where)
					->where(['gst'=>'no'])
					->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="NonGstInvoice";
				}else if($copy_request=="copy" || $Actionstatus=="NonGstCopy"){ 
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find();
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id,'SalesOrders.financial_year_id'=>$st_year_id])
					->where(['gst'=>'no'])
					->where($where)->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="NonGstCopy";
				}else if($gst_copy_request=="copy" || $Actionstatus=="GstCopy"){
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find();
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id,'SalesOrders.financial_year_id'=>$st_year_id])
					->where(['gst'=>'yes'])
					->where($where)->order(['SalesOrders.id'=>'DESC']);
					//pr($salesOrders->toArray()); exit;
					$Actionstatus="GstCopy";
				}else { 
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find()->where(['SalesOrders.sales_order_status !='=>"Close"]);
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id,'SalesOrders.financial_year_id'=>$st_year_id])
					->where($where)->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="IndexPage";
					//pr($salesOrders->toArray()); exit;
				}
				//	exit;
		}
			
			}else{
				if(!empty($items) && empty($Actionstatus) || !empty($item_group) || !empty($item_subgroup)){ 
				//$where1['Items.id']=$items;
				//pr($where1); exit;
				$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
				$salesOrders = $this->SalesOrders->find()->where(['SalesOrders.sales_order_status !='=>"Close"]);

				$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
				->innerJoinWith('SalesOrderRows')
				->group(['SalesOrders.id'])
				->matching('SalesOrderRows.Items', function ($q) use($where1,$st_company_id) {
											return $q->where($where1)->contain(['ItemCompanies'=>function ($e) use($st_company_id,$st_year_id){
												return $e->where(['ItemCompanies.company_id'=>$st_company_id]);
											}]);
							})
				->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
				->autoFields(true)
				->where(['SalesOrders.company_id'=>$st_company_id])
				->where($where);
		//pr($salesOrders->toArray());exit;
		}else{
				if($gst=="true" || $Actionstatus=="GstInvoice"){
					$tdate=date('Y-m-d',strtotime($financial_year->date_to)); 
					//pr($tdate); exit;
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find();
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id])
					->where($where)
					->where(['SalesOrders.gst'=>'yes','SalesOrders.created_on <= '=>$tdate])
					->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="GstInvoice";
				}else if($pull_request=="true" || $Actionstatus=="NonGstInvoice"){ 
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find()->where(['SalesOrders.sales_order_status !='=>"Close"]);
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id])
					->where($where)
					->where(['gst'=>'no'])
					->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="NonGstInvoice";
				}else if($copy_request=="copy" || $Actionstatus=="NonGstCopy"){ 
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find();
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id])
					->where(['gst'=>'no'])
					->where($where)->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="NonGstCopy";
				}else if($gst_copy_request=="copy" || $Actionstatus=="GstCopy"){
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find();
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id])
					->where(['gst'=>'yes'])
					->where($where)->order(['SalesOrders.id'=>'DESC']);
					//pr($salesOrders->toArray()); exit;
					$Actionstatus="GstCopy";
				}else { 
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find()->where(['SalesOrders.sales_order_status !='=>"Close"]);
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id])
					->where($where)->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="IndexPage";
					//pr($salesOrders->toArray()); exit;
				}
				//	exit;
		}
			}
		
		
		
		$total_sales=[]; $total_qty=[];
		foreach($salesOrders as $salesorder){
			$total_sales[$salesorder->id]=$salesorder->total_sales;
			foreach($salesorder->sales_order_rows as $sales_order_row){
				foreach($sales_order_row->invoice_rows as $invoice_row){
						if(sizeof($invoice_row) > 0){
							@$total_qty[$salesorder->id]+=$invoice_row->quantity;
						}
				}
			}
		}
		
		
	
		$Items = $this->SalesOrders->SalesOrderRows->Items->find('list')->order(['Items.name' => 'ASC']);
        /* $SalesMans = $this->SalesOrders->Employees->find('list')->matching(
					'Departments', function ($q) use($items,$st_company_id) {
						return $q->where(['Departments.id' =>1]);
					}
				); */
		$salesOrders = $salesOrders->contain(['Employees'=>function($q) use($st_company_id){
			return $q->matching(
					'Employees.Departments', function ($q) use($st_company_id) {
						return $q->where(['Departments.id' =>3]);
					}
				);
		}]);		
		
	
		$created_by=[];		
		foreach($salesOrders as $salesOrder){
			if(@$total_sales[@$salesOrder->id] == @$total_qty[@$salesOrder->id] && $st_year_id==@$salesOrder->financial_year_id){ 
				$created_by[] = $salesOrder->created_by;
			}
		}		
		
		 $SalesMansEmps = $this->SalesOrders->Employees->find()->matching(
						'Departments', function ($q) use($st_company_id) {
							return $q->where(['Departments.id' =>3]);
						}
					);
						
		$smemps=[];			
		foreach($SalesMansEmps as $smemp){ 
			if(@$total_sales[@$salesOrder->id] == @$total_qty[@$salesOrder->id] && $st_year_id==@$salesOrder->financial_year_id){ 
				$smemps[] = $smemp->id;
			}
		}
		//pr($created_by);exit;
		$EMP_ID =[23,16,17];
		//pr($created_by);exit;
		//pr($smemps);
		/*  if((in_array($s_employee_id,$EMP_ID) || in_array($s_employee_id,$allowed_acc)) || in_array($smemps,$created_by)){
			 $SalesMans = $this->SalesOrders->Employees->find('list')->matching(
						'Departments', function ($q) use($st_company_id) {
							return $q->where(['Departments.id' =>1]);
						}
					);
		}else{
			$SalesMans = $this->SalesOrders->Employees->find('list')->matching(
						'Departments', function ($q) use($st_company_id) {
							return $q->where(['Departments.id' =>1]);
						}
					)->where(['Employees.id'=>$s_employee_id]);
		} */	
		
		
		$SalesMans = $this->SalesOrders->Employees->find('list')->matching(
						'Departments', function ($q) use($st_company_id) {
							return $q->where(['Departments.id' =>1]);
						}
					);
	
		$ItemGroups = $this->SalesOrders->SalesOrderRows->Items->ItemGroups->find('list')->order(['ItemGroups.name' => 'ASC']);
		$ItemSubGroups = $this->SalesOrders->SalesOrderRows->Items->ItemSubGroups->find('list')->order(['ItemSubGroups.name' => 'ASC']);		
	 $this->set(compact('salesOrders','status','copy_request','gst_copy_request','job_card','SalesOrderRows','Items','gst','SalesMans','salesman_name','total_sales','total_qty','Actionstatus','st_year_id','ItemGroups','ItemSubGroups','item_subgroup','item_group','item_category','created_by'));
		 $this->set('_serialize', ['salesOrders']);
		$this->set(compact('url'));
		
		
    }
	
	

	public function exportExcel($status=null)
    { 
		$this->viewBuilder()->layout('');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		if(empty($status)){ $status="Pending"; }
		$copy_request=$this->request->query('copy-request');
		$gst_copy_request=$this->request->query('gst-copy-request');
		$job_card=$this->request->query('job-card');
		
		$Actionstatus="";
		$where=[];
		//$company_alise=$this->request->query('company_alise');
		$gst=$this->request->query('gst');
		$sales_order_no=$this->request->query('sales_order_no');
		$file=$this->request->query('file');
		$customer=$this->request->query('customer');
		$po_no=$this->request->query('po_no');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$items=$this->request->query('items');
		$salesman_name=$this->request->query('salesman_name');
		$pull_request=$this->request->query('pull-request');
		$gst=$this->request->query('gst');
		$Actionstatus=$this->request->query('Actionstatus');
		$s_employee_id = $this->viewVars['s_employee_id'];

		$this->set(compact('sales_order_no','customer','po_no','product','From','To','file','pull_request','items','gst','st_year_id'));
		/* if(!empty($company_alise)){
			$where['SalesOrders.so1 LIKE']='%'.$company_alise.'%';
		} */
		
		if(!empty($salesman_name)){
			$where['SalesOrders.employee_id']=$salesman_name;
		}
		if(!empty($sales_order_no)){
			$where['SalesOrders.so2 LIKE']=$sales_order_no;
		}
		if(!empty($file)){
			$where['SalesOrders.so3 LIKE']='%'.$file.'%';
		}
		if(!empty($customer)){
			$where['Customers.customer_name LIKE']='%'.$customer.'%';
		}
		if(!empty($po_no)){
			$where['SalesOrders.customer_po_no LIKE']='%'.$po_no.'%';
		}
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['SalesOrders.created_on >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['SalesOrders.created_on <=']=$To;
		}if(!empty($items)){
			$where['SalesOrderRows.item_id =']=$items;
		}
		$EMP_ID =[23,16,17];
		if(in_array($s_employee_id,$EMP_ID)){
			$wherre=[];
		}else{
			$wherre['SalesOrders.employee_id']=$s_employee_id;
		}
        $this->paginate = [
            'contain' => ['Customers','Employees','Categories', 'Companies']
        ];
		
        $this->paginate = [
            'contain' => ['Customers']
        ];
		//pr($status); exit;
		$styear=[1,3,2];
			if(in_array($st_year_id,$styear)){ 
				if(!empty($items) && empty($Actionstatus) || !empty($item_group) || !empty($item_subgroup)){ 
				//$where1['Items.id']=$items;
				//pr($where1); exit;
				$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
				$salesOrders = $this->SalesOrders->find()->where(['SalesOrders.sales_order_status !='=>"Close"]);

				$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
				->innerJoinWith('SalesOrderRows')
				->group(['SalesOrders.id'])
				->matching('SalesOrderRows.Items', function ($q) use($where1,$st_company_id) {
											return $q->where($where1)->contain(['ItemCompanies'=>function ($e) use($st_company_id,$st_year_id){
												return $e->where(['ItemCompanies.company_id'=>$st_company_id,'ItemCompanies.financial_year_id'=>$st_year_id]);
											}]);
							})
				->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
				->autoFields(true)
				->where(['SalesOrders.company_id'=>$st_company_id,'SalesOrders.financial_year_id'=>$st_year_id])
				->where($where)->where($wherre);
		//pr($salesOrders->toArray());exit;
		}else{
				if($gst=="true" || $Actionstatus=="GstInvoice"){
					$tdate=date('Y-m-d',strtotime($financial_year->date_to)); 
					//pr($tdate); exit;
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find();
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id,'SalesOrders.financial_year_id'=>$st_year_id])
					->where($where)->where($wherre)
					->where(['SalesOrders.gst'=>'yes','SalesOrders.created_on <= '=>$tdate])
					->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="GstInvoice";
				}else if($pull_request=="true" || $Actionstatus=="NonGstInvoice"){ 
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find()->where(['SalesOrders.sales_order_status !='=>"Close"]);
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id,'SalesOrders.financial_year_id'=>$st_year_id])
					->where($where)
					->where(['gst'=>'no'])->where($wherre)
					->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="NonGstInvoice";
				}else if($copy_request=="copy" || $Actionstatus=="NonGstCopy"){ 
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find();
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id,'SalesOrders.financial_year_id'=>$st_year_id])
					->where(['gst'=>'no'])->where($wherre)
					->where($where)->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="NonGstCopy";
				}else if($gst_copy_request=="copy" || $Actionstatus=="GstCopy"){
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find();
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id,'SalesOrders.financial_year_id'=>$st_year_id])
					->where(['gst'=>'yes'])->where($wherre)
					->where($where)->order(['SalesOrders.id'=>'DESC']);
					//pr($salesOrders->toArray()); exit;
					$Actionstatus="GstCopy";
				}else { 
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find()->where(['SalesOrders.sales_order_status !='=>"Close"]);
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id,'SalesOrders.financial_year_id'=>$st_year_id])
					->where($where)->where($wherre)->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="IndexPage";
					//pr($salesOrders->toArray()); exit;
				}
				//	exit;
		}
			}else{
				if(!empty($items) && empty($Actionstatus) || !empty($item_group) || !empty($item_subgroup)){ 
				//$where1['Items.id']=$items;
				//pr($where1); exit;
				$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
				$salesOrders = $this->SalesOrders->find()->where(['SalesOrders.sales_order_status !='=>"Close"]);

				$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
				->innerJoinWith('SalesOrderRows')
				->group(['SalesOrders.id'])
				->matching('SalesOrderRows.Items', function ($q) use($where1,$st_company_id) {
											return $q->where($where1)->contain(['ItemCompanies'=>function ($e) use($st_company_id,$st_year_id){
												return $e->where(['ItemCompanies.company_id'=>$st_company_id]);
											}]);
							})
				->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
				->autoFields(true)
				->where(['SalesOrders.company_id'=>$st_company_id])
				->where($where)->where($wherre);
		//pr($salesOrders->toArray());exit;
		}else{
				if($gst=="true" || $Actionstatus=="GstInvoice"){
					$tdate=date('Y-m-d',strtotime($financial_year->date_to)); 
					//pr($tdate); exit;
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find();
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id])
					->where($where)->where($wherre)
					->where(['SalesOrders.gst'=>'yes','SalesOrders.created_on <= '=>$tdate])
					->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="GstInvoice";
				}else if($pull_request=="true" || $Actionstatus=="NonGstInvoice"){ 
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find()->where(['SalesOrders.sales_order_status !='=>"Close"]);
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id])
					->where($where)->where($wherre)
					->where(['gst'=>'no'])
					->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="NonGstInvoice";
				}else if($copy_request=="copy" || $Actionstatus=="NonGstCopy"){ 
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find();
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id])
					->where(['gst'=>'no'])->where($wherre)
					->where($where)->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="NonGstCopy";
				}else if($gst_copy_request=="copy" || $Actionstatus=="GstCopy"){
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find();
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_idx])
					->where(['gst'=>'yes'])->where($wherre)
					->where($where)->order(['SalesOrders.id'=>'DESC']);
					//pr($salesOrders->toArray()); exit;
					$Actionstatus="GstCopy";
				}else { 
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$salesOrders = $this->SalesOrders->find()->where(['SalesOrders.sales_order_status !='=>"Close"]);
					$salesOrders->select(['id','total_sales'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')
					->group(['SalesOrders.id'])
					->contain(['Customers','Quotations','SalesOrderRows.InvoiceRows','SalesOrderRows'=>['Items'=>['InvoiceRows']]])
					->autoFields(true)
					->where(['SalesOrders.company_id'=>$st_company_id])->where($wherre)
					->where($where)->order(['SalesOrders.id'=>'DESC']);
					$Actionstatus="IndexPage";
					//pr($salesOrders->toArray()); exit;
				}
				//	exit;
		}
			}
		$total_sales=[]; $total_qty=[];
		foreach($salesOrders as $salesorder){
			$total_sales[$salesorder->id]=$salesorder->total_sales;
			foreach($salesorder->sales_order_rows as $sales_order_row){
				foreach($sales_order_row->invoice_rows as $invoice_row){
						if(sizeof($invoice_row) > 0){
							@$total_qty[$salesorder->id]+=$invoice_row->quantity;
						}
				}
			}
		}
		
		
	
		$Items = $this->SalesOrders->SalesOrderRows->Items->find('list')->order(['Items.name' => 'ASC']);
		
        /* $SalesMans = $this->SalesOrders->Employees->find('list')->matching(
					'Departments', function ($q) use($items,$st_company_id) {
						return $q->where(['Departments.id' =>1]);
					}
				); */ //pr($salesOrders->toArray()); exit;
		$EMP_ID =[23,16,17];
		if(in_array($s_employee_id,$EMP_ID)){
			 $SalesMans = $this->SalesOrders->Employees->find('list')->matching(
						'Departments', function ($q) use($st_company_id) {
							return $q->where(['Departments.id' =>1]);
						}
					);
		}else{
			$SalesMans = $this->SalesOrders->Employees->find('list')->matching(
						'Departments', function ($q) use($st_company_id) {
							return $q->where(['Departments.id' =>1]);
						}
					)->where(['Employees.id'=>$s_employee_id]);
		}			
	 $this->set(compact('salesOrders','status','copy_request','gst_copy_request','job_card','SalesOrderRows','Items','gst','SalesMans','salesman_name','total_sales','total_qty','Actionstatus','ItemGroups','ItemSubGroups'));
		 $this->set('_serialize', ['salesOrders']);
		$this->set(compact('url'));
		
    }
	
	public function report(){
		echo "hello"; 
		//$this->viewBuilder()->layout('');
		$Quotations =$this->SalesOrders->Quotations->find()->where(['Quotations.id' =>117]);
		pr($Quotations);exit;
	}
	

    /**
     * View method
     *
     * @param string|null $id Sales Order id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $salesOrder = $this->SalesOrders->get($id, [
            'contain' => ['Customers', 'Companies','Carrier','Courier','SalesOrderRows' => ['Items']]
        ]);
        $this->set('salesOrder', $salesOrder);
        $this->set('_serialize', ['salesOrder']);
    }
	
	public function pdf($id = null)
    {
		$this->viewBuilder()->layout('');
		$id = $this->EncryptingDecrypting->decryptData($id);
        $salesOrder = $this->SalesOrders->get($id, [
            'contain' => ['Customers', 'Companies','Carrier','Creator','Editor','Courier','Employees','SalesOrderRows' => function($q){
				return $q->order(['SalesOrderRows.id' => 'ASC'])->contain(['SaleTaxes','Items'=>['Units']]);
			}]
        ]);

        $this->set('salesOrder', $salesOrder);
        $this->set('_serialize', ['salesOrder']);
    }
	
	public function gstPdf($id = null)
    {
		$this->viewBuilder()->layout('');
		$id = $this->EncryptingDecrypting->decryptData($id);
        $salesOrder = $this->SalesOrders->get($id, [
            'contain' => ['Customers', 'Companies','Carrier','Creator','Editor','Courier','Employees','SalesOrderRows' => function($q){
				return $q->order(['SalesOrderRows.id' => 'ASC'])->contain(['Items'=>['Units']]);
			}]
        ]);
		
		$cgst_per=[];
		$sgst_per=[];
		$igst_per=[];
		foreach($salesOrder->sales_order_rows as $sales_order_row){
			if($sales_order_row->cgst_per){
				$cgst_per[$sales_order_row->id]=$this->SalesOrders->SaleTaxes->get(@$sales_order_row->cgst_per);
			}
			if($sales_order_row->sgst_per){
				$sgst_per[$sales_order_row->id]=$this->SalesOrders->SaleTaxes->get(@$sales_order_row->sgst_per);
			}
			if($sales_order_row->igst_per){
				$igst_per[$sales_order_row->id]=$this->SalesOrders->SaleTaxes->get(@$sales_order_row->igst_per);
			}
		}
		
		
		$this->set(compact('salesOrder','cgst_per','sgst_per','igst_per'));
		//$this->set('salesOrder', $salesOrder,'cgst_per','sgst_per','igst_per');
        $this->set('_serialize', ['salesOrder']);
    }
	
	public function confirm($id = null)
    {
		$this->viewBuilder()->layout('pdf_layout');
		$id = $this->EncryptingDecrypting->decryptData($id);
		$salesorder = $this->SalesOrders->get($id, [
            'contain' => ['SalesOrderRows']
			]);
		if ($this->request->is(['patch', 'post', 'put'])) {
            foreach($this->request->data['sales_order_rows'] as $sales_order_row_id=>$value){
				$salesOrderRow=$this->SalesOrders->SalesOrderRows->get($sales_order_row_id);
				$salesOrderRow->height=$value["height"];
				$this->SalesOrders->SalesOrderRows->save($salesOrderRow);
			}
			$id = $this->EncryptingDecrypting->encryptData($id);
			return $this->redirect(['action' => 'confirm',$id]);
        }
		
		$this->set(compact('salesorder','id'));
    }
	
	
	/* public function DataMigrate()
    {
		$this->viewBuilder()->layout('index_layout');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$Quotations=$this->SalesOrders->Quotations->QuotationRows->find();
		//pr($Quotation->toArray()); exit;
		foreach($Quotations as $Quotation){
			$salesOrders=$this->SalesOrders->find()->contain(['SalesOrderRows'])->where(['SalesOrders.quotation_id'=>$Quotation->quotation_id])->toArray();
		//	pr($salesOrders);
			if(sizeof($salesOrders) > 0){
				foreach($salesOrders as $salesOrder){
					foreach($salesOrder->sales_order_rows as $sales_order_row){ //pr($Quotation->item_id); exit;
					
						
						$query = $this->SalesOrders->SalesOrderRows->query();
						$query->update()
							->set(['quotation_row_id' => $Quotation->id])
							->where(['item_id' => $Quotation->item_id,'sales_order_id'=>$salesOrder->id])
							->execute();
						}
				}
			}
		}
		exit;
	}
	
	 */
	
	
    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		
		$s_employee_id=$this->viewVars['s_employee_id'];
		$status = $status_close=$this->request->query('status');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$Company = $this->SalesOrders->Companies->get($st_company_id);
		
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->SalesOrders->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		
		
			   $SessionCheckDate = $this->FinancialYears->get($st_year_id);
			   $fromdate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_from));   
			   $todate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_to)); 
			   $tody1 = DATE("Y-m-d");

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

			
				

		$quotation_id=@(int)$this->request->query('quotation');
		$quotation=array(); 
		$process_status='New';
		if(!empty($quotation_id)){
			$quotation = $this->SalesOrders->Quotations->get($quotation_id, [
				'contain' => ['QuotationRows.Items'
					],'Customers'=>['CustomerAddress' => function($q){
					return $q->where(['default_address'=>1]);
				}]
			]);
			$process_status='Pulled From Quotation';
		}
		
		$this->set(compact('quotation','process_status'));
		
		$sales_id=$this->request->query('copy');
		$job_id=$this->request->query('job');
		//pr($process_status); exit;
		
		if(!empty($sales_id)){ 
			
			$salesOrder_data = $this->SalesOrders->newEntity();
			
			$salesOrder = $this->SalesOrders->get($sales_id, [
				'contain' => ['Customers'=>['CustomerContacts'=>function($q){
				return $q
			->where(['CustomerContacts.default_contact'=>1]);
			}],'Employees','SalesOrderRows'=>['Items']]
			]);
			$process_status='Copy';
			
		}
		elseif(!empty($job_id)){
			$salesOrder = $this->SalesOrders->get($job_id, [
				'contain' => ['SalesOrderRows']
			]);
		}
		else{
			  $salesOrder_data = $this->SalesOrders->newEntity();
			}
		
      
        if ($this->request->is(['patch', 'post', 'put'])) {
			
			//$salesOrder = $this->SalesOrders->newEntity();
			
            $salesOrder = $this->SalesOrders->patchEntity($salesOrder_data, $this->request->data);
			$last_so_no=$this->SalesOrders->find()->select(['so2'])->where(['company_id' => $st_company_id])->order(['so2' => 'DESC'])->first();
			$salesOrder->expected_delivery_date=date("Y-m-d",strtotime($salesOrder->expected_delivery_date)); 
			$salesOrder->po_date=date("Y-m-d",strtotime($salesOrder->po_date)); 
			$salesOrder->created_by=$s_employee_id; 
			if($last_so_no){
				$salesOrder->so2=$last_so_no->so2+1;
			}else{
				$salesOrder->so2=1;
			}
			
			
			$salesOrder->created_on=date("Y-m-d",strtotime($salesOrder->created_on));
			$salesOrder->edited_on=date("Y-m-d",strtotime($salesOrder->edited_on));
			$salesOrder->quotation_id=$quotation_id;
			$salesOrder->created_on_time= date("Y-m-d h:i:sA");
			$salesOrder->company_id=$st_company_id;
			
			
			
			//pr($salesOrder);exit;
			if ($this->SalesOrders->save($salesOrder)) { 
				$status_close=$this->request->query('status');
				if($status_close=="open")
				{
					$totalSalesOrderIDs=[];
					foreach($salesOrder->sales_order_rows as $sales_order_row)
					{
						$totalSalesOrderIDs[$sales_order_row->quotation_row_id]=$sales_order_row->quotation_row_id;
					}
					$totalSalesOrderQty =[];
					$Quotation = $this->SalesOrders->Quotations->get($salesOrder->quotation_id, [
                     'contain' => ['QuotationRows'=>['SalesOrderRows'],'SalesOrders' => ['SalesOrderRows']]
                    ]);
					
					if(!empty($Quotation->quotation_rows))
					{
						foreach($Quotation->quotation_rows as $quotation_row)
						{
							if(!empty($quotation_row->sales_order_rows))
							{
								foreach($quotation_row->sales_order_rows as $sales_order_row)
								{
									@$totalSalesOrderQty[@$sales_order_row->quotation_row_id] +=@$sales_order_row->quantity;
								}
							}
						}
					}
					
					if(!empty($Quotation->quotation_rows))
					{
						foreach($Quotation->quotation_rows as $quotation_row)
						{
							if($quotation_row->quantity!=$totalSalesOrderQty[$quotation_row->id] )
							{
								$query_pending = $this->SalesOrders->Quotations->query();
								$query_pending->update()
								->set(['status' => 'Pending'])
								->where(['id' => $salesOrder->quotation_id])
								->execute();
							}
							else if($quotation_row->quantity==$totalSalesOrderQty[$quotation_row->id] && $quotation_row->id==@$totalSalesOrderIDs[@$quotation_row->id])
							{
								$query_pending = $this->SalesOrders->Quotations->query();
								$query_pending->update()
								->set(['status' => 'Converted Into Sales Order'])
								->where(['id' => $salesOrder->quotation_id])
								->execute();
							}
						}
					}
				}  
				else if($status_close=="close"){
					$query = $this->SalesOrders->Quotations->query();
					$query->update()
					->set(['status' => 'Converted Into Sales Order'])
					->where(['id' => $quotation_id])
					->execute();
				}
				
				$this->Flash->success(__('The sales order has been saved.'));
				return $this->redirect(['action' => 'confirm/'.$salesOrder->id]);

            } else {
                $this->Flash->error(__('The sales order could not be saved. Please, try again.'));
            }
        }
        $customers = $this->SalesOrders->Customers->find('all')->order(['Customers.customer_name' => 'ASC'])->contain(['CustomerAddress'=>function($q){
			return $q
			->where(['CustomerAddress.default_address'=>1]);
		}])->matching(
					'CustomerCompanies', function ($q) use($st_company_id) {
						return $q->where(['CustomerCompanies.company_id' => $st_company_id]);
					}
				);
		$copy=$this->request->query('copy');
		//pr ($copy); exit;
		if(!empty($copy)){
			$process_status='Copy';
		}
		//pr ($process_status); exit;
		if($quotation_id){
			$Filenames = $this->SalesOrders->Filenames->find()->where(['customer_id' => $quotation->customer_id]);
		}elseif($id){
			$Filenames = $this->SalesOrders->Filenames->find()->where(['customer_id' => $salesOrder->customer_id]);
		}else{
			$Filenames = $this->SalesOrders->Filenames->find();
		}
		if(!empty(@$quotation_id)){
			 $SalesOrders = $this->SalesOrders->Quotations->get($quotation_id, [
            'contain' => (['SalesOrders'=>['SalesOrderRows' => function($q) {
				return $q->select(['sales_order_id','quotation_row_id','item_id','total_qty' => $q->func()->sum('SalesOrderRows.quantity')])->group('SalesOrderRows.quotation_row_id');
			}]])
        ]);
			
		$sales_orders_qty=[];
			foreach($SalesOrders->sales_orders as $sales_orders){ 
				foreach($sales_orders->sales_order_rows as $sales_order_row){ 
					$sales_orders_qty[@$sales_order_row->quotation_row_id]=@$sales_orders_qty[$sales_order_row->quotation_row_id]+$sales_order_row->total_qty;
					
				}
			}	
		}
		
        $companies = $this->SalesOrders->Companies->find('all');
		$quotationlists = $this->SalesOrders->Quotations->find()->where(['status'=>'Pending'])->order(['Quotations.id' => 'DESC']);
				
		$items = $this->SalesOrders->Items->find()->matching(
					'ItemCompanies', function ($q) use($st_company_id) {
						return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
					} 
				)->order(['Items.name' => 'ASC']);		
				
		$ItemsOptions=[];
		foreach($items as $item){ 
					$ItemsOptions[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
		}		

		$Item_datas = $this->SalesOrders->Items->find()->order(['Items.name' => 'ASC'])->matching(
						'ItemCompanies', function ($q) use($st_company_id) {
							return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0])->orWhere(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 1]);
						}
					);		
					
			$ItemsOptionsData=[];
			foreach($Item_datas as $item){ 
						$ItemsOptionsData[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
			}	
		$transporters = $this->SalesOrders->Carrier->find('list')->order(['Carrier.transporter_name' => 'ASC']);
		//$employees = $this->SalesOrders->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC']);
		$employees = $this->SalesOrders->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.company_id' => $st_company_id,'EmployeeCompanies.freeze' => 0]);
					}
				);
		$termsConditions = $this->SalesOrders->TermsConditions->find('all');
		$SaleTaxes = $this->SalesOrders->SaleTaxes->find('all')->where(['SaleTaxes.freeze'=>0])->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				);
		$QuotaionQty=[];$totalSalesOrderQty=[];$MaxQty=[];
		if(!empty(@$quotation_id))
		{
			$Quotations = $this->SalesOrders->Quotations->get($quotation_id, [
            'contain' => (['SalesOrders'=>['SalesOrderRows'],'QuotationRows'])
             ]);
			if(!empty($Quotations->sales_orders))
			{ 
				foreach($Quotations->sales_orders as $sales_order)
				{
					if(!empty($sales_order->sales_order_rows))
					{
						foreach($sales_order->sales_order_rows as $sales_order_row)
						{
							@$totalSalesOrderQty[@$sales_order_row->quotation_row_id] +=@$sales_order_row->quantity;
						}
					}
				}
			}
			foreach($Quotations->quotation_rows as $quotation_row)
			{
				@$QuotaionQty[@$quotation_row->id]=@$quotation_row->quantity;
			}
			
			foreach($Quotations->quotation_rows as $quotation_row)
			{
				@$MaxQty[@$quotation_row->id] = @$QuotaionQty[@$quotation_row->id]-@$totalSalesOrderQty[@$quotation_row->id];
			}
		}
		
		//pr($totalSalesOrderQty);exit;
        $this->set(compact('salesOrder', 'customers', 'companies','quotationlists','items','transporters','Filenames','termsConditions','serviceTaxs','exciseDuty','employees','SaleTaxes','copy','process_status','Company','chkdate','financial_year','sales_id','salesOrder_copy','job_id','salesOrder_data','sales_orders_qty','MaxQty','ItemsOptions','ItemsOptionsData'));
        $this->set('_serialize', ['salesOrder']);
    }
	
	public function pullFromQuotation(){
		if ($this->request->is('post')) {
			$quotation_id=$this->request->data["quotation_id"];
            return $this->redirect(['action' => 'add?quotation='.$quotation_id]);
        }
	}

    /**
     * Edit method
     *
     * @param string|null $id Sales Order id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$so = $this->SalesOrders->get($id); //pr($salesOrder->quotation_id);exit;
		if($so->quotation_id > 0){ 
        $salesOrder = $this->SalesOrders->get($id, [
            'contain' => ['Quotations'=>['QuotationRows'],'SalesOrderRows' => ['Items','JobCardRows'],'Invoices' => ['InvoiceRows']]
        ]);
		}else{ 
			$salesOrder = $this->SalesOrders->get($id, [
            'contain' => ['SalesOrderRows' => ['Items','JobCardRows'],'Invoices' => ['InvoiceRows']]
        ]);
		}
		//pr($salesOrder->quotation->quotation_rows);
		$qt_data=[];
		$qt_data1=[];
		
		if($so->quotation_id > 0){
		$session = $this->request->session();
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->SalesOrders->FinancialYears->find()->where(['id'=>$st_year_id])->first();
			foreach($salesOrder->quotation->quotation_rows as $quotation_row){
				$qt_data[$quotation_row->item_id]=$quotation_row->quantity;
				//$qt_data1[$quotation_row->item_id]=$quotation_row->proceed_qty;
			}
		}
//pr($qt_data1); exit;
		$closed_month=$this->viewVars['closed_month'];
		
		if(!in_array(date("m-Y",strtotime($salesOrder->created_on)),$closed_month))
		{

			$Em = new FinancialYearsController;
			$financial_year_data = $Em->checkFinancialYear($salesOrder->created_on);
			

			$s_employee_id=$this->viewVars['s_employee_id'];
			
			$session = $this->request->session();
			$st_company_id = $session->read('st_company_id');
			$st_year_id = $session->read('st_year_id');
			
			   $SessionCheckDate = $this->FinancialYears->get($st_year_id);
			   $fromdate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_from));   
			   $todate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_to)); 
			   $tody1 = DATE("Y-m-d");

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
				$salesOrder = $this->SalesOrders->patchEntity($salesOrder, $this->request->data);
				
				$salesOrder->expected_delivery_date=date("Y-m-d",strtotime($salesOrder->expected_delivery_date));
				$salesOrder->po_date=date("Y-m-d",strtotime($salesOrder->po_date)); 
				$salesOrder->date=date("Y-m-d",strtotime($salesOrder->date));
				$salesOrder->edited_by=$s_employee_id;
				$salesOrder->edited_on=date("Y-m-d");
				$salesOrder->edited_on_time= date("Y-m-d h:i:sA");
				//pr($salesOrder);exit;
				if ($this->SalesOrders->save($salesOrder)) {
					
					foreach($salesOrder->sales_order_rows as $sales_order_row){
						$job_card_row_ids=explode(',',$sales_order_row->job_card_row_ids);
						foreach($job_card_row_ids as $job_card_row_id){
							//pr($job_card_row_id); exit;
							$query = $this->SalesOrders->SalesOrderRows->JobCardRows->query();
							$query->update()
							->set(['sales_order_row_id' => $sales_order_row->id])
							->where(['id' => $job_card_row_id])
							->execute();
						}
					}
					
						
					$falg=0;
					
					if($falg==1){
						$query_pending = $this->SalesOrders->Quotations->query();
						$query_pending->update()
						->set(['status' => 'Pending'])
						->where(['id' => $salesOrder->quotation_id])
						->execute();
					}else{
						$query_pending = $this->SalesOrders->Quotations->query();
						$query_pending->update()
						->set(['status' => 'Converted Into Sales Order'])
						->where(['id' => $salesOrder->quotation_id])
						->execute();
					}
					
					$salesOrder->job_card_status='Pending';
					$query2 = $this->SalesOrders->query();
					$query2->update()
						->set(['job_card_status' => 'Pending'])
						->where(['id' => $id])
						->execute();
					
					$this->Flash->success(__('The sales order has been saved.'));
					return $this->redirect(['action' => 'confirm/'.$salesOrder->id]);
				} else { 
					$this->Flash->error(__('The sales order could not be saved. Please, try again.'));
				}
			}
			$customers = $this->SalesOrders->Customers->find('all')->order(['Customers.customer_name' => 'ASC'])->contain(['CustomerAddress'=>function($q){
				return $q
				->where(['CustomerAddress.default_address'=>1]);
			}])->matching(
						'CustomerCompanies', function ($q) use($st_company_id) {
							return $q->where(['CustomerCompanies.company_id' => $st_company_id]);
						}
					);
			$companies = $this->SalesOrders->Companies->find('all', ['limit' => 200]);
			$quotationlists = $this->SalesOrders->Quotations->find()->where(['status'=>'Pending'])->order(['Quotations.id' => 'DESC']);
			
			$Items = $this->SalesOrders->Items->find()->order(['Items.name' => 'ASC'])->matching(
						'ItemCompanies', function ($q) use($st_company_id) {
							return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0])->orWhere(['ItemCompanies.company_id'=>$st_company_id,'ItemCompanies.freeze' => 1]);
						}
					);		
					
					
			
				
			$ItemsOptions=[];
			foreach($Items as $item){ 
						$ItemsOptions[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
			}		

			$Item_datas = $this->SalesOrders->Items->find()->order(['Items.name' => 'ASC'])->matching(
						'ItemCompanies', function ($q) use($st_company_id) {
							return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
						}
					);		
					
			$ItemsOptionsData=[];
			foreach($Item_datas as $item){ 
						$ItemsOptionsData[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
			}		
					
			////start unique validation and procees qty
			$SalesOrders = $this->SalesOrders->get($id, [
            'contain' => (['Invoices'=>['InvoiceRows' => function($q) {
				return $q->select(['invoice_id','sales_order_row_id','item_id','total_qty' => $q->func()->sum('InvoiceRows.quantity')])->group('InvoiceRows.sales_order_row_id');
			}],'SalesOrderRows'=>['Items']])
			]);
				
			$sales_orders_qty=[];
				foreach($SalesOrders->invoices as $invoices){ 
					foreach($invoices->invoice_rows as $invoice_row){ 
						$sales_orders_qty[@$invoice_row->sales_order_row_id]=@$sales_orders_qty[$invoice_row->sales_order_row_id]+$invoice_row->total_qty;
						@$invoice_row_id[$invoice_row->sales_order_row_id]=@$invoice_row->id;
						
					}
				}	

			////end unique validation and procees qty	
				
					
			$transporters = $this->SalesOrders->Carrier->find('list', ['limit' => 200])->order(['Carrier.transporter_name' => 'ASC']);
			$employees = $this->SalesOrders->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])->matching(
						'EmployeeCompanies', function ($q) use($st_company_id) {
							return $q->where(['EmployeeCompanies.company_id' => $st_company_id]);
						}
					);
			$termsConditions = $this->SalesOrders->TermsConditions->find('all',['limit' => 200]);
			//$SaleTaxes = $this->SalesOrders->SaleTaxes->find('all')->where(['SaleTaxes.freeze'=>0]);
			$Filenames = $this->SalesOrders->Filenames->find()->where(['customer_id' => $salesOrder->customer_id]);
			$SaleTaxes = $this->SalesOrders->SaleTaxes->find('all')->where(['SaleTaxes.freeze'=>0])->matching(
						'SaleTaxCompanies', function ($q) use($st_company_id) {
							return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
						} 
					);
					
			$QuotaionQty=[];$totalSalesOrderQty=[];$MaxQty=[];
			if($so->quotation_id>0)
			{
				$SalesOrdersDetail = $this->SalesOrders->get($id, [
				'contain' => (['SalesOrderRows','Quotations'=>['QuotationRows'=>['SalesOrderRows']]])
				 ]);
				
				if(!empty($SalesOrdersDetail->quotation->quotation_rows))
				{ 
					foreach($SalesOrdersDetail->quotation->quotation_rows as $quotation_row)
					{ 
						@$QuotaionQty[@$quotation_row->id]=@$quotation_row->quantity;
						if(!empty($quotation_row->sales_order_rows))
						{
							foreach($quotation_row->sales_order_rows as $sales_order_row)
							{
								@$totalSalesOrderQty[@$sales_order_row->quotation_row_id] +=@$sales_order_row->quantity;
							}
						}
					}
				} 
				
				foreach($SalesOrdersDetail->sales_order_rows as $sales_order_row)
				{
					@$MaxQty[@$sales_order_row->quotation_row_id] = @$QuotaionQty[@$sales_order_row->quotation_row_id]-@$totalSalesOrderQty[@$sales_order_row->quotation_row_id]+$sales_order_row->quantity;
					@$quotation_row_id[@$sales_order_row->quotation_row_id] =@$sales_order_row->quotation_row_id;
				}
			}
			//pr($MaxQty);exit;
			$this->set(compact('salesOrder', 'customers', 'quotation_row_id', 'companies','quotationlists','items','transporters','termsConditions','serviceTaxs','exciseDuty','employees','SaleTaxes','Filenames','financial_year_data','chkdate','qt_data','qt_data1','financial_year','sales_orders_qty','invoice_row_id','quotation_qty','current_so_rows','quotation_row_id','existing_quotation_rows','MaxQty','ItemsOptions','ItemsOptionsData'));
			$this->set('_serialize', ['salesOrder']);
			
			
			//pr($MaxQty);exit;
		}
		else
		{
			$this->Flash->error(__('This month is locked.'));
			return $this->redirect(['action' => 'index']);
		}
    }

    /**
     * Delete method
     *
     * @param string|null $id Sales Order id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $salesOrder = $this->SalesOrders->get($id);
        if ($this->SalesOrders->delete($salesOrder)) {
            $this->Flash->success(__('The sales order has been deleted.'));
        } else {
            $this->Flash->error(__('The sales order could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function gstSalesOrderAdd($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		
		$s_employee_id=$this->viewVars['s_employee_id'];
		
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$Company = $this->SalesOrders->Companies->get($st_company_id);
		//$st_year_id = $session->read('st_year_id');
		/* $SessionCheckDate = $this->FinancialYears->get($st_year_id);
	   $fromdate1 = date("Y-m-d",strtotime($SessionCheckDate->date_from));   
	   $todate1 = date("Y-m-d",strtotime($SessionCheckDate->date_to));  */
		
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->SalesOrders->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		
		
			   $SessionCheckDate = $this->FinancialYears->get($st_year_id);
			   $fromdate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_from));   
			   $todate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_to)); 
			   $tody1 = DATE("Y-m-d");

			   $fromdate = strtotime($fromdate1);
			   $todate = strtotime($todate1); 
			   $tody = strtotime($tody1);

			 if($fromdate > $tody || $todate < $tody)
			   {
				   $chkdate = 'Not Found';
			   }
			   else
			   {
				  $chkdate = 'Found';
			   }

			
				

		$quotation_id=$this->request->query('quotation');
		if(!empty($quotation_id)){
				$quotation_id = $this->EncryptingDecrypting->decryptData($quotation_id);
		}
		$quotation=array(); 
		$process_status='New';
		if(!empty($quotation_id)){
			$quotation = $this->SalesOrders->Quotations->get($quotation_id, [
				'contain' => ['QuotationRows.Items'
					],'Customers'=>['CustomerAddress' => function($q){
					return $q->where(['default_address'=>1]);
				}]
			]);
			$process_status='Pulled From Quotation';
		}
		
		$this->set(compact('quotation','process_status'));
		
		$sales_id=$this->request->query('copy');
		
		$job_id=$this->request->query('job');
		//pr($process_status); exit;
		
		if(!empty($sales_id)){ 
			$sales_id = $this->EncryptingDecrypting->decryptData($sales_id);
			$salesOrder_data = $this->SalesOrders->newEntity();
			
			$salesOrder = $this->SalesOrders->get($sales_id, [
				'contain' => ['Customers'=>['CustomerContacts'=>function($q){
				return $q
			->where(['CustomerContacts.default_contact'=>1]);
			}],'Employees','SalesOrderRows'=>['Items']]
			]);
			$process_status='Copy';
			
		}
		elseif(!empty($job_id)){
			$salesOrder = $this->SalesOrders->get($job_id, [
				'contain' => ['SalesOrderRows']
			]);
		}
		else{
			  $salesOrder_data = $this->SalesOrders->newEntity();
			}
			
		//$last_so_no=$this->SalesOrders->find()->select(['so2'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['so2' => 'DESC'])->first();
			 
			/* if($last_so_no){
				$salesOrder->so2=$last_so_no->so2+1;
			}else{
				$salesOrder->so2=1;
			} */
		//pr($last_so_no); exit;
      
        if ($this->request->is(['patch', 'post', 'put'])) {
			
			//$salesOrder = $this->SalesOrders->newEntity();
			
            $salesOrder = $this->SalesOrders->patchEntity($salesOrder_data, $this->request->data);
			$last_so_no=$this->SalesOrders->find()->select(['so2'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['so2' => 'DESC'])->first();
			$salesOrder->expected_delivery_date=date("Y-m-d",strtotime($salesOrder->expected_delivery_date)); 
			$salesOrder->po_date=date("Y-m-d",strtotime($salesOrder->po_date)); 
			$salesOrder->created_by=$s_employee_id; 
			if($last_so_no){
				$salesOrder->so2=$last_so_no->so2+1;
			}else{
				$salesOrder->so2=1;
			}
			//pr($salesOrder->so2); exit;
			
			$salesOrder->created_on=date("Y-m-d",strtotime($salesOrder->created_on));
			$salesOrder->edited_on=date("Y-m-d",strtotime($salesOrder->edited_on));
			$salesOrder->quotation_id=$quotation_id;
			$salesOrder->created_on_time= date("Y-m-d h:i:sA");
			$salesOrder->company_id=$st_company_id;
			$salesOrder->financial_year_id=$st_year_id;
			
			//$salesOrder->id=808;
			//$salesOrder->id = $this->EncryptingDecrypting->encryptData($salesOrder->id);
			//$status=$this->sendEmail($salesOrder->id);
			//pr($salesOrder->id); exit;
			if ($this->SalesOrders->save($salesOrder)) {
				
				$this->sendEmail($salesOrder->id);
				$status_close=$this->request->query('status');
				$totalSalesOrderIDs=[];
				foreach($salesOrder->sales_order_rows as $sales_order_row)
				{
					$totalSalesOrderIDs[$sales_order_row->quotation_row_id]=$sales_order_row->quotation_row_id;
				}
			
				if($status_close=='close')
				{
					$query = $this->SalesOrders->Quotations->query();
					$query->update()
						->set(['status' => 'Converted into Sales Order'])
						->where(['id' => $quotation_id])
						->execute();
				} 
				else if($status_close=='open')
				{
					
					$QuotationRows = $this->SalesOrders->Quotations->QuotationRows->find();
					$Quotations = $this->SalesOrders->Quotations->find();
					$Quotations->select(['id','total_quotaion_qty'=>$QuotationRows->func()->sum('QuotationRows.quantity')])
					->innerJoinWith('QuotationRows')
					->group(['Quotations.id'])->where(['Quotations.id'=>$quotation_id]);
					
					$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
					$SalesOrders = $this->SalesOrders->find();
					$SalesOrders->select(['id','total_sales_qty'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
					->innerJoinWith('SalesOrderRows')->where(['SalesOrderRows.quotation_row_id > '=> 0])
					->group(['SalesOrders.quotation_id'])->where(['SalesOrders.quotation_id'=>$quotation_id]);
					
					$total_quotaion_qty=$Quotations->first()->total_quotaion_qty;
					$total_sales_qty=$SalesOrders->first()->total_sales_qty;
					
				
					if(@$total_quotaion_qty!=@$total_sales_qty)
					{
						$query_pending = $this->SalesOrders->Quotations->query();
						$query_pending->update()
						->set(['status' => 'Pending'])
						->where(['id' => $salesOrder->quotation_id])
						->execute();
					}
					else if($total_quotaion_qty==$total_sales_qty)
					{
						$query_pending = $this->SalesOrders->Quotations->query();
						$query_pending->update()
						->set(['status' => 'Converted into Sales Order'])
						->where(['id' => $salesOrder->quotation_id])
						->execute();
					}
				}
					
				$salesOrder->id = $this->EncryptingDecrypting->encryptData($salesOrder->id);
				$this->Flash->success(__('The sales order has been saved.'));
				return $this->redirect(['action' => 'gstConfirm',$salesOrder->id]);

            } else {
                $this->Flash->error(__('The sales order could not be saved. Please, try again.'));
            }
        }
        $customers = $this->SalesOrders->Customers->find('all')->order(['Customers.customer_name' => 'ASC'])->contain(['CustomerAddress'=>function($q){
			return $q
			->where(['CustomerAddress.default_address'=>1]);
		}])->matching(
					'CustomerCompanies', function ($q) use($st_company_id) {
						return $q->where(['CustomerCompanies.company_id' => $st_company_id]);
					}
				);
		$copy=$this->request->query('copy');
		//pr ($copy); exit;
		if(!empty($copy)){
			$process_status='Copy';
		}
		//pr ($process_status); exit;
		if($quotation_id){
			$Filenames = $this->SalesOrders->Filenames->find()->where(['customer_id' => $quotation->customer_id]);
		}elseif($id){
			$Filenames = $this->SalesOrders->Filenames->find()->where(['customer_id' => $salesOrder->customer_id]);
		}else{
			$Filenames = $this->SalesOrders->Filenames->find();
		}
			
        $companies = $this->SalesOrders->Companies->find('all');
		$quotationlists = $this->SalesOrders->Quotations->find()->where(['status'=>'Pending'])->order(['Quotations.id' => 'DESC']);
		/* $items = $this->SalesOrders->Items->find('list')->matching(
					'ItemCompanies', function ($q) use($st_company_id) {
						return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
					} 
				)->order(['Items.name' => 'ASC']); */
				
		$Items = $this->SalesOrders->Items->find()->order(['Items.name' => 'ASC'])->matching(
						'ItemCompanies', function ($q) use($st_company_id) {
							return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0])->orWhere(['ItemCompanies.company_id'=>$st_company_id,'ItemCompanies.freeze' => 1]);
						}
					);		
					
					
			
				
			$ItemsOptions=[];
			foreach($Items as $item){ 
						$ItemsOptions[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
			}		

			$Item_datas = $this->SalesOrders->Items->find()->order(['Items.name' => 'ASC'])->matching(
						'ItemCompanies', function ($q) use($st_company_id) {
							return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
						}
					);		
					
			$ItemsOptionsData=[];
			foreach($Item_datas as $item){ 
						$ItemsOptionsData[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
			}					
		$transporters = $this->SalesOrders->Carrier->find('list')->order(['Carrier.transporter_name' => 'ASC']);
		//$employees = $this->SalesOrders->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC']);
		 $employees = $this->SalesOrders->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.company_id' => $st_company_id,'EmployeeCompanies.freeze' => 0]);
					}
				); 
		/* $EMP_ID =[23,16,17];
		if(in_array($s_employee_id,$EMP_ID)){
			 $employees = $this->SalesOrders->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.company_id' => $st_company_id,'EmployeeCompanies.freeze' => 0]);
					}
				);
		}else{
			$employees = $this->SalesOrders->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.company_id' => $st_company_id,'EmployeeCompanies.freeze' => 0]);
					}
				)->where(['Employees.id'=>$s_employee_id]);
		} */			
		$termsConditions = $this->SalesOrders->TermsConditions->find('all');
		$SaleTaxes = $this->SalesOrders->SaleTaxes->find('all')->where(['SaleTaxes.freeze'=>0])->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				);
		$GstTaxes = $this->SalesOrders->SaleTaxes->find()->where(['SaleTaxes.account_second_subgroup_id'=>5])->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				);
		
			$QuotaionQty=[];$totalSalesOrderQty=[];$MaxQty=[];
			if(!empty(@$quotation_id))
			{
				$Quotations = $this->SalesOrders->Quotations->get($quotation_id, [
				'contain' => (['SalesOrders'=>['SalesOrderRows'],'QuotationRows'])
				 ]);
				if(!empty($Quotations->sales_orders))
				{ 
					foreach($Quotations->sales_orders as $sales_order)
					{
						if(!empty($sales_order->sales_order_rows))
						{
							foreach($sales_order->sales_order_rows as $sales_order_row)
							{
								@$totalSalesOrderQty[@$sales_order_row->quotation_row_id] +=@$sales_order_row->quantity;
							}
						}
					}
				}
				foreach($Quotations->quotation_rows as $quotation_row)
				{
					@$QuotaionQty[@$quotation_row->id]=@$quotation_row->quantity;
				}
				
				foreach($Quotations->quotation_rows as $quotation_row)
				{
					@$MaxQty[@$quotation_row->id] = @$QuotaionQty[@$quotation_row->id]-@$totalSalesOrderQty[@$quotation_row->id];
				}
			}
		//pr($salesOrder); exit; 
        $this->set(compact('salesOrder', 'customers', 'MaxQty','companies','quotationlists','Items','transporters','Filenames','termsConditions','serviceTaxs','exciseDuty','employees','SaleTaxes','copy','process_status','Company','chkdate','financial_year','sales_id','salesOrder_copy','job_id','salesOrder_data','GstTaxes','sales_orders_qty','ItemsOptions','ItemsOptionsData','fromdate1','todate1'));
        $this->set('_serialize', ['salesOrder']);
    }

	public function sendEmail($id=null){ 
		$salesOrder = $this->SalesOrders->get($id, [
            'contain' => ['Companies','Carrier','Creator','Courier','Customers'=>['CustomerAddress','Employees']]
        ]);
		
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$company_data=$this->SalesOrders->Companies->get($st_company_id);
		$x=$this->pdfDownload($id);
		$email = new Email('default');
		$email->transport('gmail');
		$email_to1=$salesOrder->dispatch_email;
		$email_to2=$salesOrder->dispatch_email2;
		$email_to3=$salesOrder->dispatch_email3;
		//$email_to='dimpaljain892@gmail.com';
		$cc_mail=@$salesOrder->customer->employee->company_email;
		//$cc_mail='dimpaljain892@gmail.com';
		//exit;
		$name='last_so'; 
		$attachments='';
		$attachments='Invoice_email/'.$name.'.pdf';
		$email_tos=[$email_to1,$email_to2,$email_to3];
		//pr($email_to);
		//pr($cc_mail); exit;
		//$email_to="gopalkrishanp3@gmail.com";
		//$cc_mail="dimpaljain892@gmail.com";
		//$member_name="Gopal";
		$from_name=$company_data->alias;
		$sub="Purchase order acknowledgement";
	foreach($email_tos as $email_to){ 
		if(!empty($email_to)){		
			try { 
				$email->from(['dispatch@mogragroup.com' => $from_name])
				->to($email_to)
				->cc($cc_mail)
				->replyTo('dispatch@mogragroup.com')
				->subject($sub)
				->template('send_sales_order')
				->emailFormat('html')
				->viewVars(['salesOrder'=>$salesOrder,'email_to'=>$email_to])
				->attachments($attachments); // pr($salesOrder); exit;
			} catch (Exception $e) {
					echo 'Exception : ',  $e->getMessage(), "\n";
				}	
			$email->send();
		}	
	}
		return;
	}
	
	public function gstSalesOrderEdit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$id = $this->EncryptingDecrypting->decryptData($id);
		$so = $this->SalesOrders->get($id); //pr($salesOrder->quotation_id);exit;
		if($so->quotation_id > 0){ 
			$salesOrder = $this->SalesOrders->get($id, [
            'contain' => ['Quotations'=>['QuotationRows'],'SalesOrderRows' => ['Items','JobCardRows'],'Invoices' => ['InvoiceRows']]
        ]);
		}
		else{
			$salesOrder = $this->SalesOrders->get($id, [
            'contain' => ['SalesOrderRows' => ['Items','JobCardRows'],'Invoices' => ['InvoiceRows']]
        ]);
		}
		//pr($salesOrder); exit;
		$qt_data=[];
		$qt_data1=[];
		
		if($so->quotation_id>0){
		$session = $this->request->session();
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->SalesOrders->FinancialYears->find()->where(['id'=>$st_year_id])->first();
			foreach($salesOrder->quotation->quotation_rows as $quotation_row){
				$qt_data[$quotation_row->item_id]=$quotation_row->quantity;
				//$qt_data1[$quotation_row->item_id]=$quotation_row->proceed_qty;
			}
		}
//pr($qt_data1); exit;
		$closed_month=$this->viewVars['closed_month'];
		
		if(!in_array(date("m-Y",strtotime($salesOrder->created_on)),$closed_month))
		{

			$Em = new FinancialYearsController;
			$financial_year_data = $Em->checkFinancialYear($salesOrder->created_on);
			

			$s_employee_id=$this->viewVars['s_employee_id'];
			
			$session = $this->request->session();
			$st_company_id = $session->read('st_company_id');
			$st_year_id = $session->read('st_year_id');
			
			   $SessionCheckDate = $this->FinancialYears->get($st_year_id);
			   $fromdate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_from));   
			   $todate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_to)); 
			   $tody1 = DATE("Y-m-d");

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
				$salesOrder = $this->SalesOrders->patchEntity($salesOrder, $this->request->data);
				
				$salesOrder->expected_delivery_date=date("Y-m-d",strtotime($salesOrder->expected_delivery_date));
				$salesOrder->po_date=date("Y-m-d",strtotime($salesOrder->po_date)); 
				$salesOrder->date=date("Y-m-d",strtotime($salesOrder->date));
				$salesOrder->edited_by=$s_employee_id;
				$salesOrder->edited_on=date("Y-m-d");
				$salesOrder->edited_on_time= date("Y-m-d h:i:sA");
				//pr($salesOrder); exit;
				if ($this->SalesOrders->save($salesOrder)) {
					
					if($so->quotation_id>0)
					{
						$QuotationRows = $this->SalesOrders->Quotations->QuotationRows->find();
						$Quotations = $this->SalesOrders->Quotations->find();
						$Quotations->select(['id','total_quotaion_qty'=>$QuotationRows->func()->sum('QuotationRows.quantity')])
						->innerJoinWith('QuotationRows')
						->group(['Quotations.id'])->where(['Quotations.id'=>$so->quotation_id]);
						
						$SalesOrderRows = $this->SalesOrders->SalesOrderRows->find();
						
						$SalesOrders = $this->SalesOrders->find();
						
						$SalesOrders->select(['id','total_sales_qty'=>$SalesOrderRows->func()->sum('SalesOrderRows.quantity')])
						->innerJoinWith('SalesOrderRows')->where(['SalesOrderRows.quotation_row_id > '=> 0])
						->group(['SalesOrders.quotation_id'])
						->where(['SalesOrders.quotation_id'=>$so->quotation_id]);
						
						
						/* $pushNotifications = $this->PushNotifications->find()->contain(['PushNotificationCustomers' => function($q) {
								$q->select([
									 'PushNotificationCustomers.push_notification_id',
									 'count_customer' => $q->func()->count('PushNotificationCustomers.push_notification_id')
								])
								->group(['PushNotificationCustomers.push_notification_id']);

								return $q;
							}]); */
						
						$total_quotaion_qty=$Quotations->first()->total_quotaion_qty;
						@$total_sales_qty=$SalesOrders->first()->total_sales_qty;
					
						/* pr($SalesOrderRows->toArray());
						pr($SalesOrders->toArray());
						pr($total_quotaion_qty);
						pr($total_sales_qty); exit; */
						
						if(@$total_quotaion_qty!=@$total_sales_qty)
						{
							$query_pending = $this->SalesOrders->Quotations->query();
							$query_pending->update()
							->set(['status' => 'Pending'])
							->where(['id' => $so->quotation_id])
							->execute();
						}
						else if($total_quotaion_qty==$total_sales_qty)
						{
							$query_pending = $this->SalesOrders->Quotations->query();
							$query_pending->update()
							->set(['status' => 'Converted into Sales Order'])
							->where(['id' => $so->quotation_id])
							->execute();
						}
							
					}
					$salesOrder->job_card_status='Pending';
					$query2 = $this->SalesOrders->query();
					$query2->update()
						->set(['job_card_status' => 'Pending'])
						->where(['id' => $id])
						->execute();
					$salesOrder->id = $this->EncryptingDecrypting->encryptData($salesOrder->id);
					$this->Flash->success(__('The sales order has been saved.'));
					return $this->redirect(['action' => 'gstConfirm',$salesOrder->id]);
				} else { 
					$this->Flash->error(__('The sales order could not be saved. Please, try again.'));
				}
			}
			$customers = $this->SalesOrders->Customers->find('all')->order(['Customers.customer_name' => 'ASC'])->contain(['CustomerAddress'=>function($q){
				return $q
				->where(['CustomerAddress.default_address'=>1]);
			}])->matching(
						'CustomerCompanies', function ($q) use($st_company_id) {
							return $q->where(['CustomerCompanies.company_id' => $st_company_id]);
						}
					);
			$companies = $this->SalesOrders->Companies->find('all', ['limit' => 200]);
			$quotationlists = $this->SalesOrders->Quotations->find()->where(['status'=>'Pending'])->order(['Quotations.id' => 'DESC']);
			$Items = $this->SalesOrders->Items->find()->order(['Items.name' => 'ASC'])->matching(
						'ItemCompanies', function ($q) use($st_company_id) {
							return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0])->orWhere(['ItemCompanies.company_id'=>$st_company_id,'ItemCompanies.freeze' => 1]);
						}
					);		
					
					
			
				
			$ItemsOptions=[];
			foreach($Items as $item){ 
						$ItemsOptions[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
			}		

			$Item_datas = $this->SalesOrders->Items->find()->order(['Items.name' => 'ASC'])->matching(
						'ItemCompanies', function ($q) use($st_company_id) {
							return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
						}
					);		
					
			$ItemsOptionsData=[];
			foreach($Item_datas as $item){ 
						$ItemsOptionsData[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
			}			
			$transporters = $this->SalesOrders->Carrier->find('list', ['limit' => 200])->order(['Carrier.transporter_name' => 'ASC']);
			 $employees = $this->SalesOrders->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])->matching(
						'EmployeeCompanies', function ($q) use($st_company_id) {
							return $q->where(['EmployeeCompanies.company_id' => $st_company_id]);
						}
					); 
				/* $EMP_ID =[23,16,17];
				if(in_array($s_employee_id,$EMP_ID)){
					$employees = $this->SalesOrders->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
					return $q->where(['EmployeeCompanies.company_id' => $st_company_id,'EmployeeCompanies.freeze' => 0]);
					}
					);
				}else{
					$employees = $this->SalesOrders->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
					return $q->where(['EmployeeCompanies.company_id' => $st_company_id,'EmployeeCompanies.freeze' => 0]);
					}
					)->where(['Employees.id'=>$s_employee_id]);
				} */			
			$termsConditions = $this->SalesOrders->TermsConditions->find('all',['limit' => 200]);
			//$SaleTaxes = $this->SalesOrders->SaleTaxes->find('all')->where(['SaleTaxes.freeze'=>0]);
			$Filenames = $this->SalesOrders->Filenames->find()->where(['customer_id' => $salesOrder->customer_id]);
			
			$GstTaxes = $this->SalesOrders->SaleTaxes->find()->where(['SaleTaxes.account_second_subgroup_id'=>5])->matching(
					'SaleTaxCompanies', function ($q) use($st_company_id) {
						return $q->where(['SaleTaxCompanies.company_id' => $st_company_id]);
					} 
				);
				
			////start unique validation and procees qty
			$SalesOrders = $this->SalesOrders->get($id, [
            'contain' => (['Invoices'=>['InvoiceRows' => function($q) {
				return $q->select(['invoice_id','sales_order_row_id','item_id','total_qty' => $q->func()->sum('InvoiceRows.quantity')])->group('InvoiceRows.sales_order_row_id');
			}],'SalesOrderRows'=>['Items']])
        ]);
			
		$sales_orders_qty=[];
			foreach($SalesOrders->invoices as $invoices){ 
				foreach($invoices->invoice_rows as $invoice_row){ 
					$sales_orders_qty[@$invoice_row->sales_order_row_id]=@$sales_orders_qty[$invoice_row->sales_order_row_id]+$invoice_row->total_qty;
					
				}
			}	
			$QuotaionQty=[];$totalSalesOrderQty=[];$MaxQty=[];
			if($so->quotation_id>0)
			{
				$SalesOrdersDetail = $this->SalesOrders->get($id, [
				'contain' => (['SalesOrderRows','Quotations'=>['QuotationRows'=>['SalesOrderRows']]])
				 ]);
				
				if(!empty($SalesOrdersDetail->quotation->quotation_rows))
				{ 
					foreach($SalesOrdersDetail->quotation->quotation_rows as $quotation_row)
					{ 
						@$QuotaionQty[@$quotation_row->id]=@$quotation_row->quantity;
						if(!empty($quotation_row->sales_order_rows))
						{
							foreach($quotation_row->sales_order_rows as $sales_order_row)
							{
								@$totalSalesOrderQty[@$sales_order_row->quotation_row_id] +=@$sales_order_row->quantity;
							}
						}
					}
				} 
				
				foreach($SalesOrdersDetail->sales_order_rows as $sales_order_row)
				{
					@$MaxQty[@$sales_order_row->quotation_row_id] = @$QuotaionQty[@$sales_order_row->quotation_row_id]-@$totalSalesOrderQty[@$sales_order_row->quotation_row_id]+$sales_order_row->quantity;
					@$quotation_row_id[@$sales_order_row->quotation_row_id] =@$sales_order_row->quotation_row_id;
				}
			}
			////end unique validation and procees qty
			$this->set(compact('salesOrder', 'customers', 'MaxQty', 'quotation_row_id', 'companies','quotationlists','items','transporters','termsConditions','serviceTaxs','exciseDuty','employees','SaleTaxes','Filenames','financial_year_data','chkdate','qt_data','qt_data1','financial_year','GstTaxes','sales_orders_qty','ItemsOptions','ItemsOptionsData','fromdate1','todate1'));
			$this->set('_serialize', ['salesOrder']);
		}
		else
		{
			$this->Flash->error(__('This month is locked.'));
			return $this->redirect(['action' => 'index']);
		}
    }
	
	public function gstConfirm($id = null)
    {
		$this->viewBuilder()->layout('pdf_layout');
		$id = $this->EncryptingDecrypting->decryptData($id);
		$session = $this->request->session();
		$st_year_id = $session->read('st_year_id');
		$salesorder = $this->SalesOrders->get($id, [
            'contain' => ['SalesOrderRows']
			]);
		if ($this->request->is(['patch', 'post', 'put'])) {
            foreach($this->request->data['sales_order_rows'] as $sales_order_row_id=>$value){
				$salesOrderRow=$this->SalesOrders->SalesOrderRows->get($sales_order_row_id);
				$salesOrderRow->height=$value["height"];
				$this->SalesOrders->SalesOrderRows->save($salesOrderRow);
			}
			$id = $this->EncryptingDecrypting->encryptData($id);
			return $this->redirect(['action' => 'gstConfirm',$id]);
        }
		
		$this->set(compact('salesorder','id','st_year_id'));
    }
	

	public function getClosedQuotations(){
		
		$Quotations =$this->SalesOrders->Quotations->find()->where(['Quotations.status' =>'Closed']);
		//pr(count($Quotations));exit;
		$data=[];$i=1;
		foreach($Quotations as $quotation){
			
			$SalesOrdersData = $this->SalesOrders->exists(['quotation_id' => $quotation->id]);
			
			if($SalesOrdersData){
				$Quotation = $this->SalesOrders->Quotations->get(['id' => $quotation->id]);
				$data[] = $Quotation->id ;
			}
			
			
		}
		pr($data);exit;
	}
	
	public function showPendingItemForInvoice(){
			$this->viewBuilder()->layout('index_layout');
			$session = $this->request->session();
			$st_company_id = $session->read('st_company_id');
			
			$SalesOrders = $this->SalesOrders->find()->contain(['SalesOrderRows'=>['InvoiceRows' => function($q) {
				return $q->select(['invoice_id','sales_order_row_id','item_id','total_qty' => $q->func()->sum('InvoiceRows.quantity')])->group('InvoiceRows.sales_order_row_id');
			}]])->where(['SalesOrders.company_id'=>$st_company_id])->where(['SalesOrders.sales_order_status !='=>"Close"]);
			
			$sales_order_qty=[];
			$invoice_qty=[];
			$itemName=[];
			$itemSoQty=[];

			foreach($SalesOrders as $SalesOrder){ $sales_qty=[]; $inc_qty=[]; 
				foreach($SalesOrder->sales_order_rows as $sales_order_row){ 
					foreach($sales_order_row->invoice_rows as $invoice_row){ //pr($invoice_row); exit;
						@$invoice_qty[$invoice_row['item_id']]+=$invoice_row['total_qty'];
						@$inc_qty[$invoice_row['item_id']]+=$invoice_row['total_qty'];
					}
					@$sales_order_qty[$sales_order_row['item_id']]+=$sales_order_row['quantity'];
					@$sales_qty[$sales_order_row['item_id']]+=$sales_order_row['quantity'];
				}
				
				
				foreach(@$sales_qty as $key=>$sales_order_qt){
						if(@$sales_order_qt > @$inc_qty[$key] ){ 
							$pen=@$sales_order_qt-@$inc_qty[$key];
								$itm= $this->SalesOrders->SalesOrderRows->Items->get($key);
								@$itemName[$key]=$itm->name;
								@$itemSoQty[$key]+=$pen;
						}
				}
			}
			
			$ItemLedgers = $this->SalesOrders->SalesOrderRows->Items->ItemLedgers->find();
				$totalInCase = $ItemLedgers->newExpr()
					->addCase(
						$ItemLedgers->newExpr()->add(['in_out' => 'In']),
						$ItemLedgers->newExpr()->add(['quantity']),
						'integer'
					);
				$totalOutCase = $ItemLedgers->newExpr()
					->addCase(
						$ItemLedgers->newExpr()->add(['in_out' => 'Out']),
						$ItemLedgers->newExpr()->add(['quantity']),
						'integer'
					);

				$ItemLedgers->select([
					'total_in' => $ItemLedgers->func()->sum($totalInCase),
					'total_out' => $ItemLedgers->func()->sum($totalOutCase),'id','item_id'
				])
				->group('item_id')
				->autoFields(true)
				->where(['company_id'=>$st_company_id])
				->contain(['Items' => function($q) use($st_company_id){
					return $q->where(['Items.source'=>'Purchessed/Manufactured'])->orWhere(['Items.source'=>'Purchessed'])->contain(['ItemCompanies'=>function($p) use($st_company_id){
						return $p->where(['company_id'=>$st_company_id,'ItemCompanies.freeze' => 0]);
					}]);
				}]);
			
			foreach ($ItemLedgers as $itemLedger){ 
				$Current_Stock[$itemLedger->item->id]=$itemLedger->total_in-$itemLedger->total_out;
			}
		
		$this->set(compact('itemSoQty','Current_Stock','itemName'));
					
		
	}
	
	
	public function showPendingItem($id=null){
		
			$SalesOrders = $this->SalesOrders->find()->contain(['SalesOrderRows'=>['InvoiceRows' => function($q) {
				return $q->select(['invoice_id','sales_order_row_id','item_id','total_qty' => $q->func()->sum('InvoiceRows.quantity')])->group('InvoiceRows.sales_order_row_id');
			}]])->where(['SalesOrders.id'=>$id])->where(['SalesOrders.sales_order_status !='=>"Close"]);
			
			$sales_order_qty=[];
			 $invoice_qty=[];
			$salesData=[];
			
				foreach($SalesOrders as $SalesOrder){ $sales_qty=[]; $inc_qty=[]; 
					foreach($SalesOrder->sales_order_rows as $sales_order_row){ 
						foreach($sales_order_row->invoice_rows as $invoice_row){ //pr($invoice_row); exit;
							@$invoice_qty[$invoice_row['item_id']]+=$invoice_row['total_qty'];
							@$inc_qty[$invoice_row['item_id']]+=$invoice_row['total_qty'];
						}
						@$sales_order_qty[$sales_order_row['item_id']]+=$sales_order_row['quantity'];
						@$sales_qty[$sales_order_row['item_id']]+=$sales_order_row['quantity'];
					}
					
					
					foreach(@$sales_qty as $key=>$sales_order_qt){
							if(@$sales_order_qt > @$inc_qty[$key] ){ 
								$pen=@$sales_order_qt-@$inc_qty[$key];
								$itm= $this->SalesOrders->SalesOrderRows->Items->get($key);
								@$salesData[$itm->name]=$pen;
							}
					}
				}
		
		$this->set(compact('salesData'));
					
		
	}
	public function getproceedqty(){
		$SalesOrders = $this->SalesOrders->SalesOrderRows->find()->where(['processed_quantity > quantity']);
		$data=[];
		foreach($SalesOrders as $salesorders){
			$data[$salesorders->id]=$salesorders->sales_order_id;
		}
		pr($data);exit;
	}
	
	public function pdfDownload($id=null){ //echo "sdfds"; exit;
		$Number = new NumberHelper(new \Cake\View\View());
		$Html = new HtmlHelper(new \Cake\View\View());
		$Text = new TextHelper(new \Cake\View\View());

		 $salesOrder = $this->SalesOrders->get($id, [
            'contain' => ['Customers', 'Companies','Carrier','Creator','Editor','Courier','Employees','SalesOrderRows' => function($q){
				return $q->order(['SalesOrderRows.id' => 'ASC'])->contain(['Items'=>['Units']]);
			}]
        ]);
		
		$cgst_per=[];
		$sgst_per=[];
		$igst_per=[];
		foreach($salesOrder->sales_order_rows as $sales_order_row){
			if($sales_order_row->cgst_per){
				$cgst_per[$sales_order_row->id]=$this->SalesOrders->SaleTaxes->get(@$sales_order_row->cgst_per);
			}
			if($sales_order_row->sgst_per){
				$sgst_per[$sales_order_row->id]=$this->SalesOrders->SaleTaxes->get(@$sales_order_row->sgst_per);
			}
			if($sales_order_row->igst_per){
				$igst_per[$sales_order_row->id]=$this->SalesOrders->SaleTaxes->get(@$sales_order_row->igst_per);
			}
		}
	
require_once(ROOT . DS  .'vendor' . DS  . 'dompdf' . DS . 'autoload.inc.php');
		
		$options = new Options();
		$options->set('defaultFont', 'Lato-Hairline');
		$dompdf = new Dompdf($options);

		$dompdf = new Dompdf();

		//$html = pr($salesOrder);
		$html = '
		<html>
		<head>
		  <style>
			 @page { margin: 150px 15px 10px 30px; }

		  body{
			line-height: 20px;
			}
			
			#header { position:fixed; left: 0px; top: -150px; right: 0px; height: 150px;}
			
			#content{
			position: relative; 
			}
			
			@font-face {
				font-family: Lato;
				src: url("https://fonts.googleapis.com/css?family=Lato");
			}
			p{
				margin:0;font-family: Lato;font-weight: 100;line-height: 12px !important;margin-top:-9px;
			}
			.odd td p{
				margin:0;font-family: Lato;font-weight: 100;line-height: 17px !important;margin-bottom: -1px;
			}
			.show td p{
					margin:0;font-family: Lato;font-weight: 100;line-height: 17px !important;
			}
			.topdata p{
				margin:0;font-family: Lato;font-weight: 100;line-height: 17px !important;margin-bottom: 1px;
			}
			.even p{
					margin: 0;
					font-family: Lato;
					font-weight: 100;
					line-height: 18px !important;
			}
			table td{
				margin:0;font-family: Lato;font-weight: 100;padding:0;line-height: 1;
			}
			table.table_rows tr.odd{
				page-break-inside: avoid;
			}
			.table_rows, .table_rows th, .table_rows td {
				border: 1px solid  #000; 
				border-collapse: collapse;
				padding:2px; 
			}
			.itemrow tbody td{
				border-bottom: none;border-top: none;
			}
			
			.table2 td{
				border: 0px solid  #000;font-size: 14px;padding:0px; 
			}
			.table_top td{
				font-size: 12px !important; 
			}
			.table-amnt td{
				border: 0px solid  #000;padding:0px; 
			}
			.table_rows th{
				border: 1px solid  #000;
				font-size: 16px !important;px
			}
			.table_rows td{
				border: 1px solid  #000;
				font-size: 16px !important;
			}
			.avoid_break{
				page-break-inside: avoid;
			}
			.table-bordered{
				border: hidden;
			}
			table.table-bordered td {
				border: hidden;
			}
			</style>
		<body>
		  <div id="header">
				<table width="100%">
					<tr>
						<td width="35%" rowspan="2">
						<img src='.ROOT . DS  . 'webroot' . DS  .'logos/'.$salesOrder->company->logo.' height="80px" style="height:80px;"/>
						</td>
						<td colspan="2" align="right">
						<+>'. h($salesOrder->company->name) .'</span>
						</td>
					</tr>
					<tr>
						<td width="30%" valign="top">
						<div align="center" style="font-size: 28px;font-weight: bold;color: #0685a8;">SALES ORDER</div>
						</td>
						<td align="right" width="35%" style="font-size: 12px;">
						<span>'. $Text->autoParagraph(h($salesOrder->company->address)) .'</span>
						<span><img src='.ROOT . DS  . 'webroot' . DS  .'img/telephone.gif height="11px" style="height:11px;margin-top:5px;"/> '. h($salesOrder->company->mobile_no).'</span> | 
						<span><img src='.ROOT . DS  . 'webroot' . DS  .'img/email.png height="15px" style="height:15px;margin-top:4px;"/> '. h($salesOrder->company->email).'</span>
						</td>
					</tr>
					<!--<tr>
						<td colspan="3" >
							<div style="border:solid 2px #0685a8;margin-top: 5px; margin-top:15px;"></div>
						</td>
					</tr>-->
				</table>
		  </div>
		  
		  <div id="content"> ';
		  
		$html.='
	<table width="100%" class="table_rows itemrow" style="margin-top: -22px;" >
		<thead>
			<tr class="show">
				<td align="">';
				$html.='
					<table  valign="center" width="100%"  class="table2">
						<tr>
							<td width="50%" valign="top" text-align="right" >
								<span><b>'. h(($salesOrder->customer->customer_name)) .'</b></span><br/>
								'. $Text->autoParagraph(h($salesOrder->customer_address)) .'
								
							</td>
							<td style="white-space:nowrap" width="30%" valign="top" text-align="right">
								<table width="100%">
									<tr>
										<td valign="top" style="vertical-align: top;" width="5%" >Sales Order No</td>
										<td valign="top" width="4%">:</td>
										<td valign="top">'. h(($salesOrder->so1."/SO-".str_pad($salesOrder->so2, 3, "0", STR_PAD_LEFT)."/".$salesOrder->so3."/".$salesOrder->so4)) .'</td>
									</tr>
									<tr>
										<td valign="top" style="vertical-align: top;">Date</td>
										<td valign="top">:</td>
										<td valign="top">'. h(date("d-m-Y",strtotime($salesOrder->created_on))) .'</td>
									</tr>
									
								</table>
							</td>
						</tr>
						
					</table>
				</td>
			</tr>	
			<tr>
				<td style="font-size:12px; border-top:1px solid #000;" >
				Customer P.O. No. '. h($salesOrder->customer_po_no) .' dated '. h(date("d-m-Y",strtotime($salesOrder->po_date))) .'
				</td>
			</tr>			
	</thead>
</table>';
 foreach ($salesOrder->sales_order_rows as $salesOrderRows)
 {
	 if($salesOrderRows->cgst_per > 0){ $cgst=1;}
	 if($salesOrderRows->sgst_per > 0){ $sgst=1;}
	 if($salesOrderRows->igst_per > 0){ $igst=1;}
}
$html.='
<table width="100%" class="table_rows">
		<tr>
			<th style="text-align: bottom;" rowspan="2">S No</th>
			<th rowspan="2">Item</th>
			<th rowspan="2">Unit</th>
			<th rowspan="2">Quantity</th>
			<th rowspan="2">Rate</th>
			<th rowspan="2">Amount</th>
			<th style="text-align: center;" colspan="2" >Discount</th>
			<th style="text-align: center;" colspan="2" >P&F </th>
			<th style="text-align: center;" rowspan="2" >Taxable Value</th>';
			if(@$cgst)
			{
			   $colspan=15;
			   $html .='<th style="text-align: center;" colspan="2">CGST</th>';
			}
			if(@$sgst)
			{
			   $html .='<th style="text-align: center;" colspan="2" >SGST</th>';
			}
			if(@$igst)
			{
				$colspan=13;
			   $html .='<th style="text-align: center;" colspan="2" >IGST</th>';
			}
			
			$html .='<th style="text-align: center;" rowspan="2" >Total</th>
			</tr>
			<tr> <th style="text-align: center;" > %</th>
				<th style="text-align: center;">Amt</th>
				<th style="text-align: center;" > %</th>
				<th style="text-align: center;" >Amt</th>';
				if(@$cgst)
			    {
					$html .='<th style="text-align: center;" > %</th>
							 <th style="text-align: center;" >Amt</th>';
				}
				if(@$sgst)
			    {
					$html .='<th style="text-align: center;" > %</th>
				             <th style="text-align: center;" >Amt</th>';
				}
				if(@$igst)
			    {
					$html .='<th style="text-align: center; " >%</th>
				             <th style="text-align: center;" >Amt</th>';
				}
				
				$html .='</tr>';
$sr=0; $h="-"; foreach ($salesOrder->sales_order_rows as $salesOrderRows): $sr++; 
$html.='
	<tr class="odd">
	    <td style="padding-top:8px;padding-bottom:5px;" valign="top" align="center">'. h($sr) .'</td>
		<td style="padding-top:8px;" class="even" width="100%">';
		
		if(!empty($salesOrderRows->description)){
			$html.= h($salesOrderRows->item->name).$salesOrderRows->description.'<div style="height:'.$salesOrderRows->height.'"></div>'
		;
		}else{
			$html.= h($salesOrderRows->item->name).'<div style="height:'.$salesOrderRows->height.'"></div> ';
		}
		
		$html.='</td>
		<td align="right" valign="top"  style="padding-top:10px;">'. h($salesOrderRows->item->unit->name) .'</td>
		<td style="padding-top:8px;padding-bottom:5px;" valign="top" align="center">'. h($salesOrderRows->quantity) .'</td>
		<td style="padding-top:8px;padding-bottom:5px;" align="right" valign="top">'. $Number->format($salesOrderRows->rate,[ 'places' => 2]) .'</td>
		<td style="padding-top:8px;padding-bottom:5px;" align="right" valign="top">'. $Number->format($salesOrderRows->amount,[ 'places' => 2]) .'</td>';
		if($salesOrderRows->discount==0){
		$html.='<td align="center" valign="top" style="padding-top:10px;">'. h($h) .'</td>
		<td align="center" valign="top" style="padding-top:10px;">'. h($h) .'</td>';
		}else{
			$html.='<td align="center" valign="top" style="padding-top:10px;">'. h($salesOrderRows->discount_per) .'</td>
		<td align="center" valign="top" style="padding-top:10px;">'. $Number->format($salesOrderRows->discount,[ 'places' => 2]) .'</td>';
		}
		if($salesOrderRows->pnf==0){
		$html.='<td align="center" valign="top" style="padding-top:10px;">'. h($h) .'</td>
		<td align="center" valign="top" style="padding-top:10px;">'. h($h) .'</td>';
		}else{
			$html.='<td align="center" valign="top" style="padding-top:10px;">'. h($salesOrderRows->pnf_per) .'</td>
		<td align="center" valign="top" style="padding-top:10px;">'. $Number->format($salesOrderRows->pnf,[ 'places' => 2]) .'</td>';
		}
		
		$html.='<td align="center" valign="top" style="padding-top:10px;">'. h($salesOrderRows->taxable_value) .'</td>';
		if($salesOrderRows->cgst_per > 0){ 
		$html.='<td style="padding-top:8px;padding-bottom:5px;" align="right" valign="top">';
            if($cgst_per[$salesOrderRows->id]['tax_figure'] >= 0)
			{
				$html.=$cgst_per[$salesOrderRows->id]['tax_figure'].'%';
			}
			$html.='</td><td style="padding-top:8px;padding-bottom:5px;" align="right" valign="top">'. $Number->format($salesOrderRows->cgst_amount,['places'=>2]) .'</td>';
		}
		
		if($salesOrderRows->sgst_per > 0){ 
		$html.='<td style="padding-top:8px;padding-bottom:5px;" align="right" valign="top">';
			if($sgst_per[$salesOrderRows->id]['tax_figure'] >= 0)
			{
				$html.=$sgst_per[$salesOrderRows->id]['tax_figure'].'%';
			}
		$html.='</td><td style="padding-top:8px;padding-bottom:5px;" align="right" valign="top">'. $Number->format($salesOrderRows->sgst_amount,['places'=>2]) .'</td>';
		}
		
		if($salesOrderRows->igst_per > 0){ 
		$html.='<td style="padding-top:8px;padding-bottom:5px;" align="right" valign="top">';
			if($igst_per[$salesOrderRows->id]['tax_figure'] >= 0)
			{
				$html.=$igst_per[$salesOrderRows->id]['tax_figure'].'%';
			}
			$html.='</td><td style="padding-top:8px;padding-bottom:5px;" align="right" valign="top">'. $Number->format($salesOrderRows->igst_amount,['places'=>2]) .'</td>';
		}
		
		$html.='</td><td style="padding-top:8px;padding-bottom:5px;" align="right" valign="top">'. $Number->format($salesOrderRows->total,['places'=>2]) .'</td>
	</tr>';
	
endforeach;

if($salesOrder->discount_type=='1'){ $discount_text='Discount @ '.$salesOrder->discount_per.'%'; }else{ $discount_text='Discount'; }
if($salesOrder->pnf_type=='1'){ $pnf_text='P&F @ '.$salesOrder->pnf_per.'%'; }else{ $pnf_text='P&F'; }
$html.='</table>';		
$html.='
<table width="100%" class="table_rows">
	<tbody>';
		if(!empty($salesOrder->discount)){
		$html.='<tr>
					<td style="text-align:right;">'.$discount_text.'</td>
					<td style="text-align:right;" width="104">'. $Number->format($salesOrder->discount,[ 'places' => 2]).'</td>
				</tr>';
		}
		$html.='<tr>
				<td style="text-align:right;">Total</td>
				<td style="text-align:right;" width="125">'. $Number->format($salesOrder->total,[ 'places' => 2]).'</td>
			</tr>';
		if(!empty($salesOrder->pnf)){
		$html.='<tr>
					<td style="text-align:right;">Total after P&F</td>
					<td style="text-align:right;" width="104">'. $Number->format($salesOrder->total_after_pnf,[ 'places' => 2]).'</td>
				</tr>';
		}
			
			
		$html.='</tbody>
	</table>'; 
	
$html.='
	<table width="100%" class="table_rows">
		<tr>
			<td width="60%" valign="top">
				<table class="table2" width="100%">
					<tr>
						<td width="" style="white-space: nowrap;">Transporter</td>
						<td style="white-space: nowrap;">:</td>
						<td width="40%"> '. h($salesOrder->carrier->transporter_name) .'</td>
					</tr>
					<tr>
						<td valign="top">Carrier</td>
						<td>:</td>
						<td style="white-space: nowrap;"> '. h($salesOrder->courier->transporter_name) .'</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;" valign="top">Delivery Description</td>
						<td style="white-space: nowrap;">:</td>
						<td style="white-space: nowrap;"> '. h($salesOrder->delivery_description).'</td>
					</tr>
					
				</table>
			</td>
			<td valign="top">
				<table class="table2" width="100%">
					<tr>
						<td style="white-space: nowrap;" valign="top">E-Way Bill</td>
						<td style="white-space: nowrap;">:</td>
						<td style="white-space: nowrap;">'. h($salesOrder->e_way_bill).'</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;" valign="top">Expected Delivery Date</td>
						<td style="white-space: nowrap;">:</td>
						<td style="white-space: nowrap;"> '. h(date("d-m-Y",strtotime($salesOrder->expected_delivery_date))).'</td>
						
					</tr>
					<tr>
						<td style="white-space: nowrap;" valign="top">Payment Terms</td>
						<td style="white-space: nowrap;">:</td>
						<td style="white-space: nowrap;"> '. h($salesOrder->payment_terms).'</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<table width="100%" class="table_rows ">
		<tr>
			<td valign="top" width="18%">Additional Note</td>
			<td  valign="top" class="topdata">'. $Text->autoParagraph($salesOrder->additional_note).'</td>

		</tr>
	</table>
	<table width="100%" class="table_rows ">
		<tr>
			<td width="60%" valign="top">
				<b style="font-size:13px;"><u>Dispatch Details</u></b>
				<table class="table2" width="100%">
					<tr>
						<td valign="top">Name</td>
						<td valign="top"> : <td>
						<td valign="top">'. h($salesOrder->dispatch_name).'</td>
						<td width="10%"></td>
						<td valign="top">Mobile</td>
						<td valign="top"> : <td>
						<td valign="top">'. h($salesOrder->dispatch_mobile).'</td>
						
					</tr>
					<tr>
						<td valign="top">Address</td>
						<td valign="top"> : <td>
						<td valign="top" width="60%" >'. h($salesOrder->dispatch_address).'</td>
						<td></td>
						<td valign="top">Email</td>
						<td valign="top"> : <td>
						<td valign="top">'. h($salesOrder->dispatch_email).'</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>	
</table>	
';

$html.='<table width="100%" class="table_rows ">
		<tr><td width="30%" align="right">';
		
if(!empty($salesOrder->edited_by)){
$html.='<div align="center">
		<img src='.ROOT . DS  . 'webroot' . DS  .'signatures/'.$salesOrder->editor->signature.' height="50px" style="height:50px;"/>
		<br/>
		<span><b>Edited by</b></span><br/>
		<span>'. h($salesOrder->editor->name) .'</span><br/>
		
		On '. h(date("d-m-Y",strtotime($salesOrder->edited_on))).','. h(date("h:i:s A",strtotime($salesOrder->edited_on_time))).'<br/>
		</div>';
}
			
$html.='</td>
<td width="30%" align="right">
			<div align="center">
			<img src='.ROOT . DS  . 'webroot' . DS  .'signatures/'.$salesOrder->creator->signature.' height="50px" style="height:50px;"/>
			<br/>
			<span><b>Created by</b></span><br/>
			
			<span>'. h($salesOrder->creator->name).' </span><br/>
			On '. h(date("d-m-Y",strtotime($salesOrder->created_on))).','. h(date("h:i:s A",strtotime($salesOrder->created_on_time))).'<br/>
			</div>
		</td>';
			
			
$html.='</tr>
	</table>';

$html .= '</div>
</body>
</html>';
		  
		//echo $html; exit;
		
		$name='last_so';
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();
		$output = $dompdf->output(); //echo $name; exit;
		file_put_contents('Invoice_email/'.$name.'.pdf', $output);
		//$dompdf->stream($name,array('Attachment'=>0));
		return;
	}
}
