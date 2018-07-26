<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Salary'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Employee Salary Divisions'), ['controller' => 'EmployeeSalaryDivisions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee Salary Division'), ['controller' => 'EmployeeSalaryDivisions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="salaries index large-9 medium-8 columns content">
    <h3><?= __('Salaries') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('employee_id') ?></th>
                <th><?= $this->Paginator->sort('company_id') ?></th>
                <th><?= $this->Paginator->sort('employee_salary_division_id') ?></th>
                <th><?= $this->Paginator->sort('amount') ?></th>
                <th><?= $this->Paginator->sort('loan_amount') ?></th>
                <th><?= $this->Paginator->sort('other_amount') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($salaries as $salary): ?>
            <tr>
                <td><?= $this->Number->format($salary->id) ?></td>
                <td><?= $salary->has('employee') ? $this->Html->link($salary->employee->name, ['controller' => 'Employees', 'action' => 'view', $salary->employee->id]) : '' ?></td>
                <td><?= $salary->has('company') ? $this->Html->link($salary->company->name, ['controller' => 'Companies', 'action' => 'view', $salary->company->id]) : '' ?></td>
                <td><?= $salary->has('employee_salary_division') ? $this->Html->link($salary->employee_salary_division->name, ['controller' => 'EmployeeSalaryDivisions', 'action' => 'view', $salary->employee_salary_division->id]) : '' ?></td>
                <td><?= $this->Number->format($salary->amount) ?></td>
                <td><?= $this->Number->format($salary->loan_amount) ?></td>
                <td><?= $this->Number->format($salary->other_amount) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $salary->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $salary->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $salary->id], ['confirm' => __('Are you sure you want to delete # {0}?', $salary->id)]) ?>
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
