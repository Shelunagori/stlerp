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
		<div class="caption">
			<span class="caption-subject font-purple-intense ">Update Inventory Voucher : Invoice - <?= h(($iv->invoice->in1." / IN-".str_pad($iv->invoice->in2, 3, "0", STR_PAD_LEFT)." / ".$iv->invoice->in3." / ".$iv->invoice->in4)) ?></span>
		</div>
	</div>
	<div class="portlet-body">
	<?= $this->Form->create($iv,['id'=>'form_sample_3']) ?>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Transaction Date<span class="required" aria-required="true">*</span></label>
					<?php echo $this->Form->input('transaction_date', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','value' => date("d-m-Y")]); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table MainTable">
						<thead>
							<tr>
								<th colspan="3"><b>Invoice items (Production)</b></th>
								<th ><b>Consumption</b></th>
							</tr>
							<tr>
								<th width="200"><b>Item</b></th>
								<th width="50"><b>Quantity</b></th>
								<th width="150"><b>Serial Nos</b></th>
								<th></th>
							</tr>
						</thead>
						<tbody class="MainTbody">
							<?php foreach($iv->iv_rows as $iv_row){ ?>
							<tr class="MainTr" row_no='<?php echo @$iv_row->id; ?>'>
								<td>
									<input type="hidden" value="<?php echo $iv_row->id; ?>" class="ivs_id"/>
									<input type="hidden" value="<?php echo $iv_row->invoice_row_id; ?>" class="invoice_row_id"/>
									<input type="hidden" value="<?php echo $iv_row->item_id; ?>" class="item_id"/>
									<input type="hidden" value="<?php echo $iv_row->quantity; ?>" class="quantity"/>
									<input type="hidden" value="<?php echo $iv_row->item->item_companies[0]->serial_number_enable; ?>" class="serial_number"/>

										<?php  echo $iv_row->item->name; ?>
								</td>
								<td><?= h($iv_row->quantity); ?></td>
								<td>
								<?php if($iv_row->item->item_companies[0]->serial_number_enable == '1'){ ?>
									<?php echo $this->requestAction('/SerialNumbers/getSerialNumberListIV?iv_row_id='.$iv_row->id.'&item_id='.$iv_row->item_id); ?>
									
								<?php } ?>
								</td>
								<td colspan="3">
									<table class="table subTable">
										<thead>
											<th width="300"><b>Item</b></th>
											<th width="50"><b>Quantity</b></th>
											<th width="150"><b>Serial Nos</b></th>
											<th></th>
										</thead>
										<tbody class="subTbody">
											<?php  foreach($iv_row->iv_row_items as $iv_row_items){  ?>
												<tr class='tr1 SampleTable'>
													<td>
														<?php echo $this->Form->input('id',['type'=>'hidden','label'=>false,'class'=>'form-control ivrowitemsId','value'=>$iv_row_items->id])?>
														
														<?php echo $this->Form->input('item_id', ['options' => $ItemsOptions,'empty'=>'--select--','label' => false,'class' => 'form-control input-sm select_item','value'=>$iv_row_items->item_id]); ?>
													</td>
													<td>
														<?php echo $this->Form->input('quantity', ['type' => 'text','label' => false,'class' => 'form-control input-sm qty_bx','value'=>$iv_row_items->quantity]); ?>
													</td>
													<td>
													<?php if($iv_row_items->item->item_companies[0]->serial_number_enable == '1'){ ?>
															<?php echo $this->requestAction('/SerialNumbers/getSerialNumberListIVItemsEdit?iv_row_items='.$iv_row_items->id.'&item_id='.$iv_row_items->item_id); ?>
													<?php } ?>		
													</td>
													<td>
														<a class="btn btn-xs btn-default addrow" href="#" role='button'><i class="fa fa-plus"></i></a><a class="btn btn-xs btn-default deleterow" href="#" role='button'><i class="fa fa-times"></i></a>
													</td>
											</tr>
											<?php } ?>
											
										</tbody>
									</table>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Narration</label>
					<?php echo $this->Form->input('narration', ['label' => false,'class' => 'form-control ', 'placeholder' => 'Narration', 'type' => 'textarea']); ?>
				</div>
			</div>
		</div>
	<button type="submit" id='submitbtn' class="btn btn-primary">Submit</button>
    <?= $this->Form->end() ?>
	</div>
</div>
<table id="sample_tb" style="display:none;">
	<tbody>
		<tr class="tr1 SampleTable" >
			<td>
			
			<?php echo $this->Form->input('item_id', ['options' => $ItemsOptions,'empty'=>'--select--','label' => false,'class' => 'form-control input-sm select_item']); ?>
			</td>
			<td>
			<?php echo $this->Form->input('quantity', ['type' => 'text','label' => false,'class' => 'form-control input-sm qty_bx']); ?>
			</td>
			<td></td>
			<td>
			<a class="btn btn-xs btn-default addrow" href="#" role='button'><i class="fa fa-plus"></i></a><a class="btn btn-xs btn-default deleterow" href="#" role='button'><i class="fa fa-times"></i></a>
			</td>
		</tr>
	</tbody>
</table>

<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
	var form3 = $('#form_sample_3');
	var error3 = $('.alert-danger', form3);
	var success3 = $('.alert-success', form3);
	form3.validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block help-block-error', // default input error message class
		focusInvalid: true, // do not focus the last invalid input
		rules: {
			item_serial_numbers:{
				required: true,
			}
		},
	});
	
	$('.addrow').die().live("click",function() {
		var tr=$(this).closest('tr.MainTr');
		addRow(tr);
	});
	
	function addRow(tr){
		var tr1=$("#sample_tb tbody").html();
		tr.find('table.subTable tbody.subTbody').append(tr1);
		rename_rows_name();
	}
	
	/* rename_input();
	function rename_input()
	{
		var q=0;
		$(".MainTable tbody.MainTbody tr.MainTr").each(function(){
			var row_no=$(this).attr('row_no');
			var i=0;
				$(this).find("td:nth-child(3) .sr_no").each(function()
				{
					
					$(this).attr({name:"iv_rows["+q+"][serial_numbers]["+i+"]"}).rules("add", "required");
					i++;
				});
				q++;
			});
	} */
	
	
	$('.select_item ').die().live("change",function() {
		var t=$(this);
		var row_no=t.closest('.MainTable tbody.MainTbody tr.MainTr').attr('row_no');
		var select_item_id=$(this).find('option:selected').val(); 
		var serial_number_enable = $(this).find('option:selected').attr('serial_number_enable');
		var url1="<?php echo $this->Url->build(['controller'=>'SerialNumbers','action'=>'getSerialNumberList']); ?>";
		url1=url1+'?item_id='+select_item_id,
		$.ajax({
			url: url1
		}).done(function(response) { 
		if(serial_number_enable == 1){
			$(t).closest('tr').find('td:nth-child(3)').html(response);
			$(t).closest('tr').find('td:nth-child(3) select').attr({name:"iv_rows["+row_no+"][iv_row_items]["+row_no+"][serial_numbers][]", id:"iv_rows-"+row_no+"-iv_row_items"+row_no+"-serial_numbers"});
			rename_rows_name();
			$(t).closest('tr').find('td:nth-child(3) select').select2({ placeholder: "Serial Number"});
		 }else{
			 
			 $(t).closest('tr').find('td:nth-child(3)').html('');
			 $(t).closest('tr').find('td:nth-child(3) select').attr({name:"iv_rows["+row_no+"][iv_row_items]["+row_no+"][serial_numbers][]", id:"iv_rows-"+row_no+"-iv_row_items"+row_no+"-serial_numbers"});
		 }
  			
		});
	});
	
	
	//addRowOnLoad();
	function addRowOnLoad(){
		$('.MainTable tbody.MainTbody tr.MainTr').each(function(){
			var tr1=$("#sample_tb tbody").html();
			$(this).find('table.subTable tbody.subTbody').append(tr1);
		});
		rename_rows_name();
	}
	rename_rows_name();
	function rename_rows_name(){
		var q=0;
		$('.MainTable tbody.MainTbody tr.MainTr').each(function(){ 
			var i=0; 
			$(this).find("td:nth-child(1) input.ivs_id").attr({name:"iv_rows["+q+"][id]", id:"iv_rows-"+q+"-id"});

			$(this).find("td:nth-child(1) input.invoice_row_id").attr({name:"iv_rows["+q+"][invoice_row_id]", id:"iv_rows-"+q+"-invoice_row_id"});
			$(this).find("td:nth-child(1) input.item_id").attr({name:"iv_rows["+q+"][item_id]", id:"iv_rows-"+q+"-item_id"});
			$(this).find("td:nth-child(1) input.quantity").attr({name:"iv_rows["+q+"][quantity]", id:"iv_rows-"+q+"-quantity"});
			
			$(this).find('table.subTable tbody.subTbody tr').each(function(){ 
				
				$(this).find("td:nth-child(1) input.ivrowitemsId").attr({name:"iv_rows["+q+"][iv_row_items]["+i+"][id]", id:"iv_rows-"+q+"-iv_row_items"+i+"-id"});
				$(this).find("td:nth-child(1) select").attr({name:"iv_rows["+q+"][iv_row_items]["+i+"][item_id]", id:"iv_rows-"+q+"-iv_row_items"+i+"-item_id"}).select2().rules('add', {required: true});
				
				$(this).find("td:nth-child(2) input").attr({name:"iv_rows["+q+"][iv_row_items]["+i+"][quantity]", id:"iv_rows-"+q+"-iv_row_items"+i+"-quantity"}).rules('add', {
							required: true
					});
				if($(this).find('td:nth-child(3) select').length>0){
					$(this).find('td:nth-child(3) select').attr({name:"iv_rows["+q+"][iv_row_items]["+i+"][serial_numbers][]", id:"iv_rows-"+q+"-iv_row_items"+i+"-serial_numbers"}).rules("add", "required");
				}
				i++;
			});
			q++;
		});
	}
	validate_serial();
	$('.qty_bx').die().live("keyup",function() {
		var tr_obj=$(this).closest('tr');  
		var item_id=tr_obj.find('td:nth-child(1) select option:selected').val()
		if(item_id > 0){ 
			var serial_number_enable=tr_obj.find('td:nth-child(1) select option:selected').attr('serial_number_enable');
				if(serial_number_enable == '1'){
					var quantity=tr_obj.find('td:nth-child(2) input').val();
					 if(quantity.search(/[^0-9]/) != -1)
						{
							alert("Item serial number is enabled !!! Please Enter Only Digits")
							tr_obj.find('td:nth-child(2) input').val("");
						}
				rename_rows_name();
				validate_serial();
				}
		}	
		
    });
	
	function validate_serial(){
		$(".MainTable tbody.MainTbody tr.MainTr table.subTable tbody.subTbody tr").each(function(){ 
			var OriginalQty=$(this).find('td:nth-child(2) input').val();
				Quantities = OriginalQty.split('.'); 
				qty=Quantities[0];
				
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
	
	$('.deleterow').live("click",function() {
		var l=$(this).closest("table tbody").find("tr").length;
		if (confirm("Are you sure to remove row ?") == true) {
			if(l>1){
				var row_no=$(this).closest("tr").attr("row_no");
				var del=$(this).closest("tr");
				$(del).remove();
				rename_rows_name();
			}
		} 
	});
	
});
</script>
