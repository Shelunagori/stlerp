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
				<?php if($s_employee_id==16 || $empData->department->name=="HR & Administration"){ ?>
				<form method="GET" >
					<table class="table">
						<tr>
							<td>
								<?php echo $this->Form->input('employee_id', ['empty'=>'--Select--','options' =>@$Employees,'label' => false,'class' => 'form-control input-sm select2me', 'value'=>@$employee_id]); ?>
							</td>
							<td>
								<input type="text" name="reason" class="form-control input-sm" value="<?php echo @$reason; ?>" placeholder="Reason"/>
							</td>
							<td>
								<input type="text" name="amountFrom" class="form-control input-sm" value="<?php echo @$amountFrom; ?>" placeholder="Amount From"/>
							</td>
							<td>
								<input type="text" name="amountTo" class="form-control input-sm" value="<?php echo @$amountTo; ?>" placeholder="Amount To"/>
							</td>
							<td>
								<button type="submit" class="btn btn-sm blue">Filter</button>
							</td>
						</tr>
					</table>
				</form>
				<?php } ?>
				
				<?php $page_no=$this->Paginator->current('loanApplications'); $page_no=($page_no-1)*20; 
					
				?>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Employee Name</th>
							<th>Reason For Loan</th>
							<th>Salary PM</th>
							<th>Applied Amount</th>
							<th>Approved Amount</th>
							<th>Installments start from</th>
							<th class="actions"><?= __('Actions') ?></th>
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
							<td align="right"><?= h($loanApplication->salary_pm) ?></td>
							<td align="right"><?= $loanApplication->amount_of_loan==0?'-':$loanApplication->amount_of_loan ?></td>
							<td align="right"><?= $loanApplication->approve_amount_of_loan==0?'-':$loanApplication->approve_amount_of_loan ?></td>
							<td><?= h(@$loanApplication->installment_start_month.'-'.$loanApplication->installment_start_year) ?></td>
							<td class="actions">
							<?php if($loanApplication->status!="approved"){ ?>
								<?php 
								if(in_array(195,$allowed_pages)){
								
								echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $loanApplication->id],array('escape'=>false,'class'=>'btn btn-xs blue')); 
								} 
								if(in_array(196,$allowed_pages)){
								?>
								<?= $this->Form->postLink('<i class="fa fa-trash"></i> ',
								['action' => 'delete', $loanApplication->id], 
								[
									'escape' => false,
									'class' => 'btn btn-xs btn-danger',
									'confirm' => __('Are you sure ?', $loanApplication->id)
								]
							) ?>
							<?php }} 
							if(in_array(194,$allowed_pages)){
							?>
							
							<?php echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'view', $loanApplication->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View ')); 
							} ?>
							
							<?php if($empData->department->name=='HR & Administration' || $empData->designation->name=='Director'){
								if($loanApplication->status=="approved"){
									if(in_array(193,$allowed_pages)){
										echo $this->Html->link('<i class="fa fa-edit"></i>',['action' => 'approveLoan', $loanApplication->id],['escape'=>false,'target'=>'_blank','class'=>'btn btn-xs purple tooltips','data-original-title'=>'Edit after approve ']);
									}
								}
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