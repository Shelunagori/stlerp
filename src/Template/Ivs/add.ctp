<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-purple-intense ">Create Inventory Voucher : Invoice - <?= h(($Invoice->in1." / IN-".str_pad($Invoice->in2, 3, "0", STR_PAD_LEFT)." / ".$Invoice->in3." / ".$Invoice->in4)) ?></span>
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
							<?php foreach($Invoice->invoice_rows as $invoice_row){ ?>
							<tr class="MainTr">
								<td>
									<input type="hidden" value="<?php echo $invoice_row->id; ?>" class="invoice_row_id"/>
									<input type="hidden" value="<?php echo $invoice_row->item_id; ?>" class="item_id"/>
									<input type="hidden" value="<?php echo $invoice_row->quantity; ?>" class="quantity"/>
									<?= h($invoice_row->item->name); ?>
								</td>
								<td><?= h($invoice_row->quantity); ?></td>
								<td></td>
								<td colspan="3">
									<table class="table subTable">
										<thead>
											<th width="300"><b>Item</b></th>
											<th width="50"><b>Quantity</b></th>
											<th width="150"><b>Serial Nos</b></th>
											<th></th>
										</thead>
										<tbody class="subTbody">
										
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
	<?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
	</div>
</div>
<table id="sample_tb" style="display:none;">
	<tbody>
		<tr class="tr1 SampleTable">
			<td>
			<?php echo $this->Form->input('item_id', ['options' => $Items,'empty'=>'--select--','label' => false,'class' => 'form-control input-sm select_item']); ?>
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
	addRowOnLoad();
	function addRowOnLoad(){
		$('.MainTable tbody.MainTbody tr.MainTr').each(function(){
			var tr1=$("#sample_tb tbody").html();
			$(this).find('table.subTable tbody.subTbody').append(tr1);
		});
		rename_rows_name();
	}
	
	function rename_rows_name(){
		var q=0;
		$('.MainTable tbody.MainTbody tr.MainTr').each(function(){
			var i=0;
			
			$(this).find("td:nth-child(1) input.invoice_row_id").attr({name:"iv_rows["+q+"][invoice_row_id]", id:"iv_rows-"+q+"-invoice_row_id"});
			$(this).find("td:nth-child(1) input.item_id").attr({name:"iv_rows["+q+"][item_id]", id:"iv_rows-"+q+"-item_id"});
			$(this).find("td:nth-child(1) input.quantity").attr({name:"iv_rows["+q+"][quantity]", id:"iv_rows-"+q+"-quantity"});
			
			$(this).find('table.subTable tbody.subTbody tr').each(function(){
				$(this).find("td:nth-child(1) select").attr({name:"iv_rows["+q+"][iv_row_items]["+i+"][item_id]", id:"iv_rows-"+q+"-iv_row_items"+i+"-item_id"}).select2().rules('add', {required: true});
				
				$(this).find("td:nth-child(2) input").attr({name:"iv_rows["+q+"][iv_row_items]["+i+"][quantity]", id:"iv_rows-"+q+"-iv_row_items"+i+"-quantity"}).rules('add', {
							required: true,
							digits: true
					});
				i++;
			});
			q++;
		});
	}
	
	
	
});
</script>