<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="80%">
	<thead>
		<tr>
			<td>S.N</td>
			<td>Employee Name</td>
			<td colspan="4" style="text-align:center">Explanations of leaves</td>
			<td width="20%">Present Days</td>
			<td>Adjustment Days</td>
			<td>Final Attendance</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td colspan="1">Prior Leave</td>
			<td colspan="1">Without Prior Leave</td>
			<td colspan="1">Intimated Leave</td>
			<td colspan="1">Unintimated Leave</td>
			<td ></td>
			<td ></td>
			<td ></td>
			
		</tr>
	</thead>
	<tbody id="main_tbody1">
		<?php $p=1; $i=1; foreach($employees as $data){ ?>
				<tr>
					<td><?php echo $p++; ?>
					<td><?php echo $data->name; ?>
					<?php echo $this->Form->input('employee_attendances.'.$i.'.employee_id', ['type' => 'hidden','placeholder'=>'','class'=>'form-control input-sm','value'=>$data->id]); ?>
					</td>
					<td><?php echo $this->Form->input('employee_attendances.'.$i.'.prior_approval', ['type'=>'text','label' => false,'placeholder'=>'','class'=>'form-control input-sm','value'=>@$employee_leave_prior_approval[@$data->id], 'readonly']); ?></td>
					<td><?php echo $this->Form->input('employee_attendances.'.$i.'.without_prior_approval', ['type'=>'text','label' => false,'placeholder'=>'','class'=>'form-control input-sm','max'=>5,'value'=>@$employee_leave_without_prior_approval[@$data->id], 'readonly']); ?></td>
					<td><?php echo $this->Form->input('employee_attendances.'.$i.'.intimated_leave', ['type'=>'text','label' => false,'placeholder'=>'','class'=>'form-control input-sm','value'=>@$employee_leave_intimated_leave[@$data->id], 'readonly']); ?></td>
					<td><?php echo $this->Form->input('employee_attendances.'.$i.'.unintimated_leave', ['type'=>'text','label' => false,'placeholder'=>'','class'=>'form-control input-sm','value'=>@$employee_leave_unintimated_leave[@$data->id], 'readonly']); ?></td>
					<td width="20%"><?php echo $this->Form->input('employee_attendances.'.$i.'.attendance', ['type'=>'text','label' => false,'placeholder'=>'','class'=>'form-control input-sm amount attn','max'=>$total_day,'value'=>$total_day-@$employee_leave[@$data->id], 'readonly']); ?></td>
					<td><?php echo $this->Form->input('employee_attendances.'.$i.'.adjustment_days', ['type'=>'text','label' => false,'placeholder'=>'','class'=>'form-control input-sm amount adjst','value'=>@$adjstDays[$data->id]]); ?></td>
					<td ><?php echo $this->Form->input('employee_attendances.'.$i.'.present_day', ['type'=>'text','label' => false,'placeholder'=>'','class'=>'form-control input-sm amount','max'=>$total_day,'value'=>$total_day-@$employee_leave[@$data->id]+@$adjstDays[$data->id], 'readonly']); ?></td>
				</tr>
		<?php $i++; }  ?>
	</tbody>
	
</table>

