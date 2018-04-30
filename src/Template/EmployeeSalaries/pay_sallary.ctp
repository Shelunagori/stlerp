
<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="80%">
	<thead>
		<tr>
			<td>S.N</td>
			<td>Employee Name</td>
			<td>Total Sallary</td>
			<?php  foreach($EmployeeSalaryAddition as $addition){   ?>
			<td><?php echo $addition->name; ?></td>
			<?php  }  ?>

			<?php  foreach($EmployeeSalaryDeduction as $deduct){   ?>
			<td><?php echo $deduct->name; ?></td>
			<?php  }  ?>
			<td>Net Sallary</td>
		</tr>
	</thead>
	<tbody id="main_tbody1">
		<?php $p=1; $i=1; foreach($employees as $data){  $dr_amt=0; $cr_amt=0; ?>
				<tr>
					<td><?php echo $p++; ?>
					<td><?php echo $data->name; ?>
					<?php echo $this->Form->input('employee_attendances.'.$i.'.employee_id', ['type' => 'hidden','placeholder'=>'','class'=>'form-control input-sm','value'=>$data->id]); ?>
					</td>
					<td><?php echo @$basic_sallary[@$data->id] ?></td>
					<?php  foreach($EmployeeSalaryAddition as $data2){  
							$dr_amt+=@$emp_sallary_division[@$data->id][@$data2->id];
						?>
						<td align="right"><?= h($this->Number->format(@$emp_sallary_division[@$data->id][@$data2->id],[ 'places' => 2])) ?></td>
					<?php }  ?>
					
					<?php  foreach($EmployeeSalaryDeduction as $data4){  
							$cr_amt+=@$emp_sallary_division[@$data->id][@$data4->id];
						
						?>
						<td align="right"><?= h($this->Number->format(@$emp_sallary_division[@$data->id][@$data4->id],[ 'places' => 2])) ?></td>
						
					<?php }  ?>
						<td align="right"><?= h($this->Number->format(@$dr_amt-$cr_amt,[ 'places' => 2])) ?></td>
						
				</tr>
		<?php $i++; }  ?>
	</tbody>
	
</table>

