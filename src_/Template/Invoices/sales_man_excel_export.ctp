<?php 

	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="GST_Sales_Man_report_".$date.'_'.$time;

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
				<b><h4 class="caption-subject font-black-steel uppercase">Sales Invoice
				<?php if(!empty($From) || !empty($To)){ echo date('d-m-Y',strtotime($From)); ?> TO <?php echo date('d-m-Y',strtotime($To));  } ?></h4></b>
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
			foreach($invoice->invoice_rows as $invoice_row)
			{ 
				 if($invoice_row['igst_percentage'] == 0){
				
				@$gstTotal[$invoice->id][$invoice_row['cgst_percentage']]+=@$gstTotal[$invoice->id][$invoice_row['cgst_percentage']]+(@$invoice_row->taxable_value);
				}
				else if($invoice_row['igst_percentage'] > 0){
					@$SigstTotal[$invoice->id][$invoice_row['igst_percentage']]+=@$SigstTotal[$invoice->id][$invoice_row['igst_percentage']]+$invoice_row->taxable_value;
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
<table border="1">
	<thead>
		<tr><?php $col=sizeof($invoicesGst->toArray()); $col=($col*1)+5;  ?>
			<td colspan="<?php echo $col; ?>" align="center"  valign="top">
				<b><h4 class="caption-subject font-black-steel uppercase">Sales Order Booked
				<?php if(!empty($From) || !empty($To)){ echo date('d-m-Y',strtotime($From)); ?> TO <?php echo date('d-m-Y',strtotime($To));  } ?></h4></b>
			</td>
		</tr>
		<tr>
			<th>Sr.No.</th>
			<th>Sales Order  No</th>
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
			<th style="text-align:right;">Expected Delivery Date</th>
		</tr>
	</thead>
	<?php $SalesOrderTotal=[];$SalesOrdergstTotal=[];$SalesOrdergstRowTotal=[]; $SalesOrdergstTotal=[];$i=1; $SalesOrderigstRowTotal=[];$SalesOrderigstTotal=[];
		foreach ($SalesOrders as $SalesOrder):
			foreach($SalesOrder->sales_order_rows as $sales_order_rows)
			{ 
				 if($sales_order_rows['igst_per'] == 0){
				
				@$SalesOrderTotal[$SalesOrder->id][$sales_order_rows['cgst_per']]=@$SalesOrderTotal[$SalesOrder->id][$sales_order_rows['cgst_per']]+(@$sales_order_rows->taxable_value);
				}
				else if($sales_order_rows['igst_per'] > 0){
					@$SalesOrderigstTotal[$SalesOrder->id][$sales_order_rows['igst_per']]=@$SalesOrderigstTotal[$SalesOrder->id][$sales_order_rows['igst_per']]+$sales_order_rows->taxable_value;
				} 
			} ?>
		<tbody>
		<tr>
			<td><?php echo $i; ?></td>
			<td>
				<?php echo $SalesOrder->so1.'/SO-'.str_pad($SalesOrder->so2, 3, '0', STR_PAD_LEFT).'/'.$SalesOrder->so3.'/'.$SalesOrder->so4; ?>
			</td>
			<td><?php echo date("d-m-Y",strtotime($SalesOrder->created_on)); ?></td>
			<td><?php echo $SalesOrder->customer->customer_name.'('.$SalesOrder->customer->alias.')'?></td>
			<?php $k=0; $AllTaxs=[];
				foreach($invoicesGst as $Key1=>$SaleTaxeGst){ 
						$AllTaxs[$k]=$SaleTaxeGst->id;
						$k++;
				}
				//pr($AllTaxs); exit;
			?>
			<?php foreach($AllTaxs as  $key=>$AllTax){ 
				if(isset($SalesOrderTotal[$SalesOrder->id][$AllTax]))
				{?>
					
						<td style="text-align:right;"><?php echo $this->Number->format($SalesOrderTotal[$SalesOrder->id][$AllTax],['places'=>2]); 
						$SalesOrdergstRowTotal[$AllTax]=@$SalesOrdergstRowTotal[$AllTax]+$SalesOrderTotal[$SalesOrder->id][$AllTax];
						?></td>
						<?php 
				}else if(isset($SalesOrderigstTotal[$SalesOrder->id][$AllTax])){  ?>
					<td style="text-align:right;"><?php echo $this->Number->format($SalesOrderigstTotal[$SalesOrder->id][$AllTax],['places'=>2]); 
						$SalesOrderigstRowTotal[$AllTax]=@$SalesOrderigstRowTotal[$AllTax]+$SalesOrderigstTotal[$SalesOrder->id][$AllTax];
						?></td>
				<?php }							
				else 
				{ 
				?>
					
					<td style="text-align:right;"><?php echo "-"; ?></td>
				<?php 
				} 
				
			}  ?>
			<td><?php echo date('d-m-Y',strtotime($SalesOrder->expected_delivery_date)); ?></td>
		</tr>
		<?php $i++; endforeach; ?>
		<tr>
			<td style="text-align:right;" colspan=4><b>Total</b></td>
			<?php 
				foreach($invoicesGst as $Key1=>$SaleTaxeGst){  
					if(!empty($SalesOrdergstRowTotal[$SaleTaxeGst->id])){
				?>
					<td style="text-align:right;"><b><?php echo 
					$this->Number->format(@$SalesOrdergstRowTotal[$SaleTaxeGst->id],['places'=>2]); ?></b></td>
				<?php }else if(!empty($SalesOrderigstRowTotal[$SaleTaxeGst->id])){ ?>
					<td style="text-align:right;"><b><?php echo 
					$this->Number->format(@$SalesOrderigstRowTotal[$SaleTaxeGst->id],['places'=>2]); ?></b></td>
				<?php }
				else{ ?>
					<td style="text-align:right;"><b>-</b></td>
					
				<?php }} ?>
			<td></td>
		</tr>
	</tbody>
</table>

<table border="1">
	<thead>
			<tr>
				<td colspan="8" align="center"  valign="top">
					<b><h4 class="caption-subject font-black-steel uppercase">Open Quotations <?php if(!empty($From) || !empty($To)){ echo date('d-m-Y',strtotime($From)); ?> TO <?php echo date('d-m-Y',strtotime($To));  } ?></h4></b>
				</td>
			</tr>
			<tr>
				<th>Sr.No.</th>
				<th>Quotation  No</th>
				<th>Date</th>
				<th>Customer</th>
				<th>Brand</th>
				<th>Item</th>
				<th style="text-align:right;">Value</th>
				<th>Expected Finalisation Date</th>
			</tr>
	</thead>
		<?php $i=1; $total=0;
		foreach ($OpenQuotations as $openquotation):    ?>
		<tbody>
			<tr>
				<td>
					<?php echo $i; ?>
				</td>
				<td>
					<?php echo $openquotation->qt1.'/QT-'.str_pad($openquotation->qt2, 3, '0', STR_PAD_LEFT).'/'.$openquotation->qt3.'/'.$openquotation->qt4; ?>
				</td>
				<td>
					<?php echo date("d-m-Y",strtotime($openquotation->created_on)); ?>
				</td>
				<td>
					<?php echo @$openquotation->customer->customer_name.'('.@$openquotation->customer->alias.')'?>
				</td>
				<td>
					<?php  echo @$openquotation->quotation_rows[0]->item->item_category->name; ?>
				</td>
				<td>
					<?php  echo @$openquotation->quotation_rows[0]->item->name; ?>
				</td>
				<td align="right">
					<?php  echo $this->Number->format(@$openquotation->total,['places'=>2]);
								$total=$total+($openquotation->total);	?>
				</td>
				<td><?php echo date("d-m-Y",strtotime(@$openquotation->finalisation_date)); ?></td>
				
			</tr>
		<?php $i++; endforeach; ?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td align="right"><b>Total</b></td>
			<td align="right"><b><?php echo $this->Number->format($total,['places'=>2]); ?></b></td>
			<td align="right"></td>
		</tr>
		</tbody>
</table>

<table border='1'>
	<thead>
		<tr>
			<td colspan="8" align="center"  valign="top">
				<b><h4 class="caption-subject font-black-steel uppercase">Closed Quotations <?php if(!empty($From) || !empty($To)){ echo date('d-m-Y',strtotime($From)); ?> TO <?php echo date('d-m-Y',strtotime($To));  } ?></h4></b>
			</td>
		</tr>	
		<tr>
			<th>Sr.No.</th>
			<th>Quotation  No</th>
			<th>Date</th>
			<th>Customer</th>
			<th>Brand</th>
			<th>Item</th>
			<th style="text-align:right;">Value</th>
			<th>Closure reason</th>
		</tr>
	</thead>
	<?php $i=1; $total=0;
	foreach ($ClosedQuotations as $closedquotation):    ?>
		<tbody>
		<tr>
			<td>
				<?php echo $i; ?>
			</td>
			<td>
				<?php echo $closedquotation->qt1.'/QT-'.str_pad($closedquotation->qt2, 3, '0', STR_PAD_LEFT).'/'.$closedquotation->qt3.'/'.$closedquotation->qt4; ?>
			</td>
			<td>
				<?php echo date("d-m-Y",strtotime($closedquotation->created_on)); ?>
			</td>
			<td>
				<?php echo @$closedquotation->customer->customer_name.'('.@$closedquotation->customer->alias.')'?>
			</td>
			<td>
				<?php  echo @$closedquotation->quotation_rows[0]->item->item_category->name; ?>
			</td>
			<td>
				<?php  echo @$closedquotation->quotation_rows[0]->item->name; ?>
			</td>
			<td align="right">
				<?php  echo $this->Number->format(@$closedquotation->total,['places'=>2]);
							$total=$total+($closedquotation->total);	?>
			</td>
			<td><?php if(!empty($closedquotation->reason)){ echo @$closedquotation->reason; }else{ echo "-"; } ?></td>
			
		</tr>
		<?php $i++; endforeach; ?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td align="right"><b>Total</b></td>
			<td align="right"><b><?php echo $this->Number->format($total,['places'=>2]); ?></b></td>
			<td align="right"></td>
		</tr>
	</tbody>
</table>

