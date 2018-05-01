<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $employeeFamilyMember->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $employeeFamilyMember->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Employee Family Members'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="employeeFamilyMembers form large-9 medium-8 columns content">
    <?= $this->Form->create($employeeFamilyMember) ?>
    <fieldset>
        <legend><?= __('Edit Employee Family Member') ?></legend>
        <?php
            echo $this->Form->input('employee_id', ['options' => $employees]);
            echo $this->Form->input('member_name');
            echo $this->Form->input('relationship');
            echo $this->Form->input('mobile');
            echo $this->Form->input('telephone');
            echo $this->Form->input('address');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
