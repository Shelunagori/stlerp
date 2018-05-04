<?php echo $this->Html->css('/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<div id="myModal4" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false" style="display:block; padding-right: 12px;"><div class="modal-backdrop fade in" ></div>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id="result_ajax">
			<h4>Approve Loan</h4>
				<div style=" overflow: auto; height: 300px;">
					<div class="col-md-4">
							<div class="form-group leave_type">
								<label class="control-label  label-css">Loan Amount</label>
								<?php echo $this->Form->input('approve_amount_of_loan', ['type'=>'text','label' => false,'class' => 'form-control input-sm approve_amount_of_loan','value'=>$LoanApplications->amount_of_loan]); ?>
							</div>
					</div>
					<div class="col-md-4">
							<div class="form-group">
								<label class="control-label  label-css">Effected From </label>
								<?php echo $this->Form->input('starting_date_of_loan', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker starting_date_of_loan','data-date-format'=>'dd-mm-yyyy','value'=>'','id'=>'from_leave_date']); ?>
							</div>
					</div>
				
					<div class="col-md-4">
							<div class="form-group">
								<label class="control-label  label-css">Effected To </label>
								<?php echo $this->Form->input('ending_date_of_loan', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker ending_date_of_loan','data-date-format'=>'dd-mm-yyyy','value'=>'']); ?>
							</div>
					</div>
					
					<div class="col-md-4">
							<div class="form-group">
								<label class="control-label  label-css">No of Instalment</label>
								<?php echo $this->Form->input('no_of_instalment', ['type'=>'text','label' => false,'class' => 'form-control input-sm no_of_instalment']); ?>
							</div>
					</div>
					<div class="col-md-4">
							<div class="form-group">
								<label class="control-label  label-css">Instalment Amount</label>
								<?php echo $this->Form->input('instalment_amount', ['type'=>'text','label' => false,'class' => 'form-control input-sm instalment_amount']); ?>
							</div>
					</div>
					<div class="col-md-9 ">
							<div class="form-group comment1">
								<label class="control-label  label-css">Comment</label>
								<?php echo $this->Form->textarea('comment', ['type'=>'text','label' => false,'class' => 'form-control input-sm comment']); ?>
							</div>
					</div>
				
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn default closebtn4">Close</button>
				<button class="btn btn-primary insert_tc">Approve</button>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<?php echo $this->Html->script('/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>
<?php echo $this->Html->script('/assets/admin/pages/scripts/components-pickers.js'); ?>

<script>
$(document).ready(function() {   
	ComponentsPickers.init();
	$('#from_leave_date').datepicker();

	$('.no_of_instalment').on("keyup",function() {
		var amount_of_loan=$('.approve_amount_of_loan').val(); 
		var installment=$(this).val(); 
		var int_amt=amount_of_loan/installment; 
		$('.instalment_amount').val(round(int_amt,2));
	});
	$('.insert_tc').on("click",function() {
		 var id ="<?php echo $id; ?>";
		 var approve_amount_of_loan = $('.approve_amount_of_loan').val();
		 var starting_date_of_loan = $('.starting_date_of_loan').val();
		 var ending_date_of_loan = $('.ending_date_of_loan').val();
		 var no_of_instalment = $('.no_of_instalment').val();
		 var instalment_amount = $('.instalment_amount').val();
		 var comment = $('.comment').val();
		 
		 //var leave_type = $(this).closest("div.leave_type").find("option:selected").val();
		
		
		var url="<?php echo $this->Url->build(['controller'=>'LoanApplications','action'=>'approved']); ?>";
			url=url+'/'+id+'/'+approve_amount_of_loan+'/'+starting_date_of_loan+'/'+ending_date_of_loan+'/'+no_of_instalment+'/'+instalment_amount+'/'+comment,
			$.ajax({
				url: url,
				type: 'GET',
			}).done(function(response) { 
				
			});
		//$("#myModal3").show();
    });
});
</script>