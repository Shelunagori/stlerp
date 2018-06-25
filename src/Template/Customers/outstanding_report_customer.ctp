<style>
table td, table th{
    white-space: nowrap;
	font-size:12px !important;
}
.clrRed{
	color:red;
}
</style>
<?php  $url_excel="/?".$url; 

?>
<div class="portlet box red">
	
	<div class="portlet-title">
		<div class="caption">Outstandings for Customers for <?php echo $to_send['tdate']; ?></div>
	</div>
	
	<div class="portlet-body">
	<form method="GET" >
		<table class="table table-condensed" width="100%">
			<tbody>
				<tr>
					<?php
						$EMP_ID =[23,16,17];
					if(in_array($s_employee_id,$EMP_ID)){ ?>
							<td width="15%"><?php echo $this->Form->input('salesman_name', ['empty'=>'--SalesMans--','options' => $SalesMans,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'SalesMan Name','value'=> h(@$salesman_name) ]); ?></td>
					<?php }else{ ?>
						<td width="15%"><?php echo $this->Form->input('salesman_name', ['empty'=>'--SalesMans--','options' => $SalesMans,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'SalesMan Name','value'=> h(@$s_employee_id) ]); ?></td>
					<?php } ?>
						<td width="15%">
							<?php 
							$options = [];
							$options = [['text'=>'All','value'=>'All'],['text'=>'Zero','value'=>'Zero'],['text'=>'Negative','value'=>'Negative'],['text'=>'Positive','value'=>'Positive']];
							echo $this->Form->input('total1', ['options' => $options,'label' => false,'class' => 'form-control input-sm select2me stock1','placeholder'=>'Sub-Group','value'=> h(@$amountType)]); ?>
						</td>
					
						
						<td>
								<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
							</td>
						
					   <td align="right" width="10%"><input type="text" class="form-control input-sm pull-right" placeholder="Search..." id="search3"  style="width: 100%;"></td>
					   <td align="right" width="8%">
							<?php $url=json_encode($to_send, true);
						if(!empty($salesman_name)){
							 echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/Customers/Customer-Export-Excel/'.$url.'?salesman_name='.$salesman_name.'&total1='.$amountType,['class' =>'btn  green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']);
						}else{
							 echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/Customers/Customer-Export-Excel/'.$url.'?total1='.$amountType,['class' =>'btn  green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']);
						}	
			             ?>
					   </td>
				</tr>
			</tbody>
		</table>
	</form>
	</form>
	<div class="table-toolbar">
		<div class="row">
			<div class="col-md-6"></div>
			<div class="col-md-6" >
				<div class="btn-group" style="float:right;">
					
				</div>
			</div>
		</div>
	</div>
		<div class="table-responsive" >
		
			<table class="table table-bordered" id="main_tble">
			<thead>
			<tr>
				<th>#</th>
				<th>Customer</th>
				<th>Payment Term</th>
				<th><?php echo $to_send['range0'].' - '.$to_send['range1'].' Days'; ?></th>
				<th><?php echo $to_send['range2'].' - '.$to_send['range3'].' Days'; ?></th>
				<th><?php echo $to_send['range4'].' - '.$to_send['range5'].' Days'; ?></th>
				<th><?php echo $to_send['range6'].' - '.$to_send['range7'].' Days'; ?></th>
				<th><?php echo ' > '.$to_send['range7'].' Days'; ?></th>
				<th>On Account</th>
				<th>Total Outstanding</th>
				<th>No-Due</th>
				<th>Closing Balance</th>
				<th>Send Mail</th>
			</tr>
			</thead>
			<tbody>
			<?php 
			$ClosingBalanceLedgerWise=[];
			foreach($LedgerAccounts as $LedgerAccount){
				if(in_array(@$LedgerAccount->customer->employee_id,@$allowed_emp)){
					$ttlamt=round(@$Outstanding[$LedgerAccount->id]['Slab1']+@$Outstanding[$LedgerAccount->id]['Slab2']+@$Outstanding[$LedgerAccount->id]['Slab3']+@$Outstanding[$LedgerAccount->id]['Slab4']+@$Outstanding[$LedgerAccount->id]['Slab5']+@$Outstanding[$LedgerAccount->id]['NoDue']+@$Outstanding[$LedgerAccount->id]['OnAccount'],2);
					
					if($amountType=='Zero' && $ttlamt==0){
						$ClosingBalanceLedgerWise[$LedgerAccount->id]= "Yes";
					}else if($amountType=='Positive' && $ttlamt > 0 ){ 
						$ClosingBalanceLedgerWise[$LedgerAccount->id]= "Yes";
					}else if($amountType=='Negative' && $ttlamt < 0 ){
						//$ClosingBalanceLedgerWise[$LedgerAccount->id]= $ttlamt;
						$ClosingBalanceLedgerWise[$LedgerAccount->id]= "Yes";
					}else if($amountType=='All'){
						//$ClosingBalanceLedgerWise[$LedgerAccount->id]= $ttlamt;
						$ClosingBalanceLedgerWise[$LedgerAccount->id]= "Yes";
					}else{
						$ClosingBalanceLedgerWise[$LedgerAccount->id]= "No";
					}
				}
			}
			//pr($amountType);
			


			$sr=0; $ClosingBalance=0; 
			$ColumnOnAccount=0; $ColumnOutStanding=0; $ColumnNoDue=0; $ColumnClosingBalance=0;
			foreach($LedgerAccounts as $LedgerAccount){ 
			if(in_array(@$LedgerAccount->customer->employee_id,@$allowed_emp)){
			
				if($ClosingBalanceLedgerWise[$LedgerAccount->id]=="Yes"){
			
			?>
			<tr>
				<td><?php echo ++$sr; ?></td>
				<td style=" white-space: normal; width: 200px; ">
				<?php if(!empty($LedgerAccount->alias)){ ?>
				<?php echo  $this->Html->link( $LedgerAccount->name." (". $LedgerAccount->alias.")",[
							'controller'=>'Ledgers','action' => 'AccountStatementRefrence?status=completed&ledgerid='.$LedgerAccount->id],array('target'=>'_blank')); 
				}else{ 
					echo $this->Html->link($LedgerAccount->name,[
							'controller'=>'Ledgers','action' => 'AccountStatementRefrence?status=completed&ledgerid='.$LedgerAccount->id],array('target'=>'_blank'));
					
				}		?></td>
				<td><?php echo $CustmerPaymentTerms[$LedgerAccount->id].' Days'; ?></td>
				<td>
					<?php if(@$Outstanding[$LedgerAccount->id]['Slab1'] > 0){
						echo '<span class="clrRed">'.round(@$Outstanding[$LedgerAccount->id]['Slab1'],2).'</span>';
					}else{
						echo '<span>'.round(@$Outstanding[$LedgerAccount->id]['Slab1'],2).'</span>';
					} ?>
				</td>
				<td>
					<?php if(@$Outstanding[$LedgerAccount->id]['Slab2'] > 0){
						echo '<span class="clrRed">'.round(@$Outstanding[$LedgerAccount->id]['Slab2'],2).'</span>';
					}else{
						echo '<span>'.round(@$Outstanding[$LedgerAccount->id]['Slab2'],2).'</span>';
					} ?>
				</td>
				<td>
					<?php if(@$Outstanding[$LedgerAccount->id]['Slab3'] > 0){
						echo '<span class="clrRed">'.round(@$Outstanding[$LedgerAccount->id]['Slab3'],2).'</span>';
					}else{
						echo '<span>'.round(@$Outstanding[$LedgerAccount->id]['Slab3'],2).'</span>';
					} ?>
				</td>
				<td>
					<?php if(@$Outstanding[$LedgerAccount->id]['Slab4'] > 0){
						echo '<span class="clrRed">'.round(@$Outstanding[$LedgerAccount->id]['Slab4'],2).'</span>';
					}else{
						echo '<span>'.round(@$Outstanding[$LedgerAccount->id]['Slab4'],2).'</span>';
					} ?>
				</td>
				<td>
					<?php if(@$Outstanding[$LedgerAccount->id]['Slab5'] > 0){
						echo '<span class="clrRed">'.round(@$Outstanding[$LedgerAccount->id]['Slab5'],2).'</span>';
					}else{
						echo '<span>'.round(@$Outstanding[$LedgerAccount->id]['Slab5'],2).'</span>';
					} ?>
				</td>
				
				<td>
				<?php 
					echo round(@$Outstanding[$LedgerAccount->id]['OnAccount'],2); 
					@$ColumnOnAccount+=@$Outstanding[$LedgerAccount->id]['OnAccount'];
				?>
				</td>
				<td>
				<?php $TotalOutStanding=@$Outstanding[$LedgerAccount->id]['Slab1']+@$Outstanding[$LedgerAccount->id]['Slab2']+@$Outstanding[$LedgerAccount->id]['Slab3']+@$Outstanding[$LedgerAccount->id]['Slab4']+@$Outstanding[$LedgerAccount->id]['Slab5']+@$Outstanding[$LedgerAccount->id]['OnAccount']; ?>
				<?php 
				if($TotalOutStanding>0){
					echo '<span id="outstnd" class="clrRed">'.round(@$TotalOutStanding,2).'</span>';
				}elseif($TotalOutStanding<0){
					echo '<span id="outstnd">'.round(@$TotalOutStanding,2).'</span>';
				} ?>
				<?php
					@$ColumnOutStanding+=@$TotalOutStanding;
				?>
				</td>
				<td>
					<?php 
					echo round(@$Outstanding[$LedgerAccount->id]['NoDue'],2);
					@$ColumnNoDue+=@$Outstanding[$LedgerAccount->id]['NoDue'];
					?>
				</td>
				<td>
				<?php $ClosingBalance=@$Outstanding[$LedgerAccount->id]['Slab1']+@$Outstanding[$LedgerAccount->id]['Slab2']+@$Outstanding[$LedgerAccount->id]['Slab3']+@$Outstanding[$LedgerAccount->id]['Slab4']+@$Outstanding[$LedgerAccount->id]['Slab5']+@$Outstanding[$LedgerAccount->id]['NoDue']+@$Outstanding[$LedgerAccount->id]['OnAccount']; ?>
				<?php if($ClosingBalance!=0){
					echo round($ClosingBalance,2);
				}else{
					echo "0";
				} ?>
				<?php
					@$ColumnClosingBalance+=$ClosingBalance;
				?>
				</td>
				<td style="text-align:center;">
					<?php 
					$ClosingBalance= round($ClosingBalance,2);
					if($ClosingBalance > 0){ ?>
						
						<a href="#" class="btn-primary btn-sm send_mail" title="Send Email " amt=<?php echo $ClosingBalance; ?>  ledger_id=<?php echo $LedgerAccount->id; ?>><i class="fa fa-envelope"></i></a>
				<?php	} ?>
				</td>
			</tr>
			<?php }} } ?>
			</tbody>
			<tfoot id='tf'>
				<tr>
					<th colspan="8"><div  align="right">TOTAL</div></th>
					<th class="oa"><?php echo round($ColumnOnAccount,2); ?></th>
					<th class="os"><?php echo round($ColumnOutStanding,2); ?></th>
					<th class="nd"><?php echo round($ColumnNoDue,2); ?></th>
					<th class="cb"><?php echo (number_format((float)$ColumnClosingBalance, 2, '.', '')); ?></th>
					<th></th>
				</tr>
			</tfoot>
			</table>
		</div>
	</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
	
$('.send_mail').die().live("click",function() {
	if(confirm("Are you sure you want to Send Email?")){
		var amt=$(this).attr('amt');
		var ledger_id=$(this).attr('ledger_id');
		
		var url="<?php echo $this->Url->build(['controller'=>'Customers','action'=>'sendMail']); ?>";
		url=url+'?id='+ledger_id+'&amount='+amt;
		
		$.ajax({
			url: url,
			type: "GET",
		}).done(function(response) { 
		//alert(response);
			alert("Email Send successfully")
		}); 
	}else{
		  return false;
	}	
	
});	
	

var $rows = $('#main_tble tbody tr');
	$('#search3').on('keyup',function() {
	
			var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
    		var v = $(this).val();
    		if(v){ 
    			$rows.show().filter(function() {
    				var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
		
    				return !~text.indexOf(val);
    			}).hide();
    		}else{
    			$rows.show();
    		}
    	});
	/////
	$('.stock').die().live("change",function(){
		var stock = $(this).val();
			var total_closing_bal=0;
			var total_nodue=0;
			var total_out=0;
			var total_on_acc=0;
			$("#main_tble tbody tr").each(function(){
				var closing_bal = $(this).find("td:nth-child(12)").html();
				var no_due = $(this).find("td:nth-child(11)").html();
				var total_outstanding = 
				$(this).find("td:nth-child(10) #outstnd").html();
				
				var on_acc = $(this).find("td:nth-child(9)").html();
				
				
				
				if(stock =='Positive' && closing_bal > 0){
					$(this).show();
					total_closing_bal=parseFloat(total_closing_bal)+parseFloat(closing_bal);
					total_nodue=parseFloat(total_nodue)+parseFloat(no_due);
					total_out=parseFloat(total_out)+parseFloat(total_outstanding);
					total_on_acc=parseFloat(total_on_acc)+parseFloat(on_acc);
					total_on_acc=round(total_on_acc,2);
					total_closing_bal=round(total_closing_bal,2);
					total_nodue=round(total_nodue,2);
					$("#main_tble #tf tr th.oa").html('');
					$("#main_tble #tf tr th.os").html('');
					$("#main_tble #tf tr th.nd").html('');
					$("#main_tble #tf tr th.cb").html('');
					if(total_on_acc == 0){
						$("#main_tble #tf tr th.oa").html(0);
					}else{
						$("#main_tble #tf tr th.oa").html('');
						$("#main_tble #tf tr th.oa").html(total_on_acc);
					}
					if(total_out == 0){
						$("#main_tble #tf tr th.os").html(0);
					}else{
						$("#main_tble #tf tr th.os").html('');
						$("#main_tble #tf tr th.os").html(total_out);
					}
					if(total_nodue==0){
						$("#main_tble #tf tr th.nd").html(0);
					}else{
						$("#main_tble #tf tr th.nd").html('');
						$("#main_tble #tf tr th.nd").html(total_nodue);
					}	
					if(total_closing_bal==0){
						$("#main_tble #tf tr th.cb").html(0);
					}else{
						$("#main_tble #tf tr th.cb").html('');
						$("#main_tble #tf tr th.cb").html(total_closing_bal);
					}	
					
				}else if(stock =='Negative' && closing_bal < 0){
					$(this).show(); 
					total_closing_bal=parseFloat(total_closing_bal)+parseFloat(closing_bal);
					total_nodue=parseFloat(total_nodue)+parseFloat(no_due);
					total_out=parseFloat(total_out)+parseFloat(total_outstanding);
					total_on_acc=parseFloat(total_on_acc)+parseFloat(on_acc);
					total_nodue=round(total_nodue,2);
					total_on_acc=round(total_on_acc,2);
					total_closing_bal=round(total_closing_bal,2);
					
					
				
					if(total_on_acc == 0){
						$("#main_tble #tf tr th.oa").html(0);
					}else{
						$("#main_tble #tf tr th.oa").html('');
						$("#main_tble #tf tr th.oa").html(total_on_acc);
					}
					if(total_out == 0){
						$("#main_tble #tf tr th.os").html(0);
					}else{
						$("#main_tble #tf tr th.os").html('');
						$("#main_tble #tf tr th.os").html(total_out);
					}
					if(total_nodue==0){
						$("#main_tble #tf tr th.nd").html(0);
					}else{
						$("#main_tble #tf tr th.nd").html('');
						$("#main_tble #tf tr th.nd").html(total_nodue);
					}	
					if(total_closing_bal==0){
						$("#main_tble #tf tr th.cb").html(0);
					}else{
							$("#main_tble #tf tr th.cb").html('');
						$("#main_tble #tf tr th.cb").html(total_closing_bal);
					}	
				}else if(stock =='Zero' && closing_bal == 0){
					$(this).show();
					$("#main_tble #tf tr th.os").html(0);
					$("#main_tble #tf tr th.cb").html(0);
					$("#main_tble #tf tr th.oa").html(0);
					$("#main_tble #tf tr th.nd").html(0);
				}else{
					$(this).hide();
				}
				
				
			});
			
			rename_rows();
		
	});	

	function rename_rows(){ 
		var i=0;
		$("#main_tble tbody tr").each(function(){ //alert(i);
			$(this).find("td:nth-child(1)").val(i++);
		});
	}
});
		
</script>