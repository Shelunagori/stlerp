<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $employeeReferenceDetail->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $employeeReferenceDetail->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Employee Reference Details'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="employeeReferenceDetails form large-9 medium-8 columns content">
    <?= $this->Form->create($employeeReferenceDetail) ?>
    <fieldset>
        <legend><?= __('Edit Employee Reference Detail') ?></legend>
        <?php
            echo $this->Form->input('employee_id', ['options' => $employees]);
            echo $this->Form->input('name');
            echo $this->Form->input('mobile');
            echo $this->Form->input('telephone');
            echo $this->Form->input('address');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
