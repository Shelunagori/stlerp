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
						<th>Sr. No.</th>
						<th>Invoice No</th>
						<th>HSN</th>
						<th>Cost</th>
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
						<td  width="25%" ><?= h(($invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4)) ?></td>
						<td  width="70%" colspan="2">
							<table class="table table-bordered table-striped table-hover" id="main_tble">
								
									
										<?php foreach ($invoice->invoice_rows as $invoice_row){ 
												if($invoice_row->iv_row){ 
												foreach($invoice_row->iv_row->iv_row_items as $iv_row_item){
													//pr($iv_row_item->item_ledgers[0]->rate);
												?>
													
													<tr>	
														<td width="50%"><?php echo $iv_row_item->item->hsn_code; ?></td>
														<td width="50%"><?php echo $iv_row_item->item_ledgers[0]->rate*$iv_row_item->item_ledgers[0]->quantity; ?></td>
													</tr>
												<?php  } }else{ ?>
													<tr>	
														<td width="50%"><?php echo $invoice_row->item->hsn_code; ?></td>
														<td width="50%"><?php echo $invoice_row->rate*$invoice_row->quantity; ?></td>
													</tr>
												<?php } 
												
										} ?>
									
								
							</table>
						</td>
					</tr>
					<?php  endforeach; ?>
				</tbody>
			
			</table>
		</div>
		
	</div>
</div>


