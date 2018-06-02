<?php $months=[4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'october',11=>'November',12=>'December',1=>'January',2=>'February',3=>'March']; ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">Leave Status</h3>
	</div>
	<div class="panel-body">
		<button type="button" onclick="ExportToExcel('qwerty');" class="hide_at_print">Excel</button>
		<div class="table-scrollable">
			<table class="table table-condensed table-hover table-bordered" id="qwerty">
				<thead >
					<tr>
						<th></th>
						<th></th>
						<?php foreach($months as $month=>$Mname){ ?>
							<th colspan="4" style="text-align:center"><?php echo $Mname; ?></th>
						<?php } ?>
						<th colspan="4" style="text-align:center"><b>Total</b></th>
					</tr>
					<tr>
						<th></th>
						<th></th>
						<?php foreach($months as $month=>$Mname){ ?>
							<th colspan="2" style="text-align:center">CL</th>
							<th colspan="2" style="text-align:center">SL</th>
						<?php } ?>
						<th colspan="2" style="text-align:center">CL</th>
						<th colspan="2" style="text-align:center">SL</th>
					</tr>
					<tr>
						<th>SR</th>
						<th>Name</th>
						<?php foreach($months as $month){ ?>
							<th style="text-align:center">Taken</th>
							<th style="text-align:center">Remaining</th>
							<th style="text-align:center">Taken</th>
							<th style="text-align:center">Remaining</th>
						<?php } ?>
						<th style="text-align:center">Total Taken</th>
						<th style="text-align:center">Total Remaining</th>
						<th style="text-align:center">Total Taken</th>
						<th style="text-align:center">Total Remaining</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$sr=0;
					foreach($Employees as $Employee){ 
					$TotalTakenCL=0; 
					$TotalTakenSL=0; 
					$TotalRemainingCL=0; 
					$TotalRemainingSL=0; 
					?>
					<tr>
						<td><?php echo ++$sr; ?></td>
						<td style="white-space: nowrap;font-size: 11px;">
							<?php echo $Employee->name; ?>
						</td>
						<?php foreach($months as $month=>$Mname){ ?>
							<td style="text-align:center">
								<?php 
								echo @$currentLeaves[$Employee->id][$month][1]; 
								$TotalTakenCL+=@$currentLeaves[$Employee->id][$month][1];
								?>
							</td>
							<td style="text-align:center">
								<?php 
								echo (1.25-@$currentLeaves[$Employee->id][$month][1]) ;
								$TotalRemainingCL+=(1.25-@$currentLeaves[$Employee->id][$month][1]);
								?>
							</td>
							<td style="text-align:center">
								<?php 
								echo @$currentLeaves[$Employee->id][$month][2];
								$TotalTakenSL+=@$currentLeaves[$Employee->id][$month][2];
								?>
							</td>
							<td style="text-align:center">
								<?php 
								echo (1.25-@$currentLeaves[$Employee->id][$month][2]) ;
								$TotalRemainingSL+=(1.25-@$currentLeaves[$Employee->id][$month][2]);
								?>
							</td>
						<?php } ?>
						<th style="text-align:center"><?php echo $TotalTakenCL; ?></th>
						<th style="text-align:center"><?php echo $TotalRemainingCL; ?></th>
						<th style="text-align:center"><?php echo $TotalTakenSL; ?></th>
						<th style="text-align:center"><?php echo $TotalRemainingSL; ?></th>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	function ExportToExcel(mytblId){
       var htmltable= document.getElementById(mytblId);
       var html = htmltable.outerHTML;
       window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));
    }
</script>