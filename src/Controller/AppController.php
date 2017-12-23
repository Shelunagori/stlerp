<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
		$this->loadComponent('Csrf');
		
		date_default_timezone_set('Asia/Kolkata');
		
		$session = $this->request->session();
		/*$db = @$session->read('db');
		$db='default';
		$conn = ConnectionManager::get($db);
		$conn->begin();*/
		$closed_month=[];
		
		$controller = $this->request->params['controller'];
		$action = $this->request->params['action']; 
		if (in_array($controller, ['Logins']) and in_array($action, ['index','generateOtp'])) {
		}else{
			$st_login_id = $session->read('st_login_id');
			$st_company_id = $session->read('st_company_id');
			$st_year_id =  $session->read('st_year_id');
			//$st_opt_confirm =  $session->read('st_opt_confirm');
			
			//pr($st_opt_confirm);exit;
			
			if(empty($st_login_id)){
				return $this->redirect('/logins'); exit;
				//return $this->redirect(['controller'=>'Homes','action'>'logins']); 
			}else{
				$this->loadModel('Logins');
				$login=$this->Logins->get($st_login_id);
				$this->set('s_employee_id',$login->employee_id);
				
				
				$this->loadModel('Employees');
				$sessionEmployee=$this->Employees->get($login->employee_id);
				
				if($st_company_id){
					$this->loadModel('Companies');
					$sessionCompany=$this->Companies->get($st_company_id);
					$this->set('s_company_name',$sessionCompany->name);
				}
				if($st_year_id){
					$this->loadModel('FinancialYears');
					$sessionYears=$this->FinancialYears->get($st_year_id);
					$this->set('s_year_from',date("Y",strtotime($sessionYears->date_from)));
					$this->set('s_year_to',date("Y",strtotime($sessionYears->date_to)));
				}
				
				
				$this->set('s_employee_name',$sessionEmployee->name);
				
				
			}
			////// Financial Year Or Month Closed /////////////
			$this->loadModel('FinancialYears');
			$this->loadModel('FinancialMonths');
			if($st_year_id){
				$SessionCheckDate = $this->FinancialYears->get($st_year_id);
				$fromdate1 = date("Y-m-d",strtotime($SessionCheckDate->date_from));   
				$todate1 = date("Y-m-d",strtotime($SessionCheckDate->date_to));
			
			}
			$FinancialClose = $this->FinancialYears->find()->where(['company_id'=>$st_company_id])->contain(['FinancialMonths' => function($q){
				return $q->where(['status' => 'Closed']);
			}
			])->toArray();
			foreach($FinancialClose as $financial_closes)
			{
				foreach($financial_closes->financial_months as $financial_months)
				{
					$closed_month[]=$financial_months->month;
				}
				//pr($closed_month); exit;
			}
			$this->set(compact('closed_month','fromdate1','todate1'));
			
			////////////////////////////////////////////
		}
		if(!empty($st_login_id)){
			$this->loadModel('UserRights');
			$UserRights=$this->UserRights->find()->where(['login_id'=>$st_login_id]);
			$allowed_pages=array();
			foreach($UserRights as $qwe){
				$allowed_pages[]=$qwe->page_id;
			}
			$this->set(compact('allowed_pages','st_company_id'));
		}

		$this->loadModel('Pages');
		$pages=$this->Pages->find()->where(['master'=>1]);
		$this->set(compact('pages'));

		$page=$this->Pages->find()->where(['controller'=>$controller,'action'=>$action])->first();

		if(!empty($page->id) and !in_array($page->id,$allowed_pages)){
			$pages=[];
			$this->set(compact('pages'));
			$this->viewBuilder()->layout('index_layout');
			$this -> render('/Error/not_allow'); 
		}
		
		
			$coreVariable = [
				'st_company_id' =>$session->read('st_company_id'),
			];
			
			$this->coreVariable = $coreVariable;
			$this->set(compact('coreVariable'));
	  }
	  
	  public function listRefArray($ledger_id=null)
		{
			$this->loadModel('ReferenceDetails');
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
				if($referenceDetail->total_debit!=$referenceDetail->total_credit){
					$option[]=['text' =>$referenceDetail->reference_no.' ('.$bal.')', 'value' => $referenceDetail->reference_no, 'amt' => abs($remider)];
				}
			}
			return $option;
		}
		
		
	public function stockValuation(){ 
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		
		$this->loadModel('ItemLedgers');
		$Items =$this->ItemLedgers->Items->find()->contain(['ItemCompanies'=>function($p) use($st_company_id){
		return $p->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
		}]);
		
		$stock=[];  $sumValue=0; $itemSerialRate=[]; $itemSerialQuantity=[];
		foreach($Items as $Item){
			if(@$Item->item_companies[0]->serial_number_enable==0){  
				$StockLedgers=$this->ItemLedgers->find()->where(['ItemLedgers.item_id'=>$Item->id,'ItemLedgers.company_id'=>$st_company_id])->order(['ItemLedgers.processed_on'=>'ASC']);
				foreach($StockLedgers as $StockLedger){ 
					if($StockLedger->in_out=='In'){ 
						if(($StockLedger->source_model=='Grns' and $StockLedger->rate_updated=='Yes') or ($StockLedger->source_model!='Grns')){
							for($inc=0;$inc<$StockLedger->quantity;$inc++){
								$stock[$Item->id][]=$StockLedger->rate;
							}
						}
					}
				}
				foreach($StockLedgers as $StockLedger){
					if($StockLedger->in_out=='Out'){
						if(sizeof(@$stock[$Item->id])>0){
							$stock[$Item->id] = array_slice($stock[$Item->id], $StockLedger->quantity); 
						}
					}
				}
				if(sizeof(@$stock[$Item->id]) > 0){ 
					foreach(@$stock[$Item->id] as $stockRate){
						@$sumValue+=@$stockRate;
					}
				}
			}else if(@$Item->item_companies[0]->serial_number_enable==1){
				$ItemSerialNumbers=$this->ItemLedgers->SerialNumbers->find()->where(['SerialNumbers.item_id'=>$Item->id,'SerialNumbers.company_id'=>$st_company_id,'status'=>'In'])->toArray();
				foreach($ItemSerialNumbers as $ItemSerialNumber){		
					if(@$ItemSerialNumber->grn_id > 0){ 
					$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id]);
						if($outExist == 0){
							$ItemLedgerData =$this->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->grn_id,'source_model'=>"Grns",'source_row_id'=>$ItemSerialNumber->grn_row_id])->first();
						//	pr($ItemLedgerData); 
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
					if(@$ItemSerialNumber->sale_return_id > 0){ 
					$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id]);
						if($outExist == 0){
							$ItemLedgerData =$this->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->sale_return_id,'source_model'=>"Sale Return",'source_row_id'=>$ItemSerialNumber->sales_return_row_id])->first();
						//	pr($ItemLedgerData); 
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
					if(@$ItemSerialNumber->itv_id > 0){
					$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id]); 
						if($outExist == 0){  
							$ItemLedgerData =$this->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->itv_id,'source_model'=>"Inventory Transfer Voucher",'source_row_id'=>$ItemSerialNumber->itv_row_id])->first();
							//pr($ItemLedgerData); 
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
					if(@$ItemSerialNumber->iv_row_id > 0){
					$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id]); 
						if($outExist == 0){  
							$ItemLedgerData =$this->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->itv_id,'source_model'=>"Inventory Vouchers",'iv_row_id'=>$ItemSerialNumber->itv_row_id])->first();
							//pr($ItemLedgerData); 
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
				
					if(@$ItemSerialNumber->is_opening_balance == "Yes"){
					$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id]); 
						if($outExist == 0){  
							$ItemLedgerData =$this->ItemLedgers->find()->where(['source_model'=>"Items",'company_id'=>$st_company_id])->first();
							//pr($ItemLedgerData); 
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
				}
			
			}
		}
		return $sumValue;
	}
	
	public function stockValuationWithDate($date=null){ 
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		//$date=date('Y-m-d');
		//pr($date);exit;
		//$date=$this->request->query('date');
		$date=date("Y-m-d",strtotime($date));
	
		$this->loadModel('ItemLedgers');
		$Items =$this->ItemLedgers->Items->find()->contain(['ItemCompanies'=>function($p) use($st_company_id){
		return $p->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
		}]);
		
		$stock=[];  $sumValue=0; $itemSerialRate=[]; $itemSerialQuantity=[];
		foreach($Items as $Item){
			if(@$Item->item_companies[0]->serial_number_enable==0){
				if(strtotime($date)==strtotime('2017-4-1')){
					$StockLedgers=$this->ItemLedgers->find()
					->where(['ItemLedgers.item_id'=>$Item->id,'ItemLedgers.company_id'=>$st_company_id,'ItemLedgers.processed_on <='=>$date, 'ItemLedgers.source_model'=>'Items'])
					->order(['ItemLedgers.processed_on'=>'ASC']);
				}else{
					$StockLedgers=$this->ItemLedgers->find()->where(['ItemLedgers.item_id'=>$Item->id,'ItemLedgers.company_id'=>$st_company_id,'ItemLedgers.processed_on <'=>$date])->order(['ItemLedgers.processed_on'=>'ASC']);
				}
				
				foreach($StockLedgers as $StockLedger){
					if($StockLedger->in_out=='In'){
						if(($StockLedger->source_model=='Grns' and $StockLedger->rate_updated=='Yes') or ($StockLedger->source_model!='Grns')){
							for($inc=0.01;$inc<$StockLedger->quantity;$inc+=0.01){
								$stock[$Item->id][]=$StockLedger->rate/100;
							}
						}
					}
				}
				foreach($StockLedgers as $StockLedger){
					if($StockLedger->in_out=='Out' and $StockLedger->processed_on<$date){
						if(sizeof(@$stock[$Item->id])>0){
							$stock[$Item->id] = array_slice($stock[$Item->id], $StockLedger->quantity*100); 
						}
					}
				}
				if(sizeof(@$stock[$Item->id]) > 0){ 
					foreach(@$stock[$Item->id] as $stockRate){
						@$sumValue+=@$stockRate;
					}
				}
			}else if(@$Item->item_companies[0]->serial_number_enable==1){
				if(strtotime($date)==strtotime('2017-4-1')){
					$ItemSerialNumbers=$this->ItemLedgers->SerialNumbers->find()->where(['SerialNumbers.item_id'=>$Item->id,'SerialNumbers.company_id'=>$st_company_id,'status'=>'In','transaction_date <= '=>$date])->toArray();
				}else{
					$ItemSerialNumbers=$this->ItemLedgers->SerialNumbers->find()->where(['SerialNumbers.item_id'=>$Item->id,'SerialNumbers.company_id'=>$st_company_id,'status'=>'In','transaction_date < '=>$date])->toArray();
				}
				
				foreach($ItemSerialNumbers as $ItemSerialNumber){ 		
					if(@$ItemSerialNumber->grn_id > 0){ 
					$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id,'transaction_date < '=>$date]);
						if($outExist == 0){ 
							$ItemLedgerData =$this->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->grn_id,'source_model'=>"Grns",'source_row_id'=>$ItemSerialNumber->grn_row_id,'ItemLedgers.processed_on <='=>$date])->first();
						//	pr($ItemLedgerData); 
							if($ItemLedgerData){
								@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
								@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
					if(@$ItemSerialNumber->sale_return_id > 0){ 
					$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id,'transaction_date < '=>$date]);
						if($outExist == 0){
							$ItemLedgerData =$this->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->sale_return_id,'source_model'=>"Sale Return",'source_row_id'=>$ItemSerialNumber->sales_return_row_id,'ItemLedgers.processed_on <='=>$date])->first();
						//	pr($ItemLedgerData); 
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
					if(@$ItemSerialNumber->itv_id > 0){
					$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id,'transaction_date < '=>$date]); 
						if($outExist == 0){  
							$ItemLedgerData =$this->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->itv_id,'source_model'=>"Inventory Transfer Voucher",'source_row_id'=>$ItemSerialNumber->itv_row_id,'ItemLedgers.processed_on <='=>$date])->first();
							//pr($ItemLedgerData); 
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
					if(@$ItemSerialNumber->iv_row_id > 0){
					$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id,'transaction_date < '=>$date]); 
						if($outExist == 0){  
							$ItemLedgerData =$this->ItemLedgers->find()->where(['source_model'=>"Inventory Vouchers",'iv_row_id'=>$ItemSerialNumber->iv_row_id,'ItemLedgers.processed_on <='=>$date])->first();
							//pr($ItemLedgerData); 
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
					if(@$ItemSerialNumber->is_opening_balance == "Yes"){ //pr($ItemSerialNumber->id);
						if(strtotime($date)==strtotime('2017-4-1')){
							$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id,'transaction_date < '=>$date]); 
						}else{
							$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id,'transaction_date < '=>$date]); 
						}
					
						if($outExist == 0){
							$ItemLedgerData =$this->ItemLedgers->find()->where(['ItemLedgers.item_id'=>$Item->id,'source_model'=>"Items",'company_id'=>$st_company_id,'ItemLedgers.processed_on <='=>$date])->first();
							
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;//pr(@$ItemLedgerData['rate']);
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
				}
			
		}
		}
		//pr($sumValue); exit;
		return round($sumValue,2);
	}
	
	public function stockValuationWithDate2($date=null){ 
		$session = $this->request->session();
		$st_company_id = $session->read('st_company_id');
		//$date=date('Y-m-d');
		//pr($date);exit;
		//$date=$this->request->query('date');
		$date=date("Y-m-d",strtotime($date));
	
		$this->loadModel('ItemLedgers');
		$Items =$this->ItemLedgers->Items->find()->contain(['ItemCompanies'=>function($p) use($st_company_id){
		return $p->where(['ItemCompanies.company_id' => $st_company_id,'ItemCompanies.freeze' => 0]);
		}]);
		
		$stock=[];  $sumValue=0; $itemSerialRate=[]; $itemSerialQuantity=[];
		foreach($Items as $Item){
			if(@$Item->item_companies[0]->serial_number_enable==0){  
				$StockLedgers=$this->ItemLedgers->find()->where(['ItemLedgers.item_id'=>$Item->id,'ItemLedgers.company_id'=>$st_company_id,'ItemLedgers.processed_on <='=>$date])->order(['ItemLedgers.processed_on'=>'ASC']);
				foreach($StockLedgers as $StockLedger){ 
					if($StockLedger->in_out=='In'){ 
						if(($StockLedger->source_model=='Grns' and $StockLedger->rate_updated=='Yes') or ($StockLedger->source_model!='Grns')){
							for($inc=0.01;$inc<$StockLedger->quantity;$inc+=0.01){
								$stock[$Item->id][]=$StockLedger->rate/100;
							}
						}
					}
				}
				foreach($StockLedgers as $StockLedger){
					if($StockLedger->in_out=='Out' and $StockLedger->processed_on<$date){
						if(sizeof(@$stock[$Item->id])>0){
							$stock[$Item->id] = array_slice($stock[$Item->id],  $StockLedger->quantity*100); 
						}
					}
				}
				if(sizeof(@$stock[$Item->id]) > 0){ 
					foreach(@$stock[$Item->id] as $stockRate){
						@$sumValue+=@$stockRate;
					}
				}
			}else if(@$Item->item_companies[0]->serial_number_enable==1){
				$ItemSerialNumbers=$this->ItemLedgers->SerialNumbers->find()->where(['SerialNumbers.item_id'=>$Item->id,'SerialNumbers.company_id'=>$st_company_id,'status'=>'In','transaction_date <= '=>$date])->toArray();
				foreach($ItemSerialNumbers as $ItemSerialNumber){		
					if(@$ItemSerialNumber->grn_id > 0){ 
					$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id,'transaction_date <= '=>$date]);
						if($outExist == 0){
							$ItemLedgerData =$this->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->grn_id,'source_model'=>"Grns",'source_row_id'=>$ItemSerialNumber->grn_row_id,'ItemLedgers.processed_on <='=>$date])->first();
						//	pr($ItemLedgerData); 
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
					if(@$ItemSerialNumber->sale_return_id > 0){ 
					$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id,'transaction_date <= '=>$date]);
						if($outExist == 0){
							$ItemLedgerData =$this->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->sale_return_id,'source_model'=>"Sale Return",'source_row_id'=>$ItemSerialNumber->sales_return_row_id,'ItemLedgers.processed_on <='=>$date])->first();
						//	pr($ItemLedgerData); 
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
					if(@$ItemSerialNumber->itv_id > 0){
					$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id,'transaction_date <= '=>$date]); 
						if($outExist == 0){  
							$ItemLedgerData =$this->ItemLedgers->find()->where(['source_id'=>$ItemSerialNumber->itv_id,'source_model'=>"Inventory Transfer Voucher",'source_row_id'=>$ItemSerialNumber->itv_row_id,'ItemLedgers.processed_on <='=>$date])->first();
							//pr($ItemLedgerData); 
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
					if(@$ItemSerialNumber->iv_row_id > 0){
					$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id,'transaction_date <= '=>$date]); 
						if($outExist == 0){  
							$ItemLedgerData =$this->ItemLedgers->find()->where(['source_model'=>"Inventory Vouchers",'iv_row_id'=>$ItemSerialNumber->iv_row_id,'ItemLedgers.processed_on <='=>$date])->first();
							//pr($ItemLedgerData); 
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
					if(@$ItemSerialNumber->is_opening_balance == "Yes"){
					$outExist = $this->ItemLedgers->Items->SerialNumbers->exists(['SerialNumbers.parent_id' => $ItemSerialNumber->id,'transaction_date <= '=>$date]); 
						if($outExist == 0){ 
							$ItemLedgerData =$this->ItemLedgers->find()->where(['ItemLedgers.item_id'=>$Item->id,'source_model'=>"Items",'company_id'=>$st_company_id,'ItemLedgers.processed_on <='=>$date])->first();
							
							if($ItemLedgerData){
							@$itemSerialQuantity[@$ItemSerialNumber->item_id]=$itemSerialQuantity[@$ItemSerialNumber->item_id]+1;
							@$sumValue+=@$ItemLedgerData['rate'];
							}
						}
					}
				}
			
		}
		}
		
		
		return round($sumValue,2);
	}
	
	public function differenceInOpeningBalance(){
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		
		$this->loadModel('Ledgers');
		$Ledgers=$this->Ledgers->find()->where(['Ledgers.company_id'=>$st_company_id, 'Ledgers.voucher_source'=> 'Opening Balance']);
		
		$output=0;
		foreach($Ledgers as $Ledger){
			$output+=$Ledger->debit;
			$output-=$Ledger->credit;
		}
		
		$this->loadModel('ItemLedgers');
		$ItemLedgers=$this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$st_company_id, 'ItemLedgers.source_model'=> 'Items']);
		
		foreach($ItemLedgers as $ItemLedger){
			$output+=$ItemLedger->quantity*$ItemLedger->rate;
		}
		return round($output,2);
	}
	
	public function GrossProfit($from_date,$to_date){
		$session = $this->request->session();
        $st_company_id = $session->read('st_company_id');
		$AccountCategories=$this->Ledgers->LedgerAccounts->AccountSecondSubgroups->AccountFirstSubgroups->AccountGroups->AccountCategories->find()
		->where(['AccountCategories.id In'=>[3,4]])
		->contain(['AccountGroups.AccountFirstSubgroups.AccountSecondSubgroups.LedgerAccounts']);
		
		$groupForPrint=[];
		foreach($AccountCategories as $AccountCategory){
			foreach($AccountCategory->account_groups as $account_group){
				foreach($account_group->account_first_subgroups as $account_first_subgroup){
					foreach($account_first_subgroup->account_second_subgroups as $account_second_subgroup){
						foreach($account_second_subgroup->ledger_accounts as $ledger_account){
							$query=$this->Ledgers->find();
							$query->select(['ledger_account_id','totalDebit' => $query->func()->sum('Ledgers.debit'),'totalCredit' => $query->func()->sum('Ledgers.credit')])
							->where(['Ledgers.ledger_account_id'=>$ledger_account->id,    'Ledgers.transaction_date <='=>$to_date])->first();
							@$groupForPrint[$account_group->id]['name']=@$account_group->name;
							@$groupForPrint[$account_group->id]['balance']+=@$query->first()->totalDebit-@$query->first()->totalCredit;
						}
					}
				}
			}
		}
		//pr($groupForPrint); exit;
		$totalDr=0; $totalCr=0;
		foreach($groupForPrint as $groupForPrintRow){
			if($groupForPrintRow['balance']>0){
				$totalDr+=abs($groupForPrintRow['balance']);
			}else{
				$totalCr+=abs($groupForPrintRow['balance']);
			}
		}
		
		$openingValue= $this->StockValuationWithDate(date('Y-m-d',strtotime('2017-4-1')));
		$closingValue= $this->StockValuationWithDate2($to_date);
		
		$totalDr+=$openingValue;
		$totalCr+=$closingValue;
		return round($totalCr-$totalDr,2);
	}

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }
	   
}
