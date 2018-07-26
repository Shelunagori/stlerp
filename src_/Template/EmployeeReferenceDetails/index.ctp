<div class="row">
	<div class="col-md-8">
		<div class="portlet box red">
			<div class="portlet-title">
				<div class="caption">
					Reference Details of <b><?php echo $Employee->name; ?></b>
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable">
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?= $this->Paginator->sort('name') ?></th>
								<th><?= $this->Paginator->sort('mobile') ?></th>
								<th><?= $this->Paginator->sort('telephone') ?></th>
								<th class="actions"><?= __('Actions') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($employeeReferenceDetails as $employeeReferenceDetail1): ?>
							<tr>
								<td><?= h($employeeReferenceDetail1->name) ?></td>
								<td><?= h($employeeReferenceDetail1->mobile) ?></td>
								<td><?= h($employeeReferenceDetail1->telephone) ?></td>
								<td class="actions">
									<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $employeeReferenceDetail1->id, $employeeID], ['confirm' => __('Are you sure you want to delete ?')]) ?>
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
	<div class="col-md-4">
		<div class="portlet box red">
			<div class="portlet-title">
				<div class="caption">
					Add Reference Detail for <b><?php echo $Employee->name; ?></b>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($employeeReferenceDetail) ?>
					<?php
						echo $this->Form->input('employee_id', ['value' => $employeeID,'type' => 'hidden']);
					?>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Name </label>
								<?php echo $this->Form->input('name', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Name']); ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Mobile </label>
								<?php echo $this->Form->input('mobile', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Mobile']); ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Telephone </label>
								<?php echo $this->Form->input('telephone', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Telephone']); ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Address </label>
								<?php echo $this->Form->input('address', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Address']); ?>
							</div>
						</div>
					</div>
				<?= $this->Form->button(__('Submit'),['class'=>'btn blue']) ?>
				<?= $this->Form->end() ?>
				
			</div>
		</div>
	</div>
</div>






