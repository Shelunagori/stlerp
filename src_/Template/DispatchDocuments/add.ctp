<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Dispatch Documents'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="dispatchDocuments form large-9 medium-8 columns content">
    <?= $this->Form->create($dispatchDocument) ?>
    <fieldset>
        <legend><?= __('Add Dispatch Document') ?></legend>
        <?php
            echo $this->Form->input('text_line');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
