<?php 

	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="outstanding_report_customer_".$date.'_'.$time;

	/* header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/vnd.ms-excel");
	header ("Content-Disposition: attachment; filename=".$filename.".xls");
	header ("Content-Description: Generated Report" ); */

?>



<table border="1">
	<thead>
		<tr>
			<td colspan="12" align="center">
				<b> Outstanding report Customer
			</td>
		</tr>	
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
		</tr>
	</thead>
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
				}else if(@$Emp_department_id->dipartment_id == 2){
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
		//	pr($amountType); exit;
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
				<?php echo  $LedgerAccount->name." (". $LedgerAccount->alias.")"; 
				}else{ 
					echo $LedgerAccount->name;
					
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
				
			</tr>
			<?php }}else if(@$Emp_department_id->dipartment_id == 2){
				if(@$ClosingBalanceLedgerWise[$LedgerAccount->id]=="Yes"){
				if(!empty(@$LedgerAccount->customer)){
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
				
				
				<?php }}} }?>
			</tbody>
			<tfoot id='tf'>
				<tr>
					<th colspan="8"><div  align="right">TOTAL</div></th>
					<th class="oa"><?php echo round($ColumnOnAccount,2); ?></th>
					<th class="os"><?php echo round($ColumnOutStanding,2); ?></th>
					<th class="nd"><?php echo round($ColumnNoDue,2); ?></th>
					<th class="cb"><?php echo (number_format((float)$ColumnClosingBalance, 2, '.', '')); ?></th>
					
				</tr>
			</tfoot>
			</table>
			