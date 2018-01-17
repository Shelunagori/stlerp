<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Salary Advance'), ['action' => 'edit', $salaryAdvance->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Salary Advance'), ['action' => 'delete', $salaryAdvance->id], ['confirm' => __('Are you sure you want to delete # {0}?', $salaryAdvance->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Salary Advances'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Salary Advance'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="salaryAdvances view large-9 medium-8 columns content">
    <h3><?= h($salaryAdvance->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Employee Name') ?></th>
            <td><?= h($salaryAdvance->employee_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($salaryAdvance->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Amount') ?></th>
            <td><?= $this->Number->format($salaryAdvance->amount) ?></td>
        </tr>
        <tr>
            <th><?= __('Create Date') ?></th>
            <td><?= h($salaryAdvance->create_date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Reason') ?></h4>
        <?= $this->Text->autoParagraph(h($salaryAdvance->reason)); ?>
    </div>
</div>
