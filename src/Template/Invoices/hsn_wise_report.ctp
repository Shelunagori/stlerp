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
						<th>HSN</th>
						<th>Item Category</th>
						<th>Unit</th>
						<th>Quantity</th>
						<th>Total Value</th>
						<th>Taxable Value</th>
						<th>cgst</th>
						<th>sgst</th>
						<th>igst</th>
						
					</tr>
				</thead>
				<tbody>
					<?php $i=0; foreach ($hsn as $hsn): 	 
					if($hsn){	$i++; 	?>
					<tr>
						<td><?= h($i) ?></td>
						<td><?= h($hsn) ?></td>
						<td><?= h($item_category[$hsn]) ?></td>
						<td><?= h($unit[$hsn]) ?></td>
						<td><?= h($quantity[$hsn]) ?></td>
						<td><?= h($total_value[$hsn]) ?></td>
						<td><?= h($taxable_value[$hsn]) ?></td>
						<td><?= h($cgst[$hsn]) ?></td>
						<td><?= h($sgst[$hsn]) ?></td>
						<td><?= h($igst[$hsn]) ?></td>
						
					</tr>
					<?php } endforeach; ?>
				</tbody>
			
			</table>
			</div>
		</div>
		
	</div>
</div>


