<?php 
	
	if(!empty($status)){
		$url_excel=$status."/?".$url;
	}else{
		$url_excel="/?".$url;
	} //pr($status); exit;
?>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Invoice Gross Profit Report</span>
		</div>
		<div class="actions">
			<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/Invoices/Profit-Export/'.$url_excel.'',['class' =>'btn  green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
		</div>
	</div>	
	
	<div class="portlet-body form">
		<div class="row">
			<div class="col-md-12">
				<form method="GET" >
					<table class="table table-bordered table-striped table-hover">
						<tr>
							<td width="15%">
								<?php echo $this->Form->input('salesman', ['empty'=>'--SalesMans--','options' => $SalesMans,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'SalesMan Name','value'=> h(@$salesman) ]); ?>
							</td>
							<td width="15%">
								<input type="text" name="customer" class="form-control input-sm" placeholder="Customer" value="<?php echo @$customer; ?>">
							</td>	
							<td width="15%">
									<?php echo $this->Form->input('item_name', ['empty'=>'--Item--','options' => $Items,'label' => false,'class' => 'form-control input-sm select2me item_name','placeholder'=>'Category','value'=> h(@$item_name) ]); ?>
							</td>
							<td width="15%">
									<?php echo $this->Form->input('item_category', ['empty'=>'--Category--','options' => $ItemCategories,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'Category','value'=> h(@$item_category) ]); ?>
							</td>
							<td width="15%">
								<div id="item_group_div">
								<?php echo $this->Form->input('item_group_id', ['empty'=>'--Group--','options' =>$ItemGroups,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'Group','value'=> h(@$item_group)]); ?></div>
							</td>
							<td width="15%">
								<div id="item_sub_group_div">
								<?php echo $this->Form->input('item_sub_group_id', ['empty'=>'--SubGroup--','options' =>$ItemSubGroups,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'Sub-Group','value'=> h(@$item_sub_group)]); ?></div>
							</td>
							<td><button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
									</td>
						</tr>
					</table>
				</form>
			</div>
		</div>	
		
		
		<div class="table-scrollable">
		<?php $page_no=$this->Paginator->current('Customers'); $page_no=($page_no-1)*20; ?>
			<table class="table table-bordered table-striped table-hover">
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
						<?php
						if($invoice->invoice_type == 'GST'){
							echo $this->Html->link($invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4,[
							'controller'=>'Invoices','action' => 'gstConfirm',$invoice_id],array('target'=>'_blank'));
						}else{
							echo $this->Html->link($invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4,[
							'controller'=>'Invoices','action' => 'confirm',$invoice_id],array('target'=>'_blank'));
						}	
						?></td>
						<td  width="8%" style="text-align:center; vertical-align: top !important;" rowspan="">
							<?php echo date('d-m-Y',strtotime($invoice->date_created)); ?>
						</td>
						<td  width="15%" style="text-align:center; vertical-align: top !important;" rowspan="">
						<?php $sales_order_id = $EncryptingDecrypting->encryptData($invoice->sales_order->id);	 ?>
							<?php if($invoice->sales_order->gst == 'yes'){ 
								echo $this->Html->link($invoice->sales_order->so1.'/SO-'.str_pad($invoice->sales_order->so2, 3, '0', STR_PAD_LEFT).'/'.$invoice->sales_order->so3.'/'.$invoice->sales_order->so4,[
								'controller'=>'SalesOrders','action' => 'gstConfirm',$sales_order_id],array('target'=>'_blank'));
							}else{
								echo $this->Html->link($invoice->sales_order->so1.'/SO-'.str_pad($invoice->sales_order->so2, 3, '0', STR_PAD_LEFT).'/'.$invoice->sales_order->so3.'/'.$invoice->sales_order->so4,[
								'controller'=>'SalesOrders','action' => 'confirm',$sales_order_id],array('target'=>'_blank'));
							}?>
						</td>
						<td  width="8%" style="text-align:center; vertical-align: top !important;" rowspan="">
							<?php echo date('d-m-Y',strtotime($invoice->sales_order->created_on)); ?>
						</td>
						<td style="vertical-align: top !important;">
							<table class="table table-bordered  ">
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
			</div>
		
</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>

$(document).ready(function(){
	////////
	$('select[name="item_category"]').on("change",function() {
		$('#item_group_div').html('Loading...');
		$('#item_sub_group_div').html('Loading...');
		var itemCategoryId=$('select[name="item_category"] option:selected').val();
		var url="<?php echo $this->Url->build(['controller'=>'ItemGroups','action'=>'ItemGroupDropdown']); ?>";
		url=url+'/'+itemCategoryId,
		$.ajax({
			url: url,
			type: 'GET',
		}).done(function(response) {
			$('#item_group_div').html(response);
			$('select[name="item_group_id"]').select2();
			
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
