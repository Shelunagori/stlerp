<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Employee Record'), ['action' => 'edit', $employeeRecord->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Employee Record'), ['action' => 'delete', $employeeRecord->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeRecord->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Employee Records'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee Record'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="employeeRecords view large-9 medium-8 columns content">
    <h3><?= h($employeeRecord->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Employee') ?></th>
            <td><?= $employeeRecord->has('employee') ? $this->Html->link($employeeRecord->employee->name, ['controller' => 'Employees', 'action' => 'view', $employeeRecord->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($employeeRecord->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Amount') ?></th>
            <td><?= $this->Number->format($employeeRecord->amount) ?></td>
        </tr>
        <tr>
            <th><?= __('Total Attenence') ?></th>
            <td><?= $this->Number->format($employeeRecord->total_attenence) ?></td>
        </tr>
        <tr>
            <th><?= __('Overtime') ?></th>
            <td><?= $this->Number->format($employeeRecord->overtime) ?></td>
        </tr>
        <tr>
            <th><?= __('Month Year') ?></th>
            <td><?= h($employeeRecord->month_year) ?></td>
        </tr>
        <tr>
            <th><?= __('Create Date') ?></th>
            <td><?= h($employeeRecord->create_date) ?></td>
        </tr>
    </table>
</div>
