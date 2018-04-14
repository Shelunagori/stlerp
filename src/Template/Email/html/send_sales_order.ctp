<table width="100%">
		<tr>
			<td align="left" style="font-size: 28px;font-weight: bold;color: #0685a8;"><?php echo $salesOrder->company->name ?>
			</td>
		</tr>
		<tr>
			<td width="50%" valign="top" align="left">
				<?php echo $salesOrder->customer->customer_name; echo "<br/>"; ?>
				<?php echo $salesOrder->customer->customer_address[0]->address; echo "<br/>" ?>
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>Sub : Purchase order acknowledgement <?php echo "<br/>"; ?></td>
			
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
			<td>We thank you for your purchase order No.<?php echo $salesOrder->customer_po_no; ?> dated <?php echo date("d-m-Y",strtotime($salesOrder->po_date)); ?> and please find our order acknowledgment.  Your order has been booked with reference number <?php echo $salesOrder->so3; ?> and you may please use this reference number in future for any details / clarification.,</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>
				Please note your material will be dispatch on following terms :- <?php echo "<br/>"; ?>
				1.      Item description is mention in our sales order.<?php echo "<br/>"; ?>
				2.      Your transporter will be <?php echo $salesOrder->carrier->transporter_name; ?> <?php echo "<br/>"; ?>
				3.      Your documents will be sent by courier through <?php echo $salesOrder->courier->transporter_name; ?> <?php echo "<br/>"; ?>
				4.      Your freight term is <?php echo $salesOrder->delivery_description; ?>.<?php echo "<br/>"; ?>
				5.      Payment term is within  <?php echo $salesOrder->customer->payment_terms; ?> days.<?php echo "<br/>"; ?>
				6.      Dispatch intimation will be sent to <?php echo $salesOrder->dispatch_name; ?> at <?php echo $salesOrder->dispatch_email; ?> . <?php echo "<br/>"; ?>
				7.      Your expected delivery date is <?php echo date("d-m-Y",strtotime($salesOrder->expected_delivery_date)); ?> <?php echo "<br/>"; ?>
				</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>We request you to verify and confirm to us for proper dispatch and documentation. <?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>In case of any further clarification you may write back to us or call your salesperson <?php echo $salesOrder->creator->name; ?> at <?php echo $salesOrder->creator->mobile; ?>.<?php echo "<br/>"; ?>
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
			<td>Sales Coordinator <?php echo "<br/>"; ?>
			</td>
		</tr>
		
</table>