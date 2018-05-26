<?php 	$url_excel="/?".$url; ?>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Purchase Report</span>
			
		</div>
		
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
				<form method="GET">
					<table class="table table-condensed">
						<tbody>
							<tr>
								<td width="15%">
									<div class="input-group" style="" id="pnf_text">
									<span class="input-group-addon">IB-No</span><input type="text" name="book_no" class="form-control input-sm" placeholder="Invoice Booking No" value="<?php echo @$book_no; ?>">
									</div>
								</td>
								<td width="9%">
									<input type="text" name="file" class="form-control input-sm" placeholder="IB File" value="<?php echo @$file; ?>">
								</td>
								<td width="10%">
									<div class="input-group" style="" id="pnf_text">
									<span class="input-group-addon">IN</span><input type="text" name="in_no" class="form-control input-sm" placeholder="Invoice No" value="<?php echo @$in_no; ?>">
									</div>
								</td>
								
								<td width="15%">
									<?php 
										$options=array();
										//pr($vendor); exit();
										foreach($vendor as $vendors){
											if(empty($vendors->alias)){
												$merge=$vendors->company_name;
											}else{
												$merge=$vendors->company_name.'('.$vendors->alias.')';
											}
											
											$options[]=['text' =>$merge, 'value' => $vendors->id, 'payment_terms' => $vendors->payment_terms];

										}
										echo $this->Form->input('vendor_id', ['empty' => "--Select Vendor--",'label' => false,'options' => $options,'class' => 'form-control input-sm select2me','value' => @$vendor_id]); 

												
									?>
								</td>
								<td width="10%"><?php
								echo $this->Form->input('items', ['empty' => "--Select Item--",'label' => false,'options' => $Items,'class' => 'form-control input-sm select2me','value' => @$item_id]); 
								?>
								
						</tr>
					</tbody>
				</table>
				<table class="table table-condensed">
					<tr>
						<td >
									<input type="text" name="Po_From" class="form-control input-sm date-picker" placeholder="PO Date From" value="<?php echo @$Po_From; ?>" data-date-format="dd-mm-yyyy" >
								</td>
								<td >
									<input type="text" name="Po_To" class="form-control input-sm date-picker" placeholder="PO Date To" value="<?php echo @$Po_To; ?>" data-date-format="dd-mm-yyyy" >
								</td>
								<td >
									<input type="text" name="From" class="form-control input-sm date-picker" placeholder="Date From" value="<?php echo @$From; ?>" data-date-format="dd-mm-yyyy" >
								</td>
								<td >
									<input type="text" name="To" class="form-control input-sm date-picker" placeholder="Date To" value="<?php echo @$To; ?>" data-date-format="dd-mm-yyyy" >
								</td>
								<td><button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button></td>
					</tr>
				</table>
			</form>
				<table class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th width="5%">Sr. No.</th>
							<th width="10%">Invoice Booked On</th>
							<th width="15%">Invoice Booking No.</th>
							<th width="10%">PO No.</th>
							<th width="10%">Invoice No.</th>
							<th width="10%">Supplier Name</th>
							<th width="10%" style="text-align:right;">PO Amount</th>
							<th width="10%" style="text-align:right;">Amount</th>
						</tr>
					</thead>
					<tbody>
					<?php $total=0;$totalamt=0; $i=0;foreach ($invoiceBookings as $invoiceBooking):
					$total+=$invoiceBooking->taxable_value;
					$totalamt+=$invoiceBooking->grn->purchase_order->total;
					?>
						<tr>
							<td><?= h(++$i) ?></td>
							<td><?php echo date("d-m-Y",strtotime($invoiceBooking->created_on)) ?></td>
							<td><?php if(in_array($invoiceBooking->created_by,$allowed_emp)){ ?>
							<?php 
							if($invoiceBooking->gst=='no'){
								echo $this->Html->link( $invoiceBooking->ib1.'/IB-'.str_pad($invoiceBooking->ib2, 3, '0', STR_PAD_LEFT).'/'.$invoiceBooking->ib3.'/'.$invoiceBooking->ib4, [
								'controller'=>'InvoiceBookings','action' => 'view', $invoiceBooking->id],array('target'=>'_blank')); ?>
							<?php  }else{
								echo $this->Html->link($invoiceBooking->ib1.'/IB-'.str_pad($invoiceBooking->ib2, 3, '0', STR_PAD_LEFT).'/'.$invoiceBooking->ib3.'/'.$invoiceBooking->ib4,['controller'=>'InvoiceBookings','action' => 'GstInvoiceBookingView', $invoiceBooking->id,],array('escape'=>false,'target'=>'_blank'));
							}} ?></td>
							<td><?php echo $this->Html->link($invoiceBooking->grn->purchase_order->po1.'/PO-'.str_pad($invoiceBooking->grn->purchase_order->po2, 3, '0', STR_PAD_LEFT).'/'.$invoiceBooking->grn->purchase_order->po3.'/'.$invoiceBooking->grn->purchase_order->po4,['controller'=>'PurchaseOrders','action' => 'confirm', $invoiceBooking->grn->purchase_order->id,],array('escape'=>false,'target'=>'_blank')); ?></td>
							<td><?= h($invoiceBooking->invoice_no) ?></td>
							<td><?= h($invoiceBooking->vendor->company_name) ?></td>
							<td  style="text-align:right;"><?= h($this->Number->format($invoiceBooking->grn->purchase_order->total,['places'=>2])) ?></td>
							<td	 style="text-align:right;"><?= h($this->Number->format($invoiceBooking->taxable_value,['places'=>2])) ?></td>
						</tr>
							<?php endforeach; ?>
						<tr>
							<td colspan="6" align="right"><b>Total</b></td>
							<td style="text-align:right;"><b><?= h($this->Number->format($totalamt,['places'=>2])) ?></b></td>
							<td style="text-align:right;"><b><?= h($this->Number->format($total,['places'=>2])) ?></b></td>
							
						</tr>
						</tbody>
					</table>
				
				
			</div>
		</div>
	</div>
</div>

<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

