 
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Employee Attendence</span> 
		</div>
		<form method="GET" >
				<table class="table table-condensed">
					<tbody>
						<tr>
							<td width="15%">
								<input type="text" name="From" class="form-control input-sm date-picker" placeholder="Date From" value="<?php echo @$From; ?>" data-date-format="mm-yyyy" >
							</td>
							<td><button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button></td>
						</tr>
					</tbody>
				</table>
			</form>
		
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
			
				<?php $page_no=$this->Paginator->current('employeeSalaries'); $page_no=($page_no-1)*20; ?>
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Employee Name</th>
							<th>Month</th>
							<th>Present Day</th>
							<th>Leave</th>
							
						</tr>
					</thead>
					<tbody>
						<?php  $i=0; foreach ($employeeAttendances as $data): $i++; 
						$month=date("F",$data->month);
$month = date('F', mktime(0, 0, 0, $data->month, 10));
						?>
						
						<tr>
							<td><?= h(++$page_no) ?></td>
							<td><?= h($data->employee->name) ?></td>
							<td><?= h($month) ?></td>
							<td><?= h($data->present_day) ?></td>
							<td><?= h($data->no_of_leave) ?></td>
							
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
<style>
#sortable li{
	cursor: -webkit-grab;
}
</style>
<?php echo $this->Html->css('/drag_drop/jquery-ui.css'); ?>


