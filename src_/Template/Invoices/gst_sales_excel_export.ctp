<?php 

	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="GST_Sales_report_".$date.'_'.$time;

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
	<?php $col=sizeof($invoiceGst->toArray()); $col=($col*2)+4;  ?>
		<tr>
			<td colspan="<?php echo $col; ?>" align="center"  valign="top">
				<b><h4 class="caption-subject font-black-steel uppercase">Sales Invoice
				<?php if(!empty($From) || !empty($To)){ echo date('d-m-Y',strtotime($From)); ?> TO <?php echo date('d-m-Y',strtotime($To));  } ?></h4></b>
			</td>
		</tr>
		<tr>
			<th>Sr.No.</th>
			<th>Invoice No</th>
			<th>Date</th>
			<th>Customer</th>
		
			<?php  foreach($invoiceGst as $Key1=>$SaleTaxeGst){ 
			$saletax=($SaleTaxeGst->tax_figure*2);
			?>
			<th style="text-align:right;">Sales GST @ <?php echo $saletax; ?> % </th>
			<th style="text-align:right;">GST @ <?php echo $saletax; ?>%</th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php $SalesTotal=[];$SalesgstTotal=[];$gstRowTotal=[]; $gstTotal=[];$i=1;
			foreach ($invoices as $invoice): $frigtData=[]; $frigtAmount=[];
						$invoice_id = $EncryptingDecrypting->encryptData($invoice->id);
						foreach($invoice->invoice_rows as $invoice_row)
						{
							if($invoice_row['igst_percentage'] == 0){
							@$gstRowTotal[$invoice->id][$invoice_row['cgst_percentage']]=@$gstRowTotal[$invoice->id][$invoice_row['cgst_percentage']]+(@$invoice_row->cgst_amount*2)
							;
							@$gstTotal[$invoice->id][$invoice_row['cgst_percentage']]=@$gstTotal[$invoice->id][$invoice_row['cgst_percentage']]+(@$invoice_row->taxable_value);
							
							$frigtData[@$invoice->fright_cgst_percent]=@$invoice->fright_cgst_amount*2;
							$frigtAmount[@$invoice->fright_cgst_percent]=@$invoice->fright_amount;
							//pr($invoice->fright_cgst_amount); 
							//pr($frigtAmount); 
							}
						}
				
					
			?>
		<tr>
			<td><?php echo $i; ?></td>
			<td>
				<?php echo $invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4; ?>
			</td>
			<td><?php echo date("d-m-Y",strtotime($invoice->date_created)); ?></td>
			<td><?php echo $invoice->customer->customer_name.'('.$invoice->customer->alias.')'?></td>
			<?php $k=0; $AllTaxs=[];
				foreach($invoiceGst as $Key1=>$SaleTaxeGst){ 
						$AllTaxs[$k]=$SaleTaxeGst->id;
						$k++;
				}
			?>
			<?php foreach($AllTaxs as  $key=>$AllTax){ 
				if(isset($gstRowTotal[$invoice->id][$AllTax]))
				{?>
						<td style="text-align:right;"><?php echo 
						$this->Number->format(@$gstTotal[@$invoice->id][$AllTax]+@$frigtAmount[$AllTax],['places'=>2]); 
						$SalesTotal[$AllTax]=@$SalesTotal[$AllTax]+$gstTotal[$invoice->id][$AllTax]+@$frigtAmount[$AllTax];
						?></td>
						<td style="text-align:right;"><?php echo $this->Number->format($gstRowTotal[$invoice->id][$AllTax]+@$frigtData[$AllTax],['places'=>2]); 
						$SalesgstTotal[$AllTax]=@$SalesgstTotal[$AllTax]+$gstRowTotal[$invoice->id][$AllTax]+@$frigtData[$AllTax];
						?></td>
						<?php 
				}							
				else 
				{
				?>
					<td style="text-align:right;"><?php echo "-"; ?></td>
					<td style="text-align:right;"><?php echo "-"; ?></td>
				<?php 
				} 
				
			}  ?>
			
		</tr>
		<?php $i++; endforeach;  ?>
		<tr>
			<td style="text-align:right;" colspan=4><b>Total</b></td>
			<?php 
				foreach($invoiceGst as $Key1=>$SaleTaxeGst){  
					if(!empty($SalesTotal[$SaleTaxeGst->id])){
				?>
					<td style="text-align:right;"><b><?php echo 
					$this->Number->format(@$SalesTotal[$SaleTaxeGst->id],['places'=>2]); ?></b></td>
					<td style="text-align:right;"><b><?php echo 
					$this->Number->format(@$SalesgstTotal[$SaleTaxeGst->id],['places'=>2]); ?></b></td>
				<?php }else{ ?>
					<td style="text-align:right;"><b>-</b></td>
					<td style="text-align:right;"><b>-</b></td>
				<?php }} ?>
		</tr>
	</tbody>
</table>
<table border="1">
	<thead>
		<tr><?php $col=sizeof($invoiceIGst->toArray()); $col=($col*2)+4;  ?>
			<td colspan="<?php echo $col;?>" align="center"  valign="top">
				<b><h4 class="caption-subject font-black-steel uppercase">Sales Inter State
				<?php if(!empty($From) || !empty($To)){ echo date('d-m-Y',strtotime($From)); ?> TO <?php echo date('d-m-Y',strtotime($To));  } ?> 
				</h4></b>
			</td>
		</tr>
		<tr>
			<th>Sr.No.</th>
			<th>Invoice No</th>
			<th>Date</th>
			<th>Customer</th>
			<?php  foreach($invoiceIGst as $Key1=>$SaleTaxeGst){ ?>
			<th style="text-align:right;">Sales IGST @<?php echo $SaleTaxeGst->tax_figure; ?> %</th>
			<th style="text-align:right;">IGST @ <?php echo $SaleTaxeGst->tax_figure; ?> %</th>
			<?php } ?>
		</tr>
	</thead>
<?php $SalesIgstTotal=[]; $SalesIgstRowTotal=[]; $j=1;	
				foreach ($interStateInvoice as $invoiceigsts){  
				$invoiceigsts_id = $EncryptingDecrypting->encryptData($invoiceigsts->id);
				?>
				<tbody>
					<?php $SigstRowTotal=[]; $SigstTotal=[]; $frigtData=[]; $frigtAmount=[];
					if(!empty($invoiceigsts->invoice_rows)){  
					foreach($invoiceigsts->invoice_rows as $invoice_rows_data){
						if($invoice_rows_data['igst_percentage'] > 0){
								
								@$SigstRowTotal[$invoiceigsts->id][$invoice_rows_data['igst_percentage']]+=@$gstRowTotal[$invoiceigsts->id][$invoice_rows_data['igst_percentage']]+(@$invoice_rows_data->igst_amount);
								
								@$SigstTotal[$invoiceigsts->id][$invoice_rows_data['igst_percentage']]+=@$gstTotal[$invoiceigsts->id][$invoice_rows_data['igst_percentage']]+(@$invoice_rows_data->taxable_value);
								
								$frigtData[@$invoiceigsts->fright_igst_percent]=@$invoiceigsts->fright_igst_amount;
								$frigtAmount[@$invoiceigsts->fright_igst_percent]=@$invoiceigsts->fright_amount;
							}
						}
						
				 }
				
				 
				 ?>
				
					<tr>
						<td><?php echo $j++; ?></td>
						<td>
						
								<?php echo $invoiceigsts->in1.'/IN-'.str_pad($invoiceigsts->in2, 3, '0', STR_PAD_LEFT).'/'.$invoiceigsts->in3.'/'.$invoiceigsts->in4; ?>
							
						</td>
						<td><?php echo date("d-m-Y",strtotime($invoiceigsts->date_created)); ?></td>
						<td><?php echo $invoiceigsts->customer->customer_name.'('.$invoiceigsts->customer->alias.')'?></td>
						<?php $k=0; $AllTaxs=[];
							foreach($invoiceIGst as $Key1=>$SaleTaxeGst){ 
									$AllTaxs[$k]=$SaleTaxeGst->id;
									$k++;
							}
						?>
						
						<?php foreach($AllTaxs as  $key=>$AllTax){ 
							if(isset($SigstRowTotal[$invoiceigsts->id][$AllTax]))
							{?>
								
									
							<td style="text-align:right;"><?php echo 
									$this->Number->format(@$SigstTotal[@$invoiceigsts->id][$AllTax]+@$frigtAmount[$AllTax],['places'=>2]); 
									$SalesIgstTotal[$AllTax]=@$SalesIgstTotal[$AllTax]+$SigstTotal[$invoiceigsts->id][$AllTax]+@$frigtAmount[$AllTax];
									?></td>
									<td style="text-align:right;"><?php echo $this->Number->format($SigstRowTotal[$invoiceigsts->id][$AllTax]+@$frigtData[$AllTax],['places'=>2]); 
									$SalesIgstRowTotal[$AllTax]=@$SalesIgstRowTotal[$AllTax]+$SigstRowTotal[$invoiceigsts->id][$AllTax]+@$frigtData[$AllTax];
									?></td>
									
									
									
									<?php 
							}							
							else 
							{
							?>
								<td style="text-align:right;"><?php echo "-"; ?></td>
								<td style="text-align:right;"><?php echo "-"; ?></td>
							<?php 
							} 
							
						}  ?>
						
					</tr>
				<?php }  ?>
					<tr>
				<td style="text-align:right;" colspan=4><b>Total</b></td>
				<?php 
					//pr($PurchaseTotal); exit;
					foreach($invoiceIGst as $Key1=>$SaleTaxeGst){  
						if(!empty($SalesIgstTotal[$SaleTaxeGst->id])){ ?>
						<td style="text-align:right;"><b><?php echo 
						$this->Number->format(@$SalesIgstTotal[$SaleTaxeGst->id],['places'=>2]); ?></b></td>
						<td style="text-align:right;"><b><?php echo 
						$this->Number->format(@$SalesIgstRowTotal[$SaleTaxeGst->id],['places'=>2]); ?></b></td>
				<?php }else{ ?>
						<td style="text-align:right;"><b>-</b></td>
						<td style="text-align:right;"><b>-</b></td>
					<?php }} ?>
				
				</tr>
				</tbody>
</table>
<table border="1">
	<thead>
		<tr><?php $col=sizeof($invoiceBookingsGst->toArray()); $col=($col*2)+4;  ?>
			<td colspan="<?php echo $col;?>" align="center"  valign="top">
				<b><h4 class="caption-subject font-black-steel uppercase">Purchase
				<?php if(!empty($From) || !empty($To)){ echo date('d-m-Y',strtotime($From)); ?> TO <?php echo date('d-m-Y',strtotime($To));  } ?> 
				</h4></b>
			</td>
		</tr>
		<tr>
			<th>Sr.No.</th>
			<th>Invoice Booking No</th>
			<th>Date</th>
			<th>Supplier</th>
			<?php  foreach($invoiceBookingsGst as $Key1=>$inbookingsgst){ 
			$inbookingsgsttax=($inbookingsgst->tax_figure*2);
			?>
			<th style="text-align:right;">Purchase GST @ <?php echo $inbookingsgsttax; ?> % </th>
			<th style="text-align:right;">GST @ <?php echo $inbookingsgsttax; ?>%</th>
			<?php } ?>
		</tr>
	</thead>
				<tbody>
					<?php $PurchaseBookingTotal=[];$PurchaseBookinggstTotal=[];$PurchaseBookingRowTotal=[]; $PurchaseBookingTotals=[];$i=1;$data=[];
					foreach ($invoiceBookings as $invoicebooking):
						$invoicebooking_id = $EncryptingDecrypting->encryptData($invoicebooking->id);
						foreach($invoicebooking->invoice_booking_rows as $invoice_booking_row_data)
						{
							
							if($invoice_booking_row_data['igst_per'] == 0){
									@$PurchaseBookingRowTotal[$invoicebooking->id][$invoice_booking_row_data['cgst_per']]=@$PurchaseBookingRowTotal[$invoicebooking->id][$invoice_booking_row_data['cgst_per']]+(@$invoice_booking_row_data->cgst*2);
									@$PurchaseBookingTotals[$invoicebooking->id][$invoice_booking_row_data['cgst_per']]=@$PurchaseBookingTotals[$invoicebooking->id][$invoice_booking_row_data['cgst_per']]+(@$invoice_booking_row_data->taxable_value);
							}
						}
					?>
					<?php $k=0; $AllTaxs=[];
							foreach($invoiceBookingsGst as $Key1=>$SaleTaxeGst){ 
									$AllTaxs[$k]=$SaleTaxeGst->id;
									$k++;
							}
								
						?>
					<?php if($invoicebooking->invoice_booking_rows){?>
					<tr>
						<td><?php echo $i; ?></td>
						<td>
					
							<?php echo $invoicebooking->ib1.'/IB-'.str_pad($invoicebooking->ib2, 3, '0', STR_PAD_LEFT).'/'.$invoicebooking->ib3.'/'.$invoicebooking->ib4; ?>
						
						</td>
						<td><?php echo date("d-m-Y",strtotime($invoicebooking->supplier_date)); ?></td>
						<td><?php echo $invoicebooking->vendor->company_name; ?></td>
						
						<?php foreach($AllTaxs as  $key=>$AllTax){ 
							
							if(isset($PurchaseBookingRowTotal[$invoicebooking->id][$AllTax]))
							{?>
									<td style="text-align:right;"><?php echo 
									$this->Number->format(@$PurchaseBookingTotals[@$invoicebooking->id][$AllTax],['places'=>2]); 
									$PurchaseBookingTotal[$AllTax]=@$PurchaseBookingTotal[$AllTax]+$PurchaseBookingTotals[$invoicebooking->id][$AllTax];
									?></td>
									<td style="text-align:right;"><?php echo $this->Number->format($PurchaseBookingRowTotal[$invoicebooking->id][$AllTax],['places'=>2]); 
									$PurchaseBookinggstTotal[$AllTax]=@$PurchaseBookinggstTotal[$AllTax]+$PurchaseBookingRowTotal[$invoicebooking->id][$AllTax];
									?></td>
									<?php 
							}							
							else 
							{
							?>
								<td style="text-align:right;"><?php echo "-"; ?></td>
								<td style="text-align:right;"><?php echo "-"; ?></td>
							<?php 
							} 
							
						}  ?>
						
					</tr>
					<?php $i++; }  endforeach;  ?>
				<tr>
				<td style="text-align:right;" colspan=4><b>Total</b></td>
				<?php 
					foreach($invoiceBookingsGst as $Key1=>$SaleTaxeGst){  
						if(!empty($PurchaseBookingTotal[$SaleTaxeGst->id])){
					?>
						<td style="text-align:right;"><b><?php echo 
						$this->Number->format(@$PurchaseBookingTotal[$SaleTaxeGst->id],['places'=>2]); ?></b></td>
						<td style="text-align:right;"><b><?php echo 
						$this->Number->format(@$PurchaseBookinggstTotal[$SaleTaxeGst->id],['places'=>2]); ?></b></td>
					<?php }else{ ?>
						<td style="text-align:right;"><b>-</b></td>
						<td style="text-align:right;"><b>-</b></td>
					<?php }} ?>
				
				</tr>
				</tbody>
</table>
<table border="1">
	<thead>
		<tr><?php $col=sizeof($PurchaseIgst->toArray()); $col=($col*2)+4;  ?>
			<td colspan="<?php echo $col; ?>" align="center"  valign="top">
				<b><h4 class="caption-subject font-black-steel uppercase">Purchase Inter State
				<?php if(!empty($From) || !empty($To)){ echo date('d-m-Y',strtotime($From)); ?> TO <?php echo date('d-m-Y',strtotime($To));  } ?> 
				</h4></b>
			</td>
		</tr>
		<tr>
			<th>Sr.No.</th>
			<th>Invoice Booking No</th>
			<th>Date</th>
			<th>Supplier</th>
			<?php  foreach($PurchaseIgst as $Key1=>$SaleTaxeGst){ ?>
			<th style="text-align:right;">Purchase IGST @<?php echo $SaleTaxeGst->tax_figure; ?> %</th>
			<th style="text-align:right;">IGST <?php echo $SaleTaxeGst->tax_figure; ?> %</th>
			<?php } ?>
		</tr>
	</thead>
		<?php $PurchaseIgstTotal=[]; $PurchaseTotal=[]; $i=1;
				foreach ($invoiceBookingsInterState as $invoiceBooking):   
				$invoiceBooking_id1 = $EncryptingDecrypting->encryptData($invoice->id);
				?>
				<tbody>
					<?php $igstRowTotal=[]; $igstTotal=[];
					if(!empty($invoiceBooking->invoice_booking_rows)){  
						foreach($invoiceBooking->invoice_booking_rows as $invoice_booking_row)
						{ 
							if($invoice_booking_row['igst_per'] > 0){
								@$igstRowTotal[$invoiceBooking->id][$invoice_booking_row['igst_per']]=@$igstRowTotal[$invoiceBooking->id][$invoice_booking_row['igst_per']]+@$invoice_booking_row->igst;
							}
						}
						@$igstTotal[$invoiceBooking->id]=@$igstTotal[$invoiceBooking->id]+$invoiceBooking->taxable_value;
				?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td>
						
							<?php echo $invoiceBooking->ib1.'/IB-'.str_pad($invoiceBooking->ib2, 3, '0', STR_PAD_LEFT).'/'.$invoiceBooking->ib3.'/'.$invoiceBooking->ib4; ?>
					
						</td>
						<td><?php echo date("d-m-Y",strtotime($invoiceBooking->supplier_date)); ?></td>
						<td><?php echo $invoiceBooking->vendor->company_name; ?></td>
						<?php $k=0; $AllTaxs=[];
							foreach($PurchaseIgst as $Key1=>$SaleTaxeGst){ 
									$AllTaxs[$k]=$SaleTaxeGst->id;
									$k++;
							}
						?>
						<?php foreach($AllTaxs as  $key=>$AllTax){ 
							if(isset($igstRowTotal[$invoiceBooking->id][$AllTax]))
							{?>
									<td style="text-align:right;"><?php echo $this->Number->format(@$igstTotal[$invoiceBooking->id],['places'=>2]); 
									$PurchaseTotal[$AllTax]=@$PurchaseTotal[$AllTax]+$igstTotal[$invoiceBooking->id];
									?></td>
									<td style="text-align:right;"><?php echo $this->Number->format(@$igstRowTotal[$invoiceBooking->id][$AllTax],['places'=>2]); 
									$PurchaseIgstTotal[$AllTax]=@$PurchaseIgstTotal[$AllTax]+$igstRowTotal[$invoiceBooking->id][$AllTax];
									?></td>
									<?php 
							}							
							else 
							{
							?>
								<td style="text-align:right;"><?php echo "-"; ?></td>
								<td style="text-align:right;"><?php echo "-"; ?></td>
							<?php 
							} 
							
						}  ?>

						
					</tr>
				<?php   }   endforeach; ?>
					
					<tr>
				<td style="text-align:right;" colspan=4><b>Total</b></td>
				<?php 
					//pr($PurchaseTotal); exit;
					foreach($PurchaseIgst as $Key1=>$SaleTaxeGst){  ?>
						<td style="text-align:right;"><b><?php echo 
						$this->Number->format(@$PurchaseTotal[$SaleTaxeGst->id],['places'=>2]); ?></b></td>
						<td style="text-align:right;"><b><?php echo 
						$this->Number->format(@$PurchaseIgstTotal[$SaleTaxeGst->id],['places'=>2]); ?></b></td>
				<?php } ?>
				
				</tr>
					
				</tbody>
</table>
<table border="1">
	<thead>
		<tr>
		<?php $col=sizeof($LedgerAccountDetails); $col=($col*2)+2;  ?>
			<td colspan="<?php echo $col; ?>" align="center"  valign="top">
				<b><h4 class="caption-subject font-black-steel uppercase">Voucher
				<?php if(!empty($From) || !empty($To)){ echo date('d-m-Y',strtotime($From)); ?> TO <?php echo date('d-m-Y',strtotime($To));  } ?> 
				</h4></b>
			</td>
		</tr>
		<tr>
			<th>Sr.No.</th>
			<th>Voucher</th>
			<?php  foreach($LedgerAccountDetails as $Key1=>$SaleTaxeGst){ ?>
			<th style="text-align:right;"><?php echo $SaleTaxeGst; ?></th>
			<?php } ?>
		</tr>
	</thead>
				<tbody>
				<?php   $i=1; $TotalAmountDr=[]; $TotalAmountCr=[]; 
				
				foreach($voucherLedgerDetailsGst as  $key=>$voucherLedgerDetailsGst){  
				if($voucherSourceGst[$key]=="Petty Cash Payment Voucher"){
					$voucher_no="Petty Cash Payment Voucher";
					$url_path="/PettyCashVouchers/view/".$voucherLedgerDetailsGst->voucher_id;
				}else if($voucherSourceGst[$key]=="Journal Voucher"){
					$voucher_no="Journal Voucher";
					$url_path="/JournalVouchers/view/".$voucherLedgerDetailsGst->voucher_id;
				}else if($voucherSourceGst[$key]=="Receipt Voucher"){
					$voucher_no="Receipt Voucher";
					$url_path="/Receipts/view/".$voucherLedgerDetailsGst->voucher_id;
				}else if($voucherSourceGst[$key]=="Non Print Payment Voucher"){
					$voucher_no="Non Print Payment Voucher";
					$url_path="/Nppayments/view/".$voucherLedgerDetailsGst->voucher_id;
				}else if($voucherSourceGst[$key]=="Payment Voucher"){
					$voucher_no="Payment Voucher";
					$url_path="/Payments/view/".$voucherLedgerDetailsGst->voucher_id;
				}else if($voucherSourceGst[$key]=="Contra Voucher"){
					$voucher_no="Contra Voucher";
					$url_path="/ContraVouchers/view/".$voucherLedgerDetailsGst->voucher_id;
				}
				?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td>
						
						<?php echo $voucher_no; ?>
						
						</td>
						<?php $k=0; $AllTax=[];
							foreach($LedgerAccountDetails as $Key1=>$SaleTaxeGst){ 
									$AllTax[$k]=$Key1;
									$k++;
									
							}
							?>
							<?php 
							foreach($AllTax as  $key=>$AllTax){  
							if($voucherLedgerDetailsGst->ledger_account_id==$AllTax){ ?>
								<?php if($voucherLedgerDetailsGst->debit == 0){ ?>
							<td style="text-align:right;"><?php echo $voucherLedgerDetailsGst->credit;  echo "Cr"; 
								@$TotalAmountCr[@$AllTax]=@$TotalAmountCr[@$AllTax]+@$voucherLedgerDetailsGst->credit;
								?>
							</td>
							<?php } else {?>
							<td style="text-align:right;"><?php echo $voucherLedgerDetailsGst->debit; echo "Dr";  
								@$TotalAmountDr[@$AllTax]=@$TotalAmountDr[@$AllTax]+@$voucherLedgerDetailsGst->debit;
								?>
							</td>
							<?php } ?>
							<?php }else{ ?>
								<td style="text-align:right;"><?php echo "-"; ?></td>
								<?php	}
							?>
							<?php } ?>
						
					</tr>
				<?php } ?>
				<tr>
				<td style="text-align:right;" colspan=2>Total</td>
				<?php  foreach($LedgerAccountDetails as $Key1=>$SaleTaxeGst){ ?>
						<?php if(@$TotalAmountDr[$Key1] > @$TotalAmountCr[$Key1]) {?>
						<td style="text-align:right;"><?php echo @$TotalAmountDr[$Key1]-@$TotalAmountCr[$Key1]; echo "Dr";?></td>
						<?php } else if(@$TotalAmountDr[$Key1] < @$TotalAmountCr[$Key1]){ ?>
						<td style="text-align:right;"><?php echo @$TotalAmountCr[$Key1]-@$TotalAmountDr[$Key1]; echo "Cr";?></td>
					<?php }else{ ?>
							<td style="text-align:right;"><?php echo "-"; ?></td>
					<?php	}} ?>
				
				</tr>
					
				</tbody>
</table>
<table border="1">
	<thead>
	<?php $col=sizeof($LedgerAccountDetailIgst); $col=($col*2)+2;  ?>
		<tr>
			<td colspan="<?php echo $col; ?>" align="center"  valign="top">
				<b><h4 class="caption-subject font-black-steel uppercase">Inter State Voucher
				<?php if(!empty($From) || !empty($To)){ echo date('d-m-Y',strtotime($From)); ?> TO <?php echo date('d-m-Y',strtotime($To));  } ?> 
				</h4></b>
			</td>
		</tr>
		<tr>
			<th>Sr.No.</th>
			<th>Voucher</th>
			
			<?php foreach($LedgerAccountDetailIgst as $SaleTaxeGst){ ?>
			<th style="text-align:right;"><?php echo $SaleTaxeGst; ?></th>
			<?php } ?>
		</tr>
	</thead>
		<tbody>
				<?php  $i=1; $TotalIGSTAmountDr=[]; $TotalIGSTAmountCr=[]; 
				foreach($voucherLedgerDetailIgst as  $key=>$voucherLedgerDetailIgst){  
					if($voucherSourceIgst[$key]=="Petty Cash Payment Voucher"){
					$voucher_no="Petty Cash Payment Voucher";
					$url_path="/PettyCashVouchers/view/".$voucherLedgerDetailIgst->voucher_id;
				}else if($voucherSourceIgst[$key]=="Journal Voucher"){
					$voucher_no="Journal Voucher";
					$url_path="/JournalVouchers/view/".$voucherLedgerDetailIgst->voucher_id;
				}else if($voucherSourceIgst[$key]=="Receipt Voucher"){
					$voucher_no="Receipt Voucher";
					$url_path="/Receipts/view/".$voucherLedgerDetailIgst->voucher_id;
				}else if($voucherSourceIgst[$key]=="Non Print Payment Voucher"){
					$voucher_no="Non Print Payment Voucher";
					$url_path="/Nppayments/view/".$voucherLedgerDetailIgst->voucher_id;
				}else if($voucherSourceIgst[$key]=="Payment Voucher"){
					$voucher_no="Payment Voucher";
					$url_path="/Payments/view/".$voucherLedgerDetailIgst->voucher_id;
				}else if($voucherSourceIgst[$key]=="Contra Voucher"){
					$voucher_no="Contra Voucher";
					$url_path="/ContraVouchers/view/".$voucherLedgerDetailIgst->voucher_id;
				}
				?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><?php echo $voucher_no; ?></td>
						<?php $k=0; $AllTax=[];
							foreach($LedgerAccountDetailIgst as $Key1=>$SaleTaxeGst){ 
									$AllTax[$k]=$Key1;
									$k++;
							}
							?>
							<?php 
							foreach($AllTax as  $key=>$AllTax){  
							if($voucherLedgerDetailIgst->ledger_account_id==$AllTax){ ?>
								<?php if($voucherLedgerDetailIgst->debit == 0){ ?>
							<td style="text-align:right;"><?php echo $voucherLedgerDetailIgst->credit; echo "Cr";
								@$TotalIGSTAmountCr[@$AllTax]=@$TotalIGSTAmountCr[@$AllTax]+@$voucherLedgerDetailIgst->credit;
								?>
							</td>
							<?php } else {?>
							<td style="text-align:right;"><?php echo $voucherLedgerDetailIgst->debit; echo "Dr"; 
								@$TotalIGSTAmountDr[@$AllTax]=@$TotalIGSTAmountDr[@$AllTax]+@$voucherLedgerDetailIgst->debit;
								?>
							</td>
							<?php } ?>
							<?php }else{ ?>
								<td style="text-align:right;"><?php echo "-"; ?></td>
								<?php	}
							?>
							<?php } ?>
						
					</tr>
				<?php } ?>
				<tr>
				<td style="text-align:right;" colspan=2>Total</td>
				<?php  foreach($LedgerAccountDetailIgst as $Key1=>$SaleTaxeGst){ ?>
					<?php if(@$TotalIGSTAmountDr[$Key1] > @$TotalIGSTAmountCr[$Key1]) {?>
						<td style="text-align:right;"><?php echo @$TotalIGSTAmountDr[$Key1]-@$TotalIGSTAmountCr[$Key1]; echo "Dr";?></td>
						<?php } else if(@$TotalIGSTAmountDr[$Key1] < @$TotalIGSTAmountCr[$Key1]) { ?>
						<td style="text-align:right;"><?php echo @$TotalIGSTAmountCr[$Key1]-@$TotalIGSTAmountDr[$Key1]; echo "Cr";?></td>
					<?php }else { ?>
							<td style="text-align:right;"><?php echo "-"; ?></td>
					<?php	}} ?>
				
				</tr>
				</tbody>
</table>
				
					