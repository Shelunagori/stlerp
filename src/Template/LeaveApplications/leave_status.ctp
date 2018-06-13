<?php $months=[4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'october',11=>'November',12=>'December',1=>'January',2=>'February',3=>'March']; ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">Leave Status</h3>
	</div>
	<div class="panel-body">
		<div class="table-scrollable">
			<table>
				<thead >
					<tr>
						<th></th>
						<?php foreach($months as $month=>$Mname){ ?>
							<th colspan="4" style="text-align:center"><?php echo $Mname; ?></th>
						<?php } ?>
						<th colspan="4" style="text-align:center"><b>Total</b></th>
					</tr>
					<tr>
						<th></th>
						<?php foreach($months as $month=>$Mname){ ?>
							<th colspan="2" style="text-align:center">CL</th>
							<th colspan="2" style="text-align:center">SL</th>
						<?php } ?>
						<th colspan="2" style="text-align:center">CL</th>
						<th colspan="2" style="text-align:center">SL</th>
					</tr>
					<tr>
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
						<td style="text-align:center"><?php echo $TotalTakenCL; ?></td>
						<td style="text-align:center"><?php echo $TotalRemainingCL; ?></td>
						<td style="text-align:center"><?php echo $TotalTakenSL; ?></td>
						<td style="text-align:center"><?php echo $TotalRemainingSL; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<style>
table {
  position: relative;
  width: 1000px;
  background-color: #FFF;
  overflow: hidden;
  border-collapse: collapse;
}


/*thead*/
thead {
  position: relative;
  display: block; /*seperates the header from the body allowing it to be positioned*/
  width: 1000px;
  overflow: visible;
}

thead th {
  background-color: #FFF;
  min-width: 100px;
  height: 20px;
  border: 1px solid #CCC;
}

thead th:nth-child(1) {/*first cell in the header*/
  position: relative;
  display: block; /*seperates the first cell in the header from the header*/
  background-color: #FFF;
  min-width: 150px;
}


/*tbody*/
tbody {
  position: relative;
  display: block; /*seperates the tbody from the header*/
  width: 1000px;
  height: 400px;
  overflow: scroll;
}

tbody td {
  background-color: #FFF;
  min-width: 100px;
  height: 20px !important;
  border: 1px solid #CCC;
}

tbody tr td:nth-child(1) {  /*the first cell in each tr*/
  position: relative;
  display: block; /*seperates the first column from the tbody*/
  height: 40px;
  background-color: #FFF;
  min-width: 150px;
}

</style>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>
$(document).ready(function() {
  $('tbody').scroll(function(e) { //detect a scroll event on the tbody
  	/*
    Setting the thead left value to the negative valule of tbody.scrollLeft will make it track the movement
    of the tbody element. Setting an elements left value to that of the tbody.scrollLeft left makes it maintain 			it's relative position at the left of the table.    
    */
    $('thead').css("left", -$("tbody").scrollLeft()); //fix the thead relative to the body scrolling
    $('thead th:nth-child(1)').css("left", $("tbody").scrollLeft()); //fix the first cell of the header
    $('tbody td:nth-child(1)').css("left", $("tbody").scrollLeft()); //fix the first column of tdbody
  });
});
</script>