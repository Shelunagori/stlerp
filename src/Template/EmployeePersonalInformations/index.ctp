<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel ucfirst">Employee Personal Information</span> 
		</div>
		<div class="actions">
			
			
		</div>	
	
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
				<form method="GET" >
				
				</form>
				<?php $page_no=$this->Paginator->current('EmployeePersonalInformations'); $page_no=($page_no-1)*20; 
					
				?>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="5%">Sr. No.</th>
							<th width="15%">First Name</th>
							<th width="15%">Middle Name</th>
							<th width="15%">Last Name</th>
							<th width="15%">Date Of Birth</th>
							<th width="10%" class="actions"><?= __('Actions') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0; foreach ($employeePersonalInformations as $employeePersonalInformation):  $i++; ?>
						<tr>
							<td><?= h(++$page_no) ?></td>
							<td><?= h($employeePersonalInformation->first_name) ?></td>
							<td><?= h($employeePersonalInformation->middle_name) ?></td>
							<td><?= h($employeePersonalInformation->last_name) ?></td>
							<td><?= h($employeePersonalInformation->date_of_birth) ?></td>
							<td class="actions">
								<?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $employeePersonalInformation->id],array('escape'=>false,'class'=>'btn btn-xs blue')); ?>
								<?= $this->Form->postLink('<i class="fa fa-trash"></i> ',
								['action' => 'delete', $employeePersonalInformation->id], 
								[
									'escape' => false,
									'class' => 'btn btn-xs btn-danger',
									'confirm' => __('Are you sure ?', $employeePersonalInformation->id)
								]
							) ?>
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