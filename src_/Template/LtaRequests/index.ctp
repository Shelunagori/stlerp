<div class="portlet box red">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>LEAVE ALLOWANCE REQUESTS
		</div>
	</div>
	<div class="portlet-body">
		<div class="table-scrollable">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Sr</th>
						<th><?= $this->Paginator->sort('employee_id') ?></th>
						<th><?= $this->Paginator->sort('data_of_submission') ?></th>
						<th><?= $this->Paginator->sort('date_of_leave_required_from') ?></th>
						<th><?= $this->Paginator->sort('date_of_leave_required_to') ?></th>
						<th><?= $this->Paginator->sort('proposed_date_of_onward_journey') ?></th>
						<th><?= $this->Paginator->sort('probable_date_of_return_journey') ?></th>
						<th><?= $this->Paginator->sort('place_of_visit') ?></th>
						<th class="actions"><?= __('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $sr=1; foreach ($ltaRequests as $ltaRequest): ?>
					<tr>
						<td><?php echo $sr++; ?></td>
						<td><?= $ltaRequest->has('employee') ? $this->Html->link($ltaRequest->employee->name, ['controller' => 'Employees', 'action' => 'view', $ltaRequest->employee->id]) : '' ?></td>
						<td><?= h($ltaRequest->data_of_submission->format('d-m-Y')) ?></td>
						<td><?= h($ltaRequest->date_of_leave_required_from->format('d-m-Y')) ?></td>
						<td><?= h($ltaRequest->date_of_leave_required_to->format('d-m-Y')) ?></td>
						<td><?= h($ltaRequest->proposed_date_of_onward_journey->format('d-m-Y')) ?></td>
						<td><?= h($ltaRequest->probable_date_of_return_journey->format('d-m-Y')) ?></td>
						<td><?= h($ltaRequest->place_of_visit) ?></td>
						<td class="actions">
							<?= $this->Html->link(__('View'), ['action' => 'view', $ltaRequest->id]) ?>
							<?= $this->Html->link(__('Edit'), ['action' => 'edit', $ltaRequest->id]) ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div class="paginator">
			<ul class="pagination">
				<?= $this->Paginator->prev('< ' . __('previous')) ?>
				<?= $this->Paginator->numbers() ?>
				<?= $this->Paginator->next(__('next') . ' >') ?>
			</ul>
			<p><?= $this->Paginator->counter() ?></p>
		</div>
	</div>
</div>
