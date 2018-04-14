<table width="100%">
		<tr>
			<td align="left" style="font-size: 28px;font-weight: bold;color: #0685a8;"><?php echo $company ?>
			</td>
		</tr>
		<tr>
			<td width="50%" valign="top" align="left">
				<?php echo $member_name; echo "<br/>"; ?>
				<?php echo wordwrap($vendorAddress,15,"<br>\n"); echo "<br/>" ?>
				
			</td>
		</tr>
		<tr>
			<td><?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td>Sub : Payment advice <?php echo "<br/>"; ?></td>
			
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
			<td>We have initiated payment to your account for your following invoices :-</td>
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
								<th>Invoice No.</th>
								<th>Date</th>
								<th>Dr</th>
								<th>Cr</th>
								
						</tr>
						<?php $i=1; foreach($payment->reference_details as $reference_detail){  ?>
							<tr>
								<td><?php echo $i++; ?></td>
								<td><?php echo $reference_detail->reference_no; ?></td>
								<td><?php echo date("d-m-Y",strtotime($reference_detail->transaction_date)) ?></td>
								<td><?= h($this->Number->format($reference_detail->debit,['places'=>2])) ?>
								<td><?= h($this->Number->format($reference_detail->credit,['places'=>2])) ?>
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
			<td>We request you to kindly acknowledge on receipt of the same.. <?php echo "<br/>"; ?>
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
			<td>Accounts Executive <?php echo "<br/>"; ?>
			</td>
		</tr>
		
</table>