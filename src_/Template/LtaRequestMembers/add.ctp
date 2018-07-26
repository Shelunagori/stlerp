<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Lta Request Members'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Lta Requests'), ['controller' => 'LtaRequests', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Lta Request'), ['controller' => 'LtaRequests', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="ltaRequestMembers form large-9 medium-8 columns content">
    <?= $this->Form->create($ltaRequestMember) ?>
    <fieldset>
        <legend><?= __('Add Lta Request Member') ?></legend>
        <?php
            echo $this->Form->input('lta_request_id', ['options' => $ltaRequests]);
            echo $this->Form->input('name');
            echo $this->Form->input('age');
            echo $this->Form->input('relation');
            echo $this->Form->input('whether_dependent');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
