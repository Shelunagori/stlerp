<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Iv Row Item'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Iv Rows'), ['controller' => 'IvRows', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Iv Row'), ['controller' => 'IvRows', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="ivRowItems index large-9 medium-8 columns content">
    <h3><?= __('Iv Row Items') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('iv_row_id') ?></th>
                <th><?= $this->Paginator->sort('item_id') ?></th>
                <th><?= $this->Paginator->sort('quantity') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ivRowItems as $ivRowItem): ?>
            <tr>
                <td><?= $this->Number->format($ivRowItem->id) ?></td>
                <td><?= $ivRowItem->has('iv_row') ? $this->Html->link($ivRowItem->iv_row->id, ['controller' => 'IvRows', 'action' => 'view', $ivRowItem->iv_row->id]) : '' ?></td>
                <td><?= $ivRowItem->has('item') ? $this->Html->link($ivRowItem->item->name, ['controller' => 'Items', 'action' => 'view', $ivRowItem->item->id]) : '' ?></td>
                <td><?= $this->Number->format($ivRowItem->quantity) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $ivRowItem->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $ivRowItem->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $ivRowItem->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ivRowItem->id)]) ?>
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
