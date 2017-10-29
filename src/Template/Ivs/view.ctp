<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Iv'), ['action' => 'edit', $iv->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Iv'), ['action' => 'delete', $iv->id], ['confirm' => __('Are you sure you want to delete # {0}?', $iv->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Ivs'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Iv'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Invoices'), ['controller' => 'Invoices', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Invoice'), ['controller' => 'Invoices', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Iv Rows'), ['controller' => 'IvRows', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Iv Row'), ['controller' => 'IvRows', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="ivs view large-9 medium-8 columns content">
    <h3><?= h($iv->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Invoice') ?></th>
            <td><?= $iv->has('invoice') ? $this->Html->link($iv->invoice->id, ['controller' => 'Invoices', 'action' => 'view', $iv->invoice->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Company') ?></th>
            <td><?= $iv->has('company') ? $this->Html->link($iv->company->name, ['controller' => 'Companies', 'action' => 'view', $iv->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($iv->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Voucher No') ?></th>
            <td><?= $this->Number->format($iv->voucher_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Transaction Date') ?></th>
            <td><?= h($iv->transaction_date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Narration') ?></h4>
        <?= $this->Text->autoParagraph(h($iv->narration)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Iv Rows') ?></h4>
        <?php if (!empty($iv->iv_rows)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Iv Id') ?></th>
                <th><?= __('Invoice Row Id') ?></th>
                <th><?= __('Item Id') ?></th>
                <th><?= __('Quantity') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($iv->iv_rows as $ivRows): ?>
            <tr>
                <td><?= h($ivRows->id) ?></td>
                <td><?= h($ivRows->iv_id) ?></td>
                <td><?= h($ivRows->invoice_row_id) ?></td>
                <td><?= h($ivRows->item_id) ?></td>
                <td><?= h($ivRows->quantity) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'IvRows', 'action' => 'view', $ivRows->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'IvRows', 'action' => 'edit', $ivRows->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'IvRows', 'action' => 'delete', $ivRows->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ivRows->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
