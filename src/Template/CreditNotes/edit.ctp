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
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption" >
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Edit Credit Note</span>
		</div>
	</div>
	<div class="portlet-body form">
    <?= $this->Form->create($creditNote,['id'=>'form_sample_3']) ?>
	<?php
	//pr($financial_month_first);exit;
 	$first="01";
				$last="31";
				$start_date=$first.'-'.$financial_month_first->month;
				$end_date=$last.'-'.$financial_month_last->month;
				//pr($start_date); exit;
		?>
        <div class="row">
		
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Voucher no<span class="required" aria-required="true">*</span></label><br/>
					<?php echo $this->Form->input('voucher_no', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm']); ?>
					<?php echo  "CR/".$creditNote->voucher_no; ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Transaction Date<span class="required" aria-required="true">*</span></label>
					<?php echo $this->Form->input('transaction_date', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','value' => date("d-m-Y"),'data-date-start-date' 
					=>$start_date ,'data-date-end-date' => $end_date,'required']); ?>
				</div>

					<span style="color: red;">
						<?php if($chkdate == 'Not Found'){  ?>
							You are not in Current Financial Year
						<?php } ?>
					</span>				

			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Supplier/Customer<span class="required" aria-required="true">*</span></label>
					<?php 
					
					echo $this->Form->input('customer_suppiler_id', ['empty'=>'--Select-','options'=>$bankCashes,'label' => false,'class' => 'form-control input-sm select2me']); ?>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label">Cr/Dr<span class="required" aria-required="true">*</span></label>
					<?php
					$options=['Dr'=>'Dr','Cr'=>'Cr'];
					echo $this->Form->input('cr_dr', ['options'=>$options,'label' => false,'class' => 'form-control input-sm  cr_dr_amount','style'=>'vertical-align: top !important;','empty'=>'--Select--','value'=>$creditNote->cr_dr]); ?>
				</div>
			</div>
			
			
			
		</div>
		<div style="overflow: auto;">
		<table  class="table tableitm" id="main_table" border="1">
				<thead>
						<tr >
							<th rowspan="2" style="width:25%;">Paid To</th>
							<th rowspan="2" style="width:10%;">Taxable Value</th>
							<th style=" text-align: center; width:15%;" colspan="2" width="15%">CGST</th>
							<th style=" text-align: center; width:15%;" colspan="2" width="15%">SGST</th>
							<th  style=" text-align: center; width:15%;" colspan="2" width="15%">IGST</th>
							<th rowspan="2" style="width:10%;">Total</th>
							<th rowspan="2" width="10px"></th>
						</tr>
						<tr> 
							<th style="text-align: center;" >%</th>
							<th style="text-align: center;" >Amt</th>
							<th style="text-align: center;" >%</th>
							<th style="text-align: center;" >Amt</th>
							<th style="text-align: center;">%</th>
							<th style="text-align: center;">Amt</th>
						</tr>
						
						<tbody id="main_tbody">
						<?php //pr($creditNote); exit; ?>
						<?php foreach($creditNote->credit_notes_rows as $credit_notes_row){ ?>
							<tr class="main_tr preimp">
								<td width="25%"><?php echo $this->Form->input('received_from_id', ['empty'=>'--Select-','options'=>$receivedFroms,'label' => false,'class' => 'form-control input-sm received_from','value'=>$credit_notes_row->received_from_id]); ?>
								<?php echo $this->Form->input('row_id', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm row_id']); ?>
								</td>
								<td width="5%" ><?php echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm amount','placeholder'=>'Amount','value'=>$credit_notes_row->amount]); ?></td>
								
								<td style=""><?php echo $this->Form->input('q', ['label' => false,'empty'=>'Select','options'=>$cgst_options,'class' => 'form-control input-sm cgst_percentage','placeholder'=>'%','step'=>0.01,'value'=>$credit_notes_row->cgst_percentage]); ?></td>
								<td style=""><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm row_textbox cgst_amount','placeholder'=>'Amount','readonly','step'=>0.01,'value'=>$credit_notes_row->cgst_amount]); ?></td>
								
								<td style=""><?php echo $this->Form->input('q', ['label' => false,'empty'=>'Select','options'=>$cgst_options,'class' => 'form-control input-sm sgst_percentage','placeholder'=>'%','step'=>0.01,'value'=>$credit_notes_row->sgst_percentage]); ?></td>
								<td style=""><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm row_textbox sgst_amount','placeholder'=>'Amount','readonly','step'=>0.01,'value'=>$credit_notes_row->sgst_amount]); ?></td>
								
								<td style=""><?php echo $this->Form->input('q', ['label' => false,'empty'=>'Select','options'=>$cgst_options,'class' => 'form-control input-sm igst_percentage','placeholder'=>'%','step'=>0.01,'value'=>$credit_notes_row->igst_percentage]); ?></td>
								<td style=""><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm row_textbox igst_amount','placeholder'=>'Amount','readonly','step'=>0.01,'value'=>$credit_notes_row->igst_amount]); ?></td>
								<td ><?php echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm total_amount','placeholder'=>'Amount','readonly','value'=>$credit_notes_row->total_amount]); ?></td>
								<td><a class="btn btn-xs btn-default deleterow" href="#" role="button"><i class="fa fa-times"></i></a></td>
							</tr>
							<tr class="main_tr1 preimp">
									<td  colspan="9" class="main"><?php echo $this->Form->input('narration', ['type'=>'textarea','label' => false,'class' => 'form-control input-sm narration','placeholder'=>'Narration','required','value'=>$credit_notes_row->narration]); ?></td>
								
								<td></td>
							</tr>
						<?php } ?>	
						</tbody>
						<tfoot>
					<tr>
						<td colspan="7"><a class="btn btn-xs btn-default addrow" href="#" role="button"><i class="fa fa-plus"></i> Add row</a></td>
						<td colspan="1">CGST Amount</td>
						<td colspan="2"><?php echo $this->Form->input('cgst_total_amount', ['label' => false,'class' => 'form-control input-sm cgst_total_amount','readonly']); ?></td>
					</tr>
					<tr>
						<td colspan="7"></td>
						<td colspan="1">SGST Amount</td>
						<td colspan="2"><?php echo $this->Form->input('sgst_total_amount', ['label' => false,'class' => 'form-control input-sm sgst_total_amount','readonly']); ?></td>
					</tr>
					<tr>
						<td colspan="7"></td>
						<td colspan="1">IGST Amount</td>
						<td colspan="2"><?php echo $this->Form->input('igst_total_amount', ['label' => false,'class' => 'form-control input-sm igst_total_amount','readonly']); ?></td>
					</tr>
					<tr>
						<td colspan="7"></td>
						<td colspan="1">Total Amount</td>
						<td colspan="2"><?php echo $this->Form->input('grand_total', ['label' => false,'class' => 'form-control input-sm grand_total','readonly']); ?></td>
					</tr>
					<tr>
						<td colspan="10">
						<?php if($chkdate == 'Not Found'){  ?>
							<label class="btn btn-danger"> You are not in Current Financial Year </label>
						<?php } else { ?>
						<button type="submit" class="btn btn-primary" id='submitbtn'>CREATE CREDIT NOTE</button>
						<?php } ?>					
						</td>
						
					</tr>
				
			</tfoot>
		</table>
		</div>
		
    
    <?= $this->Form->end() ?>
	</div>
</div>
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
				cr_dr:{
					required: true,
				},
				customer_supplier_id:{
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
	
	//add_row();
	function add_row(){ 
		var tr=$("#sample_table tbody tr").clone();
		$("#main_table tbody#main_tbody").append(tr);
		var tr2=$("#sample_table tbody tr.main_tr1").clone();
		$("#main_tb tbody#main_tbody").append(tr2);
		
		var w=0; var r=0;
		$("#main_table tbody#main_tbody tr.preimp").each(function(){
			$(this).attr("row_no",w);
			r++;
			if(r==2){ w++; r=0; }
		});
		rename_rows();
	}
	
	rename_rows();
	function rename_rows(){  
		var i=0; 
		$("#main_table tbody#main_tbody tr.main_tr").each(function(){
			$(this).find("select.received_from").select2().attr({name:"credit_notes_rows["+i+"][received_from_id]"}).rules("add", "required");
			$(this).find(".amount").attr({name:"credit_notes_rows["+i+"][amount]", id:"credit_notes_rows-"+i+"-amount"}).rules("add", "required");
			
			$(this).find("select.cgst_percentage").select2().attr({name:"credit_notes_rows["+i+"][cgst_percentage]"});
			$(this).find(".cgst_amount").attr({name:"credit_notes_rows["+i+"][cgst_amount]", id:"credit_notes_rows-"+i+"-cgst_amount"}).rules("add", "required");
			
			$(this).find("select.sgst_percentage").select2().attr({name:"credit_notes_rows["+i+"][sgst_percentage]"});
			$(this).find(".sgst_amount").attr({name:"credit_notes_rows["+i+"][sgst_amount]", id:"credit_notes_rows-"+i+"-sgst_amount"}).rules("add", "required");
			
			$(this).find("select.igst_percentage").select2().attr({name:"credit_notes_rows["+i+"][igst_percentage]"});
			$(this).find(".igst_amount").attr({name:"credit_notes_rows["+i+"][igst_amount]", id:"credit_notes_rows-"+i+"-igst_amount"}).rules("add", "required");
			
			$(this).find(".total_amount").attr({name:"credit_notes_rows["+i+"][total_amount]", id:"credit_notes_rows-"+i+"-total_amount"}).rules("add", "required");
			
			i++;
			
		});
		var i=0;
		$("#main_table tbody#main_tbody tr.main_tr1").each(function(){
			$(this).find(".narration").attr({name:"credit_notes_rows["+i+"][narration]", id:"credit_notes_rows-"+i+"-narration"}).rules("add", "required");
			i++;
		});
		calculate_total();
	}
	
	$('.amount ').die().live("keyup",function() { 
		rename_rows();  
		calculate_total();
    });
	$("select.cgst_percentage").die().live("change",function(){ 
		rename_rows();  
		calculate_total();
	});
	$("select.sgst_percentage").die().live("change",function(){  
		rename_rows();  
		calculate_total();
	});
	$("select.igst_percentage").die().live("change",function(){ 
		rename_rows();  
		calculate_total();
	});
	
	
	function calculate_total(){
		var grand_total=0;
		var cgst_total=0;
		var sgst_total=0;
		var igst_total=0;
		
		$("#main_table tbody#main_tbody tr.main_tr").each(function(){
			var total_amt=0;
			var taxable_amount=parseFloat($(this).find(".amount").val());
			var total_amt=taxable_amount;
			var cgst_percentage=parseFloat($(this).find("td:nth-child(3) option:selected").attr("percentage"));
			var sgst_percentage=parseFloat($(this).find("td:nth-child(5) option:selected").attr("percentage"));
			if(cgst_percentage > 0 || sgst_percentage > 0){
				$(this).find(".igst_amount").val(round(0));
				if(isNaN(cgst_percentage)){ 
						 var cgst_amount = 0;  
						$(this).find(".cgst_amount").val(round(cgst_amount,2));
					}else{  
						var taxable_value=parseFloat($(this).find(".amount").val());
						var cgst_amount = (taxable_value*cgst_percentage)/100;
						$(this).find(".cgst_amount").val(round(cgst_amount,2));
						total_amt=total_amt+cgst_amount;
						cgst_total=cgst_total+cgst_amount;
						//alert(total_amt);
				}
				
				if(isNaN(sgst_percentage)){
						 var sgst_amount = 0;  
						$(this).find(".sgst_amount").val(round(sgst_amount,2));
					}else{  
						var taxable_value=parseFloat($(this).find(".amount").val());
						var sgst_amount = (taxable_value*sgst_percentage)/100;
						$(this).find(".sgst_amount").val(round(sgst_amount,2));
						total_amt=total_amt+sgst_amount;
						sgst_total=sgst_total+sgst_amount;
						
				}
			}else{
				var igst_percentage=parseFloat($(this).find("td:nth-child(7) option:selected").attr("percentage"));
				$(this).find(".sgst_amount").val(round(0));
				$(this).find(".cgst_amount").val(round(0));
					if(isNaN(igst_percentage)){ 
						 var igst_amount = 0;  
						$(this).find(".igst_amount").val(round(igst_amount,2));
					}else{  
						var taxable_value=parseFloat($(this).find(".amount").val());
						var igst_amount = (taxable_value*igst_percentage)/100;
						$(this).find(".igst_amount").val(round(igst_amount,2));
						total_amt=total_amt+igst_amount;
						igst_total=igst_total+igst_amount;
					}
			
				}
				grand_total=grand_total+total_amt;
				$(this).find(".total_amount").val(round(total_amt,2));
				$(".cgst_total_amount").val(round(cgst_total,2));
				$(".sgst_total_amount").val(round(sgst_total,2));
				$(".igst_total_amount").val(round(igst_total,2));
				$(".grand_total").val(round(grand_total,2));
				
			});
			
			
	
	
	}
	
	$('.addrow').live("click",function() {
		add_row();
		rename_rows();
	});
	
	
	
	/* $('.deleterow').live("click",function() {
		$(this).closest(".main_tr").remove();
		$(this).closest(".main_tr1").remove();
		//$("#main_table tbody#main_tbody tr.main_tr1 ").closest("tr").remove();
		//$(this).closest("tr").find(".main_tr1").remove();
		calculate_total();
	});	
	 */
	$('.deleterow').live("click",function() {
		var l=$(this).closest("table tbody").find("tr").length;
		if (confirm("Are you sure to remove row ?") == true) {
			if(l>2){
				var row_no=$(this).closest("tr").attr("row_no");
				var del="tr[row_no="+row_no+"]";
				$(del).remove();
				rename_rows();
			}
		} 
	});
	
	
});
</script>

<table id="sample_table" style="display:none;">
	<tbody>
		<tr class="main_tr preimp">
			<td width="25%"><?php echo $this->Form->input('received_from_id', ['empty'=>'--Select-','options'=>$receivedFroms,'label' => false,'class' => 'form-control input-sm received_from']); ?>
			<?php echo $this->Form->input('row_id', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm row_id']); ?>
			</td>
			<td width="5%" ><?php echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm amount','placeholder'=>'Amount']); ?></td>
			
			<td style=""><?php echo $this->Form->input('q', ['label' => false,'empty'=>'Select','options'=>$cgst_options,'class' => 'form-control input-sm cgst_percentage','placeholder'=>'%','step'=>0.01]); ?></td>
			<td style=""><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm row_textbox cgst_amount','placeholder'=>'Amount','readonly','step'=>0.01]); ?></td>
			
			<td style=""><?php echo $this->Form->input('q', ['label' => false,'empty'=>'Select','options'=>$sgst_options,'class' => 'form-control input-sm','class' => 'form-control input-sm row_textbox sgst_percentage','placeholder'=>'%','step'=>0.01]); ?></td>
			<td style=""><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm sgst_amount','placeholder'=>'Amount','readonly','step'=>0.01]); ?></td>
			
			<td style=""><?php echo $this->Form->input('q', ['label' => false,'empty'=>'Select','options'=>$igst_options,'class' => 'form-control input-sm','class' => 'form-control input-sm row_textbox igst_percentage','placeholder'=>'%','step'=>0.01]); ?></td>
			<td style=""><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm igst_amount','placeholder'=>'Amount','readonly','step'=>0.01]); ?></td>
			<td ><?php echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm total_amount','placeholder'=>'Amount','readonly']); ?></td>
			<td><a class="btn btn-xs btn-default deleterow" href="#" role="button"><i class="fa fa-times"></i></a></td>
		</tr>
		<tr class="main_tr1 preimp">
				<td  colspan="9" class="main"><?php echo $this->Form->input('narration', ['type'=>'textarea','label' => false,'class' => 'form-control input-sm narration','placeholder'=>'Narration','required']); ?></td>
			
			<td></td>
		</tr>
		
	</tbody>
	
</table>


<?php last: ?>