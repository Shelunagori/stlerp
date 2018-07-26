<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Loan Installment'), ['action' => 'edit', $loanInstallment->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Loan Installment'), ['action' => 'delete', $loanInstallment->id], ['confirm' => __('Are you sure you want to delete # {0}?', $loanInstallment->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Loan Installments'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Loan Installment'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Loan Applications'), ['controller' => 'LoanApplications', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Loan Application'), ['controller' => 'LoanApplications', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="loanInstallments view large-9 medium-8 columns content">
    <h3><?= h($loanInstallment->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Loan Application') ?></th>
            <td><?= $loanInstallment->has('loan_application') ? $this->Html->link($loanInstallment->loan_application->id, ['controller' => 'LoanApplications', 'action' => 'view', $loanInstallment->loan_application->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($loanInstallment->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Month') ?></th>
            <td><?= $this->Number->format($loanInstallment->month) ?></td>
        </tr>
        <tr>
            <th><?= __('Year') ?></th>
            <td><?= $this->Number->format($loanInstallment->year) ?></td>
        </tr>
        <tr>
            <th><?= __('Amount') ?></th>
            <td><?= $this->Number->format($loanInstallment->amount) ?></td>
        </tr>
    </table>
</div>
