<?php $url_excel="/?".$url; ?>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Inventory Transfer Vouchers</span>
		</div>
		<div class='actions'>
			<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/InventoryTransferVouchers/Excel-Export/'.$url_excel.'',['class' =>'btn  green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
		</div>
	<div class="portlet-body">
		<div class="row">
		<form method="GET" >
				<table class="table table-condensed">
					<tbody>
						<tr>
							<td width="20%">
								<?php 
								
								$options=array();
								foreach($customers as $customer){
									if(empty($customer->alias)){
										$merge=$customer->customer_name;
									}else{
										$merge=$customer->customer_name.'	('.$customer->alias.')';
									}
									
									$options[]=['text' =>$merge, 'value' => $customer->id];
								}
								
								echo $this->Form->input('customers_id', ['empty'=>'--Customers--','options' => $options,'label' => false,'class' => 'form-control input-sm customers_id','placeholder'=>'Category','value'=> h(@$customers_id) ]); ?>
							</td>
							<td width="20%">
								<?php 
								foreach($vendor as $vendors){
								if(empty($vendors->alias)){
									$merge1=$vendors->company_name;
								}else{
									$merge1=$vendors->company_name.'('.$vendors->alias.')';
								}
								
								$options1[]=['text' =>$merge1, 'value' => $vendors->id];

							}
								
								echo $this->Form->input('vendor_id', ['empty'=>'--Vendor--','options' => $options1,'label' => false,'class' => 'form-control input-sm vendor_id','placeholder'=>'Category','value'=> h(@$vendor_id) ]); ?>
							</td>
							
							<td width="20%"> 
								<div class="input-group" style="" id="pnf_text">
									<span class="input-group-addon">ITV-</span>
								<input type="text" name="vouch_no" class="form-control input-sm" placeholder="Voucher No" value="<?php echo @$vouch_no; ?>">
							</div>	
							</td>
							<td width="20%">
								<input type="text" name="From" class="form-control input-sm date-picker" placeholder="Transaction Date From" value="<?php echo @$From; ?>" data-date-format="dd-mm-yyyy" >
							</td>
							<td width="20%">
								<input type="text" name="To" class="form-control input-sm date-picker" placeholder="Transaction Date To" value="<?php echo @$To; ?>" data-date-format="dd-mm-yyyy" >
							</td>
							
							<td ><button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button></td>
						</tr>
					</tbody>
				</table>
				</form>
			<div class="col-md-12">
				<?php $page_no=$this->Paginator->current('InventoryTransferVouchers'); $page_no=($page_no-1)*20; ?>
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Customer</th>
							<th>Supplier</th>
							<th>Vocher No</th>
							<th>Transaction Date</th>
							<th>Action</th>
							
							
						</tr>
					</thead>
					<tbody>
						<?php $i=0; foreach ($inventory_transfer_vouchs as $inventory_transfer_vouch_data): $i++; 
						$inventory_transfer_vouch_data->id = $EncryptingDecrypting->encryptData($inventory_transfer_vouch_data->id);
					?>
						<tr>
							<td><?= h(++$page_no) ?></td>
							<td>
								<?php if(!empty($inventory_transfer_vouch_data->customer)){
									echo $inventory_transfer_vouch_data->customer->customer_name;
								}else{
									echo "-";
								}
									?>
							</td>
							<td>
								<?php if(!empty($inventory_transfer_vouch_data->vendor)){
									echo $inventory_transfer_vouch_data->vendor->company_name;
								}else{
									echo "-";
								}
									?>
							</td>
							<?php if($inventory_transfer_vouch_data->in_out=='in_out'){ ?>
							<td><?= h('ITV-'.str_pad($inventory_transfer_vouch_data->voucher_no, 4, '0', STR_PAD_LEFT)) ?></td>
							<?php }else if($inventory_transfer_vouch_data->in_out=='In') { ?>
							<td><?= h('ITVI-'.str_pad($inventory_transfer_vouch_data->voucher_no, 4, '0', STR_PAD_LEFT)) ?></td>
							<?php }else { ?>
							<td><?= h('ITVO-'.str_pad($inventory_transfer_vouch_data->voucher_no, 4, '0', STR_PAD_LEFT)) ?></td>
							<?php } ?>
							<td><?= h(date("d-m-Y",strtotime($inventory_transfer_vouch_data->transaction_date)))?></td>
							<td>
							<?php //if(in_array($inventory_transfer_vouch_data->created_by,$allowed_emp)){ ?>
							<?php if($inventory_transfer_vouch_data->in_out=='Out'){ ?>
							<?php 
							if(in_array(143,$allowed_pages)){
							echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'outView', $inventory_transfer_vouch_data->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View '));  ?>
							<?php }}else if($inventory_transfer_vouch_data->in_out=='In') { ?>
							<?php 
							if(in_array(144,$allowed_pages)){
								echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'inView', $inventory_transfer_vouch_data->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View '));  ?>
							<?php }}else { ?>
							<?php 
							if(in_array(139,$allowed_pages)){
							echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'view', $inventory_transfer_vouch_data->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View '));  ?>
							<?php }} ?>
							
							<?php if($inventory_transfer_vouch_data->in_out=='Out'){ ?>
							<?php 
							if(in_array(147,$allowed_pages)){
							echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'editInventoryOut', $inventory_transfer_vouch_data->id,],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit'));    ?>
							<?php }}else if($inventory_transfer_vouch_data->in_out=='In') { ?>
							<?php 
							if(in_array(148,$allowed_pages)){
							echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'editInventoryIn', $inventory_transfer_vouch_data->id,],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit'));   ?>
							<?php }}else { ?>
							<?php 
							if(in_array(138,$allowed_pages)){
							echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $inventory_transfer_vouch_data->id,],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit'));  ?>
							<?php }} ?>
							</td>
							
						
						</tr>
						<?php  endforeach; ?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
				
			</div>
		</div>
	<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>
$(document).ready(function() {
	$('.customers_id').select2({allowClear :true});
	$('.vendor_id').select2({allowClear :true});
	
});
</script>

