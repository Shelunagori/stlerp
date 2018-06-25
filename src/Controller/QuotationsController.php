<?php
namespace App\Controller;

use App\Controller\AppController;


class QuotationsController extends AppController
{
	
    public function index($status=null)
    {
		
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		
		$copy_request=$this->request->query('copy-request');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$this->viewBuilder()->layout('index_layout');
		$where=[];$where1=[];
		$company_id=$this->request->query('company_id');
		$qt2=$this->request->query('qt2');
		$file=$this->request->query('file');
		$customer=$this->request->query('customer');
		$salesman=$this->request->query('salesman');
		$product=$this->request->query('product');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$q_dateFrom=$this->request->query('q_dateFrom');
		$q_dateTo=$this->request->query('q_dateTo');
		$copy_request=$this->request->query('copy-request');
		$pull_request=$this->request->query('pull-request');
		$gst_pull_request=$this->request->query('gst-pull-request');
		$close_status=$this->request->query('status');
		$st_year_id = $session->read('st_year_id');
		$s_employee_id = $this->viewVars['s_employee_id'];
		//echo $st_year_id;exit;
		$financial_year = $this->Quotations->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$SessionCheckDate = $this->FinancialYears->get($st_year_id);
		$fromdate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_from));   
		$todate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_to)); 
		$items=$this->request->query('items');
		$item_group=$this->request->query('item_group');
		$item_subgroup=$this->request->query('item_subgroup');
		$this->set(compact('qt2','customer','salesman','product','From','To','q_dateFrom','q_dateTo','company_id','file','pull_request','gst_pull_request','close_status','items','item_group','item_subgroup')); 
		$where['company_id']=$st_company_id;
		//$where['financial_year_id']=$st_year_id;
		if(!empty($company_id)){
			$where['company_id']=$company_id;
		}
		if(!empty($qt2)){
			$where['Quotations.qt2 LIKE']=$qt2;
		}
		if(!empty($file)){
			$where['Quotations.qt3 LIKE']='%'.$file.'%';
		}
		if(!empty($customer)){
			$where['Customers.customer_name LIKE']='%'.$customer.'%';
		}
		if(!empty($salesman)){
			$where['Quotations.employee_id']=$salesman;
		}
		if(!empty($product)){
			$where['ItemGroups.name LIKE']='%'.$product.'%';
		}
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['finalisation_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['finalisation_date <=']=$To;
		}
		if(!empty($q_dateFrom)){
			$q_dateFrom=date("Y-m-d",strtotime($this->request->query('q_dateFrom')));
			$where['created_on >=']=$q_dateFrom;
		}
		if(!empty($q_dateTo)){
			$q_dateTo=date("Y-m-d",strtotime($this->request->query('q_dateTo')));
			$where['created_on <=']=$q_dateTo;
		}
        /* $this->paginate = [
            'contain' => ['Customers','Employees','ItemGroups']
        ]; */
		$EMP_ID =[23,16,17];
		if(in_array($s_employee_id,$EMP_ID)){
			$wherre=[];
		}else{
			$wherre['Quotations.employee_id']=$s_employee_id;
		}
		if($status==null or $status=='Pending'){ 
			$where['Quotations.status']='Pending'; 
		}elseif($status=='Converted into Sales Order'){
			$where['Quotations.status']='Converted Into Sales Order';
		}elseif($status=='Closed'){
			$where['Quotations.status']='Closed';
		}
		
		$subquery=$this->Quotations->find();
		$subquery->select(['max_id' => $subquery->func()->max('id')])->group('quotation_id');
		$max_ids=[];
		foreach($subquery as $data){
			$max_ids[]=$data->max_id;
		} 
		
		$styear=[1,3,2];
		if(in_array($st_year_id,$styear)){ 
			if(sizeof($max_ids)>0){ 
				$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups',	'QuotationRows'=>['Items']])->where($where)->where($wherre)->where(['Quotations.id IN' =>$max_ids])->where(['company_id'=>$st_company_id,'financial_year_id'=>$st_year_id])->order(['Quotations.id' => 'DESC']);
				
			}else{  
				$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($wherre)->where($where)->where(['company_id'=>$st_company_id,'financial_year_id'=>$st_year_id])->order(['Quotations.id' => 'DESC']); 
			}
		}else{
			if(sizeof($max_ids)>0){
					$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($wherre)->where($where)->where(['Quotations.id IN' =>$max_ids ])->where(['company_id'=>$st_company_id,'financial_year_id'=>$st_year_id])->order(['Quotations.id' => 'DESC']);
					
			}else{  
					$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($wherre)->where($where)->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']); 
			}
		}
		
		 
		/* if(sizeof($max_ids)>0){ echo"hello";
			$quotations = $this->paginate($this->Quotations->find()->contain(['QuotationRows'=>['Items']])->where($where)->where(['Quotations.id IN' =>$max_ids])->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']));
		} */
		$where1=[];
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
		if(in_array($st_year_id,$styear)){ 
			if(!empty($items) || !empty($item_group) || !empty($item_subgroup)){  
				
				$quotations=$this->paginate($this->Quotations->find()
				->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])
				->where($wherre)
				->matching(
						'QuotationRows.Items', function ($q) use($items,$st_company_id,$where1) {
							return $q->where($where1)->contain(['ItemCompanies'=>function ($e) use($st_company_id){
													return $e->where(['ItemCompanies.company_id'=>$st_company_id,'financial_year_id'=>$st_year_id]);
													}]);
						}
					)
					->where($where));
					//pr($quotations->toArray());exit;
			}else if($gst_pull_request=="true"){ 
				$tdate=date('Y-m-d',strtotime($financial_year->date_to)); 
				$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($wherre)->where($where)->where(['Quotations.id IN' =>$max_ids])->where(['Quotations.company_id'=>$st_company_id,'Quotations.created_on <='=>$tdate,'financial_year_id'=>$st_year_id])->order(['Quotations.id' => 'DESC']);
			}else{   
				if(sizeof($max_ids)>0){
					$quotations = $this->Quotations->find()->where($wherre)->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($where)->where(['Quotations.id IN' =>$max_ids])->where(['company_id'=>$st_company_id,'financial_year_id'=>$st_year_id])->order(['Quotations.id' => 'DESC']);
						
				}else{  
					$quotations = $this->Quotations->find()->where($wherre)->contain(['QuotationRows'=>['Items']])->where($where)->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']); 
				}
			
											
			}
		}else{
						if(!empty($items) || !empty($item_group) || !empty($item_subgroup)){  
				
				$quotations=$this->paginate($this->Quotations->find()
				->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])
				->where($wherre)
				->matching(
						'QuotationRows.Items', function ($q) use($items,$st_company_id,$where1) {
							return $q->where($where1)->contain(['ItemCompanies'=>function ($e) use($st_company_id){
													return $e->where(['ItemCompanies.company_id'=>$st_company_id]);
													}]);
						}
					)
					->where($where));
					//pr($quotations->toArray());exit;
			}else if($gst_pull_request=="true"){ 
				$tdate=date('Y-m-d',strtotime($financial_year->date_to)); 
				$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($wherre)->where($where)->where(['Quotations.id IN' =>$max_ids])->where(['Quotations.company_id'=>$st_company_id,'Quotations.created_on <='=>$tdate])->order(['Quotations.id' => 'DESC']);
			}else{   
			if($status=='Converted into Sales Order'){
				if(sizeof($max_ids)>0){
					$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($wherre)->where($where)->where(['Quotations.id IN' =>$max_ids])->where(['company_id'=>$st_company_id,'financial_year_id'=>$st_year_id])->order(['Quotations.id' => 'DESC']);
						
				}else{  
					$quotations = $this->Quotations->find()->contain(['QuotationRows'=>['Items']])->where($where)->where($wherre)->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']); 
				}
			}else if($status=='Closed'){
				if(sizeof($max_ids)>0){
					$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($where)->where(['Quotations.id IN' =>$max_ids])->where($wherre)->where(['company_id'=>$st_company_id,'financial_year_id'=>$st_year_id])->order(['Quotations.id' => 'DESC']);
						
				}else{  
					$quotations = $this->Quotations->find()->contain(['QuotationRows'=>['Items']])->where($where)->where($wherre)->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']); 
				}
			}else{
				if(sizeof($max_ids)>0){
					$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($wherre)->where($where)->where(['Quotations.id IN' =>$max_ids])->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']);
						
				}else{  
					$quotations = $this->Quotations->find()->contain(['QuotationRows'=>['Items']])->where($where)->where($wherre)->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']); 
				}
			}
											
			}

		}
		//pr($allowed_emp); exit;
		
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Quotations->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->Quotations->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->Quotations->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		
		$ItemGroups = $this->Quotations->QuotationRows->Items->ItemGroups->find('list')->order(['ItemGroups.name' => 'ASC']);
		$ItemSubGroups = $this->Quotations->QuotationRows->Items->ItemSubGroups->find('list')->order(['ItemSubGroups.name' => 'ASC']);	
		$companies = $this->Quotations->Companies->find('list');
		$Items = $this->Quotations->QuotationRows->Items->find('list')->order(['Items.name' => 'ASC']);
		$closeReasons = $this->Quotations->QuotationCloseReasons->find('all');
		/* $SalesMans = $this->Quotations->Employees->find('list')->matching(
					'Departments', function ($q) use($st_company_id) {
						return $q->where(['Departments.id' =>1]);
					}
				); */
		$EMP_ID =[23,16,17];
		if(in_array($s_employee_id,$EMP_ID)){
			 $SalesMans = $this->Quotations->Employees->find('list')->matching(
						'Departments', function ($q) use($st_company_id) {
							return $q->where(['Departments.id' =>1]);
						}
					);
		}else{
			$SalesMans = $this->Quotations->Employees->find('list')->matching(
						'Departments', function ($q) use($st_company_id) {
							return $q->where(['Departments.id' =>1]);
						}
					)->where(['Employees.id'=>$s_employee_id]);
		}		
        $this->set(compact('quotations','status','copy_request','companies','closeReasons','closed_month','close_status','Items','financial_month_first','financial_month_last','st_year_id','ItemGroups','ItemSubGroups','SalesMans','s_employee_id','EMP_ID'));
        $this->set('_serialize', ['quotations']);
		$this->set(compact('url'));
	}
	
	
	public function showPendingItem($id=null){
		
			$Quotations = $this->Quotations->find()->contain(['QuotationRows'=>['SalesOrderRows' => function($q) {
				return $q->select(['sales_order_id','quotation_row_id','item_id','total_qty' => $q->func()->sum('SalesOrderRows.quantity')])->group('SalesOrderRows.quotation_row_id');
			}]])->where(['Quotations.id'=>$id]);
			
			$sales_order_qty=[];
			 $invoice_qty=[];
			$salesData=[];
			
				foreach($Quotations as $SalesOrder){ $sales_qty=[]; $inc_qty=[]; 
					foreach($SalesOrder->quotation_rows as $sales_order_row){  
						foreach($sales_order_row->sales_order_rows as $invoice_row){ //pr($invoice_row); exit;
							@$invoice_qty[$invoice_row['item_id']]+=$invoice_row['total_qty'];
							@$inc_qty[$invoice_row['item_id']]+=$invoice_row['total_qty'];
						}
						@$sales_order_qty[$sales_order_row['item_id']]+=$sales_order_row['quantity'];
						@$sales_qty[$sales_order_row['item_id']]+=$sales_order_row['quantity'];
					}
					
					
					foreach(@$sales_qty as $key=>$sales_order_qt){
							if(@$sales_order_qt > @$inc_qty[$key] ){ 
								$pen=@$sales_order_qt-@$inc_qty[$key];
								$itm= $this->Quotations->QuotationRows->Items->get($key);
								@$salesData[$itm->name]=$pen;
							}
					}
				}
		
		$this->set(compact('salesData'));
					
		
	}
	
	
	 public function exportExcel($status=null)
    {	
		$this->viewBuilder()->layout('');
		if(empty($status)){ $status="Pending"; } //pr($status); exit;
		$where=[];$where1=[];
		$copy_request=$this->request->query('copy-request');
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$st_year_id = $session->read('st_year_id');
		$company_id=$this->request->query('company_id');
		$s_employee_id = $this->viewVars['s_employee_id'];
		$qt2=$this->request->query('qt2');
		$file=$this->request->query('file');
		$customer=$this->request->query('customer');
		$salesman=$this->request->query('salesman');
		$product=$this->request->query('product');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		$q_dateFrom=$this->request->query('q_dateFrom');
		$q_dateTo=$this->request->query('q_dateTo');
		$copy_request=$this->request->query('copy-request');
		$pull_request=$this->request->query('pull-request');
		$gst_pull_request=$this->request->query('gst-pull-request');
		$close_status=$this->request->query('status');
		$items=$this->request->query('items');
		
		//$st_year_id = $session->read('st_year_id');
		$SessionCheckDate = $this->FinancialYears->get($st_year_id);
		$fromdate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_from));   
		$todate1 = DATE("Y-m-d",strtotime($SessionCheckDate->date_to)); 
		$tody1 = DATE("Y-m-d");
		
		$this->set(compact('qt2','customer','salesman','product','From','To','q_dateFrom','q_dateTo','company_id','file','pull_request','gst_pull_request','close_status','items')); 
		if(!empty($company_id)){
			$where['company_id']=$company_id;
		}
		if(!empty($qt2)){
			$where['Quotations.qt2 LIKE']=$qt2;
		}
		if(!empty($file)){
			$where['Quotations.qt3 LIKE']='%'.$file.'%';
		}
		if(!empty($customer)){
			$where['Customers.customer_name LIKE']='%'.$customer.'%';
		}
		if(!empty($salesman)){
			$where['Quotations.employee_id']=$salesman;
		}
		if(!empty($product)){
			$where['ItemGroups.name LIKE']='%'.$product.'%';
		}
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['finalisation_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['finalisation_date <=']=$To;
		}
		if(!empty($q_dateFrom)){
			$q_dateFrom=date("Y-m-d",strtotime($this->request->query('q_dateFrom')));
			$where['created_on >=']=$q_dateFrom;
		}
		if(!empty($q_dateTo)){
			$q_dateTo=date("Y-m-d",strtotime($this->request->query('q_dateTo')));
			$where['created_on <=']=$q_dateTo;
		}
        /* $this->paginate = [
            'contain' => ['Customers','Employees','ItemGroups']
        ]; */
		$styear=[1,3,2];
		$EMP_ID =[23,16,17];
		if(in_array($s_employee_id,$EMP_ID)){
			$wherre=[];
		}else{
			$wherre['Quotations.employee_id']=$s_employee_id;
		}
		if($status==null or $status=='Pending'){ 
			$where['Quotations.status']='Pending'; 
		}elseif($status=='Converted into Sales Order'){
			$where['Quotations.status']='Converted Into Sales Order';
		}elseif($status=='Closed'){
			$where['Quotations.status']='Closed';
		}
		
		$subquery=$this->Quotations->find();
		$subquery->select(['max_id' => $subquery->func()->max('id')])->group('quotation_id');
		$max_ids=[];
		foreach($subquery as $data){
			$max_ids[]=$data->max_id;
		} 
		
		if(sizeof($max_ids)>0){
			$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($where)->where($wherre)->where(['Quotations.id IN' =>$max_ids])->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']);
				
		}else{  
			$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($wherre)->where($where)->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']); 
		}
		 
		/* if(sizeof($max_ids)>0){ echo"hello";
			$quotations = $this->paginate($this->Quotations->find()->contain(['QuotationRows'=>['Items']])->where($where)->where(['Quotations.id IN' =>$max_ids])->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']));
		} */
		/* if(!empty($items)){  
			
			$quotations=$this->paginate($this->Quotations->find()
			->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])
			->matching(
					'QuotationRows.Items', function ($q) use($items,$st_company_id,$where) {
						return $q->where(['Items.id' =>$items,'company_id'=>$st_company_id])->where($where);
					}
				)
				);
		}else{  
		
			if(sizeof($max_ids)>0){  
				$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($where)->where(['Quotations.id IN' =>$max_ids])->where(['company_id'=>$st_company_id,'financial_year_id'=>$st_year_id])->order(['Quotations.id' => 'DESC']);
					
			}else{
				$quotations = $this->Quotations->find()->contain(['QuotationRows'=>['Items']])->where($where)->where(['company_id'=>$st_company_id,'created_on >='=>$fromdate1,'created_on <='=>$todate1])->order(['Quotations.id' => 'DESC']); 
			}
		
										
		} */
		if(in_array($st_year_id,$styear)){ 
			if(!empty($items) || !empty($item_group) || !empty($item_subgroup)){  
				
				$quotations=$this->paginate($this->Quotations->find()
				->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])
				->where($wherre)
				->matching(
						'QuotationRows.Items', function ($q) use($items,$st_company_id,$where1) {
							return $q->where($where1)->contain(['ItemCompanies'=>function ($e) use($st_company_id){
													return $e->where(['ItemCompanies.company_id'=>$st_company_id,'financial_year_id'=>$st_year_id]);
													}]);
						}
					)
					->where($where));
					//pr($quotations->toArray());exit;
			}else if($gst_pull_request=="true"){ 
				$tdate=date('Y-m-d',strtotime($financial_year->date_to)); 
				$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($wherre)->where($where)->where(['Quotations.id IN' =>$max_ids])->where(['Quotations.company_id'=>$st_company_id,'Quotations.created_on <='=>$tdate,'financial_year_id'=>$st_year_id])->order(['Quotations.id' => 'DESC']);
			}else{   
				if(sizeof($max_ids)>0){
					$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($wherre)->where($where)->where(['Quotations.id IN' =>$max_ids])->where(['company_id'=>$st_company_id,'financial_year_id'=>$st_year_id])->order(['Quotations.id' => 'DESC']);
						
				}else{  
					$quotations = $this->Quotations->find()->contain(['QuotationRows'=>['Items']])->where($where)->where($wherre)->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']); 
				}
			
											
			}
		}else{
						if(!empty($items) || !empty($item_group) || !empty($item_subgroup)){  
				
				$quotations=$this->paginate($this->Quotations->find()
				->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])
				->where($wherre)
				->matching(
						'QuotationRows.Items', function ($q) use($items,$st_company_id,$where1) {
							return $q->where($where1)->contain(['ItemCompanies'=>function ($e) use($st_company_id){
													return $e->where(['ItemCompanies.company_id'=>$st_company_id]);
													}]);
						}
					)
					->where($where));
					//pr($quotations->toArray());exit;
			}else if($gst_pull_request=="true"){ 
				$tdate=date('Y-m-d',strtotime($financial_year->date_to)); 
				$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($wherre)->where($where)->where(['Quotations.id IN' =>$max_ids])->where(['Quotations.company_id'=>$st_company_id,'Quotations.created_on <='=>$tdate])->order(['Quotations.id' => 'DESC']);
			}else{   
			if($status=='Converted into Sales Order'){
				if(sizeof($max_ids)>0){
					$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($wherre)->where($where)->where(['Quotations.id IN' =>$max_ids])->where(['company_id'=>$st_company_id,'financial_year_id'=>$st_year_id])->order(['Quotations.id' => 'DESC']);
						
				}else{  
					$quotations = $this->Quotations->find()->contain(['QuotationRows'=>['Items']])->where($where)->where($wherre)->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']); 
				}
			}else if($status=='Closed'){
				if(sizeof($max_ids)>0){
					$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($where)->where(['Quotations.id IN' =>$max_ids])->where($wherre)->where(['company_id'=>$st_company_id,'financial_year_id'=>$st_year_id])->order(['Quotations.id' => 'DESC']);
						
				}else{  
					$quotations = $this->Quotations->find()->contain(['QuotationRows'=>['Items']])->where($where)->where($wherre)->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']); 
				}
			}else{
				if(sizeof($max_ids)>0){
					$quotations = $this->Quotations->find()->contain(['Customers','Employees','ItemGroups','QuotationRows'=>['Items']])->where($where)->where(['Quotations.id IN' =>$max_ids])->where($wherre)->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']);
						
				}else{  
					$quotations = $this->Quotations->find()->contain(['QuotationRows'=>['Items']])->where($where)->where($wherre)->where(['company_id'=>$st_company_id])->order(['Quotations.id' => 'DESC']); 
				}
			}
											
			}

		}
		//pr($quotations->toArray()); exit;
		
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Quotations->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->Quotations->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->Quotations->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		
		 
		$companies = $this->Quotations->Companies->find('list');
		$Items = $this->Quotations->QuotationRows->Items->find('list')->order(['Items.name' => 'ASC']);
		$closeReasons = $this->Quotations->QuotationCloseReasons->find('all');
        $this->set(compact('quotations','status','copy_request','companies','closeReasons','closed_month','close_status','Items','financial_month_first','financial_month_last'));
        $this->set('_serialize', ['quotations']);
    }
	
	
	
	public function ConvertedQuotation()
    {
		$this->viewBuilder()->layout('index_layout');
        $this->paginate = [
            'contain' => ['Customers']
        ];
        $quotations = $this->paginate($this->Quotations);
		$start = $this->Paginator->counter(array('format' => '%start%'));

        $this->set(compact('quotations'));
        $this->set('_serialize', ['quotations']);
    }

    /**
     * View method
     *
     * @param string|null $id Quotation id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $quotation = $this->Quotations->get($id, [
            'contain' => ['Customers','Companies','Employees','ItemGroups','QuotationRows' => ['Items']]
        ]);
		$this->set('quotation', $quotation);
		$this->set('_serialize', ['quotation']);
    }
	
	public function confirm($id = null)
    {
		$this->viewBuilder()->layout('pdf_layout');
		$id = $this->EncryptingDecrypting->decryptData($id);
		$session = $this->request->session();
		$st_year_id = $session->read('st_year_id');
		$quotation = $this->Quotations->get($id, [
            'contain' => ['QuotationRows','Customers'=>['CustomerContacts'=>function ($q) {
						   return $q
								->where(['CustomerContacts.default_contact'=>1]);
						}]]
		]);
		$quote_id=$quotation->quotation_id;
		$query = $this->Quotations->find()->where(['quotation_id' => $quote_id]);
		$max = $query
		->select(['revision' => $query->func()->max('Quotations.revision')])->first();
		$revision=$max->revision;
		if ($this->request->is(['patch', 'post', 'put'])) {
			
			if(!empty($this->request->data['pdf_font_size'])){
				$pdf_font_size=$this->request->data['pdf_font_size'];
				$query = $this->Quotations->query();
					$query->update()
						->set(['pdf_font_size' => $pdf_font_size])
						->where(['id' => $id])
						->execute();
			}
			
			if(!empty($this->request->data['quotation_rows'])){
				foreach($this->request->data['quotation_rows'] as $quotation_row_id=>$value){
					$quotationRow=$this->Quotations->QuotationRows->get($quotation_row_id);
					$quotationRow->height=$value["height"];
					$this->Quotations->QuotationRows->save($quotationRow);
			}
			}
			$id = $this->EncryptingDecrypting->encryptData($id);
			return $this->redirect(['action' => 'confirm/'.$id]);
        }
		
		
		$this->set(compact('quotation','id','email','revision','st_year_id'));
        
    }
	
	
	
	public function pdf($id = null)
    {
		$this->viewBuilder()->layout('');
		$send_email=$this->request->query('sendemail');
		$quotation_id=$this->request->query('quotaionid');
		//$quotation_id = $this->EncryptingDecrypting->decryptData($quotation_id);
		$id = $this->EncryptingDecrypting->decryptData($id);
		
		if(!empty($send_email))
		{
		$send_email='true';	
		}else{
		$send_email='false';	
		}
        $this->set(compact('send_email','email_id','emailRecord','quotation_id'));
		if(!empty($quotation_id)){
		$quotation = $this->Quotations->get($quotation_id, [
            'contain' => ['Customers'=>['CustomerContacts'=>function($q){
			return $q
			->where(['CustomerContacts.default_contact'=>1]);
		}],'Companies','Employees'=>['Designations'],'ItemGroups','Creator'=>['Designations'],'Editor'=>['Designations'],'QuotationRows' => ['Items'=>['Units']]]
        ]);
		}
		else{
		$quotation = $this->Quotations->get($id, [
            'contain' => ['Customers'=>['CustomerContacts'=>function($q){
			return $q
			->where(['CustomerContacts.default_contact'=>1]);
		}],'Companies','Employees'=>['Designations'],'ItemGroups','Creator'=>['Designations'],'Editor'=>['Designations'],'QuotationRows' => ['Items'=>['Units']]]
        ]);	
		}
		
		$this->set('quotation', $quotation);
        $this->set('_serialize', ['quotation']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $copy=$this->request->query('copy');
		
		$revision=$this->request->query('revision');
		
		$id=$this->request->query('copy');
		if(!empty($id)){
			$quotation = $this->Quotations->get($id, [
				'contain' => ['QuotationRows']
			]);
		}elseif(!empty($revision)){
			$revision = $this->EncryptingDecrypting->decryptData($revision);
			$quotation = $this->Quotations->get($revision, [
				'contain' => ['QuotationRows']
			]);
			$add_revision=$quotation->revision+1;
			
			$quotation_id=$quotation->quotation_id;
			
		}else{
			$quotation = $this->Quotations->newEntity();
		}
		$s_employee_id=$this->viewVars['s_employee_id'];
		
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		$Company = $this->Quotations->Companies->get($st_company_id);
		
        $st_year_id = $session->read('st_year_id');

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

				
				
        if ($this->request->is(['patch', 'post', 'put'])) {
			//pr($this->request->data); exit;
			$quotation = $this->Quotations->newEntity();
            $quotation = $this->Quotations->patchEntity($quotation, $this->request->data);
			$last_qt_no=$this->Quotations->find()->select(['qt2'])->where(['company_id' => $st_company_id,'financial_year_id'=>$st_year_id])->order(['qt2' => 'DESC'])->first();
			
			if($last_qt_no){
				if(!empty($revision)){
					
					$last_qt_revision_no=$this->Quotations->find()->select(['qt2'])->where(['company_id' => $st_company_id,'id' => $revision,'financial_year_id'=>$st_year_id])->order(['qt2' => 'DESC'])->first();
					
					
					$quotation->qt2=$last_qt_revision_no->qt2;
				}else{
					$quotation->qt2=$last_qt_no->qt2+1;
				}
			}else{
				$quotation->qt2=1;
			}	
			//pr($quotation->qt2); exit;
			if(!empty($revision)){
			$quotation->revision=$add_revision;
			$quotation->quotation_id=$quotation_id;
			}
			
			$quotation->financial_year_id=$st_year_id;
			$quotation->created_by=$s_employee_id;
			$quotation->created_on=date("Y-m-d",strtotime($quotation->created_on));
			$quotation->finalisation_date=date("Y-m-d",strtotime($quotation->finalisation_date));
			$quotation->company_id=$st_company_id;
			//pr($quotation); exit;
            if ($this->Quotations->save($quotation)) {
				if(empty($revision)){
					$lastQuotation=$this->Quotations->get($quotation->id);
					$lastQuotation->quotation_id=$quotation->id;
					$this->Quotations->save($lastQuotation);
				}
				$quotation->id = $this->EncryptingDecrypting->encryptData($quotation->id);
                return $this->redirect(['action' => 'confirm',$quotation->id]);
            } else {
                $this->Flash->error(__('The quotation could not be saved. Please, try again.'));
            }
        }
		$Filenames = $this->Quotations->Filenames->find()->where(['customer_id' => $quotation->customer_id]);

		$copy=$this->request->query('copy');
		$companies = $this->Quotations->Companies->find('all');
		
        $customers = $this->Quotations->Customers->find('all')->contain(['Filenames'])->order(['Customers.customer_name' => 'ASC'])->matching(
					'CustomerCompanies', function ($q) use($st_company_id) {
						return $q->where(['CustomerCompanies.company_id' => $st_company_id]);
					}
				);
		//$employees = $this->Quotations->Employees->find('list', ['limit' => 200])->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC']);
		
		/* $employees = $this->Quotations->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.company_id' => $st_company_id,'EmployeeCompanies.freeze' => 0]);
					}
				); */
		$EMP_ID =[23,16,17];
		if(in_array($s_employee_id,$EMP_ID)){
			 $employees = $this->Quotations->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.company_id' => $st_company_id,'EmployeeCompanies.freeze' => 0]);
					}
				);
		}else{
			$employees = $this->Quotations->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.company_id' => $st_company_id,'EmployeeCompanies.freeze' => 0]);
					}
				)->where(['Employees.id'=>$s_employee_id]);
		}		
		$ItemGroups = $this->Quotations->ItemGroups->find('list')->order(['ItemGroups.name' => 'ASC']);
		
		$items = $this->Quotations->Items->find()->order(['Items.name' => 'ASC'])->matching(
					'ItemCompanies', function ($q) use($st_company_id) {
						return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
					}
				);	

		 $ItemsOptions=[];
		foreach($items as $item){ 
					$ItemsOptions[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
		} 				
		
		$Item_datas = $this->Quotations->Items->find()->order(['Items.name' => 'ASC'])->matching(
						'ItemCompanies', function ($q) use($st_company_id) {
							return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0])->orWhere(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 1]);
						}
					);		
					
			$ItemsOptionsData=[];
			foreach($Item_datas as $item){ 
						$ItemsOptionsData[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
			}	
		$st_year_id = $session->read('st_year_id');
		$financial_year = $this->Quotations->FinancialYears->find()->where(['id'=>$st_year_id])->first();
		$financial_month_first = $this->Quotations->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->first();
		$financial_month_last = $this->Quotations->FinancialMonths->find()->where(['financial_year_id'=>$st_year_id,'status'=>'Open'])->last();
		$termsConditions = $this->Quotations->TermsConditions->find('all',['limit' => 200]);
		
        $this->set(compact('quotation', 'customers','companies','revision','employees','Filenames','ItemGroups','items','termsConditions','copy','Company','chkdate','financial_month_first','financial_month_last','ItemsOptions','ItemsOptionsData'));
        $this->set('_serialize', ['quotation']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Quotation id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$id = $this->EncryptingDecrypting->decryptData($id);
        $quotation = $this->Quotations->get($id, [
            'contain' => ['QuotationRows']
        ]);
		$session = $this->request->session();
		$st_year_id = $session->read('st_year_id');
		$closed_month=$this->viewVars['closed_month'];
		
		if(!in_array(date("m-Y",strtotime($quotation->created_on)),$closed_month))
		{ 
		
			$s_employee_id=$this->viewVars['s_employee_id'];
			$session = $this->request->session();
			$st_company_id = $session->read('st_company_id');
			$Company = $this->Quotations->Companies->get($st_company_id);

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
				$this->request->data["finalisation_date"]=date("Y-m-d",strtotime($this->request->data["finalisation_date"]));
				$quotation = $this->Quotations->patchEntity($quotation, $this->request->data);
				$quotation->created_by=$s_employee_id;
				$quotation->created_on=date("Y-m-d",strtotime($quotation->created_on));
				$quotation->finalisation_date=date("Y-m-d",strtotime($quotation->finalisation_date));
				$quotation->company_id=$st_company_id;
				//$quotation->company_id=$st_company_id;
				
				if ($this->Quotations->save($quotation)) {
				     
					 $this->Flash->success(__('The quotation has been saved.'));
					$quotation->id = $this->EncryptingDecrypting->encryptData($quotation->id);
					return $this->redirect(['action' => 'confirm',$quotation->id]);
				} else { 
					$this->Flash->error(__('The quotation could not be saved. Please, try again.'));
				}
			}
			$Filenames = $this->Quotations->Filenames->find()->where(['customer_id' => $quotation->customer_id]);
			
			$companies = $this->Quotations->Companies->find('all',['limit' => 200]);
			
			$customers = $this->Quotations->Customers->find('all')->contain(['Filenames'])->order(['Customers.customer_name' => 'ASC'])->matching(
						'CustomerCompanies', function ($q) use($st_company_id) {
							return $q->where(['CustomerCompanies.company_id' => $st_company_id]);
						}
					);
			/* $employees = $this->Quotations->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])->matching(
						'EmployeeCompanies', function ($q) use($st_company_id) {
							return $q->where(['EmployeeCompanies.company_id' => $st_company_id]);
						}
					); */
					
			$EMP_ID =[23,16,17];
		if(in_array($s_employee_id,$EMP_ID)){
			 $employees = $this->Quotations->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.company_id' => $st_company_id,'EmployeeCompanies.freeze' => 0]);
					}
				);
		}else{
			$employees = $this->Quotations->Employees->find('list')->where(['dipartment_id' => 1])->order(['Employees.name' => 'ASC'])->matching(
					'EmployeeCompanies', function ($q) use($st_company_id) {
						return $q->where(['EmployeeCompanies.company_id' => $st_company_id,'EmployeeCompanies.freeze' => 0]);
					}
				)->where(['Employees.id'=>$s_employee_id]);
		}			
			$ItemGroups = $this->Quotations->ItemGroups->find('list')->order(['ItemGroups.name' => 'ASC']);
			
			$Items = $this->Quotations->Items->find()->order(['Items.name' => 'ASC'])->matching(
						'ItemCompanies', function ($q) use($st_company_id) {
							return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0])->orWhere(['ItemCompanies.company_id'=>$st_company_id,'ItemCompanies.freeze' => 1]);
						}
					);		
					
					
			
				
			$ItemsOptions=[];
			foreach($Items as $item){ 
						$ItemsOptions[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
			}		

			$Item_datas = $this->Quotations->Items->find()->order(['Items.name' => 'ASC'])->matching(
						'ItemCompanies', function ($q) use($st_company_id) {
							return $q->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
						}
					);		
					
			$ItemsOptionsData=[];
			foreach($Item_datas as $item){ 
						$ItemsOptionsData[]=['value'=>$item->id,'text'=>$item->name,'serial_number_enable'=>@$item->_matchingData['ItemCompanies']->serial_number_enable];
			}	

		
			$termsConditions = $this->Quotations->TermsConditions->find('all',['limit' => 200]);
			////start unique validation and procees qty
			$QuotationsId = $this->Quotations->get($id, [
            'contain' => (['SalesOrders'=>['SalesOrderRows' => function($q) {
				return $q->select(['sales_order_id','quotation_row_id','item_id','total_qty' => $q->func()->sum('SalesOrderRows.quantity')])->group('SalesOrderRows.quotation_row_id');
			}]])
        ]);
		
		$quotation_qty=[];
			foreach($QuotationsId->sales_orders as $sales_orders){ 
				foreach($sales_orders->sales_order_rows as $sales_order_row){ 
					$quotation_qty[@$sales_order_row->quotation_row_id]=@$quotation_qty[$sales_order_row->quotation_row_id]+$sales_order_row->total_qty;
					
				}
			}	
			
			$MinQty=[];
			if(!empty($id))
			{
				$Quotations = $this->Quotations->get($id, [
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
								@$MinQty[@$sales_order_row->quotation_row_id] +=@$sales_order_row->quantity;
							}
						}
					}
				}
				
			}
			//pr($MinQty);exit;
			////end unique validation and procees qty
			
			$this->set(compact('quotation', 'customers','companies','employees','ItemGroups','items','termsConditions','Filenames','chkdate','quotation_qty','MinQty','ItemsOptions','ItemsOptionsData'));
			$this->set('_serialize', ['quotation']);
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
     * @param string|null $id Quotation id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $quotation = $this->Quotations->get($id);
        if ($this->Quotations->delete($quotation)) {
            $this->Flash->success(__('The quotation has been deleted.'));
        } else {
            $this->Flash->error(__('The quotation could not be deleted. Please, try again.'));
        }
		return $this->redirect(['action' => 'index']);
    }
	
	public function close($id = null,$reason=null)
    {
        $quotation = $this->Quotations->get($id);
		$quotation_reason=$this->Quotations->QuotationCloseReasons->get($reason);
		$quotation->reason=$quotation_reason->reason;
		$quotation->status='Closed';
		$quotation->closing_date=date("Y-m-d");
		 if ($this->Quotations->save($quotation)) {
            $this->Flash->success(__('The quotation has been closed.'));
        } else {
            $this->Flash->error(__('The quotation could not be closed. Please, try again.'));
        }
		return $this->redirect(['action' => 'index']);
    }
	
	public function revision($id = null)
    {
		$quotation = $this->Quotations->get($id);
		$quot_id = $quotation->quotation_id;
		$revision = $quotation->revision;
		$quotations =$this->Quotations->find()->contain(['Customers','Employees','ItemGroups'])->where(['Quotations.quotation_id' =>$quot_id,'Quotations.revision !=' => $revision ]);
		$this->set(compact('quotations','quot_id','edit_hide'));
    }
	
	public function reopen($id = null)
    {
        $quotation = $this->Quotations->get($id);
		$quotation->reason='';
		$quotation->status='Pending';
		 if ($this->Quotations->save($quotation)) {
            $this->Flash->success(__('The quotation has been reopened.'));
        } else {
            $this->Flash->error(__('The quotation could not be Reopened. Please, try again.'));
        }
		return $this->redirect(['action' => 'index']);
    }
	
	public function download($id=null) { 
		$file = $this->Quotations->getFile($id);
	   // a view.
	   $this->response->file(
		  $file['files/content'],
		  ['download' => true]
	   );
	   return $this->response;
	}
	
	public function getClosedQuotations(){
		
		$Quotations =$this->Quotations->find()->where(['Quotations.status' =>'Closed']);
		foreach($Quotations as $quotation){
			$SalesOrdersData=$this->Quotations->SalesOrders->exists(['invoice_id'=>$invoice_id,'left_item_id'=>$q_item_id]);
		}
	}
}
