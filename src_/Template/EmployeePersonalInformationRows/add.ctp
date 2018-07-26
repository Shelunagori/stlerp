<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Employee Personal Information Rows'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="employeePersonalInformationRows form large-9 medium-8 columns content">
    <?= $this->Form->create($employeePersonalInformationRow) ?>
    <fieldset>
        <legend><?= __('Add Employee Personal Information Row') ?></legend>
        <?php
            echo $this->Form->input('emp_personal_information_id');
            echo $this->Form->input('detail_type');
            echo $this->Form->input('name');
            echo $this->Form->input('dob');
            echo $this->Form->input('address');
            echo $this->Form->input('mobile_no');
            echo $this->Form->input('phone_no');
            echo $this->Form->input('relation');
            echo $this->Form->input('dependent');
            echo $this->Form->input('whether_employed');
            echo $this->Form->input('period');
            echo $this->Form->input('company_name');
            echo $this->Form->input('designation');
            echo $this->Form->input('duties_nature');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
