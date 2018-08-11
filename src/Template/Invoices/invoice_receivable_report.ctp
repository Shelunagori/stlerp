<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Invoice Recivable Report</span>
		</div>
		<div class="actions">
		
		</div>
		
	
	<div class="portlet-body form">
		
		<!-- BEGIN FORM-->
		
		<div class="row ">
		
		<div class="col-md-12">
		
		
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Sr.No.</th>
						<th>Invoice No</th>
						<th>Date</th>
						<th>No Of Days</th>
						<th>Reciept No</th>
						<th>Reciept Date</th>
					</tr>
				</thead>
				<tbody><?php  ?>
				<?php $i=1; foreach ($Invoices as $invoice):
				$refSize=0;
				$refSize=(sizeof($Receiptdatas[$invoice->id])); 
				if($refSize > 0){
				?>
					<tr>
						<td rowspan="<?php echo $refSize; ?>"><?php echo $i++; ?></td>
						<td rowspan="<?php echo $refSize; ?>"><?= h(($invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4)) ?></td>
						<td rowspan="<?php echo $refSize; ?>"><?php echo date("d-m-Y",strtotime($invoice->date_created)); ?></td>
						<td rowspan="<?php echo $refSize; ?>"><?php echo $invoice->customer->payment_terms ?>Days</td>
						<?php 
							$jk=0;
								foreach($Receiptdatas[$invoice->id] as $data){
									if($jk > 0)
									{
										?>
										<tr>
										<?php
									}
									$jk++;
									?>
									<td><?php echo $data->receipt->voucher_no; ?></td>
									<td rowspan="<?php echo $refSize; ?>"><?php echo date("d-m-Y",strtotime($data->receipt->transaction_date)); ?></td>
									</tr>
								 <?php } ?>
								   <?php } else{?>
									<td ><?php echo $i++; ?></td>
									<td ><?= h(($invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4)) ?></td>
									<td><?php echo date("d-m-Y",strtotime($invoice->date_created)); ?></td>
									<td ><?php echo $invoice->customer->payment_terms ?>Days</td> 
									<td >-</td> 
								   <?php }?>
					</tr>
				<?php  endforeach; ?>
				
				</tbody>
			</table>
			</div>
		</div>
	</div>
</div>