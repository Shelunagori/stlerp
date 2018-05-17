<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="80%">
	<thead>
		<tr>
			<td>S.N</td>
			<td>Employee Name</td>
			<td>Present Days</td>
			<td>Adjustment Days</td>
			<td>Final Attendance</td>
		</tr>
	</thead>
	<tbody id="main_tbody1">
		<?php $p=1; $i=1; foreach($employees as $data){ ?>
				<tr>
					<td><?php echo $p++; ?>
					<td><?php echo $this->Form->input('employee_name', ['type'=>'text','label' => false,'placeholder'=>'','class'=>'form-control input-sm','value'=>$data->name,'readonly']); ?>
					<?php echo $this->Form->input('employee_attendances.'.$i.'.employee_id', ['type' => 'hidden','placeholder'=>'','class'=>'form-control input-sm','value'=>$data->id]); ?>
					</td>
					<td><?php echo $this->Form->input('employee_attendances.'.$i.'.attendance', ['type'=>'text','label' => false,'placeholder'=>'','class'=>'form-control input-sm amount','max'=>$total_day,'value'=>$total_day-@$employee_leave[@$data->id], 'readonly']); ?></td>
					<td><?php echo $this->Form->input('employee_attendances.'.$i.'.adjustment_days', ['type'=>'text','label' => false,'placeholder'=>'','class'=>'form-control input-sm amount adjst','value'=>@$adjstDays[$data->id]]); ?></td>
					<td><?php echo $this->Form->input('employee_attendances.'.$i.'.present_day', ['type'=>'text','label' => false,'placeholder'=>'','class'=>'form-control input-sm amount','max'=>$total_day,'value'=>$total_day-@$employee_leave[@$data->id]+@$adjstDays[$data->id], 'readonly']); ?></td>
				</tr>
		<?php $i++; }  ?>
	</tbody>
	
</table>

