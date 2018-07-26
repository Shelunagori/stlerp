<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Lta Request Member'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Lta Requests'), ['controller' => 'LtaRequests', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Lta Request'), ['controller' => 'LtaRequests', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="ltaRequestMembers index large-9 medium-8 columns content">
    <h3><?= __('Lta Request Members') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('lta_request_id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('age') ?></th>
                <th><?= $this->Paginator->sort('relation') ?></th>
                <th><?= $this->Paginator->sort('whether_dependent') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ltaRequestMembers as $ltaRequestMember): ?>
            <tr>
                <td><?= $this->Number->format($ltaRequestMember->id) ?></td>
                <td><?= $ltaRequestMember->has('lta_request') ? $this->Html->link($ltaRequestMember->lta_request->id, ['controller' => 'LtaRequests', 'action' => 'view', $ltaRequestMember->lta_request->id]) : '' ?></td>
                <td><?= h($ltaRequestMember->name) ?></td>
                <td><?= h($ltaRequestMember->age) ?></td>
                <td><?= h($ltaRequestMember->relation) ?></td>
                <td><?= h($ltaRequestMember->whether_dependent) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $ltaRequestMember->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $ltaRequestMember->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $ltaRequestMember->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ltaRequestMember->id)]) ?>
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
