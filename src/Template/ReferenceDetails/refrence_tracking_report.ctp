<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Refrence Tracking  Report</span>
		</div>
		<div class="actions">
		
		</div>
	</div>	
	
	<div class="portlet-body form">
		
		
		
		
		<div class="table-scrollable">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th  style="text-align:left;">Sr.No.</th>
						<th style="text-align:center;">Refrence</th>
						<th style="text-align:center;"></th>
						
					</tr>
				</thead>
				<tbody><?php  ?>
				<?php $i=1; $refSize=0; $dataArray=[]; 
				
				foreach ($InRef as $key=>$InDatas):
					foreach ($InDatas as $key=>$InData){
						pr($OutRef[$key]) exit;
				?>
					<tr>
						<td width="5%"  style="text-align:center; vertical-align: top !important;" rowspan=""><?php echo $i++; ?></td>
						
						<td  width="30%" style="text-align:center; vertical-align: top !important;" rowspan="">
						<?php
							echo $key;
						?></td>
						<td style="vertical-align: top !important;">
							<table class="table table-bordered  ">
								<thead>
									<tr>		
										<th style="text-align:center;" width="5%">S.N</th>
										<th style="text-align:center;" width="">Voucher </th>
									</tr>
								</thead>
								<tbody>
									<?php $p=1; foreach($invoice->gross_profit_reports as $data): 
									
									?>
									<tr>
										<td style="text-align:center;" rowspan=""><?php echo $p++; ?></td>
										<td  style="text-align:center;" rowspan=""><?php echo $data->invoice_row->item->name; ?></td>
										
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</td>
					</tr>
					<?php } endforeach; ?>
				
				</tbody>
			</table>
			</div>
		
</div>
</div>
