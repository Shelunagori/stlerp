<div class="row">
	<div class="col-md-8">
		<div class="portlet box red">
			<div class="portlet-title">
				<div class="caption">
					Work Experience of <b><?php echo $Employee->name; ?></b>
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable">
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?= $this->Paginator->sort('period') ?></th>
								<th><?= $this->Paginator->sort('company_name') ?></th>
								<th><?= $this->Paginator->sort('designation') ?></th>
								<th><?= $this->Paginator->sort('nature_of_the_duties') ?></th>
								<th class="actions"><?= __('Actions') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($employeeWorkExperiences as $employeeWorkExperience1): ?>
							<tr>
								<td><?= h($employeeWorkExperience1->period) ?></td>
								<td><?= h($employeeWorkExperience1->company_name) ?></td>
								<td><?= h($employeeWorkExperience1->designation) ?></td>
								<td><?= h($employeeWorkExperience1->nature_of_the_duties) ?></td>
								<td class="actions">
									<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $employeeWorkExperience1->id, $employeeID], ['confirm' => __('Are you sure you want to delete ?')]) ?>
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
					Add Work Experience for <b><?php echo $Employee->name; ?></b>
				</div>
			</div>
			<div class="portlet-body">
				<?= $this->Form->create($employeeWorkExperience) ?>
					<?php
						echo $this->Form->input('employee_id', ['value' => $employeeID,'type' => 'hidden']);
					?>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Period </label>
								<?php echo $this->Form->input('period', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Period']); ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Company Name </label>
								<?php echo $this->Form->input('company_name', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Company Name']); ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Designation </label>
								<?php echo $this->Form->input('designation', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Designation']); ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Nature of the duties </label>
								<?php echo $this->Form->input('nature_of_the_duties', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Nature of the duties']); ?>
							</div>
						</div>
					</div>
				<?= $this->Form->button(__('Submit'),['class'=>'btn blue']) ?>
				<?= $this->Form->end() ?>
				
			</div>
		</div>
	</div>
</div>





