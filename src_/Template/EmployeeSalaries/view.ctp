<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Employee Salary'), ['action' => 'edit', $employeeSalary->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Employee Salary'), ['action' => 'delete', $employeeSalary->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeSalary->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Employee Salaries'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee Salary'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employee Salary Divisions'), ['controller' => 'EmployeeSalaryDivisions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee Salary Division'), ['controller' => 'EmployeeSalaryDivisions', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="employeeSalaries view large-9 medium-8 columns content">
    <h3><?= h($employeeSalary->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Employee') ?></th>
            <td><?= $employeeSalary->has('employee') ? $this->Html->link($employeeSalary->employee->name, ['controller' => 'Employees', 'action' => 'view', $employeeSalary->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Employee Salary Division') ?></th>
            <td><?= $employeeSalary->has('employee_salary_division') ? $this->Html->link($employeeSalary->employee_salary_division->name, ['controller' => 'EmployeeSalaryDivisions', 'action' => 'view', $employeeSalary->employee_salary_division->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($employeeSalary->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Amount') ?></th>
            <td><?= $this->Number->format($employeeSalary->amount) ?></td>
        </tr>
        <tr>
            <th><?= __('Effective Date') ?></th>
            <td><?= h($employeeSalary->effective_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Created On') ?></th>
            <td><?= h($employeeSalary->created_on) ?></td>
        </tr>
    </table>
</div>
