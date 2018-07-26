<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Travel Request Rows'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Travel Requests'), ['controller' => 'TravelRequests', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Travel Request'), ['controller' => 'TravelRequests', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="travelRequestRows form large-9 medium-8 columns content">
    <?= $this->Form->create($travelRequestRow) ?>
    <fieldset>
        <legend><?= __('Add Travel Request Row') ?></legend>
        <?php
            echo $this->Form->input('travel_request_id', ['options' => $travelRequests]);
            echo $this->Form->input('party_name');
            echo $this->Form->input('destination');
            echo $this->Form->input('meeting_person');
            echo $this->Form->input('date');
            echo $this->Form->input('reporting_time');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
