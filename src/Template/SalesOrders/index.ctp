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
			<span class="caption-subject font-blue-steel uppercase">Sales Order</span>
			<?php if($pull_request=="true"){ ?>
			: Select a Sales-Order to convert into Invoice
			<?php }elseif($gst=="true"){ ?>
			: Select a Sales-Order to convert into GST Invoice
			<?php }  elseif($copy_request=="copy"){?>
			: Select a Sales-Order to Copy <?php }
			  elseif($gst_copy_request=="copy"){ ?>
			: Select a Sales-Order to Copy
			<?php }  elseif($job_card=="true"){?>
			: Select a Sales-Order to Create Job Card
			<?php } ?>
		</div>
		<div class="actions">
			
			<div class="btn-group">
			<?php
			if($status==null or $status=='Pending'){ $class1='btn btn-primary'; }else{ $class1='btn btn-default'; }
			if($status=='Converted Into Invoice'){ $class2='btn btn-primary'; }else{ $class2='btn btn-default'; }
			?>
						<?php if($Actionstatus=="IndexPage"){ ?>
				<?= $this->Html->link(
					'Pending',
					'/Sales-Orders/index/Pending',
					['class' => $class1]
				); ?>
				<?= $this->Html->link(
					'Converted in Invoice',
					'/Sales-Orders/index/Converted Into Invoice',
					['class' => $class2]
				); ?>
				<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/SalesOrders/Export-Excel/'.$url_excel.'',['class' =>'btn  green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
			<?php }?>
			</div>
		</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
				<form method="GET" >
				<input type="hidden" name="pull-request" value="<?php echo @$pull_request; ?>">
				<input type="hidden" name="gst" value="<?php echo @$gst; ?>">
				<input type="hidden" name="gst-copy-request" value="<?php echo @$gst_copy_request; ?>">
				<input type="hidden" name="copy-request" value="<?php echo @$copy_request; ?>">
				<input type="hidden" name="job-card" value="<?php echo @$job_card; ?>">
				<input type="hidden" name="Actionstatus" value="<?php echo @$Actionstatus; ?>">
				<table class="table table-condensed">
					<tbody>
						<tr>
							<td width="17%">
								<div class="input-group" id="pnf_text">
									<span class="input-group-addon">SO-</span><input type="text" name="sales_order_no" class="form-control input-sm" placeholder="Sales Order No" value="<?php echo @$sales_order_no; ?>">
								</div>
							</td>
							<td width="12%">
								<input type="text" name="file" class="form-control input-sm" placeholder="File" value="<?php echo @$file; ?>">
							</td>
							<td width="13%">
								<input type="text" name="customer" class="form-control input-sm" placeholder="Customer" value="<?php echo @$customer; ?>">
							</td>
							<td width="15%">
								<input type="text" name="po_no" class="form-control input-sm" placeholder="PO No." value="<?php echo @$po_no; ?>">
							</td>
							<td width="11%">
								<?php echo $this->Form->input('salesman_name', ['empty'=>'--SalesMans--','options' => $SalesMans,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'SalesMan Name','value'=> h(@$salesman_name) ]); ?>
							</td>
							<td width="11%">
								<?php echo $this->Form->input('items', ['empty'=>'--Items--','options' => $Items,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'Category','value'=> h(@$items) ]); ?>
							</td>
							<td width="9%">
								<input type="text" name="From" class="form-control input-sm date-picker" placeholder="From" value="<?php echo @$From; ?>" data-date-format="dd-mm-yyyy" >
							</td>
							<td width="9%">
								<input type="text" name="To" class="form-control input-sm date-picker" placeholder="To" value="<?php echo @$To; ?>" data-date-format="dd-mm-yyyy" >
							</td>
							
							<td>
								<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
							</td>
						</tr>
					</tbody>
				</table>
				</form>
				<?php $page_no=0; ?>
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th width="5%" >S. No.</th>
							<th width="15%" >Sales Order No</th>
							<th width="15%" >Quotation No</th>
							<th width="15%">Customer</th>
							<th width="10%">PO No.</th>
							<th width="10%">Items Name</th>
							<th width="10%">Created Date</th>
							<th width="10%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						//pr($salesOrders);exit;
						foreach ($salesOrders as $salesOrder):
						
							$TotalSalesOrderQuantity=0;
							$item_ids=[];
							foreach($salesOrder->sales_order_rows as $sales_order_row){ 
								$TotalSalesOrderQuantity+=$sales_order_row->quantity;
								$item_ids[$sales_order_row->item_id]=$sales_order_row->item_id;
							}
							
						?>
						
						<tr <?php if($status=='Converted Into Invoice'){  echo 'style="background-color:#f4f4f4"';   
							if(@$total_sales[@$salesOrder->id] == @$total_qty[@$salesOrder->id]){ 
						?> > 
							<td><?= h(++$page_no) ?></td>
							<td><?= h(($salesOrder->so1.'/SO-'.str_pad($salesOrder->so2, 3, '0', STR_PAD_LEFT).'/'.$salesOrder->so3.'/'.$salesOrder->so4)) ?></td>
							<?php if($salesOrder->quotation_id != 0){ ?>
							<td>
							<?php echo $this->Html->link( $salesOrder->quotation->qt1.'/QT-'.str_pad($salesOrder->quotation->qt2, 3, '0', STR_PAD_LEFT).'/'.$salesOrder->quotation->qt3.'/'.$salesOrder->quotation->qt4,[
							'controller'=>'Quotations','action' => 'confirm', $salesOrder->quotation->id],array('target'=>'_blank')); ?>
							</td><?php }else{ ?><td>-</td><?php } ?>
							<td><?php echo $salesOrder->customer->customer_name.'('.$salesOrder->customer->alias.')' ?></td>
							<td><?= h($salesOrder->customer_po_no); ?></td>
							<td>
								<div class="btn-group">
									<button id="btnGroupVerticalDrop5" type="button" class="btn  btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Items <i class="fa fa-angle-down"></i></button>
										<ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop5">
										<?php  foreach($salesOrder->sales_order_rows as $sales_order_rows){ 
											if($sales_order_rows->sales_order_id == $salesOrder->id){?>
											<li><p><?= h($sales_order_rows->item->name) ?></p></li>
											<?php }}?>
										</ul>
								</div>
							</td>
							<td><?php echo date("d-m-Y",strtotime($salesOrder->created_on)); ?></td>
							
							<td class="actions">
							<?php if(in_array($salesOrder->created_by,$allowed_emp)){ ?>
									<?php if(in_array(22,$allowed_pages)){
										if($salesOrder->gst=="no")
										{
										echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'confirm', $salesOrder->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View as PDF')); 
										}
										else{
											echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'gstConfirm', $salesOrder->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View as PDF')); 
										}
									}} ?>
								
							</td>
						</tr>
							<?php  }}else if($status==null || $status=='Pending'){ //pr($salesOrders->toArray());  ?>
								<tr>    
							<?php if(@$total_sales[@$salesOrder->id] > @$total_qty[@$salesOrder->id]){ ?> 
							<td><?= h(++$page_no) ?></td>
							<td><?= h(($salesOrder->so1.'/SO-'.str_pad($salesOrder->so2, 3, '0', STR_PAD_LEFT).'/'.$salesOrder->so3.'/'.$salesOrder->so4)) ?></td>
							<?php if($salesOrder->quotation_id != 0){ ?>
							<td>
							<?php if(in_array($salesOrder->quotation->created_by,$allowed_emp)){  ?>
							<?php echo $this->Html->link( $salesOrder->quotation->qt1.'/QT-'.str_pad($salesOrder->quotation->qt2, 3, '0', STR_PAD_LEFT).'/'.$salesOrder->quotation->qt3.'/'.$salesOrder->quotation->qt4,[
							'controller'=>'Quotations','action' => 'confirm', $salesOrder->quotation->id],array('target'=>'_blank')); ?>
							<?php } ?>
							</td><?php }else{ ?><td>-</td><?php } ?>
							<td><?php echo $salesOrder->customer->customer_name.'('.$salesOrder->customer->alias.')' ?></td>
							<td><?= h($salesOrder->customer_po_no); ?></td>
							<td>
								<div class="btn-group">
									<button id="btnGroupVerticalDrop5" type="button" class="btn  btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Items <i class="fa fa-angle-down"></i></button>
										<ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop5">
										<?php  foreach($salesOrder->sales_order_rows as $sales_order_rows){ 
											if($sales_order_rows->sales_order_id == $salesOrder->id){?>
											<li><p><?= h($sales_order_rows->item->name) ?></p></li>
											<?php }}?>
										</ul>
								</div>
							</td>
							<td><?php echo date("d-m-Y",strtotime($salesOrder->created_on)); ?></td>
							
						<td class="actions">
							<?php if(in_array(22,$allowed_pages)){ ?>
								<?php if($Actionstatus=='GstInvoice' ){ 
									echo $this->Html->link('<i class="fa fa-repeat"></i>  Convert Into Invoice','/Invoices/gstAdd?sales-order='.$salesOrder->id,array('escape'=>false,'class'=>'btn btn-xs default blue-stripe'));
								}elseif($Actionstatus=='NonGstInvoice'){ 
									echo $this->Html->link('<i class="fa fa-repeat"></i>  Convert Into Invoice','/Invoices/Add?sales-order='.$salesOrder->id,array('escape'=>false,'class'=>'btn btn-xs default blue-stripe'));
								}elseif($Actionstatus=='NonGstCopy'){ 
									echo $this->Html->link('<i class="fa fa-repeat "></i>  Copy','/SalesOrders/Add?copy='.$salesOrder->id,array('escape'=>false,'class'=>'btn btn-xs default blue-stripe'));
								}elseif($Actionstatus=='GstCopy'){ 
									echo $this->Html->link('<i class="fa fa-repeat "></i>  Copy','/SalesOrders/gstSalesOrderAdd?copy='.$salesOrder->id,array('escape'=>false,'class'=>'btn btn-xs default blue-stripe'));
								}else{
								 if(in_array($salesOrder->created_by,$allowed_emp)){ 
									if(in_array(4,$allowed_pages)){
										if($salesOrder->gst=="no")
										{
										echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $salesOrder->id],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit')); 
										}
										else{
											echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'gstSalesOrderEdit', $salesOrder->id],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit')); 
										}
									}
									
									if(in_array(22,$allowed_pages)){
										if($salesOrder->gst=="no")
										{
										echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'confirm', $salesOrder->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View as PDF')); 
										}
										else{
											echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'gstConfirm', $salesOrder->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View as PDF')); 
										}
									}
								} }
								
								?>
								
							<?php	} ?>
								
								
								
							</td>
						</tr>
							<?php }}else if(($pull_request=="true") || ($gst=="true")){   ?>
								<tr>    
							<?php 
						//	pr(@$total_sales[@$salesOrder->id]);
						//	pr(@$total_qty[@$salesOrder->id]); exit;
							if(@$total_sales[@$salesOrder->id] > @$total_qty[@$salesOrder->id]){?>
								<td><?= h(++$page_no) ?></td>
							<td><?= h(($salesOrder->so1.'/SO-'.str_pad($salesOrder->so2, 3, '0', STR_PAD_LEFT).'/'.$salesOrder->so3.'/'.$salesOrder->so4)) ?></td>
							<?php if($salesOrder->quotation_id != 0){ ?>
							<td>
							<?php echo $this->Html->link( $salesOrder->quotation->qt1.'/QT-'.str_pad($salesOrder->quotation->qt2, 3, '0', STR_PAD_LEFT).'/'.$salesOrder->quotation->qt3.'/'.$salesOrder->quotation->qt4,[
							'controller'=>'Quotations','action' => 'confirm', $salesOrder->quotation->id],array('target'=>'_blank')); ?>
							</td><?php }else{ ?><td>-</td><?php } ?>
							<td><?php echo $salesOrder->customer->customer_name.'('.$salesOrder->customer->alias.')' ?></td>
							<td><?= h($salesOrder->customer_po_no); ?></td>
							<td>
								<div class="btn-group">
									<button id="btnGroupVerticalDrop5" type="button" class="btn  btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Items <i class="fa fa-angle-down"></i></button>
										<ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop5">
										<?php  foreach($salesOrder->sales_order_rows as $sales_order_rows){ 
											if($sales_order_rows->sales_order_id == $salesOrder->id){?>
											<li><p><?= h($sales_order_rows->item->name) ?></p></li>
											<?php }}?>
										</ul>
								</div>
							</td>
							<td><?php echo date("d-m-Y",strtotime($salesOrder->created_on)); ?></td>
							
							<td class="actions">
							<?php if(in_array(22,$allowed_pages)){ ?>
								<?php if($Actionstatus=='GstInvoice' ){ 
									echo $this->Html->link('<i class="fa fa-repeat"></i>  Convert Into Invoice','/Invoices/gstAdd?sales-order='.$salesOrder->id,array('escape'=>false,'class'=>'btn btn-xs default blue-stripe'));
								}elseif($Actionstatus=='NonGstInvoice'){ 
									echo $this->Html->link('<i class="fa fa-repeat"></i>  Convert Into Invoice','/Invoices/Add?sales-order='.$salesOrder->id,array('escape'=>false,'class'=>'btn btn-xs default blue-stripe'));
								}elseif($Actionstatus=='NonGstCopy'){ 
									echo $this->Html->link('<i class="fa fa-repeat "></i>  Copy','/SalesOrders/Add?copy='.$salesOrder->id,array('escape'=>false,'class'=>'btn btn-xs default blue-stripe'));
								}elseif($Actionstatus=='GstCopy'){ 
									echo $this->Html->link('<i class="fa fa-repeat "></i>  Copy','/SalesOrders/gstSalesOrderAdd?copy='.$salesOrder->id,array('escape'=>false,'class'=>'btn btn-xs default blue-stripe'));
								}else{
									
									if(in_array(4,$allowed_pages)){
										if($salesOrder->gst=="no")
										{
										echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $salesOrder->id],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit')); 
										}
										else{
											echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'gstSalesOrderEdit', $salesOrder->id],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit')); 
										}
									}
									
									if(in_array(22,$allowed_pages)){
										if($salesOrder->gst=="no")
										{
										echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'confirm', $salesOrder->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View as PDF')); 
										}
										else{
											echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'gstConfirm', $salesOrder->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View as PDF')); 
										}
									}
								} 
								
								?>
								
							<?php	} ?>
								
								
								
							</td>
						</tr>
							
							
							<?php } ?> 
								
						<?php } 
						endforeach; ?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
</div>

