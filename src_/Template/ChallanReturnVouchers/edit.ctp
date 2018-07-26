<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $challanReturnVoucher->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $challanReturnVoucher->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Challan Return Vouchers'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Challans'), ['controller' => 'Challans', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Challan'), ['controller' => 'Challans', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Challan Return Voucher Rows'), ['controller' => 'ChallanReturnVoucherRows', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Challan Return Voucher Row'), ['controller' => 'ChallanReturnVoucherRows', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="challanReturnVouchers form large-9 medium-8 columns content">
    <?= $this->Form->create($challanReturnVoucher) ?>
    <fieldset>
        <legend><?= __('Edit Challan Return Voucher') ?></legend>
        <?php
            echo $this->Form->input('voucher_no');
            echo $this->Form->input('company_id', ['options' => $companies]);
            echo $this->Form->input('created_on');
            echo $this->Form->input('created_by');
            echo $this->Form->input('transaction_date');
            echo $this->Form->input('challan_id', ['options' => $challans]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
