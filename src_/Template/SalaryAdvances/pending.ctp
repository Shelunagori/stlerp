<?php if(sizeof($salaryAdvances->toArray())>0){ ?>
<div class="portlet light bordered">
	<div style="font-size:16px;">
		<span class="caption-subject font-purple-intense ">Pending Salary Advance Request</span>
	</div>
	<div class="portlet-body">
		<div class="table-scrollable">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Employee Name</th>
						<th>Applied Amount</th>
						<th>Reason</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=0; foreach ($salaryAdvances as $salaryAdvance):  ?>
					<tr>
						<td><?= h(++$i) ?></td>
						<td><?= h($salaryAdvance->employee->name) ?></td>
						<td align="right"><?= h($salaryAdvance->applied_amount) ?></td>
						<td><?= h($salaryAdvance->reason) ?></td>
						<td><?php echo $this->Html->link('Approve',['controller' => 'SalaryAdvances','action' => 'approve', $salaryAdvance->id],array('escape'=>false,'class'=>'btn btn-xs blue')); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php }?>