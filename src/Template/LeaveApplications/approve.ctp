<?php echo $this->Html->css('/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<div id="myModal3" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false" style="display:block; padding-right: 12px;"><div class="modal-backdrop fade in" ></div>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id="result_ajax">
			<h4>Approve Leave</h4>
				<div style=" overflow: auto; height: 250px;">
					<div class="col-md-4">
							<div class="form-group">
								<label class="control-label  label-css">Approve From (From)</label>
								<?php echo $this->Form->input('approve_leave_from', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker approve_leave_from','data-date-format'=>'dd-mm-yyyy','value'=>date('d-m-Y',strtotime($LeaveApplication->from_leave_date)),'id'=>'from_leave_date']); ?>
							</div>
					</div>
				
					<div class="col-md-4">
							<div class="form-group">
								<label class="control-label  label-css">Approve From (To)</label>
								<?php echo $this->Form->input('approve_leave_to', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker approve_leave_to','data-date-format'=>'dd-mm-yyyy','value'=>date('d-m-Y',strtotime($LeaveApplication->to_leave_date))]); ?>
							</div>
					</div>
					<div class="col-md-4">
							<div class="form-group leave_type">
								<label class="control-label  label-css">Leave Type</label>
								<?php echo $this->Form->input('leave_mode', ['options'=>['Paid'=>'Paid','Unpaid'=>'Unpaid'],'label' => false,'class' => 'form-control input-sm  leave_type','style'=>'vertical-align: top !important;']); ?>
							</div>
					</div>

					<div class="col-md-12 ">
							<div class="form-group comment1">
								<label class="control-label  label-css">Comment</label>
								<?php echo $this->Form->textarea('comment', ['type'=>'text','label' => false,'class' => 'form-control input-sm comment']); ?>
							</div>
					</div>
				
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn default closebtn2">Close</button>
				<button class="btn btn-primary insert_tc">Approve</button>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<?php echo $this->Html->script('/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>
<?php echo $this->Html->script('/assets/admin/pages/scripts/components-pickers.js'); ?>

<script>
$(document).ready(function() {   
	ComponentsPickers.init();
	$('#from_leave_date').datepicker();

	$('.insert_tc').on("click",function() {
		$('.insert_tc').text('Submtting...');
		 var id ="<?php echo $id; ?>";
		 var comment = $('.comment').val();
		 var approve_leave_to = $('.approve_leave_to').val();
		 var approve_leave_from = $('.approve_leave_from').val();
		 var leave_type = $('.leave_type').find('option:selected').val();
		 //var leave_type = $(this).closest("div.leave_type").find("option:selected").val();
		
		
		var url="<?php echo $this->Url->build(['controller'=>'LeaveApplications','action'=>'approved']); ?>";
			url=url+'/'+id+'/'+approve_leave_from+'/'+approve_leave_to+'/'+leave_type+'/'+comment, 
			$.ajax({
				url: url,
				type: 'GET',
			}).done(function(response) { 
				$("#show_model").html('');
				location.reload();
			});
		//$("#myModal3").show();
    });
});
</script>