<?php $url_excel="/?".$url;?>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Account Statement For Reference Balance</span>
		</div>
		<div class="actions">
			<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/Ledgers/Excel-Export-Account-Ref/'.$url_excel.'',['class' =>'btn btn-sm green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
		</div>
<div class="portlet-body form">
	<form method="GET" >
		<table class="table table-condensed" >
			<tbody>
				<tr>
					<td width="15%">
						<?php echo $this->Form->input('ledgerid', ['empty'=>'--Select--','options' => $options,'empty' => "--Select Ledger Account--",'label' => false,'class' => 'form-control input-sm input-medium  select2me','required','value'=>@$ledger_account_id]); ?>
						<?php echo $this->Form->input('status', ['type'=>'hidden','value'=>'Pending']); ?>
					</td>
					<td width="75%">
						<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
					</td>
					
				</tr>
			</tbody>
		</table>
	</form>
		<!-- BEGIN FORM-->
<?php if(!empty($Ledger_Account_data)){  ?>
		<div class="row ">
			<div class="col-md-12">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<div class="col-md-12" style="text-align:center; font-size: 20px;">Statement of Account As on  <?php echo date('d-m-Y');?> of<div class="uppercase"><?php 
					if(!empty($Ledger_Account_data->alias)){
					echo $Ledger_Account_data->name.' ('.$Ledger_Account_data->alias.')'; }else{
						echo $Ledger_Account_data->name;
					}
					?></div></div>
				</div>
				<div class="col-md-2"></div>
			</div>
		</div><br/>


		<div class="row ">
		<div class="col-md-12">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Invoice No.</th>
						<th>Transaction Date</th>
						<th>Due Date</th>
						
						<th>PO Number</th>
						<th>PO Date</th>
						<th style="text-align:right;">Dr</th>
						<th style="text-align:right;">Cr</th>
					</tr>
				</thead>
				<tbody>
				<?php $total_debit=0; $total_credit=0;$payment_terms=0; foreach($ReferenceBalances as $key=>$ReferenceBalance){ 
				
				$default_date="2017-04-01";
				//pr($key); 
				//pr(); exit;
					if($ReferenceBalance['reference_type']!="On_account"){
						if(!empty($customer_data->payment_terms)){
							$payment_terms=$customer_data->payment_terms;
						}else{
							$payment_terms=0;
						}
					
					if($Ledger_Account_data->source_model=="Vendors"){
								//echo (date('d-m-Y',strtotime(@$refInvoiceBookingNo[$key]['supplier_date']))); 
								$due_date=date('Y-m-d', strtotime(@$refInvoiceBookingNo[$key]['supplier_date']. ' +'. $payment_terms .'days'));
					}else{
						$due_date=date('Y-m-d', strtotime($ReferenceBalance['transaction_date']. ' +'. $payment_terms .'days'));
					}
					
					if($Ledger_Account_data->source_model=="Vendors" && $ReferenceBalance['opening_balance']!="Yes" && !empty($refInvoiceBookingNo[$key])){
								$due_date=date('Y-m-d', strtotime(@$refInvoiceBookingNo[$key]['supplier_date']. ' +'. $payment_terms .'days'));
					}else if($Ledger_Account_data->source_model=="Customers" && $ReferenceBalance['opening_balance']!="Yes"){
						$due_date=date('Y-m-d', strtotime(@$Invoice_data[$refInvoiceNo[$key]]['date_created']. ' +'. $payment_terms .'days'));
					}else if($ReferenceBalance['opening_balance']=="Yes"){
						$due_date=date('Y-m-d', strtotime($default_date. ' +'. $payment_terms .'days'));
						
					}else{
						$due_date=date('Y-m-d', strtotime($default_date. ' +'. $ReferenceBalance['transaction_date'] .'days'));
						
					}
			$amt=round($DueReferenceBalances[$key]);
				if($amt != 0){
					$td=date('d-m-Y',strtotime(@$Invoice_data[$refInvoiceNo[$key]]['date_created']));
					
				?>
				
				<tr>
						<td> <?php 
							if($ReferenceBalance['opening_balance']=="Yes"){
								echo "Opening Balance";
							}else if($td =="01-01-1970"){ 
								echo $ReferenceBalance['reference_no'];
							}else if(@$Voucher_data[$key]){  
								echo @$Voucher_data[$key];
							}
								 ?> </td>
						<td><?php 
							if($Ledger_Account_data->source_model=="Vendors" && $ReferenceBalance['opening_balance']!="Yes" && !empty($refInvoiceBookingNo[$key])){
								echo (date('d-m-Y',strtotime(@$refInvoiceBookingNo[$key]['supplier_date']))); 
							}else if($Ledger_Account_data->source_model=="Customers" && $ReferenceBalance['opening_balance']!="Yes" && $td !="01-01-1970"   ){ 
								echo (date('d-m-Y',strtotime(@$Invoice_data[$refInvoiceNo[$key]]['date_created']))); 
							}else if($ReferenceBalance['opening_balance']=="Yes" ){
								echo (date('d-m-Y',strtotime($default_date)));
							}else if($td =="01-01-1970"){ 
								
							}else{ 
								echo (date('d-m-Y',strtotime($ReferenceBalance['transaction_date'])));
							}
						
						 ?></td>
						<td><?php 
						 if($Ledger_Account_data->source_model=="Vendors" && $DueReferenceBalances[$key] < 0 && $td !="01-01-1970"){
							echo (date('d-m-Y',strtotime($due_date))); 
						}else if($Ledger_Account_data->source_model=="Customers" && $DueReferenceBalances[$key] > 0 && $td !="01-01-1970"){
							echo (date('d-m-Y',strtotime($due_date))); 
						}else if($td =="01-01-1970"){ 
								if(date('d-m-Y',strtotime($due_date))=="01-01-1970"){
								}else{
								echo (date('d-m-Y',strtotime($due_date)));
								}
						}else if($due_date){
							echo (date('d-m-Y',strtotime($due_date))); 
						}
						?></td>
						<td>
						<?php if(@$Ledger_Account_data->source_model=="Vendors" && !empty(@$refInvoiceBookingNo[$key]) && @$ReferenceBalance['opening_balance'] != "Yes"){
								echo @$refInvoiceBookingNo[$key]['grn']['purchase_order']->po1.'/PO-'.str_pad(@$refInvoiceBookingNo[$key]['grn']['purchase_order']->po2, 3, '0', STR_PAD_LEFT).'/'.@@$refInvoiceBookingNo[$key]['grn']['purchase_order']->po3.'/'.@$refInvoiceBookingNo[$key]['grn']['purchase_order']->po4;
								//echo $refInvoiceBookingNo[$key]['grn']['purchase_order']; 
							}else if($Ledger_Account_data->source_model=="Customers"&& @$ReferenceBalance['opening_balance'] != "Yes"){ 
								echo @$Invoice_data[@$refInvoiceNo[$key]]['customer_po_no']; 
								
							}else{
								
							}?>
						</td>
						<td>
						<?php	
							 if(@$Ledger_Account_data->source_model=="Vendors" && !empty(@$refInvoiceBookingNo[$key]) && @$ReferenceBalance['opening_balance'] != "Yes"){
								
								echo (date('d-m-Y',strtotime($refInvoiceBookingNo[$key]['grn']['purchase_order']->date_created))); 
							}else if($Ledger_Account_data->source_model=="Customers" && $ReferenceBalance['opening_balance'] != "Yes"){
								echo (date('d-m-Y',strtotime(@$Invoice_data[@$refInvoiceNo[$key]]['po_date']))); 
							}else{
								
							}?>
						</td>
						<?php if(round($DueReferenceBalances[$key]) > 0){
							$total_debit+=$DueReferenceBalances[$key];
							$bal_status='';
							if($ReferenceBalance['credit'] > 0){
								$bal_status="<span style='color:red'>(BAL)</span>";
							}
							?>
							<td align="right"><?php echo $bal_status; ?> <?= $this->Number->format($DueReferenceBalances[$key],[ 'places' => 2]); ?></td>
							<td align="right"><?php echo "0"; ?></td>
						<?php }else if(round($DueReferenceBalances[$key]) < 0){
							 $total_credit+=abs($DueReferenceBalances[$key]);
							 $bal_status='';
							if($ReferenceBalance['debit'] > 0){
								$bal_status="<span style='color:red'>(BAL)</span>";
							}
							?>
							<td align="right"><?php echo "0"; ?></td>
							<td align="right"><?php echo $bal_status; ?><?= $this->Number->format(abs($DueReferenceBalances[$key]),[ 'places' => 2]); ?></td>
						<?php } ?>
						
						<?php 
							   ?>

				</tr>
				<?php } } } 
				
				
				?>
				<tr>
					<td align="right" colspan="5">Total</td>
					<td align="right"><?= $this->Number->format($total_debit,[ 'places' => 2]); ?>Dr.</td>
					<td align="right"><?= $this->Number->format($total_credit,[ 'places' => 2]); ?>Cr.</td>
				</tr>
				<tr>
					<td  align="right" colspan="5">On Account</td>	
					<?php 
						$on_acc=0;
						$on_acc=$on_dr-$on_cr;
						?>
					<?php if($on_acc >= 0){ 
					$closing_balance=($on_acc+$total_debit)-$total_credit;
					?>
								<td align="right"><?php echo $this->Number->format(abs($on_acc),['places'=>2]); ?>Dr.</td>	
								<td align="right">0 Cr.</td>
							<?php } else{ 
							$closing_balance=(abs($on_acc)+$total_credit)-abs($total_debit);
							?>
								<td align="right">0 Dr.</td>
								<td align="right"><?php echo $this->Number->format(abs($on_acc),['places'=>2]); ?>Cr.</td>
					
					<?php } ?>
				</tr>
				</tbody>
			</table>
			</div>
			<?php
			if($on_dr > $on_cr){
					$total_debit=$total_debit+($on_dr-$on_cr);
				}else{
					$total_credit=$total_credit+($on_cr-$on_dr);
				}
			?>
			<div class="col-md-12">
				<div class="col-md-8"></div>	
				<div class="col-md-4 caption-subject " align="left" style="background-color:#E3F2EE; font-size: 16px;"><b>Closing Balance: </b>
				<?php if($total_debit > $total_credit){
					echo $this->Number->format(abs($total_debit-$total_credit),['places'=>2]).'Dr.'; 
				}else{
					echo $this->Number->format(abs($total_credit-$total_debit),['places'=>2]).'Cr.'; 
				}	
				?>
				</div>
			</div>
			
		</div>
<?php } ?>
</div></div>
</div>