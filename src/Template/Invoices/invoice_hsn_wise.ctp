<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Item Report</span>
		</div>
	</div>
	<div class="portlet-body">
				<form method="GET" >
			<table class="table table-condensed">
				<tbody>
					<tr>
						<td width="20%">
							<input type="text" name="From" class="form-control input-sm date-picker" placeholder="Transaction From" value="<?php echo @$From; ?>"  data-date-format="dd-mm-yyyy" >
						</td>
						<td width="20%">
									<input type="text" name="To" class="form-control input-sm date-picker" placeholder="Transaction To" value="<?php echo @$To; ?>"  data-date-format="dd-mm-yyyy" >
						</td>
						<td>
							<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		<div class="table-scrollable">
		<?php $page_no=$this->Paginator->current('Customers'); $page_no=($page_no-1)*20; ?>
			 <table class="table table-bordered table-striped table-hover" id="main_tble">
				 <thead>
					<tr>
						<th>S.N</th>
						<th>Invoice No</th>
						<th colspan="5"></th>
						<th>Freight</th>
						<th>Taxable Value </th>
						<th>Total CGST </th>
						<th>Total SGST </th>
						<th>Total IGST </th>
						<th>Total </th>
					</tr>
				</thead>
				<tbody>
					<?php $i=0;// pr( $Invoices);exit; 
						foreach ($Invoices as $invoice): 	 
						$i++; 
						$invoiceROwsSize=sizeof($invoice->invoice_rows);
					
						?>
					<tr rowspan=<?php echo $invoiceROwsSize ?>>
						<td width="5%"><?= h($i) ?></td>
						<td  width="25%" ><?= h(($invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT))) ?></td>
						<td  width="70%" colspan="5">
							<table class="table table-bordered table-striped table-hover" id="main_tble">
								<th width="12%">HSN</th>
								<th width="12%">Source</th>
								<th  width="10%">Unit</th>
								<th  width="8%">Quantity</th>
								<th  width="12%">Cost</th>
								<th  width="12%">Taxable Value </th>
											
										<?php foreach ($invoice->invoice_rows as $invoice_row){ 
												if($invoice_row->iv_row){ 
												foreach($invoice_row->iv_row->iv_row_items as $iv_row_item){
													//pr($iv_row_item->item_ledgers[0]->rate);
												?>
													
													<tr>	
														
														<td width="20%"><?php echo $iv_row_item->item->hsn_code; ?></td>
														<td width=""><?php echo "IV"; ?></td>
														<td><?php echo $iv_row_item->item->unit->name; ?></td>
														<td ><?php echo $iv_row_item->item_ledgers[0]->quantity; ?></td>
														<td width="25%"><?php echo $iv_row_item->item_ledgers[0]->rate*$iv_row_item->item_ledgers[0]->quantity; ?></td>
														<td ><?php echo $invoice_row->taxable_value; ?></td>
													</tr>
												<?php  } }else{ //pr($invoice_row); exit; ?>
													<tr>	
														
														<td width="20%" ><?php echo $invoice_row->item->hsn_code; ?></td>
														<td width="20%"><?php echo "Direct"; ?></td>
														<td ><?php echo $invoice_row->item->unit->name; ?></td>
														<td><?php echo $invoice_row->quantity; ?></td>
														<td width="25%"><?php echo $invoice_row->taxable_value; ?></td>
														<td ><?php echo $invoice_row->taxable_value; ?></td>
													</tr>
												<?php } 
												
										} ?>
									
								
							</table>
						</td>
						<td><?php echo $invoice->fright_amount; ?></td>
						<td><?php echo $invoice->total_after_pnf; ?></td>
						<td><?php echo $invoice->total_cgst_amount; ?></td>
						<td><?php echo $invoice->total_sgst_amount; ?></td>
						<td><?php echo $invoice->total_igst_amount; ?></td>
						<td><?php echo $invoice->grand_total; ?></td>
						
					</tr>
					<?php  endforeach; ?>
				</tbody>
			
			</table>
		</div>
		
	</div>
</div>


