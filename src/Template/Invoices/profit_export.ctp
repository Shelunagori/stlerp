<?php 

	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="Invoice_report_".$date.'_'.$time;

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
					<tr>
						<th  style="text-align:left;">Sr.No.</th>
						<th  style="text-align:left;">Customer Name</th>
						<th style="text-align:center;">Invoice No</th>
						<th style="text-align:center;">Invoice Date</th>
						<th style="text-align:center;">SO No</th>
						<th style="text-align:center;">SO Date</th>
						
					</tr>
					
				</thead>
				<tbody><?php  ?>
				<?php $x=0; $i=1; $refSize=0; $dataArray=[]; foreach ($Invoices as $invoice):
				
				$invoice_id = $EncryptingDecrypting->encryptData($invoice->id);
				$refSize=(sizeof($invoice->gross_profit_reports)); 
				if(@$refSize){  $x++;
				?>
					<tr>
						<td width="5%"  style="text-align:center; vertical-align: top !important;" rowspan=""><?php echo $i++; ?></td>
						<td  style="text-align:center; vertical-align: top !important;" rowspan="">
							<?php 
							if(!empty($invoice->customer->alias)){
								echo $invoice->customer->customer_name.'('.$invoice->customer->alias.')';
							}else{
								echo $invoice->customer->customer_name;
							}
							 ?>
						</td>
						<td  width="15%" style="text-align:center; vertical-align: top !important;" rowspan="">
						<?php echo $invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4;
						?></td>
						<td  width="8%" style="text-align:center; vertical-align: top !important;" rowspan="">
							<?php echo date('d-m-Y',strtotime($invoice->date_created)); ?>
						</td>
						<td  width="15%" style="text-align:center; vertical-align: top !important;" rowspan="">
						<?php echo $invoice->sales_order->so1.'/SO-'.str_pad($invoice->sales_order->so2, 3, '0', STR_PAD_LEFT).'/'.$invoice->sales_order->so3.'/'.$invoice->sales_order->so4;?>
						</td>
						<td  width="8%" style="text-align:center; vertical-align: top !important;" rowspan="">
							<?php echo date('d-m-Y',strtotime($invoice->sales_order->created_on)); ?>
						</td>
						<td style="vertical-align: top !important;">
							<table border="1">
							<?php if($x==1){ ?>
								<tr>
									<th style="text-align:center;" width="5%">S.N</th>
									<th style="text-align:center;" width="">Item </th>
									<th style="text-align:center;" width="15%">Cost </th>
									<th style="text-align:center;" width="20%">Net Amount </th>
									<th style="text-align:center;" width="20%">GP(%) </th>
								</tr>
							<?php } ?>
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
				<?php } endforeach; ?>
				
				</tbody>
</table>