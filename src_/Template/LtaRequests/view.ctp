<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Lta Request'), ['action' => 'edit', $ltaRequest->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Lta Request'), ['action' => 'delete', $ltaRequest->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ltaRequest->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Lta Requests'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Lta Request'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Lta Request Members'), ['controller' => 'LtaRequestMembers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Lta Request Member'), ['controller' => 'LtaRequestMembers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="ltaRequests view large-9 medium-8 columns content">
    <h3><?= h($ltaRequest->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Employee') ?></th>
            <td><?= $ltaRequest->has('employee') ? $this->Html->link($ltaRequest->employee->name, ['controller' => 'Employees', 'action' => 'view', $ltaRequest->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Place Of Visit') ?></th>
            <td><?= h($ltaRequest->place_of_visit) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ltaRequest->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Data Of Submission') ?></th>
            <td><?= h($ltaRequest->data_of_submission) ?></td>
        </tr>
        <tr>
            <th><?= __('Date Of Leave Required From') ?></th>
            <td><?= h($ltaRequest->date of_leave_required_from) ?></td>
        </tr>
        <tr>
            <th><?= __('Date Of Leave Required To') ?></th>
            <td><?= h($ltaRequest->date of_leave_required_to) ?></td>
        </tr>
        <tr>
            <th><?= __('Proposed Date Of Onward Journey') ?></th>
            <td><?= h($ltaRequest->proposed_date_of_onward_journey) ?></td>
        </tr>
        <tr>
            <th><?= __('Probable Date Of Return Journey') ?></th>
            <td><?= h($ltaRequest->probable_date_of_return_journey) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Particulars Of Ltc Availed For Block Year') ?></h4>
        <?= $this->Text->autoParagraph(h($ltaRequest->particulars_of_ltc_availed_for_block_year)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Lta Request Members') ?></h4>
        <?php if (!empty($ltaRequest->lta_request_members)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Lta Request Id') ?></th>
                <th><?= __('Name') ?></th>
                <th><?= __('Age') ?></th>
                <th><?= __('Relation') ?></th>
                <th><?= __('Whether Dependent') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ltaRequest->lta_request_members as $ltaRequestMembers): ?>
            <tr>
                <td><?= h($ltaRequestMembers->id) ?></td>
                <td><?= h($ltaRequestMembers->lta_request_id) ?></td>
                <td><?= h($ltaRequestMembers->name) ?></td>
                <td><?= h($ltaRequestMembers->age) ?></td>
                <td><?= h($ltaRequestMembers->relation) ?></td>
                <td><?= h($ltaRequestMembers->whether_dependent) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'LtaRequestMembers', 'action' => 'view', $ltaRequestMembers->]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'LtaRequestMembers', 'action' => 'edit', $ltaRequestMembers->]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'LtaRequestMembers', 'action' => 'delete', $ltaRequestMembers->], ['confirm' => __('Are you sure you want to delete # {0}?', $ltaRequestMembers->)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
