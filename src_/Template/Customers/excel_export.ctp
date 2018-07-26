<?php 
	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="Customers".$date.'_'.$time;

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
		<thead>
		<tr>
			<td colspan="6" align="center">
				<b>Customers</b>
			</td>
		</tr>
			<tr>
				<th>SN.</th>
				<th>Customer Name</th>
				<th width="10%">District</th>
				<th width="10%">Customer Seg</th>
				<th width="10%">Tin No</th>
				<th width="10%">Gst No</th>
				<th>Mobile No</th>
				<th>Email</th>
				<th>Sales Person Name</th>
			</tr>
		</thead>
		<tbody>
			<?php $i=1; foreach($customers as $customer){ ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php if(!empty($customer->alias)){ echo $customer->customer_name.'('; echo $customer->alias.')'; }else{ echo $customer->customer_name; } ?></td>
				<td><?php echo @$customer->district->district; ?></td>
				<td><?php echo @$customer->customer_seg->name; ?></td>
				<td><?php echo @$customer->tin_no; ?></td>
				<td><?php echo @$customer->gst_no; ?></td>
				<td><?php echo @$customer->customer_contacts[0]->mobile; ?></td>
				<td><?php echo @$customer->customer_contacts[0]->email; ?></td>
				<td><?php echo @$customer->employee->name; ?></td>
			</tr>
			<?php $i++; }  ?>
		</tbody>
	</table>	