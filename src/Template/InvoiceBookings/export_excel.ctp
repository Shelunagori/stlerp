<?php 

	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="Invoice_Booking_report_".$date.'_'.$time;

	header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/vnd.ms-excel");
	header ("Content-Disposition: attachment; filename=".$filename.".xls");
	header ("Content-Description: Generated Report" );

?>
<table border='1'>
		<thead>
				<tr>
					<td colspan="6" align="center">
					<b> Invoice Booking Report
					<?php if(!empty($From) || !empty($To)){ if(date('d-m-Y',strtotime($From)) == "01-01-1970"){ echo ""; }else{ echo date('d-m-Y',strtotime($From)); } ?> TO <?php  if(date('d-m-Y',strtotime($To)) == "01-01-1970"){ echo ""; }else{date('d-m-Y',strtotime($To));}} ?>
					
					</b>
					</td>
				</tr>
				<tr>
							<th width="5%">Sr. No.</th>
							<th width="15%">Invoice Booking No.</th>
							<th width="15%">GRN No.</th>
							<th width="10%">Invoice No.</th>
							<th width="10%">Supplier Name</th>
							<th width="10%">Invoice Booked On</th>
							<th width="10%">Amount</th>
						</tr>
					</thead>
					<tbody>
					<?php $i=1; foreach ($invoiceBookings as $invoiceBooking): ?>
					<tr>
							<td><?= h($i++) ?></td>
							<td><?php echo $invoiceBooking->ib1.'/IB-'.str_pad($invoiceBooking->ib2, 3, '0', STR_PAD_LEFT).'/'.$invoiceBooking->ib3.'/'.$invoiceBooking->ib4; ?></td>
							<td><?php echo  $invoiceBooking->grn->grn1.'/GRN-'.str_pad($invoiceBooking->grn->grn2, 3, '0', STR_PAD_LEFT).'/'.$invoiceBooking->grn->grn3.'/'.$invoiceBooking->grn->grn4; ?></td>
							
							<td><?= h($invoiceBooking->invoice_no) ?></td>
							<td><?= h($invoiceBooking->vendor->company_name) ?></td>
							<td><?php echo date("d-m-Y",strtotime($invoiceBooking->created_on)) ?></td>
							<td>
							<?= h($this->Number->format($invoiceBooking->total,['places'=>2]))?>
							</td>
					</tr>
							<?php endforeach; ?>
						</tbody>
					</table>		