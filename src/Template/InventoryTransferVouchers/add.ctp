<style>
.help-block-error{
	font-size: 10px;
}

.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
	vertical-align: top !important;
}



</style>

<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption" >
			<i class="icon-globe font-blue-steel"></i>
				<span class="caption-subject font-blue-steel uppercase">Create Inventory Transfer Voucher</span>
		</div>
		
		<div class="actions">
			
			<?php  echo $this->Html->link('<i class="fa fa-puzzle-piece"></i> In/Out',array('controller'=>'InventoryTransferVouchers','action'=>'add'),array('escape'=>false,'class'=>'btn btn-primary')); ?>
			<?php echo $this->Html->link('In',array('controller'=>'InventoryTransferVouchers','action'=>'InventoryIn'),array('escape'=>false,'class'=>'btn btn-default')); ?>
			<?php echo $this->Html->link('Out',array('controller'=>'InventoryTransferVouchers','action'=>'InventoryOut'),array('escape'=>false,'class'=>'btn btn-default')); ?>
			
		</div>
	</div>
	<div class="portlet-body form">
	<?= $this->Form->create($inventoryTransferVoucher,['id'=>'form_sample_3']) ?>
	<?php 	$first="01";
				$last="31";
				$start_date=$first.'-'.$financial_month_first->month;
				$end_date=$last.'-'.$financial_month_last->month;
				//pr($start_date); exit;
		?>
	<div class="row">
		<div class="col-md-3">
		<label>Transaction Date</label>
		<?php echo $this->Form->input('transaction_date', ['type' => 'text','label' => false,'class' => 'form-control  date-picker','data-date-format'=>'dd-mm-yyyy','placeholder'=>'dd-mm-yyyy','data-date-start-date' 
										=>$start_date ,'data-date-end-date' => $end_date,'value'=>date('d-m-Y')]); ?>
		
		
		
		</div>
		<div class="col-md-6">
			<label>Narration</label>
			<?php echo $this->Form->input('narration', ['type' => 'textarea','label' => false,'class' => 'form-control input-sm ','placeholder' => 'Narration']); ?>
		</div>
	</div>
	<div style="width: 100%;overflow: auto;">
		<table>
		  <tr>
			<td valign="top">
			<h5>For Out -</h5>
				
					<table id="main_table"  class="table table-condensed" style="width: 1000px;">
						<thead>
							<tr>
								<th >Item</th>
								<th  >Quantity</th>
								<th >Serial Number</th>
								<th >Narration</th>
								<th></th>
							</tr>
						</thead>
					<tbody id="maintbody"></tbody>
				</table>
			</td>
			
			<td valign="top">
			<h5>For In -</h5>

				<table id="main_table_1" class="table table-condensed" style="width: 1000px;">
					<thead>
						<tr>
							<th >Item</th>
							<th >Quantity</th>
							<th >Serial Number</th>
							<th >Rate</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody id="maintbody_1"></tbody>
				</table>
			</td>
		  </tr>
		</table>
	</div>
	
		<button type="submit" id='submitbtn' class="btn btn-primary">Submit</button>
<?= $this->Form->end() ?>		
	</div>
</div>	

<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

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
				transaction_date:{
					required: true,
				},
				item_id :{
							required: true,
						  },
				quantity :{
							required: true,
						  }
			},

		messages: { // custom messages for radio buttons and checkboxes
			
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
			validate_serial();
			$('#submitbtn').prop('disabled', true);
			$('#submitbtn').text('Submitting.....');
			success3.show();
			error3.hide();
			form[0].submit(); // submit the form
		}

	});
	
	
	add_row_in();
	add_row_out();

	$('.addrow_1').die().live("click",function() { 
		add_row_in();
	});
	
	$('.addrow').die().live("click",function() { 
		add_row_out();
	});
	
	$('.deleterow').live("click",function() {
		var l=$(this).closest("table tbody").find("tr").length;
		if (confirm("Are you sure to remove row ?") == true) {
			if(l>1){
				var row_no=$(this).closest("tr").attr("row_no");
				var del=$(this).closest("tr");
				$(del).remove();
				rename_rows_out();
			}
		} 
	});
	
	$('.deleterow_1').live("click",function() {
		var l=$(this).closest("table tbody").find("tr").length;
		if (confirm("Are you sure to remove row ?") == true) {
			if(l>1){
				var row_no=$(this).closest("tr").attr("row_no");
				var del=$(this).closest("tr");
				$(del).remove();
				rename_rows_in();
			}
		} 
	});
	
	function add_row_in(){
		var tr2=$("#sampletable_1 tbody tr").clone();
		$("#main_table_1 tbody#maintbody_1").append(tr2);
		rename_rows_in();
		
	}
	
	function add_row_out(){
		var tr2=$("#sampletable tbody tr").clone();
		$("#main_table tbody#maintbody").append(tr2);
		rename_rows_out();
		
	}
	
	$('.select_item_in').die().live("change",function() {
		var tr_obj=$(this).closest('tr');
		sr_nos(tr_obj);
		rename_rows_in();
	});
	
	$('.qty_bx_in').die().live("blur",function() {
		var tr_obj=$(this).closest('tr');  
		var item_id=tr_obj.find('td:nth-child(1) select option:selected').val()
		if(item_id > 0){ 
		sr_nos(tr_obj);
		}
    });
	
	function sr_nos(tr_obj){ 
		var serial_number_enable=tr_obj.find('td:nth-child(1) select option:selected').attr('serial_number_enable');
		//alert(serial_number_enable);
		if(serial_number_enable==1){
			var qty=tr_obj.find('td:nth-child(2) input').val();
			var row_no=tr_obj.attr('row_no');
			tr_obj.find('td:nth-child(3) div.sr_container').html('');
			for(var w=0; w<qty; w++){
				tr_obj.find('td:nth-child(3) div.sr_container').append('<input type="text" name="inventory_transfer_voucher_rows[in]['+row_no+'][sr_no]['+w+']" id="inventory_transfer_voucher_rows-in-'+row_no+'-sr_no-'+w+'" required="required" placeholder="serial number '+w+'" />');
			}
		}else{
			tr_obj.find('td:nth-child(3) div.sr_container').html('');
		}
		
	}
	

	function rename_rows_in(){
		var j=0;
		$("#main_table_1 tbody#maintbody_1 tr.main").each(function(){
			
			$(this).attr('row_no',j);
			$(this).find("td:nth-child(1) select").select2().attr({name:"inventory_transfer_voucher_rows[in]["+j+"][item_id_in]", id:"inventory_transfer_voucher_rows-"+j+"-item_id_in"}).rules("add",
					{ 
						required: true
					});
			$(this).find('td:nth-child(2) input').attr({name:"inventory_transfer_voucher_rows[in]["+j+"][quantity_in]", id:"inventory_transfer_voucher_rows-"+j+"-quantity_in", row:j}).rules("add", "required");
		
			$(this).find('td:nth-child(4) input').attr({name:"inventory_transfer_voucher_rows[in]["+j+"][amount]", id:"inventory_transfer_voucher_rows-"+j+"-amount"}).rules("add", "required");
			j++; 
	   });
	}	
	
	$('.select_item_out').die().live("change",function() {
		var t=$(this);
		var row_no=t.closest('tr').attr('row_no');
		var select_item_id=$(this).find('option:selected').val();
		var url1="<?php echo $this->Url->build(['controller'=>'InventoryTransferVouchers','action'=>'ItemSerialNumber']); ?>";
		url1=url1+'/'+select_item_id,
		
		$.ajax({
			url: url1,
		}).done(function(response) { 
		$(t).closest('tr').find('td:nth-child(3)').html(response);
		$(t).closest('tr').find('td:nth-child(3) select').attr({name:"inventory_transfer_voucher_rows[out]["+row_no+"][serial_number_data][]", id:"inventory_transfer_voucher_rows-"+row_no+"-serial_number_data"});
			$(t).closest('tr').find('td:nth-child(3) select').select2({ placeholder: "Serial Number"});
  			
		});
	});
	

	
	function rename_rows_out(){
		var i=0;
		$("#main_table tbody#maintbody tr.main").each(function(){
			
			$(this).attr('row_no',i);
			$(this).find("td:nth-child(1) select").select2().attr({name:"inventory_transfer_voucher_rows[out]["+i+"][item_id]", id:"inventory_transfer_voucher_rows-"+i+"-item_id"}).rules("add", "required");
			$(this).find('td:nth-child(2) input').attr({name:"inventory_transfer_voucher_rows[out]["+i+"][quantity]", id:"inventory_transfer_voucher_rows-"+i+"-quantity"}).rules("add", "required");
			if($(this).find('td:nth-child(3) select').length>0){
				$(this).find('td:nth-child(3) select').attr({name:"inventory_transfer_voucher_rows[out]["+i+"][serial_number_data][]", id:"inventory_transfer_voucher_rows-"+i+"-serial_number_data"}).rules("add", "required");
			}
			$(this).find('td:nth-child(4) input').attr({name:"inventory_transfer_voucher_rows[out]["+i+"][narration]", id:"inventory_transfer_voucher_rows-"+i+"-narration"}).rules("add", "required");
			i++; 
		});
	}
	
	$('.qty_bx').die().live("keyup",function() {
		validate_serial();
    });
	
	function validate_serial(){
		$("#main_table tbody#maintbody tr.main").each(function(){
			var qty=$(this).find('td:nth-child(2) input').val();
			if($(this).find('td:nth-child(3) select').length>0){
				$(this).find('td:nth-child(3) select').attr('test',qty).rules('add', {
							required: true,
							minlength: qty,
							maxlength: qty,
							messages: {
								maxlength: "select serial number equal to quantity.",
								minlength: "select serial number equal to quantity."
							}
					});
			}
		});	
	}
});

	
	
</script>	

<table id="sampletable" style="display:none;" width="100%">
	<tbody>
		<tr class="main">
			<td style="width: 200px;">
				<?php 
				$item_option=[];
				foreach($display_items as $Item){  
					if(sizeof($Item->item_companies) > 0 ){
						$item_option[]=['text' =>$Item->name, 'value' => $Item->id, 'serial_number_enable' => (int)@$Item->item_companies[0]->serial_number_enable];
					}
				}
				echo $this->Form->input('q', ['empty'=>'Select','options' => $item_option,'label' => false,'style'=>' display: block; width:100%;','class' => 'form-control input-sm select_item_out item_id']); ?>
			</td>
			<td style="width: 100px;">
				<?php echo $this->Form->input('q', ['type' => 'text','label' => false,'class' => 'form-control input-sm qty_bx','placeholder' => 'Quantity','style'=>'width:100%;']); ?>
			</td>
			<td style="width: 200px;"></td>
			<td style="width: 300px;">
				<?php echo $this->Form->input('q', ['type' => 'text','label' => false,'class' => 'form-control','placeholder' => 'Narration','style' => 'width:100%;']); ?>
			</td>
			<td><a class="btn btn-xs btn-default addrow" href="#" role='button'><i class="fa fa-plus"></i></a><a class="btn btn-xs btn-default deleterow" href="#" role='button'><i class="fa fa-times"></i></a></td>
		</tr>
	</tbody>
</table>
<table id="sampletable_1" style="display:none;" width="100%">
	<tbody>
		<tr class="main">
			<td style="width: 200px;">
				<?php 
				$item_option=[];
				foreach($display_items as $Item){  
					if(sizeof($Item->item_companies) > 0 ){
						$item_option[]=['text' =>$Item->name, 'value' => $Item->id, 'serial_number_enable' => (int)@$Item->item_companies[0]->serial_number_enable];
					}
				}
				echo $this->Form->input('q', ['empty'=>'Select','options' => $item_option,'label' => false,'style'=>'width: 100%; display: block;','class' => 'form-control input-sm select_item_in item_id']); ?>
			</td>
			<td style="width: 100px;"> 
				<?php echo $this->Form->input('q', ['type' => 'text','label' => false,'class' => 'form-control input-sm qty_bx_in','placeholder' => 'Quantity']); ?>
			</td>
			<td style="width: 200px;"><div class="sr_container"></div></td>
			<td style="width: 300px;">
				<?php echo $this->Form->input('amount', ['type' => 'text','label' => false,'class' => 'form-control input-sm ','placeholder' => 'Rate']); ?>
			</td>
			<td><a class="btn btn-xs btn-default addrow_1" href="#" role='button'><i class="fa fa-plus"></i></a><a class="btn btn-xs btn-default deleterow_1" href="#" role='button'><i class="fa fa-times"></i></a></td>
		</tr>
	</tbody>
</table>