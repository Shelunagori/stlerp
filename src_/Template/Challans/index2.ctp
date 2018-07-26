<?php //pr($challans); exit; ?>
<div class="portlet light bordered">

	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Converted Challans</span>
		
		</div>
		<div class="actions">
			<div class="btn-group">
			<?= $this->Html->link(
				'Pending',
				'/Challans/PendingChallanForInvoice',
				['class' => 'btn btn-default']
			); ?>
			<?= $this->Html->link(
				'Converted',
				'/Challans/Index2',
				['class' => 'btn btn-primary']
			); ?>
			</div>
		</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
			<?php $page_no=$this->Paginator->current('Challans'); $page_no=($page_no-1)*20; ?>
				<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th>S.No</th>
								<th>Challan.No</th>
								<th>Status</th>
							</tr>
					
					</thead>

					<tbody>
            <?php foreach ($Challans as $challan): ?>
            <tr>
                <td><?= h(++$page_no) ?></td>
				<td><?= h(($challan->ch1.'/CH-'.str_pad($challan->ch2, 3, '0', STR_PAD_LEFT).'/'.$challan->ch3.'/'.$challan->ch4)) ?></td>
                          
				<td>
					<?php echo $challan->challan_status; ?>
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
		</div>
	</div>
</div>

