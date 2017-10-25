<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Reference Detail'), ['action' => 'add']) ?></li>
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
<div class="referenceDetails index large-9 medium-8 columns content">
    <h3><?= __('Reference Details') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('ledger_account_id') ?></th>
                <th><?= $this->Paginator->sort('receipt_id') ?></th>
                <th><?= $this->Paginator->sort('receipt_row_id') ?></th>
                <th><?= $this->Paginator->sort('payment_id') ?></th>
                <th><?= $this->Paginator->sort('invoice_id') ?></th>
                <th><?= $this->Paginator->sort('reference_no') ?></th>
                <th><?= $this->Paginator->sort('credit') ?></th>
                <th><?= $this->Paginator->sort('debit') ?></th>
                <th><?= $this->Paginator->sort('reference_type') ?></th>
                <th><?= $this->Paginator->sort('invoice_booking_id') ?></th>
                <th><?= $this->Paginator->sort('credit_note_id') ?></th>
                <th><?= $this->Paginator->sort('journal_voucher_id') ?></th>
                <th><?= $this->Paginator->sort('auto_inc') ?></th>
                <th><?= $this->Paginator->sort('sale_return_id') ?></th>
                <th><?= $this->Paginator->sort('purchase_return_id') ?></th>
                <th><?= $this->Paginator->sort('petty_cash_voucher_id') ?></th>
                <th><?= $this->Paginator->sort('nppayment_id') ?></th>
                <th><?= $this->Paginator->sort('contra_voucher_id') ?></th>
                <th><?= $this->Paginator->sort('opening_balance') ?></th>
                <th><?= $this->Paginator->sort('transaction_date') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($referenceDetails as $referenceDetail): ?>
            <tr>
                <td><?= $this->Number->format($referenceDetail->id) ?></td>
                <td><?= $referenceDetail->has('ledger_account') ? $this->Html->link($referenceDetail->ledger_account->name, ['controller' => 'LedgerAccounts', 'action' => 'view', $referenceDetail->ledger_account->id]) : '' ?></td>
                <td><?= $this->Number->format($referenceDetail->receipt_id) ?></td>
                <td><?= $this->Number->format($referenceDetail->receipt_row_id) ?></td>
                <td><?= $this->Number->format($referenceDetail->payment_id) ?></td>
                <td><?= $referenceDetail->has('invoice') ? $this->Html->link($referenceDetail->invoice->id, ['controller' => 'Invoices', 'action' => 'view', $referenceDetail->invoice->id]) : '' ?></td>
                <td><?= h($referenceDetail->reference_no) ?></td>
                <td><?= $this->Number->format($referenceDetail->credit) ?></td>
                <td><?= $this->Number->format($referenceDetail->debit) ?></td>
                <td><?= h($referenceDetail->reference_type) ?></td>
                <td><?= $referenceDetail->has('invoice_booking') ? $this->Html->link($referenceDetail->invoice_booking->id, ['controller' => 'InvoiceBookings', 'action' => 'view', $referenceDetail->invoice_booking->id]) : '' ?></td>
                <td><?= $referenceDetail->has('credit_note') ? $this->Html->link($referenceDetail->credit_note->id, ['controller' => 'CreditNotes', 'action' => 'view', $referenceDetail->credit_note->id]) : '' ?></td>
                <td><?= $this->Number->format($referenceDetail->journal_voucher_id) ?></td>
                <td><?= $this->Number->format($referenceDetail->auto_inc) ?></td>
                <td><?= $this->Number->format($referenceDetail->sale_return_id) ?></td>
                <td><?= $this->Number->format($referenceDetail->purchase_return_id) ?></td>
                <td><?= $this->Number->format($referenceDetail->petty_cash_voucher_id) ?></td>
                <td><?= $this->Number->format($referenceDetail->nppayment_id) ?></td>
                <td><?= $this->Number->format($referenceDetail->contra_voucher_id) ?></td>
                <td><?= h($referenceDetail->opening_balance) ?></td>
                <td><?= h($referenceDetail->transaction_date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $referenceDetail->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $referenceDetail->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $referenceDetail->id], ['confirm' => __('Are you sure you want to delete # {0}?', $referenceDetail->id)]) ?>
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
