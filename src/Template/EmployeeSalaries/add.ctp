<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Employee Record</span>
		</div>
		<div class="actions">
			
		</div>
		<div class="portlet-body">
		<?php echo $this->Form->create($employeeSalary, ['id'=>'form_sample_3']); ?>
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label  label-css">Employee Name</label>
							<?php echo $this->Form->input('employee_name', ['label' => false,'placeholder'=>'First Name','class'=>'form-control input-sm','readonly','value'=>$employee->name]); ?>
							<?php echo $this->Form->input('employee_id', ['type' => 'hidden','placeholder'=>'First Name','class'=>'form-control input-sm','readonly','value'=>$employee->id]); ?>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label  label-css">Employee Designation</label>
							<?php echo $this->Form->input('designation_id', ['empty'=>'--Select--','options' =>@$employeeDesignation,'label' => false,'class' => 'form-control input-sm select2me','value'=>$employee->designation_id]); ?>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label  label-css">Effective Date From</label>
							<?php echo $this->Form->input('effective_date_from', ['type'=>'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy']); ?>
							
						</div>
					</div>

						<div class="col-md-3">
						<div class="form-group">
							<label class="control-label  label-css">Effective Date To</label>
							<?php echo $this->Form->input('effective_date_to', ['type'=>'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy']); ?>
						</div>
					</div>
				</div>
				<div class="col-md-12">
				
				<div id="form_attached">
				
				</div>
			</div>
			</div>
			<?= $this->Form->button(__('Submit'),['class'=>'btn btn-success submit']) ?>
			<?php echo $this->Form->end(); ?>
		</div>
    </div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>

$(document).ready(function() 
{

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
			success3.hide();
			error3.show();
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
			form[0].submit(); // submit the form
		}

	});


onload_row();
  function onload_row(){
	  var copy=$("#copy_form").clone(); 
	  $("#form_attached").html(copy);
	  add_first_row();
 }
  
  $('.addrow').live('click',function(){ 
		add_row();
	});
	
	function add_first_row()
	{
		var tr=$("#main_table tbody tr.main_tr").clone();
		$("#form_attached #main_table #main_tbody1").append(tr);
		rename_rows();
	}
	function add_row()
	{ 
		var tr=$("#main_table #main_tbody2 tr.main_tr").clone();
		$("#form_attached #main_table #main_tbody1").append(tr);
		rename_rows();
		calculate_total();
	}
	
	$('.deleterow').live('click',function() 
	{ 
		var rowCount = $('#main_table tbody#main_tbody1 tr').length; 
		if(rowCount>1)
		{
			$(this).closest('tr').remove();
		}
    });
	function rename_rows()
	{ 
		var i=0;
		$("#main_table tbody#main_tbody1 tr").each(function(){ 
			$(this).find("td:nth-child(1) select").select2().attr({name:"employee_salary_rows["+i+"][employee_salary_division_id]", id:"employee_salary_rows-"+i+"-employee_salary_division_id"}).rules("add", "required");
			$(this).find("td:nth-child(2) input").attr({name:"employee_salary_rows["+i+"][amount]", id:"employee_salary_rows-"+i+"-amount"}).rules("add", "required");
			i++;
		});
	}
		
	$('.amount').die().live("keyup",function() {
		calculate_total();
    });
	
	$("select.employee_salary_division").die().live("change",function(){ 
		calculate_total();
	})

	function calculate_total()
	{ 
		var total_cr=0;
		var total_dr=0;
		var total_amt=0;
		$("#main_table tbody#main_tbody1 tr").each(function(){ 
				var salary_type=$(this).find('td:nth-child(1) select option:selected').attr('salary_type');
				var amount=parseFloat($(this).find(".amount").val());
				if(isNaN(amount)){ amount=0;}
				if(salary_type=="addition"){
						total_dr=total_dr+amount;
				}else{
						total_cr=total_cr+amount;
				}
				total_amt=total_dr-total_cr;
				$(".total_amount").val(total_amt);
				
		});
	}
  
});
</script>
<div style="display:none;" >
<div class="box box-primary" id="copy_form">
		<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="80%">
			<thead>
				<tr>
					<td>Type</td>
					<td>Amount</td>
					<td>Action</td>
				</tr>
			</thead>
			<tbody id="main_tbody1">
				
			</tbody>
			<tfoot id="main_tfoot1">
				<tr id="tfoot_tr">
					<td colspan="1" align="right">Total</td>
					<td id="tfoot_td"><?php echo $this->Form->input('amount', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm total_amount']); ?></td>
					<td ></td>
				</tr>
			</tfoot>
		</table>
		
</div>

<div class="box box-primary" id="copy_form1">
		<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="80%">
			<tbody id="main_tbody2">
				<tr class="main_tr" class="tab">
					<td style="vertical-align: top !important;width:18%;">
						<?php echo $this->Form->input('employee_salary_division', ['empty'=>'--Select--','options' =>@$employeeDetails,'label' => false,'class' => 'form-control input-sm employee_salary_division','placeholder'=>'']); ?>
					</td>
					<td width="15%" style="vertical-align: top !important;">
						<?php echo $this->Form->input('amount', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm amount']); ?>
					</td>							  
					<td style="width:5%;" style="vertical-align: top !important;">
						<button type="button" class="addrow btn btn-default btn-xs"><i class="fa fa-plus"></i> </button>
						<a class="btn btn-danger deleterow btn-xs" href="#" role="button" style="margin-bottom: 1px;"><i class="fa fa-times"></i></a>
					</td>
				</tr>
			</tbody>
		</table>
		
</div>

			
</div>