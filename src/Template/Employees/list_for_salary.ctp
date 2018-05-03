<div class="portlet box purple" style="width:70%;">
	<div class="portlet-title">
		<div class="caption">
			Here we can decide, from which company the employee will get the salary.
		</div>
	</div>
	<div class="portlet-body">
		<div class="table-scrollable">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Employee Name</th>
						<th style="width:40%;">Company</th>
					</tr>
				</thead>
				<tbody>
					<?php  foreach($employees as $employee){ ?>
					<tr>
						<td><?php echo $employee->name; ?></td>
						<td><?php echo $this->Form->input('salary_company_id', ['empty'=> '---Select Company---','label' => false,'class'=>'form-control input-sm','options'=>@$companies,'employee_id'=>$employee->id,'value'=>$employee->salary_company_id]); ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script type="text/javascript">
$(document).ready(function () {
	$('select[name=salary_company_id]').change(function () {
		var ths=$(this);
		var c_id=$(this).find('option:selected').val();
		var employee_id=$(this).attr('employee_id');
		
		var url="<?php echo $this->Url->build(['controller'=>'Employees','action'=>'saveSalaryInfo']); ?>";
		url=url+'/'+c_id+'/'+employee_id;
		
		$.ajax({
			url: url,
			type: 'GET',
		}).done(function(response) {
			ths.closest('td').append('<span class="qw" >Saved</span>');
			setTimeout(function(){
				ths.closest('td').find('span.qw').remove();
			}, 3000);
		});
	});
});
</script>