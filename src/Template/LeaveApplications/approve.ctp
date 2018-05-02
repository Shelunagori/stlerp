<div id="myModal3" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false" style="display:block; padding-right: 12px;"><div class="modal-backdrop fade in" ></div>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id="result_ajax">
			<h4>Approve Leave</h4>
				<div style=" overflow: auto; height: 450px;">
					<div class="col-md-4">
							<div class="form-group">
								<label class="control-label  label-css">Approve From (From)</label>
								<?php echo $this->Form->input('from_leave_date', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','value'=>date('d-m-Y',strtotime($LeaveApplication->from_leave_date))]); ?>
							</div>
					</div>
				
					<div class="col-md-4">
							<div class="form-group">
								<label class="control-label  label-css">Approve From (To)</label>
								<?php echo $this->Form->input('from_leave_date', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','value'=>date('d-m-Y',strtotime($LeaveApplication->to_leave_date))]); ?>
							</div>
					</div>
				
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn default closebtn2">Close</button>
				<button class="btn btn-primary insert_tc">Send Email</button>
			</div>
		</div>
	</div>
</div>

<?php echo $this->Html->script('/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>