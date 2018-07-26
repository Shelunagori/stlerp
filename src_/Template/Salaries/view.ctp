<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Salary'), ['action' => 'edit', $salary->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Salary'), ['action' => 'delete', $salary->id], ['confirm' => __('Are you sure you want to delete # {0}?', $salary->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Salaries'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Salary'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employee Salary Divisions'), ['controller' => 'EmployeeSalaryDivisions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee Salary Division'), ['controller' => 'EmployeeSalaryDivisions', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="salaries view large-9 medium-8 columns content">
    <h3><?= h($salary->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Employee') ?></th>
            <td><?= $salary->has('employee') ? $this->Html->link($salary->employee->name, ['controller' => 'Employees', 'action' => 'view', $salary->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Company') ?></th>
            <td><?= $salary->has('company') ? $this->Html->link($salary->company->name, ['controller' => 'Companies', 'action' => 'view', $salary->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Employee Salary Division') ?></th>
            <td><?= $salary->has('employee_salary_division') ? $this->Html->link($salary->employee_salary_division->name, ['controller' => 'EmployeeSalaryDivisions', 'action' => 'view', $salary->employee_salary_division->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($salary->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Amount') ?></th>
            <td><?= $this->Number->format($salary->amount) ?></td>
        </tr>
        <tr>
            <th><?= __('Loan Amount') ?></th>
            <td><?= $this->Number->format($salary->loan_amount) ?></td>
        </tr>
        <tr>
            <th><?= __('Other Amount') ?></th>
            <td><?= $this->Number->format($salary->other_amount) ?></td>
        </tr>
    </table>
</div>
