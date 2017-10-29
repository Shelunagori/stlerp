<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Iv Row'), ['action' => 'edit', $ivRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Iv Row'), ['action' => 'delete', $ivRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ivRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Iv Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Iv Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Ivs'), ['controller' => 'Ivs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Iv'), ['controller' => 'Ivs', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Invoice Rows'), ['controller' => 'InvoiceRows', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Invoice Row'), ['controller' => 'InvoiceRows', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Iv Row Items'), ['controller' => 'IvRowItems', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Iv Row Item'), ['controller' => 'IvRowItems', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Serial Numbers'), ['controller' => 'SerialNumbers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Serial Number'), ['controller' => 'SerialNumbers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="ivRows view large-9 medium-8 columns content">
    <h3><?= h($ivRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Iv') ?></th>
            <td><?= $ivRow->has('iv') ? $this->Html->link($ivRow->iv->id, ['controller' => 'Ivs', 'action' => 'view', $ivRow->iv->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Invoice Row') ?></th>
            <td><?= $ivRow->has('invoice_row') ? $this->Html->link($ivRow->invoice_row->id, ['controller' => 'InvoiceRows', 'action' => 'view', $ivRow->invoice_row->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Item') ?></th>
            <td><?= $ivRow->has('item') ? $this->Html->link($ivRow->item->name, ['controller' => 'Items', 'action' => 'view', $ivRow->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ivRow->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($ivRow->quantity) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Iv Row Items') ?></h4>
        <?php if (!empty($ivRow->iv_row_items)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Iv Row Id') ?></th>
                <th><?= __('Item Id') ?></th>
                <th><?= __('Quantity') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ivRow->iv_row_items as $ivRowItems): ?>
            <tr>
                <td><?= h($ivRowItems->id) ?></td>
                <td><?= h($ivRowItems->iv_row_id) ?></td>
                <td><?= h($ivRowItems->item_id) ?></td>
                <td><?= h($ivRowItems->quantity) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'IvRowItems', 'action' => 'view', $ivRowItems->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'IvRowItems', 'action' => 'edit', $ivRowItems->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'IvRowItems', 'action' => 'delete', $ivRowItems->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ivRowItems->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Serial Numbers') ?></h4>
        <?php if (!empty($ivRow->serial_numbers)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Name') ?></th>
                <th><?= __('Item Id') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Iv Row Id') ?></th>
                <th><?= __('Iv Row Items') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ivRow->serial_numbers as $serialNumbers): ?>
            <tr>
                <td><?= h($serialNumbers->id) ?></td>
                <td><?= h($serialNumbers->name) ?></td>
                <td><?= h($serialNumbers->item_id) ?></td>
                <td><?= h($serialNumbers->status) ?></td>
                <td><?= h($serialNumbers->iv_row_id) ?></td>
                <td><?= h($serialNumbers->iv_row_items) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'SerialNumbers', 'action' => 'view', $serialNumbers->]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'SerialNumbers', 'action' => 'edit', $serialNumbers->]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'SerialNumbers', 'action' => 'delete', $serialNumbers->], ['confirm' => __('Are you sure you want to delete # {0}?', $serialNumbers->)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
