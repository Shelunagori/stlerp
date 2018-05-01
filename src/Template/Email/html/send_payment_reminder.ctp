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
			<td width="50%" valign="top" align="left">
			<?= $this->Text->autoParagraph(h($customer_data->customer_name.'('.$customer_data->alias.')')) ?>
			
			</td>
		</tr>
		
		<tr>
			<td width="50%" valign="top" align="left">
			<?= $this->Text->autoParagraph(h($address)) ?>
			
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
			<td >Sub : Payment reminder <?php echo "<br/>"; ?></td>
			
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
			<td>Our payment for following invoices is due and we request to make the payment at earliest. :-</td>
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
								<th>Your PO No.</th>
								<th>Invoice No.</th>
								<th>Due Date</th>
								<th>Amount</th>
								
						</tr>
						<?php $total_amt=0; $i=1; foreach($ReferenceBalances as $key=>$ReferenceBalance){  
						if($ReferenceBalance['reference_type']!="On_account"){
						if(!empty($customer_data['payment_terms'])){
							$payment_terms=$customer_data['payment_terms'];
						}else{
							$payment_terms=0;
						}
						$due_date=date('Y-m-d', strtotime(@$Invoice_data[$refInvoiceNo[$key]]['date_created']. ' +'. $payment_terms .'days'));
						$due_day=date("d-m-Y")-date("d-m-Y",strtotime($due_date));
						//$rem=date('Y-m-d')-$due_date;
						$amt=round($DueReferenceBalances[$key]);
						if($amt != 0){
							$td=date('d-m-Y',strtotime(@$Invoice_data[$refInvoiceNo[$key]]['date_created']));
						?>
							
							<tr>
								<td width="5%" align="center"><?php echo $i++; ?></td>
								<td width="20%" align="center"><?php if(@$Invoice_data[@$refInvoiceNo[$key]]['customer_po_no']){ 
									echo @$Invoice_data[@$refInvoiceNo[$key]]['customer_po_no']; 
								
								}else{
									echo @$Invoice_data[@$refInvoiceNo[$key]]['customer_po_no']; 
								} ?></td>
								<td> <?php 
									if($ReferenceBalance['opening_balance']=="Yes"){
										echo "Opening Balance";
									}else if($td =="01-01-1970"){ 
										echo $ReferenceBalance['reference_no'];
									}else if(@$Voucher_data[$key]){  
										echo @$Voucher_data[$key];
									}
								 ?> 
								</td>
								<td width="15%" align="center"><?php echo date("d-m-Y",strtotime($due_date));  ?></td>
								<td align="right" width="10%"><?php echo abs($DueReferenceBalances[$key]); 
								$total_amt+=$DueReferenceBalances[$key];
								?></td>
								
							</tr>
							
						<?php } } }?>
							<tr>
								<td colspan="4" align="right">Total</td>
								<td  align="right"><?php echo $total_amt; ?></td>
							</tr>
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
			<td>In case you have any issues please write back to us or call your salesperson <?php echo $salesmaninfo['name']; ?> at +91 <?php echo $salesmaninfo['mobile']; ?> to clarify.<?php echo "<br/>"; ?>
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
			<td>Accounts Executive <?php echo "<br/><br/>"; ?>
			</td>
		</tr>
		
</table>