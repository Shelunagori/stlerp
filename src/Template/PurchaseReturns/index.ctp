<?php $url_excel="/?".$url; ?>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Purchase Returns</span> 
			
		</div>
		<div class='actions'>
			<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/PurchaseReturns/Export-Excel/'.$url_excel.'',['class' =>'btn  green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
		</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
			<form method="GET" >
				<table class="table table-condensed">
					<tbody>
						<tr>
							<td width="20%">
								<input type="text" name="vendor_name" class="form-control input-sm" placeholder="Supplier Name" value="<?php echo @$vendor_name; ?>">
							</td>
							<td width="20%">
								<div class="input-group" style="" id="pnf_text">
								<span class="input-group-addon">PR</span><input type="text" name="vouch_no" class="form-control input-sm" placeholder="Voucher No" value="<?php echo @$vouch_no; ?>">
								</div>
							</td>
							<td width="15%">
								<input type="text" name="From" class="form-control input-sm date-picker" placeholder="Date From" value="<?php echo @$From; ?>"  data-date-format="dd-mm-yyyy" >
							</td>
							<td width="15%">
								<input type="text" name="To" class="form-control input-sm date-picker" placeholder="Date To" value="<?php echo @$To; ?>"  data-date-format="dd-mm-yyyy" >
							</td>
							<td><button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button></td>
						</tr>
					</tbody>
				</table>
			</form>
				<?php $page_no=$this->Paginator->current('Invoices'); $page_no=($page_no-1)*20; ?>
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Supplier</th>
							<th>Purchase Return No</th>
							<th>Invoice Booking No</th>
							<th>Date</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($purchaseReturns as $purchaseReturn): 
						//pr($saleReturn); 
						?>
						<tr>
							<td><?= h(++$page_no) ?></td>
							<td>
								<?= h($purchaseReturn->vendor->company_name);?>
							</td>
							<td><?php
							if($purchaseReturn->gst_type=="Gst"){
								echo $this->Html->link('#'.str_pad($purchaseReturn->voucher_no, 4, '0', STR_PAD_LEFT),['controller'=>'PurchaseReturns',
								'action' => 'gstView', $purchaseReturn->id],array('target'=>'_blank'));
							}else{
								echo $this->Html->link('#'.str_pad($purchaseReturn->voucher_no, 4, '0', STR_PAD_LEFT),['controller'=>'PurchaseReturns',
								'action' => 'View', $purchaseReturn->id],array('target'=>'_blank'));
							}		?></td>
							<td>
								<?php 
								if($purchaseReturn->invoice_booking->gst=='no'){
									echo $this->Html->link($purchaseReturn->invoice_booking->ib1.'/IB-'.str_pad($purchaseReturn->invoice_booking->ib2, 3, '0', STR_PAD_LEFT).'/'.$purchaseReturn->invoice_booking->ib3.'/'.$purchaseReturn->invoice_booking->ib4,[
								'controller'=>'InvoiceBookings','action' => 'view', $purchaseReturn->invoice_booking->id],array('target'=>'_blank'));
								}else{
										echo $this->Html->link($purchaseReturn->invoice_booking->ib1.'/IB-'.str_pad($purchaseReturn->invoice_booking->ib2, 3, '0', STR_PAD_LEFT).'/'.$purchaseReturn->invoice_booking->ib3.'/'.$purchaseReturn->invoice_booking->ib4,[
								'controller'=>'InvoiceBookings','action' => 'GstInvoiceBookingView', $purchaseReturn->invoice_booking->id],array('target'=>'_blank'));
								}	?>
							</td>
							<td><?php echo date("d-m-Y",strtotime($purchaseReturn->created_on)); ?></td>
						<td class="actions">
							<?php 
							if($purchaseReturn->gst_type=="Gst"){
								echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'gstView', $purchaseReturn->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View ')); 	
								echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'gstEdit?purchaseReturn='.$purchaseReturn->id,],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit')); 
							}
							else{
								echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'View', $purchaseReturn->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View ')); 	
								echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'Edit?purchaseReturn='.$purchaseReturn->id,],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit')); 
							}		?>
							
						</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="paginator">
			<ul class="pagination">
				<?= $this->Paginator->prev('< ' . __('previous')) ?>
				<?= $this->Paginator->numbers() ?>
				<?= $this->Paginator->next(__('next') . ' >') ?>
			</ul>
			<p><?= $this->Paginator->counter() ?></p>
	</div>
  </div>
</div>

