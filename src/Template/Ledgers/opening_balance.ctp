
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Opening Balance</span>
		</div>
		
		<div class="actions">
			<?php echo $this->Html->link('Opening Balance','/opening-balance/',array('escape'=>false,'class'=>'btn btn-primary')); ?>
			<?php echo $this->Html->link('Opening Balance View','/ledgers/opening-balance-view/',array('escape'=>false,'class'=>'btn btn-default')); ?>
			
		</div>
		
		
	</div>
	<div class="portlet-body form">

    <?= $this->Form->create($ledger,['id'=>'opening_balance']) ?>
    <div class="form-body">
		<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="col-md-6 control-label">Ledger Accounts</label>
					<?= $this->Form->input('ledger_account_id', ['empty'=>'--select--','options' => $ledgerAccounts,'label' => false,'class'=>'form-control input-sm select2me']) ?>
						</div>
				</div>
				<div class="col-md-2">
						<div class="form-group">
							<label class="control-label">Date</label>
							<?php echo $this->Form->input('transaction_date', ['type' => 'text','label' => false,'class' => 'form-control input-sm ','data-date-format' => 'dd-mm-yyyy','value' =>date("d-m-Y",strtotime($financial_year->date_from)),'readonly']); ?>
						</div>
				</div>
				<div class="col-md-1">
						<div class="form-group">
							<label class="control-label">Dr/Cr</label>
							<?php echo $this->Form->input('type_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm   refDrCr','value'=>'Dr']); ?>
						</div>
				</div>
				
				<div class="col-md-3">
					<div class="form-group">
						<label class="col-md-6 control-label">Amount</label>
						<?php echo $this->Form->input('amount', ['type' => 'text','label' => false,'class' => 'form-control input-sm amount','placeholder'=>'Amount']); ?>
						</div>
				</div>
				
				
			<?= $this->Form->hidden('voucher_source',['value'=>'Opening Balance']) ?>
			</div>
	<table class="table table-bordered" id="main_table" style="width:83%">
	<thead>
	<tr>
	<th>Ref. No.</th>
	<th>Amount</th>
	<th>Dr/Cr</th>
	<th></th>
	</tr>
	</thead>
	<tbody id="main_tbody">
		<tr id="main_tr">
			<td width="28%"><?= $this->Form->input('reference_no[]',['type'=>'text','class'=>'distinctreference','label'=>false,'id'=>'reference_no_1']) ?></td>
			<td width="35%"><?= $this->Form->input('amount[]',['type'=>'text','class'=>'credit','label'=>false, 'value'=>0]) ?></td>
			<td width="15%"><?php echo $this->Form->input('type_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm  calculation drcrChange','value'=>'Dr']); ?></td>
			<td width="15%"><?= $this->Form->button(__('<i class="fa fa-plus"></i>'),['type'=>'button','class'=>'add_row','label'=>false]) ?></td>
		</tr>
	</tbody>
	</table>
	
	<div class="row">
			<div class="col-md-3">
			</div>
			<div class="col-md-2">
			<input type="text" class="form-control input-sm rightAligntextClass noBorder " name="totalMainDr" id="totalMainDr" readonly>
			</div>
	</div>
	
	<button class="btn btn-sm btn-primary" name="submit" value="save" type="submit">Submit</button>
	</div>
    
    <?= $this->Form->end() ?>
    <?= $this->Form->end() ?>
	
</div>
</div>
<table class="table table-bordered" id="temp_table" style="display:none;">
<tbody id="temp_tbody">
	<tr id="main_tr">
	<td><?= $this->Form->input('reference_no[]',['type'=>'text','class'=>'distinctreference','label'=>false,'id'=>'reference_no_2']) ?></td>
	<td><?= $this->Form->input('amount[]',['type'=>'text','class'=>'credit','label'=>false, 'value'=>0]) ?></td>
	<td><?php echo $this->Form->input('type_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm  calculation drcrChange','value'=>'Dr']); ?></td>
	<td><?= $this->Form->button(__('<i class="fa fa-plus"></i>'),['type'=>'button','class'=>'add_row add_row1','label'=>false]) ?><?= $this->Form->button(__('<i class="fa fa-minus"></i>'),['type'=>'button','class'=>'remove_row','label'=>false]) ?></td>
	</tr>
</tbody>
	</table>
	
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {


	//--------- FORM VALIDATION
	var form3 = $('#opening_balance');
	var error3 = $('.alert-danger', form3);
	var success3 = $('.alert-success', form3);
	form3.validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block help-block-error', // default input error message class
		focusInvalid: true, // do not focus the last invalid input
		rules: {
				ledger_account_id:{
					required: true,
				},
				transaction_date:{
					required: true,
				},
				amount:{
					required: true,
				},
			},

		messages: { // custom messages for radio buttons and checkboxes
			'reference_no[]': {
				remote: "Reference no. is alredy taken."
			},
			
		},

		errorPlacement: function (error, element) { // render error placement for each input type
			if (element.parent(".input-group").size() > 0) {
				error.insertAfter(element.parent(".input-group"));
			} else if (element.attr("data-error-container")) { 
				error.appendTo(element.attr("data-error-container"));
			} else if (element.parents('.radio-list').size() > 0) { 
				error.appendTo(element.parents('.radio-list').attr("data-error-container"));
			} else if (element.parents('.radio-inline').size() > 0) { 
				error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
			} else if (element.parents('.checkbox-list').size() > 0) {
				error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
			} else if (element.parents('.checkbox-inline').size() > 0) { 
				error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
			} else {
				error.insertAfter(element); // for other inputs, just perform default behavior
			}
		},

		invalidHandler: function (event, validator) { //display error alert on form submit   
			success3.hide();
			error3.show();
			
			//Metronic.scrollTo(error3, -200);
		},

		highlight: function (element) { // hightlight error inputs
		   $(element)
				.closest('.form-group').addClass('has-error'); // set error class to the control group
		},

		unhighlight: function (element) { // revert the change done by hightlight
			$(element)
				.closest('.form-group').removeClass('has-error'); // set error class to the control group
		},

		success: function (label) {
			label
				.closest('.form-group').removeClass('has-error'); // set success class to the control group
		},

		submitHandler: function (form) {
			
			var total_amt=$('.amount').val();
			var totalMainDr=$('#totalMainDr').val();
			alert(totalMainDr)
			alert(total_amt)
			
			success3.show();
			error3.hide();
			form[0].submit(); // submit the form
		}

	});
	//--	 END OF VALIDATION
	
		
	
	$('.add_row').live("click",function() { 
		var new_line=$('#temp_table tbody#temp_tbody').html(); 
		$("#main_table tbody#main_tbody").append(new_line);
		var i=1;
		rename_rows();
	});
	
	$('.credit').live("keyup",function() {
		rename_rows();
	});
	
	$('.drcrChange').live("change",function() { 
		rename_rows();
	});
	
	
	function rename_rows(){
		var total_dr=0;
		var total_cr=0;
		var type=$('.refDrCr').val();
		
		
		$("#main_table tbody#main_tbody tr").each(function(){
			var dr_cr=$(this).find("td:nth-child(3) select").val();
			if(dr_cr=="Cr"){
				var amt=parseFloat($(this).find("td:nth-child(2) input").val());
				total_cr=total_cr+amt;
			}else{
				var amt=parseFloat($(this).find("td:nth-child(2) input").val());
				total_dr=total_dr+amt;
			}
		});
		
		if(total_dr > total_cr){
			var total_amt=$('.amount').val();
			var refamt=total_dr-total_cr;
			$('#totalMainDr').val(refamt.toFixed(2));
		}else if(total_dr < total_cr){
			var total_amt=$('.amount').val();
			var refamt=total_cr-total_dr;
			$('#totalMainDr').val(refamt.toFixed(2));
		}
		else{
			$('#totalMainDr').val(0);
		}
		
		
	}
	
	$('.remove_row').live("click",function() {
		$(this).closest("#main_table tr").remove();
		var i=1;
		
	});

	

	
	
});	
</script>