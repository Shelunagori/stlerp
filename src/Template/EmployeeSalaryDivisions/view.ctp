<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Employee Salary Division'), ['action' => 'edit', $employeeSalaryDivision->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Employee Salary Division'), ['action' => 'delete', $employeeSalaryDivision->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeSalaryDivision->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Employee Salary Divisions'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee Salary Division'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="employeeSalaryDivisions view large-9 medium-8 columns content">
    <h3><?= h($employeeSalaryDivision->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($employeeSalaryDivision->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Salary Type') ?></th>
            <td><?= h($employeeSalaryDivision->salary_type) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($employeeSalaryDivision->id) ?></td>
        </tr>
    </table>
</div>
