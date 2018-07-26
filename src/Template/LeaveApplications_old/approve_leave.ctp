<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-purple-intense ">Approve Leave Application</span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
				<table width="70%">
					<tr>
						<td width="15%"><b>Employee: </b></td>
						<td><?php echo $LeaveApplication->employee->name; ?></td>
						<td width="15%"><b>No. of leaves: </b></td>
						<td><?php echo $LeaveApplication->day_no; ?></td>
					</tr>
					<tr>
						<td width="15%"><b>Leave Dates: </b></td>
						<td>
							From <?php echo $LeaveApplication->from_leave_date->format('d-m-Y'); ?> 
							<?php if($LeaveApplication->from_full_half!='Full Day'){ echo '('.$LeaveApplication->from_full_half.')'; } ?> 
							To <?php echo $LeaveApplication->to_leave_date->format('d-m-Y'); ?>
							<?php if($LeaveApplication->to_full_half!='Full Day'){ echo '('.$LeaveApplication->to_full_half.')'; } ?>
						</td>
						<td width="15%"><b>Leave Type: </b></td>
						<td><?php echo $LeaveApplication->leave_type->leave_name; ?></td>
					</tr>
					<tr>
						<td width="15%"><b>Reason for leave: </b></td>
						<td><?php echo $LeaveApplication->leave_reason; ?></td>
						<td width="15%"></td>
						<td></td>
					</tr>
				</table>
			
			</div>
		</div>
		<hr/>
		
		<form method="post">
			<table width="100%">
				<tr>
					<td>
						<div class="row">
							<div class="col-md-12">
								<?php 
								if($LeaveApplication->leave_status=='approved'){
									$single_multiple=$LeaveApplication->approve_single_multiple;
								}else{
									$single_multiple=$LeaveApplication->single_multiple;
								}
								echo $this->Form->radio(
									'approve_single_multiple',
									[
										['value' => 'Single', 'text' => 'Single Day'],
										['value' => 'Multiple', 'text' => 'Multiple Days']
									],['value'=>$single_multiple]
								); ?>
							</div>
						</div>
						<div class="row" id="date_from">
							<div class="col-md-8">
								<div class="form-group" >
									<label class="control-label  label-css">Date of Leave Required (From)</label>   
									<?php 
									if($LeaveApplication->leave_status=='approved'){
										$from_leave_date=$LeaveApplication->approve_leave_from;
									}else{
										$from_leave_date=$LeaveApplication->from_leave_date;
									}
									echo $this->Form->input('approve_leave_from', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','value'=>$from_leave_date->format('d-m-Y')]); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group" id="from_half">
									<label class="control-label  label-css">.</label>  
									<?php
									if($LeaveApplication->leave_status=='approved'){
										$from_full_half=$LeaveApplication->approve_full_half_from;
									}else{
										$from_full_half=$LeaveApplication->from_full_half;
									}
									$options[]=['text' =>'Full Day', 'value' => 'Full Day'];
									$options[]=['text' =>'First Half Day', 'value' => 'First Half Day'];
									$options[]=['text' =>'Second Half Day', 'value' => 'Second Half Day'];
									echo $this->Form->input('approve_full_half_from', ['label' => false,'options' => $options,'class' => 'form-control input-sm','value' => $from_full_half]); ?>
								</div>
							</div>
						</div>
						<div class="row" id="date_to">
							<div class="col-md-8">
								<div class="form-group" >
									<label class="control-label  label-css">Date of Leave Required (To)</label>   
									<?php 
									if($LeaveApplication->leave_status=='approved'){
										$to_leave_date=$LeaveApplication->approve_leave_to;
									}else{
										$to_leave_date=$LeaveApplication->to_leave_date;
									}
									echo $this->Form->input('approve_leave_to', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','value'=>$to_leave_date->format('d-m-Y')]); ?>
								</div>
							</div>
						   <div class="col-md-4">
								<div class="form-group" id="to_half">
									<label class="control-label  label-css">.</label>  
									<?php 
									if($LeaveApplication->leave_status=='approved'){
										$to_full_half=$LeaveApplication->approve_full_half_to;
									}else{
										$to_full_half=$LeaveApplication->to_full_half;
									}
									echo $this->Form->input('approve_full_half_to', ['label' => false,'options' => $options,'class' => 'form-control input-sm','value' => $to_full_half]); ?>
								</div>
							</div>
							
						</div>
					</td>
					<td align="center">
						<div id="qwerty"></div>
						<table>
							<tr>
								<td>
									<label class="control-label  label-css">Intimated  Leaves </label>   
									<?php echo $this->Form->input('intimated_leave', ['type'=>'text','label' => false,'class'=>'form-control input-sm intimated_leave','value'=>@$LeaveApplication->intimated_leave]); ?>
								</td>
								<td>
									<label class="control-label  label-css">Unintimated Leaves </label>   
									<?php echo $this->Form->input('unintimated_leave', ['type'=>'text','label' => false,'class'=>'form-control input-sm unintimated_leave','readonly']); ?>
								</td>
								<td>
									
								</td>
							</tr>
							<tr>
								<td>
									<label class="control-label  label-css">Paid Leaves </label>   
									<?php echo $this->Form->input('paid_leaves', ['type'=>'text','label' => false,'class'=>'form-control input-sm','readonly']); ?>
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
	

	
	$('input[name=intimated_leave]').live("keyup",function(){
		countLeaves();
	});
	$('input[name=approve_leave_to]').live("blur",function(){
		countLeaves();
	});
	
	$('input[name=approve_single_multiple]').live("click",function(){
		var single_multiple=$(this).val();
		expandHalfDay(single_multiple);
		if(single_multiple=="Single"){
			var q=$('input[name=approve_leave_from]').val();
			$('input[name=approve_leave_to]').val(q);
		}
		countLeaves();
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
		
		var w=$('input[name=approve_leave_to]').val();
		if(w==""){
			var t=$('input[name=approve_leave_from]').val();
			$('input[name=approve_leave_to]').val(t);
		}
		
		var employee_id='<?php echo $LeaveApplication->employee_id; ?>';
		var leaveAppId='<?php echo $LeaveApplication->id; ?>';
		var url="<?php echo $this->Url->build(['controller'=>'LeaveApplications','action'=>'leaveInfo']); ?>";
        url=url+'/'+employee_id+'/'+leaveAppId; 
        $.ajax({
            url: url,
            type: 'GET',
        }).done(function(response) {
			var res = response.split("-");
			var ML=res[0];
			var PPL=res[1];
			var PUL=res[2];
			
			$('div#qwerty').html('ML:'+ML+', PPL:'+PPL+', PUL'+PUL);
			
			var p=$('input[name="approve_leave_from"]').val().split('-');
			var approve_leave_from = new Date(p[2], p[1] - 1, p[0]);
			
			var p=$('input[name="approve_leave_to"]').val().split('-');
			var approve_leave_to = new Date(p[2], p[1] - 1, p[0]);
			
			var diff = new Date(approve_leave_to - approve_leave_from);
			var days = diff/1000/60/60/24;
			days=days+1;
			
			if(days<0){
				var q=$('input[name="approve_leave_from"]').val();
				$('input[name="approve_leave_to"]').val(q);
			}
			
			var single_multiple=$('input[name="approve_single_multiple"]:checked').val();
			var approve_full_half_from=$('select[name="approve_full_half_from"] option:selected').val();
			var approve_full_half_to=$('select[name="approve_full_half_to"] option:selected').val();
			if(single_multiple=='Single'){
				if(approve_full_half_from!='Full Day'){
					days=0.5;
				}else{
					days=1;
				}
			}else{
				if(approve_full_half_from=='Second Half Day'){
					days-=0.5;
				}
				if(approve_full_half_to=='First Half Day'){
					days-=0.5;
				}
			}
			var T=days;
			
			var I=$('input[name="intimated_leave"]').val();
			
			if(I>T){
				I=0;
				$('input[name="intimated_leave"]').val(0);
			}
			var U=T-I;
			$('input[name="unintimated_leave"]').val(U);
			
			var PL=0; var UPL=0;
			var R=5-PUL;
			
			var X=R-U;
			if(X>=0){
				var Q=ML-PPL;
				var Z=Q-U;
				if(Z>=0){ PL=U; }
				if(Z<0){ PL=Q; UPL=Math.abs(Z); }
			}else{
				var Q=ML-PPL;
				var Z=Q-R;
				if(Z>=0){ PL=R; UPL=Math.abs(X); }
				if(Z<0){ PL=Q; UPL=Math.abs(Z); UPL+=Math.abs(X); }
			}
			
			
			var B=ML-(parseFloat(PPL)+parseFloat(PL));
			var C=B-I;
			if(C>=0){ PL+=parseFloat(I);  }
			if(C<0){ PL+=B; UPL+=Math.abs(C);  }
			$('input[name="paid_leaves"]').val(PL);
			$('input[name="unpaid_leaves"]').val(UPL);
			$('input[name="total_approved_leaves"]').val(T);
        });
		
		
		
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