<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
						<?php foreach($leavedatas as $leavedata){ ?>
							<td>Total <?php  echo $leavedata->leave_name ?></td>
						<?php  } ?>
						<?php foreach($leavedatas as $leavedata){ ?>
							<td>Pending <?php  echo $leavedata->leave_name ?></td>
						<?php  } ?>
						<tr>
					</thead>
					<tbody>
						
						<tr>	
							<?php foreach($leavedatas as $leavedata){ ?>
								<td><?php  echo $Totaalleave[$leavedata->id]; ?></td>
							<?php  } ?>	
							<?php foreach($leavedatas as $leavedata){ ?>
								<td><?php  echo $Totaalleave[$leavedata->id]-@$TotaalleaveTake[@$leavedata->id]; ?></td>
							<?php  } ?>	
						</tr>
						
					</tbody>
				</table>