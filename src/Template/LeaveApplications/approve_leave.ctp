<style>
.disabledbutton {
    pointer-events: none;
    //opacity: 0.4;
}
.table > tbody > tr > td {
	border:none !important;
}
</style>

<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-purple-intense ">Approve Leave Application</span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
				<table width="70%" class="table" >
					<tr>
						<td width="15%"><b>Employee: </b></td>
						<td><?php echo $LeaveApplication->employee->name; ?></td>
						<td width="15%"><b>No. of leaves: </b></td>
						<td><?php echo $LeaveApplication->day_no; ?>
						<input type="hidden" class="day_nos" value="<?php echo $LeaveApplication->day_no; ?>">
						</td>
						<td width="15%"><b>Leave Type: </b></td>
						<td><?php 
							if($per_month_leave < $no_of_day_approval){
								echo 'Casual Leave';
							}else{
								echo $LeaveApplication->leave_type->leave_name;
							}
							
							
							
						?></td>
					</tr>
					<tr>
						<td width="15%"><b>Leave Dates: </b></td>
						<td>
							From <?php echo $LeaveApplication->from_leave_date->format('d-m-Y'); ?> 
							<?php if($LeaveApplication->from_full_half!='Full Day'){ echo '('.$LeaveApplication->from_full_half.')'; } ?> 
							To <?php echo $LeaveApplication->to_leave_date->format('d-m-Y'); ?>
							<?php if($LeaveApplication->to_full_half!='Full Day'){ echo '('.$LeaveApplication->to_full_half.')'; } ?>
						</td>
						<td width="15%"><b>Reason for leave: </b></td>
						<td><?php echo $LeaveApplication->leave_reason; ?></td>
						<?php if(!empty($LeaveApplication->supporting_attached)){ ?>
							<td><b>Attachment :<b></td>
							<td><a href="<?= $this->Url->Build($LeaveApplication->supporting_attached)?>" target="_blank">View Attachment</a></td>
						<?php } ?>
						
					</tr>
					<tr>
						
						<td  colspan="3"><div id="qwerty"></div></td>
						
					</tr>
				</table>
			
			</div>
		</div>
		<hr/>
		
		<form method="post" id="form_sample_3">
			<?php
				if($per_month_leave < $no_of_day_approval){
					echo $this->Form->input('leave_type_id', ['type'=>'hidden','label' => false,'value'=>'1']);
				}else{
					echo $this->Form->input('leave_type_id', ['type'=>'hidden','label' => false,'value'=>$LeaveApplication->leave_type_id]);
				}

			?>
			<div class="row">
				<div class="col-md-5"></div>
				<div class="col-md-6">
					<?php 
					if($LeaveApplication->single_multiple == "Single"){
						echo $this->Form->radio(
						'approve_single_multiple',
						[
							['value' => 'Single', 'text' => 'Single Day'],
							
						],['value'=>$LeaveApplication->single_multiple]
					);
					}else if($LeaveApplication->single_multiple == "Multiple"){
						echo $this->Form->radio(
						'approve_single_multiple',
						[
							['value' => 'Multiple', 'text' => 'Multiple Days']
						],['value'=>$LeaveApplication->single_multiple]
					);
					}else{
						echo $this->Form->radio(
						'approve_single_multiple',
						[
							['value' => 'Single', 'text' => 'Single Day'],
							['value' => 'Multiple', 'text' => 'Multiple Days']
						],['value'=>$LeaveApplication->single_multiple]
					);
					}
					 ?>
				</div>
			</div>
			<div class="row">
				<div id="date_from">
					<div class="col-md-3">
						<div class="form-group" >
							<label class="control-label  label-css">Date of Leave Required (From)</label>   
							<?php 
							
							echo $this->Form->input('approve_leave_from', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker disabledbutton','data-date-format'=>'dd-mm-yyyy','value'=>$LeaveApplication->from_leave_date->format('d-m-Y')]); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group" id="from_half">
							<label class="control-label  label-css">&nbsp;</label>  
							<?php
							
							$options[]=['text' =>'Full Day', 'value' => 'Full Day'];
							$options[]=['text' =>'First Half Day', 'value' => 'First Half Day'];
							$options[]=['text' =>'Second Half Day', 'value' => 'Second Half Day'];
							echo $this->Form->input('approve_full_half_from', ['label' => false,'options' => $options,'class' => 'form-control input-sm disabledbutton','value' => $LeaveApplication->from_full_half]); ?>
						</div>
					</div>
				</div>	
				<div id="date_to">
					<div class="col-md-3">
						<div class="form-group" >
							<label class="control-label  label-css">Date of Leave Required (To)</label>   
							<?php 
							
							echo $this->Form->input('approve_leave_to', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker disabledbutton','data-date-format'=>'dd-mm-yyyy','value'=>$LeaveApplication->to_leave_date->format('d-m-Y')]); ?>
						</div>
					</div>
				   <div class="col-md-3">
						<div class="form-group" id="to_half">
							<label class="control-label  label-css">&nbsp;</label>  
							<?php 
							
							echo $this->Form->input('approve_full_half_to', ['label' => false,'options' => $options,'class' => 'form-control input-sm disabledbutton','value' => $LeaveApplication->to_full_half]); ?>
						</div>
					</div>
				</div>
			</div>
			
			<table width="100%" >
				<tr>
					<td align="center">
						<table class="table">
							<tr>
								<td width="50%" class="radioforCL">
									<div class="radio-list">
										<div class="radio-inline" data-error-container="#leave_types_required_error">
										<?php echo $this->Form->radio(
											'leave_types1',
											[
												['value' => 'prior_approval', 'text' => 'Prior Approval','checked'],
												['value' => 'without_prior_approval', 'text' => 'Without Prior Approval'],
												['value' => 'unintimated_leave', 'text' => 'Unintimated Leaves']
											]
										); ?>
										</div>
										<div id="leave_types_required_error"></div>
									</div>
								</td>
								<td width="50%" class="radioforSL" style="display:none;">
									<div class="radio-list">
										<div class="radio-inline" data-error-container="#leave_types_required_error">
										<?php echo $this->Form->radio(
											'leave_types',
											[
												['value' => 'intimated_leave', 'text' => 'Intimated Leave','checked'],
												['value' => 'unintimated_leaves', 'text' => 'Unintimated Leaves']
											]
										); ?>
										</div>
										<div id="leave_types_required_error"></div>
									</div>
								</td>
								
								<td class="hideprior">
									<label class="control-label  label-css">Prior Approval </label>   
									<?php echo $this->Form->input('prior_approval', ['type'=>'text','label' => false,'class'=>'form-control input-sm prior_approval','value'=>@$LeaveApplication->prior_approval]); ?>
								</td>
								<td class="hidewithoutprior">
									<label class="control-label  label-css">Without Prior</label>   
									<?php echo $this->Form->input('without_prior_approval', ['type'=>'text','label' => false,'class'=>'form-control input-sm without_prior_approval','value'=>@$LeaveApplication->without_prior_approval,'readonly']); ?>
								</td>
								<td style="display:none;" class="addclass">
									<label class="control-label  label-css">Intimated Leaves </label>   
									<?php echo $this->Form->input('intimated_leave', ['type'=>'text','label' => false,'class'=>'form-control input-sm intimated_leave','value'=>@$LeaveApplication->intimated_leave,'readonly']); ?>
								</td>
								<td>
									<label class="control-label  label-css">Unintimated Leaves </label>  
									<?php echo $this->Form->input('unintimated_leave', ['type'=>'text','label' => false,'class'=>'form-control input-sm unintimated_leave','value'=>@$LeaveApplication->unintimated_leave,'readonly']); ?>									
								</td>
								<td></td>
							</tr>
						</table>
						<table class="table">		
							<tr>
								<td>
									<label class="control-label  label-css">Paid Leaves </label>   
									<?php echo $this->Form->input('paid_leaves', ['type'=>'text','label' => false,'class'=>'form-control input-sm paid_leaves','readonly']); ?>
								</td>
								<td>
									<label class="control-label  label-css">Unpaid Leaves </label>   
									<?php echo $this->Form->input('unpaid_leaves', ['type'=>'text','label' => false,'class'=>'form-control input-sm unpaid_leaves','readonly']); ?>
								</td>
								<td>
									<label class="control-label  label-css">Total Approved Leaves </label>   
									<?php echo $this->Form->input('total_approved_leaves', ['type'=>'text','label' => false,'class'=>'form-control input-sm total_approved_leaves','readonly']); ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			
			
			<?php if(@$LeaveApplication->leave_status == "Pending"){ ?>
				<button type="submit" id="btnsubmit" class="btn blue sub">Approve</button>
			<?php }else{ ?>
				<a href="<?php echo $this->Url->build(['controller'=>'LeaveApplications', 'action' => 'cancle', $LeaveApplication->id]); ?>"><button type="button" class="btn btn-danger">Cancel</button></a>
				
			<?php } ?>
			
		</form>
	</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function(){
	
var day_nos = $('.day_nos').val();	 
$('input[name="without_prior_approval"]').val(0);
$('input[name="prior_approval"]').val(0);
	
	/* $('input[name=without_prior_approval]').live("keyup",function(){
		countLeaves();
	});
	$('input[name=prior_approval]').live("keyup",function(){
		countLeaves();
	});
	$('input[name=unintimated_leave]').live("keyup",function(){
		countLeaves();
	});
	$('input[name=approve_leave_to]').live("blur",function(){
		countLeaves();
	}); */
	
	
	var leave_type = "<?php if($per_month_leave < $no_of_day_approval){ echo "Casual Leave"; }else{ echo $LeaveApplication->leave_type->leave_name; } ?>";
	if(leave_type == 'Casual Leave'){ 
		$('.addclass').hide();
		$('.radioforSL').hide();
		$('.hidewithoutprior').show();
		$('.hideprior').show();
		$('.radioforCL').show();
	}else if(leave_type == 'Sick Leave'){ 
		$('.addclass').show();
		$('.hidewithoutprior').hide();
		$('.hideprior').hide();
		$('.radioforSL').show();
		$('.radioforCL').hide();
	}
	
	$('input[name=leave_types]').live("change",function(){ 
		countLeaves();
	});
	 $('input[name=leave_types1]').live("click",function(){
		var  leave_types = $(this).val();
		if(leave_types == "prior_approval"){
			
			$('.paid_leaves').removeAttr('readonly');
			$('.unpaid_leaves').removeAttr('readonly');
			$('.total_approved_leaves').removeAttr('readonly');
			
		}else if(leave_types == "without_prior_approval"){
			
			$('.paid_leaves').removeAttr('readonly');
			$('.unpaid_leaves').removeAttr('readonly');
			$('.total_approved_leaves').removeAttr('readonly');
			
		}else if(leave_types == "unintimated_leave"){
			
			$('.paid_leaves').removeAttr('readonly');
			$('.unpaid_leaves').removeAttr('readonly');
			$('.total_approved_leaves').removeAttr('readonly');

		}
	}); 
	
	$('input[name=leave_types1]').live("change",function(){ 
		countLeaves();
	});
	
	/* $('input[name=leave_types1]').live("click",function(){
		var  leave_types = $(this).val();
		
	}); */
	
	$('input[name=approve_single_multiple]').live("click",function(){
		var single_multiple=$(this).val();
		expandHalfDay(single_multiple);
		if(single_multiple=="Single"){
			var q=$('input[name=approve_leave_from]').val();
			$('input[name=approve_leave_to]').val(q);
		}
		countLeaves();
	});
	
	var single_multiple=$('input[name=approve_single_multiple]:checked').val();
	expandHalfDay(single_multiple);
	
	function expandHalfDay(single_multiple){
		if(single_multiple=="Single"){
			$('#date_to').hide();
			
			$('#from_half').find('select option[value="First Half Day"]').removeAttr('disabled','disabled');
			//$('#from_half').find('select option[value="Full Day"]').attr('selected','selected');
		}else{
			$('#date_to').show();
			
			var q=$('#from_half').find('select option:selected').val();
			if(q=='First Half Day'){
				$('#from_half').find('select option[value="Full Day"]').attr('selected','selected');
			}
			
			$('#to_half').find('select option[value="Second Half Day"]').attr('disabled','disabled');
			//$('#to_half').find('select option[value="Full Day"]').attr('selected','selected');
			
			$('#from_half').find('select option[value="First Half Day"]').attr('disabled','disabled');
			//$('#from_half').find('select option[value="Full Day"]').attr('selected','selected');
		}
	}
	
	countLeaves(); 
	function countLeaves(){ 
		var  leave_types = $('input[name=leave_types]:checked').val();
		var day_no=0;
		var employee_id='<?php echo $LeaveApplication->employee_id; ?>';
		var leaveAppId='<?php echo $LeaveApplication->id; ?>';
		var url="<?php echo $this->Url->build(['controller'=>'LeaveApplications','action'=>'leaveInfoEmployees']); ?>";
        url=url+'/'+employee_id+'/'+leaveAppId;  
		 $.ajax({
            url: url,
            type: 'GET',
		    dataType : 'json',
		}).done(function(response) { 
			var paid_leave = JSON.parse(response.total_paid_leave);
			var unpaid_leave = JSON.parse(response.total_unpaid_leave);
			var un_initimate_leave = JSON.parse(response.total_uninitimate_leave);
			var prior_leave = JSON.parse(response.total_prior_leave);
			var without_prior_leave = JSON.parse(response.total_withoutprior_leave);
			day_no =  JSON.parse(response.day_no);
			var total_past_paid_leave =  JSON.parse(response.total_past_paid_leave);
			var total_past_unpaid_leave =  JSON.parse(response.total_past_unpaid_leave);
			var casual_leave =  JSON.parse(response.casual_leaves);
			var sick_leave =  JSON.parse(response.sick_leaves);
			var past_casual_leave =  JSON.parse(response.past_casual_leave);
			var past_sick_leave =  JSON.parse(response.past_sick_leave);
			var past_withoutprior_leave =  JSON.parse(response.total_past_withoutprior_leave);
			var past_intimated_leave =  JSON.parse(response.total_past_intimated_leave);
			var total_current_unpaid_leave =  JSON.parse(response.total_current_unpaid_leave);
			var total_current_paid_leave =  JSON.parse(response.total_current_paid_leave);
			var leave_type = "<?php echo $LeaveApplication->leave_type->leave_name; ?>";
			
			/****start code here for show past paid leave*****/
				$('div#qwerty').html('<table class="table table-bordered" border="1"><tr><td>Total Past-Paid Leave</td><td>'+total_past_paid_leave+'</td><td>Total Past-UnPaid Leave</td><td>'+total_past_unpaid_leave+'</td></tr><tr><td>Total Current-Paid Leave</td><td>'+total_current_paid_leave+'</td><td>Total Current-UnPaid Leave</td><td>'+total_current_unpaid_leave+'</td></tr><tr><td>Casual Leave</td><td>'+casual_leave+'</td><td>Sick Leave</td><td>'+sick_leave+'</td></tr></table>');
			/****ends code here for show past paid leave****/
			var intimated_leave =0; var unintimated_leave=0;
			if(leave_types == "intimated_leave"){ 
				 $('input[name="intimated_leave"]').val(day_no); 
				 intimated_leave = $('input[name="intimated_leave"]').val(); 
				 $('input[name="unintimated_leave"]').val(0);
				 unintimated_leave = $('input[name="unintimated_leave"]').val();
			}else if(leave_types == "unintimated_leaves"){ 
				$('input[name="intimated_leave"]').val(0);
				intimated_leave =  $('input[name="intimated_leave"]').val();
				$('input[name="unintimated_leave"]').val(day_no);
				unintimated_leave =$('input[name="unintimated_leave"]').val();
			}
			
			/**start sick leave calculation**/
			var SL =15; 
			if(intimated_leave > 0){
				if(leave_type == "Sick Leave"){
					if(intimated_leave > SL){	
						$('input[name="unpaid_leaves"]').val(intimated_leave);
						$('input[name="total_approved_leaves"]').val(intimated_leave);
						$('input[name="paid_leaves"]').val(0);
					}else{
						$('input[name="paid_leaves"]').val(intimated_leave);
						$('input[name="unpaid_leaves"]').val(0);
						$('input[name="total_approved_leaves"]').val(intimated_leave);
					}	
				}
			}
			
			if(unintimated_leave > 0){
				//var double_value = parseFloat(unintimated_leave)*2;
				$('input[name="paid_leaves"]').val(0);
				$('input[name="unpaid_leaves"]').val(unintimated_leave);
				$('input[name="total_approved_leaves"]').val(unintimated_leave);
			}
			/**ends sick leave calculation**/
		
			/**starts casual leave calculation**/
			
			/**ends casual leave calculation**/
		});
		
		
		
		
	}
	function countLeaves1(){ 
		var employee_id='<?php echo $LeaveApplication->employee_id; ?>';
		var leaveAppId='<?php echo $LeaveApplication->id; ?>';
		var url="<?php echo $this->Url->build(['controller'=>'LeaveApplications','action'=>'leaveInfoEmployees']); ?>";
        url=url+'/'+employee_id+'/'+leaveAppId; 
		//alert(url);
		 $.ajax({
            url: url,
            type: 'GET',
		    dataType : 'json',
		}).done(function(response) {  
			
			var paid_leave = JSON.parse(response.total_paid_leave);
			var unpaid_leave = JSON.parse(response.total_unpaid_leave);
			var un_initimate_leave = JSON.parse(response.total_uninitimate_leave);
			var prior_leave = JSON.parse(response.total_prior_leave);
			var without_prior_leave = JSON.parse(response.total_withoutprior_leave);
			var day_no =  JSON.parse(response.day_no);
			var total_past_paid_leave =  JSON.parse(response.total_past_paid_leave);
			var total_past_unpaid_leave =  JSON.parse(response.total_past_unpaid_leave);
			var casual_leave =  JSON.parse(response.casual_leaves);
			var sick_leave =  JSON.parse(response.sick_leaves);
			var past_casual_leave =  JSON.parse(response.past_casual_leave);
			var past_sick_leave =  JSON.parse(response.past_sick_leave);
			var past_withoutprior_leave =  JSON.parse(response.total_past_withoutprior_leave);
			var past_intimated_leave =  JSON.parse(response.total_past_intimated_leave);
			var leave_type = "<?php echo $LeaveApplication->leave_type->leave_name; ?>";
			
			$('input[name="paid_leaves"]').val(paid_leave);
			$('input[name="unpaid_leaves"]').val(unpaid_leave);
			
			
			var total_approve = parseFloat(paid_leave)+parseFloat(unpaid_leave);
			
			
			$('input[name="total_approved_leaves"]').val(total_approve);
			$('div#qwerty').html('<td><b> Past Paid Leave:</b><strong>'+' '+total_past_paid_leave+'</strong>&nbsp;&nbsp;<b> Past Unpaid Leave:</b><strong>'+' '+total_past_unpaid_leave+'</strong>&nbsp;&nbsp;<b>CL(Casual Leave):</b><strong>'+' '+casual_leave+'</strong>&nbsp;&nbsp;<b>SL(Sick Leave):</b><strong>'+' '+sick_leave+'');
			
			/**start Prior approval in Casual leave**/
			var CL =15;
			var ML =6;
			
			var prior_approval = $('input[name="prior_approval"]').val();
				if(prior_approval > 0){
					if(leave_type == "Casual Leave"){
						if(prior_approval > CL){
							   $('input[name="paid_leaves"]').val(0);
							   $('input[name="unpaid_leaves"]').val(prior_approval);
							   $('input[name="total_approved_leaves"]').val(prior_approval);
						}else{
							$('input[name="paid_leaves"]').val(prior_approval);
							$('input[name="unpaid_leaves"]').val(0);
							 $('input[name="total_approved_leaves"]').val(prior_approval);
						}
					}	
				}
			/**ends Prior approval in Casual leave**/
			
			/**start without Prior approval in Casual leave**/
			var without_prior_approvals = $('input[name="without_prior_approval"]').val();
			
			var WPA =5; var tot_prior =0; var WPATOT=0;
			if(without_prior_approvals > 0){
				if(past_withoutprior_leave > 0){
					WPATOT = parseFloat(past_withoutprior_leave)+parseFloat(without_prior_approvals);
					if(WPATOT < WPA){
						$('input[name="paid_leaves"]').val(without_prior_approvals);
						$('input[name="unpaid_leaves"]').val(0);
						$('input[name="total_approved_leaves"]').val(without_prior_approvals);
					}else if(WPATOT > WPA){
						$('input[name="paid_leaves"]').val(0);
						$('input[name="unpaid_leaves"]').val(without_prior_approvals);
						$('input[name="total_approved_leaves"]').val(without_prior_approvals);
					}
				}else{
					WPATOT = parseFloat(past_withoutprior_leave)+parseFloat(without_prior_approvals);
					
					if(WPATOT < WPA){ 
						$('input[name="paid_leaves"]').val(without_prior_approvals);
						$('input[name="unpaid_leaves"]').val(0);
						$('input[name="total_approved_leaves"]').val(without_prior_approvals);
					}else if(WPATOT > WPA){  
						$('input[name="paid_leaves"]').val(0);
						$('input[name="unpaid_leaves"]').val(without_prior_approvals);
						$('input[name="total_approved_leaves"]').val(without_prior_approvals);
					}
				}
			}
			
			/**ends without Prior approval in Casual leave**/
			
			/**start sick leave calculation**/
			var SL =15; 
			var intimated_leave = $('input[name="intimated_leave"]').val();
			if(intimated_leave > 0){
				if(leave_type == "Sick Leave"){
					if(intimated_leave > SL){	
						$('input[name="unpaid_leaves"]').val(intimated_leave);
						$('input[name="total_approved_leaves"]').val(intimated_leave);
						$('input[name="paid_leaves"]').val(0);
					}else{
						$('input[name="paid_leaves"]').val(intimated_leave);
						$('input[name="unpaid_leaves"]').val(0);
						$('input[name="total_approved_leaves"]').val(intimated_leave);
					}	
				}
			}
			
			var unintimated_leave = $('input[name="unintimated_leave"]').val(); 
			if(unintimated_leave > 0){
				var double_value = parseFloat(unintimated_leave)*2;
				$('input[name="unpaid_leaves"]').val(Math.abs(double_value));
				$('input[name="total_approved_leaves"]').val(Math.abs(double_value));
			}
			/**ends sick leave calculation**/
		});
	
	
	
	}
	
	var form3 = $('#form_sample_3');
	var error3 = $('.alert-danger', form3);
	var success3 = $('.alert-success', form3);
	form3.validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block help-block-error', // default input error message class
		focusInvalid: true, // do not focus the last invalid input
		rules: {
		
		},

		messages: { // custom messages for radio buttons and checkboxes
			
		},

		errorPlacement: function (error, element) { // render error placement for each input type
			if (element.parent(".input-group").size() > 0) {
				error.insertAfter(element.parent(".input-group"));
			} else if (element.attr("data-error-container")) { 
				error.appendTo(element.attr("data-error-container"));
			} else if (element.parents('.radio-list').size() > 0) { 
				error.appendTo(element.parents('.radio-list').attr("data-error-container"));
			} else if (element.parents('.radio-inline').size() > 0) { 
				error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
			} else if (element.parents('.checkbox-list').size() > 0) {
				error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
			} else if (element.parents('.checkbox-inline').size() > 0) { 
				error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
			} else {
				error.insertAfter(element); // for other inputs, just perform default behavior
			}
		},

		invalidHandler: function (event, validator) { //display error alert on form submit   
			//put_code_description();
			success3.hide();
			error3.show();
			//$("#add_submit").removeAttr("disabled");
			//Metronic.scrollTo(error3, -200);
		},

		highlight: function (element) { // hightlight error inputs
		   $(element)
				.closest('.form-group').addClass('has-error'); // set error class to the control group
		},

		unhighlight: function (element) { // revert the change done by hightlight
			$(element)
				.closest('.form-group').removeClass('has-error'); // set error class to the control group
		},

		success: function (label) {
			label
				.closest('.form-group').removeClass('has-error'); // set success class to the control group
		},
	
		submitHandler: function (form) {
			$('#btnsubmit').prop('disabled', true);
			$('#btnsubmit').text('Submitting.....');
		
			//put_code_description();
			success3.show();
			error3.hide();
			form[0].submit();
		}

	});
	
	$('input[name="unpaid_leaves"]').live("keyup",function(){
		countLeaves();
	});
	$('input[name="paid_leaves"]').live("keyup",function(){
		countLeaves();
	});
	$('input[name="intimated_leave"]').live("keyup",function(){
		countLeaves();
	});
	$('input[name="without_prior_approval"]').live("keyup",function(){
		countLeaves();
	});
	$('input[name="prior_approval"]').live("keyup",function(){
		countLeaves();
	});
	$(".sub").mouseover(function(){
		countLeaves();
	});
	
	
	
});
</script>