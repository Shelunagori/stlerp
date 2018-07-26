<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="80%">
	<thead>
		<tr>
			<td>S.N</td>
			<td>Employee Name</td>
			<td>Present Day</td>
		</tr>
	</thead>
	<tbody id="main_tbody1">
		<?php $p=1; $i=1; foreach($employees as $data){   ?>
				<tr>
					<td><?php echo $p++; ?>
					<td><?php echo $this->Form->input('employee_name', ['type'=>'text','label' => false,'placeholder'=>'','class'=>'form-control input-sm','value'=>$data->employee->name,'readonly']); ?>
					<?php echo $this->Form->input('employee_attendances.'.$i.'.employee_id', ['type' => 'hidden','placeholder'=>'','class'=>'form-control input-sm','value'=>$data->employee->id]); ?>
					</td>
					<td><?php echo $this->Form->input('employee_attendances.'.$i.'.present_day', ['type'=>'text','label' => false,'placeholder'=>'','class'=>'form-control input-sm amount','max'=>$total_day,'value'=>$data->present_day]); ?></td>
				</tr>
		<?php $i++; }  ?>
	</tbody>
	
</table>

