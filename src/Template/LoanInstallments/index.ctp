<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Loan Installment'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Loan Applications'), ['controller' => 'LoanApplications', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Loan Application'), ['controller' => 'LoanApplications', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="loanInstallments index large-9 medium-8 columns content">
    <h3><?= __('Loan Installments') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('loan_application_id') ?></th>
                <th><?= $this->Paginator->sort('month') ?></th>
                <th><?= $this->Paginator->sort('year') ?></th>
                <th><?= $this->Paginator->sort('amount') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($loanInstallments as $loanInstallment): ?>
            <tr>
                <td><?= $this->Number->format($loanInstallment->id) ?></td>
                <td><?= $loanInstallment->has('loan_application') ? $this->Html->link($loanInstallment->loan_application->id, ['controller' => 'LoanApplications', 'action' => 'view', $loanInstallment->loan_application->id]) : '' ?></td>
                <td><?= $this->Number->format($loanInstallment->month) ?></td>
                <td><?= $this->Number->format($loanInstallment->year) ?></td>
                <td><?= $this->Number->format($loanInstallment->amount) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $loanInstallment->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $loanInstallment->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $loanInstallment->id], ['confirm' => __('Are you sure you want to delete # {0}?', $loanInstallment->id)]) ?>
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
