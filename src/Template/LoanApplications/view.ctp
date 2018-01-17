<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Loan Application'), ['action' => 'edit', $loanApplication->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Loan Application'), ['action' => 'delete', $loanApplication->id], ['confirm' => __('Are you sure you want to delete # {0}?', $loanApplication->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Loan Applications'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Loan Application'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="loanApplications view large-9 medium-8 columns content">
    <h3><?= h($loanApplication->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Employee Name') ?></th>
            <td><?= h($loanApplication->employee_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Reason For  Loan') ?></th>
            <td><?= h($loanApplication->reason_for _loan) ?></td>
        </tr>
        <tr>
            <th><?= __('Amount  Of Loan') ?></th>
            <td><?= h($loanApplication->amount _of_loan) ?></td>
        </tr>
        <tr>
            <th><?= __('Amount  Of Loan In Word') ?></th>
            <td><?= h($loanApplication->amount _of_loan_in_word) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($loanApplication->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Salary Pm') ?></th>
            <td><?= $this->Number->format($loanApplication->salary_pm) ?></td>
        </tr>
        <tr>
            <th><?= __('Starting Date Of Loan') ?></th>
            <td><?= h($loanApplication->starting_date_of_loan) ?></td>
        </tr>
        <tr>
            <th><?= __('Ending Date Of Loan') ?></th>
            <td><?= h($loanApplication->ending_date_of_loan) ?></td>
        </tr>
        <tr>
            <th><?= __('Create Date') ?></th>
            <td><?= h($loanApplication->create_date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Remark') ?></h4>
        <?= $this->Text->autoParagraph(h($loanApplication->remark)); ?>
    </div>
</div>
