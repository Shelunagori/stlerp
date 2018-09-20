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
			<div style="overflow:auto;" class="hide_at_print">
				<?php $url= $this->Url->build(['controller'=>'Salaries','action'=>'sendPaySlip']); ?>
				<form method="post" action="<?php echo $url; ?>" id="f<?php echo $Employee->id; ?>" target="_blank">
					<input type="hidden" name="email" value="<?php echo $Employee->company_email; ?>" />
					<textarea name="qwerty" id="t<?php echo $Employee->id; ?>" style="display:none;"></textarea>
					<button type="button" id="<?php echo $Employee->id; ?>" class="btnsb btn btn-sm btn-primary" style="float: left;">Send by mail</button>
				</form>
				<?php $url= $this->Url->build(['controller'=>'Salaries','action'=>'printPaySlip']); ?>
				<form method="post" action="<?php echo $url; ?>" id="f2<?php echo $Employee->id; ?>" target="_blank">
					<input type="hidden" name="email" value="<?php echo $Employee->company_email; ?>" />
					<textarea name="qwerty" id="t2<?php echo $Employee->id; ?>" style="display:none;"></textarea>
					<button type="button" id="<?php echo $Employee->id; ?>" class="btnprint btn btn-sm btn-info" style="float: left;">Print</button>
				</form>
			</div>
			<div style="border:solid 1px;background-color: #FFF;padding:2px;" id="d<?php echo $Employee->id; ?>">
				<div>
					<table width="100%" class="divHeader">
						<tr>
							<td width="30%"><?php echo $this->Html->image('/logos/'.$company->logo, ['width' => '30%','fullBase' => true]); ?></td>
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
						<td width="10%"><b>Payslip for:</b> </td>
						<td><?php echo date('M',strtotime($month_year[1].'-'.$month_year[0].'-1')).'-'.$month_year[1]; ?></td>
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
						<td width="10%"><b>Leave:</b></td>
						<td><?php echo 'CL-'.@$currentLeaves[$Employee->id][$month_year[0]][1]; echo ' SL-'.@$currentLeaves[$Employee->id][$month_year[0]][2]; ?></td>
					</tr>
				</table><br/>
				<table width="100%" class="">
					<tr class="qwerty">
						<th style="border: solid 1px #CCC;border-collapse: collapse;padding:2px;">Head</th>
						<th style="border: solid 1px #CCC;border-collapse: collapse;padding:2px;">Type</th>
						<th style="border: solid 1px #CCC;border-collapse: collapse;padding:2px;">Amount (₹)</th>
					</tr>
					<?php $tot=0; 
					foreach($Employee->salaries as $salarie){
						if($salarie->employee_salary_division_id){
						?>
						<tr>
							<td style="width:50%;border: solid 1px #CCC;border-collapse: collapse;padding:2px;"><?php echo $salarie->employee_salary_division->name; ?></td>
							<td style="width:30%;border: solid 1px #CCC;border-collapse: collapse;padding:2px;"><?php echo ucwords($salarie->employee_salary_division->salary_type); ?></td>
							<td align="right" style="border: solid 1px #CCC;border-collapse: collapse;padding:2px;">
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
					<?php } ?>
					<?php foreach($Employee->salaries as $salarie){ ?>
						<?php if($salarie->loan_amount>0){ ?>
						<tr>
							<td style="width:50%;border: solid 1px #CCC;border-collapse: collapse;padding:2px;">Loan Installment</td>
							<td style="width:30%;border: solid 1px #CCC;border-collapse: collapse;padding:2px;">Deduction</td>
							<td align="right" style="border: solid 1px #CCC;border-collapse: collapse;padding:2px;">
								(-) <?php echo $salarie->loan_amount; $tot-=$salarie->loan_amount;?>
							</td>
						</tr>
						<?php } ?>
						
						<?php if($salarie->other_amount!=0){ ?>
						<tr>
							<td style="width:50%;border: solid 1px #CCC;border-collapse: collapse;padding:2px;">Others</td>
							<td style="width:30%;border: solid 1px #CCC;border-collapse: collapse;padding:2px;">
								<?php if($salarie->other_amount>0){
									echo 'Addition';
								}else{
									echo 'Deduction';
								} ?>
							</td>
							<td align="right" style="border: solid 1px #CCC;border-collapse: collapse;padding:2px;">
								<?php if($salarie->other_amount>0){
									echo $salarie->other_amount;
									$tot-=$salarie->other_amount;
								}else{
									echo '(-) '.abs($salarie->other_amount);
									 $tot+=abs($salarie->other_amount);
								} ?>
								<?php?>
							</td>
						</tr>
						<?php } ?>
					<?php } ?>
					<tr>
						<th colspan="2" style="text-align:right;border: solid 1px #CCC;border-collapse: collapse;padding:2px;">Total</th>
						<th style="text-align:right;border: solid 1px #CCC;border-collapse: collapse;padding:2px;"><?php echo round($tot); ?></th>
					</tr>
				</table>
				<table width="100%" class="divFooter">
					<tr> 
						<td width="70%" align="left" valign="top">
						  
						</td>
						<td align="right">
							<table>
								<tr>
									<td align="center">
									<span style="font-weight: bold;">For</span> <span style="font-weight: bold;"><?php echo $company->name; ?><br></span>
									<?php 
									echo $this->Html->Image('/signatures/'.$em->signature,['height'=>'50px','style'=>'height:50px;','fullBase' => true]); 
									?><br>
									<span style="font-weight: bold;">Authorised Signatory</span>
									<br>
									<span style="font-weight: bold;"><?php echo $em->name; ?></span><br>
									</td>
								</tr>
							</table>
						</td>
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
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>
$(document).ready(function() {
	$('.btnsb').die().live("click",function() {
		var id=$(this).attr('id');
		var html=$('div#d'+id).html();
		$('textarea#t'+id).text(html);
		$('form#f'+id).submit();
	});
	$('.btnprint').die().live("click",function() {
		var id=$(this).attr('id');
		var html=$('div#d'+id).html();
		$('textarea#t2'+id).text(html);
		$('form#f2'+id).submit();
	});
});
</script>