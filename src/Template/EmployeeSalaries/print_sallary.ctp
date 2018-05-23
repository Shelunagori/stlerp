<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Print salary sheet</h3>
	</div>
	<div class="panel-body">
		<?php $yearFrom=date('Y',strtotime($financial_year->date_from)); ?>
		<form method="post" class="hide_at_print">
			<select name="month_year">
				<option value="4-<?php echo $yearFrom; ?>">April-<?php echo $yearFrom; ?></option>
				<option value="5-<?php echo $yearFrom; ?>">May-<?php echo $yearFrom; ?></option>
				<option value="6-<?php echo $yearFrom; ?>">June-<?php echo $yearFrom; ?></option>
				<option value="7-<?php echo $yearFrom; ?>">July-<?php echo $yearFrom; ?></option>
				<option value="8-<?php echo $yearFrom; ?>">August-<?php echo $yearFrom; ?></option>
				<option value="9-<?php echo $yearFrom; ?>">September-<?php echo $yearFrom; ?></option>
				<option value="10-<?php echo $yearFrom; ?>">October-<?php echo $yearFrom; ?></option>
				<option value="11-<?php echo $yearFrom; ?>">November-<?php echo $yearFrom; ?></option>
				<option value="12-<?php echo $yearFrom; ?>">December-<?php echo $yearFrom; ?></option>
				<option value="1-<?php echo $yearFrom+1; ?>">January-<?php echo $yearFrom+1; ?></option>
				<option value="2-<?php echo $yearFrom+1; ?>">February-<?php echo $yearFrom+1; ?></option>
				<option value="3-<?php echo $yearFrom+1; ?>">March-<?php echo $yearFrom+1; ?></option>
			</select>
			<button type="submit">View</button>
		</form>
		
		<?php if(sizeof(@$Employees)>0){
			$division=[];
			$allDivisions=[];
			$salary_type=[];
			$Loan=[];
			$Others=[];
			foreach($Employees as $Employee){
				foreach($Employee->salaries as $salarie){
					$allDivisions[$salarie->employee_salary_division_id]=@$salarie->emp_sal_div->name;
					$salary_type[$Employee->id][$salarie->employee_salary_division_id]=@$salarie->emp_sal_div->salary_type;
					$division[$Employee->id][$salarie->employee_salary_division_id]=$salarie->amount;
					$Loan[$Employee->id]=$salarie->loan_amount;
					if($salarie->other_amount!=0){
						$Others[$Employee->id]=$salarie->other_amount;
						
					}
				}
			}
			//pr($allDivisions);
			//pr($salary_type);
			//pr($division); 
			//exit;
			?>
			<button type="button" onclick="window.print()" class="hide_at_print">Print</button>
			<table class="table table-condensed table-hover">
				<tr>
					<th>Employee Name</th>
					<?php foreach($allDivisions as $DivisionId=>$DivisionName){
						echo '<th>'.$DivisionName.'</th>';
					} ?>
					<th>Loan installment</th>
					<th>Others amount</th>
					<th>Total</th>
				</tr>
				<?php $total_sal=[];  $total_loan=0; $total_other=0; foreach($Employees as $Employee){ ?>
				<tr>
					<td><?php echo $Employee->name; ?></td>
					<?php $total_add=0; $total_ded=0; foreach($allDivisions as $DivisionId=>$DivisionName){
						
						echo '<td>'.@$division[$Employee->id][$DivisionId].'</td>';
						//pr($salary_type[$Employee->id][$DivisionId]);
						 if(@$salary_type[$Employee->id][$DivisionId]=="addition"){
							@$total_add+=@$division[$Employee->id][$DivisionId];
						}else  if(@$salary_type[$Employee->id][$DivisionId]=="deduction"){
							@$total_ded+=@$division[$Employee->id][$DivisionId];
						} 
						//pr($total_add);
						//pr($total_ded);
						//@$total_sal[@$DivisionId]+=@$division[$Employee->id][$DivisionId];
					} ?>
					<td><?php echo @$Loan[$Employee->id]; $total_loan+=@$Loan[$Employee->id];?></td>
					<td><?php echo @$Others[$Employee->id]; $total_other+=@$Others[$Employee->id];?></td>
					<?php $p=@$total_add-@$total_ded-@$Loan[$Employee->id];
						$q=$p-@$Others[$Employee->id]; 
						//if(@$Others[$Employee->id])
					?>
					
					<td><?php echo @$q; ?></td>
					
				</tr>
				<?php } ?>
				
			</table>
		<?php }else{
			echo 'Salary not submited.';
		}?>
		
	</div>
</div>
<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
</style>