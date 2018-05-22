<table width="100%" style="font-family:Palatino Linotype;">
		<tr>
			<td  align="left" style="font-size: 28px;font-weight: bold;color:#000000;"><?php echo $company ?>
			</td>
		</tr>
		<tr>
			<td>
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
			<td>Dear Sir,<?php echo "<br/>"; ?> </td> 
		</tr>
		<tr>
			<td>
			</td>
		</tr>
		<tr>
			<td>Our delivery for following purchase orders is due and we request to confirm the delivery at earliest.</td>
		</tr>
		
		<tr>
			<td>
					<table border="1" width="80%">
						<tr>
								<th>S. No.</th>
								<th>Our PO No.</th>
								<th>PO Date</th>
								<th>Delivery Date</th>
								<th>Overdue Days</th>
								
						</tr>
						<?php $i=1; foreach($po_no as $key=>$po_no){  
						$due_date=date('Y-m-d', strtotime(@$po_date[$key]. ' +'. $payment_terms .'days'));
						?>
							<?php if($due_day[$key] >= 0){ ?>
							<tr>
								<td width="15%"><?php echo $i++; ?></td>
								<td>
								<?php echo $this->Html->link($po_no,[
							'controller'=>'PurchaseOrders','action' => 'confirm',$key],array('target'=>'_blank')); ?>
								</td>
								<td  width="20%"  align="center"><?php echo $po_date[$key]; ?></td>
								<td  width="20%"  align="center"><?php echo $delevery_date[$key]; ?></td>
								<td   width="20%" align="center"><?php echo $due_day[$key]; ?></td>
							</tr>
							<?php } ?>	
						<?php } ?>
					</table>
			</td>
		</tr>
		<tr>
			<td>
			</td>
		</tr>
		
		<tr>
			<td> We are in immediate requirement of the material.In case you have any further delay please write back to us.<?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
		<td>
			</td>
		</tr>
		
		<tr>
			<td>Regards,
			</td>
		</tr>
		<tr>
		<td>
			</td>
		</tr>
		<tr>
			<td>Purchase Executive
			</td>
		</tr>
		
</table>