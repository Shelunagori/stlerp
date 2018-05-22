
<table width="100%" style="font-family:Palatino Linotype;">
		<tr>
			<td  align="left" style="font-size: 28px;font-weight: bold;color:#000000;"><?php echo $company ?>
			</td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td width="50%" valign="top" align="left" style="word-wrap: break-word;">
			<?php echo $customer_data->customer_name.'('.$customer_data->alias.')'; ?>
			</td>
		</tr>
		
		<tr>
			<td width="50%" valign="top" align="left"  style="word-wrap: break-word;">
			<?php echo $address; ?>
			</td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td>Dear Sir,<?php echo "<br/>"; ?> </td> 
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td>Our payment for following invoices is due and we request to make the payment at earliest. :-</td>
		</tr>
		<tr>
			<td></td>
		</tr>
		
		<tr>
			<td>
					<table class="table table-bordered" border='1' width="80%">
						<tr>
								<th>S. No.</th>
								<th>Your PO No.</th>
								<th>Invoice No.</th>
								<th>Invoice Date</th>
								<th>Due Date</th>
								<th colspan="2">Amount</th>
								
						</tr>
						<tr>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th>Dr</th>
								<th>Cr</th>
								
						</tr>
						<?php $total_amt_cr=0;$total_amt_dr=0; $i=1; foreach($ReferenceBalances as $key=>$ReferenceBalance){  
						if($ReferenceBalance['reference_type']!="On_account"){
						if(!empty($customer_data['payment_terms'])){
							$payment_terms=$customer_data['payment_terms'];
						}else{
							$payment_terms=0;
						}
						$due_date=date('Y-m-d', strtotime(@$Invoice_data[$refInvoiceNo[$key]]['date_created']. ' +'. $payment_terms .'days'));
						$due_day=date("d-m-Y")-date("d-m-Y",strtotime($due_date));
						$invoice_date=date("d-m-Y",strtotime(@$Invoice_data[$refInvoiceNo[$key]]['date_created']));
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
								<td width="15%" align="center"><?php echo $invoice_date;  ?></td>
								<td width="15%" align="center"><?php echo date("d-m-Y",strtotime($due_date));  ?></td>
								<td align="right" width="10%">
								<?php 
								if($DueReferenceBalances[$key] > 0){
									echo $this->Number->format($DueReferenceBalances[$key],['places'=>2]); 
									$total_amt_dr+=$DueReferenceBalances[$key];
								}else{
									echo $this->Number->format(0,['places'=>2]);
								}
								
								
								?></td>
								<td align="right" width="10%">
								<?php 
								if($DueReferenceBalances[$key] < 0){
									echo $this->Number->format($DueReferenceBalances[$key],['places'=>2]); 
									$total_amt_cr+=$DueReferenceBalances[$key];
								}else{
									echo $this->Number->format(0,['places'=>2]);
								}
								
								
								?></td>
							</tr>
							
						<?php } } }?>
							<tr>
								<td colspan="5" align="right">Total</td>
								<td  align="right"><?php echo $this->Number->format($total_amt_dr,['places'=>2]); ?></td>
								<td  align="right"><?php echo $this->Number->format($total_amt_cr,['places'=>2]); ?></td>
							</tr>
					</table>
			</td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td>Please find our bank details for making payment :-</td>
		</tr>
		<tr>
			<td><?php echo ucwords($CompanyBanks->bank_name); ?></td>
		</tr>
		<tr>
			<table class="table table-bordered" width="50%">
				<tr>
					<td>Account No :</td>
					<td><?php echo $CompanyBanks->account_no; ?></td>
					<td>IFSC Code :</td>
					<td><?php echo $CompanyBanks->ifsc_code; ?></td>
				</tr>
				<tr>
					<td>Branch :</td>
					<td><?php echo $CompanyBanks->branch; ?></td>
					<td>Account Type :</td>
					<td><?php echo "Current" ?></td>
				</tr>
			</table>
			
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td>In case you have any issues please write back to us or call your salesperson <b><?php echo ucwords($customerSalesManNames); ?></b> at <b>+91 <?php echo $customerSalesManMobile; ?></b> to clarify.<?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td>Regards, <?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td>Accounts Executive
			</td>
		</tr>
		<tr>
			<td>8696029999</td>
		</tr>
		
</table>