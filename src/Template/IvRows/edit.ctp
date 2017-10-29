<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $ivRow->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $ivRow->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Iv Rows'), ['action' => 'index']) ?></li>
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
<div class="ivRows form large-9 medium-8 columns content">
    <?= $this->Form->create($ivRow) ?>
    <fieldset>
        <legend><?= __('Edit Iv Row') ?></legend>
        <?php
            echo $this->Form->input('iv_id', ['options' => $ivs]);
            echo $this->Form->input('invoice_row_id', ['options' => $invoiceRows]);
            echo $this->Form->input('item_id', ['options' => $items]);
            echo $this->Form->input('quantity');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
