<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Travel Request Row'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Travel Requests'), ['controller' => 'TravelRequests', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Travel Request'), ['controller' => 'TravelRequests', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="travelRequestRows index large-9 medium-8 columns content">
    <h3><?= __('Travel Request Rows') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('travel_request_id') ?></th>
                <th><?= $this->Paginator->sort('party_name') ?></th>
                <th><?= $this->Paginator->sort('destination') ?></th>
                <th><?= $this->Paginator->sort('meeting_person') ?></th>
                <th><?= $this->Paginator->sort('date') ?></th>
                <th><?= $this->Paginator->sort('reporting_time') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($travelRequestRows as $travelRequestRow): ?>
            <tr>
                <td><?= $this->Number->format($travelRequestRow->id) ?></td>
                <td><?= $travelRequestRow->has('travel_request') ? $this->Html->link($travelRequestRow->travel_request->id, ['controller' => 'TravelRequests', 'action' => 'view', $travelRequestRow->travel_request->id]) : '' ?></td>
                <td><?= h($travelRequestRow->party_name) ?></td>
                <td><?= h($travelRequestRow->destination) ?></td>
                <td><?= h($travelRequestRow->meeting_person) ?></td>
                <td><?= h($travelRequestRow->date) ?></td>
                <td><?= h($travelRequestRow->reporting_time) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $travelRequestRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $travelRequestRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $travelRequestRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $travelRequestRow->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
