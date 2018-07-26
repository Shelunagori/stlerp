<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Employee Work Experience'), ['action' => 'edit', $employeeWorkExperience->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Employee Work Experience'), ['action' => 'delete', $employeeWorkExperience->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeWorkExperience->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Employee Work Experiences'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee Work Experience'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="employeeWorkExperiences view large-9 medium-8 columns content">
    <h3><?= h($employeeWorkExperience->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Employee') ?></th>
            <td><?= $employeeWorkExperience->has('employee') ? $this->Html->link($employeeWorkExperience->employee->name, ['controller' => 'Employees', 'action' => 'view', $employeeWorkExperience->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Period') ?></th>
            <td><?= h($employeeWorkExperience->period) ?></td>
        </tr>
        <tr>
            <th><?= __('Company Name') ?></th>
            <td><?= h($employeeWorkExperience->company_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Designation') ?></th>
            <td><?= h($employeeWorkExperience->designation) ?></td>
        </tr>
        <tr>
            <th><?= __('Nature Of The Duties') ?></th>
            <td><?= h($employeeWorkExperience->nature_of_the_duties) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($employeeWorkExperience->id) ?></td>
        </tr>
    </table>
</div>
