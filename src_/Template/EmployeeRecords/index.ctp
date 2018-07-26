<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel ucfirst">Employee Records</span> 
		</div>
		<div class="actions">
			
			
		</div>	
	
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
				<form method="GET" >
				
				</form>
				<?php $page_no=$this->Paginator->current('EmployeeRecordss'); $page_no=($page_no-1)*20; 
					
				?>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th><?= $this->Paginator->sort('id') ?></th>
							<th><?= $this->Paginator->sort('employee_id') ?></th>
							<th><?= $this->Paginator->sort('amount') ?></th>
							<th><?= $this->Paginator->sort('total_attenence') ?></th>
							<th><?= $this->Paginator->sort('overtime') ?></th>
							<th><?= $this->Paginator->sort('month_year') ?></th>
							<th><?= $this->Paginator->sort('create_date') ?></th>
							<th class="actions"><?= __('Actions') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($employeeRecords as $employeeRecord): ?>
						<tr>
							<td><?= $this->Number->format($employeeRecord->id) ?></td>
							<td><?= $employeeRecord->has('employee') ? $this->Html->link($employeeRecord->employee->name, ['controller' => 'Employees', 'action' => 'view', $employeeRecord->employee->id]) : '' ?></td>
							<td><?= $this->Number->format($employeeRecord->amount) ?></td>
							<td><?= $this->Number->format($employeeRecord->total_attenence) ?></td>
							<td><?= $this->Number->format($employeeRecord->overtime) ?></td>
							<td><?= h($employeeRecord->month_year) ?></td>
							<td><?= h($employeeRecord->create_date) ?></td>
							<td class="actions">
								<?= $this->Html->link(__('View'), ['action' => 'view', $employeeRecord->id]) ?>
								<?= $this->Html->link(__('Edit'), ['action' => 'edit', $employeeRecord->id]) ?>
								<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $employeeRecord->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeRecord->id)]) ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
