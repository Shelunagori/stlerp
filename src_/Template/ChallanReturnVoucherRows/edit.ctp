<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $challanReturnVoucherRow->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $challanReturnVoucherRow->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Challan Return Voucher Rows'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Challan Return Vouchers'), ['controller' => 'ChallanReturnVouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Challan Return Voucher'), ['controller' => 'ChallanReturnVouchers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Challan Rows'), ['controller' => 'ChallanRows', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Challan Row'), ['controller' => 'ChallanRows', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="challanReturnVoucherRows form large-9 medium-8 columns content">
    <?= $this->Form->create($challanReturnVoucherRow) ?>
    <fieldset>
        <legend><?= __('Edit Challan Return Voucher Row') ?></legend>
        <?php
            echo $this->Form->input('challan_return_voucher_id', ['options' => $challanReturnVouchers]);
            echo $this->Form->input('item_id', ['options' => $items]);
            echo $this->Form->input('quantity');
            echo $this->Form->input('challan_row_id', ['options' => $challanRows]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
