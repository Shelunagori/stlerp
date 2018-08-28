<style>
	input{
	margin: 0 !important;
	}
</style>

<form method="post" action="<?php echo $this->Url->build(['controller'=>'EmployeeSalaries','action'=>'generateSalary']); ?>">

<input type="hidden" name="From" value="<?php echo $From; ?>" />
<div class="table-responsive">
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
			<?php if($q>0){ ?>
			<td style="background-color:red;" align="center" colspan=
				<?php echo $q; ?>>
				<span style='color:white'>Deduction</span>
			</td>
			<?php } ?>
			<td rowspan="2">Loan Amount</td>
			<td rowspan="2" >Other</td>
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
		<?php $total=0; $r=5; $l=1; $i=1;   
		$loan_amt=0;
		$totalNetSalary=0; $TotalAddition=[]; $TotalDeduction=[]; $TotalLoanAmount=0; $TotalOthers=0;
		foreach($employees as $data){ $total_row=0; $dr_amt=0; $cr_amt=0; 
		
		?>
		<tr class="tr1">
			<td>
				<?php echo $l++; ?>
			</td>
			<td employee_id="<?php echo $data->id; ?>">
				<?php echo $data->name; ?>
				<?php echo $this->Form->input('employee_attendances.'.$i.'.employee_id', ['type' => 'hidden','placeholder'=>'','class'=>'form-control input-sm','value'=>$data->id]); ?>
			</td>
			<td>
				<?php echo $this->Form->input('amount_of_loan', ['label' => false,'style'=>'width:50px;','class'=>'form-control input-sm','type'=>'text','readonly','value'=>@$EmployeeAtten[@$data->id]]); ?>
				
			</td>
			<td>
				<?php echo @$basic_sallary[@$data->id]; $totalNetSalary+=@$basic_sallary[@$data->id]; ?>
			</td>
			<?php  
			foreach($EmployeeSalaryAddition as $data2){ 
				$dr_amt+=@$emp_sallary_division[@$data->id][@$data2->id];?>
				<td align="right" salary_div="<?php echo @$data2->id;?>">
					<?php //echo $this->Form->input('sales_order_rows.'.$q.'.quotation_row_id', ['style'=>'text-align:right;','label' => false,'placeholder'=>'','class'=>'form-control input-sm','type'=>'text','readonly','value'=>round(@$emp_sallary_division[@$data->id][@$data2->id])]); ?>
					<?php echo round(@$emp_sallary_division[@$data->id][@$data2->id]); 
						@$TotalAddition[@$data2->id]+=round(@$emp_sallary_division[@$data->id][@$data2->id]);
					?>
				</td>
			<?php }  ?>
			<?php  
			foreach($EmployeeSalaryDeduction as $data4){  
				$cr_amt+=@$emp_sallary_division[@$data->id][@$data4->id];?>
				<td align="right" salary_div="<?php echo @$data4->id;?>">
					<?php //echo $this->Form->input('amount_of_loan', ['style'=>'text-align:right;','label' => false,'placeholder'=>'','class'=>'form-control input-sm','type'=>'text','readonly','value'=>round(@$emp_sallary_division[@$data->id][@$data4->id])]); ?>
					<?php echo round(@$emp_sallary_division[@$data->id][@$data4->id]); 
						@$TotalDeduction[@$data4->id]+=round(@$emp_sallary_division[@$data->id][@$data4->id]);
					?>
				</td>
			<?php }  ?>
			<td align="right">
				<?php echo $this->Form->input('loan_amount['.$data->id.']', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm loanAm','type'=>'text','readonly','value'=>round(@$loan_amount[@$data->id])]); 
				echo $this->Form->input('loan_app['.$data->id.']', ['style'=>'text-align:right;','class'=>'form-control input-sm','type'=>'hidden','value'=>@$loan_app[@$data->id]]); 
				$loan_amt=round(@$loan_amount[@$data->id]);
				$TotalLoanAmount+=$loan_amt;
				
				?>
				<a href="#" class="hold" style="float:right;">Hold</a>
			</td>
			<td align="right">
				<?php  $total_row=@$other_amount[@$data->id]+@$total_loan_amt[@$data->id]; ?>
				<?php echo $this->Form->input('other_amount['.$data->id.']', ['style'=>'text-align:right;width:80px;','label' => false,'placeholder'=>'','class'=>'form-control input-sm other_amount1','type'=>'text','value'=>@$other_amount[@$data->id]+@$total_loan_amt[@$data->id]]); 
				$TotalOthers+=$other_amount[@$data->id]+@$total_loan_amt[@$data->id];
				?>
			</td>
			<td align="right">
				<?php $abc = round((@$dr_amt-$cr_amt)); ?>
				<?php echo $this->Form->input('net_amount', ['style'=>'text-align:right;','label' => false,'placeholder'=>'','class'=>'form-control input-sm net_amount','type'=>'text','value'=>round((($abc)-$loan_amt)-$total_row),'loan'=>@$loan_amount[@$data->id],'other'=>@$other_amount[@$data->id],'net'=>(@$dr_amt-$cr_amt),'readonly']); ?>
			</td>
			<?php $total+=round((@$dr_amt-$cr_amt)-$total_row-$loan_amt); ?>
		</tr>
		<?php $i++; }  $r+=$p+$q+1;?>
		<?php
			foreach($employees_unfreeze as $datas1){ $total_row=0; $dr_amt=0; $cr_amt=0;
				$effective_month = strtotime(date('Y-m-t',strtotime($datas1->employee_companies[0]['effective_date'])));
				$From2='01-'.$From1;
				$From3 = strtotime(date('Y-m-t',strtotime($From2)));
				
				if($effective_month > $From3){ ?>
				<tr class="tr1">
					<td>
						<?php echo $l++; ?>
					</td>
					<td employee_id="<?php echo $datas1->id; ?>">
						<?php echo $datas1->name; ?>
						<?php echo $this->Form->input('employee_attendances.'.$i.'.employee_id', ['type' => 'hidden','placeholder'=>'','class'=>'form-control input-sm','value'=>$datas1->id]); ?>
					</td>
					<td>
						<?php echo $this->Form->input('amount_of_loan', ['label' => false,'style'=>'width:50px;','class'=>'form-control input-sm','type'=>'text','readonly','value'=>round(@$EmployeeAtten1[@$datas1->id])]); ?>
						
					</td>
					<td>
						<?php echo @$basic_sallary[@$datas1->id]; $totalNetSalary+=@$basic_sallary[@$datas1->id]; ?>
					</td>
					<?php  
					foreach($EmployeeSalaryAddition as $data2){ 
						$dr_amt+=@$emp_sallary_division1[@$datas1->id][@$data2->id];?>
						<td align="right" salary_div="<?php echo @$data2->id;?>">
							<?php //echo $this->Form->input('sales_order_rows.'.$q.'.quotation_row_id', ['style'=>'text-align:right;','label' => false,'placeholder'=>'','class'=>'form-control input-sm','type'=>'text','readonly','value'=>round(@$emp_sallary_division[@$data->id][@$data2->id])]); ?>
							<?php echo round(@$emp_sallary_division1[@$datas1->id][@$data2->id]); 
								@$TotalAddition[@$data2->id]+=round(@$emp_sallary_division1[@$datas1->id][@$data2->id]);
							?>
						</td>
					<?php }  ?>
					<?php  
					foreach($EmployeeSalaryDeduction as $data4){  
						$cr_amt+=@$emp_sallary_division1[@$datas1->id][@$data4->id];?>
						<td align="right" salary_div="<?php echo @$data4->id;?>">
							<?php //echo $this->Form->input('amount_of_loan', ['style'=>'text-align:right;','label' => false,'placeholder'=>'','class'=>'form-control input-sm','type'=>'text','readonly','value'=>round(@$emp_sallary_division[@$data->id][@$data4->id])]); ?>
							<?php echo round(@$emp_sallary_division1[@$datas1->id][@$data4->id]); 
								@$TotalDeduction[@$data4->id]+=round(@$emp_sallary_division1[@$datas1->id][@$data4->id]);
							?>
						</td>
					<?php }  ?>
					<td align="right">
						<?php echo $this->Form->input('loan_amount['.$datas1->id.']', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm loanAm','type'=>'text','readonly','value'=>round(@$loan_amount[@$datas1->id])]); 
						echo $this->Form->input('loan_app['.$datas1->id.']', ['style'=>'text-align:right;','class'=>'form-control input-sm','type'=>'hidden','value'=>@$loan_app[@$datas1->id]]); 
						$loan_amt=round(@$loan_amount[@$datas1->id]);
						$TotalLoanAmount+=$loan_amt;
						
						?>
						<a href="#" class="hold" style="float:right;">Hold</a>
					</td>
					<td align="right">
						<?php  $total_row=@$other_amount1[@$datas1->id]+@$total_loan_amt[@$datas1->id]; ?>
						<?php echo $this->Form->input('other_amount['.$datas1->id.']', ['style'=>'text-align:right;width:80px;','label' => false,'placeholder'=>'','class'=>'form-control input-sm other_amount1','type'=>'text','value'=>@$other_amount1[@$datas1->id]+@$total_loan_amt[@$datas1->id]]); 
						@$TotalOthers+=@$other_amount1[@$datas1->id]+@$total_loan_amt[@$datas1->id];
						?>
					</td>
					<td align="right">
						<?php $abc = round((@$dr_amt-$cr_amt)); ?>
						<?php echo $this->Form->input('net_amount', ['style'=>'text-align:right;','label' => false,'placeholder'=>'','class'=>'form-control input-sm net_amount','type'=>'text','value'=>round((($abc)-$loan_amt)-$total_row),'loan'=>@$loan_amount[@$datas1->id],'other'=>@$other_amount1[@$datas1->id],'net'=>(@$dr_amt-$cr_amt),'readonly']); ?>
					</td>
					<?php $total+=round((@$dr_amt-$cr_amt)-$total_row-$loan_amt); ?>
				</tr>
			<?php	} else if($effective_month == $From3){ 
			?>
				<tr class="tr1">
					<td>
						<?php echo $l++; ?>
					</td>
					<td employee_id="<?php echo $datas1->id; ?>">
						<?php echo $datas1->name; ?>
						<?php echo $this->Form->input('employee_attendances.'.$i.'.employee_id', ['type' => 'hidden','placeholder'=>'','class'=>'form-control input-sm','value'=>$datas1->id]); ?>
					</td>
					<td>
						<?php echo $this->Form->input('amount_of_loan', ['label' => false,'style'=>'width:50px;','class'=>'form-control input-sm','type'=>'text','readonly','value'=>round(@$EmployeeAtten1[@$datas1->id])]); ?>
						
					</td>
					<td>
						<?php echo @$basic_sallary[@$datas1->id]; $totalNetSalary+=@$basic_sallary[@$datas1->id]; ?>
					</td>
					<?php  
					foreach($EmployeeSalaryAddition as $data2){ 
						$dr_amt+=@$emp_sallary_division1[@$datas1->id][@$data2->id];?>
						<td align="right" salary_div="<?php echo @$data2->id;?>">
							<?php //echo $this->Form->input('sales_order_rows.'.$q.'.quotation_row_id', ['style'=>'text-align:right;','label' => false,'placeholder'=>'','class'=>'form-control input-sm','type'=>'text','readonly','value'=>round(@$emp_sallary_division[@$data->id][@$data2->id])]); ?>
							<?php echo round(@$emp_sallary_division1[@$datas1->id][@$data2->id]); 
								@$TotalAddition[@$data2->id]+=round(@$emp_sallary_division1[@$datas1->id][@$data2->id]);
							?>
						</td>
					<?php }  ?>
					<?php  
					foreach($EmployeeSalaryDeduction as $data4){  
						$cr_amt+=@$emp_sallary_division1[@$datas1->id][@$data4->id];?>
						<td align="right" salary_div="<?php echo @$data4->id;?>">
							<?php //echo $this->Form->input('amount_of_loan', ['style'=>'text-align:right;','label' => false,'placeholder'=>'','class'=>'form-control input-sm','type'=>'text','readonly','value'=>round(@$emp_sallary_division[@$data->id][@$data4->id])]); ?>
							<?php echo round(@$emp_sallary_division1[@$datas1->id][@$data4->id]); 
								@$TotalDeduction[@$data4->id]+=round(@$emp_sallary_division1[@$datas1->id][@$data4->id]);
							?>
						</td>
					<?php }  ?>
					<td align="right">
						<?php echo $this->Form->input('loan_amount['.$datas1->id.']', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm loanAm','type'=>'text','readonly','value'=>round(@$loan_amount[@$datas1->id])]); 
						echo $this->Form->input('loan_app['.$datas1->id.']', ['style'=>'text-align:right;','class'=>'form-control input-sm','type'=>'hidden','value'=>@$loan_app[@$datas1->id]]); 
						$loan_amt=round(@$loan_amount[@$datas1->id]);
						$TotalLoanAmount+=$loan_amt;
						
						?>
						<a href="#" class="hold" style="float:right;">Hold</a>
					</td>
					<td align="right">
						<?php  $total_row=@$other_amount1[@$datas1->id]+@$total_loan_amt[@$datas1->id]; ?>
						<?php echo $this->Form->input('other_amount['.$datas1->id.']', ['style'=>'text-align:right;width:80px;','label' => false,'placeholder'=>'','class'=>'form-control input-sm other_amount1','type'=>'text','value'=>@$other_amount1[@$datas1->id]+@$total_loan_amt[@$datas1->id]]); 
						@$TotalOthers+=@$other_amount1[@$datas1->id]+@$total_loan_amt[@$datas1->id];
						?>
					</td>
					<td align="right">
						<?php $abc = round((@$dr_amt-$cr_amt)); ?>
						<?php echo $this->Form->input('net_amount', ['style'=>'text-align:right;','label' => false,'placeholder'=>'','class'=>'form-control input-sm net_amount','type'=>'text','value'=>round((($abc)-$loan_amt)-$total_row),'loan'=>@$loan_amount[@$datas1->id],'other'=>@$other_amount1[@$datas1->id],'net'=>(@$dr_amt-$cr_amt),'readonly']); ?>
					</td>
					<?php $total+=round((@$dr_amt-$cr_amt)-$total_row-$loan_amt); ?>
				</tr>
				
			<?php }
					
				}


		?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<th><?php echo $totalNetSalary; ?></th>
			<?php foreach($EmployeeSalaryAddition as $addition){   ?>
				<th>
					<?php echo (@$TotalAddition[$addition->id]); ?>
				</th>
			<?php  }  ?>
			<?php foreach($EmployeeSalaryDeduction as $deduction){   ?>
				<th>
					<?php echo (@$TotalDeduction[$deduction->id]); ?>
				</th>
			<?php  }  ?>
			<th id="totLoan"><?php echo $TotalLoanAmount; ?></th>
			<th id="totOther"><?php echo $TotalOthers; ?></th>
			
			<th align="right" colspan="">
				<b id="tot">
					<?= h($this->Number->format(@$total)) ?>
				</b>
			</th>
		</tr>
	</tbody>
</table>
</div>
<input type="text" name="trans_date" class="date-picker" data-date-format='dd-mm-yyyy' value="<?php echo date('d-m-Y'); ?>" data-date-format="d-m-Y" >
<select name="bank_id">
	<?php foreach($bankCashes->toArray() as $bank_id=>$bank_name){
		if($bank_id == "145"){
			echo '<option value="'.$bank_id.'" selected>'.$bank_name.'</option>';
		}else{
			echo '<option value="'.$bank_id.'">'.$bank_name.'</option>';
		}
	} ?>
</select>
<button type="submit" class="btn blue genertSlry">GENERATE SALARY</button>
</form>

<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {   
	
	$('.hold').on("click",function(event) {
		event.preventDefault();
		var undo=$(this).attr('undo');
		if(undo==1){
			var loan_amount=$(this).attr('loan_amount');
			$(this).closest('td').find('input').val(loan_amount);
			$(this).text('hold');
			$(this).attr('undo','');
			var net_amount=$(this).closest('tr').find('input[name="net_amount"]').val();
			$(this).closest('tr').find('input[name="net_amount"]').val(round(net_amount-loan_amount,2));
		}else{
			var am=parseFloat($(this).closest('td').find('input').val());
			$(this).closest('td').find('input').val(0);
			$(this).attr('loan_amount',am);
			$(this).text('undo');
			$(this).attr('undo','1');
			var net_amount=parseFloat($(this).closest('tr').find('input[name="net_amount"]').val());
			$(this).closest('tr').find('input[name="net_amount"]').val(round(net_amount+am));
		}
		totalColumn();
	});
	
	
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
		var ths=$(this);
		var net_amount=$(this).closest('tr').find('.net_amount').attr('net');
		var loan_amount=$(this).closest('tr').find('.net_amount').attr('loan');
		if(isNaN(loan_amount)){ 
			loan_amount =0;
		}
		if(isNaN(net_amount)){ 
			net_amount =0;
		}
		var other_amt=$(this).closest('tr').find('.other_amount1').val();
		
		if(isNaN(other_amt)){ 
			other_amt =0;
		}
		var amount_after_other= round(net_amount-loan_amount);
		amount_after_other = round(amount_after_other-other_amt);
		var net_amount=$(this).closest('tr').find('.net_amount').val(amount_after_other);
		totalColumn();
	});
	
	function totalColumn(){
		var t=0; var o=0; var l=0;
		$("#main_table tbody#main_tbody1 tr.tr1").each(function(){
			var loanAm=parseFloat($(this).find('input.loanAm').val());
			var other_amount1=parseFloat($(this).find('input.other_amount1').val());
			var net_amount=parseFloat($(this).find('input.net_amount').val());
			l=round(l+loanAm);
			t=round(t+net_amount);
			o=round(o+other_amount1);
			$('#totLoan').html('<b>'+l+'</b>');
			$('#totOther').html('<b>'+o+'</b>');
			$('#tot').html('<b>'+t+'</b>');
		});
	}
	
});
</script>