<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $employeeRecord->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $employeeRecord->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Employee Records'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="employeeRecords form large-9 medium-8 columns content">
    <?= $this->Form->create($employeeRecord) ?>
    <fieldset>
        <legend><?= __('Edit Employee Record') ?></legend>
        <?php
            echo $this->Form->input('employee_id', ['options' => $employees]);
            echo $this->Form->input('amount');
            echo $this->Form->input('total_attenence');
            echo $this->Form->input('overtime');
            echo $this->Form->input('month_year');
            echo $this->Form->input('create_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
