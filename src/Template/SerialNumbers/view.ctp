<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Serial Number'), ['action' => 'edit', $serialNumber->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Serial Number'), ['action' => 'delete', $serialNumber->id], ['confirm' => __('Are you sure you want to delete # {0}?', $serialNumber->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Serial Numbers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Serial Number'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Iv Rows'), ['controller' => 'IvRows', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Iv Row'), ['controller' => 'IvRows', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="serialNumbers view large-9 medium-8 columns content">
    <h3><?= h($serialNumber->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($serialNumber->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Item') ?></th>
            <td><?= $serialNumber->has('item') ? $this->Html->link($serialNumber->item->name, ['controller' => 'Items', 'action' => 'view', $serialNumber->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= h($serialNumber->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Iv Row') ?></th>
            <td><?= $serialNumber->has('iv_row') ? $this->Html->link($serialNumber->iv_row->id, ['controller' => 'IvRows', 'action' => 'view', $serialNumber->iv_row->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($serialNumber->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Iv Row Item Id') ?></th>
            <td><?= $this->Number->format($serialNumber->iv_row_item_id) ?></td>
        </tr>
    </table>
</div>
