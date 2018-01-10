<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Employee Personal Information Row'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="employeePersonalInformationRows index large-9 medium-8 columns content">
    <h3><?= __('Employee Personal Information Rows') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('emp_personal_information_id') ?></th>
                <th><?= $this->Paginator->sort('detail_type') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('dob') ?></th>
                <th><?= $this->Paginator->sort('mobile_no') ?></th>
                <th><?= $this->Paginator->sort('phone_no') ?></th>
                <th><?= $this->Paginator->sort('relation') ?></th>
                <th><?= $this->Paginator->sort('dependent') ?></th>
                <th><?= $this->Paginator->sort('whether_employed') ?></th>
                <th><?= $this->Paginator->sort('period') ?></th>
                <th><?= $this->Paginator->sort('company_name') ?></th>
                <th><?= $this->Paginator->sort('designation') ?></th>
                <th><?= $this->Paginator->sort('duties_nature') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employeePersonalInformationRows as $employeePersonalInformationRow): ?>
            <tr>
                <td><?= $this->Number->format($employeePersonalInformationRow->id) ?></td>
                <td><?= $this->Number->format($employeePersonalInformationRow->emp_personal_information_id) ?></td>
                <td><?= h($employeePersonalInformationRow->detail_type) ?></td>
                <td><?= h($employeePersonalInformationRow->name) ?></td>
                <td><?= h($employeePersonalInformationRow->dob) ?></td>
                <td><?= h($employeePersonalInformationRow->mobile_no) ?></td>
                <td><?= h($employeePersonalInformationRow->phone_no) ?></td>
                <td><?= h($employeePersonalInformationRow->relation) ?></td>
                <td><?= h($employeePersonalInformationRow->dependent) ?></td>
                <td><?= h($employeePersonalInformationRow->whether_employed) ?></td>
                <td><?= h($employeePersonalInformationRow->period) ?></td>
                <td><?= h($employeePersonalInformationRow->company_name) ?></td>
                <td><?= h($employeePersonalInformationRow->designation) ?></td>
                <td><?= h($employeePersonalInformationRow->duties_nature) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $employeePersonalInformationRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $employeePersonalInformationRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $employeePersonalInformationRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeePersonalInformationRow->id)]) ?>
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
