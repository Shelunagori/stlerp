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
			<span class="caption-subject font-blue-steel uppercase">Travel Request</span>
		</div>
	</div>

	<div class="portlet-body form">
		<?php echo $this->Form->create($travelRequest, ['id'=>'form_sample_3','enctype'=>'multipart/form-data']); ?>
				
					
	<div class="row">
			
			<div class="form-body">
			<div class="form-body">
				<div class="row">
				
			      <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Employee Name</label>   
							<?php echo $this->Form->input('employee_name', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm']); ?>
						</div>
					</div>
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Employee Designation</label>
							<div id="present_district2">
								<?php echo $this->Form->input('employee_designation', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm']); ?>
							</div>
						</div>
					</div>
			       <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Advance Amount</label>
							<?php echo $this->Form->input('advance_amt', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm','type'=>'text']); ?>
						</div>
					</div>
			</div>
			<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label class="control-label  label-css">Date of Travel (From)</label>
							<?php echo $this->Form->input('travel_from_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text']); ?>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<label class="control-label  label-css">Date of Travel (To)</label>
							<?php echo $this->Form->input('travel_to_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text']); ?>
						</div>
					</div>
			      <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Purpose</label>
							<div class="radio-list">
								<div class="radio-inline" style="padding-left: 0px;">
									<?php echo $this->Form->radio(
									'purpose',
									[
										['value' => 'Sales Visit', 'text' => 'Sales Visit','class' => 'radio-task','checked' => 'checked'],
										['value' => 'Service Call', 'text' => 'Service Call','class' => 'radio-task'],
										['value' => 'Others Specify', 'text' => 'Others Specify','class' => 'radio-task']

									]
									); ?>
								</div>
							</div>
						</div>
					</div>
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css"></label>
							<?php echo $this->Form->input('purpose_specification', ['label' => false,'placeholder'=>'Others Specify','class'=>'form-control input-sm specify_hide','type'=>'textarea','rows'=>2,'style'=>'display:none']); ?>
						</div>
					</div>
			</div>
			<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label class="control-label  label-css">Mode Of Travel</label>
							<?php 
							$mode[]=['value'=>'Taxi','text'=>'Taxi'];
							$mode[]=['value'=>'Bus','text'=>'Bus'];
							$mode[]=['value'=>'Rail','text'=>'Rail'];
							$mode[]=['value'=>'Self','text'=>'Self'];
							echo $this->Form->input('travel_mode', ['empty'=> '---Select State---','label' => false,'class'=>'form-control select2me input-sm','options'=>@$mode]); ?>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<label class="control-label  label-css">Date of Travel (From)</label>
							<?php echo $this->Form->input('travel_mode_from_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text']); ?>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label class="control-label  label-css">Date of Travel (To)</label>
							<?php echo $this->Form->input('travel_mode_to_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text']); ?>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label class="control-label  label-css">Mode Of Travel (<small>Return</small>)</label>
							<?php 
							$rmode[]=['value'=>'Taxi','text'=>'Taxi'];
							$rmode[]=['value'=>'Bus','text'=>'Bus'];
							$rmode[]=['value'=>'Rail','text'=>'Rail'];
							$rmode[]=['value'=>'Self','text'=>'Self'];
							echo $this->Form->input('return_travel_mode', ['empty'=> '---Select State---','label' => false,'class'=>'form-control select2me input-sm','options'=>@$rmode]); ?>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<label class="control-label  label-css">Date of Travel (From)</label>
							<?php echo $this->Form->input('return_mode_from_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text']); ?>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label class="control-label  label-css">Date of Travel (To)</label>
							<?php echo $this->Form->input('return_mode_to_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text']); ?>
						</div>
					</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Other Mode</label>
						<?php echo $this->Form->input('other_mode', ['label' => false,'placeholder'=>'Other Mode Pls specify','class'=>'form-control input-sm','type'=>'textarea','rows'=>2]); ?>
					</div>
				</div>        
			</div>
			</div>
			</div>
			
		</fieldset>	
		
		<fieldset style="margin-left: 6px;margin-right: 16px;">
			<legend><b>Travel Schedule Date wise only </b></legend>
			<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="100%">
				<thead>
					<tr align="center">
					   <td><label>S.n.<label></td>
					   <td><label>Date<label></td>
					   <td><label>Party Name<label></td>
					   <td><label>Destination<label></td>
					   <td><label>Person whom to meet<label></td>
					   <td><label>Reporting time<label></td>
					   <td></td>
					</tr>
				</thead>
				<tbody id='main_tbody' class="tab">
					
				</tbody>
			</table>
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
			employee_designation : {
				  required: true,
			},
			advance_amt : {
				  required: true,
			},
			travel_from_date : {
				  required: true,
			},			
			travel_to_date:{
				required: true
			},
			family_member_name:{
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
			religion: {
				  required: true,
			},
			home_state: {
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
	$('.add').live('click',function()
	{ 
		add_row();
	});
	add_row();
	function add_row()
	{ 
			var tr=$("#sample_table tbody tr.main_tr").clone();
			$("#main_table tbody#main_tbody").append(tr);
			
			rename_rows();
	}

	
	function rename_rows()
	{ 
				var i=0;
				$("#main_table tbody#main_tbody tr.main_tr").each(function(){ 
					$(this).find('td:nth-child(1)').html(i+1);
					$(this).find("td:nth-child(2) input").datepicker().attr({name:"travel_request_rows["+i+"][date]", id:"travel_request_rows-"+i+"-date"}).rules("add", "required");
					$(this).find("td:nth-child(3) input").attr({name:"travel_request_rows["+i+"][party_name]", id:"travel_request_rows-"+i+"-party_name"}).rules("add", "required");		
					$(this).find("td:nth-child(4) input").attr({name:"travel_request_rows["+i+"][destination]", id:"travel_request_rows-"+i+"-destination"}).rules("add", "required");
					$(this).find("td:nth-child(5) input").attr({name:"travel_request_rows["+i+"][meeting_person]", id:"travel_request_rows-"+i+"-meeting_person"}).rules("add", "required");
					$(this).find("td:nth-child(6) input").timepicker().attr({name:"travel_request_rows["+i+"][reporting_time]", id:"travel_request_rows-"+i+"-reporting_time"}).rules("add", "required");
					i++;
				}); 
	}
	
	$('.delete-tr').live('click',function() 
	{ 
		var rowCount = $('#main_table tbody#main_tbody tr').length; 
		if(rowCount>1)
		{
			$(this).closest('tr').remove();
			rename_rows();
		}
    });
	
	$("input[type='radio']").change(function(){
           if($(this).val()=="Others Specify")
			{ 
				$('.specify_hide').show();
			}
			else
			{
				$('.specify_hide').hide();
			}
		});
});
</script>
<table id="sample_table" style="display:none;" width="100%">
	<tbody>
		<tr class="main_tr" class="tab">
			<td style="width:3%;"></td>
			<td style="vertical-align: top !important;width:12%;" >
				<?php echo $this->Form->input('date', ['label' => false,'class' => 'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','placeholder'=>'dd/mm/yyyy']); ?>
			</td>
			<td width="18%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('party_name', ['label' => false,'class' => 'form-control input-sm count_value','id'=>'check']); ?>
			</td>
			<td width="14%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('destination', ['label' => false,'class' => 'form-control input-sm first','id'=>'check']); ?>
			</td>
			<td width="18%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('meeting_person', ['label' => false,'class' => 'form-control input-sm']); ?>	
			</td>
			<td style="width:8%;vertical-align: top !important;">
				<?php 
				echo $this->Form->input('time', ['label' => false,'class'=>'form-control input-sm timepicker timepicker-default']); ?>
			</td>							  
			<td style="width:5%;">
				<button type="button" class="add btn btn-default btn-xs"><i class="fa fa-plus"></i> </button>
				<a class="btn btn-danger delete-tr btn-xs" href="#" role="button" ><i class="fa fa-times"></i></a>
			</td>
		</tr>
	</tbody>
</table>