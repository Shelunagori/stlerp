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
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12" id="leaveData">
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
						<?php foreach($leavedatas as $leavedata){ ?>
							<td>Total <?php  echo $leavedata->leave_name ?></td>
						<?php  } ?>
						<?php foreach($leavedatas as $leavedata){ ?>
							<td>Pending <?php  echo $leavedata->leave_name ?></td>
						<?php  } ?>
						<tr>
					</thead>
					<tbody>
						<tr>	
							<?php foreach($leavedatas as $leavedata){ ?>
								<td><?php  echo $Totaalleave[$leavedata->id]; ?></td>
							<?php  } ?>	
							<?php foreach($leavedatas as $leavedata){ ?>
								<td><?php  echo $Totaalleave[$leavedata->id]-@$TotaalleaveTake[@$leavedata->id]; ?></td>
							<?php  } ?>	
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

<div class="portlet-body form">
		<?php echo $this->Form->create($leaveApplication, ['id'=>'form_sample_3','enctype'=>'multipart/form-data']); ?>
			<?php 	$first="01";
				$last="31";
				$start_date=$first.'-'.$financial_month_first->month;
				$end_date=$last.'-'.$financial_month_last->month;
			
			$default_date='';
			$cur_date=date('Y-m-d');
			$start_date1=date('Y-m-d',strtotime($start_date));
			$end_date1=date('Y-m-d',strtotime($end_date)); 
			if($start_date1 <= $cur_date && $end_date1 >= $cur_date){
				$default_date=date('d-m-Y');
			}else{
				$default_date=date('d-m-Y',strtotime($end_date));
			}
				
		?>
			<div class="form-body">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Name</label> 
						<?php if($empData->department->name=='HR & Administration' || $empData->designation->name=='Director'){ ?>
							<?php echo $this->Form->input('employee_id', ['empty'=>'--Select--','options' =>@$employees,'label' => false,'class' => 'form-control input-sm select2me empDropDown employeeData','value'=>$leaveApplication->employee_id]); ?>
						
						<?php } else { ?>
							<?php echo $this->Form->input('name', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm','value'=>$empData->name,'readonly']); ?>
							<?php echo $this->Form->input('employee_id', ['type'=>'hidden','label' => false,'placeholder'=>'','class'=>'form-control input-sm employeeData','value'=>$empData->id,'readonly']); ?>
						<?php } ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Leave Type</label>
						<?php 
						$type[]=['value'=>'sick','text'=>'sick'];
						$type[]=['value'=>'casual','text'=>'casual'];
						echo $this->Form->input('leave_type_id', ['empty'=> '---Select Leave type---','label' => false,'class'=>'form-control input-sm leave_type','options'=>@$leavetypes]); ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css attache_file" style="display:none;">Attachment File</label>
						<?php echo $this->Form->input('supporting_attached', ['label' => false,'placeholder'=>'','class'=>'form-control attache_file','type'=>'file','style'=>'display:none']); ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<?php echo $this->Form->radio(
						'single_multiple',
						[
							['value' => 'Single', 'text' => 'Single Day','checked'],
							['value' => 'Multiple', 'text' => 'Multiple Days']
						]
					); ?>
				</div>
				
			</div>
			<div class="row" id="date_from">
				<div class="col-md-3">
					<div class="form-group" >
						<label class="control-label  label-css">Date of Leave Required (From)</label>   
						<?php echo $this->Form->input('from_leave_date', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','data-date-start-date'=>$start_date ,'data-date-end-date' => $end_date,'id'=>'value_date1']); ?>
					</div>
				</div>
			   <div class="col-md-2">
					<div class="form-group" id="from_half">
						<label class="control-label  label-css">.</label>  
						<select name="from_full_half" class="form-control input-sm cr_dr" >
							<option value="Full Day">Full Day</option>
							<option value="First Half Day">First Half Day</option>
							<option value="Second Half Day">Second Half Day</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row" id="date_to">
				<div class="col-md-3">
					<div class="form-group" >
						<label class="control-label  label-css">Date of Leave Required (To)</label>   
						<?php echo $this->Form->input('to_leave_date', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','data-date-start-date'=>$start_date ,'data-date-end-date' => $end_date]); ?>
					</div>
				</div>
			   <div class="col-md-2">
					<div class="form-group" id="to_half">
						<label class="control-label  label-css">.</label>  
						<select name="to_full_half" class="form-control input-sm cr_dr" >
							<option value="Full Day">Full Day</option>
							<option value="First Half Day">First Half Day</option>
							<option value="Second Half Day">Second Half Day</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label  label-css">Reason for leave</label>
						<?php echo $this->Form->input('leave_reason', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm','type'=>'textarea','rows'=>4]); ?>
					</div>
				</div>
				<div class="col-md-6">
					<label class="control-label  label-css">Intimated/Uninitiated</label><br/>
					<?php echo $this->Form->radio(
						'intimated_or_not',
						[
							['value' => 'Intimated', 'text' => 'Intimated','checked'],
							['value' => 'Uninitiated', 'text' => 'Uninitiated']
						]
					); ?>
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
			employee_id:{
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
			leave_type_id: {
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
	
/* 	$('#value_date1').datepicker().on('changeDate', function (ev) {
		var valuefirstone = $(this).val();
		var employeeData = $('.employeeData').val();  
		var single_multiple = $('input[name=single_multiple]:checked').val();
		var url="<?php echo $this->Url->build(['controller'=>'LeaveApplications','action'=>'checkData']); ?>";
        url=url+'/'+employeeData+'/'+valuefirstone+'/'+single_multiple; alert(url);
        $.ajax({
            url: url,
            type: 'GET',
        }).done(function(response) {
            $('#leaveData').html(response);
        });
		alert(valuefirstone);
	}); */
	
	
	$('input[name=single_multiple]').live("click",function(){
		var single_multiple=$(this).val();
		expandHalfDay(single_multiple);
	});
	
	var single_multiple=$('input[name=single_multiple]:checked').val();
	expandHalfDay(single_multiple);
	
	function expandHalfDay(single_multiple){
		if(single_multiple=="Single"){
			$('#date_to').hide();
			
			$('#from_half').find('select option[value="First Half Day"]').removeAttr('disabled','disabled');
			$('#from_half').find('select option[value="Full Day"]').attr('selected','selected');
		}else{
			$('#date_to').show();
			
			$('#to_half').find('select option[value="Second Half Day"]').attr('disabled','disabled');
			$('#to_half').find('select option[value="Full Day"]').attr('selected','selected');
			
			$('#from_half').find('select option[value="First Half Day"]').attr('disabled','disabled');
			$('#from_half').find('select option[value="Full Day"]').attr('selected','selected');
		}
	}
	
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
	
	
	
	$('.empDropDown').live("change",function(){
		$('#leaveData').html('Loading...');
		var empId=$(this).find('option:selected').val();
		var url="<?php echo $this->Url->build(['controller'=>'LeaveApplications','action'=>'leaveData']); ?>";
        url=url+'/'+empId;
        $.ajax({
            url: url,
            type: 'GET',
        }).done(function(response) {
            $('#leaveData').html(response);
        });
	});
});
</script>