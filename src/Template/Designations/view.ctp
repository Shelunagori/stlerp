<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Designation'), ['action' => 'edit', $designation->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Designation'), ['action' => 'delete', $designation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $designation->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Designations'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Designation'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="designations view large-9 medium-8 columns content">
    <h3><?= h($designation->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($designation->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($designation->id) ?></td>
        </tr>
    </table>
</div>
