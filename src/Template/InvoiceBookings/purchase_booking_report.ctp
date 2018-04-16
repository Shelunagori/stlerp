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
								<td width="18%">
									<div class="input-group" style="" id="pnf_text">
									<span class="input-group-addon">IB-No</span><input type="text" name="book_no" class="form-control input-sm" placeholder="Invoice Booking No" value="<?php echo @$book_no; ?>">
									</div>
								</td>
								<td width="9%">
									<input type="text" name="file" class="form-control input-sm" placeholder="IB File" value="<?php echo @$file; ?>">
								</td>
								<td width="13%">
									<div class="input-group" style="" id="pnf_text">
									<span class="input-group-addon">IN</span><input type="text" name="in_no" class="form-control input-sm" placeholder="Invoice No" value="<?php echo @$in_no; ?>">
									</div>
								</td>
								
								<td width="20%">
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
										echo $this->Form->input('vendor_id', ['empty' => "--Select Vendor--",'label' => false,'options' => $options,'class' => 'form-control input-sm select2me','value' => @$vendor->id]); 

												
									?>
								</td>
								<td width="20%"><?php
								echo $this->Form->input('items', ['empty' => "--Select Item--",'label' => false,'options' => $Items,'class' => 'form-control input-sm select2me','value' => @$Items->id]); 
								?>
								<td width="10%">
									<input type="text" name="From" class="form-control input-sm date-picker" placeholder="Date From" value="<?php echo @$From; ?>" data-date-format="dd-mm-yyyy" >
								</td>
								<td width="10%">
									<input type="text" name="To" class="form-control input-sm date-picker" placeholder="Date To" value="<?php echo @$To; ?>" data-date-format="dd-mm-yyyy" >
								</td>
								<td><button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button></td>
						</tr>
					</tbody>
				</table>
			</form>
				<table class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th width="5%">Sr. No.</th>
							<th width="10%">Invoice Booked On</th>
							<th width="15%">Invoice Booking No.</th>
							<th width="10%">Invoice No.</th>
							<th width="10%">Supplier Name</th>
							<th width="10%">Amount</th>
						</tr>
					</thead>
					<tbody>
					<?php $i=0;foreach ($invoiceBookings as $invoiceBooking):
					?>
						<tr>
							<td><?= h(++$i) ?></td>
							<td><?php echo date("d-m-Y",strtotime($invoiceBooking->created_on)) ?></td>
							<td><?php if(in_array($invoiceBooking->created_by,$allowed_emp)){ ?>
							<?php echo $this->Html->link( $invoiceBooking->ib1.'/IB-'.str_pad($invoiceBooking->ib2, 3, '0', STR_PAD_LEFT).'/'.$invoiceBooking->ib3.'/'.$invoiceBooking->ib4, [
							'controller'=>'InvoiceBookings','action' => 'view', $invoiceBooking->id],array('target'=>'_blank')); ?>
							<?php  } ?></td>
							<td><?= h($invoiceBooking->invoice_no) ?></td>
							<td><?= h($invoiceBooking->vendor->company_name) ?></td>
							<td><?= h($invoiceBooking->taxable_value) ?></td>
						</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				
				
			</div>
		</div>
	</div>
</div>

<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

