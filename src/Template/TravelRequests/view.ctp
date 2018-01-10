<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Travel Request'), ['action' => 'edit', $travelRequest->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Travel Request'), ['action' => 'delete', $travelRequest->id], ['confirm' => __('Are you sure you want to delete # {0}?', $travelRequest->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Travel Requests'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Travel Request'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Travel Request Rows'), ['controller' => 'TravelRequestRows', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Travel Request Row'), ['controller' => 'TravelRequestRows', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="travelRequests view large-9 medium-8 columns content">
    <h3><?= h($travelRequest->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Employee Name') ?></th>
            <td><?= h($travelRequest->employee_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Employee Designation') ?></th>
            <td><?= h($travelRequest->employee_designation) ?></td>
        </tr>
        <tr>
            <th><?= __('Purpose') ?></th>
            <td><?= h($travelRequest->purpose) ?></td>
        </tr>
        <tr>
            <th><?= __('Travel Mode') ?></th>
            <td><?= h($travelRequest->travel_mode) ?></td>
        </tr>
        <tr>
            <th><?= __('Return Travel Mode') ?></th>
            <td><?= h($travelRequest->return_travel_mode) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($travelRequest->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Advance Amt') ?></th>
            <td><?= $this->Number->format($travelRequest->advance_amt) ?></td>
        </tr>
        <tr>
            <th><?= __('Travel From Date') ?></th>
            <td><?= h($travelRequest->travel_from_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Travel To Date') ?></th>
            <td><?= h($travelRequest->travel_to_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Travel Mode From Date') ?></th>
            <td><?= h($travelRequest->travel_mode_from_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Travel Mode To Date') ?></th>
            <td><?= h($travelRequest->travel_mode_to_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Returnl Mode To Date') ?></th>
            <td><?= h($travelRequest->returnl_mode_to_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Returnl Mode From Date') ?></th>
            <td><?= h($travelRequest->returnl_mode_from_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Create Date') ?></th>
            <td><?= h($travelRequest->create_date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Purpose Specification') ?></h4>
        <?= $this->Text->autoParagraph(h($travelRequest->purpose_specification)); ?>
    </div>
    <div class="row">
        <h4><?= __('Other Mode') ?></h4>
        <?= $this->Text->autoParagraph(h($travelRequest->other_mode)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Travel Request Rows') ?></h4>
        <?php if (!empty($travelRequest->travel_request_rows)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Travel Request Id') ?></th>
                <th><?= __('Party Name') ?></th>
                <th><?= __('Destination') ?></th>
                <th><?= __('Meeting Person') ?></th>
                <th><?= __('Date') ?></th>
                <th><?= __('Reporting Time') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($travelRequest->travel_request_rows as $travelRequestRows): ?>
            <tr>
                <td><?= h($travelRequestRows->id) ?></td>
                <td><?= h($travelRequestRows->travel_request_id) ?></td>
                <td><?= h($travelRequestRows->party_name) ?></td>
                <td><?= h($travelRequestRows->destination) ?></td>
                <td><?= h($travelRequestRows->meeting_person) ?></td>
                <td><?= h($travelRequestRows->date) ?></td>
                <td><?= h($travelRequestRows->reporting_time) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'TravelRequestRows', 'action' => 'view', $travelRequestRows->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'TravelRequestRows', 'action' => 'edit', $travelRequestRows->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'TravelRequestRows', 'action' => 'delete', $travelRequestRows->id], ['confirm' => __('Are you sure you want to delete # {0}?', $travelRequestRows->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
