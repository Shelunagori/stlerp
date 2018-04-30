<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $employeeAttendance->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $employeeAttendance->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Employee Attendances'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Financial Years'), ['controller' => 'FinancialYears', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Financial Year'), ['controller' => 'FinancialYears', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="employeeAttendances form large-9 medium-8 columns content">
    <?= $this->Form->create($employeeAttendance) ?>
    <fieldset>
        <legend><?= __('Edit Employee Attendance') ?></legend>
        <?php
            echo $this->Form->input('financial_year_id', ['options' => $financialYears]);
            echo $this->Form->input('month');
            echo $this->Form->input('employee_id', ['options' => $employees]);
            echo $this->Form->input('total_month_day');
            echo $this->Form->input('no_of_leave');
            echo $this->Form->input('present_day');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
