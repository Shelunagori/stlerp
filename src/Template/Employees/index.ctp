 
<?php 

	if(!empty($status)){
		$url_excel=$status."/?".$url;
	}else{
		$url_excel="/?".$url;
	}

?>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Employees</span>
		</div>
	</div>
	<div class="portlet-body">
	<div class="row">
				<div class="col-md-12">
				<form method="GET" >
				<div class="row">
									
					<div class="col-md-3">
						<input type="text" name="employee_name" class="form-control input-sm" placeholder="Employee Name" value="<?php echo @$employee_name; ?>">
					</div>
					<div class="col-md-3">
						<input type="text" name="department_name" class="form-control input-sm" placeholder="Department Name" value="<?php echo @$department_name; ?>">
					</div>
					<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
				</div>
				</form>

		<div class="table-scrollable">
				<?php $page_no=$this->Paginator->current('Employees'); $page_no=($page_no-1)*20; ?>

			 <table class="table table-hover">
				 <thead>
					<tr>
						<th><?= $this->Paginator->sort('id') ?></th>
						<th><?= $this->Paginator->sort('name') ?></th>
						<th><?= $this->Paginator->sort('sex') ?></th>
						<th><?= $this->Paginator->sort('department_id') ?></th>
						<th><?= $this->Paginator->sort('mobile') ?></th>
						<th><?= $this->Paginator->sort('Email') ?></th>
						<th class="actions"><?= __('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $i=0; foreach ($employees as $employee): $i++; ?>
					<tr>
						<td><?= h(++$page_no) ?></td>
						<td><?= h($employee->name) ?></td>
						<td><?= h($employee->sex) ?></td>
						<td><?= h($employee->department->name) ?></td>
						<td><?= h($employee->mobile) ?></td>
						<td><?= h($employee->email) ?></td>
						<td class="actions">
							<?php if(in_array(47,$allowed_pages) && @$employee->employee_companies[0]->freeze==0){ ?>
							<?php echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'view', $employee->id],array('escape'=>false,'class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View')); ?>
							<?php } ?>
							<?php if(in_array(48,$allowed_pages) && @$employee->employee_companies[0]->freeze==0){?>
								<?php echo $this->Html->link('<i class="fa fa-user"></i>',['action' => 'edit', $employee->id],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit Personal Info')); ?>
								<?php echo $this->Html->link('<i class="fa fa-users"></i>','/employee-family-members/index/'.$employee->id,array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit Family Info')); ?>
								<?php echo $this->Html->link('<i class="fa fa-warning"></i>','/EmployeeEmergencyDetails/index/'.$employee->id,array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit Emergency Details')); ?>
								<?php echo $this->Html->link('<i class="fa fa-anchor"></i>','/EmployeeReferenceDetails/index/'.$employee->id,array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit Reference Details')); ?>
								<?php echo $this->Html->link('<i class="fa fa-graduation-cap"></i>','/EmployeeWorkExperiences/index/'.$employee->id,array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit Work Experience')); ?>
								<?php echo $this->Html->link('<i class="fa fa-inr"></i>','/EmployeeSalaries/add/'.$employee->id,array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit Salary')); ?>
								<?php echo $this->Html->link('<i class="fa fa-file"></i>','/EmployeeSalaries/historyofSalary/'.$employee->id,array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'History of Salary')); ?>
							<?php } ?>
							<?php if(in_array(49,$allowed_pages)){?>
							<?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'EditCompany', $employee->id],array('escape'=>false,'class'=>'btn btn-xs green tooltips','data-original-title'=>'EditCompany')); ?>
							<?php } ?>
						</td>
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
</div>
