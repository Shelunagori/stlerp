
<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="80%">
	<thead>
		<tr>
			<td rowspan="2">S.N</td>
			<td rowspan="2">Employee Name</td>
			<td rowspan="2">Total Salary</td>
			<?php $p=sizeof($EmployeeSalaryAddition->toArray()); ?>
			<td style="background-color:green;"  align="center" colspan=<?php echo $p; ?>><span style='color:white'>Addition</span></td>
			<?php $q=sizeof($EmployeeSalaryDeduction->toArray()); ?>
			<td style="background-color:red;" align="center" colspan=<?php echo $q; ?>><span style='color:white'>Deduction</span></td>
			<td rowspan="2">Net Salary</td>
		</tr>
		<tr>
			<?php foreach($EmployeeSalaryAddition as $addition){   ?>
			<td><span style='color:green'><?php echo $addition->name; ?></span></td>
			
			<?php  }  ?>
			<?php foreach($EmployeeSalaryDeduction as $deduct){   ?>
			<td><span style='color:red'><?php echo $deduct->name; ?></span></td>
			<?php  }  ?>
		</tr>
		
	</thead>
	<tbody id="main_tbody1">
		<?php $total=0; $r=3; $l=1; $i=1; foreach($employees as $data){  $dr_amt=0; $cr_amt=0; ?>
				<tr>
					<td><?php echo $l++; ?></td>
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
						<?php $total+=@$dr_amt-$cr_amt; ?>
				</tr>
		<?php $i++; }  $r+=$p+$q;?>
				<tr>	
					<td align="right" colspan="<?php echo $r; ?>"><b>Total</b></td>
					<td align="right" colspan=""><b><?= h($this->Number->format(@$total,[ 'places' => 2])) ?></b></td>
				</tr>
	</tbody>
	
</table>

