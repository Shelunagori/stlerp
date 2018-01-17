<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Employee Record'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="employeeRecords index large-9 medium-8 columns content">
    <h3><?= __('Employee Records') ?></h3>
    <table cellpadding="0" cellspacing="0">
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
