<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-comments"></i>Items
		</div>
	<!--<input type="text" class="form-control input-sm pull-right" placeholder="Search..." id="search3"  style="width: 20%;">-->
	<div class="portlet-body">
	<form method="GET" >
			<table class="table table-condensed" >
				<tbody>
					<tr>
					    <td width="20%">
							<?php echo $this->Form->input('item_name', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Item Name','value'=> h(@$item_name) ]); ?>
						</td>
						<td width="20%">
							<?php echo $this->Form->input('item_category', ['empty'=>'---Category---','options'=>$ItemCategories,'label' => false,'class' => 'form-control input-sm item_category select2me','placeholder'=>'Item Name','value'=> h(@$item_category) ]); ?>
						</td>
						<td width="20%">
							<?php echo $this->Form->input('item_group', ['empty'=>'---Group---','options'=>$ItemGroups,'label' => false,'class' => 'form-control input-sm item_group select2me','placeholder'=>'Item Name','value'=> h(@$item_group) ]); ?>
						</td>
						<td width="20%">
							<?php echo $this->Form->input('item_subgroup', ['empty'=>'---Sub Group---','options'=>$ItemSubGroups,'label' => false,'class' => 'form-control input-sm item_subgroup select2me','placeholder'=>'Item Name','value'=> h(@$item_subgroup) ]); ?>
						</td>
						<!--<td width="20%">
							<?php echo $this->Form->input('serial_no', ['empty'=>'---Serial Number Type---','options'=>$serialnos,'label' => false,'class' => 'form-control input-sm serial_no select2me','placeholder'=>'Item Name','value'=> h(@$serial_no) ]); ?>
						</td> -->	
						<td>
							<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		<div class="table-scrollable">
			
			<?php $page_no=$this->Paginator->current('Items'); $page_no=($page_no-1)*20; ?>
			 <table class="table table-bordered table-striped table-hover table-condensed" id="main_tble">
				 <thead>
					<tr>
						<th>Sr. No.</th>
						<th>Item Name</th>
						<th>Category</th>
						<th>Group</th>
						<th>Sub-Group</th>
						<th>Unit</th>
						<th>Serial No</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=0; foreach ($Items as $item): $i++; 
					?>
					<tr>
						<td><?= h(++$page_no) ?></td>
						<td><?= h($item->name) ?></td>
						<td><?= $item->item_category->name ?></td>
						<td><?= $item->item_group->name ?></td>
						<td><?= $item->item_sub_group->name ?></td>
						<td><?= $item->unit->name ?></td>
						<?php 
						if(@$item->item_companies[0]->serial_number_enable==1){ ?>
						<td>Enable</td>
						<?php } else if(@$item->item_companies[0]->serial_number_enable==0) { ?>
						<td>Disable</td>						
						<?php }  ?>
						<td class="actions">
							<?php if(in_array(52,$allowed_pages)){ ?>
							<?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $item->id],array('escape'=>false,'class'=>'btn btn-xs blue')); ?>
							<?php $this->Form->postLink('<i class="fa fa-trash"></i> ',
								['action' => 'delete', $item->id], 
								[
									'escape' => false,
									'class' => 'btn btn-xs btn-danger',
									'confirm' => __('Are you sure ?', $item->id)
								]
							); ?>
							<?php } ?>
							<?php if(in_array(53,$allowed_pages)){ ?>
							<?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'EditCompany', $item->id],array('escape'=>false,'class'=>'btn btn-xs green tooltips','data-original-title'=>'Add/Remove in other companies, Freeze/Unfreeze, Serial Number Enable/Disable')); ?>
							<?php } ?>
						</td>
					</tr>
					<?php  endforeach; ?>
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
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {

var $rows = $('#main_tble tbody tr');
	$('#search3').on('keyup',function() {
	
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
	////
});
		
</script>