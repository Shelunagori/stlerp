<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Reference Detail'), ['action' => 'edit', $referenceDetail->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Reference Detail'), ['action' => 'delete', $referenceDetail->id], ['confirm' => __('Are you sure you want to delete # {0}?', $referenceDetail->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Reference Details'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Reference Detail'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Ledger Accounts'), ['controller' => 'LedgerAccounts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Ledger Account'), ['controller' => 'LedgerAccounts', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Receipt Vouchers'), ['controller' => 'ReceiptVouchers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Receipt Voucher'), ['controller' => 'ReceiptVouchers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Payment Vouchers'), ['controller' => 'PaymentVouchers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Payment Voucher'), ['controller' => 'PaymentVouchers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Invoices'), ['controller' => 'Invoices', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Invoice'), ['controller' => 'Invoices', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Invoice Bookings'), ['controller' => 'InvoiceBookings', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Invoice Booking'), ['controller' => 'InvoiceBookings', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Credit Notes'), ['controller' => 'CreditNotes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Credit Note'), ['controller' => 'CreditNotes', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="referenceDetails view large-9 medium-8 columns content">
    <h3><?= h($referenceDetail->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Ledger Account') ?></th>
            <td><?= $referenceDetail->has('ledger_account') ? $this->Html->link($referenceDetail->ledger_account->name, ['controller' => 'LedgerAccounts', 'action' => 'view', $referenceDetail->ledger_account->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Invoice') ?></th>
            <td><?= $referenceDetail->has('invoice') ? $this->Html->link($referenceDetail->invoice->id, ['controller' => 'Invoices', 'action' => 'view', $referenceDetail->invoice->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Reference No') ?></th>
            <td><?= h($referenceDetail->reference_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Reference Type') ?></th>
            <td><?= h($referenceDetail->reference_type) ?></td>
        </tr>
        <tr>
            <th><?= __('Invoice Booking') ?></th>
            <td><?= $referenceDetail->has('invoice_booking') ? $this->Html->link($referenceDetail->invoice_booking->id, ['controller' => 'InvoiceBookings', 'action' => 'view', $referenceDetail->invoice_booking->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Credit Note') ?></th>
            <td><?= $referenceDetail->has('credit_note') ? $this->Html->link($referenceDetail->credit_note->id, ['controller' => 'CreditNotes', 'action' => 'view', $referenceDetail->credit_note->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Opening Balance') ?></th>
            <td><?= h($referenceDetail->opening_balance) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($referenceDetail->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Receipt Id') ?></th>
            <td><?= $this->Number->format($referenceDetail->receipt_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Receipt Row Id') ?></th>
            <td><?= $this->Number->format($referenceDetail->receipt_row_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Payment Id') ?></th>
            <td><?= $this->Number->format($referenceDetail->payment_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Credit') ?></th>
            <td><?= $this->Number->format($referenceDetail->credit) ?></td>
        </tr>
        <tr>
            <th><?= __('Debit') ?></th>
            <td><?= $this->Number->format($referenceDetail->debit) ?></td>
        </tr>
        <tr>
            <th><?= __('Journal Voucher Id') ?></th>
            <td><?= $this->Number->format($referenceDetail->journal_voucher_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Auto Inc') ?></th>
            <td><?= $this->Number->format($referenceDetail->auto_inc) ?></td>
        </tr>
        <tr>
            <th><?= __('Sale Return Id') ?></th>
            <td><?= $this->Number->format($referenceDetail->sale_return_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Purchase Return Id') ?></th>
            <td><?= $this->Number->format($referenceDetail->purchase_return_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Petty Cash Voucher Id') ?></th>
            <td><?= $this->Number->format($referenceDetail->petty_cash_voucher_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Nppayment Id') ?></th>
            <td><?= $this->Number->format($referenceDetail->nppayment_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Contra Voucher Id') ?></th>
            <td><?= $this->Number->format($referenceDetail->contra_voucher_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Transaction Date') ?></th>
            <td><?= h($referenceDetail->transaction_date) ?></td>
        </tr>
    </table>
</div>
