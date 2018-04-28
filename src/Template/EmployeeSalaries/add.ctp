<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Employee Salaries'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Employee Salary Divisions'), ['controller' => 'EmployeeSalaryDivisions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee Salary Division'), ['controller' => 'EmployeeSalaryDivisions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="employeeSalaries form large-9 medium-8 columns content">
    <?= $this->Form->create($employeeSalary) ?>
    <fieldset>
        <legend><?= __('Add Employee Salary') ?></legend>
        <?php
            echo $this->Form->input('employee_id', ['options' => $employees]);
            echo $this->Form->input('employee_salary_division_id', ['options' => $employeeSalaryDivisions]);
            echo $this->Form->input('effective_date');
            echo $this->Form->input('amount');
            echo $this->Form->input('created_on');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
