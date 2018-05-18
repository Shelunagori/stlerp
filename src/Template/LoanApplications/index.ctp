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
				<?php $page_no=$this->Paginator->current('loanApplications'); $page_no=($page_no-1)*20; 
					
				?>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="5%">Sr. No.</th>
							<th width="15%">Employee Name</th>
							<th width="15%">Reason For Loan</th>
							<th width="15%">Salary PM</th>
							<th width="15%">Amount Of Loan</th>
							<th width="15%">Starting Date Of Loan</th>
							<th width="15%">Ending Date Of Loan</th>
							<th width="10%" class="actions"><?= __('Actions') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0; foreach ($loanApplications as $loanApplication): 
						if(!empty($loanApplication->starting_date_of_loan))
						{
							if($loanApplication->starting_date_of_loan!='1/1/70')
							{   
								$startingDateOfLoan = date("d-m-Y",strtotime($loanApplication->starting_date_of_loan));
							}
						}
						if(!empty($loanApplication->ending_date_of_loan))
						{
							if($loanApplication->ending_date_of_loan!='1/1/70')
							{   
								$endingDateOfLoan = date("d-m-Y",strtotime($loanApplication->ending_date_of_loan));
							}
						}
						?>
						<tr>
							<td><?= h(++$page_no) ?></td>
							<td><?= h($loanApplication->employee->name) ?></td>
							<td><?= h($loanApplication->reason_for_loan) ?></td>
							<td><?= h($loanApplication->salary_pm) ?></td>
							<td><?= h($loanApplication->amount_of_loan) ?></td>
							<td><?= h(@$startingDateOfLoan) ?></td>
							<td><?= h(@$endingDateOfLoan) ?></td>
							<td class="actions">
							<?php if($loanApplication->status!="approved"){ ?>
								<?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $loanApplication->id],array('escape'=>false,'class'=>'btn btn-xs blue')); ?>
								<?= $this->Form->postLink('<i class="fa fa-trash"></i> ',
								['action' => 'delete', $loanApplication->id], 
								[
									'escape' => false,
									'class' => 'btn btn-xs btn-danger',
									'confirm' => __('Are you sure ?', $loanApplication->id)
								]
							) ?>
							<?php } ?>
							<?php echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'view', $loanApplication->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View ')); ?>
							
							<?php if($empData->department->name=='HR & Administration' || $empData->designation->name=='Director'){
								echo $this->Html->link('Edit after approve',['action' => 'approveLoan', $loanApplication->id],['escape'=>false,'target'=>'_blank','class'=>'']);
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