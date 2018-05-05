
<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="80%">
	<thead>
		<tr>
			<td rowspan="2">S.N</td>
			<td rowspan="2">Employee Name</td>
			<td rowspan="2">Atten.</td>
			<td rowspan="2">Total Salary</td>
			<?php $p=sizeof($EmployeeSalaryAddition->toArray()); ?>
			<td style="background-color:green;"  align="center" colspan=
				<?php echo $p; ?>>
				<span style='color:white'>Addition</span>
			</td>
			<?php $q=sizeof($EmployeeSalaryDeduction->toArray()); ?>
			<td style="background-color:red;" align="center" colspan=
				<?php echo $q; ?>>
				<span style='color:white'>Deduction</span>
			</td>
			<td rowspan="2">Loan Amount</td>
			<td rowspan="2">Other</td>
			<td rowspan="2">Net.Salary</td>
		</tr>
		<tr>
			<?php foreach($EmployeeSalaryAddition as $addition){   ?>
			<td>
				<span style='color:green'>
					<?php echo $addition->name; ?>
				</span>
			</td>
			<?php  }  ?>
			<?php foreach($EmployeeSalaryDeduction as $deduct){   ?>
			<td>
				<span style='color:red'>
					<?php echo $deduct->name; ?>
				</span>
			</td>
			<?php  }  ?>
		</tr>
	</thead>
	<tbody id="main_tbody1">
		<?php $total=0; $r=5; $l=1; $i=1;   foreach($employees as $data){ $total_row=0; $dr_amt=0; $cr_amt=0; ?>
		<tr class="tr1">
			<td>
				<?php echo $l++; ?>
			</td>
			<td>
				<?php echo $data->name; ?>
				
				<?php echo $this->Form->input('employee_attendances.'.$i.'.employee_id', ['type' => 'hidden','placeholder'=>'','class'=>'form-control input-sm','value'=>$data->id]); ?>
			</td>
			<td>
				<?php echo $this->Form->input('amount_of_loan', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm','type'=>'text','readonly','value'=>round(@$EmployeeAtten[@$data->id],2)]); ?>
				
			</td>
			<td>
				<?php echo @$basic_sallary[@$data->id]; ?>
				
				
			</td>
			<?php  foreach($EmployeeSalaryAddition as $data2){ 
							$dr_amt+=@$emp_sallary_division[@$data->id][@$data2->id];
						?>
			<td align="right" salary_div="<?php echo @$data2->id;?>">
				<?php echo $this->Form->input('sales_order_rows.'.$q.'.quotation_row_id', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm','type'=>'text','readonly','value'=>round(@$emp_sallary_division[@$data->id][@$data2->id],2)]); ?>
			</td>
			<?php }  ?>
			<?php  foreach($EmployeeSalaryDeduction as $data4){  
							$cr_amt+=@$emp_sallary_division[@$data->id][@$data4->id];
							 
						?>
			<td align="right" salary_div="<?php echo @$data4->id;?>">
				
				<?php echo $this->Form->input('amount_of_loan', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm','type'=>'text','readonly','value'=>round(@$emp_sallary_division[@$data->id][@$data4->id],2)]); ?>
			</td>
			<?php }  ?>
			<td align="right">
				<?php echo $this->Form->input('amount_of_loan', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm','type'=>'text','readonly','value'=>round(@$loan_amount[@$data->id],2)]); 
				$loan_amt=round(@$loan_amount[@$data->id],2);
				?>
				
			</td>
			<td align="right">
				
				<?php  $total_row=@$other_amount[@$data->id]; ?>
				<?php echo $this->Form->input('other_amount', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm other_amount1','type'=>'text','value'=>@$other_amount[@$data->id]]); ?>
				
			</td>
			<td align="right">
				
				<?php echo $this->Form->input('net_amount', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm net_amount','type'=>'text','value'=>(@$dr_amt-$cr_amt)-$total_row-$loan_amt,'other'=>@$other_amount[@$data->id],'net'=>(@$dr_amt-$cr_amt)]); ?>
			</td>
			<?php $total+=(@$dr_amt-$cr_amt)-$total_row-$loan_amt; ?>
		</tr>
		<?php $i++; }  $r+=$p+$q;?>
		<tr>
			<td align="right" colspan="
				<?php echo $r; ?>">
				<b>Total</b>
			</td>
			<td align="right" colspan="">
				<b>
					<?= h($this->Number->format(@$total,[ 'places' => 2])) ?>
				</b>
			</td>
		</tr>
	</tbody>
</table>
<button type="button" class="btn blue genertSlry">GENERATE SALARY</button>


<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {   
	
	$('.save').on("click",function() {
		
		$("#main_table tbody#main_tbody1 tr.tr1").each(function(){ var counter=0; 
				$(this).find('td').each(function(){ counter++;
					var p=$(this);
					if(counter > 3){
						var salary_div=$(this).attr("salary_div");
						$(this).html(salary_div);
					}
				});
		});
    });

	$('.other_amount1').on("blur",function() {
		var net_amount=$(this).closest('tr').find('.net_amount').attr('net');
		var other_amt=$(this).closest('tr').find('.other_amount1').val();
		var amount_after_other=round(net_amount-other_amt);
		var net_amount=$(this).closest('tr').find('.net_amount').val(amount_after_other);
	});
	
});
</script>