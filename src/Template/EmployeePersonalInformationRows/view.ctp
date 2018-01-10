<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Employee Personal Information Row'), ['action' => 'edit', $employeePersonalInformationRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Employee Personal Information Row'), ['action' => 'delete', $employeePersonalInformationRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeePersonalInformationRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Employee Personal Information Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee Personal Information Row'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="employeePersonalInformationRows view large-9 medium-8 columns content">
    <h3><?= h($employeePersonalInformationRow->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Detail Type') ?></th>
            <td><?= h($employeePersonalInformationRow->detail_type) ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($employeePersonalInformationRow->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Mobile No') ?></th>
            <td><?= h($employeePersonalInformationRow->mobile_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Phone No') ?></th>
            <td><?= h($employeePersonalInformationRow->phone_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Relation') ?></th>
            <td><?= h($employeePersonalInformationRow->relation) ?></td>
        </tr>
        <tr>
            <th><?= __('Dependent') ?></th>
            <td><?= h($employeePersonalInformationRow->dependent) ?></td>
        </tr>
        <tr>
            <th><?= __('Whether Employed') ?></th>
            <td><?= h($employeePersonalInformationRow->whether_employed) ?></td>
        </tr>
        <tr>
            <th><?= __('Period') ?></th>
            <td><?= h($employeePersonalInformationRow->period) ?></td>
        </tr>
        <tr>
            <th><?= __('Company Name') ?></th>
            <td><?= h($employeePersonalInformationRow->company_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Designation') ?></th>
            <td><?= h($employeePersonalInformationRow->designation) ?></td>
        </tr>
        <tr>
            <th><?= __('Duties Nature') ?></th>
            <td><?= h($employeePersonalInformationRow->duties_nature) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($employeePersonalInformationRow->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Emp Personal Information Id') ?></th>
            <td><?= $this->Number->format($employeePersonalInformationRow->emp_personal_information_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Dob') ?></th>
            <td><?= h($employeePersonalInformationRow->dob) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Address') ?></h4>
        <?= $this->Text->autoParagraph(h($employeePersonalInformationRow->address)); ?>
    </div>
</div>
