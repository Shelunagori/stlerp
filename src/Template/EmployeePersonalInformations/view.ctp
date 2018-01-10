<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Employee Personal Information'), ['action' => 'edit', $employeePersonalInformation->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Employee Personal Information'), ['action' => 'delete', $employeePersonalInformation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeePersonalInformation->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Employee Personal Informations'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee Personal Information'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="employeePersonalInformations view large-9 medium-8 columns content">
    <h3><?= h($employeePersonalInformation->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('First Name') ?></th>
            <td><?= h($employeePersonalInformation->first_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Middle Name') ?></th>
            <td><?= h($employeePersonalInformation->middle_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Last Name') ?></th>
            <td><?= h($employeePersonalInformation->last_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Family Member Type') ?></th>
            <td><?= h($employeePersonalInformation->family_member_type) ?></td>
        </tr>
        <tr>
            <th><?= __('Family Member Name') ?></th>
            <td><?= h($employeePersonalInformation->family_member_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Gender') ?></th>
            <td><?= h($employeePersonalInformation->gender) ?></td>
        </tr>
        <tr>
            <th><?= __('Identity Mark') ?></th>
            <td><?= h($employeePersonalInformation->identity_mark) ?></td>
        </tr>
        <tr>
            <th><?= __('Caste') ?></th>
            <td><?= h($employeePersonalInformation->caste) ?></td>
        </tr>
        <tr>
            <th><?= __('Religion') ?></th>
            <td><?= h($employeePersonalInformation->religion) ?></td>
        </tr>
        <tr>
            <th><?= __('Adhar Card No') ?></th>
            <td><?= h($employeePersonalInformation->adhar_card_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Account Type') ?></th>
            <td><?= h($employeePersonalInformation->account_type) ?></td>
        </tr>
        <tr>
            <th><?= __('Branch Ifsc Code') ?></th>
            <td><?= h($employeePersonalInformation->branch_ifsc_code) ?></td>
        </tr>
        <tr>
            <th><?= __('Martial Status') ?></th>
            <td><?= h($employeePersonalInformation->martial_status) ?></td>
        </tr>
        <tr>
            <th><?= __('Category') ?></th>
            <td><?= h($employeePersonalInformation->category) ?></td>
        </tr>
        <tr>
            <th><?= __('Blood Group') ?></th>
            <td><?= h($employeePersonalInformation->blood_group) ?></td>
        </tr>
        <tr>
            <th><?= __('Driving Licence No') ?></th>
            <td><?= h($employeePersonalInformation->driving_licence_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Pan Card No') ?></th>
            <td><?= h($employeePersonalInformation->pan_card_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Bank Branch') ?></th>
            <td><?= h($employeePersonalInformation->bank_branch) ?></td>
        </tr>
        <tr>
            <th><?= __('Present Pin Code') ?></th>
            <td><?= h($employeePersonalInformation->present_pin_code) ?></td>
        </tr>
        <tr>
            <th><?= __('Present Mobile No') ?></th>
            <td><?= h($employeePersonalInformation->present_mobile_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Present Phone No') ?></th>
            <td><?= h($employeePersonalInformation->present_phone_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Present Email') ?></th>
            <td><?= h($employeePersonalInformation->present_email) ?></td>
        </tr>
        <tr>
            <th><?= __('Permanent Pin Code') ?></th>
            <td><?= h($employeePersonalInformation->permanent_pin_code) ?></td>
        </tr>
        <tr>
            <th><?= __('Permanent Mobile No') ?></th>
            <td><?= h($employeePersonalInformation->permanent_mobile_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Permanent Phone No') ?></th>
            <td><?= h($employeePersonalInformation->permanent_phone_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Permanent Email') ?></th>
            <td><?= h($employeePersonalInformation->permanent_email) ?></td>
        </tr>
        <tr>
            <th><?= __('Nominee Name') ?></th>
            <td><?= h($employeePersonalInformation->nominee_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Relation With Employee') ?></th>
            <td><?= h($employeePersonalInformation->relation_with_employee) ?></td>
        </tr>
        <tr>
            <th><?= __('Nomination Type') ?></th>
            <td><?= h($employeePersonalInformation->nomination_type) ?></td>
        </tr>
        <tr>
            <th><?= __('Nominee Pin Code') ?></th>
            <td><?= h($employeePersonalInformation->nominee_pin_code) ?></td>
        </tr>
        <tr>
            <th><?= __('Nominee Mobile No') ?></th>
            <td><?= h($employeePersonalInformation->nominee_mobile_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Employee') ?></th>
            <td><?= $employeePersonalInformation->has('employee') ? $this->Html->link($employeePersonalInformation->employee->name, ['controller' => 'Employees', 'action' => 'view', $employeePersonalInformation->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Initial Designation') ?></th>
            <td><?= h($employeePersonalInformation->initial_designation) ?></td>
        </tr>
        <tr>
            <th><?= __('Office Name') ?></th>
            <td><?= h($employeePersonalInformation->office_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Recruitment Mode') ?></th>
            <td><?= h($employeePersonalInformation->recruitment_mode) ?></td>
        </tr>
        <tr>
            <th><?= __('Deduction Type') ?></th>
            <td><?= h($employeePersonalInformation->deduction_type) ?></td>
        </tr>
        <tr>
            <th><?= __('Gpf No') ?></th>
            <td><?= h($employeePersonalInformation->gpf_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($employeePersonalInformation->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Home State') ?></th>
            <td><?= $this->Number->format($employeePersonalInformation->home_state) ?></td>
        </tr>
        <tr>
            <th><?= __('Passport No') ?></th>
            <td><?= $this->Number->format($employeePersonalInformation->passport_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Account No') ?></th>
            <td><?= $this->Number->format($employeePersonalInformation->account_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Height') ?></th>
            <td><?= $this->Number->format($employeePersonalInformation->height) ?></td>
        </tr>
        <tr>
            <th><?= __('Home District') ?></th>
            <td><?= $this->Number->format($employeePersonalInformation->home_district) ?></td>
        </tr>
        <tr>
            <th><?= __('Present State') ?></th>
            <td><?= $this->Number->format($employeePersonalInformation->present_state) ?></td>
        </tr>
        <tr>
            <th><?= __('Present District') ?></th>
            <td><?= $this->Number->format($employeePersonalInformation->present_district) ?></td>
        </tr>
        <tr>
            <th><?= __('Permanent State') ?></th>
            <td><?= $this->Number->format($employeePersonalInformation->permanent_state) ?></td>
        </tr>
        <tr>
            <th><?= __('Permanent District') ?></th>
            <td><?= $this->Number->format($employeePersonalInformation->permanent_district) ?></td>
        </tr>
        <tr>
            <th><?= __('Nominee State') ?></th>
            <td><?= $this->Number->format($employeePersonalInformation->nominee_state) ?></td>
        </tr>
        <tr>
            <th><?= __('Nominee District') ?></th>
            <td><?= $this->Number->format($employeePersonalInformation->nominee_district) ?></td>
        </tr>
        <tr>
            <th><?= __('Reporting To') ?></th>
            <td><?= $this->Number->format($employeePersonalInformation->reporting_to) ?></td>
        </tr>
        <tr>
            <th><?= __('Basic Pay') ?></th>
            <td><?= $this->Number->format($employeePersonalInformation->basic_pay) ?></td>
        </tr>
        <tr>
            <th><?= __('Date Of Birth') ?></th>
            <td><?= h($employeePersonalInformation->date_of_birth) ?></td>
        </tr>
        <tr>
            <th><?= __('Appointment Date') ?></th>
            <td><?= h($employeePersonalInformation->appointment_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Dept Joining Date') ?></th>
            <td><?= h($employeePersonalInformation->dept_joining_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Retirement Date') ?></th>
            <td><?= h($employeePersonalInformation->retirement_date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Present Address') ?></h4>
        <?= $this->Text->autoParagraph(h($employeePersonalInformation->present_address)); ?>
    </div>
    <div class="row">
        <h4><?= __('Permanent Address') ?></h4>
        <?= $this->Text->autoParagraph(h($employeePersonalInformation->permanent_address)); ?>
    </div>
    <div class="row">
        <h4><?= __('Nominee Present Address') ?></h4>
        <?= $this->Text->autoParagraph(h($employeePersonalInformation->nominee_present_address)); ?>
    </div>
</div>
