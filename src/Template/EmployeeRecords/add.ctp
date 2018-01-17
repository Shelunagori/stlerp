<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Employee Record</span>
		</div>
		<div class="actions">
			
		</div>
		<div class="portlet-body">
			<div class="row">
				<table class="table table-condensed">
					<tbody>
						<tr>
							<td width="22%">
								<?php 
								$employee_record_types[]=['value'=>'Attendance','text'=>'Attendance'];
								$employee_record_types[]=['value'=>'Over Time','text'=>'Over Time'];
								$employee_record_types[]=['value'=>'Conveyance','text'=>'Conveyance'];
								echo $this->Form->input('employee_record_type', ['empty'=>'--Select--','options' => @$employee_record_types,'label' => false,'class' => 'form-control input-sm select2me employee_record_type','placeholder'=>'','value'=>@$employee_record_type]); ?>
							</td>
							<td width="15%">
								<input type="text" name="month_year" class="form-control input-sm date-picker" placeholder="Transaction Date To" data-date-format="mm-yyyy" value="<?php  echo @$month_year;?>">
							</td>
							<td><button type="button" class="btn btn-primary btn-sm emp_rec"><i class="fa fa-filter"></i> Filter</button></td>
						</tr>
					</tbody>
				</table>
				<div class="col-md-12">
				<?php echo $this->Form->create($employeeRecord, ['id'=>'form_sample_3']); ?>
				<div id="form_attached">
				
				</div>
				<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
    </div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>

$(document).ready(function() 
{
  $('.emp_rec').live('click',function(){
	  var employee_record_type = $('.employee_record_type option:selected').val(); 
	  var date_picker = $('.date-picker').val(); 
	  var copy=$("#copy_form").clone(); alert(copy);
	  $("#form_attached").html(copy);
  });
});
</script>
<div style="display:none;" >
<div class="box box-primary" id="copy_form">
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
							<label class="control-label  label-css">Salary Pm</label>
							<?php echo $this->Form->input('salary_pm', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm']); ?>
						</div>
					</div>
			</div>
			<div class="box-footer">
				<center>
				<button type="submit" class="btn btn-primary" id='submitbtn' >Save</button>
				</center>
			</div>
			
		</fieldset>	
		
</div>
<div class="box-footer">
	<center>
	
	 <button type="submit" class="btn btn-primary" id='submitbtn' >Save</button>
	</center>
</div>
			
</div>