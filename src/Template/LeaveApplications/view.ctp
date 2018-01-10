<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Leave Application'), ['action' => 'edit', $leaveApplication->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Leave Application'), ['action' => 'delete', $leaveApplication->id], ['confirm' => __('Are you sure you want to delete # {0}?', $leaveApplication->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Leave Applications'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Leave Application'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="leaveApplications view large-9 medium-8 columns content">
    <h3><?= h($leaveApplication->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($leaveApplication->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Day No') ?></th>
            <td><?= h($leaveApplication->day_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Leave Type') ?></th>
            <td><?= h($leaveApplication->leave_type) ?></td>
        </tr>
        <tr>
            <th><?= __('Supporting Attached') ?></th>
            <td><?= h($leaveApplication->supporting_attached) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($leaveApplication->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Submission Date') ?></th>
            <td><?= h($leaveApplication->submission_date) ?></td>
        </tr>
        <tr>
            <th><?= __('From Leave Date') ?></th>
            <td><?= h($leaveApplication->from_leave_date) ?></td>
        </tr>
        <tr>
            <th><?= __('To Leave Date') ?></th>
            <td><?= h($leaveApplication->to_leave_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Create Date') ?></th>
            <td><?= h($leaveApplication->create_date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Leave Reason') ?></h4>
        <?= $this->Text->autoParagraph(h($leaveApplication->leave_reason)); ?>
    </div>
</div>
