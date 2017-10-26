<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Reference Details'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Ledger Accounts'), ['controller' => 'LedgerAccounts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger Account'), ['controller' => 'LedgerAccounts', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Receipt Vouchers'), ['controller' => 'ReceiptVouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Receipt Voucher'), ['controller' => 'ReceiptVouchers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Payment Vouchers'), ['controller' => 'PaymentVouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Payment Voucher'), ['controller' => 'PaymentVouchers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Invoices'), ['controller' => 'Invoices', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Invoice'), ['controller' => 'Invoices', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Invoice Bookings'), ['controller' => 'InvoiceBookings', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Invoice Booking'), ['controller' => 'InvoiceBookings', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Credit Notes'), ['controller' => 'CreditNotes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Credit Note'), ['controller' => 'CreditNotes', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="referenceDetails form large-9 medium-8 columns content">
    <?= $this->Form->create($referenceDetail) ?>
    <fieldset>
        <legend><?= __('Add Reference Detail') ?></legend>
        <?php
            echo $this->Form->input('ledger_account_id', ['options' => $ledgerAccounts]);
            echo $this->Form->input('receipt_id');
            echo $this->Form->input('receipt_row_id');
            echo $this->Form->input('payment_id');
            echo $this->Form->input('invoice_id', ['options' => $invoices]);
            echo $this->Form->input('reference_no');
            echo $this->Form->input('credit');
            echo $this->Form->input('debit');
            echo $this->Form->input('reference_type');
            echo $this->Form->input('invoice_booking_id', ['options' => $invoiceBookings]);
            echo $this->Form->input('credit_note_id', ['options' => $creditNotes]);
            echo $this->Form->input('journal_voucher_id');
            echo $this->Form->input('auto_inc');
            echo $this->Form->input('sale_return_id');
            echo $this->Form->input('purchase_return_id');
            echo $this->Form->input('petty_cash_voucher_id');
            echo $this->Form->input('nppayment_id');
            echo $this->Form->input('contra_voucher_id');
            echo $this->Form->input('opening_balance');
            echo $this->Form->input('transaction_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
