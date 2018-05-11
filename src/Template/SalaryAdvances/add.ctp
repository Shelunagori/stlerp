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
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Salary Advance</span>
		</div>
	</div>

<div class="portlet-body form">
		<?php echo $this->Form->create($salaryAdvance, ['id'=>'form_sample_3','enctype'=>'multipart/form-data']); ?>
				
					
	
			<div class="form-body ">
				<div class="row">
			        <div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Employee Name</label>   
							<?php if($s_employee_id==16 || $empData->department->name=="HR & Administration"){ ?>
										<?php echo $this->Form->input('employee_id', ['empty'=>'--Select--','options' =>@$Employees,'label' => false,'class' => 'form-control input-sm select2me employee_id']); ?>
								<?php }else{ ?>
									<?php echo $this->Form->input('employee_name', ['readonly','label' => false,'placeholder'=>'','class'=>'form-control input-sm','value'=>$empData->name]); ?>
									<?php echo $this->Form->input('employee_id', ['type'=>'hidden','label' => false,'placeholder'=>'','class'=>'form-control input-sm','value'=>$empData->id]); ?>
								<?php } ?>
						</div>
					</div>
			        <div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Salary</label>
							<?php if($s_employee_id==16 || $empData->department->name=="HR & Administration"){ ?>
							<?php echo $this->Form->input('amount1', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm','readonly','id'=>'salary_pm']); ?>
							<?php }else{ ?>
							<?php echo $this->Form->input('amount1', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm','readonly','id'=>'salary_pm','value'=>$empSallary]); ?>
							<?php } ?>
						</div>
					</div>
					<div class="col-md-3">
						<label class="control-label">Amount</label>
						<?php echo $this->Form->input('amount', ['label' => false,'class'=>'form-control input-sm']); ?>
					</div>
			       <div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Reason</label>
							<?php echo $this->Form->input('reason', ['label' => false,'placeholder'=>'','class'=>'form-control','type'=>'textarea','rows'=>2]); ?>
						</div>
					</div>
			</div>
			</div>
			</div>
			
		<div class="form-actions right">
			 <button type="submit" class="btn btn-primary" id='submitbtn' >Save</button>
		</div>
		
			</div>
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
			employee_name:{
				required: true,
			},
			amount : {
				  required: true,
			},
			reason : {
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
			$('#submitbtn').prop('disabled', true);
			$('#submitbtn').text('Submitting.....');
			success3.show();
			error3.hide();
			form[0].submit();
		}

	});
	$('.employee_id').live("change",function() {
		var employee=$(this).find('option:selected').val();
		var url="<?php echo $this->Url->build(['controller'=>'LoanApplications','action'=>'getsalary']); ?>";
			url=url+'/'+employee, 
			$.ajax({
				url: url,
				type: 'GET',
			}).done(function(response) {  
				$("#salary_pm").val(response);
			});
		
	});
	//--	 END OF VALIDATION
	
});
</script>