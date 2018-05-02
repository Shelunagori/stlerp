<?php if($employee_id==23 or $employee_id==16){ ?>
<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
		<div class="dashboard-stat blue-madison">
			<div class="visual">
				<i class="fa fa-comments"></i>
			</div>
			<div class="details">
				<div class="number">
				<?= h($this->Number->format($monthelySaleForQO,[ 'places' => 2])) ?>
					 <?php //echo $monthelySaleForQO; ?>
				</div>
				<div class="desc">
					 Quotations
				</div>
			</div>
			
			<?php echo $this->Html->link('View more <i class="m-icon-swapright m-icon-white"></i>',['controller'=>'Quotations','action' => 'index'],array('escape'=>false,'target'=>'_blank','class'=>'more')); ?>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<div class="dashboard-stat purple-plum">
			<div class="visual">
				<i class="fa fa-group fa-icon-medium"></i>
			</div>
			<div class="details">
				<div class="number">
				<?= h($this->Number->format($monthelySaleForSO,[ 'places' => 2])) ?>
					<?php //echo $monthelySaleForSO; ?>
				</div>
				<div class="desc">
					 Sales Orders
				</div>
			</div>
			<?php echo $this->Html->link('View more <i class="m-icon-swapright m-icon-white"></i>',['controller'=>'SalesOrders','action' => 'index'],array('escape'=>false,'target'=>'_blank','class'=>'more')); ?>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<div class="dashboard-stat green-haze">
			<div class="visual">
				<i class="fa fa-shopping-cart"></i>
			</div>
			<div class="details">
				<div class="number">
					<?= h($this->Number->format($monthelySaleForInvoice,[ 'places' => 2])) ?>
					 <?php //echo $monthelySaleForInvoice; ?>
				</div>
				<div class="desc">
					 Sales Invoice
				</div>
			</div>
			<?php echo $this->Html->link('View more <i class="m-icon-swapright m-icon-white"></i>',['controller'=>'Invoices','action' => 'index'],array('escape'=>false,'target'=>'_blank','class'=>'more')); ?>
		</div>
	</div>

</div>
<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
		<div class="dashboard-stat green-haze">
			<div class="visual">
				<i class="fa fa-comments"></i>
			</div>
			<div class="details">
				<div class="number">
					 <?php echo $pending_po; ?>
				</div>
				<div class="desc">
					 Purchase order for GRNs
				</div>
			</div>
			<?php echo $this->Html->link('View more <i class="m-icon-swapright m-icon-white"></i>',['controller'=>'Purchase-Orders','action' => 'index'],array('escape'=>false,'target'=>'_blank','class'=>'more')); ?>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<div class="dashboard-stat red-intense">
			<div class="visual">
				<i class="fa fa-bar-chart-o"></i>
			</div>
			<div class="details">
				<div class="number">
					<?php echo $pending_grn; ?>
				</div>
				<div class="desc">
					 GRN for Invoice Bookings
				</div>
			</div>
			<?php echo $this->Html->link('View more <i class="m-icon-swapright m-icon-white"></i>',['controller'=>'Grns','action' => 'index'],array('escape'=>false,'target'=>'_blank','class'=>'more')); ?>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
		
	</div>
	<div class="row">
	<div class="col-md-12">
					<!-- BEGIN SAMPLE TABLE PORTLET-->
					<div class="portlet box blue-madison">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-docs"></i>Report
							</div>
							
						</div>
						<div class="portlet-body">
							<table class="table ">
							
							<tbody>
							<tr>
								<td>
									<?php
										$fromdate1 = date('d-m-Y',strtotime($fromdate1));
										$todate1 = date('d-m-Y',strtotime($todate1));
									?>
									<?php $today =date('d-m-Y'); echo $this->Html->link('<i class="fa "></i> Daily Report',array('controller'=>'Ledgers','action'=>'Index','from'=>@$today,'to'=>@$today),array('escape'=>false,'class'=>'btn default btn-block')); ?>
								</td>
								<td>
									<?php $today =date('d-m-Y'); echo $this->Html->link('<i class="fa "></i> Material Indent',array('controller'=>'Item-Ledgers','action'=>'material-indent-report','stockstatus'=>'Positives','company_name'=>@$st_company_id),array('escape'=>false,'class'=>'btn default btn-block')); ?>
								</td>
								<td>
									<?php $today =date('d-m-Y'); echo $this->Html->link('<i class="fa "></i> Account Statement',array('controller'=>'Ledgers','action'=>'Account-Statement'),array('escape'=>false,'class'=>'btn default btn-block')); ?>
								</td>
								<td width="25%">
									<?php $today =date('d-m-Y'); echo $this->Html->link('<i class="fa "></i> Account Statement Ref',array('controller'=>'Ledgers','action'=>'AccountStatementRefrence'),array('escape'=>false,'class'=>'btn default btn-block')); ?>
								</td>
							</tr>
							<tr>
								<td width="25%">
									<?php
										$fromdate1 = date('d-m-Y',strtotime($fromdate1));
										$todate1 = date('d-m-Y',strtotime($todate1));
									?>
									<?php $today =date('d-m-Y'); echo $this->Html->link('<i class="fa "></i> Outstandings for Customers',array('controller'=>'Customers','action'=>'Breakup-Range-Overdue-New','request'=>'customer'),array('escape'=>false,'class'=>'btn default btn-block')); ?>
								</td>
								<td width="25%">
									<?php $today =date('d-m-Y'); echo $this->Html->link('<i class="fa "></i> Outstandings for Vendors',array('controller'=>'Customers','action'=>'Breakup-Range-Overdue-New','request'=>'vendor'),array('escape'=>false,'class'=>'btn default btn-block')); ?>
								</td>
								<td width="25%">
									<?php $firstday =date('01-m-Y'); $today =date('d-m-Y'); echo $this->Html->link('<i class="fa "></i> Non-GST Sales',array('controller'=>'Invoices','action'=>'salesReport','From'=>$fromdate1,'To'=>@$todate1),array('escape'=>false,'class'=>'btn default btn-block')); ?>
								</td>
								<td width="25%">
									<?php $firstday =date('01-m-Y'); $today =date('d-m-Y'); echo $this->Html->link('<i class="fa "></i> GST Sales',array('controller'=>'Invoices','action'=>'gstSalesReport','From'=>$fromdate1,'To'=>@$todate1),array('escape'=>false,'class'=>'btn default btn-block')); ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php
										$fromdate1 = date('d-m-Y',strtotime($fromdate1));
										$todate1 = date('d-m-Y',strtotime($todate1));
									?>
									<?php $today =date('d-m-Y'); echo $this->Html->link('<i class="fa "></i> Stock Report',array('controller'=>'ItemLedgers','action'=>'stockSummery','stock'=>'Positive','to_date'=>@$todate1),array('escape'=>false,'class'=>'btn default btn-block')); ?>
								</td>
								<td>
									<?php $today =date('d-m-Y'); echo $this->Html->link('<i class="fa "></i> Trial Balance',array('controller'=>'ledgers','action'=>'Trail-Balance'),array('escape'=>false,'class'=>'btn default btn-block')); ?>
								</td>
								<td>
									<?php $today =date('d-m-Y'); echo $this->Html->link('<i class="fa "></i> Profit & Loss ',array('controller'=>'Ledgers','action'=>'ProfitLossStatement','from_date'=>$fromdate1,'to_date'=>@$todate1),array('escape'=>false,'class'=>'btn default btn-block')); ?>
								</td>
								<td width="25%">
									<?php $today =date('d-m-Y'); echo $this->Html->link('<i class="fa "></i> Balance Sheet',array('controller'=>'Ledgers','action'=>'BalanceSheet','from_date'=>$fromdate1,'to_date'=>@$todate1),array('escape'=>false,'class'=>'btn default btn-block')); ?>
								</td>
							</tr>
							
							</tbody>
							</table>
						</div>
					</div>
					<!-- END SAMPLE TABLE PORTLET-->
				</div>
	
	</div>

</div>
<!-- END DASHBOARD STATS -->
<div class="clearfix"></div>
<?php if($PendingleaveRequests){ ?>
<div class="col-md-12 col-sm-12">
	<div class="portlet grey-cascade box">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-cogs"></i>Pending Leave Request
			</div>
			<div class="actions">
				
			</div>
		</div>
		<div class="portlet-body">
			<div class="table-responsive">
				<table class="table table-hover table-bordered table-striped">
				<thead>
					<tr>
						<th>S.No</th>
						<th>Employee Name</th>
						<th>No of Days</th>
						<th>Leave Status</th>
						<th>Pending From</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=1;	foreach($PendingleaveRequests as $PendingRequest){ ?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><?php echo $PendingRequest->name; 
							 echo $this->Form->input('emp_id', ['type'=>'hidden','class'=>'emp_id','value' => @$PendingRequest->id]); ?></td>
						<td><?php echo $PendingRequest->day_no; ?></td>
						<td><span class="label label-sm label-success"><?php echo $PendingRequest->leave_status; ?></span>
						</td>
						<td><?php echo $PendingRequest->emp_data->name; ?>
						</td>
						<td><a href="#" class="approve"><i class="fa fa-thumbs-o-up"></i> Approve </a></td>
					</tr>
					<?php } ?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php }?>
<?php if($PendingTravelRequests){ ?>
<div class="col-md-12 col-sm-12">
	<div class="portlet grey-cascade box">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-cogs"></i>Pending Travel Request
			</div>
			<div class="actions">
				
			</div>
		</div>
		<div class="portlet-body">
			<div class="table-responsive">
				<table class="table table-hover table-bordered table-striped">
				<thead>
					<tr>
						<th>S.No</th>
						<th>Employee Name</th>
						<th>Status</th>
						<th>Pending From</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=1;	foreach($PendingTravelRequests as $PendingRequest){ ?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><?php echo $PendingRequest->employee->name; ?></td>
						<td><span class="label label-sm label-success"><?php echo $PendingRequest->status; ?></span>
						</td>
						<td><?php echo $PendingRequest->emp_data->name; ?>
						</td>
						<td><?= $this->Html->link(' Approve ',
								['controller'=>'TravelRequests', 'action' => 'approved', $PendingRequest->id],
								[
									'escape' => false
									
								]
							) ?>
								<?= $this->Html->link(' Cancle ',
								['controller'=>'TravelRequests', 'action' => 'cancle', $PendingRequest->id],
								[
									'escape' => false
									
								]
							) ?></td>
					</tr>
					<?php } ?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php }?>
<?php } else { ?>	

<?php if($PendingTravelRequestStatus){  ?>
<div class="col-md-12 col-sm-12">
	<div class="portlet grey-cascade box">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-cogs"></i>Travel Request Status
			</div>
			<div class="actions">
				
			</div>
		</div>
		<div class="portlet-body">
			<div class="table-responsive">
				<table class="table table-hover table-bordered table-striped">
				<thead>
					<tr>
						<th>S.No</th>
						<th>Status</th>
						<th>Pending From</th>
						
					</tr>
				</thead>
				<tbody>
					<?php $i=1;	foreach($PendingTravelRequestStatus as $PendingTravelRequest){ ?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><span class="label label-sm label-success"><?php echo $PendingTravelRequest->status; ?></span>
						</td>
						<td><?php echo $PendingTravelRequest->emp_data->name; ?>
						</td>
						
					</tr>
					<?php } ?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php }?>

<?php if($PendingleaveRequests){ ?>
<div class="col-md-12 col-sm-12">
	<div class="portlet grey-cascade box">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-cogs"></i>Pending Leave Request
			</div>
			<div class="actions">
				
			</div>
		</div>
		<div class="portlet-body">
			<div class="table-responsive">
				<table class="table table-hover table-bordered table-striped">
				<thead>
					<tr>
						<th>S.No</th>
						<th>Employee Name</th>
						<th>No of Days</th>
						<th>Leave Status</th>
						<th>Pending From</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=1;	foreach($PendingleaveRequests as $PendingRequest){ ?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><?php echo $PendingRequest->name; ?></td>
						<td><?php echo $PendingRequest->day_no; ?></td>
						<td><span class="label label-sm label-success"><?php echo $PendingRequest->leave_status; ?></span>
						</td>
						<td><?php echo $PendingRequest->emp_data->name; ?>
						</td>
						<td><span class="approve">Approve</span>
						
					</tr>
					<?php } ?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php }?>

<?php if($PendingleaveStatus){

?>
<div class="col-md-12 col-sm-12">
	<div class="portlet grey-cascade box">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-cogs"></i>Leave Request Status
			</div>
			<div class="actions">
				
			</div>
		</div>
		<div class="portlet-body">
			<div class="table-responsive">
				<table class="table table-hover table-bordered table-striped">
				<thead>
					<tr>
						<th>S.No</th>
						<th>Status</th>
						<th>Pending From</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=1;	foreach($PendingleaveStatus as $Pendingleave){ //pr($Pendingleave); ?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><span class="label label-sm label-success"><?php echo $Pendingleave->leave_status; ?></span>
						</td>
						<?php if($Pendingleave->leave_status=='Pending'){ ?>
						<td><?php echo $Pendingleave->emp_data->name; ?></td>
						<?php } else {?>
						<td><?php echo $Pendingleave->emp_data->name; ?></td>
						<?php }?>
					</tr>
					<?php } ?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php }?>
<?php }  ?>	
	
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() { 

    $('.onhover').die().live("click",function() { 
		
		var employee_id=$(this).attr('employee_id');
		var id=$(this).attr('req_id');
		open_details(employee_id,id);
	}); 
	
	function open_details(employee_id,id){
		
		$("#result_ajax").html('<div align="center"><?php echo $this->Html->image('/img/wait.gif', ['alt' => 'wait']); ?> Loading</div>');
		var url="<?php echo $this->Url->build(['controller'=>'RequestLeaves','action'=>'showDetails']); ?>";
		url=url+'/'+id+'/'+employee_id,
		
		$("#myModal12").show();
		$.ajax({
			url: url,
		}).done(function(response) {  
			$("#result_ajax").html(response);
		});
    }
	
	$('.closebtn').on("click",function() { 
		$("#myModal12").hide();
    });
	
	$('.approve').die().live("click",function() { 
		
		var emp_id=$(this).closest('tr').find('.emp_id').val(); 
		var url="<?php echo $this->Url->build(['controller'=>'LeaveApplications','action'=>'approve']); ?>";
			url=url+'/'+emp_id, 
			$.ajax({
				url: url,
				type: 'GET',
			}).done(function(response) { alert(response);
				$("#show_model").html(response);
			});
		//$("#myModal3").show();
    });

	$('.closebtn2').die().live("click",function() { 
		$("#myModal3").hide();
    });

	

});

</script>
<div id="myModal12" class="modal fade in" tabindex="-1"  style="display: none; padding-right: 12px;"><div class="modal-backdrop fade in" ></div>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id="result_ajax">
				
			</div>
			<div class="modal-footer">
				<button class="btn default closebtn">Close</button>
			</div>
		</div>
	</div>
</div>


<div id="show_model">

</div>
