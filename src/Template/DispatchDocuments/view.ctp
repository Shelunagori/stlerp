<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Dispatch Document'), ['action' => 'edit', $dispatchDocument->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Dispatch Document'), ['action' => 'delete', $dispatchDocument->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dispatchDocument->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Dispatch Documents'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Dispatch Document'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="dispatchDocuments view large-9 medium-8 columns content">
    <h3><?= h($dispatchDocument->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Text Line') ?></th>
            <td><?= h($dispatchDocument->text_line) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($dispatchDocument->id) ?></td>
        </tr>
    </table>
</div>
