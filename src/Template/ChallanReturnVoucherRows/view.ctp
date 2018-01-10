<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Challan Return Voucher Row'), ['action' => 'edit', $challanReturnVoucherRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Challan Return Voucher Row'), ['action' => 'delete', $challanReturnVoucherRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $challanReturnVoucherRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Challan Return Voucher Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Challan Return Voucher Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Challan Return Vouchers'), ['controller' => 'ChallanReturnVouchers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Challan Return Voucher'), ['controller' => 'ChallanReturnVouchers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Challan Rows'), ['controller' => 'ChallanRows', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Challan Row'), ['controller' => 'ChallanRows', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="challanReturnVoucherRows view large-9 medium-8 columns content">
    <h3><?= h($challanReturnVoucherRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Challan Return Voucher') ?></th>
            <td><?= $challanReturnVoucherRow->has('challan_return_voucher') ? $this->Html->link($challanReturnVoucherRow->challan_return_voucher->id, ['controller' => 'ChallanReturnVouchers', 'action' => 'view', $challanReturnVoucherRow->challan_return_voucher->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Item') ?></th>
            <td><?= $challanReturnVoucherRow->has('item') ? $this->Html->link($challanReturnVoucherRow->item->name, ['controller' => 'Items', 'action' => 'view', $challanReturnVoucherRow->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Challan Row') ?></th>
            <td><?= $challanReturnVoucherRow->has('challan_row') ? $this->Html->link($challanReturnVoucherRow->challan_row->id, ['controller' => 'ChallanRows', 'action' => 'view', $challanReturnVoucherRow->challan_row->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($challanReturnVoucherRow->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($challanReturnVoucherRow->quantity) ?></td>
        </tr>
    </table>
</div>
