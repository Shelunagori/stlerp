<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Invoice Gross Profit Report</span>
		</div>
		<div class="actions">
		
		</div>
	</div>	
	
	<div class="portlet-body form">
		
		
		
		
		<div class="table-scrollable">
		<?php $page_no=$this->Paginator->current('Customers'); $page_no=($page_no-1)*20; ?>
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th  style="text-align:left;">Sr.No.</th>
						<th style="text-align:center;">Invoice No</th>
						<th style="text-align:center;"></th>
						
					</tr>
				</thead>
				<tbody><?php  ?>
				<?php $i=1; $refSize=0; $dataArray=[]; foreach ($Invoices as $invoice):
				
				$refSize=(sizeof($invoice->gross_profit_reports)); 
				?>
					<tr>
						<td width="5%"  style="text-align:center; vertical-align: top !important;" rowspan=""><?php echo $i++; ?></td>
						
						<td  width="30%" style="text-align:center; vertical-align: top !important;" rowspan="">
						<?php
							echo $invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4;
						?></td>
						<td style="vertical-align: top !important;">
							<table class="table table-bordered  ">
								<thead>
									<tr>		
										<th style="text-align:center;" width="5%">S.N</th>
										<th style="text-align:center;" width="">Item </th>
										<th style="text-align:center;" width="15%">Cost </th>
										<th style="text-align:center;" width="20%">Net Amount </th>
										<th style="text-align:center;" width="20%">GP(%) </th>
									</tr>
								<thead>
								<tbody>
									<?php $p=1; foreach($invoice->gross_profit_reports as $data): 
									$per=($data->inventory_ledger_cost/$data->taxable_value)*100
									?>
									<tr>
										<td style="text-align:center;" rowspan=""><?php echo $p++; ?></td>
										<td  style="text-align:center;" rowspan=""><?php echo $data->invoice_row->item->name; ?></td>
										<td style="text-align:right;" rowspan=""><?= h($this->Number->format($data->inventory_ledger_cost,[ 'places' => 2])) ?></td>
										<td style="text-align:right;" rowspan=""><?= h($this->Number->format($data->taxable_value,[ 'places' => 2])) ?></td>
										
										<td  style="text-align:right;" rowspan=""><?php echo round(100-$per,2); ?>%</td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</td>
					</tr>
				<?php endforeach; ?>
				
				</tbody>
			</table>
			</div>
		
</div>
</div>
