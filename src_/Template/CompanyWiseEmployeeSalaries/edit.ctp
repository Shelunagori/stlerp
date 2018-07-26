<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $companyWiseEmployeeSalary->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $companyWiseEmployeeSalary->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Company Wise Employee Salaries'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="companyWiseEmployeeSalaries form large-9 medium-8 columns content">
    <?= $this->Form->create($companyWiseEmployeeSalary) ?>
    <fieldset>
        <legend><?= __('Edit Company Wise Employee Salary') ?></legend>
        <?php
            echo $this->Form->input('employee_id', ['options' => $employees]);
            echo $this->Form->input('company_id', ['options' => $companies]);
            echo $this->Form->input('from_date');
            echo $this->Form->input('to_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
