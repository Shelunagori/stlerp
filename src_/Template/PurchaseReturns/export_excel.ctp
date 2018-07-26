<?php 

	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="Purchase_Return_report_".$date.'_'.$time;

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
			<td colspan="5" align="center">
				<b> Purchase Return Report
				<?php if(!empty($From) || !empty($To)){ if(date('d-m-Y',strtotime($From)) == "01-01-1970"){ echo ""; }else{ echo date('d-m-Y',strtotime($From)); } ?> TO <?php  if(date('d-m-Y',strtotime($To)) == "01-01-1970"){ echo ""; }else{date('d-m-Y',strtotime($To));}} ?> </b>
			</td>
		</tr>	
		<tr>
							<th>Sr. No.</th>
							<th>Supplier</th>
							<th>Purchase Return No</th>
							<th>Invoice Booking No</th>
							<th>Date</th>
						</tr>
				</thead>
				<tbody>
						<?php $i=1; foreach ($purchaseReturns as $purchaseReturn): 
						//pr($saleReturn); 
						?>
						<tr>
							<td><?= h($i++) ?></td>
							<td>
								<?= h($purchaseReturn->vendor->company_name);?>
							</td>
							<td>
							<?php $voucher=('DN/'.str_pad($purchaseReturn->voucher_no, 4, '0', STR_PAD_LEFT)); ?>
							<?php 
							$s_year_from = date("Y",strtotime($purchaseReturn->financial_year->date_from));
							$s_year_to = date("Y",strtotime($purchaseReturn->financial_year->date_to));
							$fy=(substr($s_year_from, -2).'-'.substr($s_year_to, -2)); 
							echo $voucher.'/'.$fy;
							?>
							</td>
							<td>
								<?= h($purchaseReturn->invoice_booking->ib1.'/IB-'.str_pad($purchaseReturn->invoice_booking->ib2, 3, '0', STR_PAD_LEFT).'/'.$purchaseReturn->invoice_booking->ib3.'/'.$purchaseReturn->invoice_booking->ib4);?>
							</td>
							<td><?php echo date("d-m-Y",strtotime($purchaseReturn->created_on)); ?></td>
						
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>