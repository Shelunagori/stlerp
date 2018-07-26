<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Create Employee Hierarchy</span>
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<div class="row ">
		<div class="col-md-6">
		 <?= $this->Form->create($employeeHierarchy,array("class"=>"form-horizontal",'id'=>'form_sample_3')) ?>
			<div class="form-body">
				<div class="form-group">
					<label class="control-label col-md-4">Employee Name <span class="required" aria-required="true">
					* </span>
					</label>
					<div class="col-md-8">
						<div class="input-icon right">
							<i class="fa"></i>
							 <?php echo $this->Form->input('employee_id',['class'=>'form-control input-sm select2me','empty'=>'-Select-','label'=>false,'options' => $employees,'required'=>true]); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">Under  <span class="required" aria-required="true">
					* </span>
					</label>
					<div class="col-md-8">
						<div class="input-icon right">
							<i class="fa"></i>
							<?php echo $this->Form->input('parent_id',['class'=>'form-control input-sm select2me   calculation','label'=>false,'empty'=>'-Select-', 'options' => $employees_parent,'required'=>true]); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-offset-4 col-md-8">
						<button type="submit" class="btn green">Add</button>
					</div>
				</div>
			</div>
		<?= $this->Form->end() ?>
		</div>
		<div class="col-md-6">
			<div class="portlet-body">
			<div class="table-scrollable">
			<?php $page_no=$this->Paginator->current('Account Categories'); $page_no=($page_no-1)*20; ?>
			<table class="table table-hover">
				 <thead>
					<tr>
						<th>S.No</th>
						<th>Employee Name</th>
						<th>Under</th>
						<th width="80"><?= __('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
						<?php foreach ($employeeHierarchies as $employeedata):  ?>
						<tr>
							<td><?= h(++$page_no) ?></td>
							
							<td><?= h($employeedata->name) ?></td>
							
							<td><?= h($employeedata->parent_accounting_group['name']) ?></td>
							
							<td> <?php if($child_exist[$employeedata->id]=="No"){ ?>
								<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $employeedata->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeedata->id)]) ?>
								<?php } ?>			
							</td>
						</tr>
						<?php endforeach; ?>
				</tbody>
			</table>
			</div>
			

   
			<div class="paginator">
				<ul class="pagination">
					<?= $this->Paginator->prev('<') ?>
					<?= $this->Paginator->numbers() ?>
					<?= $this->Paginator->next('>') ?>
				</ul>
				<p><?= $this->Paginator->counter() ?></p>
			</div>
			</div>
		</div>
		<!-- END FORM-->
	</div>
</div>
</div>
<?php //echo $this->Tree->generate($children, array('id' => 'my-tree')); ?>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

