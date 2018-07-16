<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Employee Attendence</span>
		</div>
		<div class="actions">
			
		</div>
		<div class="portlet-body">
		<?php echo $this->Form->create($employeeAttendance, ['id'=>'form_sample_3']); ?>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
							
								<table class="table table-condensed">
									<tbody>
										<tr>
											<td width="15%">
												<input type="text" name="From" class="select_date form-control input-sm date-picker" placeholder="Date From" value="<?php echo @$From; ?>" data-date-format="mm-yyyy" required >
											</td>
											<td><button type="button" class="btn btn-primary btn-sm emp_rec"><i class="fa fa-filter"></i> Go</button></td>
										</tr>
									</tbody>
								</table>
							
						</div>
				</div>
				<div class="col-md-6">
				
				<div id="form_attached">
					<div class="box box-primary" id="copy_form">
						
					</div>
				</div>
			</div>
			
			</div>
			<?= $this->Form->button(__('Submit'),['class'=>'btn btn-success','type'=>'Submit']) ?>
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
			From:{
				required: true,
			},
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
	$('.adjst').live('keyup',function(){ alert();
		var one=parseFloat($(this).closest('tr').find('td:nth-child(6) input').val());
		var two=parseFloat($(this).closest('tr').find('td:nth-child(7) input.adjst').val());
		$(this).closest('tr').find('td:nth-child(8) input.amount').val(one+two);
	});
	$('.emp_rec').live('click',function(){
		var select_date=$(this).closest('tr').find('.select_date').val();
		
		var url="<?php echo $this->Url->build(['controller'=>'EmployeeAttendances','action'=>'getAttendenceList']); ?>";
		url=url+'/'+select_date, 
		 $.ajax({
			url: url,
		}).done(function(response) {
			$("#copy_form").html(response);
		});
	});
});
</script>