 
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Employee salary</span> 
		</div>
		
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
			
				<?php $page_no=$this->Paginator->current('employeeSalaries'); $page_no=($page_no-1)*20; ?>
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Employee Name</th>
							<th>Employee Designation</th>
							<th>From</th>
							<th>To</th>
							
						</tr>
					</thead>
					<tbody>
						<?php  $i=0; foreach ($employeeSalaries as $data): $i++;?>
						<tr>
							<td><?= h(++$page_no) ?></td>
							<td><?= h($data->employee->name) ?></td>
							<td><?= h($data->employee->designation->name) ?></td>
							<td><?= h(date('d-m-Y',strtotime($data->effective_date_from))) ?></td>
							<td><?= h(date('d-m-Y',strtotime($data->effective_date_to))) ?></td>
							
							
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


