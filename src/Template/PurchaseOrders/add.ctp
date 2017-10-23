<style>
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
	vertical-align: top !important;
}
</style>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Add Purchase Order</span>
		</div>
		<div class="actions">
			<?php echo $this->Html->link('<i class="icon-home"></i> Pull Material Indent','/MaterialIndents/AddNew?pull-request=true',array('escape'=>false,'class'=>'btn btn-xs blue')); ?>
		</div>
	</div>
		
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<?= $this->Form->create($purchaseOrder,['id'=>'form_sample_3']) ?>
			<div class="form-body">
				<div class="row">
					
					<div class="col-md-5">
						<div class="form-group">
							<label class="control-label">Purchase Order No. <span class="required" aria-required="true">*</span></label>
							<div class="row">
								<div class="col-md-4">
									<?php echo $this->Form->input('po1', ['label' => false,'class' => 'form-control input-sm','readonly','value'=>$Company->alias]); ?>
								</div>
								<div class="col-md-4">
									<?php echo $this->Form->input('po3', ['empty'=>'--Select--','options'=>$filenames,'label' => false,'class' => 'form-control input-sm select2me']); ?>
								</div>
								<div class="col-md-4">
									<?php echo $this->Form->input('po4', ['label' => false,'value'=>substr($s_year_from, -2).'-'.substr($s_year_to, -2),'class' => 'form-control input-sm','readonly']); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Supplier <span class="required" aria-required="true">*</span></label>

									<?php
							$options=array();
							//pr($vendor); exit();
							foreach($vendor as $vendors){
								if(empty($vendors->alias)){
									$merge=$vendors->company_name;
								}else{
									$merge=$vendors->company_name.'('.$vendors->alias.')';
								}
								
								$options[]=['text' =>$merge, 'value' => $vendors->id, 'payment_terms' => $vendors->payment_terms];

							}
							echo $this->Form->input('vendor_id', ['empty' => "--Select--",'label' => false,'options' => $options,'class' => 'form-control input-sm select2me','value' => @$vendor->id]); ?>


					
							<label id="payment_terms"></label>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label class="control-label">Date</label>
							<?php echo $this->Form->input('date_created', ['type' => 'text','label' => false,'class' => 'form-control input-sm','value' => date("d-m-Y"),'readonly']); ?>
						</div>
							<span style="color: red;"><?php if($chkdate == 'Not Found'){  ?>
					You are not in Current Financial Year
				<?php } ?></span>	
					</div>
				</div>	
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<?php echo $this->Form->textarea('descryption', ['label' => false,'class' => 'form-control input-sm','value' => 'Dear Sir,
With reference to your price list we are pleased to place an order for the following items as per conditions given below.','required']); ?>
						</div>
					</div>
				</div>
				<div class="table-scrollable">
					<table class="table tableitm" id="main_tb">
						<thead>
							<tr>
								<th width="50">Sr.No. </th>
								<th>Items</th>
								<th width="130">Quantity</th>
								<th width="130">Rate</th>
								<th width="130">Amount</th>
								<th width="70"></th>
							</tr>
						</thead>
						<tbody id="main_tbody">
						<?php if(sizeof(@$to_be_send2)>0){
							$q=0; foreach ($to_be_send2 as $item_id=>$data): ?>
								<tr class="tr1" row_no='<?php echo @$item_id; ?>'>
									<td rowspan="2"><?php echo ++$q; $q--; ?></td>
									<td>
										
										<?php 
										echo $this->Form->input('purchase_order_rows.'.$q.'.item_id', ['label' => false,'type'=>'hidden','value'=>$item_id,'class'=>'item_id']);  ?>
										<?php echo $this->Form->input('purchase_order_rows.'.$q.'.pull_status', ['label' => false,'type'=>'hidden','value'=>'PULLED_FROM_MI']);  ?>
										<?php echo $data['item_name']; ?><br/>
										<span class="label label-sm label-warning ">Pulled from MI</span>
									</td>
									<td>
										<?php echo $this->Form->input('purchase_order_rows.'.$q.'.quantity', ['label' => false,'type'=>'text','value'=>$data['qty'],'class'=>'form-control input-sm','max'=>$data['qty']]); ?>
									</td>
									<td>
										<?php echo $this->Form->input('purchase_order_rows.'.$q.'.rate', ['label' => false,'type'=>'text','class'=>'form-control input-sm']); ?>
									</td>
									<td>
										<?php echo $this->Form->input('purchase_order_rows.'.$q.'.amount', ['label' => false,'type'=>'text','class'=>'form-control input-sm']); ?>
									</td>
									<td  width="70"><a class="btn btn-xs btn-default addrow" href="#" role='button'><i class="fa fa-plus"></i></a><a class="btn btn-xs btn-default deleterow" href="#" role='button'><i class="fa fa-times"></i></a></td>
									
								</tr>
								<tr class="tr2" row_no='<?php echo @$item_id; ?>'>
									<td colspan="4"><?php echo $this->Form->textarea('purchase_order_rows.'.$q.'.description', ['label' => false,'class' => 'form-control input-sm autoExpand','placeholder' => 'Description','rows'=>'1',]); ?></td>
									<td></td>
								</tr>
							
						
						<?php $q++; endforeach; ?>
						<?php } ?>
						</tbody>
						<tfoot>
						
							<tr>
								<td colspan="4" align="right"><b>Total</b></td>
								<td><?php echo $this->Form->input('total', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Total']); ?></td>
								<td></td>
							</tr>
						
							<tr>
								<td colspan="4" align="right"><b>Discount</b></td>
								<td><?php echo $this->Form->input('discount', ['label' => false,'class' => 'form-control input-sm ','placeholder' => 'Discount']); ?>
								<?php echo $this->Form->radio('discount_type',[['value' => '%', 'text' => 'Percent(%)'],['value' => '', 'text' => 'Amount','checked']]); ?>
								</td>
							</tr>
							
							<tr>
								<td colspan="4" align="right"><b>P&F</b></td>
								<td><?php echo $this->Form->input('pnf', ['label' => false,'class' => 'form-control input-sm ','placeholder' => 'P&f']); ?>
								</td>
							</tr>
							
							
						</tfoot>
					</table>
				</div>
				
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Material to be transported at<span class="required" aria-required="true">*</span></label>
							<?php 
							echo $this->Form->input('material_to_be_transported',['label' => false,'class' => 'form-control input-sm','placeholder'=>'Material to be transported at']); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Sale Tax <span class="required" aria-required="true">*</span></label>
							<?php 
							$options=[];
							foreach($sale_tax_ledger_accounts as $key=>$SaleTaxe){
								$tax_figure=$sale_tax_ledger_accounts1[$key];
								$tax_description=$sale_tax_ledger_accounts[$key];
								$options[]=['text' => (string)$tax_figure.'('.$tax_description.')', 'value' => $tax_figure, 'description' => $SaleTaxe];
							}
							echo $this->Form->input('sale_tax_per', ['empty'=>'--Select--','options'=>$options,'label' => false,'class' => 'form-control input-sm select2me','id'=>'saletax']);
							?>
							
							<?php echo $this->Form->input('sale_tax_description', ['type'=>'text','label' => false,'class' => 'form-control input-sm ', 'placeholder'=>'Sale Tax Description']);
							?>
							</div>
							
						
				
											
					</div>
					
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Delivery <span class="required" aria-required="true">*</span></label>
							<?php 
							echo $this->Form->input('delivery',['label' => false,'class' => 'form-control input-sm','placeholder'=>'Delivery']); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Transporter <span class="required" aria-required="true">*</span></label>
							<?php 
							echo $this->Form->input('transporter_id',['empty'=>'--Select--','options'=>$transporters,'label' => false,'class' => 'form-control input-sm select2me']); ?>
						</div>
					</div>
					
				
				</div>
				</div>
				
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">LR to be prepared in favour of</label>
							<?php 
							echo $this->Form->input('lr_to_be_prepared_in_favour_of',['label' => false,'class' => 'form-control input-sm','placeholder'=>'LR to be prepared in favour of', 'value'=>$Company->name]); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Payment Terms <span class="required" aria-required="true">*</span></label>
							<?php 
							echo $this->Form->input('payment_terms',['label' => false,'class' => 'form-control input-sm','placeholder'=>'Payment Terms']); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Road permit form 47 <span class="required" aria-required="true">*</span></label>
							<?php 
							echo $this->Form->input('road_permit_form47',['label' => false,'class' => 'form-control input-sm','placeholder'=>'Road permit form 47']); ?>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-3">
							<div class="form-group">
								<label class="control-label">Excise Duty </label>
								<?php 
								echo $this->Form->input('excise_duty',['label' => false,'class' => 'form-control input-sm','placeholder'=>'Excise Duty']); ?>
							</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<div class="radio-list" >
							<label class="control-label">Excise for customer</label>
							<?php echo $this->Form->radio('is_exceise_for_customer',[['value' => 'Yes', 'text' => 'Yes'],['value' => 'No', 'text' => 'No']],['value'=>'No']); ?>
							</div>
						</div>
					</div>
					
					<div id="ex_div" style="display:none;">
						<div id="qwert"></div>
					</div>
				</div>
				
			</div>
			<div class="form-actions">
					<?php if($chkdate == 'Not Found'){  ?>
					<label class="btn btn-danger"> You are not in Current Financial Year </label>
				<?php } else { ?>
					<button type="submit" class="btn blue-hoki" id='submitbtn'>Add Purchase Order</button>
				<?php } ?>	
				 
			</div>
		<?= $this->Form->end() ?>
		<!-- END FORM-->
	</div>
</div>

<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<style>
#sortable li{
	cursor: -webkit-grab;
}
 
.table thead tr th {
    color: #FFF;
	background-color: #254b73;
}
</style>
<?php echo $this->Html->css('/drag_drop/jquery-ui.css'); ?>
<?php echo $this->Html->script('/drag_drop/jquery-1.12.4.js'); ?>
<?php echo $this->Html->script('/drag_drop/jquery-ui.js'); ?>

<script>
$( function() {
$( "#sortable" ).sortable();
$( "#sortable" ).disableSelection();
} );
</script>
<script>
$(document).ready(function() { 
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
	}, jQuery.format(""))
	//--------- FORM VALIDATION
	var form3 = $('#form_sample_3');
	var error3 = $('.alert-danger', form3);
	var success3 = $('.alert-success', form3);
	form3.validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block help-block-error', // default input error message class
		focusInvalid: true, // do not focus the last invalid input
		rules: {
				company_id:{
					required: true,
				},
				customer_id : {
					  required: true,
				},
				po1 : {
					  required: true,
				},
				po3:{
					required: true
				},
				po4:{
					required: true,
				},
				excise_duty:{
					required: true,
				},
			},
		messages: { // custom messages for radio buttons and checkboxes
			membership: {
				required: "Please select a Membership type"
			},
			service: {
				required: "Please select  at least 2 types of Service",
				minlength: jQuery.validator.format("Please select  at least {0} types of Service")
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
			$('#submitbtn').prop('disabled', true);
			$('#submitbtn').text('Submitting.....');
				put_code_description();
				success3.show();
				error3.hide();
				form[0].submit(); // submit the form
		}

	});
	//--	 END OF VALIDATION

	
	
		<?php if(empty($purchaseOrder->purchase_order_rows)){ ?>
		add_row();
		<?php } ?> 
	
	//add_row();
	
		$("#discount_per").on('click',function(){
		if($(this).is(':checked')){
			$("#discount_text").show();
			$('input[name="discount"]').attr('readonly','readonly');
		}else{
			$("#discount_text").hide();
			$('input[name="discount"]').removeAttr('readonly');
		}
		calculate_total();
	})

	$('select[name="company_id"]').on("change",function() {
		var alias=$('select[name="company_id"] option:selected').attr("alias");
		$('input[name="po1"]').val(alias);
    });

	rename_rows();
	calculate_total();
	
    $('.addrow').die().live("click",function() { 
		add_row();
    });
	
	function add_row(){ 
		var tr1=$("#sample_tb tbody tr.tr1").clone();
		$("#main_tb tbody#main_tbody").append(tr1);
		var tr2=$("#sample_tb tbody tr.tr2").clone();
		$("#main_tb tbody#main_tbody").append(tr2);
		
		var w=0; var r=0;
		$("#main_tb tbody#main_tbody tr.maintr").each(function(){
			$(this).attr("row_no",w);
			r++;
			if(r==2){ w++; r=0; }
		});
		rename_rows();
		calculate_total();
	}
	
		$('.deleterow').die().live("click",function() {
		var l=$(this).closest("table tbody").find("tr").length;
		if (confirm("Are you sure to remove row ?") == true) {
			if(l>2){
				var row_no=$(this).closest("tr").attr("row_no");
				var del="tr[row_no="+row_no+"]";
				$(del).remove();
				rename_rows();
				calculate_total();
			}
		} 
    });
	
	
	function rename_rows(){
			var i=0;
			$("#main_tb tbody#main_tbody tr.tr1").each(function(){
				$(this).find("td:nth-child(1)").html(++i); i--;
				var len=$(this).find("td:nth-child(2) select").length;
				if(len>0){
					//$(this).find("td:nth-child(2) select").select2().attr({name:"purchase_order_rows["+i+"][item_id]", id:"purchase_order_rows-"+i+"-item_id"}).rules("add", "required");
					$(this).find("td:nth-child(2) select").select2().attr({name:"purchase_order_rows["+i+"][item_id]", id:"purchase_order_rows-"+i+"-item_id",popup_id:i}).rules('add', {
							required: true,
							notEqualToGroup: ['.item_id'],
							messages: {
								notEqualToGroup: "Do not select same Item again."
							}
						});
				}else{
					$(this).find("td:nth-child(2) input").attr({name:"purchase_order_rows["+i+"][item_id]", id:"purchase_order_rows-"+i+"-item_id"}).rules("add", "required");
					
					$(this).find("td:nth-child(2) input:eq(1)").attr({name:"purchase_order_rows["+i+"][pull_status]", id:"purchase_order_rows-"+i+"-pull_status"}).rules("add", "required");
					
				}
				
				$(this).find("td:nth-child(3) input").attr({name:"purchase_order_rows["+i+"][quantity]", id:"purchase_order_rows-"+i+"-quantity"}).rules("add", "required");
				$(this).find("td:nth-child(4) input").attr({name:"purchase_order_rows["+i+"][rate]", id:"purchase_order_rows-"+i+"-rate"}).rules("add", "required");
				$(this).find("td:nth-child(5) input").attr("name","purchase_order_rows["+i+"][amount]");
				i++;
			});
			var i=0;
			$("#main_tb tbody#main_tbody tr.tr2").each(function(){
				var row_no=$(this).attr('row_no');
				
				var htm=$(this).find('td:nth-child(1)').find('div.note-editable').html();
				if(!htm){ htm=""; }
				$(this).find('td:nth-child(1)').html('');
				$(this).find('td:nth-child(1)').append('<div id=summer'+i+'>'+htm+'</div>');
				$(this).find('td:nth-child(1)').find('div#summer'+i).summernote();
				$('#main_tb tbody tr.tr2[row_no="'+row_no+'"] td:nth-child(1)').append('<textarea name="purchase_order_rows['+i+'][description]" style="display:none;"></textarea>');
			i++; });
		}
		$('#main_tb input').die().live("keyup","blur",function() { 
		calculate_total();
    });
	
	function calculate_total(){
		var total=0;  var grand_total=0;
		$("#main_tb tbody tr.tr1").each(function(){
			var unit=$(this).find("td:nth-child(3) input").val();
			var Rate=$(this).find("td:nth-child(4) input").val();
			var Amount=unit*Rate;
			$(this).find("td:nth-child(5) input").val(Amount.toFixed(2));
			total=total+Amount;
		});
		$('input[name="total"]').val(total.toFixed(2));
		
	}
	
	put_code_description();
	function put_code_description(){
			var i=0;
			$("#main_tb tbody tr.tr2").each(function(){
				var row_no=$(this).attr('row_no');	
				var code=$(this).find('div#summer'+i).code();
				$('#main_tb tbody tr.tr2[row_no="'+row_no+'"]').find('td:nth-child(1) textarea').val(code);
			i++; });
		}
	
	
	$('select[name=sale_tax_per]').die().live("change",function() { 
		var description=$('select[name=sale_tax_per] option:selected').attr('description');
		$('input[name=sale_tax_description]').val(description);
    });
	
	
});
</script>
<table id="sample_tb" style="display:none;">
	<tbody>
		<tr class="tr1 preimp maintr">
			<td rowspan="2" width="10">0</td>
			<td width="300"><?php echo $this->Form->input('q', ['empty'=>'Select','options' => $items,'label' => false,'class' => 'form-control input-sm item_id']); ?></td>
			<td ><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm quantity','placeholder' => 'Quantity']); ?></td>
			<td ><?php echo $this->Form->input('q', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Rate']); ?></td>
			<td><?php echo $this->Form->input('q', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Amount']); ?></td>
			<td  width="70"><a class="btn btn-xs btn-default addrow" href="#" role='button'><i class="fa fa-plus"></i></a><a class="btn btn-xs btn-default deleterow" href="#" role='button'><i class="fa fa-times"></i></a></td>
		</tr>
		<tr class="tr2 preimp maintr">
			<td colspan="4"><?php echo $this->Form->textarea('description', ['label' => false,'class' => 'form-control input-sm autoExpand','placeholder' => 'Description','rows'=>'1']); ?></td>
			<td></td>
		</tr>
	</tbody>
</table>


<script type="text/javascript">
$(document).ready(function(){
    $("select.vendor").change(function(){
       var vid = $(".vendor option:selected").val();
       var url = "<?php echo $this->Url->build(['controller'=>'PurchaseOrders','action'=>'vid']);?>";
       url=url+'/'+vid,
        $.ajax({
			url: url,
			type: 'GET',
		}).done(function(response) {
			//alert(response);
			$('#vendrid').html(response);
			//$('select[name="account_second_subgroup_id"]').select2();
		});




    });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
    $('select[name="vendor_id"]').on("change",function() {
      var payment_terms = $('select[name="vendor_id"] option:selected').attr("payment_terms");
		if(typeof payment_terms !== "undefined")
		{ $("#payment_terms").text('Payment Terms :' + payment_terms); }
		else{ $("#payment_terms").text(''); }
    });
	
	$('select[name="po3"]').on("change",function() {
		var file=$(this).find('option:selected').val();
		var url = "<?php echo $this->Url->build(['controller'=>'PurchaseOrders','action'=>'customerFromFilename']);?>";
		url=url+'/'+file,
        $.ajax({
			url: url,
			type: 'GET',
		}).done(function(response) {
			$('#qwert').html(response);
		});
	});
	
	$('input[type=radio][name="is_exceise_for_customer"]').on("click",function() {
		var ex=$(this).val();
		if(ex=="Yes"){
			$('#ex_div').show();
		}else{
			$('#ex_div').hide();
		}
	});
	
	$('.select_address').die().live("click",function() {
		$("#myModal1").show();
    });
	
	$('.insert_address').die().live("click",function() { 
		var addr=$(this).text();
		$('textarea[name="customer_address"]').val(addr);
		$("#myModal1").hide();
    });
	$('.closebtn').die().live("click",function() { 
		$("#myModal1").hide();
    });
});
</script>
	