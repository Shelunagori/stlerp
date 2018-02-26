<?php 
$jobCardStatus=$status;
	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="Job_Cards_report_".$date.'_'.$time;

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
			<td colspan="6" align="center">
			<b> Job Cards Report
			<?php if(!empty($Required_From) || !empty($Required_To)){ echo date('d-m-Y',strtotime($Required_From)); ?> TO <?php echo date('d-m-Y',strtotime($Required_To));  } ?> 
			<?php if(!empty($Created_From) || !empty($Created_To)){ echo date('d-m-Y',strtotime($Created_From)); ?> TO <?php echo date('d-m-Y',strtotime($Created_To));} ?>
			</b>
			</td>
		</tr>
		<tr>
			<th >Sr.No.</th>
			<th>Job Card No.</th>
			<th>Sales Order</th>
			<th>Required Date</th>
			<th>Created Date</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		  <?php  $i=1;  foreach ($jobCards as $jobCard): 
		  if(in_array($jobCard->created_by,$allowed_emp)){
			$so=@$SalesOrderQty[@$jobCard->sales_order_id];
			$in=@$InvoiceQty[@$jobCard->sales_order_id];
			$iv=@$InventoryVoucherQty[@$jobCard->sales_order_id];
			
			if(($jobCardStatus==null || $jobCardStatus=='Pending')){ 
				if($so != $in || $so != $iv || $in != $iv ){
			?>
		<tr>
			<td><?= h($i++) ?></td>
			<td><?= h(($jobCard->jc1.'/JC-'.str_pad($jobCard->jc2, 3, '0', STR_PAD_LEFT).'/'.$jobCard->jc3.'/'.$jobCard->jc4))?></td>
			<td><?= h(($jobCard->sales_order->so1.'/SO-'.str_pad($jobCard->sales_order->so2, 3, '0', STR_PAD_LEFT).'/'.$jobCard->sales_order->so3.'/'.$jobCard->sales_order->so4))?></td>
			<td><?= date("d-m-Y",strtotime($jobCard->required_date));?></td>
			<td><?= date("d-m-Y",strtotime($jobCard->created_on));?></td> 
			<td><?php 
			
				echo ucwords($jobCardStatus);
			
			 ?></td>			
		</tr>
			<?php } } else if($jobCardStatus=='Closed'){
					if((($so == $in) && ($so == $iv) && ($so == $iv) )  || $jobCard->status=="Closed"){
			?>
			<tr>
			<td><?= h($i++) ?></td>
			<td><?= h(($jobCard->jc1.'/JC-'.str_pad($jobCard->jc2, 3, '0', STR_PAD_LEFT).'/'.$jobCard->jc3.'/'.$jobCard->jc4))?></td>
			<td><?= h(($jobCard->sales_order->so1.'/SO-'.str_pad($jobCard->sales_order->so2, 3, '0', STR_PAD_LEFT).'/'.$jobCard->sales_order->so3.'/'.$jobCard->sales_order->so4))?></td>
			<td><?= date("d-m-Y",strtotime($jobCard->required_date));?></td>
			<td><?= date("d-m-Y",strtotime($jobCard->created_on));?></td> 
			<td><?php 
			
				echo ucwords($jobCardStatus);
			
			 ?></td>	
		  <?php } } } endforeach;?>			 
		</tr>
	</tbody>
</table>	