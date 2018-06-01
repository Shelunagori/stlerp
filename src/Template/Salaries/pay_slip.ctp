<style>
.qwerty th {
    color: #FFF;
	background-color: #254b73;
}
</style>
<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0 5px 0 20px;  /* this affects the margin in the printer settings */
}
</style>
<div class="panel panel-primary hide_at_print">
	<div class="panel-heading">
		<h3 class="panel-title">Generate Pay Slip</h3>
	</div>
	<div class="panel-body">
		<?php $yearFrom=date('Y',strtotime($financial_year->date_from)); ?>
		<form method="post">
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
			<button type="submit">Print</button>
		</form>
		
		
	</div>
</div>

		<?php 
		if(@$Employees){
		foreach($Employees as $Employee){
			if(sizeof($Employee->salaries)>0){
			?>
			<div style="border:solid 1px;background-color: #FFF;padding:2px;">
				<div>
					<table width="100%" class="divHeader">
						<tr>
							<td width="30%"><?php echo $this->Html->image('/logos/'.$company->logo, ['width' => '30%']); ?></td>
							<td align="center" width="40%" style="font-size: 14px;"><div align="center" style="font-size: 16px;font-weight: bold;color: #0685a8;">PAYSLIP</div></td>
							<td align="right" width="30%" style="font-size: 12px;">
							<span style="font-size: 14px;"><?= h($company->name) ?></span>
							<span><?= $this->Text->autoParagraph(h($company->address)) ?>
							<?= h($company->mobile_no) ?></span>
							</td>
						</tr>
						<tr>
							<td colspan="3">
								<div style="border:solid 2px #0685a8;margin-bottom:-12px;margin-top: 7px;"></div>
							</td>
						</tr>
					</table></br>
				</div>
				<div style="padding:5px;">
				<table width="100%">
					<tr>
						<td width="10%"><b>Employee:</b> </td>
						<td><?php echo $Employee->name; ?></td>
						<td width="10%"><b>Dath of birth:</b> </td>
						<td><?php echo $Employee->dob->format('d-m-Y'); ?></td>
					</tr>
					<tr>
						<td width="10%"><b>Designation:</b> </td>
						<td><?php echo $Employee->designation->name; ?></td>
						<td width="10%"><b>Department:</b> </td>
						<td><?php echo $Employee->department->name; ?></td>
					</tr>
					<tr>
						<td width="10%"><b>Date of joining:</b></td>
						<td><?php echo $Employee->join_date->format('d-m-Y'); ?></td>
					</tr>
				</table><br/>
				<table width="100%" class="table table-bordered">
					<tr class="qwerty">
						<th>Head</th>
						<th>Type</th>
						<th>Amount (₹)</th>
					</tr>
					<?php $tot=0; 
					foreach($Employee->salaries as $salarie){ ?>
						<tr>
							<td style="width:50%"><?php echo $salarie->employee_salary_division->name; ?></td>
							<td style="width:30%"><?php echo ucwords($salarie->employee_salary_division->salary_type); ?></td>
							<td align="right">
							<?php if($salarie->employee_salary_division->salary_type=="deduction"){
								echo '(-)';
								$tot-=$salarie->amount;
							}else{
								$tot+=$salarie->amount;
							}?>
							<?php echo $salarie->amount; ?>
							</td>
						</tr>
					<?php } ?>
					<tr >
						<th colspan="2" style="text-align:right;">Total</th>
						<th style="text-align:right;"><?php echo $tot; ?></th>
					</tr>
				</table>
				</div>
			</div><br/><br/>
			<div style="page-break-after: always"></div>
		<?php } 
		}
		}		?>
<style>
.dv{
border-top: solid 1px #CCC;
    padding: 5px;
}
</style>