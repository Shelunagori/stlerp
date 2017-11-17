<style>
table > thead > tr > th, table > tbody > tr > th, table > tfoot > tr > th, table > thead > tr > td, table > tbody > tr > td, table > tfoot > tr > td{
	vertical-align: top !important;
	border-bottom:solid 1px #CCC;
}
.help-block-error{
	font-size: 10px;
}
</style>
<?php 
if($sales_order->customer->tin_no==''){?>
	<div class="modal fade in"  ></div>
	<div id="myModaltin" class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" >
				Please Enter Tin Number For <b><?php echo $sales_order->customer->customer_name; ?></b>
			</div>
			<div class="modal-footer">
				<button class="btn default closetin">Close</button>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
$('.closetin').on("click",function() { 
		$("#myModaltin").hide();
    });
});
</script>
	
<?php } else { ?>

<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Add Invoice</span>
			<?php if($process_status!="New"){ ?>
			<br/><span style=" font-size: 13px; ">Converting Sales Orders: <?= h(($sales_order->so1.'/QO-'.str_pad($sales_order->so2, 3, '0', STR_PAD_LEFT).'/'.$sales_order->so3.'/'.$sales_order->so4)) ?></span>
			<?php } ?>
		</div>
		<div class="actions">
			<?php echo $this->Html->link('<i class="icon-home"></i> Pull Sales-Order','/SalesOrders/index?pull-request=true',array('escape'=>false,'class'=>'btn btn-xs blue')); ?>
		</div>
	</div>
	<?php if($process_status!="New"){ ?>
	<div class="portlet-body form">
		<?= $this->Form->create($invoice,['id'=>'form_sample_3']) ?>
		<div class="form-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-4 control-label">Sales Account<span class="required" aria-required="true">*</span></label>
						<div class="col-md-8">
							<?php echo $this->Form->input('sales_ledger_account', ['empty' => "--Select--",'label' => false,'options' => $ledger_account_details,'class' => 'form-control input-sm select2me','required']); ?>
						</div>
					</div>
				</div>
				<div class="col-md-3">
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="col-md-3 control-label">Date</label>
						<div class="col-md-9">
							<?php echo $this->Form->input('date_created', ['type' => 'text','label' => false,'class' => 'form-control input-sm','value' => date("d-m-Y"),'readonly']); ?>
						</div>
						<span style="color: red;"><?php if($chkdate == 'Not Found'){  ?>
					You are not in Current Financial Year
				<?php } ?></span>
					</div>
				</div>
			</div><br/>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Customer</label>
						<div class="col-md-9">
						<?php echo $this->Form->input('customer_id', ['type'=>'hidden','value' => @$sales_order->customer_id]); ?>
						<?php echo $sales_order->customer->customer_name.'('; echo $sales_order->customer->alias.')'; ?>	
							
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Invoice No.</label>
						<div class="col-md-9 padding-right-decrease">
							<?php echo $this->Form->input('in1', ['type'=>'hidden','value' => @$sales_order->so1]); ?>
							<?php echo $this->Form->input('in3', ['type'=>'hidden','value' => @$sales_order->so3]); ?>
							<?php echo $this->Form->input('in4', ['type'=>'hidden','value' => substr($s_year_from, -2).'-'.substr($s_year_to, -2)]); ?>
							<?php echo @$sales_order->so1; ?>/<?php echo @$sales_order->so3; ?>/<?php echo substr($s_year_from, -2).'-'.substr($s_year_to, -2); ?>
						</div>
						
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Address</label>
						<div class="col-md-9">
							<?php echo $this->Form->input('customer_address', ['label' => false,'class' => 'form-control','placeholder' => 'Address','value' => @$sales_order->customer_address]); ?>
							<a href="#" role="button" class="pull-right select_address" >
							Select Address </a>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">LR No. <span class="required" aria-required="true">*</span></label>
						<div class="col-md-9">
							<?php echo $this->Form->input('lr_no', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder'=>'LR No']); ?>
						</div>
					</div>
					<br/><br/>
					<div class="form-group">
						<label class="col-md-3 control-label">Salesman  </label>
						<div class="col-md-9">
							<?php echo @$sales_order->employee->name; ?>
						</div>
					</div><br/>
					
					<div class="form-group">
						<label class="col-md-3 control-label">Carrier <span class="required" aria-required="true">*</span></label>
						<div class="col-md-9">
							<?php echo $this->Form->input('transporter_id', ['empty' => "--Select--",'label' => false,'options' => $transporters,'class' => 'form-control input-sm select2me','value' => @$sales_order->transporter_id]); ?>
						</div>
					</div>
				</div>
			</div><br/>

			<div class="row">
				<div class="col-md-6">
				
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3  control-label">Delivery Description <span class="required" aria-required="true">*</span></label>
						<div class="col-md-9">
						<?php echo $this->Form->input('delivery_description', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Delivery Description','value'=>$sales_order->delivery_description]); ?>
						</div>
					</div>
				</div>
				
			</div><br/>
			
			<div class="row">
			<div class="col-md-6">
				<div class="form-group">
						<label class="col-md-3 control-label">Customer PO NO  </label>
						<div class="col-md-9">
								<?php echo $sales_order->customer_po_no; ?>
						</div>
					</div>
			</div>
			<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">PO DATE  <span class="required" aria-required="true">*</span></label>
						<div class="col-md-9">
						
							<?php echo @date("d-m-Y",strtotime($sales_order->po_date)); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
			<?php if($sales_order->road_permit_required=='Yes') {?>
				<div class="col-md-6">
				<div class="form-group">
						<label class="col-md-3 control-label">Road Permit No  <span class="required" aria-required="true">*</span></label>
						<div class="col-md-9">
							<?php echo $this->Form->input('form47', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Form 47','required']); ?>
						</div>
					</div>
				</div>
			<?php } ?>
				<?php if($sales_order->form49=='Yes'){ ?>
				<div class="col-md-6">
				<div class="form-group">
						<label class="col-md-3 control-label">Form 49 <span class="required" aria-required="true">*</span></label>
						<div class="col-md-9">
							<?php echo $this->Form->input('form49', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Form 49','required']); ?>
						</div>
					</div>
				</div>
				<?php } ?>
			</div><br/>
			<input type="text"  name="checked_row_length" id="checked_row_length" style="height: 0px;padding: 0;border: none;" />
			
			<table class="table tableitm" id="main_tb">
				<thead>
					<tr>
						<th width="50">Sr.No. </th>
						<th>Items</th>
						<th width="130">Quantity</th>
						<th width="130">Rate</th>
						<th width="130">Amount</th>
						<th width="130">Sale Tax</th>
						<th width="70"></th>
					</tr>
				</thead>
				<tbody>
					<?php $ed_des=[];
					if(!empty($sales_order->sales_order_rows)){
					$q=0; foreach ($sales_order->sales_order_rows as $sales_order_rows): 
						$ed_des[]=$sales_order_rows->excise_duty;
					 if($sales_order_rows->quantity != @$sales_orders_qty[@$sales_order_rows->id]){ 	
					?>
						<tr class="tr1  firsttr " row_no='<?php echo @$sales_order_rows->id; ?>'>
						
							<td rowspan="2">
								<?php echo ++$q; --$q; ?>
								<?php echo $this->Form->input('q', ['label' => false,'type' => 'hidden','value' => @$sales_order_rows->id,'readonly']); ?>
							</td>
							<td>
							<?php echo $this->Form->input('q', ['label' => false,'type' => 'hidden','value' => @$sales_order_rows->item->id,'readonly']); ?>
							<?php echo $sales_order_rows->item->name; ?></td>
							<td><?php echo $this->Form->input('q', ['label' => false,'type' => 'text','class' => 'form-control input-sm quantity','placeholder'=>'Quantity','value' => @$sales_order_rows->quantity-@$sales_orders_qty[@$sales_order_rows->id],'readonly','max'=>@$sales_order_rows->quantity-@$sales_orders_qty[@$sales_order_rows->id]]); ?></td>
							<td><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Rate','value' => @$sales_order_rows->rate,'readonly','step'=>0.01]); ?></td>
							<td><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Amount','value' => @$sales_order_rows->amount,'readonly','step'=>0.01]); ?></td>
							<td><?php echo @$sales_order_rows->sale_tax->tax_figure.'('.@$sales_order_rows->sale_tax->invoice_description.')'; ?></td>
							<td>
								<label><?php echo $this->Form->input('check.'.$q, ['label' => false,'type'=>'checkbox','class'=>'rename_check','value' => @$sales_order_rows->id]); ?></label>
								<?php echo $this->Form->input('q', ['label' => false,'type' => 'hidden','value' => @$sales_order_rows->sale_tax->tax_figure]); ?>
								<?php echo $this->Form->input('st_description', ['type' => 'hidden','label' => false,'value' => @$sales_order_rows->sale_tax->invoice_description]); ?>
								<?php echo $this->Form->input('st_id', ['type' => 'hidden','label' => false,'value' => @$sales_order_rows->sale_tax->id]); ?>
								<?php echo $this->Form->input('st_ledger_account_id', ['type' => 'hidden','label' => false,'value' => @$sale_tax_ledger_accounts[$sales_order_rows->sale_tax->id]]); ?>
							</td>
						</tr>
						<tr class="tr2  secondtr" row_no='<?php echo @$sales_order_rows->id; ?>'>
							<td colspan="5">

							<div contenteditable="true" class="note-editable" id="summer<?php echo $q; ?>"><?php echo @$sales_order_rows->description; ?></div>
							<?php echo $this->Form->input('q', ['label' => false,'type' => 'textarea','class' => 'form-control input-sm ','placeholder'=>'Description','style'=>['display:none'],'value' => @$sales_order_rows->description,'readonly','required']); ?>
							</td>
							<td></td>
						</tr>
						<?php if(@$sales_order_rows->item->item_companies[0]->serial_number_enable==1){ ?>
						<tr class="tr3" row_no='<?php echo @$sales_order_rows->id; ?>'>
							<td></td>
							<td colspan="5">
								<?php echo $this->requestAction('/SerialNumbers/getSerialNumberList?item_id='.$sales_order_rows->item_id); ?>
							</td>
						</tr>
					 <?php } $q++; }  endforeach; }?>
				</tbody>
			</table>
			<table class="table tableitm" id="tbl2">
				<tr>
					<td  align="right">
					<?php 
						if($sales_order->discount_type==1){ $checked2="Checked";
							echo 'In Sales Order '; echo @$sales_order->discount_per; echo ' %  ' ;
							 } 
						else{	$checked2="";
							echo 'In Sales Order '; echo  @$sales_order->discount; echo ' Rs ' ;
							 } 
					?> 
					<b>Discount <label><?php echo $this->Form->input('discount_type', ['type' => 'checkbox','label' => false,'class' => 'form-control input-sm','id'=>'discount_per','Checked'=>$checked2]); ?></label>(in %)</b>
						
						<div class="input-group col-md-2" style="display:none;" id="discount_text">
						<input type="text" name="discount_per" class="form-control input-sm" placeholder="5.5"  'step'=0.01 value="<?php echo $sales_order->discount_per; ?> "><span class="input-group-addon">%</span>
					</div>
					</td>
				
					<td><?php echo $this->Form->input('discount', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Discount','step'=>0.01,'value'=>$sales_order->discount]); ?></td>
				</tr>
				<?php if(in_array('Yes',@$ed_des)) { ?>
				<tr style="background-color:#e6faf9;">
					<td align="right"><b><?php echo $this->Form->input('ed_description', ['type'=>'textarea','label' => false,'class' => 'form-control input-sm','placeholder' => 'Excise-Duty Description']); ?> </b></td>
					<td><?php echo $this->Form->input('exceise_duty', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Excise-Duty','value' => 0]); ?></td>
				</tr>
				<?php } ?>
				<tr>
					<td align="right"><b>Total</b></td>
					<td width="20%"><?php echo $this->Form->input('total', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Total','value' => 0,'step'=>0.01,'readonly']); ?></td>
				</tr>
				<tr>
					<td  align="right">
					<?php
						if($sales_order->pnf_type==1){
							echo 'In Sales Order '; echo @$sales_order->pnf_per; echo ' % ';
							 } 
						else{
							echo 'In Sales Order '; echo  @$sales_order->pnf; echo ' Rs ' ;
							 }
							echo ' ';
						?> 
					<b>P&F <label><?php echo $this->Form->input('pnf_type', ['type' => 'checkbox','label' => false,'class' => 'form-control input-sm','id'=>'pnfper']); ?></label>(in %)</b>
					<div class="input-group col-md-2" style="display:none;" id="pnf_text">
						<input type="text" name="pnf_per" class="form-control input-sm" placeholder="5.5"  'step'=0.01><span class="input-group-addon">%</span>
					</div>
					</td>
					<td><?php echo $this->Form->input('pnf', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'P&F','step'=>0.01]); ?></td>
				</tr>
				<tr>
					<td  align="right"><b>Total after P&F </b></td>
					<td><?php echo $this->Form->input('total_after_pnf', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Total after P&F','readonly','step'=>0.01]); ?></td>
				</tr>
				<tr>
					<td  align="right">
						<?php if($process_status!="New"){ ?>
							
							<input type="hidden" name="sale_tax_id"  />
							<input type="hidden" name="st_ledger_account_id" />
							<input type="text" name="sale_tax_description" class="form-control input-sm" readonly placeholder="Sale Tax Description" style="text-align:right;" />
							<div class="input-group col-md-2">
							<div class="input-group">
							<input type="text" name="sale_tax_per" class="form-control input-sm" readonly><span class="input-group-addon">%</span>
							</div>
							</div>
						
						<?php }else{ ?>
						
						<input type="hidden" name="sale_tax_id" />
						<input type="hidden" name="st_ledger_account_id" />
						<input type="text" name="sale_tax_description" class="form-control input-sm" readonly placeholder="Sale Tax Description" style="text-align:right;" />
						<div class="input-group col-md-2">
							<div class="input-group">
						<?php					
							$options=[];
							foreach($SaleTaxes as $SaleTaxe){
								$options[]=['text' => (string) $SaleTaxe->tax_figure.'('.$SaleTaxe->invoice_description.')', 'value' => $SaleTaxe->tax_figure, 'description' => $SaleTaxe->invoice_description];
							}
							echo $this->Form->input('sale_tax_per', ['options'=>$options,'label' => false,'class' => 'form-control input-sm']); 
						} ?>
							</div>
						</div>
					</td>
					<td><?php echo $this->Form->input('sale_tax_amount', ['type' => 'text','label' => false,'class' => 'form-control input-sm','readonly','step'=>0.01]); ?></td>
				</tr>
				<tr>
					<td  align="right">
					<?php echo $this->Form->input('fright_text', ['type'=>'textarea','label' => false,'class' => 'form-control input-sm','placeholder'=>'Additional text for Fright Amount','style'=>['text-align:right']]); ?>
					</td>
					<td>
					<?php echo $this->Form->input('fright_ledger_account', ['empty' => "--Fright Account--",'label' => false,'options' =>$ledger_account_details_for_fright,'class' => 'form-control input-sm select2me','required']); ?>
						
					<?php echo $this->Form->input('fright_amount', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Fright Amount','step'=>0.01,'value'=>@$sales_order->fright_amount]); ?></td>
				</tr>
				<tr>
					<td  align="right"><b>Grand Total </b></td>
					<td><?php echo $this->Form->input('grand_total', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Grand Total','readonly','step'=>0.01]); ?></td>
				</tr>
			</table>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label class="col-md-3 control-label">Additional Note</label>
						<div class="col-md-9">
							<?php echo $this->Form->input('additional_note', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Additional Note']); ?>
						</div>
					</div>
				</div>
			</div><br/>
			
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="col-md-6 control-label">Credit Limits</label>
						<div class="col-md-6" id="due">
							<?php echo $this->Form->input('credit_limit', ['label' => false,'class' => 'form-control input-md','placeholder'=>'','readonly','value' => @$sales_order->customer->credit_limit]); ?><br/>
							<a href="#" role="button" id="update_credit_limit">Update Credit Limit</a>
							<span id="update_credit_limit_wait"></span>
						</div>
					</div>
				</div>
		
				<div class="col-md-4">
					<div class="form-group">
						<label class="col-md-6 control-label">Due Payment</label>
						<div class="col-md-6" id="due">
							<?php echo $this->Form->input('old_due_payment', ['label' => false,'class' => 'form-control input-md','placeholder'=>'','readonly','value'=>$old_due_payment]); ?>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="col-md-6 control-label">New Due Payment</label>
						<div class="col-md-6" id="due">
							<?php echo $this->Form->input('new_due_payment', ['label' => false,'class' => 'form-control input-md','placeholder'=>'','readonly','max'=>@$sales_order->customer->credit_limit]); ?>
						</div>
					</div>
				</div>
			
				
				
			</div><br/>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="col-md-6 control-label">Temporary Limit</label>
						<div class="col-md-6" id="due">
							<?php echo $this->Form->input('temp_limit', ['label' => false,'class' => 'form-control input-md','placeholder'=>'']); ?>
						</div>
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="form-group">
						<label class="col-md-6 control-label">Customer TIN</label>
						<div class="col-md-6" id="due">
							<?php echo $this->Form->input('customer_tin', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'','readonly','value' => @$sales_order->customer->tin_no,'required']); ?><br/>
							
						</div>
					</div>
				</div>
			</div>
			
			<?php $ref_types=['New Reference'=>'New Ref','Against Reference'=>'Agst Ref','Advance Reference'=>'Advance']; ?>
				
				<div class="row">
					<div class="col-md-8">
					<table width="100%" class="main_ref_table">
						<thead>
							<tr>
								<th width="25%">Ref Type</th>
								<th width="25%">Ref No.</th>
								<th width="30%">Amount</th>
								<th width="10%"></th>
								<th width="5%"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $this->Form->input('ref_types', ['empty'=>'--Select-','options'=>$ref_types,'label' => false,'class' => 'form-control input-sm ref_type']); ?></td>
								<td class="ref_no"></td>
								<td><?php echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm ref_amount_textbox','placeholder'=>'Amount']); ?></td>
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
								<td><?php echo $this->Form->input('on_acc_cr_dr', ['label' => false,'class' => 'form-control input-sm on_acc_cr_dr','placeholder'=>'Cr_Dr','readonly']); ?></td>
								<td></td>
							</tr>
						</tfoot>
					</table>
					<a class="btn btn-xs btn-default addrefrow" href="#" role="button"><i class="fa fa-plus"></i> Add row</a>
					</div>
				</div>
		<?php echo $this->Form->input('process_status', ['type' => 'hidden','value' => @$process_status]); ?>
		<?php echo $this->Form->input('sales_order_id', ['type' => 'hidden','value' => @$sales_order_id]); ?>
		
		<div class="form-actions">
			<div class="row">
				<div class="col-md-offset-3 col-md-9">
			   <?php if($chkdate == 'Not Found'){  ?>
					<label class="btn btn-danger"> You are not in Current Financial Year </label>
				<?php } else { ?>
					<?= $this->Form->button(__('ADD INVOICE'),['class'=>'btn btn-primary','id'=>'add_submit','type'=>'Submit']) ?>
				<?php } ?>	

				</div>
			</div>
		</div>
		
		<?= $this->Form->end() ?>
		
	</div>
	<?php } ?>
	
		
</div>
</div>
<style>
.table thead tr th {
    color: #FFF;
	background-color: #254b73;
}
</style>


<table id="sample_tb" style="display:none;">
	<tbody>
		<tr class="tr1">
			<td rowspan="2">0</td>
			<td><?php echo $this->Form->input('item_id', ['empty'=>'Select','options' => $items,'label' => false,'class' => 'form-control input-sm','placeholder' => 'Item']); ?></td>
			<td><?php echo $this->Form->input('unit[]', ['type' => 'text','label' => false,'class' => 'form-control input-sm quantity','placeholder' => 'Quantity']); ?></td>
			<td><?php echo $this->Form->input('rate[]', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Rate','step'=>0.01]); ?></td>
			<td><?php echo $this->Form->input('amount[]', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Amount','step'=>0.01]); ?></td>
			<td><a class="btn btn-xs btn-default addrow" href="#" role='button'><i class="fa fa-plus"></i></a><a class="btn btn-xs btn-default deleterow" href="#" role='button'><i class="fa fa-times"></i></a></td>
		</tr>
		<tr class="tr2">
			<td colspan="4"><?php echo $this->Form->textarea('description', ['label' => false,'class' => 'form-control input-sm autoExpand','placeholder' => 'Description','rows'=>'1']); ?></td>
			<td></td>
		</tr>
	</tbody>
</table>


<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

	 
<div id="myModal12" class="modal fade in" tabindex="-1"  style="display: none; padding-right: 12px;"><div class="modal-backdrop fade in" ></div>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id="result_ajax">
				
			</div>
			<div class="modal-footer">
				<button class="btn default closebtn">Close</button>
				<button class="btn yellow">Save</button>
			</div>
		</div>
	</div>
</div>


<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
		<?php echo $this->Form->create('pull_From_Sales_Order', ['url' => ['action' => 'pull_From_Sales_Order']])?>
			<div class="modal-body">
				<p>
					<label>Select Sales Order No.</label>
					<?php 
					$options=array();
					foreach($salesOrders as $salesOrderdata){
						$options[]=['text' => h(($salesOrderdata->so1.'/SO-'.str_pad($salesOrderdata->id, 3, '0', STR_PAD_LEFT).'/'.$salesOrderdata->so3.'/'.$salesOrderdata->so4)), 'value' => $salesOrderdata->id];
					}
					echo $this->Form->input('sales_order_id', ['empty' => "--Select--",'label' => false,'options' => $options,'class' => 'form-control input-sm select2me']); ?>
				</p>
			</div>
			<div class="modal-footer">
				<button class="btn default" data-dismiss="modal" aria-hidden="true">Close</button>
				<button class="btn blue" type="submit" name="pull_submit">GO</button>
			</div>
		<?= $this->Form->end() ?>
		</div>
	</div>
</div>
<?php } ?>

<?php $ref_types=['New Reference'=>'New Ref','Against Reference'=>'Agst Ref','Advance Reference'=>'Advance']; ?>
<div id="sample_ref" style="display:none;">
	<table width="100%" class="ref_table">
		<tbody>
			<tr>
				<td><?php echo $this->Form->input('ref_types', ['empty'=>'--Select-','options'=>$ref_types,'label' => false,'class' => 'form-control input-sm ref_type']); ?></td>
				<td class="ref_no"></td>
				<td><?php echo $this->Form->input('amount', ['label' => false,'class' => 'form-control input-sm ref_amount_textbox','placeholder'=>'Amount']); ?></td>
				<td width="25%" style="padding-left:0px; vertical-align: top !important;">
				<?php
				echo $this->Form->input('ref_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm  cr_dr_amount','value'=>'Cr','style'=>'vertical-align: top !important;']); ?>
				</td>
				<td><a class="btn btn-xs btn-default deleterefrow" href="#" role="button"><i class="fa fa-times"></i></a></td>
			</tr>
		</tbody>
	</table>
</div>