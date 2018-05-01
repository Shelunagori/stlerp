<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Employee Family Member'), ['action' => 'edit', $employeeFamilyMember->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Employee Family Member'), ['action' => 'delete', $employeeFamilyMember->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeFamilyMember->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Employee Family Members'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee Family Member'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="employeeFamilyMembers view large-9 medium-8 columns content">
    <h3><?= h($employeeFamilyMember->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Employee') ?></th>
            <td><?= $employeeFamilyMember->has('employee') ? $this->Html->link($employeeFamilyMember->employee->name, ['controller' => 'Employees', 'action' => 'view', $employeeFamilyMember->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Member Name') ?></th>
            <td><?= h($employeeFamilyMember->member_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Relationship') ?></th>
            <td><?= h($employeeFamilyMember->relationship) ?></td>
        </tr>
        <tr>
            <th><?= __('Mobile') ?></th>
            <td><?= h($employeeFamilyMember->mobile) ?></td>
        </tr>
        <tr>
            <th><?= __('Telephone') ?></th>
            <td><?= h($employeeFamilyMember->telephone) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($employeeFamilyMember->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Address') ?></h4>
        <?= $this->Text->autoParagraph(h($employeeFamilyMember->address)); ?>
    </div>
</div>
