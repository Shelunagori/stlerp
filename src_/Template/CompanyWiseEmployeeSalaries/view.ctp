<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Company Wise Employee Salary'), ['action' => 'edit', $companyWiseEmployeeSalary->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Company Wise Employee Salary'), ['action' => 'delete', $companyWiseEmployeeSalary->id], ['confirm' => __('Are you sure you want to delete # {0}?', $companyWiseEmployeeSalary->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Company Wise Employee Salaries'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company Wise Employee Salary'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="companyWiseEmployeeSalaries view large-9 medium-8 columns content">
    <h3><?= h($companyWiseEmployeeSalary->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Employee') ?></th>
            <td><?= $companyWiseEmployeeSalary->has('employee') ? $this->Html->link($companyWiseEmployeeSalary->employee->name, ['controller' => 'Employees', 'action' => 'view', $companyWiseEmployeeSalary->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Company') ?></th>
            <td><?= $companyWiseEmployeeSalary->has('company') ? $this->Html->link($companyWiseEmployeeSalary->company->name, ['controller' => 'Companies', 'action' => 'view', $companyWiseEmployeeSalary->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($companyWiseEmployeeSalary->id) ?></td>
        </tr>
        <tr>
            <th><?= __('From Date') ?></th>
            <td><?= h($companyWiseEmployeeSalary->from_date) ?></td>
        </tr>
        <tr>
            <th><?= __('To Date') ?></th>
            <td><?= h($companyWiseEmployeeSalary->to_date) ?></td>
        </tr>
    </table>
</div>
