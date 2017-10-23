<?php 
$total_qty=0;
$total_qty=sizeof($item->item_serial_numbers);
$min_qty=0; foreach($item->item_serial_numbers as $item_serial_number){
		if($item_serial_number->status=='Out')
		{
			$min_qty++;
		}
	}
?>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Edit Item</span>
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<?= $this->Form->create($item,['id'=>'form_sample_3']) ?>
			<div class="form-body">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Name <span class="required" aria-required="true">*</span></label>
							<?php echo $this->Form->input('name', ['label' => false,'class' => 'form-control input-sm firstupercase','placeholder'=>'Name']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Alias </label>
							<?php echo $this->Form->input('alias', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Alias']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Unit <span class="required" aria-required="true">*</span></label>
							<?php echo $this->Form->input('unit_id', ['options' => $units,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'Name']); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Category <span class="required" aria-required="true">*</span></label>
							<?php echo $this->Form->input('item_category_id', ['empty'=>'--Select--','options' => $ItemCategories,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'Category']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Group <span class="required" aria-required="true">*</span></label>
							<div id="item_group_div">
							<?php echo $this->Form->input('item_group_id', ['empty'=>'--Select--','options' => $ItemGroups,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'Group']); ?>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Sub-Group <span class="required" aria-required="true">*</span></label>
							<div id="item_sub_group_div">
							<?php echo $this->Form->input('item_sub_group_id', ['empty'=>'--Select--','options' => $ItemSubGroups,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'Sub-Group']); ?>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">HSN Code <span class="required" aria-required="true">*</span></label>
							<div>
							<?php echo $this->Form->input('hsn_code', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'HSN Code','maxlength'=>'8']); ?>
							</div>
						</div>
					</div>
				</div>
				
				
				<hr>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Minimum Quantity <span class="required" aria-required="true">*</span></label>
							<?php echo $this->Form->input('minimum_quantity', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Minimum Quantity']); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Maximum Quantity <span class="required" aria-required="true">*</span></label>
							<?php echo $this->Form->input('maximum_quantity', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Maximum Quantity']); ?>
						</div>
					</div>
					
				</div>
				
				<div class="row">
					<div class="col-md-4">
						<label class="control-label">Source <span class="required" aria-required="true">*</span></label>
						<div class="checkbox-list">
							<?php echo $this->Form->radio('source',[['value' => 'Assembled', 'text' => 'Assembled'],['value' => 'Purchessed', 'text' => 'Purchessed'],['value' => 'Manufactured', 'text' => 'Manufactured'],['value' =>  'Purchessed/Manufactured', 'text' => 'Purchessed/Manufactured']]); ?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="form-actions">
				 <button type="submit" class="btn blue-hoki">Edit Item</button>
			</div>
		<?= $this->Form->end() ?>
		<!-- END FORM-->
	</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
	
	
	//--------- FORM VALIDATION
	var form3 = $('#form_sample_3');
	var error3 = $('.alert-danger', form3);
	var success3 = $('.alert-success', form3);
	form3.validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block help-block-error', // default input error message class
		focusInvalid: true, // do not focus the last invalid input
		rules: {
			unit_id :{
				required: true,
			},
			name  : {
				  required: true,
			},
			alias  : {
				  required: true,
			},
			item_category_id    : {
				  required: true,
			},
			item_group_id    : {
				  required: true,
			},
			item_sub_group_id    : {
				  required: true,
			},
			
			minimum_quantity  : {
				  required: true,
			}
			
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
			success3.hide();
			error3.show();
			Metronic.scrollTo(error3, -200);
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
			q="ok";
			$("#main_tb tbody tr.tr1").each(function(){
				var w=$(this).find("td:nth-child(3) input").val();
				var r=$(this).find("td:nth-child(4) input").val();
				if(w=="" || r==""){
					q="e";
				}
			});
			if(q=="e"){
				$("#row_error").show();
				return false;
			}else{
				success3.show();
				error3.hide();
				form[0].submit(); // submit the form
			}
		}

	});
	//--	 END OF VALIDATION
	var ob_quantity=parseFloat($('input[name="ob_quantity"]').val());
		if(isNaN(ob_quantity)) { var ob_quantity = 0; }
		var ob_rate=parseFloat($('input[name="ob_rate"]').val());
		if(isNaN(ob_rate)) { var ob_rate = 0; }
		var total=ob_quantity*ob_rate;
		$('input[name="ob_value"]').val(Math.round(total.toFixed(8)));

	$('input[name="ob_quantity"],input[name="ob_rate"]').die().live("keyup",function() { 
		var ob_quantity=parseFloat($('input[name="ob_quantity"]').val());
		if(isNaN(ob_quantity)) { var ob_quantity = 0; }
		var ob_rate=parseFloat($('input[name="ob_rate"]').val());
		if(isNaN(ob_rate)) { var ob_rate = 0; }
		var total=ob_quantity*ob_rate;
		$('input[name="ob_value"]').val(Math.round(total.toFixed(8)));
    });
	$('input[name="ob_value"]').die().live("blur",function() { 
		var ob_quantity=parseFloat($('input[name="ob_quantity"]').val());
		if(isNaN(ob_quantity)) { var ob_quantity = 0; }
		var ob_value=parseFloat($('input[name="ob_value"]').val());
		if(isNaN(ob_value)) { var ob_value = 0; }
		
		var total=ob_value/ob_quantity;
	
		$('input[name="ob_rate"]').val((total.toFixed(8)));
    });
	$('.allLetter').keyup(function(){
	var inputtxt=  $(this).val();
	var numbers =  /^[0-9]*\.?[0-9]*$/;
	
	if(inputtxt.match(numbers))  
	{  
	} 
	else  
	{  
		$(this).val('');
		return false;  
	}
});


$('select[name="item_category_id"]').on("change",function() {
	$('#item_group_div').html('Loading...');
	var itemCategoryId=$('select[name="item_category_id"] option:selected').val();
	var url="<?php echo $this->Url->build(['controller'=>'ItemGroups','action'=>'ItemGroupDropdown']); ?>";
	url=url+'/'+itemCategoryId,
	$.ajax({
		url: url,
		type: 'GET',
	}).done(function(response) {
		$('#item_group_div').html(response);
	});
});

$('select[name="item_group_id"]').die().live("change",function() {
	$('#item_sub_group_div').html('Loading...');
	var itemGroupId=$('select[name="item_group_id"] option:selected').val();
	var url="<?php echo $this->Url->build(['controller'=>'ItemSubGroups','action'=>'ItemSubGroupDropdown']); ?>";
	url=url+'/'+itemGroupId,
	$.ajax({
		url: url,
		type: 'GET',
	}).done(function(response) {
		$('#item_sub_group_div').html(response);
	});
});
$('input[name="ob_quantity"]').die().live("blur",function() {
	var serial_number=$('input[name=serial_number_enable]:checked').val(); 
	if(serial_number==1){
		update_ob_quantity_load();
	}
	update_sr_textbox();
 });
 
 $('input[name="serial_number_enable"]').die().live("change",function() {
	update_ob_quantity_load();
	update_sr_textbox();
});
var total_qty_out=$('input[name="total_out_qty"]').val();

function update_ob_quantity_load(){ 
	var total_out=$('input[name="ob_quantity"]').val();
	if(total_qty_out==0){
		var total_out_qty=0;	
	}
	else{
		var total_out_qty=$('input[name="total_out_qty"]').val();
	}
	var tatal_quantity=total_out-total_out_qty;
	$('input[name="ob_quantity_load"]').val(tatal_quantity.toFixed());
	update_sr_textbox();
}

function update_sr_textbox(){
		var r=0;
		var serial_number=$('input[name=serial_number_enable]:checked').val(); 
		var quantity=$('input[name="ob_quantity_load"]').val();
		var l=$('#itm_srl_num').find('input').length;
		if(serial_number==1){ 
			
					if(quantity < l){
				
						for(i=l;i>=quantity;i--){ 
						
						$('input[ids="sr_no['+i+']"]').remove();
						//$('botmdiv['+i+']').remove();
						}
					}
					//
					if(quantity > l){
						//l=l+1;
						for(i=l;i<quantity;i++){
						$('#itm_srl_num').append('<div style="margin-bottom:6px;" class="botmdiv['+i+']"><input type="text" class="sr_no" name="serial_numbers['+i+'][]" ids="sr_no['+i+']" id="sr_no'+l+'"/></div>');
						
						$('#itm_srl_num').find('input#sr_no'+l).rules('add', {required: true});
						l++;
						}
					}
				}
				
		else{
				$('#itm_srl_num').find('input.sr_no').remove();
		}
	}
	
});
</script>