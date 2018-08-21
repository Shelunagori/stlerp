<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-purple-intense ">Approve Loan Application</span>
		</div>
	</div>
	<div class="portlet-body">
		<form method="post">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group leave_type">
					<label class="control-label  label-css">Last Loan Amount</label><br/>
					<span> <?php echo $lastLoanApplication->approve_amount_of_loan; ?></span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label  label-css">Last Loan Reason</label><br/>
					<span> <?php echo $lastLoanApplication->reason_for_loan; ?></span>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label  label-css">Installments for Last Loan </label><br/>
					<?php 
					$tot=0;
					foreach($lastLoanApplication->loan_installments as $loan_installment) {
						echo '<span>'.date('F', mktime(0, 0, 0, $loan_installment->month, 10)).'-'.$loan_installment->year.' (₹ '.$loan_installment->amount.')</span><br/>';
						$tot+=$loan_installment->amount;
					} ?>
					<span><b>Total ₹ <?php echo $tot; ?></b></span>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group leave_type">
					<label class="control-label  label-css">Employee</label><br/>
					<span> <?php echo $LoanApplications->employee->name; ?></span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label  label-css">Reason for loan </label><br/>
					<span> <?php echo $LoanApplications->reason_for_loan; ?></span>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label  label-css">Remark </label><br/>
					<span> <?php echo $LoanApplications->remark; ?></span>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group leave_type">
					<label class="control-label  label-css">Applied Loan Amount</label>
					<?php echo $this->Form->input('amount_of_loan', ['type'=>'text','label' => false,'class' => 'form-control input-sm','value'=>$LoanApplications->amount_of_loan, 'readonly', 'style'=>'text-align:right;']); ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group leave_type">
					<label class="control-label  label-css">Approve Loan Amount</label>
					<?php echo $this->Form->input('approve_amount_of_loan', ['type'=>'text','label' => false,'class' => 'form-control input-sm approve_amount_of_loan','value'=>$LoanApplications->approve_amount_of_loan, 'style'=>'text-align:right;']); ?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label  label-css">Installment start from </label>
					<select name="installment_from" class="form-control input-sm">
						<option value="4-<?php echo $yearFrom; ?>">Apr-<?php echo $yearFrom; ?></option>
						<option value="5-<?php echo $yearFrom; ?>">May-<?php echo $yearFrom; ?></option>
						<option value="6-<?php echo $yearFrom; ?>">June-<?php echo $yearFrom; ?></option>
						<option value="7-<?php echo $yearFrom; ?>">July-<?php echo $yearFrom; ?></option>
						<option value="8-<?php echo $yearFrom; ?>">Aug-<?php echo $yearFrom; ?></option>
						<option value="9-<?php echo $yearFrom; ?>">Sept-<?php echo $yearFrom; ?></option>
						<option value="10-<?php echo $yearFrom; ?>">Oct-<?php echo $yearFrom; ?></option>
						<option value="11-<?php echo $yearFrom; ?>">Nov-<?php echo $yearFrom; ?></option>
						<option value="12-<?php echo $yearFrom; ?>">Dec-<?php echo $yearFrom; ?></option>
						<option value="1-<?php echo $yearFrom; ?>">Jan-<?php echo $yearFrom+1; ?></option>
						<option value="2-<?php echo $yearFrom; ?>">Feb-<?php echo $yearFrom+1; ?></option>
						<option value="3-<?php echo $yearFrom; ?>">Mar-<?php echo $yearFrom+1; ?></option>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label  label-css">No of Instalment</label>
					<?php echo $this->Form->input('no_of_instalment', ['type'=>'text','label' => false,'class' => 'form-control input-sm no_of_instalment','required', 'value'=>$LoanApplications->no_of_instalment]); ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label  label-css">Instalment Amount</label>
					<?php echo $this->Form->input('instalment_amount', ['type'=>'text','label' => false,'class' => 'form-control input-sm instalment_amount','readonly', 'value'=>$LoanApplications->instalment_amount]); ?>
				</div>
			</div>
			<div class="col-md-4">
					<div class="form-group comment1">
						<label class="control-label  label-css">Comment</label>
						<?php echo $this->Form->textarea('comment', ['type'=>'text','label' => false,'class' => 'form-control input-sm comment', 'value'=>$LoanApplications->comment]); ?>
					</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label  label-css">Transaction Date</label>
					<?php echo $this->Form->input('trans_date', ['type'=>'text','label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','value'=>$LoanApplications->trans_date==null?'':$LoanApplications->trans_date->format('d-m-Y'),'required']); ?>

				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group comment1">
					<label class="control-label  label-css">Bank</label><br/>
					<select name="bank_id" class="form-control input-sm">
						<?php foreach($bankCashes as $bankId=>$bankName){
							if($bankId == "145"){
								echo '<option value="'.$bankId.'" selected>'.$bankName.'</option>';
							}else{
								echo '<option value="'.$bankId.'">'.$bankName.'</option>';
							}
						} ?>
					</select>
				</div>
			</div>
		</div>
		<button type="submit" class="btn blue">APPROVE</button>
		</form>
	</div>
</div>

					
					
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>
$(document).ready(function() {   
	$('.no_of_instalment').on("keyup",function() {
		var amount_of_loan=$('.approve_amount_of_loan').val(); 
		var installment=$(this).val(); 
		var int_amt=amount_of_loan/installment; 
		$('.instalment_amount').val(round(int_amt,2));
	});
});
</script>