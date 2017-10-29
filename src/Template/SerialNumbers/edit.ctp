<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $serialNumber->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $serialNumber->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Serial Numbers'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Iv Rows'), ['controller' => 'IvRows', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Iv Row'), ['controller' => 'IvRows', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="serialNumbers form large-9 medium-8 columns content">
    <?= $this->Form->create($serialNumber) ?>
    <fieldset>
        <legend><?= __('Edit Serial Number') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('item_id', ['options' => $items]);
            echo $this->Form->input('status');
            echo $this->Form->input('iv_row_id', ['options' => $ivRows]);
            echo $this->Form->input('iv_row_item_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
