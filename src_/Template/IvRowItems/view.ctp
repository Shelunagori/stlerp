<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Iv Row Item'), ['action' => 'edit', $ivRowItem->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Iv Row Item'), ['action' => 'delete', $ivRowItem->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ivRowItem->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Iv Row Items'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Iv Row Item'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Iv Rows'), ['controller' => 'IvRows', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Iv Row'), ['controller' => 'IvRows', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="ivRowItems view large-9 medium-8 columns content">
    <h3><?= h($ivRowItem->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Iv Row') ?></th>
            <td><?= $ivRowItem->has('iv_row') ? $this->Html->link($ivRowItem->iv_row->id, ['controller' => 'IvRows', 'action' => 'view', $ivRowItem->iv_row->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Item') ?></th>
            <td><?= $ivRowItem->has('item') ? $this->Html->link($ivRowItem->item->name, ['controller' => 'Items', 'action' => 'view', $ivRowItem->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ivRowItem->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($ivRowItem->quantity) ?></td>
        </tr>
    </table>
</div>
