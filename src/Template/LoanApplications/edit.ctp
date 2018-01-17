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


<div class="col-md-12">
<?php echo $this->Form->create($loanApplication, ['id'=>'form_sample_3']); ?>
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title">Edit Loan Application</h3>
			</div>
			<div class="box-body" style="display: block;">
			<div class="row">
	
		<fieldset style="margin-left: 20px;margin-right: 30px;">	
			<legend><b>  </b></legend>
			<div class="col-md-12 pad">
				
			        <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Employee Name</label>   
							<?php echo $this->Form->input('employee_name', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm']); ?>
						</div>
					</div>
			        <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Reason For Loan</label>
							<?php echo $this->Form->input('reason_for_loan', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm']); ?>
						</div>
					</div>
			       <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Salary Pm</label>
							<?php echo $this->Form->input('salary_pm', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm']); ?>
						</div>
					</div>
			</div>
			<div class="col-md-12 pad">
				    <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Amount Of Loan</label>
							<?php echo $this->Form->input('amount_of_loan', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm','type'=>'text']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Amount Of Loan In Word</label>   
							<?php echo $this->Form->input('amount_of_loan_in_word', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm', 'type'=>'text']); ?>
						</div>
					</div>
			        <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Starting Date Of Loan</label>
							<?php 
							if(!empty($loanApplication->starting_date_of_loan))
							{
								if($loanApplication->starting_date_of_loan!='1/1/70')
								{   
									$startingDateOfLoan = date("d-m-Y",strtotime($loanApplication->starting_date_of_loan));
								}
							}
							echo $this->Form->input('starting_date_of_loan', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text','value'=>@$startingDateOfLoan]); ?>
						</div>
					</div>
			</div>
			<div class="col-md-12 pad">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Ending Date Of Loan</label>
							<?php 
							if(!empty($loanApplication->ending_date_of_loan))
							{
								if($loanApplication->ending_date_of_loan!='1/1/70')
								{   
									$endingDateOfLoan = date("d-m-Y",strtotime($loanApplication->ending_date_of_loan));
								}
							}
							echo $this->Form->input('ending_date_of_loan', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text','value'=>@$endingDateOfLoan]); ?>
							
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css attache_file" >Remark</label>
							<?php echo $this->Form->input('remark', ['label' => false,'placeholder'=>'','class'=>'form-control','type'=>'textarea','rows'=>2]); ?>
						</div>
					</div>
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
			employee_name:{
				required: true,
			},
			reason_for_loan : {
				  required: true,
			},
			salary_pm : {
				  required: true,
			},
			amount_of_loan : {
				  required: true,
			},			
			amount_of_loan_in_word:{
				required: true
			},
			starting_date_of_loan:{
				required: true,
			},
			ending_date_of_loan:{
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
	
	//--	 END OF VALIDATION
	
});
</script>