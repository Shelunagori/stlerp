<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel ucfirst">Loan Applications</span> 
		</div>
		<div class="actions">
			
			
		</div>	
	
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
				<form method="GET" >
				
				</form>
				<?php $page_no=$this->Paginator->current('SalaryAdvances'); $page_no=($page_no-1)*20; 
					
				?>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="5%">Sr. No.</th>
							<th width="15%">Employee Name</th>
							<th width="15%">Amount</th>
							<th width="15%">Reason</th>
							<th width="10%" class="actions"><?= __('Actions') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0; foreach ($salaryAdvances as $salaryAdvance): ?>
						<tr>
							<td><?= h(++$page_no) ?></td>
							<td><?= h($salaryAdvance->employee->name) ?></td>
							<td><?= h($salaryAdvance->amount) ?></td>
							<td><?= h($salaryAdvance->reason) ?></td>
							<td class="actions">
								<?php if($salaryAdvance->status=="Pending"){?>
								<?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $salaryAdvance->id],array('escape'=>false,'class'=>'btn btn-xs blue')); ?>
								<?= $this->Form->postLink('<i class="fa fa-trash"></i> ',
								['action' => 'delete', $salaryAdvance->id], 
								[
									'escape' => false,
									'class' => 'btn btn-xs btn-danger',
									'confirm' => __('Are you sure ?', $salaryAdvance->id)
								]
							) ?>
								<?php } ?>
								<?php echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'view', $salaryAdvance->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View '));  ?>
								<?php if($empData->department->name=='HR & Administration' || $empData->designation->name=='Director'){
									echo $this->Html->link('Edit after approve',['action' => 'approve', $salaryAdvance->id],['escape'=>false,'target'=>'_blank','class'=>'']);
								} ?>
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