<style>
table > thead > tr > th, table > tbody > tr > th, table > tfoot > tr > th, table > thead > tr > td, table > tbody > tr > td, table > tfoot > tr > td{
	vertical-align: top !important;
	border-bottom:solid 1px #CCC;
}
.page-content-wrapper .page-content {
    padding: 5px;
}
.portlet.light {
    padding: 4px 10px 4px 10px;
}
.help-block-error{
	font-size: 10px;
}
</style>
<?php 	$first="01";
		$last="31";
		$start_date=$first.'-'.$financial_month_first->month;
		$end_date=$last.'-'.$financial_month_last->month;
		$start_date=strtotime(date("Y-m-d",strtotime($start_date)));
		$transaction_date=strtotime($journalVoucher->transaction_date);
if($transaction_date <  $start_date ) {
	echo "Financial Month has been Closed";
} else { ?>


<?php $ref_types=['New Reference'=>'New Ref','Against Reference'=>'Agst Ref','Advance Reference'=>'Advance']; ?>
<?php $cr_dr_options=['Dr'=>'Dr','Cr'=>'Cr']; ?>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption" >
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">EDIT JOURNAL VOUCHER</span>
		</div>
	</div>
	<div class="portlet-body form">
		<?= $this->Form->create($journalVoucher,['type' => 'file','id'=>'form_sample_3']) ?>
		<?php 	$first="01";
				$last="31";
				$start_date=$first.'-'.$financial_month_first->month;
				$end_date=$last.'-'.$financial_month_last->month;
				//pr($start_date); exit;
		?>
        <div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Transaction Date<span class="required" aria-required="true">*</span></label>
					<?php echo $this->Form->input('transaction_date', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','value' => date("d-m-Y",strtotime($journalVoucher->transaction_date)),'data-date-start-date' => $start_date,'data-date-end-date' => $end_date,'required']); ?>
				</div>
					<span style="color: red;">
						<?php if($chkdate == 'Not Found'){  ?>
							You are not in Current Financial Year
						<?php } ?>
					</span>
			</div>
			
		</div>
		
		<div style="overflow: auto;">
		<table width="100%" id="main_table">
			<thead>
				<th width="25%"><label class="control-label">Received From</label></th>
				<th width="15%"><label class="control-label">Amount</label></th>
				<th></th>
				<th width="15%"><label class="control-label">Narration</label></th>
				<th width="3%"></th>
			</thead>
			<tbody id="main_tbody">
			<?php foreach($journalVoucher->journal_voucher_rows as $journal_voucher_rows){ ?> 
				<tr class="main_tr" old_received_from_id="<?php echo $journal_voucher_rows->received_from_id; ?>">
					<td><?php echo $this->Form->input('received_from_id', ['empty'=>'--Select-','options'=>$receivedFroms,'label' => false,'class' => 'form-control input-sm received_from','value'=>$journal_voucher_rows->received_from_id,'style'=>'width:266px;']); ?>
					<?php echo $this->Form->input('row_id', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm row_id']); ?>
					
					<div class="show_grns">
										<?php if(sizeof($journal_voucher_rows->grn_ids)>0){
											$option=[];
											foreach($grn as $grn1)
											{ 
												$grnIds = explode(',',$journal_voucher_rows->grn_ids);
												if(in_array($grn1->id, $grnIds))
												{   
													$grn_no=$grn1->grn1.'/GRN-'.str_pad($grn1->grn2, 3, '0', STR_PAD_LEFT).'/'.$grn1->grn3.'/'.$grn1->grn4;
													$option[]=['text' =>$grn_no, 'value' => $grn1->id, 'selected'];
												}
												else
												{
													if($grn1->purchase_thela_bhada_status=='no')
													{
														$grn_no=$grn1->grn1.'/GRN-'.str_pad($grn1->grn2, 3, '0', STR_PAD_LEFT).'/'.$grn1->grn3.'/'.$grn1->grn4;
														$option[]=['text' =>$grn_no, 'value' => $grn1->id];
													}
												} 

											}
											echo $this->Form->input('q[]', ['label'=>false,'options' => $option,'multiple' => 'multiple','class'=>'form-control grns select2me','style'=>'width:266px']);
											}
											elseif(sizeof($journal_voucher_rows->invoice_ids) > 0)
											{
											    $option=[];
												foreach($invoice as $invoice1)
												{ 
													$invoiceIds = explode(',',$journal_voucher_rows->invoice_ids);
													if(in_array($invoice1->id, $invoiceIds))
													{   
														$invoice_no=$invoice1->in1.'/IN-'.str_pad($invoice1->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice1->in3.'/'.$invoice1->in4;
														$option[]=['text' =>$invoice_no, 'value' => $invoice1->id, 'selected'];
													}
													else
													{
														if($invoice1->sales_thela_bhada_status=='no')
														{
															$invoice_no=$invoice1->in1.'/IN-'.str_pad($invoice1->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice1->in3.'/'.$invoice1->in4;
															$option[]=['text' =>$invoice_no, 'value' => $invoice1->id];
														}
												    } 

												}
											echo $this->Form->input('q[]', ['label'=>false,'options' => $option,'multiple' => 'multiple','class'=>'form-control invoices select2me','style'=>'width:266px']);
											}

										?>
									</div>
					</td>
					<td>
					<div class="row">
						<div class="col-md-7" style="padding-right: 0;">
							<?php echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm mian_amount','placeholder'=>'Amount','value'=>$journal_voucher_rows->amount]); ?>
						</div>
						<div class="col-md-5"style="padding-left: 0;">
							<?php echo $this->Form->input('cr_dr', ['label' => false,'options'=>$cr_dr_options,'class' => 'form-control input-sm cr_dr_amount','value'=>$journal_voucher_rows->cr_dr]); ?>
						</div>
					</div>
					</td>
					<td style="width:40%">
					
						<div class="ref" style="padding:4px;">
						<table width="100%" class="ref_table">
							<thead>
								<tr>
									<th width="25%">Ref Type</th>
									<th width="25%">Ref No.</th>
									<th width="30%">Amount</th>
									<th width="15%"></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($journal_voucher_rows->reference_details as $reference_detail){
								if($reference_detail->reference_type!='On_account'){
								?>
								<tr>
									<td><?php echo $this->Form->input('ref_types', ['empty'=>'--Select-','options'=>$ref_types,'label' => false,'class' => 'form-control input-sm ref_type','value'=>$reference_detail->reference_type]); ?></td>
									<td class="ref_no">
										<?php 
										if($reference_detail->reference_type=='Against Reference')
										{
											echo $this->requestAction('/ReferenceDetails/listRefEdit?ledger_account_id='.$journal_voucher_rows->received_from_id.'&ref_name='.$reference_detail->reference_no);
										}
										else
										{
											echo '<input type="text" class="form-control input-sm" placeholder="Ref No." value="'.$reference_detail->reference_no.'"  is_old="yes">';
										}
										?> 
									</td>
									<td><?php 
									if(!empty($reference_detail->credit)){
										$amount=$reference_detail->credit;
										$dr_cr="Cr";
									}else{
										$amount=$reference_detail->debit;
										$dr_cr="Dr";
									}
									echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm ref_amount_textbox','placeholder'=>'Amount','value'=>$amount]); ?></td>
									<td><?php echo $this->Form->input('ref_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm cr_dr_amount','value'=>$dr_cr]); ?></td>
									<td><a class="btn btn-xs btn-default deleterefrow" href="#" role="button"><i class="fa fa-times"></i></a></td>
								</tr>
								<?php }
							} ?>
							
							</tbody>
							<tfoot>
								<tr>
									<td align="center" style="vertical-align: middle !important;">On Account</td>
									<td></td>
									<td><?php echo $this->Form->input('on_account', ['label' => false,'class' => 'form-control input-sm on_account','placeholder'=>'Amount','readonly']); ?></td>
									<td></td>
								</tr>
								<tr>
									<td colspan="2"><a class="btn btn-xs btn-default addrefrow" href="#" role="button"><i class="fa fa-plus"></i> Add row</a></td>
									<td></td>
									<td></td>
								</tr>
							</tfoot>
						</table>
						
						</div>
						
					</td>
					<td><?php echo $this->Form->input('narration', ['type'=>'textarea','label' => false,'class' => 'form-control input-sm','placeholder'=>'Narration','value'=>$journal_voucher_rows->narration]); ?></td>
					<td><a class="btn btn-xs btn-default deleterow" href="#" role="button"><i class="fa fa-times"></i></a></td>
				</tr>
			<?php } ?>
			</tbody>
			<tfoot>
				<td><a class="btn btn-xs btn-default addrow" href="#" role="button"><i class="fa fa-plus"></i> Add row</a></td>
				<td id="receipt_amount_dr" width="8%"><label>Total Dr</label><?php echo $this->Form->input('debitamount', ['type' => 'text','style'=>'width:65%;','label' => false,'class' => 'form-control input-sm','readonly']); ?></td>
				<td id="receipt_amount_cr" width="20%"><label>Total Cr</label><?php echo $this->Form->input('creditamount', ['type' => 'text','style'=>'width:50%;','label' => false,'class' => 'form-control input-sm','readonly']); ?></td>
				<td>
				<?php if($chkdate == 'Not Found'){  ?>
					<label class="btn btn-danger"> You are not in Current Financial Year </label>
				<?php } else { ?>
					<button type="submit" class="btn btn-primary">EDIT JOURNAL VOUCHER</button>
				<?php } ?>	
				</td>
			</tfoot>
		</table>
		</div>
    <?= $this->Form->end() ?>
	</div>
</div>
<?php }  ?>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>
$(document).ready(function() {
	//--------- FORM VALIDATION
	jQuery.validator.addMethod("noSpace", function(value, element) { 
	  return value.indexOf(" ") < 0 && value != ""; 
	}, "No space allowed");

	jQuery.validator.addMethod("notEqualToGroup", function (value, element, options) {
		// get all the elements passed here with the same class
		var elems = $(element).parents('form').find(options[0]);
		// the value of the current element
		var valueToCompare = value;
		// count
		var matchesFound = 0;
		// loop each element and compare its value with the current value
		// and increase the count every time we find one
		jQuery.each(elems, function () {
			thisVal = $(this).val();
			if (thisVal == valueToCompare) {
				matchesFound++;
			}
		});
		// count should be either 0 or 1 max
		if (this.optional(element) || matchesFound <= 1) {
			//elems.removeClass('error');
			return true;
		} else {
			//elems.addClass('error');
		}
	}, jQuery.format("Reference number should unique for one party."))


	var form3 = $('#form_sample_3');
	var error3 = $('.alert-danger', form3);
	var success3 = $('.alert-success', form3);
	form3.validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block help-block-error', // default input error message class
		focusInvalid: true, // do not focus the last invalid input
		rules: {
				debitamount:{
					
				},
				creditamount:{
					
					equalTo: "#debitamount",
					
				}
			},
		messages: {
			creditamount:{
					equalTo: "Must be equal to Debit Amount",
					
				}
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
			$('#submitbtn').prop('disabled', true);
			$('#submitbtn').text('Submitting.....');
			success3.show();
			error3.hide();
			form[0].submit(); // submit the form
		}

	});
	//--	 END OF VALIDATION
	
	
		
	$('input[name="payment_mode"]').die().live("click",function() {
		var payment_mode=$(this).val();
		if(payment_mode=="Cheque"){
			$("#chq_no").show();
		}else{
			$("#chq_no").hide();
		}
	});
	
	var payment_mode=$('input[name="payment_mode"]:checked').val();
	if(payment_mode=="Cheque"){
		$("#chq_no").show();
	}else{
		$("#chq_no").hide();
	}
	rename_rows();
	

	function add_row(){
		var tr=$("#sample_table tbody tr").clone();
		$("#main_table tbody#main_tbody").append(tr);
		rename_rows();
	}
	
	function rename_rows(){ 
		var i=0;
		$("#main_table tbody#main_tbody tr.main_tr").each(function(){
			
			$(this).find("td:eq(0) input.hidden").attr({name:"journal_voucher_rows["+i+"][id]", id:"journal_voucher_rows-"+i+"-id"});
			$(this).find("td:eq(0) select.received_from").select2().attr({name:"journal_voucher_rows["+i+"][received_from_id]", id:"journal_voucher_rows-"+i+"-received_from_id"}).rules('add', {
						required: true,
						
					});
			$(this).find("td:eq(0) .row_id").val(i);
			
				var thela_type = $(this).find("td:nth-child(1) select.received_from option:selected").attr('thelatype'); 
				if(thela_type)
				{
					if(thela_type=='grn')
					{				
						$(this).find("td:eq(0) select.grns").select2().attr({name:"journal_voucher_rows["+i+"][grn_ids][]", id:"journal_voucher_rows-"+i+"-grn_ids"}).rules('add', {
							required: true,
							notEqualToGroup: ['.grns'],
							messages: {
								notEqualToGroup: "Do not select same grn again."
							}
						});
					}
					else if(thela_type=='invoice')
					{		 		
						$(this).find("td:eq(0) select.invoices").select2().attr({name:"journal_voucher_rows["+i+"][invoice_ids][]", id:"journal_voucher_rows-"+i+"-invoice_ids"}).rules('add', {
							required: true,
							notEqualToGroup: ['.invoices'],
							messages: 
							{
								notEqualToGroup: "Do not select same invoice again."
							}
						});
					}
				}
			
			$(this).find("td:eq(1) input").attr({name:"journal_voucher_rows["+i+"][amount]", id:"journal_voucher_rows-"+i+"-amount"}).rules('add', {
						required: true,
						min: 0.01,
					});
			$(this).find("td:eq(1) select").attr({name:"journal_voucher_rows["+i+"][cr_dr]", id:"journal_voucher_rows-"+i+"-cr_dr"});
			$(this).find("td:nth-child(4) textarea").attr({name:"journal_voucher_rows["+i+"][narration]", id:"journal_voucher_rows-"+i+"-narration"}).rules("add", "required");
			i++;
		});
	}
	
	$('.addrow').live("click",function() {
		add_row();
	});
	$('.deleterow').live("click",function() {
		var sel=$(this);
		//delete_all_ref_no(sel);
		$(this).closest("tr").remove();
		do_mian_amount_total();
	});
	
	$('.addrefrow').live("click",function() {
		var sel=$(this).closest('tr.main_tr');
		var received_from_id=$(this).closest('tr.main_tr').find('td:nth-child(1) select').val();
		add_ref_row(sel,received_from_id);
	});
	
	function add_ref_row(sel,received_from_id){
		var tr=$("#sample_ref table.ref_table tbody tr").clone();
		sel.find("table.ref_table tbody").append(tr);
		rename_ref_rows(sel,received_from_id);
	}
	
	function rename_ref_rows(sel,received_from_id){
		var i=0;
		$(sel).find("table.ref_table tbody tr").each(function(){
			row_id=$(this).closest('tr.main_tr').find('td:eq(0) .row_id').val();
			$(this).find("td:nth-child(1) select").attr({name:"journal_voucher_rows["+row_id+"][ref_rows]["+i+"][ref_type]", id:"ref_rows-"+received_from_id+"-"+i+"-ref_type"}).rules("add", "required");
			var is_select=$(this).find("td:nth-child(2) select").length;
			var is_input=$(this).find("td:nth-child(2) input").length;
			
			if(is_select){
				$(this).find("td:nth-child(2) select").attr({name:"journal_voucher_rows["+row_id+"][ref_rows]["+i+"][ref_no]", id:"ref_rows-"+received_from_id+"-"+i+"-ref_no"}).rules("add", "required");
			}else if(is_input){
				$(this).find("td:nth-child(2) input").attr({name:"journal_voucher_rows["+row_id+"][ref_rows]["+i+"][ref_no]", id:"ref_rows-"+received_from_id+"-"+i+"-ref_no", class:"form-control input-sm ref_number-"+received_from_id});
			}
			
			$(this).find("td:nth-child(3) input").attr({name:"journal_voucher_rows["+row_id+"][ref_rows]["+i+"][ref_amount]", id:"ref_rows-"+received_from_id+"-"+i+"-ref_amount"}).rules("add", "required");
			$(this).find("td:nth-child(4) select").attr({name:"journal_voucher_rows["+row_id+"][ref_rows]["+i+"][ref_cr_dr]", id:"ref_rows-"+row_id+"-"+i+"-ref_cr_dr"}).rules("add", "required");
			i++;
		});
		
		$(sel).find("table.ref_table tfoot tr:nth-child(1) .on_account").attr({name:"journal_voucher_rows["+row_id+"][on_acc]", id:"ref_rows-"+row_id+"-"+i+"-ref_cr_dr"}).rules("add", "required");
		
		$(sel).find("table.ref_table tfoot tr:nth-child(1) .on_acc_cr_dr").attr({name:"journal_voucher_rows["+row_id+"][on_acc_cr_dr]", id:"ref_rows-"+row_id+"-"+i+"-ref_cr_dr"}).rules("add", "required");
		
		
	}
	

	
	
	$('.received_from').live("change",function() {
		var sel=$(this);
		$(this).closest('div.select').css("width","266px");
		load_ref_section(sel);
	});
	
	
	$('.deleterefrow').live("click",function() {
		var l=$(this).closest("table.ref_table tbody").find("tr").length;
			if(l>1){
				$(this).closest("tr").remove();
			}
		do_ref_total();
	});
	function load_ref_section(sel){
		$(sel).closest("tr.main_tr").find("td:nth-child(3)").html("Loading...");
		var sel2=$(sel).closest('tr.main_tr');
		var received_from_id=$(sel).closest("tr.main_tr").find("td:nth-child(1) select").find('option:selected').val();
		var url="<?php echo $this->Url->build(['controller'=>'LedgerAccounts','action'=>'checkBillToBillAccountingStatus']); ?>";
		url=url+'/'+received_from_id,
		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'text'
		}).done(function(response) {
			if(response.trim()=="Yes"){
				var ref_table=$("#sample_ref div.ref").clone();
				$(sel).closest("tr").find("td:nth-child(3)").html(ref_table);
			}else{
				$(sel).closest("tr").find("td:nth-child(3)").html("");
			}
			rename_ref_rows(sel2,received_from_id);
		});
		
		var url="<?php echo $this->Url->build(['controller'=>'LedgerAccounts','action'=>'checkExpenseTrackingStatus']); ?>";
		url=url+'/'+received_from_id,
		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'text'
		}).done(function(response) { 
			$(sel).closest('tr.main_tr').find('.show_grns').html(response);
			rename_rows();
		});
		
		/* var url="<?php echo $this->Url->build(['controller'=>'LedgerAccounts','action'=>'loadGrns']); ?>";
		url=url+'/'+received_from_id;
		if(received_from_id=='101' || received_from_id=='165' || received_from_id=='313')
		{ 
	       $.ajax({
				url: url,
				type: 'GET',
				dataType: 'text'
			}).done(function(response) {
				//$(this).closest("tr").remove();
				//$(".show_grns").html(response);
		    	
				$(sel).closest('tr.main_tr').find('.show_grns').html(response);
				rename_rows();
			});
		}
		else
		{
			$(sel).closest('tr.main_tr').find('.show_grns').html('');
		}
		
		var url="<?php echo $this->Url->build(['controller'=>'LedgerAccounts','action'=>'loadInvoices']); ?>";
		url=url+'/'+received_from_id;
		if(received_from_id=='105' || received_from_id=='168' || received_from_id=='316')
		{
	       $.ajax({
				url: url,
				type: 'GET',
				dataType: 'text'
			}).done(function(response) {  
				$(sel).closest('tr.main_tr').find('.show_grns').html(response);
				rename_rows();
			});
		}
		else
		{
			$(sel).closest('tr.main_tr').find('.show_grns').html('');
		} */
	}
	
	$('.ref_type').live("change",function() {
		var current_obj=$(this);
		var sel3=$(this).closest('tr.main_tr');
		var cr_dr=$(this).closest('tr.main_tr').find('td:nth-child(2) select').val();
		var ref_type=$(this).find('option:selected').val();
		var received_from_id=$(this).closest('tr.main_tr').find('td select:eq(0)').val();
		if(ref_type=="Against Reference"){
			var url="<?php echo $this->Url->build(['controller'=>'ReferenceDetails','action'=>'listRef']); ?>";
			url=url+'/'+received_from_id+'/'+cr_dr,
			$.ajax({
				url: url,
				type: 'GET',
			}).done(function(response) {
				current_obj.closest('tr').find('td:eq(1)').html(response);
				rename_ref_rows(sel3,received_from_id);
			});
		}else if(ref_type=="New Reference" || ref_type=="Advance Reference"){
			current_obj.closest('tr').find('td:eq(1)').html('<input type="text" class="form-control input-sm" placeholder="Ref No." >');
			rename_ref_rows(sel3,received_from_id);
		}else{
			current_obj.closest('tr').find('td:eq(1)').html('');
		}
		
	});
	
	$('.ref_type').live("change",function() {
		var sel=$(this);
		//delete_one_ref_no(sel);
	});
	
	$('.ref_list').live("change",function() {
		var sel=$(this);
		var due_amount=$(this).find('option:selected').attr('amt');
		$(this).closest('tr').find('td:eq(2) input').val(due_amount);
		do_ref_total();
		//delete_one_ref_no(sel);
	}); 
	
	$('.ref_amount_textbox').live("keyup",function() {
		do_ref_total();
	});
	
	do_ref_total();
	function do_ref_total(){
		$("#main_table tbody#main_tbody tr.main_tr").each(function(){
			var main_amount=$(this).find('td:nth-child(2) input').val();
			var total_ref=0;
			var main_cr_dr=$(this).find('td:nth-child(2) select').val();
			var total_ref_cr=0;
			var total_ref_dr=0;
			$(this).find("table.ref_table tbody tr").each(function(){
			
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
					on_acc=round(on_acc,2);
					
					$(this).find("table.ref_table tfoot tr:nth-child(1) td:nth-child(3) input").val(on_acc);
					$(this).find("table.ref_table tfoot tr:nth-child(1) td:nth-child(4) input").val(on_acc_cr_dr);
				}else{
					on_acc=Math.abs(on_acc);
					on_acc=round(on_acc,2);
					$(this).find("table.ref_table tfoot tr:nth-child(1) td:nth-child(3) input").val(on_acc);
					$(this).find("table.ref_table tfoot tr:nth-child(1) td:nth-child(4) input").val('Cr');
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
					on_acc=round(on_acc,2);
					$(this).find("table.ref_table tfoot tr:nth-child(1) td:nth-child(3) input").val(on_acc);
					$(this).find("table.ref_table tfoot tr:nth-child(1) td:nth-child(4) input").val(on_acc_cr_dr);
					
				}else{
					on_acc=Math.abs(on_acc);
					on_acc=round(on_acc,2);
					$(this).find("table.ref_table tfoot tr:nth-child(1) td:nth-child(3) input").val(on_acc);
					$(this).find("table.ref_table tfoot tr:nth-child(1) td:nth-child(4) input").val('Dr');
				}
			}
			
			
			
			
		});
		}
	
	$('.mian_amount').live("blur",function() {
		var v=parseFloat($(this).val());
		if(!v){ v=0; }
		$(this).val(v.toFixed(2));
	});
	
	$('.mian_amount').live("keyup",function() {
		do_mian_amount_total();
		do_ref_total();
	});
	
	do_mian_amount_total();
	function do_mian_amount_total(){
		if($("#main_table tbody#main_tbody tr.main_tr").length<1)
		{
			 $('#receipt_amount').text('');
		}
		var mian_amount_total_cr=0; var mian_amount_total_dr=0;
		$("#main_table tbody#main_tbody tr.main_tr").each(function(){
			var v=parseFloat($(this).find("td:nth-child(2) input").val());
			var cr_dr=($(this).find("td:nth-child(2) select").val());
			if(!v){ v=0; }
			if(cr_dr=="Cr"){
				mian_amount_total_cr=mian_amount_total_cr+v;
			}else{
				mian_amount_total_dr=mian_amount_total_dr+v;
			}
			
			mian_amount_total=mian_amount_total_dr-mian_amount_total_cr;
			$('#receipt_amount').text(mian_amount_total.toFixed(2));
			$('#debitamount').val(mian_amount_total_dr.toFixed(2));
			$('#creditamount').val(mian_amount_total_cr.toFixed(2));
		});
	}
	
	$('.received_from').live("change",function() {
		var sel=$(this);
		//delete_all_ref_no(sel);
	});
	
	
	$('.cr_dr_amount').live("change",function() {
		
		do_mian_amount_total();
		do_ref_total();
	});
	
	
	$("#main_table tbody#main_tbody tr.main_tr").each(function(){
		var sel2=$(this);
		var received_from_id=$(this).find("td:nth-child(1) select").find('option:selected').val();
		var url="<?php echo $this->Url->build(['controller'=>'LedgerAccounts','action'=>'checkBillToBillAccountingStatus']); ?>";
		url=url+'/'+received_from_id,
		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'text'
		}).done(function(response) {
			if(response.trim()=="Yes"){
				
			}else{
				$(sel2).find("td:nth-child(3)").html("");
			}
			rename_ref_rows(sel2,received_from_id);
		});
	});
	
});
</script>


<table id="sample_table" style="display:none;">
	<tbody>
		<tr class="main_tr">
			<td><?php echo $this->Form->input('received_from_id', ['empty'=>'--Select-','options'=>$receivedFroms,'label' => false,'class' => 'form-control input-sm received_from']); ?>
			<?php echo $this->Form->input('row_id', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm row_id']); ?><div class="show_grns"></div></td>
			<td>
			<div class="row">
				<div class="col-md-7" style="padding-right: 0;">
					<?php echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm mian_amount','placeholder'=>'Amount']); ?>
				</div>
				<div class="col-md-5"style="padding-left: 0;">
					<select name="cr_dr" class="form-control input-sm cr_dr_amount" >
						<option value="Dr">Dr</option>
						<option value="Cr">Cr</option>
					</select>
				</div>
			</div>
			</td>
			<td></td>
			<td><?php echo $this->Form->input('narration', ['type'=>'textarea','label' => false,'class' => 'form-control input-sm','placeholder'=>'Narration']); ?></td>
			<td><a class="btn btn-xs btn-default deleterow" href="#" role="button"><i class="fa fa-times"></i></a></td>
		</tr>
	</tbody>
</table>


<div id="sample_ref" style="display:none;">
	<div class="ref" style="padding:4px;">
	<table width="100%" class="ref_table">
		<thead>
			<tr>
				<th>Ref Type</th>
				<th>Ref No.</th>
				<th>Amount</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td width="25%"><?php echo $this->Form->input('ref_types', ['empty'=>'--Select-','options'=>$ref_types,'label' => false,'class' => 'form-control input-sm ref_type']); ?></td>
				<td class="ref_no"></td>
				<td width="25%"><?php echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm ref_amount_textbox','placeholder'=>'Amount']); ?></td>
				<td width="25%" style="padding-left:0px; vertical-align: top !important;">
				<?php 
				echo $this->Form->input('ref_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm  cr_dr_amount','value'=>'Cr','style'=>'vertical-align: top !important;']); ?>
			    </td>
				<td><a class="btn btn-xs btn-default deleterefrow" href="#" role="button"><i class="fa fa-times"></i></a></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td align="center" style="vertical-align: middle !important;">On Account</td>
				<td></td>
				<td><?php echo $this->Form->input('on_account', ['label' => false,'class' => 'form-control input-sm on_account','placeholder'=>'Amount','readonly']); ?></td>
				<td><?php echo $this->Form->input('cr_dr', ['label' => false,'class' => 'form-control input-sm on_acc_cr_dr','placeholder'=>'Cr_Dr','readonly']); ?></td>
			</tr>
			<tr>
				<td colspan="2"><a class="btn btn-xs btn-default addrefrow" href="#" role="button"><i class="fa fa-plus"></i> Add row</a></td>
				<td></td>
				<td></td>
			</tr>
		</tfoot>
	</table>
	</div>
</div>
