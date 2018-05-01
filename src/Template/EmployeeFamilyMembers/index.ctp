<div class="row">
	<div class="col-md-8">
		<div class="portlet box red">
			<div class="portlet-title">
				<div class="caption">
					Family Members of <b><?php echo $Employee->name; ?></b>
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable">
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?= $this->Paginator->sort('member_name') ?></th>
								<th><?= $this->Paginator->sort('relationship') ?></th>
								<th><?= $this->Paginator->sort('mobile') ?></th>
								<th><?= $this->Paginator->sort('telephone') ?></th>
								<th class="actions"><?= __('Actions') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($employeeFamilyMembers as $employeeFamilyMember1): ?>
							<tr>
								<td><?= h($employeeFamilyMember1->member_name) ?></td>
								<td><?= h($employeeFamilyMember1->relationship) ?></td>
								<td><?= h($employeeFamilyMember1->mobile) ?></td>
								<td><?= h($employeeFamilyMember1->telephone) ?></td>
								<td class="actions">
									<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $employeeFamilyMember1->id, $employeeID], ['confirm' => __('Are you sure you want to delete ?')]) ?>
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
					Add Family Member for <b><?php echo $Employee->name; ?></b>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($employeeFamilyMember) ?>
					<?php
						echo $this->Form->input('employee_id', ['value' => $employeeID,'type' => 'hidden']);
					?>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Member name </label>
								<?php echo $this->Form->input('member_name', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Member name']); ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Relationship </label>
								<?php echo $this->Form->input('relationship', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Relationship']); ?>
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



