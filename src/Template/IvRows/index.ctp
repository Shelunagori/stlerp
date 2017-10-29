<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Iv Row'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ivs'), ['controller' => 'Ivs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Iv'), ['controller' => 'Ivs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Invoice Rows'), ['controller' => 'InvoiceRows', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Invoice Row'), ['controller' => 'InvoiceRows', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Iv Row Items'), ['controller' => 'IvRowItems', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Iv Row Item'), ['controller' => 'IvRowItems', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Serial Numbers'), ['controller' => 'SerialNumbers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Serial Number'), ['controller' => 'SerialNumbers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="ivRows index large-9 medium-8 columns content">
    <h3><?= __('Iv Rows') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('iv_id') ?></th>
                <th><?= $this->Paginator->sort('invoice_row_id') ?></th>
                <th><?= $this->Paginator->sort('item_id') ?></th>
                <th><?= $this->Paginator->sort('quantity') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ivRows as $ivRow): ?>
            <tr>
                <td><?= $this->Number->format($ivRow->id) ?></td>
                <td><?= $ivRow->has('iv') ? $this->Html->link($ivRow->iv->id, ['controller' => 'Ivs', 'action' => 'view', $ivRow->iv->id]) : '' ?></td>
                <td><?= $ivRow->has('invoice_row') ? $this->Html->link($ivRow->invoice_row->id, ['controller' => 'InvoiceRows', 'action' => 'view', $ivRow->invoice_row->id]) : '' ?></td>
                <td><?= $ivRow->has('item') ? $this->Html->link($ivRow->item->name, ['controller' => 'Items', 'action' => 'view', $ivRow->item->id]) : '' ?></td>
                <td><?= $this->Number->format($ivRow->quantity) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $ivRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $ivRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $ivRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ivRow->id)]) ?>
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
