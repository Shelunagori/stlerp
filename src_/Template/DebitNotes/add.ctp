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
			<span class="caption-subject font-blue-steel uppercase">Add Debit-Note Voucher</span>
		</div>
	</div>
	<div class="portlet-body form">
    <?= $this->Form->create($debitNote,['id'=>'form_sample_3']) ?>
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
					<?php $FY=substr($s_year_from, -2).'-'.substr($s_year_to, -2); ?>
					<?php echo  ("DN/".str_pad($voucher_no,4,'0',STR_PAD_LEFT).'/'.$FY); ?>
					
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
			<div class="col-md-6"></div>
		</div>
		 <div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Supplier/Customer<span class="required" aria-required="true">*</span></label>
					<?php 
					
					echo $this->Form->input('customer_suppiler_id', ['empty'=>'--Select-','options'=>$bankCashes,'label' => false,'class' => 'form-control input-sm select2me customer_suppiler']); ?>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label">Cr/Dr<span class="required" aria-required="true">*</span></label>
					<?php 
					$options=['Dr'=>'Dr','Cr'=>'Cr'];
					echo $this->Form->input('cr_dr', ['options'=>$options,'label' => false,'class' => 'form-control input-sm  cr_dr_amount','style'=>'vertical-align: top !important;','empty'=>'--Select--',]); ?>
				</div>
			</div>
			<?php $ref_types=['New Reference'=>'New Ref','Against Reference'=>'Agst Ref','Advance Reference'=>'Advance']; ?>
			<div class="col-md-6" style="display:">
				<div class="col-md-12" id="main_ref_table">
					
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
						<td colspan="7">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">Additional Note<span class="required" aria-required="true">*</span></label><br/>
									<?php echo $this->Form->input('additional_note', ['type'=>'textarea','label' => false,'class' => 'form-control']); ?>
								</div>
							</div>
						</div>
			
		</div></td>
						<td colspan="1">Total Amount</td>
						<td colspan="2"><?php echo $this->Form->input('grand_total', ['label' => false,'class' => 'form-control input-sm grand_total','readonly','id'=>'grand_total']); ?></td>
					</tr>
					<tr>
						<td colspan="10">
						<?php if($chkdate == 'Not Found'){  ?>
							<label class="btn btn-danger"> You are not in Current Financial Year </label>
						<?php } else { ?>
						<button type="submit" class="btn btn-primary" id='submitbtn'>Create Debit Note</button>
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
				grand_total:{
					
				},
				on_account:{
					
					equalTo: "#grand_total",
					
				}
			},
		
			messages: {
			on_account:{
					equalTo: "Must be equal to Total Amount",
					
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
			put_code_description();
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
			put_code_description();
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
	
	add_row();
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
	
	function rename_rows(){  
		var i=0; 
		$("#main_table tbody#main_tbody tr.main_tr").each(function(){
			var state_id=$('select.customer_suppiler').find('option:selected').attr('state_id');
			$(this).find("select.received_from").select2().attr({name:"debit_notes_rows["+i+"][received_from_id]"}).rules("add", "required");
			$(this).find(".amount").attr({name:"debit_notes_rows["+i+"][amount]", id:"debit_notes_rows-"+i+"-amount"}).rules("add", "required");
			
			if(state_id==8){
				$(this).find("select.cgst_percentage").select2().removeAttr('readonly');
				$(this).find("select.cgst_percentage").select2().attr({name:"debit_notes_rows["+i+"][cgst_percentage]"});
				$(this).find(".cgst_amount").attr({name:"debit_notes_rows["+i+"][cgst_amount]", id:"debit_notes_rows-"+i+"-cgst_amount"}).rules("add", "required");
				
				$(this).find("select.sgst_percentage").select2().removeAttr('readonly');
				$(this).find("select.sgst_percentage").select2().attr({name:"debit_notes_rows["+i+"][sgst_percentage]"});
				$(this).find(".sgst_amount").attr({name:"debit_notes_rows["+i+"][sgst_amount]", id:"debit_notes_rows-"+i+"-sgst_amount"}).rules("add", "required");
				
				$(this).find("select.igst_percentage").select2().attr({readonly:"readonly"});
				//$(this).find("select.igst_percentage").select2().attr({name:"debit_notes_rows["+i+"][igst_percentage]"});
				$(this).find(".igst_amount").attr({name:"debit_notes_rows["+i+"][igst_amount]", id:"debit_notes_rows-"+i+"-igst_amount"}).rules("add", "required");
			}	
			else{
				$(this).find("select.cgst_percentage").select2().attr({readonly:"readonly"});
				$(this).find(".cgst_amount").attr({name:"debit_notes_rows["+i+"][cgst_amount]", id:"debit_notes_rows-"+i+"-cgst_amount"}).rules("add", "required");
				
				$(this).find("select.sgst_percentage").select2().attr({readonly:"readonly"});
				$(this).find(".sgst_amount").attr({name:"debit_notes_rows["+i+"][sgst_amount]", id:"debit_notes_rows-"+i+"-sgst_amount"}).rules("add", "required");
				
				$(this).find("select.igst_percentage").select2().removeAttr('readonly');
				$(this).find("select.igst_percentage").select2().attr({name:"debit_notes_rows["+i+"][igst_percentage]"});
				//$(this).find("select.igst_percentage").select2().attr({name:"debit_notes_rows["+i+"][igst_percentage]"});
				$(this).find(".igst_amount").attr({name:"debit_notes_rows["+i+"][igst_amount]", id:"debit_notes_rows-"+i+"-igst_amount"}).rules("add", "required");
			}

			
			$(this).find(".total_amount").attr({name:"debit_notes_rows["+i+"][total_amount]", id:"debit_notes_rows-"+i+"-total_amount"}).rules("add", "required");
			
			i++;
			
		});
		var i=0;
		$("#main_table tbody#main_tbody tr.main_tr1").each(function(){
			var htm=$(this).find('td:nth-child(1)').find('div.note-editable').html();
			if(!htm){ htm=""; }
			$(this).find('td:nth-child(1)').html('');
			$(this).find('td:nth-child(1)').append('<div id=summer>'+htm+'</div>');
			$(this).find('td:nth-child(1)').find('div#summer').summernote();
			$(this).find('td.main:nth-child(1)').append('<textarea name="debit_notes_rows['+i+'][narration]" style="display:none;"></textarea>');
			$(this).find("td:eq(0) .row_id").val(i);
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
	
	function put_code_description(){
		var i=0;
			$("#main_table tbody#main_tbody tr.main_tr1").each(function(){
			var code=$(this).find('div#summer').code();
			$(this).find('td:nth-child(1) textarea').val(code);
		i++; });
		
	}
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
	$('.deleterefrow').live("click",function() {
		var l=$(this).closest("table tbody").find("tr").length;
		if (confirm("Are you sure to remove row ?") == true) {
			if(l>1){
				$(this).closest("tr").remove();
				rename_rows();
			}
		} 
	});
	
	$("select.customer_suppiler").die().live("change",function(){
		var sel=$(this);
		var bill_to_bill_account=$(this).find('option:selected').attr('bill_to_bill_account');
		$('#main_ref_table .main_ref_table2').html('');
		if(bill_to_bill_account=="Yes"){
			$('#main_ref_table .main_ref_table2').html('');
			//$("table.main_ref_table2 ").html('');
			add_ref_row();
		}
		rename_rows();
	});
	
	
	
	function add_ref_row(){  
		var tr=$("#sample_ref table.main_ref_table2 ").clone();
		$("#main_ref_table").append(tr);
		rename_ref_rows();
	}
	
	$('.ref_type').live("change",function() {
		var current_obj=$(this);
		
		//var sel=$(this).closest('tr.main_tr');
		//var cr_dr=$(this).closest('tr.main_tr').find('td:nth-child(2) select').val();
		var ref_type=$(this).find('option:selected').val();
		var received_from_id=$("select.customer_suppiler").find('option:selected').val();
	//	var received_from_id=$(this).closest('tr.main_tr').find('td select:eq(0)').val();
		if(ref_type=="Against Reference"){
			var url="<?php echo $this->Url->build(['controller'=>'ReferenceDetails','action'=>'listRef']); ?>";
			url=url+'/'+received_from_id, 
			$.ajax({
				url: url,
				type: 'GET',
			}).done(function(response) {
				current_obj.closest('tr').find('td:eq(1)').html(response);
				rename_ref_rows();
			});
		}else if(ref_type=="New Reference" || ref_type=="Advance Reference"){
			current_obj.closest('tr').find('td:eq(1)').html('<input type="text" class="form-control input-sm" placeholder="Ref No." >');
			rename_ref_rows();
		}else{
			current_obj.closest('tr').find('td:eq(1)').html('');
		}
	});
	
	$('.ref_list').live("change",function() {
		var current_obj=$(this);
		var due_amount=$(this).find('option:selected').attr('amt');
		$(this).closest('tr').find('td:eq(2) input').val(due_amount);
		
	});
	
	$('.addrefrow').live("click",function() {
		add_ref_row1();
	});
	
	function add_ref_row1(){  
		var tr=$("#sample_ref table.main_ref_table2 tbody tr").clone();
		$("#main_ref_table table.main_ref_table2 tbody").append(tr);
		rename_ref_rows();
	}
	function rename_ref_rows(){  
		var i=0;
		$("#main_ref_table table.main_ref_table2 tbody tr").each(function(){  
			$(this).find("td:nth-child(1) select").attr({name:"ref_rows["+i+"][ref_type]", id:"ref_rows-"+i+"-ref_type"}).rules("add", "required");
			var is_select=$(this).find("td:nth-child(2) select").length;
			var is_input=$(this).find("td:nth-child(2) input").length;
			
			if(is_select){
				$(this).find("td:nth-child(2) select").attr({name:"ref_rows["+i+"][ref_no]", id:"ref_rows-"+i+"-ref_no"}).rules("add", "required");
			}else if(is_input){
				$(this).find("td:nth-child(2) input").attr({name:"ref_rows["+i+"][ref_no]", id:"ref_rows-"+i+"-ref_no", class:"form-control input-sm ref_number"}).rules('add', {
							required: true
						});
			}
			
			$(this).find("td:nth-child(3) input").attr({name:"ref_rows["+i+"][ref_amount]", id:"ref_rows-"+i+"-ref_amount"}).rules("add", "required");
			
			i++;
		});
	}

$('.ref_amount_textbox').live("keyup",function() { 
		do_ref_total();
	});
$('.ref_list').live("change",function() {
			do_ref_total();
	});
	

	function do_ref_total(){
		var main_amount=123;
		
		if(!main_amount){ main_amount=0; }
		
		var total_ref=0;
		var total_ref_cr=0;
		var total_ref_dr=0;
		$("#main_ref_table table.main_ref_table2 tbody tr").each(function(){  
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
		
		if(total_ref_cr>=0){
				$("#main_ref_table table.main_ref_table2 tfoot tr:nth-child(1) td:nth-child(3) input.on_account").val(round(total_ref_cr,2));
		}
	}
});
</script>

<table id="sample_table" style="display:none;">
	<tbody>
		<tr class="main_tr preimp">
			<td width="25%"><?php echo $this->Form->input('received_from_id', ['empty'=>'--Select-','options'=>$receivedFroms,'label' => false,'class' => 'form-control input-sm received_from']); ?>
			
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
						<td colspan="9" class="main">
							<div class="note-editable" id="summer" ></div>
							<?php echo $this->Form->input('row_id', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm row_id']); ?>
						</td>
							<td colspan="1" class=""></td>
					</tr>
	</tbody>
	
</table>

<div id="sample_ref" style="display:none;">
	<table width="100%" class="main_ref_table2">
		<thead>
			<tr>
				<th width="25%">Ref Type</th>
				<th width="25%">Ref No.</th>
				<th width="30%">Amount</th>
				<th width="10%"></th>
				<th width="5%"></th>
			</tr>
		</thead>
		<tbody class="main_tbody">
			<tr>
				<td><?php echo $this->Form->input('ref_types', ['empty'=>'--Select-','options'=>$ref_types,'label' => false,'class' => 'form-control input-sm ref_type']); ?></td>
				<td class="ref_no"></td>
				<td><?php echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm ref_amount_textbox','placeholder'=>'Amount']); ?></td>
				
				<td><a class="btn btn-xs btn-default deleterefrow" href="#" role="button"><i class="fa fa-times"></i></a></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td align="center" style="vertical-align: middle !important;">Total Amount</td>
				<td></td>
				<td><?php echo $this->Form->input('on_account', ['label' => false,'class' => 'form-control input-sm on_account','placeholder'=>'Amount','readonly']); ?></td>
				
			</tr>
			<tr>
				<td colspan="2"><a class="btn btn-xs btn-default addrefrow" href="#" role="button"><i class="fa fa-plus"></i> Add row</a></td>
				<td></td>
				<td></td>
			</tr>
		</tfoot>
		
	</table>
</div>

<?php $ref_types=['New Reference'=>'New Ref','Against Reference'=>'Agst Ref','Advance Reference'=>'Advance']; ?>
<div id="sample_ref1" style="display:none;">
	<div class="ref" style="padding:4px;">
	<table width="100%" class="ref_table1">
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
				<td width="25%"><?php echo $this->Form->input('ref_types', ['empty'=>'--Select-','options'=>$ref_types,'label' => false,'class' => 'form-control input-sm ref_type_row']); ?></td>
				<td class="ref_no"></td>
				<td width="25%"><?php echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm ref_amount_textbox','placeholder'=>'Amount']); ?></td>
				<td width="25%" style="padding-left:0px; vertical-align: top !important;">
				<?php 
				echo $this->Form->input('ref_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm  cr_dr_amount','value'=>'Dr','style'=>'vertical-align: top !important;']); ?>
			    </td>
				<td><a class="btn btn-xs btn-default deleterefrow" href="#" role="button"><i class="fa fa-times"></i></a></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td align="center" style="vertical-align: middle !important;">On Account</td>
				<td></td>
				<td><?php echo $this->Form->input('on_account', ['label' => false,'class' => 'form-control input-sm on_account','placeholder'=>'Amount','readonly']); ?></td>
				
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

<?php last: ?>