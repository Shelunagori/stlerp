<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Iv Row Items'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Iv Rows'), ['controller' => 'IvRows', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Iv Row'), ['controller' => 'IvRows', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="ivRowItems form large-9 medium-8 columns content">
    <?= $this->Form->create($ivRowItem) ?>
    <fieldset>
        <legend><?= __('Add Iv Row Item') ?></legend>
        <?php
            echo $this->Form->input('iv_row_id', ['options' => $ivRows]);
            echo $this->Form->input('item_id', ['options' => $items]);
            echo $this->Form->input('quantity');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
