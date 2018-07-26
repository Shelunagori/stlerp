<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $loanInstallment->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $loanInstallment->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Loan Installments'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Loan Applications'), ['controller' => 'LoanApplications', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Loan Application'), ['controller' => 'LoanApplications', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="loanInstallments form large-9 medium-8 columns content">
    <?= $this->Form->create($loanInstallment) ?>
    <fieldset>
        <legend><?= __('Edit Loan Installment') ?></legend>
        <?php
            echo $this->Form->input('loan_application_id', ['options' => $loanApplications]);
            echo $this->Form->input('month');
            echo $this->Form->input('year');
            echo $this->Form->input('amount');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
