<?php 

	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="outstanding_report_vendor_".$date.'_'.$time;

	header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/vnd.ms-excel");
	header ("Content-Disposition: attachment; filename=".$filename.".xls");
	header ("Content-Description: Generated Report" );

?>



<table border="1">
	<thead>
		<tr>
			<td colspan="12" align="center">
				<b> Outstanding report Vendor
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
	<tbody>
	<?php 
		$ClosingBalanceLedgerWise=[];
			foreach($LedgerAccounts as $LedgerAccount){
				
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
	$sr=0; $ClosingBalance=0; 
	$ColumnOnAccount=0; $ColumnOutStanding=0; $ColumnNoDue=0; $ColumnClosingBalance=0;
	foreach($LedgerAccounts as $LedgerAccount){ 
		if($ClosingBalanceLedgerWise[$LedgerAccount->id]=="Yes"){
	?>
	<tr>
		<td><?php echo ++$sr; ?></td>
		<td style=" white-space: normal; width: 200px; "><?php echo $LedgerAccount->name.' <br/> '.$LedgerAccount->alias; ?></td>
		<td><?php echo $VendorPaymentTerms[$LedgerAccount->id].' Days'; ?></td>
		<td>
			<?php if(@$Outstanding[$LedgerAccount->id]['Slab1'] > 0){
				echo round(@$Outstanding[$LedgerAccount->id]['Slab1'],2);
			}else{
				echo round(@$Outstanding[$LedgerAccount->id]['Slab1'],2);
			} ?>
		</td>
		<td>
			<?php if(@$Outstanding[$LedgerAccount->id]['Slab2'] > 0){
				echo round(@$Outstanding[$LedgerAccount->id]['Slab2'],2);
			}else{
				echo round(@$Outstanding[$LedgerAccount->id]['Slab2'],2);
			} ?>
		</td>
		<td>
			<?php if(@$Outstanding[$LedgerAccount->id]['Slab3'] > 0){
				echo round(@$Outstanding[$LedgerAccount->id]['Slab3'],2);
			}else{
				echo round(@$Outstanding[$LedgerAccount->id]['Slab3'],2);
			} ?>
		</td>
		<td>
			<?php if(@$Outstanding[$LedgerAccount->id]['Slab4'] > 0){
				echo round(@$Outstanding[$LedgerAccount->id]['Slab4'],2);
			}else{
				echo round(@$Outstanding[$LedgerAccount->id]['Slab4'],2);
			} ?>
		</td>
		<td>
			<?php if(@$Outstanding[$LedgerAccount->id]['Slab5'] > 0){
				echo round(@$Outstanding[$LedgerAccount->id]['Slab5'],2);
			}else{
				echo round(@$Outstanding[$LedgerAccount->id]['Slab5'],2);
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
			echo round(@$TotalOutStanding,2);
		}elseif($TotalOutStanding<0){
			echo round(@$TotalOutStanding,2);
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
		} ?>
		<?php
			@$ColumnClosingBalance+=$ClosingBalance;
		?>
		</td>
	</tr>
	<?php } } ?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="8"><div  align="right">TOTAL</div></th>
			<th><?php echo round($ColumnOnAccount,2); ?></th>
			<th><?php echo round($ColumnOutStanding,2); ?></th>
			<th><?php echo round($ColumnNoDue,2); ?></th>
			<th><?php echo round($ColumnClosingBalance,2); ?></th>
		</tr>
	</tfoot>
</table>
			