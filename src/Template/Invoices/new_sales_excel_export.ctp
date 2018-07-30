<?php 

	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="Sales_Report_Segment_Wise_".$date.'_'.$time;

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
					<?php $col=sizeof($invoicesGst->toArray()); $col=($col*1)+4;  ?>
						<td colspan="<?php echo $col; ?>" align="center"  valign="top">
							<h4 class="caption-subject font-black-steel uppercase">Sales Invoice</h4>
						</td>
					</tr>
					<tr>
						<th>Sr.No.</th>
						<th>Invoice No</th>
						<th>Date</th>
						<th>Customer</th>
						<?php foreach($invoicesGst as $Key1=> $SaleTaxeGst){ 
							if($SaleTaxeGst->cgst == 'Yes'){
								$saletax=($SaleTaxeGst->tax_figure*2); ?>
							<th style="text-align:right;">Sales GST @ <?php echo $saletax; ?> % </th>	
						<?php	}else{
								$saletax=$SaleTaxeGst->tax_figure; ?>
							<th style="text-align:right;">Sales IGST @ <?php echo $saletax; ?> % </th>	
						<?php }  } ?>
					</tr>
				</thead>
				
					<?php $SalesTotal=[];$SalesgstTotal=[];$gstRowTotal=[]; $gstTotal=[];$i=1; $SigstRowTotal=[];$SigstTotal=[];
					foreach ($invoices as $invoice):
					$invoice_id = $EncryptingDecrypting->encryptData($invoice->id);
						foreach($invoice->invoice_rows as $invoice_row)
						{ 
							 if($invoice_row['igst_percentage'] == 0){
							
							@$gstTotal[$invoice->id][$invoice_row['cgst_percentage']]=@$gstTotal[$invoice->id][$invoice_row['cgst_percentage']]+(@$invoice_row->taxable_value);
							}
							else if($invoice_row['igst_percentage'] > 0){
								@$SigstTotal[$invoice->id][$invoice_row['igst_percentage']]=@$SigstTotal[$invoice->id][$invoice_row['igst_percentage']]+$invoice_row->taxable_value;
							} 
						}
						
						
					?>
				
				
					
				<tbody>
					<tr>
						<td><?php echo $i; ?></td>
						<td>
							
								<?php echo $invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4; ?>
							
						</td>
						<td><?php echo date("d-m-Y",strtotime($invoice->date_created)); ?></td>
						<td><?php echo $invoice->customer->customer_name.'('.$invoice->customer->alias.')'?></td>
						<?php $k=0; $AllTaxs=[];
							foreach($invoicesGst as $Key1=>$SaleTaxeGst){ 
									$AllTaxs[$k]=$SaleTaxeGst->id;
									$k++;
							}
							//pr($AllTaxs); exit;
						?>
						<?php foreach($AllTaxs as  $key=>$AllTax){ 
							if(isset($gstTotal[$invoice->id][$AllTax]))
							{?>
								
									<td style="text-align:right;"><?php echo $this->Number->format($gstTotal[$invoice->id][$AllTax]+(@$invoice->fright_amount),['places'=>2]); 
									$SalesgstTotal[$AllTax]=@$SalesgstTotal[$AllTax]+$gstTotal[$invoice->id][$AllTax]+(@$invoice->fright_amount);
									?></td>
									<?php 
							}else if(isset($SigstTotal[$invoice->id][$AllTax])){  ?>
								<td style="text-align:right;"><?php echo $this->Number->format($SigstTotal[$invoice->id][$AllTax]+(@$invoice->fright_amount),['places'=>2]); 
									$gstRowTotal[$AllTax]=@$gstRowTotal[$AllTax]+$SigstTotal[$invoice->id][$AllTax]+(@$invoice->fright_amount);
									?></td>
							<?php }							
							else 
							{ 
							?>
								
								<td style="text-align:right;"><?php echo "-"; ?></td>
							<?php 
							} 
							
						}  ?>
						
					</tr>
				<?php $i++; endforeach; ?>
				<tr>
				<td style="text-align:right;" colspan=4><b>Total</b></td>
				<?php 
					foreach($invoicesGst as $Key1=>$SaleTaxeGst){  
						if(!empty($SalesgstTotal[$SaleTaxeGst->id])){
					?>
						<td style="text-align:right;"><b><?php echo 
						$this->Number->format(@$SalesgstTotal[$SaleTaxeGst->id],['places'=>2]); ?></b></td>
					<?php }else if(!empty($gstRowTotal[$SaleTaxeGst->id])){ ?>
						<td style="text-align:right;"><b><?php echo 
						$this->Number->format(@$gstRowTotal[$SaleTaxeGst->id],['places'=>2]); ?></b></td>
					<?php }
					else{ ?>
						<td style="text-align:right;"><b>-</b></td>
						
					<?php }} ?>
				
				</tr>
				</tbody>
				</table>