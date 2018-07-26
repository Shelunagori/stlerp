<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Challan Return Voucher'), ['action' => 'edit', $challanReturnVoucher->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Challan Return Voucher'), ['action' => 'delete', $challanReturnVoucher->id], ['confirm' => __('Are you sure you want to delete # {0}?', $challanReturnVoucher->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Challan Return Vouchers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Challan Return Voucher'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Challans'), ['controller' => 'Challans', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Challan'), ['controller' => 'Challans', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Challan Return Voucher Rows'), ['controller' => 'ChallanReturnVoucherRows', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Challan Return Voucher Row'), ['controller' => 'ChallanReturnVoucherRows', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="challanReturnVouchers view large-9 medium-8 columns content">
    <h3><?= h($challanReturnVoucher->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Voucher No') ?></th>
            <td><?= h($challanReturnVoucher->voucher_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Company') ?></th>
            <td><?= $challanReturnVoucher->has('company') ? $this->Html->link($challanReturnVoucher->company->name, ['controller' => 'Companies', 'action' => 'view', $challanReturnVoucher->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Challan') ?></th>
            <td><?= $challanReturnVoucher->has('challan') ? $this->Html->link($challanReturnVoucher->challan->id, ['controller' => 'Challans', 'action' => 'view', $challanReturnVoucher->challan->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($challanReturnVoucher->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created By') ?></th>
            <td><?= $this->Number->format($challanReturnVoucher->created_by) ?></td>
        </tr>
        <tr>
            <th><?= __('Created On') ?></th>
            <td><?= h($challanReturnVoucher->created_on) ?></td>
        </tr>
        <tr>
            <th><?= __('Transaction Date') ?></th>
            <td><?= h($challanReturnVoucher->transaction_date) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Challan Return Voucher Rows') ?></h4>
        <?php if (!empty($challanReturnVoucher->challan_return_voucher_rows)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Challan Return Voucher Id') ?></th>
                <th><?= __('Item Id') ?></th>
                <th><?= __('Quantity') ?></th>
                <th><?= __('Challan Row Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($challanReturnVoucher->challan_return_voucher_rows as $challanReturnVoucherRows): ?>
            <tr>
                <td><?= h($challanReturnVoucherRows->id) ?></td>
                <td><?= h($challanReturnVoucherRows->challan_return_voucher_id) ?></td>
                <td><?= h($challanReturnVoucherRows->item_id) ?></td>
                <td><?= h($challanReturnVoucherRows->quantity) ?></td>
                <td><?= h($challanReturnVoucherRows->challan_row_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ChallanReturnVoucherRows', 'action' => 'view', $challanReturnVoucherRows->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ChallanReturnVoucherRows', 'action' => 'edit', $challanReturnVoucherRows->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ChallanReturnVoucherRows', 'action' => 'delete', $challanReturnVoucherRows->id], ['confirm' => __('Are you sure you want to delete # {0}?', $challanReturnVoucherRows->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
