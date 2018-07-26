<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Quotation Close Reasons</span>
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
	<div class="row ">
		<div class="col-md-6">
		 <?= $this->Form->create($quotationCloseReason,array("class"=>"form-horizontal")) ?>
			<div class="form-body">
				<div class="form-group">
					<label class="control-label col-md-3">Reason<span class="required" aria-required="true">
					* </span>
					</label>
					<div class="col-md-9">
						<div class="input-icon right">
							<i class="fa"></i>
							 <?php echo $this->Form->input('reason', ['type'=>'textarea','label' => false,'class' => 'form-control input-sm']); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-offset-4 col-md-8">
						<button type="submit" class="btn btn-primary">ADD</button>
					</div>
				</div>
			</div>
		<?= $this->Form->end() ?>
		</div>
		<div class="col-md-6">
			<div class="portlet-body">
			<div class="table-scrollable">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Sr. No.</th>
						<th>Reason</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					 <?php $i=0; foreach ($quotationCloseReasons as $quotationCloseReason):  $i++; ?>
					<tr>
						<td><?= h($i) ?></td>
						<td><?= $this->Text->autoParagraph($quotationCloseReason->reason) ?></td>
						<td class="actions">
							<?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $quotationCloseReason->id],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit')); ?>
								<?= $this->Form->postLink('<i class="fa fa-trash"></i> ',
								['action' => 'delete', $quotationCloseReason->id], 
								[
									'escape' => false,
									'class' => 'btn btn-xs btn-danger',
									'confirm' => __('Are you sure ?', $quotationCloseReason->id)
								]
							)

							?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			</div>
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
		<!-- END FORM-->
	</div>
</div>
</div>

