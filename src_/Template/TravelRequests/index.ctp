<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel ucfirst">Travel Requests</span> 
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
								<input type="text" name="purpose" class="form-control input-sm" value="<?php echo @$purpose; ?>" placeholder="Purpose"/>
							</td>
							<td>
								<input type="text" name="tFrom" class="form-control input-sm date-picker" value="<?php echo @$tFrom; ?>" placeholder="Travel From" data-date-format='dd-mm-yyyy'/>
							</td>
							<td>
								<input type="text" name="tTo" class="form-control input-sm date-picker" value="<?php echo @$tTo; ?>" placeholder="Travel To" data-date-format='dd-mm-yyyy'/>
							</td>
							<td>
								<button type="submit" class="btn btn-sm blue">Filter</button>
							</td>
						</tr>
					</table>
				</form>
				<?php } ?>
				
				<?php $page_no=$this->Paginator->current('travelRequests'); $page_no=($page_no-1)*20; ?>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Name</th>
							<th>Purpose</th>
							<th>Applied Advance Amount</th>
							<th>Approved Advance Amount</th>
							<th>Travel From Date</th>
							<th>Travel To Date</th>
							<th>Status</th>
							<th class="actions"><?= __('Actions') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0; foreach ($travelRequests as $travelRequest):  ?>
						<tr>
							<td><?= h(++$page_no) ?></td>
							<td><?= h($travelRequest->employee->name) ?></td>
							<td><?= h($travelRequest->purpose) ?></td>
							<td align="right"><?= $travelRequest->applied_advance_amt==0?'-':$travelRequest->applied_advance_amt ?></td>
							<td align="right"><?= $travelRequest->advance_amt==0?'-':$travelRequest->advance_amt ?></td>
							<td><?= h(date("d-m-Y",strtotime($travelRequest->travel_from_date))) ?></td>
							<td><?= h(date("d-m-Y",strtotime($travelRequest->travel_to_date))) ?></td>
							<td><?= h($travelRequest->status) ?></td>
							<td class="actions" >
								<?php if($travelRequest->status=="Pending"){?>
								<?php 
								if(in_array(200,$allowed_pages)){
									echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $travelRequest->id],array('escape'=>false,'class'=>'btn btn-xs blue'));
								}	
								if(in_array(201,$allowed_pages)){
								?>
								<?= $this->Form->postLink('<i class="fa fa-trash"></i> ',
								['action' => 'delete', $travelRequest->id], 
								[
									'escape' => false,
									'class' => 'btn btn-xs btn-danger',
									'confirm' => __('Are you sure ?', $travelRequest->id)
								]
							) 
								
							?>
								<?php }} ?>
							<?php 
							if(in_array(190,$allowed_pages)){
								echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'view', $travelRequest->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View ')); } ?>
							<?php if($empData->department->name=='HR & Administration' || $empData->designation->name=='Director'){
								if($travelRequest->status=='approve'){
									if(in_array(189,$allowed_pages)){
										echo $this->Html->link('<i class="fa fa-edit"></i>',['action' => 'approve', $travelRequest->id],['escape'=>false,'target'=>'_blank','class'=>'btn btn-xs purple tooltips','data-original-title'=>'Edit after approve ']);
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