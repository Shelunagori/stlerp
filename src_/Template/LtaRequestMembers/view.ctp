<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Lta Request Member'), ['action' => 'edit', $ltaRequestMember->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Lta Request Member'), ['action' => 'delete', $ltaRequestMember->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ltaRequestMember->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Lta Request Members'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Lta Request Member'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Lta Requests'), ['controller' => 'LtaRequests', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Lta Request'), ['controller' => 'LtaRequests', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="ltaRequestMembers view large-9 medium-8 columns content">
    <h3><?= h($ltaRequestMember->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Lta Request') ?></th>
            <td><?= $ltaRequestMember->has('lta_request') ? $this->Html->link($ltaRequestMember->lta_request->id, ['controller' => 'LtaRequests', 'action' => 'view', $ltaRequestMember->lta_request->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($ltaRequestMember->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Age') ?></th>
            <td><?= h($ltaRequestMember->age) ?></td>
        </tr>
        <tr>
            <th><?= __('Relation') ?></th>
            <td><?= h($ltaRequestMember->relation) ?></td>
        </tr>
        <tr>
            <th><?= __('Whether Dependent') ?></th>
            <td><?= h($ltaRequestMember->whether_dependent) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ltaRequestMember->id) ?></td>
        </tr>
    </table>
</div>
