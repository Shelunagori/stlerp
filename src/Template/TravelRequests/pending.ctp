<?php if(sizeof($travelRequests->toArray())>0){ ?>
<div class="portlet light bordered">
	<div style="font-size:16px;">
		<span class="caption-subject font-purple-intense ">Pending Travel Requests</span>
	</div>
	<div class="portlet-body">
		<div class="table-scrollable">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Purpose</th>
						<th>Advance Amount</th>
						<th>Travel From Date</th>
						<th>Travel To Date</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=0; foreach ($travelRequests as $travelRequest):  ?>
					<tr>
						<td><?= h(++$i) ?></td>
						<td><?= h($travelRequest->employee->name) ?></td>
						<td><?= h($travelRequest->purpose) ?></td>
						<td><?= h($travelRequest->advance_amt) ?></td>
						<td><?= h(date("d-m-Y",strtotime($travelRequest->travel_from_date))) ?></td>
						<td><?= h(date("d-m-Y",strtotime($travelRequest->travel_to_date))) ?></td>
						<td><?php echo $this->Html->link('Approve',['controller' => 'TravelRequests','action' => 'approve', $travelRequest->id],array('escape'=>false,'class'=>'btn btn-xs blue')); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php }?>