<?php $url_excel="/?".$url; ?>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">GST Sales Report</span>
		</div>
		<div class="actions">
			<?php //echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/Invoices/Sales-Man-Excel-Export/'.$url_excel.'',['class' =>'btn  green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
		</div>
		
	
	<div class="portlet-body form">
		<form method="GET">
			<table width="50%" class="table table-condensed">
				<tbody>
					<tr>
						<td width="10%">
							<input type="text" name="From" class="form-control input-sm date-picker" placeholder="Transaction From" value="<?php echo @date('d-m-Y', strtotime($From));  ?>"  data-date-format="dd-mm-yyyy">
						</td>	
						<td width="10%">
							<input type="text" name="To" class="form-control input-sm date-picker" placeholder="Transaction To" value="<?php echo @date('d-m-Y', strtotime($To));  ?>"  data-date-format="dd-mm-yyyy" >
						</td>
						<td width="10%">
							<?php echo $this->Form->input('States_id', ['empty'=>'---States---','options' => $States,'label' => false,'class' => 'form-control input-sm select2me States_id','placeholder'=>'Category','value'=> h(@$States_id) ]); ?>
						</td>
						<td width="10%">
							<div id="districts_id">
							<?php echo $this->Form->input('Districts_id', ['empty'=>'---District---','options' => $Districts,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'Category','value'=> h(@$Districts_id) ]); ?></div>
						</td>
						<td width="10%">
							<?php echo $this->Form->input('Customer_segment_id', ['empty'=>'---Customer Segment---','options' => $CustomerSegments,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'Category','value'=> h(@$Customer_segment_id) ]); ?>
						</td>
						<td width="10%">
							<?php echo $this->Form->input('Customer_group_id', ['empty'=>'---Customer Group---','options' => $CustomerGroups,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'Category','value'=> h(@$Customer_group_id) ]); ?>
						</td>
						
						<td width="5%">
							<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
						</td>
					</tr>
					
				</tbody>
			</table>
			</form>
		<!-- BEGIN FORM-->
		<div class="row ">
		
		
		<div class="col-md-12">
			<table class="table table-bordered table-condensed">
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
							<?php if(in_array($invoice->created_by,$allowed_emp)){  ?>
								<?php echo $this->Html->link( $invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', 	STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4,[
								'controller'=>'Invoices','action' => 'gstConfirm',$invoice_id],array('target'=>'_blank')); ?>
							<?php } else { ?>
								<?php echo $invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4; ?>
							<?php } ?>
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
				
		
					
			<!--Closed Quaotation Report End-->
				
				
				
			</div>
		</div>
	</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>
$(document).ready(function() {
	$('select[name="States_id"]').on("change",function() {
		$('#districts_id').html('Loading...');
		var States_id=$('select[name="States_id"] option:selected').val();
		var url="<?php echo $this->Url->build(['controller'=>'Invoices','action'=>'SelectDistrict']); ?>";
		url=url+'/'+States_id,
		$.ajax({
			url: url,
			type: 'GET',
		}).done(function(response) {
			$('#districts_id').html(response);
			$('select[name="Districts_id"]').select2();
		});
	});	
	//////
	$('select[name="item_group_id"]').die().live("change",function() {
		$('#item_sub_group_div').html('Loading...');
		var itemGroupId=$('select[name="item_group_id"] option:selected').val();
		var url="<?php echo $this->Url->build(['controller'=>'ItemSubGroups','action'=>'ItemSubGroupDropdown']); ?>";
		url=url+'/'+itemGroupId,
		$.ajax({
			url: url,
			type: 'GET',
		}).done(function(response) {
			$('#item_sub_group_div').html(response);
			$('select[name="item_sub_group_id"]').select2();
		});
	});
});
</script>