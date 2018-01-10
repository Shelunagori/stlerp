<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Travel Request Row'), ['action' => 'edit', $travelRequestRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Travel Request Row'), ['action' => 'delete', $travelRequestRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $travelRequestRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Travel Request Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Travel Request Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Travel Requests'), ['controller' => 'TravelRequests', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Travel Request'), ['controller' => 'TravelRequests', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="travelRequestRows view large-9 medium-8 columns content">
    <h3><?= h($travelRequestRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Travel Request') ?></th>
            <td><?= $travelRequestRow->has('travel_request') ? $this->Html->link($travelRequestRow->travel_request->id, ['controller' => 'TravelRequests', 'action' => 'view', $travelRequestRow->travel_request->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Party Name') ?></th>
            <td><?= h($travelRequestRow->party_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Destination') ?></th>
            <td><?= h($travelRequestRow->destination) ?></td>
        </tr>
        <tr>
            <th><?= __('Meeting Person') ?></th>
            <td><?= h($travelRequestRow->meeting_person) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($travelRequestRow->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Date') ?></th>
            <td><?= h($travelRequestRow->date) ?></td>
        </tr>
        <tr>
            <th><?= __('Reporting Time') ?></th>
            <td><?= h($travelRequestRow->reporting_time) ?></td>
        </tr>
    </table>
</div>
