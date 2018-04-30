<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $employeeSalaryDivision->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $employeeSalaryDivision->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Employee Salary Divisions'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="employeeSalaryDivisions form large-9 medium-8 columns content">
    <?= $this->Form->create($employeeSalaryDivision) ?>
    <fieldset>
        <legend><?= __('Edit Employee Salary Division') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('salary_type');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
