<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Challan Return Voucher Row'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Challan Return Vouchers'), ['controller' => 'ChallanReturnVouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Challan Return Voucher'), ['controller' => 'ChallanReturnVouchers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Challan Rows'), ['controller' => 'ChallanRows', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Challan Row'), ['controller' => 'ChallanRows', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="challanReturnVoucherRows index large-9 medium-8 columns content">
    <h3><?= __('Challan Return Voucher Rows') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('challan_return_voucher_id') ?></th>
                <th><?= $this->Paginator->sort('item_id') ?></th>
                <th><?= $this->Paginator->sort('quantity') ?></th>
                <th><?= $this->Paginator->sort('challan_row_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($challanReturnVoucherRows as $challanReturnVoucherRow): ?>
            <tr>
                <td><?= $this->Number->format($challanReturnVoucherRow->id) ?></td>
                <td><?= $challanReturnVoucherRow->has('challan_return_voucher') ? $this->Html->link($challanReturnVoucherRow->challan_return_voucher->id, ['controller' => 'ChallanReturnVouchers', 'action' => 'view', $challanReturnVoucherRow->challan_return_voucher->id]) : '' ?></td>
                <td><?= $challanReturnVoucherRow->has('item') ? $this->Html->link($challanReturnVoucherRow->item->name, ['controller' => 'Items', 'action' => 'view', $challanReturnVoucherRow->item->id]) : '' ?></td>
                <td><?= $this->Number->format($challanReturnVoucherRow->quantity) ?></td>
                <td><?= $challanReturnVoucherRow->has('challan_row') ? $this->Html->link($challanReturnVoucherRow->challan_row->id, ['controller' => 'ChallanRows', 'action' => 'view', $challanReturnVoucherRow->challan_row->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $challanReturnVoucherRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $challanReturnVoucherRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $challanReturnVoucherRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $challanReturnVoucherRow->id)]) ?>
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
