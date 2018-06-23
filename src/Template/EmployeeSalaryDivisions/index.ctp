<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Salary Divisions</span>
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<div class="row ">
		<?php if(in_array(208,$allowed_pages)){ ?>
		<div class="col-md-6">
		 <?= $this->Form->create($employeeSalaryDivision,array("class"=>"form-horizontal","id"=>"form_sample_3")) ?>
			<div class="form-body">
				<div class="form-group">
					<label class="control-label col-md-3">Name  <span class="required" aria-required="true">
					* </span>
					</label>
					<div class="col-md-9">
						<div class="input-icon right">
							<i class="fa"></i>
							 <?php echo $this->Form->input('name', ['label' => false,'class' => 'form-control firstupercase']); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">Type  <span class="required" aria-required="true">
					* </span>
					</label>
					<div class="col-md-9">
						<div class="input-icon right">
							<i class="fa"></i>
							 <?php 
								echo $this->Form->input('salary_type', ['options'=>['addition'=>'Addition','deduction'=>'Deduction'],'label' => false,'class' => 'form-control input-sm  ','style'=>'vertical-align: top !important;']); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">Vary/Fixed <span class="required" aria-required="true">
					* </span>
					</label>
					<div class="col-md-9">
						<div class="input-icon right">
							<i class="fa"></i>
							 <?php 
								echo $this->Form->input('vary_fixed', ['options'=>['Vary'=>'Vary','Fixed'=>'Fixed'],'label' => false,'class' => 'form-control input-sm  ','style'=>'vertical-align: top !important;']); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">Ledger <span class="required" aria-required="true">
					* </span>
					</label>
					<div class="col-md-9">
						<div class="input-icon right">
							<i class="fa"></i>
							 <?php 
								echo $this->Form->input('ledger_account_id', ['empty'=>'--Select Ledger--','options'=>$LedgerAccounts,'label' => false,'class' => 'form-control input-sm  select2me','style'=>'vertical-align: top !important;']); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">Dr/Cr  <span class="required" aria-required="true">
					* </span>
					</label>
					<div class="col-md-9">
						<div class="input-icon right">
							<i class="fa"></i>
							 <?php 
								echo $this->Form->input('cr_dr', ['options'=>['dr'=>'Dr','cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm  ','style'=>'vertical-align: top !important;']); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-offset-4 col-md-8">
						<button type="submit" class="btn btn-primary">Add</button>
					</div>
				</div>
			</div>
		<?= $this->Form->end() ?>
		</div>
		<?php } ?>
		<div class="col-md-6">
			<div class="portlet-body">
			<div class="table-scrollable">
			<table class="table table-bordered table-striped table-hover" id="main_tble">
						<thead>
							<tr>
								<th>S.No</th>
								<th>Name</th>
								<th>Type</th>
								<th>Vary/Fixed</th>
								<th>Ledger</th>
								<th>Dr/Cr</th>
								<th>Action</th>
								
							</tr>
					
					</thead>
					<tbody>
					
						   <?php $page_no=0; foreach ($employeeSalaryDivisions as $employeeSalaryDivision): ?>
						<tr>
							<td><?= h(++$page_no) ?></td>
							
							<td><?= h($employeeSalaryDivision->name) ?></td>
							
							<td><?= h($employeeSalaryDivision->salary_type) ?></td>
							<td><?= h($employeeSalaryDivision->vary_fixed) ?></td>
							<td><?= h(@$employeeSalaryDivision->ledger_account->name) ?></td>
							<td><?= h($employeeSalaryDivision->cr_dr) ?></td>
							<td class="actions">
							<?php 
							
							if(in_array(209,$allowed_pages)){
							echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $employeeSalaryDivision->id],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit')); 
							} 
							if(in_array(210,$allowed_pages)){
							?>
							<?= $this->Form->postLink('<i class="fa fa-trash"></i> ',
								['action' => 'delete', $employeeSalaryDivision->id], 
								[
									'escape' => false,
									'class'=>'btn btn-xs red tooltips','data-original-title'=>'Delete',
									
									'confirm' => __('Are you sure ?', $employeeSalaryDivision->id)
								]
							) ?>
							
							<?php } ?>
						</td>			
							
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="paginator">
				<ul class="pagination">
					<?= $this->Paginator->prev('<') ?>
					<?= $this->Paginator->numbers() ?>
					<?= $this->Paginator->next('>') ?>
				</ul>
				<p><?= $this->Paginator->counter() ?></p>
			</div>
			</div>
		</div>
		<!-- END FORM-->
	</div>
</div>
</div>

<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>
$(document).ready(function() {
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
				ledger_account_id:{
					required: true,
				},
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
			form[0].submit(); // submit the form
		}

	});
});
</script>