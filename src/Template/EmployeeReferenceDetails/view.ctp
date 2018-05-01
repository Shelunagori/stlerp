<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Employee Reference Detail'), ['action' => 'edit', $employeeReferenceDetail->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Employee Reference Detail'), ['action' => 'delete', $employeeReferenceDetail->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeReferenceDetail->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Employee Reference Details'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee Reference Detail'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="employeeReferenceDetails view large-9 medium-8 columns content">
    <h3><?= h($employeeReferenceDetail->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Employee') ?></th>
            <td><?= $employeeReferenceDetail->has('employee') ? $this->Html->link($employeeReferenceDetail->employee->name, ['controller' => 'Employees', 'action' => 'view', $employeeReferenceDetail->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($employeeReferenceDetail->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Mobile') ?></th>
            <td><?= h($employeeReferenceDetail->mobile) ?></td>
        </tr>
        <tr>
            <th><?= __('Telephone') ?></th>
            <td><?= h($employeeReferenceDetail->telephone) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($employeeReferenceDetail->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Address') ?></h4>
        <?= $this->Text->autoParagraph(h($employeeReferenceDetail->address)); ?>
    </div>
</div>
