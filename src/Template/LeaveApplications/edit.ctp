<style>
.pad{
	padding-right: 0px;
padding-left: 0px;
}
.form-group
{
	margin-bottom: 0px;
}

fieldset {
 padding: 10px ;
 border: 1px solid #bfb7b7f7;
 margin: 12px;
}
legend{
margin-left: 20px;	
//color:#144277; 
color:#144277c9; 
font-size: 17px;
margin-bottom: 0px;
border:none;
}

.label-css{
	font-size: 10px !important;
}
</style>

<div class="portlet light bordered">
	<div class="portlet-title" >
		<div class="caption" >
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase " align="center">Leave Application</span>
		</div>
	</div>

<div class="portlet-body form">
		<?php echo $this->Form->create($leaveApplication, ['id'=>'form_sample_3','enctype'=>'multipart/form-data']); ?>
			
			<div class="form-body">
				<div class="col-md-2"></div>
				<div class="col-md-10" style="padding-bottom:10px;">
			     <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Leave Type</label>
							<?php 
							$type[]=['value'=>'sick','text'=>'sick'];
							$type[]=['value'=>'casual','text'=>'casual'];
							echo $this->Form->input('leave_type_id', ['empty'=> '---Select Leave type---','label' => false,'class'=>'form-control select2me input-sm leave_type','options'=>@$leavetypes]); ?>
							
						</div>
					</div>
					<?php  if(!empty($leaveApplication->supporting_attached)){  ?>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css attache_file" >Attachment File</label>
							<?php 
								echo $this->Form->input('supporting_attached', ['label' => false,'placeholder'=>'','class'=>'form-control attache_file','type'=>'file','value'=>$leaveApplication->supporting_attached]);
							 ?>
						</div>
					</div>
					<?php }else{
							
							}
					?>
				</div>
				<div class="col-md-12"></div>
				<div class="col-md-2"></div>
				<div class="col-md-10" style="padding-bottom:10px;">
					  <div class="col-md-4">
							<div class="form-group">
								<label class="control-label  label-css">Name</label>   
								<?php echo $this->Form->input('name', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm','value'=>$empData->name,'readonly']); ?>
							</div>
						</div>
						
					   
					   <div class="col-md-4">
							<div class="form-group">
								<label class="control-label  label-css">Designation</label>
								<?php echo $this->Form->input('designation', ['label' => false,'class'=>'form-control input-sm','value'=>$empData->designation->name,'readonly']); ?>
							</div>
						</div>
					  
				</div>
			
			<div class="col-md-2"></div>
				<div class="col-md-10" style="padding-bottom:10px;">
					<div class="col-md-4">
							<div class="form-group">
								<label class="control-label  label-css">Date of Leave Required (From)</label>   
								<?php echo $this->Form->input('from_leave_date', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','data-date-start-date' => date("d-m-Y"),'value'=>date('d-m-Y',strtotime($leaveApplication->from_leave_date))]); ?>
							</div>
						</div>
					   
					   <div class="col-md-4">
							<div class="form-group">
								<label class="control-label  label-css">Date of Leave Required (To)</label>
								<?php echo $this->Form->input('to_leave_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text','data-date-start-date' => date("d-m-Y"),'value'=>date('d-m-Y',strtotime($leaveApplication->to_leave_date))]); ?>
							</div>
						</div>
					   
				</div>
			<div class="col-md-2"></div>
				<div class="col-md-10" style="padding-bottom:10px;">
					<div class="col-md-8">
						<div class="form-group">
							<label class="control-label  label-css">Reason for leave</label>
							<?php echo $this->Form->input('leave_reason', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm','type'=>'textarea','rows'=>4]); ?>
						</div>
					</div>
				</div>
			
		</fieldset>	
		
			</div>
		</div>
			<div class="box-footer">
				<center>
					<button type="submit" class="btn btn-primary" id='submitbtn' >Save</button>
				</center>
			</div>
			</div>
			<?php echo $this->Form->end(); ?>
			</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>

$(document).ready(function() 
{
    	//--------- FORM VALIDATION
	var form3 = $('#form_sample_3');
	var error3 = $('.alert-danger', form3);
	var success3 = $('.alert-success', form3);
	form3.validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block help-block-error', // default input error message class
		focusInvalid: true, // do not focus the last invalid input
		rules: {
			name:{
				required: true,
			},
			submission_date : {
				  required: true,
			},
			from_leave_date : {
				  required: true,
			},
			to_leave_date : {
				  required: true,
			},			
			day_no:{
				required: true
			},
			leave_reason:{
				required: true,
			},
			gender:{
				required: true,
			},
			identity_mark : {
				  required: true,
			},
			caste  : {
				  required: true,
			},
			leave_type: {
				  required: true,
			}
			
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
			
			success3.show();
			error3.hide();
			form[0].submit();
		}

	});
	
	//--	 END OF VALIDATION
/* 	var leave_type = $('.leave_type').val();
		if(leave_type=='2')
		{
			$('.attache_file').show();
		}
		else
		{
			$('.attache_file').hide();
		} */
	
	$('.leave_type').live("change",function(){
		var leave_type = $(this).val();
		if(leave_type=='2')
		{
			$('.attache_file').show();
		}
		else
		{
			$('.attache_file').hide();
		}
	});
});
</script>