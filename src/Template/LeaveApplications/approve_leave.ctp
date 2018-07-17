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
						<td><?php echo $LeaveApplication->day_no; ?></td>
						<td width="15%"><b>Leave Type: </b></td>
						<td><?php echo $LeaveApplication->leave_type->leave_name; ?></td>
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
						<td></td>
						<td></td>
					</tr>
					<tr>
						
						<td colspan="4"><div id="qwerty"></div></td>
						<td colspan="2"></td>
					</tr>
				</table>
			
			</div>
		</div>
		<hr/>
		
		<form method="post" id="form_sample_3">
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
								<!--<td>
									<label class="control-label  label-css">Intimated  Leaves </label>   
									
								</td>-->
								<td>
									<label class="control-label  label-css">Prior Approval </label>   
									<?php echo $this->Form->input('prior_approval', ['type'=>'text','label' => false,'class'=>'form-control input-sm prior_approval','value'=>@$LeaveApplication->prior_approval]); ?>
								</td>
								<td>
									<label class="control-label  label-css">Without Prior Approval </label>   
									<?php echo $this->Form->input('without_prior_approval', ['type'=>'text','label' => false,'class'=>'form-control input-sm without_prior_approval','value'=>@$LeaveApplication->without_prior_approval,'max'=>5]); ?>
								</td>
								
								<td>
									<label class="control-label  label-css">Unintimated Leaves </label>  
									<?php echo $this->Form->input('unintimated_leave', ['type'=>'text','label' => false,'class'=>'form-control input-sm unintimated_leave','value'=>@$LeaveApplication->unintimated_leave]); ?>									
								</td>
								<td></td>
							</tr>
							
							<tr>
								<td>
									<label class="control-label  label-css">Paid Leaves </label>   
									<?php echo $this->Form->input('paid_leaves', ['type'=>'text','label' => false,'class'=>'form-control input-sm','readonly']); ?>
								</td>
								<td>
									<label class="control-label  label-css">Unpaid Leaves </label>   
									<?php echo $this->Form->input('unpaid_leaves', ['type'=>'text','label' => false,'class'=>'form-control input-sm','readonly']); ?>
								</td>
								<td>
									<label class="control-label  label-css">Total Approved Leaves </label>   
									<?php echo $this->Form->input('total_approved_leaves', ['type'=>'text','label' => false,'class'=>'form-control input-sm','readonly']); ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			
			
			
			<button type="submit" id="btnsubmit" class="btn blue sub">Approve</button>
		</form>
	</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function(){
	
$('input[name="without_prior_approval"]').val(0);
$('input[name="prior_approval"]').val(0);
	
	$('input[name=without_prior_approval]').live("keyup",function(){
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
	});
	
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
		var employee_id='<?php echo $LeaveApplication->employee_id; ?>';
		var leaveAppId='<?php echo $LeaveApplication->id; ?>';
		var url="<?php echo $this->Url->build(['controller'=>'LeaveApplications','action'=>'leaveInfoEmployees']); ?>";
        url=url+'/'+employee_id+'/'+leaveAppId; 
		//alert(url);
		 $.ajax({
            url: url,
            type: 'GET',
        }).done(function(response) {  //alert(response);
			var res = response.split(",");
			var paid_leave = res[0];
			var unpaid_leave = res[1];
			var un_initimate_leave = res[2];
			var prior_leave = res[3];
			var without_prior_leave = res[4];
			var day_no = res[5];
			var total_past_paid_leave = res[6];
			var total_past_unpaid_leave = res[7];
			var casual_leave = res[8];
			var sick_leave = res[9];
			var leave_type = "<?php echo $LeaveApplication->leave_type->leave_name; ?>"
			
			$('div#qwerty').html('<td><b> Past Paid Leave:</b><strong>'+' '+total_past_paid_leave+'</strong>&nbsp;&nbsp;<b> Past Unpaid Leave:</b><strong>'+' '+total_past_unpaid_leave+'</strong>&nbsp;&nbsp;<b>CL(Casual Leave):</b><strong>'+' '+casual_leave+'</strong>&nbsp;&nbsp;<b>SL(Sick Leave):</b><strong>'+' '+sick_leave+'');
			
			$('input[name="paid_leaves"]').val(paid_leave);
			$('input[name="unpaid_leaves"]').val(Math.abs(unpaid_leave)); 
			//$('input[name="unintimated_leave"]').val(un_initimate_leave);
			$('input[name="total_approved_leaves"]').val(day_no);
			var tot_paid_leave = parseFloat(paid_leave)+parseFloat(total_past_paid_leave);
			
			if(total_past_paid_leave > 0){
				
				if(tot_paid_leave < casual_leave || tot_paid_leave < sick_leave){ 
					$('input[name="paid_leaves"]').val(tot_paid_leave);
				}else if(total_past_paid_leave == casual_leave || total_past_paid_leave == sick_leave){
					$('input[name="paid_leaves"]').val(total_past_paid_leave);
					var unpaid_leave_tot = parseFloat(total_past_unpaid_leave)+parseFloat(unpaid_leave);
					$('input[name="unpaid_leaves"]').val(Math.abs(unpaid_leave_tot));
				  
				}else{  
					if(leave_type == "Sick Leave"){
						var tot_unpaid = parseFloat(tot_paid_leave)-parseFloat(sick_leave);
						$('input[name="paid_leaves"]').val(sick_leave);
					}else{
						var tot_unpaid = parseFloat(tot_paid_leave)-parseFloat(casual_leave);
						$('input[name="paid_leaves"]').val(casual_leave);
					}
					
					$('input[name="unpaid_leaves"]').val(Math.abs(tot_unpaid));
				}
			}else{ 
				if(tot_paid_leave < casual_leave || tot_paid_leave < sick_leave){ 
					//alert(tot_paid_leave);
				}
			}
			
			var prior_approval = $('input[name="prior_approval"]').val();
			if(prior_approval > 0){
				if(prior_approval > casual_leave || prior_approval > sick_leave){ 
					$('input[name="unpaid_leaves"]').val(Math.abs(prior_approval));
				}else{
				   if(leave_type == "Sick Leave"){
					   var tot_prior = parseFloat(prior_approval)-parseFloat(sick_leave);
					   $('input[name="paid_leaves"]').val(sick_leave);
				   }else{
					   var tot_prior = parseFloat(prior_approval)-parseFloat(casual_leave);
					   $('input[name="paid_leaves"]').val(casual_leave);
				   }	
					
					$('input[name="unpaid_leaves"]').val(Math.abs(tot_prior));
					
				}
				
			}
			
			var without_prior_approvals = $('input[name="without_prior_approval"]').val();
			
			var WPA =5; var tot_prior =0;
			if(without_prior_approvals > 0 ){   
				if(without_prior_leave != WPA){  
					if(total_past_paid_leave > casual_leave || total_past_paid_leave > sick_leave){ 
						$('input[name="unpaid_leaves"]').val(without_prior_approvals);
					}else{  
						 if(leave_type == "Sick Leave"){
							 if(sick_leave == paid_leave){
								var tot_without_prior = parseFloat(without_prior_approvals)-parseFloat(sick_leave);

								$('input[name="paid_leaves"]').val(sick_leave);
								if(sick_leave == paid_leave){ 
								var a = parseFloat(Math.abs(unpaid_leave))+parseFloat(without_prior_approvals);
								//alert(unpaid_leave);
								$('input[name="unpaid_leaves"]').val(Math.abs(a));
								}else{
								$('input[name="unpaid_leaves"]').val(Math.abs(tot_without_prior));
								}
							 }else{
								 var a = parseFloat(paid_leave)+parseFloat(without_prior_approvals); 
								
								if(sick_leave < a){ 
									$('input[name="paid_leaves"]').val(sick_leave);
									var paid_leave =$('input[name="paid_leaves"]').val();
									if(sick_leave == paid_leave){ 
									var unpaid_leave =$('input[name="unpaid_leaves"]').val();
									var a = parseFloat(Math.abs(paid_leave))-parseFloat(without_prior_approvals);
									
									$('input[name="unpaid_leaves"]').val(Math.abs(a));
									}else{
									var a = parseFloat(Math.abs(unpaid_leave))+parseFloat(without_prior_approvals);
									$('input[name="unpaid_leaves"]').val(Math.abs(tot_without_prior));
									}
								}else{
									$('input[name="paid_leaves"]').val(a);
									$('input[name="unpaid_leaves"]').val(0);
								}
							 }
							 
							
						 }else if(leave_type == "Casual Leave"){ 
						 
							if(casual_leave == paid_leave){ 
								var tot_without_prior = parseFloat(without_prior_approvals)-parseFloat(casual_leave);

								$('input[name="paid_leaves"]').val(casual_leave);
								if(casual_leave == paid_leave){ 
								var a = parseFloat(Math.abs(unpaid_leave))+parseFloat(without_prior_approvals);
								//alert(unpaid_leave);
								$('input[name="unpaid_leaves"]').val(Math.abs(a));
								}else{
								$('input[name="unpaid_leaves"]').val(Math.abs(tot_without_prior));
								}
							}else{ 
								var a = parseFloat(paid_leave)+parseFloat(without_prior_approvals); 
								
								if(casual_leave < a){ 
									$('input[name="paid_leaves"]').val(casual_leave);
									var paid_leave =$('input[name="paid_leaves"]').val();
									if(casual_leave == paid_leave){ 
									var unpaid_leave =$('input[name="unpaid_leaves"]').val();
									var a = parseFloat(Math.abs(paid_leave))-parseFloat(without_prior_approvals);
									
									$('input[name="unpaid_leaves"]').val(Math.abs(a));
									}else{
									var a = parseFloat(Math.abs(unpaid_leave))+parseFloat(without_prior_approvals);
									$('input[name="unpaid_leaves"]').val(Math.abs(tot_without_prior));
									}
								}else{
									$('input[name="paid_leaves"]').val(a);
									$('input[name="unpaid_leaves"]').val(0);
								}
							}
							
						 }
					
						
						
					}
					
				}else{  
					$('input[name="unpaid_leaves"]').val(Math.abs(without_prior_approvals));
				}
			}	
			
			var unintimated_leave = $('input[name="unintimated_leave"]').val(); 
			
			if(unintimated_leave > 0){
				var unpaid_leave_t =$('input[name="unpaid_leaves"]').val(); 
				var double_value = parseFloat(unintimated_leave)*2;
				var abc = parseFloat(double_value)+parseFloat(unpaid_leave_t);
				$('input[name="unpaid_leaves"]').val(Math.abs(abc));
			}
			
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
	$(".sub").mouseover(function(){
		countLeaves();
	});
	
	
	
});
</script>