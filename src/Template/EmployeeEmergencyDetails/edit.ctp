<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $employeeEmergencyDetail->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $employeeEmergencyDetail->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Employee Emergency Details'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="employeeEmergencyDetails form large-9 medium-8 columns content">
    <?= $this->Form->create($employeeEmergencyDetail) ?>
    <fieldset>
        <legend><?= __('Edit Employee Emergency Detail') ?></legend>
        <?php
            echo $this->Form->input('employee_id', ['options' => $employees]);
            echo $this->Form->input('name');
            echo $this->Form->input('relationship');
            echo $this->Form->input('mobile');
            echo $this->Form->input('telephone');
            echo $this->Form->input('address');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
