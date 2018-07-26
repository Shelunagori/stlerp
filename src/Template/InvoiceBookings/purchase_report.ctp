<?php $url_excel="/?".$url; ?>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Purchase Report</span>
		</div>
		<div class="actions">
			<?php $today =date('d-m-Y'); echo $this->Html->link('<i class="fa fa-puzzle-piece"></i> Sales Report',array('controller'=>'Invoices','action'=>'salesReport','From'=>$today,'To'=>$today),array('escape'=>false,'class'=>'btn btn-default')); ?>
			<?php echo $this->Html->link('Sales Return Report','/SaleReturns/salesReturnReport',array('escape'=>false,'class'=>'btn btn-default')); ?>
			<?php echo $this->Html->link('Purchase Report','/InvoiceBookings/purchaseReport',array('escape'=>false,'class'=>'btn btn-primary')); ?>
			<?php echo $this->Html->link('Purchase Return Report','/PurchaseReturns/purchaseReturnReport',array('escape'=>false,'class'=>'btn btn-default')); ?>
		</div>
		
	<div class="portlet-body form">
	<form method="GET" >
		<table class="table table-condensed" width="50%">
			<tbody>
				<tr>
					<td width="5%">
					<?php if(!empty($From)){ ?>
						<input type="text" name="From" class="form-control input-sm date-picker" placeholder="Transaction From" value="<?php echo @$From; ?>"  data-date-format="dd-mm-yyyy" >
						<?php }else { ?>
						<input type="text" name="From" class="form-control input-sm date-picker" placeholder="Transaction From" value="<?php echo date('01-m-Y'); ?>"  data-date-format="dd-mm-yyyy" >
						<?php } ?>
					</td>
					<td width="5%">
					<?php if(!empty($To)){ ?>
						<input type="text" name="To" class="form-control input-sm date-picker" placeholder="Transaction To" value="<?php echo @$To; ?>"  data-date-format="dd-mm-yyyy" >
					<?php }else { ?>	
						<input type="text" name="To" class="form-control input-sm date-picker" placeholder="Transaction To" value="<?php echo date('d-m-Y'); ?>"  data-date-format="dd-mm-yyyy" >
					<?php } ?>	
					</td>
					<td width="10%">
						<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
					</td>
					<td width="8%" align='right'>
							<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/InvoiceBookings/Export-Sale-Excel/'.$url_excel.'',['class' =>'btn  green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
						</td>
				</tr>
			</tbody>
		</table>
	</form>
		<!-- BEGIN FORM-->
		<div class="row ">
		
		<div class="col-md-12">
		
		 <?php $page_no=$this->Paginator->current('Ledgers'); $page_no=($page_no-1)*20; ?>
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Sr.No.</th>
						<th>Invoice Book No</th>
						<th>Date</th>
						<th>Supplier</th>
						<th style="text-align:right;">Purchase @ 5.50 %</th>
						<th style="text-align:right;">VAT @5.50 %</th>
						<th style="text-align:right;">Purchase @ 14.50 %</th>
						<th style="text-align:right;">VAT @14.50 %</th>
						<th style="text-align:right;">Purchase @ 5.00 %</th>
						<th style="text-align:right;">VAT @5.00 %</th>
					</tr>
				</thead>
				<tbody><?php $totalvat5=0; $totalvat14=0; $totalvat2=0; $total_purchase5=0; $total_purchase14=0; $total_purchase2=0; ?>
				<?php foreach ($InvoiceBookings as $InvoiceBooking):  
				if($InvoiceBooking->purchase_ledger_account != 35){ 
				?>
					<tr>
						<td><?= h(++$page_no) ?></td>
							<td><?= h(($InvoiceBooking->ib1.'/IB-'.str_pad($InvoiceBooking->ib2, 3, '0', STR_PAD_LEFT).'/'.$InvoiceBooking->ib3.'/'.$InvoiceBooking->ib4)) ?></td>
							<td><?php echo date("d-m-Y",strtotime($InvoiceBooking->supplier_date)); ?></td>
							<td><?= h($InvoiceBooking->vendor->company_name) ?></td>
							<?php  $vat5=0;  $vat14=0; $vat2=0;  $purchase5=0;   $purchase14=0; $purchase2=0;  ?>
							<?php foreach($InvoiceBooking->invoice_booking_rows as $invoice_booking_row ) {?>
								<?php if($invoice_booking_row->sale_tax==5.00){
									$amount=$invoice_booking_row->unit_rate_from_po*$invoice_booking_row->quantity;
									$amount=$amount+$invoice_booking_row->misc;
									if($invoice_booking_row->discount_per==1){
										$amount=$amount*((100-$invoice_booking_row->discount)/100);
									}else{
										$amount=$amount-$invoice_booking_row->discount;
									}

									if($invoice_booking_row->pnf_per==1){
										$amount=$amount*((100+$invoice_booking_row->pnf)/100);
									}else{
										$amount=$amount+$invoice_booking_row->pnf;
									}
									$amount=$amount*((100+	$invoice_booking_row->excise_duty)/100);
									$amountofVAT=($amount*$invoice_booking_row->sale_tax)/100;
									$vat5=$vat5+$amountofVAT;
									$amount=$amount*((100+$invoice_booking_row->sale_tax)/100);
									
									$vat_amounts=$amountofVAT/$invoice_booking_row->quantity;
									
									$amount=$amount+$invoice_booking_row->other_charges; 
									$purchase5=$purchase5+$amount;
									$total_amt=$amount/$invoice_booking_row->quantity;
									
								}else if($invoice_booking_row->sale_tax==14.5){ 
									$amount=$invoice_booking_row->unit_rate_from_po*$invoice_booking_row->quantity;
									$amount=$amount+$invoice_booking_row->misc;
									if($invoice_booking_row->discount_per==1){
										$amount=$amount*((100-$invoice_booking_row->discount)/100);
									}else{
										$amount=$amount-$invoice_booking_row->discount;
									}

									if($invoice_booking_row->pnf_per==1){
										$amount=$amount*((100+$invoice_booking_row->pnf)/100);
									}else{
										$amount=$amount+$invoice_booking_row->pnf;
									}
									$amount=$amount*((100+	$invoice_booking_row->excise_duty)/100);
									$amountofVAT=($amount*$invoice_booking_row->sale_tax)/100;
									$vat14=$vat14+$amountofVAT;
									$amount=$amount*((100+$invoice_booking_row->sale_tax)/100);
									$vat_amounts=$amountofVAT/$invoice_booking_row->quantity;
									$amount=$amount+$invoice_booking_row->other_charges;  
									$purchase14=$purchase14+$amount;
									$total_amt=$amount/$invoice_booking_row->quantity;
									
								} else if($invoice_booking_row->sale_tax==5.50){ 
									$amount=$invoice_booking_row->unit_rate_from_po*$invoice_booking_row->quantity;
									$amount=$amount+$invoice_booking_row->misc;
									if($invoice_booking_row->discount_per==1){
										$amount=$amount*((100-$invoice_booking_row->discount)/100);
									}else{
										$amount=$amount-$invoice_booking_row->discount;
									}

									if($invoice_booking_row->pnf_per==1){
										$amount=$amount*((100+$invoice_booking_row->pnf)/100);
									}else{
										$amount=$amount+$invoice_booking_row->pnf;
									}
									$amount=$amount*((100+	$invoice_booking_row->excise_duty)/100);
									$amountofVAT=($amount*$invoice_booking_row->sale_tax)/100;
									$vat2=$vat2+$amountofVAT;
									$amount=$amount*((100+$invoice_booking_row->sale_tax)/100);
									$vat_amounts=$amountofVAT/$invoice_booking_row->quantity;
									$amount=$amount+$invoice_booking_row->other_charges; 
									 $purchase2=$purchase2+$amount;
									$total_amt=$amount/$invoice_booking_row->quantity;
									
								}
								?>
							<?php }?>
							<?php  ?>
							<td align="right"><?php if($purchase2 > 0){
								echo number_format($purchase2-$vat2,2,'.',',');
							}else{
								echo "-";
							} ?></td>
							<td align="right"><?php if($vat2 > 0){
								echo number_format($vat2,2,'.',',');
							}else{
								echo "-";
							} ?></td>
							<td align="right"><?php if($purchase14 > 0){
								echo number_format($purchase14-$vat14,2,'.',',');
							}else{
								echo "-";
							} ?></td>
							<td align="right"><?php if($vat14 > 0){
								echo number_format($vat14,2,'.',',');
							}else{
								echo "-";
							} ?>
							</td>
							<td align="right"><?php if($purchase5 > 0){
								echo number_format($purchase5-$vat5,2,'.',',');
							}else{
								echo "-";
							} ?></td>
							<td align="right"><?php if($vat5 > 0){
								echo number_format($vat5,2,'.',',');
							}else{
								echo "-";
							} ?>
							</td>
							
							
				</tr>
				<?php 	$totalvat5=$totalvat5+ $vat5;
						$total_purchase5=$total_purchase5+$purchase5;
						$totalvat14=$totalvat14+ $vat14;
						$total_purchase14=$total_purchase14+$purchase14;
						$totalvat2=$totalvat2+ $vat2;
						$total_purchase2=$total_purchase2+$purchase2;
						} ?>
						
				<?php endforeach; ?>
				<tr>
					<td colspan="4" align="right"><b>Total</b></td>
					<td align="right"><b><?php echo number_format($total_purchase2-$totalvat2,2,'.',','); ?></b></td>
					<td align="right"><b><?php echo number_format($totalvat2,2,'.',','); ?></b></td>
					<td align="right"><b><?php echo number_format($total_purchase14-$totalvat14,2,'.',','); ?></b></td>
					<td align="right"><b><?php echo number_format($totalvat14,2,'.',','); ?></b></td>
					<td align="right"><b><?php echo number_format($total_purchase5-$totalvat5,2,'.',','); ?></b></td>
					<td align="right"><b><?php echo number_format($totalvat5,2,'.',','); ?></b></td>
				</tr>
				</tbody>
			</table>
			</div>
		</div>
	</div>
</div>
</div></div>