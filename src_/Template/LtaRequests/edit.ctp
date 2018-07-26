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


<div class="portlet box yellow">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>LEAVE ALLOWANCE FORM
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<?php echo $this->Form->create($ltaRequest, ['id'=>'form_sample_3','enctype'=>'multipart/form-data', 'class'=>'form-horizontal']); ?>
			<div class="form-body">
				<div class="form-group">
					<label class="col-md-3 control-label">Employee</label>
					<div class="col-md-4">
						<?php echo $this->Form->input('employee_id', ['label' => false,'options' => $employees,'class'=>'form-control input-sm']); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Data of submission</label>
					<div class="col-md-4">
						<?php echo $this->Form->input('data_of_submission', ['type'=>'text','label' => false,'class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','placeholder'=>'dd-mm-yyyy','value'=>$ltaRequest->data_of_submission->format('d-m-Y')]); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Date of leave required from</label>
					<div class="col-md-4">
						<?php echo $this->Form->input('date_of_leave_required_from', ['type'=>'text','label' => false,'class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','placeholder'=>'dd-mm-yyyy','value'=>$ltaRequest->date_of_leave_required_from->format('d-m-Y')]); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Date of leave required to</label>
					<div class="col-md-4">
						<?php echo $this->Form->input('date_of_leave_required_to', ['type'=>'text','label' => false,'class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','placeholder'=>'dd-mm-yyyy','value'=>$ltaRequest->date_of_leave_required_to->format('d-m-Y')]); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Proposed date of onward journey</label>
					<div class="col-md-4">
						<?php echo $this->Form->input('proposed_date_of_onward_journey', ['type'=>'text','label' => false,'class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','placeholder'=>'dd-mm-yyyy','value'=>$ltaRequest->proposed_date_of_onward_journey->format('d-m-Y')]); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Probable date of return journey</label>
					<div class="col-md-4">
						<?php echo $this->Form->input('probable_date_of_return_journey', ['type'=>'text','label' => false,'class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','placeholder'=>'dd-mm-yyyy','value'=>$ltaRequest->probable_date_of_return_journey->format('d-m-Y')]); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Place of visit</label>
					<div class="col-md-4">
						<?php echo $this->Form->input('place_of_visit', ['label' => false,'class'=>'form-control input-sm','placeholder'=>'Place of visit']); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Particulars of ltc availed for block year</label>
					<div class="col-md-4">
						<?php echo $this->Form->input('particulars_of_ltc_availed_for_block_year', ['label' => false,'class'=>'form-control input-sm','placeholder'=>'Particulars of ltc availed for block year']); ?>
					</div>
				</div>
				<fieldset style="margin-left: 6px;margin-right: 16px;">
					<legend><b>Particulars of family members availing the facility: </b></legend>
					<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="100%">
						<thead>
							<tr align="center">
							   <td><label>S.n.</label></td>
							   <td><label>Name</label></td>
							   <td><label>Age</label></td>
							   <td><label>Relation</label></td>
							   <td><label>Whether Dependent</label></td>
							   <td></td>
							</tr>
						</thead>
						<tbody id='main_tbody' class="tab">
							<?php foreach($ltaRequest->lta_request_members as $lta_request_member){ ?>
							<tr class="main_tr" class="tab">
								<td style="width:3%;"></td>
								<td style="vertical-align: top !important;width:12%;" >
									<?php echo $this->Form->input('name', ['label' => false,'class' => 'form-control input-sm ','placeholder'=>'Name','value'=>$lta_request_member->name]); ?>
								</td>
								<td width="18%" style="vertical-align: top !important;">
									<?php echo $this->Form->input('age', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Age','value'=>$lta_request_member->age]); ?>
								</td>
								<td width="14%" style="vertical-align: top !important;">
									<?php echo $this->Form->input('relation', ['label' => false,'class' => 'form-control input-sm first','placeholder'=>'Relation','value'=>$lta_request_member->relation]); ?>
								</td>
								<td width="18%" style="vertical-align: top !important;">
									<?php echo $this->Form->input('whether_dependent', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Whether dependent','value'=>$lta_request_member->whether_dependent]); ?>	
								</td>
								<td style="width:5%;">
									<button type="button" class="add btn btn-default btn-xs"><i class="fa fa-plus"></i> </button>
									<a class="btn btn-danger delete-tr btn-xs" href="#" role="button" ><i class="fa fa-times"></i></a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</fieldset>
			</div>
			<div class="form-actions fluid">
				<div class="row">
					<div class="col-md-offset-3 col-md-9">
						<button type="submit" class="btn green">Submit</button>
						<button type="button" class="btn default">Cancel</button>
					</div>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
		<!-- END FORM-->
	</div>
</div>
								
								

<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function(){
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
			data_of_submission : {
				  required: true,
			},
			date_of_leave_required_from : {
				  required: true,
			},
			date_of_leave_required_to : {
				  required: true,
			},			
			proposed_date_of_onward_journey:{
				required: true
			},
			probable_date_of_return_journey:{
				required: true,
			},
			place_of_visit:{
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
	
	$('.add').live('click',function(){ 
		add_row();
	});
	
	$('.delete-tr').live('click',function(){
		$(this).closest('tr').remove();
		rename_rows();
	});
	
	function add_row(){ 
		var tr=$("#sample_table tbody tr.main_tr").clone();
		$("#main_table tbody#main_tbody").append(tr);
		rename_rows();
	}
	rename_rows();
	function rename_rows(){ 
		var i=0;
		$("#main_table tbody#main_tbody tr.main_tr").each(function(){
			$(this).find('td:nth-child(1)').html(i+1); 
			$(this).find("td:nth-child(2) input").attr({name:"lta_request_members["+i+"][name]", id:"lta_request_members-"+i+"-name"}).rules("add", "required");
			$(this).find("td:nth-child(3) input").attr({name:"lta_request_members["+i+"][age]", id:"lta_request_members-"+i+"-age"}).rules("add", "required");
			$(this).find("td:nth-child(4) input").attr({name:"lta_request_members["+i+"][relation]", id:"lta_request_members-"+i+"-relation"}).rules("add", "required");
			$(this).find("td:nth-child(5) input").attr({name:"lta_request_members["+i+"][whether_dependent]", id:"lta_request_members-"+i+"-whether_dependent"}).rules("add", "required");
			i++;
		}); 
	}
});
</script>
<table id="sample_table" style="display:none;" width="100%">
	<tbody>
		<tr class="main_tr" class="tab">
			<td style="width:3%;"></td>
			<td style="vertical-align: top !important;width:12%;" >
				<?php echo $this->Form->input('name', ['label' => false,'class' => 'form-control input-sm ','placeholder'=>'Name']); ?>
			</td>
			<td width="18%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('age', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Age']); ?>
			</td>
			<td width="14%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('relation', ['label' => false,'class' => 'form-control input-sm first','placeholder'=>'Relation']); ?>
			</td>
			<td width="18%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('whether_dependent', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Whether dependent']); ?>	
			</td>
			<td style="width:5%;">
				<button type="button" class="add btn btn-default btn-xs"><i class="fa fa-plus"></i> </button>
				<a class="btn btn-danger delete-tr btn-xs" href="#" role="button" ><i class="fa fa-times"></i></a>
			</td>
		</tr>
	</tbody>
</table>

