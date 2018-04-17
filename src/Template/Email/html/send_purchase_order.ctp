<table width="100%">
		<tr>
			<td align="left" style="font-size: 28px;font-weight: bold;color: #0685a8;"><?php echo $company ?>
			</td>
		</tr>
		<tr>
			<td width="50%" valign="top" align="left">
				<?php echo $PurchaseOrders->vendor->company_name; echo "<br/>"; ?>
				<?php echo $PurchaseOrders->vendor->address; echo "<br/>" ?>
				
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>Sub : Purchase order delivery reminder <?php echo "<br/>"; ?></td>
			
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>Dear Sir,<?php echo "<br/>"; ?> </td> 
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>Our delivery for following purchase orders is due and we request to confirm the delivery at earliest. We are in immediate requirement of the material.</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>
					<table border="1">
						<tr>
								<th>S. No.</th>
								<th>Our PO No.</th>
								<th>Date</th>
								<th>Overdue Days</th>
								
						</tr>
						<?php $i=1; foreach($po_no as $key=>$po_no){  ?>
							<tr>
								<td><?php echo $i++; ?></td>
								<td><?php echo $po_no; ?></td>
								<td><?php echo $delevery_date[$key]; ?></td>
								<td><?php echo $due_day[$key]; ?></td>
								
						</tr>
						<?php } ?>
					</table>
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>In case you have any further delay please write back to us.<?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		
		<tr>
			<td>Regards, <?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>Purchase Executive <?php echo "<br/>"; ?>
			</td>
		</tr>
		
</table>