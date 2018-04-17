<table width="100%" style="font-family:Palatino Linotype;">
		<tr>
			<td  align="left" style="font-size: 28px;font-weight: bold;color:#000000;"><?php echo $company ?>
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td width="50%" valign="top" align="left">
				<?php echo $PurchaseOrders->vendor->company_name; ?>
			</td>
		</tr>
		<tr>
			<td width="50%" valign="top" align="left">
			<?= $this->Text->autoParagraph(h($PurchaseOrders->vendor->address)) ?>
			
			</td>
		</tr>
		
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td >Sub : Purchase order delivery reminder <?php echo "<br/>"; ?></td>
			
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
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
			<td>Our delivery for following purchase orders is due and we request to confirm the delivery at earliest.</td>
		</tr>
		<tr>
			<td><?php echo "<br/><br/>"; ?>
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>
					<table border="1" width="80%">
						<tr>
								<th>S. No.</th>
								<th>Our PO No.</th>
								<th>Date</th>
								<th>Overdue Days</th>
								
						</tr>
						<?php $i=1; foreach($po_no as $key=>$po_no){  ?>
							<tr>
								<td width="15%"><?php echo $i++; ?></td>
								<td><?php echo $po_no; ?></td>
								<td  width="20%"  align="center"><?php echo $delevery_date[$key]; ?></td>
								<td   width="20%" align="center"><?php echo $due_day[$key]; ?></td>
								
						</tr>
						<?php } ?>
					</table>
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/><br/>"; ?>
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td> We are in immediate requirement of the material.In case you have any further delay please write back to us.<?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
		<td><?php echo "<br/><br/>"; ?>
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>Regards, <?php echo "<br/><br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>Purchase Executive <?php echo "<br/><br/>"; ?>
			</td>
		</tr>
		
</table>