<div class="panel panel-primary">
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
		
		<?php foreach($Salaries as $Salarie){ ?>
			<div style="border:solid 1px;">
				<div>Pay Slip for <?php echo $Salarie->employee->name; ?></div>
		<?php } ?>
	</div>
</div>