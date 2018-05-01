<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Employee Emergency Detail'), ['action' => 'edit', $employeeEmergencyDetail->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Employee Emergency Detail'), ['action' => 'delete', $employeeEmergencyDetail->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeEmergencyDetail->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Employee Emergency Details'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee Emergency Detail'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="employeeEmergencyDetails view large-9 medium-8 columns content">
    <h3><?= h($employeeEmergencyDetail->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Employee') ?></th>
            <td><?= $employeeEmergencyDetail->has('employee') ? $this->Html->link($employeeEmergencyDetail->employee->name, ['controller' => 'Employees', 'action' => 'view', $employeeEmergencyDetail->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($employeeEmergencyDetail->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Relationship') ?></th>
            <td><?= h($employeeEmergencyDetail->relationship) ?></td>
        </tr>
        <tr>
            <th><?= __('Mobile') ?></th>
            <td><?= h($employeeEmergencyDetail->mobile) ?></td>
        </tr>
        <tr>
            <th><?= __('Telephone') ?></th>
            <td><?= h($employeeEmergencyDetail->telephone) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($employeeEmergencyDetail->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Address') ?></h4>
        <?= $this->Text->autoParagraph(h($employeeEmergencyDetail->address)); ?>
    </div>
</div>
