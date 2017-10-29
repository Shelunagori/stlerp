<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Serial Number'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Iv Rows'), ['controller' => 'IvRows', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Iv Row'), ['controller' => 'IvRows', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="serialNumbers index large-9 medium-8 columns content">
    <h3><?= __('Serial Numbers') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('item_id') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th><?= $this->Paginator->sort('iv_row_id') ?></th>
                <th><?= $this->Paginator->sort('iv_row_item_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($serialNumbers as $serialNumber): ?>
            <tr>
                <td><?= $this->Number->format($serialNumber->id) ?></td>
                <td><?= h($serialNumber->name) ?></td>
                <td><?= $serialNumber->has('item') ? $this->Html->link($serialNumber->item->name, ['controller' => 'Items', 'action' => 'view', $serialNumber->item->id]) : '' ?></td>
                <td><?= h($serialNumber->status) ?></td>
                <td><?= $serialNumber->has('iv_row') ? $this->Html->link($serialNumber->iv_row->id, ['controller' => 'IvRows', 'action' => 'view', $serialNumber->iv_row->id]) : '' ?></td>
                <td><?= $this->Number->format($serialNumber->iv_row_item_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $serialNumber->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $serialNumber->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $serialNumber->id], ['confirm' => __('Are you sure you want to delete # {0}?', $serialNumber->id)]) ?>
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
