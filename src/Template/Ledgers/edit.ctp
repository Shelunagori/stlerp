<?php if($allow='YES'){ ?>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Opening Balance Edit</span>
		</div>
	</div>
	<div class="portlet-body form">

    <?= $this->Form->create($ledger,['id'=>'opening_balance']) ?>
    <div class="form-body">
		<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="col-md-6 control-label">Ledger Accounts</label><br/>
						<?= h($ledger->ledger_account->name.'('.$ledger->ledger_account->alias.')') ?>
						<?= $this->Form->hidden('ledger_account_id',['type'=>'text','class'=>'form-control input-sm mian_amount','label'=>false,'value'=>$ledger->ledger_account->id]) ?>
						</div>
				</div>
				<div class="col-md-2">
						<div class="form-group">
							<label class="col-md-6 control-label">Date</label><br/>
							<?= h(date("d-m-Y",strtotime($ledger->transaction_date))) ?>
							<?= $this->Form->hidden('transaction_date',['type'=>'text','class'=>'form-control input-sm mian_amount','label'=>false,'value'=>date("d-m-Y",strtotime($ledger->transaction_date))]) ?>
						</div>
					</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="col-md-6 control-label">Amount</label>
						<?php 
						if(!empty($ledger->debit))
						{
							$amount=$ledger->debit;
							$type_cr_dr='Dr';
						}
						else
						{
							$amount=$ledger->credit;
							$type_cr_dr='Cr';
						}
						echo $this->Form->input('amount', ['type' => 'text','label' => false,'class' => 'form-control input-sm mian_amount','placeholder'=>'Amount','value'=>$amount]); ?>
						</div>
				</div>
				
				<div class="col-md-1">
						<div class="form-group">
							<label class="control-label">Dr/Cr</label>
							<?php echo $this->Form->input('type_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm  cr_dr_amount','value'=>'Dr','value'=>$type_cr_dr]); ?>
						</div>
				</div>
				
			<?= $this->Form->hidden('voucher_source',['value'=>'Opening Balance']) ?>
			</div>
			<?php $ref_types=['New Reference'=>'New Ref','Advance Reference'=>'Advance']; ?>
	<table class="table table-bordered" id="main_table" style="width:83%">
	<thead>
	<tr>
	<th>Ref Type</th>
	<th>Ref. No.</th>
	<th>Amount</th>
	<th>Dr/Cr</th>
	<th></th>
	</tr>
	</thead>
	<tbody id="main_tbody">
		<?php $cnt=0;
		foreach($ledger_details as $reference_detail){
			if($reference_detail->reference_type!='On_account'){
				if(!empty($reference_detail->debit))
				{
					$ref_amount=$reference_detail->debit;
					$ref_cr_dr='Dr';
				}
				else
				{
					$ref_amount=$reference_detail->credit;
					$ref_cr_dr='Cr';
				}
		?>
		<tr id="main_tr">
			<td width="25%"><?php echo $this->Form->input('ref_types', ['empty'=>'--Select-','options'=>$ref_types,'label' => false,'class' => 'form-control input-sm ref_type','value'=>$reference_detail->reference_type]); ?></td>
			<td width="28%"><?= $this->Form->input('reference_no[]',['type'=>'text','class'=>'form-control input-sm','label'=>false,'id'=>'reference_no_1','value'=>$reference_detail->reference_no]) ?></td>
			<td width="35%"><?= $this->Form->input('amount[]',['type'=>'text','class'=>'form-control input-sm mian_amount','label'=>false,'value'=>$ref_amount]) ?></td>
			<td width="15%"><?php echo $this->Form->input('type_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm  cr_dr_amount','value'=>$ref_cr_dr]); ?></td>
			<td width="15%"><?= $this->Form->button(__('<i class="fa fa-plus"></i>'),['type'=>'button','class'=>'add_row','label'=>false]) ?>
			<?php
			if($cnt!=0)
			{
			echo $this->Form->button(__('<i class="fa fa-minus"></i>'),['type'=>'button','class'=>'remove_row','label'=>false]);
			}
			?>
			</td>
		</tr>
		<?php
		$cnt++;
			}
		}
		?>
	</tbody>
	<tfoot>
			<tr>
				<td colspan="2" align="center" style="vertical-align: middle !important;">On Account</td>
				<td><?php echo $this->Form->input('on_account', ['label' => false,'class' => 'form-control input-sm on_account','placeholder'=>'Amount','readonly']); ?></td>
				<td><?php echo $this->Form->input('on_ac_cr_dr', ['label' => false,'class' => 'form-control input-sm on_acc_cr_dr','placeholder'=>'Cr_Dr','readonly']); ?></td>
			</tr>
		</tfoot>
	</table>
	<button class="btn btn-sm btn-primary" name="submit" value="save" type="submit">Submit</button>
	</div>
    
    <?= $this->Form->end() ?>
	
</div>
</div>
<table class="table table-bordered" id="temp_table" style="display:none;">
<tbody id="temp_tbody">
	<tr id="main_tr">
	<td width="25%"><?php echo $this->Form->input('ref_types', ['empty'=>'--Select-','options'=>$ref_types,'label' => false,'class' => 'form-control input-sm ref_type']); ?></td>
	<td><?= $this->Form->input('reference_no[]',['type'=>'text','class'=>'form-control input-sm','label'=>false,'id'=>'reference_no_2']) ?></td>
	<td><?= $this->Form->input('amount[]',['type'=>'text','class'=>'form-control input-sm mian_amount','label'=>false, 'value'=>0]) ?></td>
	<td><?php echo $this->Form->input('type_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm  cr_dr_amount','value'=>'Dr']); ?></td>
	<td><?= $this->Form->button(__('<i class="fa fa-plus"></i>'),['type'=>'button','class'=>'add_row add_row1','label'=>false]) ?><?= $this->Form->button(__('<i class="fa fa-minus"></i>'),['type'=>'button','class'=>'remove_row','label'=>false]) ?></td>
	</tr>
</tbody>
	</table>
	<?php }else{ ?>
<span> The ref. no. has been used anywhere, so you can not edit this.</span>
<?php } ?>
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
		rename_ref_rows();
	});
	rename_ref_rows();
	function rename_ref_rows(){
		var i=0;
		$("#main_table tbody#main_tbody tr").each(function(){ 
			$(this).find("td:nth-child(1) select").attr({name:"ref_rows["+i+"][ref_type]", id:"ref_rows-"+i+"-ref_type"}).rules("add", "required");
			
			$(this).find("td:nth-child(2) input").attr({name:"ref_rows["+i+"][ref_no]", id:"ref_rows-"+i+"-ref_no"}).rules("add", "required");
			
			$(this).find("td:nth-child(3) input").attr({name:"ref_rows["+i+"][ref_amount]", id:"ref_rows-"+i+"-ref_amount"}).rules("add", "required");
			
			$(this).find("td:nth-child(4) select").attr({name:"ref_rows["+i+"][ref_cr_dr]", id:"ref_rows-"+i+"-ref_cr_dr"}).rules("add", "required"); 
			i++;
		});
		
	}
	$('.cr_dr_amount').live("change",function() {
		do_ref_total();
	});
	$('.mian_amount').live("blur",function() {
		var v=parseFloat($(this).val());
		if(!v){ v=0; }
		$(this).val(round(v,2));
	});
	
	$('.mian_amount').live("keyup",function() {
		do_ref_total();
	});
	do_ref_total();
	function do_ref_total(){
		var main_amount=$('input[name=amount]').val();
		var main_cr_dr=$('select[name=type_cr_dr]').val();
		var total_ref=0;
		var total_ref_cr=0;
		var total_ref_dr=0;
		$("#main_table tbody#main_tbody tr").each(function(){
			var am=parseFloat($(this).find('td:nth-child(3) input').val());
			var ref_cr_dr=$(this).find('td:nth-child(4) select').val();
			if(!am){ am=0; }
			
			if(ref_cr_dr=='Dr')
			{
				total_ref_dr=total_ref_dr+am;
			}
			else
			{
				total_ref_cr=total_ref_cr+am;
			}
			
		});
		var on_acc=0;
		var total_ref=0;
		var on_acc_cr_dr='';
		if(main_cr_dr=='Dr')
		{
			on_acc_cr_dr='Dr';
			if(total_ref_dr > total_ref_cr)
			{
				total_ref=total_ref_dr-total_ref_cr;
				on_acc=main_amount-total_ref;
				
			}
			else if(total_ref_dr < total_ref_cr)
			{
				total_ref=total_ref_dr-total_ref_cr;
				on_acc=main_amount-total_ref;
			}
			else
			{
				on_acc=main_amount;
			}
			if(on_acc>=0){
				on_acc=Math.abs(on_acc);
				$("#main_table tfoot tr:nth-child(1) td:nth-child(2) input").val(round(on_acc,2));
				$("#main_table tfoot tr:nth-child(1) td:nth-child(3) input").val(on_acc_cr_dr);
			}else{
				on_acc=Math.abs(on_acc);
				$("#main_table tfoot tr:nth-child(1) td:nth-child(2) input").val(round(on_acc,2));
				$("#main_table tfoot tr:nth-child(1) td:nth-child(3) input").val('Cr');
			}
		}
		else
		{
			on_acc_cr_dr='Cr';
			if(total_ref_dr < total_ref_cr)
			{
				total_ref=total_ref_cr-total_ref_dr;
				on_acc=main_amount-total_ref;
			}
			else if(total_ref_dr > total_ref_cr)
			{
				total_ref=total_ref_cr-total_ref_dr;
				on_acc=main_amount-total_ref;
			}
			else
			{
				on_acc=main_amount;
			}
			if(on_acc>=0){
				on_acc=Math.abs(on_acc);
				$("#main_table tfoot tr:nth-child(1) td:nth-child(2) input").val(round(on_acc,2));
				$("#main_table tfoot tr:nth-child(1) td:nth-child(3) input").val(on_acc_cr_dr);
				
			}else{
				on_acc=Math.abs(on_acc);
				$("#main_table tfoot tr:nth-child(1) td:nth-child(2) input").val(round(on_acc,2));
				$("#main_table tfoot tr:nth-child(1) td:nth-child(3) input").val('Dr');
			}
		}
		
	}
	
	
	$('.remove_row').live("click",function() {
		$(this).closest("#main_table tr").remove();
		var i=1;
		
	});

	

	
	
});	
</script>