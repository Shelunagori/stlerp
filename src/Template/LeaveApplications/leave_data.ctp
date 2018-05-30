<table class="table table-bordered table-striped table-hover" style="width:50%">
	<thead>
		<tr>
		<?php foreach($leavedatas as $leavedata){ ?>
			<td><?php  echo $leavedata->leave_name ?></td>
		<?php  } ?>
		<tr>
	</thead>
	<tbody>
		<tr>	
			<?php foreach($leavedatas as $leavedata){ ?>
				<td><?php  echo $Totaalleave[$leavedata->id]-@$TotaalleaveTake[@$leavedata->id]; ?>/<?php  echo $Totaalleave[$leavedata->id]; ?></td>
			<?php  } ?>	
		</tr>
	</tbody>
</table>