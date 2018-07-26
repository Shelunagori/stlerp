<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Employee Attendance'), ['action' => 'edit', $employeeAttendance->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Employee Attendance'), ['action' => 'delete', $employeeAttendance->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeAttendance->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Employee Attendances'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee Attendance'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Financial Years'), ['controller' => 'FinancialYears', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Financial Year'), ['controller' => 'FinancialYears', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="employeeAttendances view large-9 medium-8 columns content">
    <h3><?= h($employeeAttendance->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Financial Year') ?></th>
            <td><?= $employeeAttendance->has('financial_year') ? $this->Html->link($employeeAttendance->financial_year->id, ['controller' => 'FinancialYears', 'action' => 'view', $employeeAttendance->financial_year->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Employee') ?></th>
            <td><?= $employeeAttendance->has('employee') ? $this->Html->link($employeeAttendance->employee->name, ['controller' => 'Employees', 'action' => 'view', $employeeAttendance->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($employeeAttendance->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Month') ?></th>
            <td><?= $this->Number->format($employeeAttendance->month) ?></td>
        </tr>
        <tr>
            <th><?= __('Total Month Day') ?></th>
            <td><?= $this->Number->format($employeeAttendance->total_month_day) ?></td>
        </tr>
        <tr>
            <th><?= __('No Of Leave') ?></th>
            <td><?= $this->Number->format($employeeAttendance->no_of_leave) ?></td>
        </tr>
        <tr>
            <th><?= __('Present Day') ?></th>
            <td><?= $this->Number->format($employeeAttendance->present_day) ?></td>
        </tr>
    </table>
</div>
