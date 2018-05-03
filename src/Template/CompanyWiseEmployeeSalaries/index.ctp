<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Company Wise Employee Salary'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="companyWiseEmployeeSalaries index large-9 medium-8 columns content">
    <h3><?= __('Company Wise Employee Salaries') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('employee_id') ?></th>
                <th><?= $this->Paginator->sort('company_id') ?></th>
                <th><?= $this->Paginator->sort('from_date') ?></th>
                <th><?= $this->Paginator->sort('to_date') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($companyWiseEmployeeSalaries as $companyWiseEmployeeSalary): ?>
            <tr>
                <td><?= $this->Number->format($companyWiseEmployeeSalary->id) ?></td>
                <td><?= $companyWiseEmployeeSalary->has('employee') ? $this->Html->link($companyWiseEmployeeSalary->employee->name, ['controller' => 'Employees', 'action' => 'view', $companyWiseEmployeeSalary->employee->id]) : '' ?></td>
                <td><?= $companyWiseEmployeeSalary->has('company') ? $this->Html->link($companyWiseEmployeeSalary->company->name, ['controller' => 'Companies', 'action' => 'view', $companyWiseEmployeeSalary->company->id]) : '' ?></td>
                <td><?= h($companyWiseEmployeeSalary->from_date) ?></td>
                <td><?= h($companyWiseEmployeeSalary->to_date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $companyWiseEmployeeSalary->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $companyWiseEmployeeSalary->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $companyWiseEmployeeSalary->id], ['confirm' => __('Are you sure you want to delete # {0}?', $companyWiseEmployeeSalary->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
