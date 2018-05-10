<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-purple-intense ">Approve Leave Application</span>
		</div>
	</div>
	<div class="portlet-body">
		<h4><?php echo $LeaveApplication->day_no; ?> days leave application form </b> <?php echo $LeaveApplication->employee->name; ?></h4>
		<h5>
			From <?php echo $LeaveApplication->from_leave_date->format('d-m-Y'); ?> 
			<?php if($LeaveApplication->from_full_half!='Full Day'){ echo '('.$LeaveApplication->from_full_half.')'; } ?> 
			To <?php echo $LeaveApplication->to_leave_date->format('d-m-Y'); ?>
			<?php if($LeaveApplication->to_full_half!='Full Day'){ echo '('.$LeaveApplication->to_full_half.')'; } ?>
		</h5>
		<div class="row">
			<div class="col-md-4">
				<b>Leave Type:</b> <?php echo $LeaveApplication->leave_type->leave_name; ?>
			</div>
			<div class="col-md-4">
				
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<b>Reason for leave:</b> <br/>
				<?php echo $LeaveApplication->leave_reason; ?>
			</div>
		</div>
		<form method="post">
		<div class="row">
			<div class="col-md-6">
				<?php echo $this->Form->radio(
					'approve_single_multiple',
					[
						['value' => 'Single', 'text' => 'Single Day'],
						['value' => 'Multiple', 'text' => 'Multiple Days']
					],['value'=>$LeaveApplication->single_multiple]
				); ?>
			</div>
		</div>
		<div class="row" id="date_from">
			<div class="col-md-3">
				<div class="form-group" >
					<label class="control-label  label-css">Date of Leave Required (From)</label>   
					<?php echo $this->Form->input('approve_leave_from', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','value'=>$LeaveApplication->from_leave_date->format('d-m-Y')]); ?>
				</div>
			</div>
		    <div class="col-md-2">
				<div class="form-group" id="from_half">
					<label class="control-label  label-css">.</label>  
					<?php
					$options[]=['text' =>'Full Day', 'value' => 'Full Day'];
					$options[]=['text' =>'First Half Day', 'value' => 'First Half Day'];
					$options[]=['text' =>'Second Half Day', 'value' => 'Second Half Day'];
					echo $this->Form->input('approve_full_half_from', ['label' => false,'options' => $options,'class' => 'form-control input-sm','value' => $LeaveApplication->from_full_half]); ?>
				</div>
			</div>
		</div>
		<div class="row" id="date_to">
			<div class="col-md-3">
				<div class="form-group" >
					<label class="control-label  label-css">Date of Leave Required (To)</label>   
					<?php echo $this->Form->input('approve_leave_to', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','value'=>$LeaveApplication->to_leave_date->format('d-m-Y')]); ?>
				</div>
			</div>
		   <div class="col-md-2">
				<div class="form-group" id="to_half">
					<label class="control-label  label-css">.</label>  
					<?php 
					echo $this->Form->input('approve_full_half_to', ['label' => false,'options' => $options,'class' => 'form-control input-sm','value' => $LeaveApplication->to_full_half]); ?>
				</div>
			</div>
		</div>
		<table>
			<tr>
				<td>
					<label class="control-label  label-css">Paid Leaves </label>   
					<?php echo $this->Form->input('paid_leaves', ['type'=>'text','label' => false,'class'=>'form-control input-sm']); ?>
				</td>
				<td>
					<label class="control-label  label-css">Unpaid Leaves </label>   
					<?php echo $this->Form->input('unpaid_leaves', ['type'=>'text','label' => false,'class'=>'form-control input-sm','readonly']); ?>
				</td>
				<td>
					<label class="control-label  label-css">Total Approved Leaves </label>   
					<?php echo $this->Form->input('total_approved_leaves', ['type'=>'text','label' => false,'class'=>'form-control input-sm','readonly']); ?>
				</td>
			</tr>
		</table>
		<button type="submit" class="btn blue sub">Approve</button>
		</form>
	</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function(){
	
	
	$('input[name=approve_single_multiple]').live("click",function(){
		var single_multiple=$(this).val();
		expandHalfDay(single_multiple);
	});
	
	var single_multiple=$('input[name=approve_single_multiple]:checked').val();
	expandHalfDay(single_multiple);
	
	function expandHalfDay(single_multiple){
		if(single_multiple=="Single"){
			$('#date_to').hide();
			
			$('#from_half').find('select option[value="First Half Day"]').removeAttr('disabled','disabled');
			//$('#from_half').find('select option[value="Full Day"]').attr('selected','selected');
		}else{
			$('#date_to').show();
			
			var q=$('#from_half').find('select option:selected').val();
			if(q=='First Half Day'){
				$('#from_half').find('select option[value="Full Day"]').attr('selected','selected');
			}
			
			$('#to_half').find('select option[value="Second Half Day"]').attr('disabled','disabled');
			//$('#to_half').find('select option[value="Full Day"]').attr('selected','selected');
			
			$('#from_half').find('select option[value="First Half Day"]').attr('disabled','disabled');
			//$('#from_half').find('select option[value="Full Day"]').attr('selected','selected');
		}
	}
	countLeaves();
	function countLeaves(){
		var p=$('input[name="approve_leave_from"]').val().split('-');
		var approve_leave_from = new Date(p[2], p[1] - 1, p[0]);
		
		var p=$('input[name="approve_leave_to"]').val().split('-');
		var approve_leave_to = new Date(p[2], p[1] - 1, p[0]);
		
		var diff = new Date(approve_leave_to - approve_leave_from);
		var days = diff/1000/60/60/24;
		days=days+1;
		
		var single_multiple=$('input[name="approve_single_multiple"]:checked').val();
		var approve_full_half_from=$('select[name="approve_full_half_from"] option:selected').val();
		var approve_full_half_to=$('select[name="approve_full_half_to"] option:selected').val();
		if(single_multiple=='Single'){
			if(approve_full_half_from!='Full Day'){
				days-=0.5;
			}
		}else{
			if(approve_full_half_from=='Second Half Day'){
				days-=0.5;
			}
			if(approve_full_half_to=='First Half Day'){
				days-=0.5;
			}
		}
		
		$('input[name="total_approved_leaves"]').val(days);
		var paid_leaves=$('input[name="paid_leaves"]').val();
		if(!paid_leaves){
			$('input[name="paid_leaves"]').val(0);
			var paid_leaves=0;
		}
		$('input[name="unpaid_leaves"]').val(days-paid_leaves);
	}
	
	$('input[name="unpaid_leaves"]').live("keyup",function(){
		countLeaves();
	});
	$('input[name="paid_leaves"]').live("keyup",function(){
		countLeaves();
	});
	$(".sub").mouseover(function(){
		countLeaves();
	});
});
</script>