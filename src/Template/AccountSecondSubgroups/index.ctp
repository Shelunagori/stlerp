<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Account Second Sub-Group</span> 
		</div>
		<div class="actions">
			<?php echo $this->Html->link('Account Group','/AccountGroups/',array('escape'=>false,'class'=>'btn btn-default')); ?>
			<?php echo $this->Html->link('Account First Sub Group','/AccountFirstSubgroups/',array('escape'=>false,'class'=>'btn btn-default')); ?>
			<?php echo $this->Html->link('Account Second Sub Group','/AccountSecondSubgroups/',array('escape'=>false,'class'=>'btn btn-primary')); ?>
			<?php echo $this->Html->link('Ledger Account','/LedgerAccounts/',array('escape'=>false,'class'=>'btn btn-default')); ?>
		</div>
		<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<div class="row ">
		<div class="col-md-12">
		<?= $this->Form->create($accountSecondSubgroup) ?>
			<div class="form-body">
				<div class="form-group">
					<div class="col-md-5">
					<label class="control-label">Account First Sub-Group <span class="required" aria-required="true">*</span></label>
						<?php 
						echo $this->Form->input('account_first_subgroup_id', ['options' => $accountFirstSubgroups,'empty' => "--Select--",'label' => false,'id'=>'search','class' => 'form-control select2me ' ,'required']); 
						?>
					</div>
					<div class="col-md-5">
					<label class="control-label">Second Sub-Group <span class="required" aria-required="true">*</span></label>
						<?php 
						echo $this->Form->input('name', ['label' => false,'class' => 'form-control input-sm ','placeholder'=>'Name']); 
						?>
					</div>
					
					<div class="col-md-2">
					<label class="control-label"> <span class="required" aria-required="true"></span> </label><br/>
						<?php 
						echo $this->Form->button(__('ADD'),['class'=>'btn btn-primary']); 
						?>
					</div>
				</div>
			</div>
		<?= $this->Form->end() ?>
		</div>
		<!-- END FORM-->
		</div>
	</div>
		
		
	<div class="portlet-body">
	<input type="text" class="form-control input-sm pull-right" placeholder="Search..." id="search4"  style="width: 20%;" >
                <br/>
		<div class="row">
			<div class="col-md-12">
				
				
				<table class="table table-bordered table-striped table-hover" id="main_tble" style="margin-top:20px;">
						<thead>
							<tr>
								<th>S.No</th>
								<th>Category</th>
								<th>Account Group</th>
								<th>First Sub-Group</th>
								<th>Second Sub-Group</th>
								<th width="80"><?= __('Actions') ?></th>
							</tr>
					
					</thead>
					<tbody>
					 <?php $i=0; foreach ($accountSecondSubgroups as $accountSecondSubgroup): ?>
						<tr>
							<td><?= h(++$i) ?></td>
							<td><?=  h($accountSecondSubgroup->account_first_subgroup->account_group->account_category->name)?></td>
							<td><?=  h($accountSecondSubgroup->account_first_subgroup->account_group->name)?></td>
							<td><?=  h($accountSecondSubgroup->account_first_subgroup->name)?></td>
							<td><?= h($accountSecondSubgroup->name) ?></td>
							 <td class="actions">
							<?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $accountSecondSubgroup->id],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit')); ?>
								<?= $this->Form->postLink('<i class="fa fa-trash"></i> ',
								['action' => 'delete', $accountSecondSubgroup->id], 
								[
									'escape' => false,
									'class' => 'btn btn-xs btn-danger',
									'confirm' => __('Are you sure ?', $accountSecondSubgroup->id)
								]
							) ?>
								
								
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				
			</div>
		</div>
	</div>
</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
var $rows = $('#main_tble tbody tr');
	$('#search').on('change',function() {
		var val = $.trim($(this).find('option:selected').text()).replace(/ +/g, ' ').toLowerCase();
		var v = $(this).find('option:selected').val();
		if(v){
			$rows.show().filter(function() {
				var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
				return !~text.indexOf(val);
			}).hide();
		}else{
			$rows.show();
		}
	});

	/////Search Module
	$('#search4').on('keyup',function() {
    		var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
    		var v = $(this).val();
    		if(v){
    			$rows.show().filter(function() {
    				var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
    				return !~text.indexOf(val);
    			}).hide();
    		}else{
    			$rows.show();
    		}
    	});
});
</script>


