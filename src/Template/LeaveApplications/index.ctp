<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel ucfirst">Leave Applications</span> 
		</div>
		<div class="actions">
			
			
		</div>	
	
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
				<form method="GET" >
				
				</form>
				<?php $page_no=$this->Paginator->current('leaveApplications'); $page_no=($page_no-1)*20; 
					
				?>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="5%">Sr. No.</th>
							<th width="15%">Name</th>
							<th width="15%">Submission Date</th>
							<th width="15%">No of Days</th>
							<th width="15%">Status</th>
							<th width="10%" class="actions"><?= __('Actions') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0; foreach ($leaveApplications as $leaveApplication):  $i++; ?>
						<tr>
							<td><?= h(++$page_no) ?></td>
							<td><?= h($leaveApplication->employee->name) ?></td>
							<td><?php 
							if($leaveApplication->submission_date!='1/1/70')
							{
							echo date("d-m-Y",strtotime($leaveApplication->submission_date));} ?></td>
							<td><?= h($leaveApplication->day_no) ?></td>
							<td><?= h($leaveApplication->leave_status) ?></td>
							<td class="actions">
								<?php if($leaveApplication->leave_status=="Pending"){?>
								<?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $leaveApplication->id],array('escape'=>false,'class'=>'btn btn-xs blue')); ?>
								<?= $this->Form->postLink('<i class="fa fa-trash"></i> ',
								['action' => 'delete', $leaveApplication->id], 
								[
									'escape' => false,
									'class' => 'btn btn-xs btn-danger',
									'confirm' => __('Are you sure ?', $leaveApplication->id)
								]
							) ?>
								<?php } ?>
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