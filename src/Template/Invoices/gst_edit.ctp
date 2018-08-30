<?PHP //EXIT; ?>
<style>
table > thead > tr > th, table > tbody > tr > th, table > tfoot > tr > th, table > thead > tr > td, table > tbody > tr > td, table > tfoot > tr > td{
	vertical-align: top !important;
	border-bottom:solid 1px #CCC;
}
.help-block-error{
	font-size: 10px;
}
.row_textbox{
	width: 100px;
}
.check_text{
	font-size:9px;
}
.add_check_text{
	font-size:9px;
}	
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
	vertical-align: top !important;
}
#main_tb thead th {
	font-size:10px;
}
</style>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Update Invoice (GST)</span>
		</div>
	</div>
	<div class="form-actions">
	</div>
	
	<div class="portlet-body form">
		<?= $this->Form->create($invoice,['id'=>'form_sample_3']) ?>
		<div class="form-body">
				<div class="row">
					
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Sales Account<span class="required" aria-required="true">*</span></label>
							<div class="row">
								<div class="col-md-12">
									<?php echo $this->Form->input('sales_ledger_account', ['label' => false,'options' => $ledger_account_details,'class' => 'form-control input-sm select2me','required']); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
					<?php //if($invoice->e_way_bill_no){ ?>
							<label class="control-label">E-Way Bill No</label>
							<div class="row">
								<div class="col-md-12">
									<?php echo $this->Form->input('e_way_bill_no', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder'=>'E-Way Bill No']); ?>
								</div>
							</div>
							<?php //} ?>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							
										
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Customer <span class="required" aria-required="true">*</span></label>
							<div class="row">
								<div class="col-md-12">
									<?php echo $this->Form->input('customer_id', ['type'=>'hidden','value' => @$invoice->customer_id]); ?>
									<?php echo $invoice->customer->customer_name.'('; echo $invoice->customer->alias.')'; ?>	
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Invoice No</label>
							<div class="row">
								<div class="col-md-4">
									<?php echo $this->Form->input('in1', ['label' => false,'class' => 'form-control input-sm','readonly','value'=>$invoice->in1]); ?>
								</div>
								<div class="col-md-4">
									<?php echo $this->Form->input('in3', ['label' => false,'class' => 'form-control input-sm','readonly','value'=>$invoice->in3]); ?>
								</div>
								<div class="col-md-4">
									<?php echo $this->Form->input('in4', ['label' => false,'value'=>substr($s_year_from, -2).'-'.substr($s_year_to, -2),'class' => 'form-control input-sm','readonly']); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Address <span class="required" aria-required="true">*</span></label>
							<div class="row">
								<div class="col-md-12">
									<?php echo $this->Form->input('customer_address', ['label' => false,'class' => 'form-control','placeholder' => 'Address','value' => @$sales_order->customer_address]); ?>
									<a href="#" role="button" class="pull-right select_address" >
									Select Address </a>	
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">LR No</label>
							<div class="row">
								<div class="col-md-12">
									<?php echo $this->Form->input('lr_no', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder'=>'LR No']); ?>
								</div>
							</div><br/>
							<label class="control-label">Carrier</label>
							<div class="row">
								<div class="col-md-12">
									<?php echo $this->Form->input('transporter_id', ['empty' => "--Select--",'label' => false,'options' => $transporters,'class' => 'form-control input-sm select2me','value' => @$sales_order->transporter_id]); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Delivery Description <span class="required" aria-required="true">*</span></label>
							<div class="row">
								<div class="col-md-12">
									<?php echo $this->Form->input('delivery_description', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Delivery Description','value'=>$invoice->delivery_description]); ?>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Customer PO NO  </label>
							<div class="row">
								<div class="col-md-12">
									<?php echo $invoice->sales_order->customer_po_no; ?>
									<?php echo $this->Form->input('customer_po_no', ['type'=>'hidden','label' => false,'class' => 'form-control input-sm','placeholder'=>'Delivery Description','value'=>$invoice->sales_order->customer_po_no]); ?>
								</div>
							</div><br/>
							<label class="control-label">PO DATE</label>
							<div class="row">
								<div class="col-md-12">
									<?php echo @date("d-m-Y",strtotime($invoice->po_date)); ?>
									
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<div class="row">
				<?php if($invoice->road_permit_required=='Yes') {?>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Road Permit No <span class="required" aria-required="true">*</span></label>
							<div class="row">
								<div class="col-md-12">
									<?php echo $this->Form->input('form47', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Form 47','required']); ?>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
					<?php if($invoice->road_permit_required=='Yes') {?>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Form 49 <span class="required" aria-required="true">*</span></label>
							<div class="row">
								<div class="col-md-12">
									<?php echo $this->Form->input('form49', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Form 47','required']); ?>
									
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
	<?php 
			$gst_hide="style:display:;";
			$igst_hide="style:display:;" ;
			$tr2_colspan=15;
			$tr3_colspan=10; 
			$tr4_colspan=7; ?>
	<?php if($invoice->customer->district->state_id!="8"){
			$gst_hide="display:none;" ;
			$tr2_colspan=12;
			$tr3_colspan=8; 
			$tr4_colspan=5;
	}else{
			$tr2_colspan=14;
			$tr3_colspan=8; 
			$tr4_colspan=5;
			$igst_hide="display:none;" ;
	}?>
			
	<div style="overflow: auto;">
		<input type="text"  name="checked_row_length" id="checked_row_length" style="height: 0px;padding: 0;border: none;" value=""/>
			<table  class="table tableitm" id="main_tb" border="1">
				<thead>
						<tr >
							<th rowspan="2" style="text-align: bottom;" width="50px">Sr.No. </th>
							<th rowspan="2" style="white-space: nowrap; width:50%;">Items</th>
							<th rowspan="2"  style="width:20%;">Quantity</th>
							<th rowspan="2"  style="width:20%;">Rate</th>
							<th rowspan="2"  style="width:20%;">Amount</th>
							<th style="text-align: center;" colspan="2" width="50%">Discount</th>
							<th style="text-align: center;" colspan="2" width="50%">P&F </th>
							<th rowspan="2"  width="100px">Taxable Value</th>
							<th style="<?php echo $gst_hide; ?>text-align: center;" colspan="2" width="50%">CGST</th>
							<th style="<?php echo $gst_hide; ?>text-align: center;" colspan="2" width="20%">SGST</th>
							<th style="<?php echo $igst_hide; ?>text-align: center;" colspan="2" width="20%">IGST</th>
							
							<th rowspan="2" width="100px">Total</th>
							<th rowspan="2" width="100px"></th>
						</tr>
						<tr> <th style="text-align: center;" width="150px">%</th>
							<th style="text-align: center;" width="150px">Amt</th>
							<th style="text-align: center;" width="150px">%</th>
							<th style="text-align: center;" width="150px">Amt</th>
							<th style="<?php echo $gst_hide; ?>text-align: center;" width="150px">%</th>
							<th style="<?php echo $gst_hide; ?>text-align: center;" width="150px">Amt</th>
							<th style="<?php echo $gst_hide; ?>text-align: center;" width="150px">%</th>
							<th style="<?php echo $gst_hide; ?>text-align: center;" width="150px">Amt</th>
							<th style="<?php echo $igst_hide; ?>text-align: center;" width="150px">%</th>
							<th style="<?php echo $igst_hide; ?>text-align: center;" width="150px">Amt</th>
						</tr>
						
				</thead>
				<tbody>
				<?php 
					$cgst_options=array();
					$sgst_options=array();
					$igst_options=array();
					foreach($GstTaxes as $GstTaxe){
						if($GstTaxe->cgst=="Yes"){
							$merge_cgst=$GstTaxe->tax_figure.' ('.$GstTaxe->invoice_description.')';
							$cgst_options[]=['text' =>$merge_cgst, 'value' => $GstTaxe->id,'percentage'=>$GstTaxe->tax_figure];
						}else if($GstTaxe->sgst=="Yes"){
							$merge_sgst=$GstTaxe->tax_figure.' ('.$GstTaxe->invoice_description.')';
							$sgst_options[]=['text' =>$merge_sgst, 'value' => $GstTaxe->id,'percentage'=>$GstTaxe->tax_figure];
						}else if($GstTaxe->igst=="Yes"){
							$merge_igst=$GstTaxe->tax_figure.' ('.$GstTaxe->invoice_description.')';
							$igst_options[]=['text' =>$merge_igst, 'value' => $GstTaxe->id,'percentage'=>$GstTaxe->tax_figure];
						}
						
					}
						
					$current_discount=[];
					$current_pnf=[];
					$current_cgst=[];
					$current_sgst=[];
					$current_igst=[];
					$descriptions=[];
					$sr_nos=[];
					foreach($invoice->invoice_rows as $current_invoice_row){
						$current_rows[]=$current_invoice_row->sales_order_row_id;
						$current_row_items[$current_invoice_row->sales_order_row_id]=$current_invoice_row->quantity;
						$descriptions[$current_invoice_row->sales_order_row_id]=$current_invoice_row->description;
						$current_discount[$current_invoice_row->sales_order_row_id]=$current_invoice_row->discount_percentage;
						$current_pnf[$current_invoice_row->sales_order_row_id]=$current_invoice_row->pnf_percentage;
						$current_cgst[$current_invoice_row->sales_order_row_id]=$current_invoice_row->cgst_percentage;
						$current_sgst[$current_invoice_row->sales_order_row_id]=$current_invoice_row->sgst_percentage;
						$current_igst[$current_invoice_row->sales_order_row_id]=$current_invoice_row->igst_percentage;
						$descriptions[$current_invoice_row->sales_order_row_id]=$current_invoice_row->description;
						$sr_nos=$current_invoice_row->serial_number;
						$invoice_sales_return[$current_invoice_row->sales_order_row_id]=$current_invoice_row->id;
					}
					
					$q=0; 
					

					foreach ($invoice->sales_order->sales_order_rows as $sales_order_row){ 
						 if($sales_order_qty[$sales_order_row->id]-@$existing_invoice_rows[$sales_order_row->id]+@$current_invoice_rows[$sales_order_row->id] > 0){
						 $in_row_id=@$invoice_sales_return[@$sales_order_row->id];
//pr($sr_qty[$in_row_id]); 
						 ?>

						<tr class="tr1" row_no="<?= h($q) ?>">
							<td rowspan="2">
								<?php echo ++$q; --$q; ?>
								<?php echo $this->Form->input('q', ['label' => false,'type' => 'hidden','value' => @$invoice_row_id[@$sales_order_row->id],'readonly','class'=>'invoiceid']); ?>
								<?php echo $this->Form->input('q', ['label' => false,'type' => 'hidden','value' => @$sales_order_row->id,'readonly','class'=>'hiddenid']); ?>
							</td>
							<td>
								<?php 
								echo $this->Form->input('q', ['type'=>'hidden','value'=>$sales_order_row->item_id,'class'=>'item_ids']);
								
								echo $this->Form->input('item_id', ['type'=>'hidden','value'=>$sales_order_row->item->item_companies[0]->serial_number_enable,'class'=>'serial_nos']); 
								echo $this->Form->input('customer_item_code[]', ['type' => 'hidden','label' => false,'class' => 'form-control input-sm customer_item_code','placeholder' => 'Item Code','value' => @$sales_order_row->customer_item_code]);
								echo $sales_order_row->item->name;
								?>
							</td>
							<td>
								<?php  
								echo $this->Form->input('q', ['type' => 'text','label' => false,'class' => 'form-control input-sm quantity row_textbox','placeholder' => 'Quantity','id'=>'quantity','value' => @$current_invoice_rows[$sales_order_row->id],'max'=>@$sales_order_qty[$sales_order_row->id]-@$existing_invoice_rows[$sales_order_row->id]+@$current_invoice_rows[$sales_order_row->id],'min'=>@$sr_qty[$in_row_id]]); 
								?>
								<span>Max: <?= h(@$sales_order_qty[$sales_order_row->id]-@$existing_invoice_rows[$sales_order_row->id]+@$current_invoice_rows[$sales_order_row->id]) ?></span>
							</td>
							<td>
								<?php echo $this->Form->input('q', ['type' => 'text','label' => false,'class' => 'form-control input-sm row_textbox','readonly','placeholder' => 'Amount','step'=>0.01,'value'=>$sales_order_row->rate]); ?>
							</td>
							<td><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Amount','readonly','step'=>0.01]); ?></td>
							<td><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm  row_textbox discount_percentage','placeholder'=>'%','value'=>@$current_discount[$sales_order_row->id]]); ?></td>
							<td><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Amount','readonly','step'=>0.01]); ?></td>
							<td><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm row_textbox pnf_percentage','placeholder'=>'%','step'=>0.01,'value'=>@$current_pnf[$sales_order_row->id]]); ?></td>
							<td><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Amount','readonly','step'=>0.01]); ?></td>
							<td><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Taxable Value','readonly','step'=>0.01]); ?></td>
							<td style="<?php echo $gst_hide; ?>"><?php echo $this->Form->input('q', ['label' => false,'empty'=>'Select','options'=>$cgst_options,'class' => 'form-control input-sm  row_textbox cgst_percentage','placeholder'=>'%','step'=>0.01,'value'=>@$current_cgst[$sales_order_row->id]]); ?></td>
							<td style="<?php echo $gst_hide; ?>"><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Amount','readonly','step'=>0.01]); ?></td>
							<td style="<?php echo $gst_hide; ?>"><?php echo $this->Form->input('q', ['label' => false,'empty'=>'Select','options'=>$sgst_options,'class' => 'form-control input-sm ','class' => 'form-control input-sm row_textbox sgst_percentage','placeholder'=>'%','step'=>0.01,'value'=>@$current_sgst[$sales_order_row->id]]); ?></td>
							<td style="<?php echo $gst_hide; ?>"><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Amount','readonly','step'=>0.01]); ?></td>
							<td style="<?php echo $igst_hide; ?>"><?php echo $this->Form->input('q', ['label' => false,'empty'=>'Select','options'=>$igst_options,'class' => 'form-control input-sm ','class' => 'form-control input-sm row_textbox igst_percentage','placeholder'=>'%','step'=>0.01,'value'=>@$current_igst[$sales_order_row->id]]); ?></td>
							<td style="<?php echo $igst_hide; ?>"><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Amount','readonly','step'=>0.01]); ?></td>
							<td><?php echo $this->Form->input('q', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Total','readonly','step'=>0.01]); ?></td>
							<?php?>
							<td><label><?php 
									
								$disable='';
								if(in_array($sales_order_row->id,$current_rows)){

									$check='checked';
								}else{
									$check='';
								}
								 if(@$sr_qty[$in_row_id] > 0) { 
									$disable = 'disabled';
								}else{
									$disable='';
								} //pr(@$in_row_id);
								echo $this->Form->input('q', ['label' => false,'type'=>'checkbox','class'=>'rename_check','value' => @$sales_order_row->id,$check,@$disable]);
								?></label>
							</td>
						<?php  ?>
						</tr>
						<tr class="tr2  secondtr" row_no="<?= h($q) ?>">
							<td colspan="<?php echo $tr2_colspan; ?>">
							<div contenteditable="true" class="note-editable" id="summer<?php echo $q; ?>" ><?php echo @$descriptions[$sales_order_row->id]; ?></div>
							<?php echo $this->Form->input('q', ['label' => false,'type' => 'textarea','class' => 'form-control input-sm  ','placeholder'=>'Description','style'=>['display:none'],'value' => @$descriptions[$sales_order_row->id],'readonly','required']); ?>
							</td>
							<td></td>
						</tr>
						<?php if($sales_order_row->item->item_companies[0]->serial_number_enable==1) { ?>
						<tr class="tr3" row_no="<?= h($q) ?>">
							<td></td>
							<td colspan="<?php echo $tr2_colspan; ?>">
							<label class="control-label">Item Serial Number <span class="required" aria-required="true">*</span></label>
							<?php
							if(!empty($invoice_row_id[@$sales_order_row->id])){
								echo $this->requestAction('/SerialNumbers/getSerialNumberEditList?item_id='.$sales_order_row->item_id.'&in_row_id='. @$invoice_row_id[@$sales_order_row->id].'&invoice_id='. @$cur_invoice_id[@$sales_order_row->id]); 
							}else{
								echo $this->requestAction('/SerialNumbers/getSerialNumberList?item_id='.$sales_order_row->item_id);
							}?>
							</td>
						</tr><?php } ?>
						 <?php  $q++;  }}   ?>

				</tbody>
				<tfoot><?php 
							$cgst_options=array();
							$sgst_options=array();
							$igst_options=array();
							foreach($GstTaxes as $GstTaxe){
								if($GstTaxe->cgst=="Yes"){
									$merge_cgst=$GstTaxe->tax_figure.' ('.$GstTaxe->invoice_description.')';
									$cgst_options[]=['text' =>$merge_cgst, 'value' => $GstTaxe->id,'percentage'=>$GstTaxe->tax_figure];
								}else if($GstTaxe->sgst=="Yes"){
									$merge_sgst=$GstTaxe->tax_figure.' ('.$GstTaxe->invoice_description.')';
									$sgst_options[]=['text' =>$merge_sgst, 'value' => $GstTaxe->id,'percentage'=>$GstTaxe->tax_figure];
								}else if($GstTaxe->igst=="Yes"){
									$merge_igst=$GstTaxe->tax_figure.' ('.$GstTaxe->invoice_description.')';
									$igst_options[]=['text' =>$merge_igst, 'value' => $GstTaxe->id,'percentage'=>$GstTaxe->tax_figure];
								}
								
							}
						?>
					<tr>
						<td align="right" colspan="<?php echo $tr3_colspan; ?>">Fright Ledger Account</td>
						<td align="right" ></td>
						<td><?php echo $this->Form->input('fright_amount', ['type' => 'text','label' => false,'class' => 'form-control input-sm fright_amount','placeholder' => 'Fright Amount','step'=>0.01,'value'=>@$sales_order->fright_amount]); ?></td>
						<td style="<?php echo $gst_hide; ?>"><?php echo $this->Form->input('fright_cgst_percent', ['label' => false,'empty'=>'Select','options'=>$cgst_options,'class' => 'form-control input-sm select2me row_textbox fright_cgst_percent','placeholder'=>'%','step'=>0.01]); ?></td>
						<td style="<?php echo $gst_hide; ?>"><?php echo $this->Form->input('fright_cgst_amount', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Amount','readonly','step'=>0.01]); ?></td>
						<td style="<?php echo $gst_hide; ?>"><?php echo $this->Form->input('fright_sgst_percent', ['label' => false,'empty'=>'Select','options'=>$sgst_options,'class' => 'form-control input-sm row_textbox sgst_percentage select2me fright_sgst_percent','placeholder'=>'%','step'=>0.01]); ?></td>
						<td style="<?php echo $gst_hide; ?>"><?php echo $this->Form->input('fright_sgst_amount', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Amount','readonly','step'=>0.01]); ?></td>
						<td style="<?php echo $igst_hide; ?>"><?php echo $this->Form->input('fright_igst_percent', ['label' => false,'empty'=>'Select','options'=>$igst_options,'class' => 'form-control input-sm row_textbox igst_percentage select2me fright_igst_percent','placeholder'=>'%','step'=>0.01]); ?></td>
						<td style="<?php echo $igst_hide; ?>"><?php echo $this->Form->input('fright_igst_amount', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Amount','readonly','step'=>0.01]); ?></td>
						<td colspan="2"><?php echo $this->Form->input('total_fright_amount', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Total','readonly','step'=>0.01]); ?></td>
						
					</tr>
					<tr>
						<td align="right" colspan="<?php echo $tr4_colspan; ?>"><?php echo $this->Form->input('total_amt', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Amount','readonly','step'=>0.01]); ?></td>
						<td align="right" colspan="2"><?php echo $this->Form->input('total_discount', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Discount','readonly','step'=>0.01]); ?></td>
						<td align="right" colspan="2"><?php echo $this->Form->input('total_pnf', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'P&F','readonly','step'=>0.01]); ?></td>
						<td align="right"><?php echo $this->Form->input('total_taxable_value', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Total','readonly','step'=>0.01]); ?></td>
						<td style="<?php echo $gst_hide; ?>" align="right" colspan="2"><?php echo $this->Form->input('total_cgst', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Total CGST','readonly','step'=>0.01]); ?></td>
						<td style="<?php echo $gst_hide; ?>" align="right" colspan="2"><?php echo $this->Form->input('total_sgst', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Total SGST','readonly','step'=>0.01]); ?></td>
						<td style="<?php echo $igst_hide; ?>" align="right" colspan="2"><?php echo $this->Form->input('total_igst', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Total IGST','readonly','step'=>0.01]); ?></td>
						<td align="left" colspan="2"><?php echo $this->Form->input('all_row_total', ['label' => false,'class' => 'form-control input-sm row_textbox','placeholder'=>'Total','readonly','step'=>0.01]); ?></td>
					</tr>
				</tfoot>
			</table>
			</div>
		<br/>
		<div class="row">
				<div class="col-md-9" align="right"><b>TOTAL</b></div>
				<div class="col-md-3">
				<?php echo $this->Form->input('total', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Grand Total','readonly','step'=>0.01]); ?>
				</div>
		</div><br/>
		<div class="row">
				<div class="col-md-9" align="right"><b>TOTAL CGST</b></div>
				<div class="col-md-3">
				<?php echo $this->Form->input('total_cgst_amount', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Grand Total','readonly','step'=>0.01]); ?>
				</div>
		</div><br/>
		<div class="row">
				<div class="col-md-9" align="right"><b>TOTAL SGST</b></div>
				<div class="col-md-3">
				<?php echo $this->Form->input('total_sgst_amount', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Grand Total','readonly','step'=>0.01]); ?>
				</div>
		</div><br/>
		<div class="row">
				<div class="col-md-9" align="right"><b>TOTAL IGST</b></div>
				<div class="col-md-3">
				<?php echo $this->Form->input('total_igst_amount', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Grand Total','readonly','step'=>0.01]); ?>
				</div>
		</div><br/>
		<div class="row">
				<div class="col-md-9" align="right"><b>FRIGHT AMOUNT</b></div>
				<div class="col-md-3">
				<?php echo $this->Form->input('fright_amt', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Grand Total','readonly','step'=>0.01]); ?>
				</div>
		</div><br/>
		<div class="row">
				<div class="col-md-9" align="right"><b>GRAND TOTAL</b></div>
				<div class="col-md-3">
				<?php echo $this->Form->input('grand_total', ['type' => 'text','label' => false,'class' => 'form-control input-sm','placeholder' => 'Grand Total','readonly','step'=>0.01]); ?>
				</div>
		</div><br/>
		
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
							<?php echo $this->Form->input('credit_limit', ['label' => false,'class' => 'form-control input-md','placeholder'=>'','readonly','value' => @$invoice->customer->credit_limit]); ?><br/>
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
				
				<!--<div class="col-md-4">
					<div class="form-group">
						<label class="col-md-6 control-label">Customer TIN</label>
						<div class="col-md-6" id="due">
							<?php echo $this->Form->input('customer_tin', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'','readonly','value' => @$invoice->customer->tin_no,'required']); ?><br/>
							
						</div>
					</div>
				</div>-->
				
				<div class="col-md-4">
					<div class="form-group">
						<label class="col-md-6 control-label">Customer GST No</label>
						<div class="col-md-6" id="due">
							<?php echo $this->Form->input('customer_gstno', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'','readonly','value' => @$invoice->customer->gst_no,'required']); ?><br/>
							
						</div>
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
							<?php foreach($invoice->reference_details as $reference_detail){
								if($reference_detail->reference_type!='On_account'){
								?>
								<tr>
									<td><?php echo $this->Form->input('ref_types', ['empty'=>'--Select-','options'=>$ref_types,'label' => false,'class' => 'form-control input-sm ref_type','value'=>$reference_detail->reference_type]); ?></td>
									<td class="ref_no">
										<?php 
										if($reference_detail->reference_type=='Against Reference')
										{
											echo $this->requestAction('/ReferenceDetails/listRefEdit?ledger_account_id='.$c_LedgerAccount->id.'&ref_name='.$reference_detail->reference_no);
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
								<td><?php echo $this->Form->input('on_acc_cr_dr', ['label' => false,'class' => 'form-control input-sm on_acc_cr_dr','placeholder'=>'Cr_Dr','readonly']); ?></td>
								<td></td>
							</tr>
							<tr>
								<td colspan="2"><a class="btn btn-xs btn-default addrefrow" href="#" role="button"><i class="fa fa-plus"></i> Add row</a></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tfoot>
					</table>
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
					<?= $this->Form->button(__('EDIT INVOICE'),['class'=>'btn btn-primary ','id'=>'add_submit','type'=>'Submit']) ?>
				<?php } ?>	

				</div>
			</div>
		</div>
		
		<?= $this->Form->end() ?>
	</div>
</div>
<style>
.table thead tr th {
    color: black;
	background-color: #97b8ef;
}
</style>

<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>	


<script>
$(document).ready(function() { 
/////////////////////////////////////////////////////////////////////
	jQuery.validator.addMethod("noSpace", function(value, element) { 
	  return value.indexOf(" ") < 0 && value != ""; 
	}, "No space please and don't leave it empty");
	
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
}, jQuery.format("Please enter a Unique Value."));
	//--------- FORM VALIDATION
	var form3 = $('#form_sample_3');
	var error3 = $('.alert-danger', form3);
	var success3 = $('.alert-success', form3);
	form3.validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block help-block-error', // default input error message class
		focusInvalid: true, // do not focus the last invalid input
		rules: {
			checked_row_length: {
				required: true,
				min : 1,
			},
			company_id:{
				required: true
			},
			date_created : {
				  required: true
			},
			customer_id : {
				  required: true
			},
			in1 : {
				  required: true
			},			
			in3:{
				required: true
			},
			in4:{
				required: true
			},
			customer_address:{
				required: true
			},
			lr_no : {
				  required: true
			},
			customer_po_no  : {
				  required: true
			},
			employee_id: {
				  required: true
			}
		},

		messages: { // custom messages for radio buttons and checkboxes
			checked_row_length: {
				required : "Please select atleast one row.",
				min : "Please select atleast one row."
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
			$("#add_submit").removeAttr("disabled");
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
	
		submitHandler: function (form3) {
			put_code_description();
			check();
			success3.show();
			error3.hide();
			form3[0].submit();
			
		}

	});
	
	$('.quantity').die().live("keyup",function() { 
		var tr_obj=$(this).closest('tr');  
		var item_id=tr_obj.find('td:nth-child(2) input.item_ids').val()
		
		if(item_id > 0){ 
			var serial_number_enable=tr_obj.find('td:nth-child(2) input.serial_nos').val();
			
				if(serial_number_enable == '1'){
					var quantity=tr_obj.find('td:nth-child(3) input.quantity').val();
					 if(quantity.search(/[^0-9]/) != -1)
						{
							alert("Item serial number is enabled !!! Please Enter Only Digits")
							tr_obj.find('td:nth-child(3) input').val("");
						}
					rename_rows(); calculate_total(); do_ref_total();
				}else{
					rename_rows(); calculate_total(); do_ref_total();
			}
		} 
    });
	
	
	$('.discount_percentage').die().live("keyup",function() {
		var qty =$(this).val();
		rename_rows(); calculate_total();
    });
	
	$('#add_submit').die().on("mouseover", function () {
		do_ref_total();	
		put_code_description();
	});
	
	$('.pnf_percentage').die().live("keyup",function() {
		var qty =$(this).val();
		rename_rows(); calculate_total();
    });
	
	/* $("select.cgst_percentage").die().live("change",function(){ 
		rename_rows(); calculate_total();
	}); */
	
	$("select.cgst_percentage").die().live("change",function(){
		
			var select_value = $(this).closest('tr').find('td:nth-child(11) select option:selected').attr('percentage');
			
			
			var sgst_options = $(this).closest('tr').find('td:nth-child(13) select.sgst_percentage option');
			
			$(sgst_options).each(function() {
				var $thisOption = $(this);
				$thisOption.attr("disabled", false);
				var valueToCompare = select_value;
				if($thisOption.attr('percentage') == valueToCompare) { 
					$thisOption.prop("selected", true);
					$thisOption.attr("disabled", false);
				}else{ 
					$thisOption.attr("disabled", "disabled");
				}
			});
			
			
			
		rename_rows(); calculate_total();
	});
	
	$("select.sgst_percentage").die().live("change",function(){ 
		rename_rows(); calculate_total();
	});
	
	$("select.igst_percentage").die().live("change",function(){ 
		rename_rows(); calculate_total();
	});
	

	$("select.fright_cgst_percent").die().live("change",function(){ 
		calculate_fright_amount_total(); calculate_total();
	});
	
	$("select.fright_sgst_percent").die().live("change",function(){ 
		calculate_fright_amount_total(); calculate_total();
	});
	
	$("select.fright_igst_percent").die().live("change",function(){ 
		calculate_fright_amount_total(); calculate_total();
	});
	

	$('.fright_amount').die().live("keyup",function() {
		var qty =$(this).val(); 
		rename_rows(); calculate_fright_amount_total(); calculate_total();  do_ref_total();
    });
	
	$('.total_fright_amount').die().live("keyup",function() {
		calculate_fright_amount_total();  calculate_total();
    });
	
	$('.rename_check').die().live("click",function() { 
		rename_rows();   calculate_total();
		 put_code_description();
    });
	
	
	<?php if($invoice->customer->district->state_id!="8"){ ?>
		$("#main_tb tbody tr.tr1").each(function(){  
			var row_no=$(this).attr('row_no');
			var val=$(this).find('td:nth-child(18) input[type="checkbox"]:checked').val();
			if(val){ 
			
			$(this).find('td:nth-child(11) select').select2().attr("name","invoice_rows["+val+"][cgst_percentage]").rules("add", "required");
			$(this).find('td:nth-child(13) select').select2().attr("name","invoice_rows["+val+"][sgst_percentage]").rules("add", "required");
			$(this).find('td:nth-child(15) select').select2().attr("name","invoice_rows["+val+"][igst_percentage]").rules("remove", "required");
			} 
		});
	<?php } else{ ?> 
			$("#main_tb tbody tr.tr1").each(function(){  
			var row_no=$(this).attr('row_no');
			var val=$(this).find('td:nth-child(18) input[type="checkbox"]:checked').val();
			if(val){ 
			
			$(this).find('td:nth-child(11) select').select2().attr("name","invoice_rows["+val+"][cgst_percentage]").rules("add", "required");
			$(this).find('td:nth-child(13) select').select2().attr("name","invoice_rows["+val+"][sgst_percentage]").rules("add", "required");
			$(this).find('td:nth-child(15) select').select2().attr("name","invoice_rows["+val+"][igst_percentage]").rules("remove", "required");
			} 
		});
			
	<?php } ?>
	
	
	rename_rows();
	
	function rename_rows(){
		var list = new Array();
		var p=0;var i=0;
		$("#main_tb tbody tr.tr1").each(function(){  
			var row_no=$(this).attr('row_no');
			
			var val=$(this).find('td:nth-child(18) input[type="checkbox"]:checked').val();
			if(val){ 
				i++;
				$(this).find('td:nth-child(1) input.hiddenid').attr("name","invoice_rows["+val+"][sales_order_row_id]").attr("id","invoice_rows-"+val+"-sales_order_row_id");
				$(this).find('td:nth-child(1) input.invoiceid').attr("name","invoice_rows["+val+"][id]").attr("id","invoice_rows-"+val+"-id");
				$(this).find('td:nth-child(2) input.item_ids').attr("name","invoice_rows["+val+"][item_id]").attr("id","invoice_rows-"+val+"-item_id").rules("add", "required");
				$(this).find("td:nth-child(2) input.customer_item_code").attr({name:"invoice_rows["+val+"][customer_item_code]", id:"invoice_rows-"+val+"-customer_item_code"});
				$(this).find('td:nth-child(3) input.quantity').removeAttr("readonly").attr("name","invoice_rows["+val+"][quantity]").attr("id","q"+val).attr("id","invoice_rows-"+val+"-quantity").rules("add", "required");
				$(this).find('td:nth-child(4) input').attr("name","invoice_rows["+val+"][rate]").attr("id","q"+val).attr("id","invoice_rows-"+val+"-rate").rules("add", "required");
				$(this).find('td:nth-child(5) input').attr("name","invoice_rows["+val+"][amount]").attr("id","q"+val).attr("id","invoice_rows-"+val+"-amount").rules("add", "required");
				$(this).find('td:nth-child(6) input').attr("name","invoice_rows["+val+"][discount_percentage]").removeAttr("readonly").attr("id","q"+val).attr("id","invoice_rows-"+val+"-discount_percentage");
				$(this).find('td:nth-child(7) input').attr("name","invoice_rows["+val+"][discount_amount]").attr("id","q"+val).attr("id","invoice_rows-"+val+"-discount_amount").rules("add", "required");
				$(this).find('td:nth-child(8) input').attr("name","invoice_rows["+val+"][pnf_percentage]").removeAttr("readonly").attr("id","q"+val).attr("id","invoice_rows-"+val+"-pnf_percentage");
				$(this).find('td:nth-child(9) input').attr("name","invoice_rows["+val+"][pnf_amount]").attr("id","q"+val).attr("id","invoice_rows-"+val+"-pnf_amount").rules("add", "required");
				$(this).find('td:nth-child(10) input').attr("name","invoice_rows["+val+"][taxable_value]").attr("id","q"+val).attr("id","invoice_rows-"+val+"-taxable_value").rules("add", "required");
				$(this).find('td:nth-child(11) select').select2().attr("name","invoice_rows["+val+"][cgst_percentage]").removeAttr("readonly").attr("id","q"+val).attr("id","invoice_rows-"+val+"-cgst_percentage").rules("add", "required");;
				$(this).find('td:nth-child(12) input').attr("name","invoice_rows["+val+"][cgst_amount]").attr("id","q"+val).attr("id","invoice_rows-"+val+"-cgst_amount").rules("add", "required");
				$(this).find('td:nth-child(13) select').select2().attr("name","invoice_rows["+val+"][sgst_percentage]").removeAttr("readonly").attr("id","q"+val).attr("id","invoice_rows-"+val+"-sgst_percentage").rules("add", "required");;
				$(this).find('td:nth-child(14) input').attr("name","invoice_rows["+val+"][sgst_amount]").attr("id","q"+val).attr("id","invoice_rows-"+val+"-sgst_amount").rules("add", "required");
				$(this).find('td:nth-child(15) select').select2().attr("name","invoice_rows["+val+"][igst_percentage]").removeAttr("readonly").attr("id","q"+val).attr("id","invoice_rows-"+val+"-igst_percentage").rules("add", "required");;
				$(this).find('td:nth-child(16) input').attr("name","invoice_rows["+val+"][igst_amount]").attr("id","q"+val).attr("id","invoice_rows-"+val+"-igst_amount").rules("add", "required");
				$(this).find('td:nth-child(17) input').attr("name","invoice_rows["+val+"][row_total]").attr("id","q"+val).attr("id","invoice_rows-"+val+"-row_total").rules("add", "required");
				
				var htm=$('#main_tb tbody tr.tr2[row_no="'+row_no+'"] >td').find('div.note-editable').html();
				$('#main_tb tbody tr.tr2[row_no="'+row_no+'"] td:nth-child(1)').closest('td').html('');
				$('#main_tb tbody tr.tr2[row_no="'+row_no+'"] td:nth-child(1)').append('<div id=summer'+row_no+'>'+htm+'</div>');
				$('#main_tb tbody tr.tr2[row_no="'+row_no+'"] td:nth-child(1)').find('div#summer'+row_no).summernote();
				$('#main_tb tbody tr.tr2[row_no="'+row_no+'"] td:nth-child(1)').append('<textarea name="invoice_rows['+val+'][description]" class="descriptions" style="display:none;"></textarea>');
				$(this).css('background-color','#fffcda');
				
				$('#main_tb tbody tr.tr2[row_no="'+row_no+'"]');
				var OriginalQty=$(this).find('td:nth-child(3) input[type="text"]').val();
				Quantity = OriginalQty.split('.'); qty=Quantity[0];
				var serial_l=$('#main_tb tbody tr.tr3[row_no="'+row_no+'"] td:nth-child(2) select').length;
				if(serial_l>0){
					$('#main_tb tbody tr.tr3[row_no="'+row_no+'"] td:nth-child(2) select').removeAttr("readonly").attr("name","invoice_rows["+val+"][serial_numbers][]").attr("id","invoice_rows-"+val+"-item_serial_no").attr('maxlength',qty).select2().rules('add', {
						    required: true,
							minlength: qty,
							maxlength: qty,
							messages: {
								maxlength: "select serial number equal to quantity.",
								minlength: "select serial number equal to quantity."
							}
					});
				}
				$('#main_tb tbody tr.tr3[row_no="'+row_no+'"]').css('background-color','#fffcda');
				var s_tax=$(this).find('td:nth-child(6)').text();
				$(this).css('background-color','#fffcda');
				$('#main_tb tbody tr.tr2[row_no="'+row_no+'"]').css('background-color','#fffcda');
				p++;
			}			
			else{
				$(this).find('td:nth-child(1) input.invoiceid').attr({ name:"q", readonly:"readonly"});
				$(this).find('td:nth-child(1) input.hiddenid').attr({ name:"q", readonly:"readonly"});
				$(this).find('td:nth-child(2) input.item_ids').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find("td:nth-child(2) input.customer_item_code").attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(3) input.quantity').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(4) input').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(5) input').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(6) input').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(7) input').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(8) input').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(9) input').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(10) input').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(11) select').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(12) input').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(13) select').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(14) input').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(15) select').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(16) input').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).find('td:nth-child(17) input').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(this).css('background-color','#FFF');
				var uncheck=$('#main_tb tbody tr.tr2[row_no="'+row_no+'"]');
				$(uncheck).find('td:nth-child(1) textarea').attr({ name:"q", readonly:"readonly"});
				//$('#main_tb tbody tr.tr2').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
				$(uncheck).css('background-color','#FFF');
				var serial_l=$('#main_tb tbody tr.tr3[row_no="'+row_no+'"] td:nth-child(2) select').length;
					if(serial_l>0){
						$('#main_tb tbody tr.tr3[row_no="'+row_no+'"] select').attr({ name:"q", readonly:"readonly"}).rules( "remove", "required" );
						$('#main_tb tbody tr.tr3[row_no="'+row_no+'"]').css('background-color','#FFF');
					}
				}
				$('input[name="checked_row_length"]').val(i);
			});
		}
	
	$('#update_credit_limit').die().on("click",function() {
		var customer_id=$('input[name="customer_id"]').val();
		$("#update_credit_limit_wait").html('Loading...');
		var url="<?php echo $this->Url->build(['controller'=>'Customers','action'=>'CreditLimit']); ?>";
		url=url+'/'+customer_id,
		$.ajax({
			url: url,
		}).done(function(response) {
			$('input[name="credit_limit"]').val(response);
			$("#update_credit_limit_wait").html('');
			$('input[name="new_due_payment"]').attr('max',response).rules('add', {
						required: true,
						max: response,
						messages: {
							max: "Credit Limit Exieded ."
						}
					});
		});
    });
	
	$('input[name="temp_limit"]').die().live("keyup",function(){
	var credit_limit=$('input[name="credit_limit"]').val();
	var temp_limit=$('input[name="temp_limit"]').val();
    var sum= parseFloat(temp_limit) + parseFloat(credit_limit);
	$('input[name="new_due_payment"]').attr('max',sum).rules('add', {
						required: true,
						max: sum,
						messages: {
							max: "Credit Limit Exieded ."
						}
					});
	});	
		
	function put_code_description(){ 
		var i=0;
			$("#main_tb tbody tr.tr1").each(function(){ 
				var row_no=$(this).attr('row_no');
				var val=$(this).find('td:nth-child(18) input[type="checkbox"]:checked').val();
				if(val){ 
				var code=$('#main_tb tbody tr.secondtr[row_no="'+row_no+'"]').find('div#summer'+row_no).code();
				$('#main_tb tbody tr.tr2[row_no="'+row_no+'"]').find('td:nth-child(1) textarea').val(code);
				}
			i++; 
		});
		
	}
	
	calculate_fright_amount_total();
	function calculate_fright_amount_total(){ 
		//var t=$(this);
		var fright_cgst_percent=parseFloat($('select[name="fright_cgst_percent"] option:selected').attr("percentage"));
			if(isNaN(fright_cgst_percent)){ 
				 var fright_cgst_amount = 0; 
				$('input[name="fright_cgst_amount"]').val(round(fright_cgst_amount,2));
			}else{ 
				var fright_amount=parseFloat($('input[name="fright_amount"]').val());
				var fright_cgst_amount = (round(fright_amount,2)*round(fright_cgst_percent,3))/100;
				$('input[name="fright_cgst_amount"]').val(round(fright_cgst_amount,2));
			}
		
		var fright_sgst_percent=parseFloat($('select[name="fright_sgst_percent"] option:selected').attr("percentage"));
			if(isNaN(fright_sgst_percent)){ 
				 var fright_sgst_amount = 0; 
				$('input[name="fright_sgst_amount"]').val(round(fright_sgst_amount,2));
			}else{ 
				var fright_amount=parseFloat($('input[name="fright_amount"]').val());
				var fright_sgst_amount = (round(fright_amount,2)*round(fright_sgst_percent,3))/100;
				$('input[name="fright_sgst_amount"]').val(round(fright_sgst_amount,2));
			}
			
		var fright_igst_percent=parseFloat($('select[name="fright_igst_percent"] option:selected').attr("percentage"));
			if(isNaN(fright_igst_percent)){ 
				 var fright_igst_amount = 0; 
				$('input[name="fright_igst_amount"]').val(round(fright_igst_amount,2));
			}else{ 
				var fright_amount=parseFloat($('input[name="fright_amount"]').val());
				var fright_igst_amount = (round(fright_amount,2)*round(fright_igst_percent,3))/100;
				$('input[name="fright_igst_amount"]').val(round(fright_igst_amount,2));
			}
			var total_fright=round(fright_amount,2)+round(fright_cgst_amount,2)+round(fright_igst_amount,2)+round(fright_sgst_amount,2);
			if(isNaN(total_fright)){
				 var total_fright = 0; 
				 $('input[name="total_fright_amount"]').val(round(total_fright,2));
			}else{
				$('input[name="total_fright_amount"]').val(round(total_fright,2));

			}
	}
	
	calculate_total();	
	function calculate_total(){  
		var total=0; var grand_total=0; var total_amt=0; var total_discount=0; var total_pnf=0; var total_taxable_value=0; var total_cgst=0; var total_sgst=0; var total_igst=0;  var total_row_amount=0
		$("#main_tb tbody tr.tr1").each(function(){
			var val=$(this).find('td:nth-child(18) input[type="checkbox"]:checked').val();
			if(val){
				var qty=parseFloat($(this).find("td:nth-child(3) input").val());
				var Rate=parseFloat($(this).find("td:nth-child(4) input").val());
				var Amount=qty*round(Rate,2);
				$(this).find("td:nth-child(5) input").val(round(Amount,2));
				var amount=parseFloat($(this).find("td:nth-child(5) input").val());
				total_amt=total_amt+round(amount,2);
				var discount_percentage=parseFloat($(this).find("td:nth-child(6) input").val());
				if(isNaN(discount_percentage)){ 
					 var discount_amount = 0; 
					$(this).find("td:nth-child(7) input").val(round(discount_amount,2));
				}else{ 
					var amount=parseFloat($(this).find("td:nth-child(5) input").val());
					var discount_amount = (round(amount,2)*round(discount_percentage,3))/100;
					$(this).find("td:nth-child(7) input").val(round(discount_amount,2));
				}
				total_discount=total_discount+discount_amount;
				var pnf_percentage=parseFloat($(this).find("td:nth-child(8) input").val());
				if(isNaN(pnf_percentage)){ 
					 var pnf_amount = 0; 
					$(this).find("td:nth-child(9) input").val(round(pnf_amount,2));
				}else{ 
					var amount=parseFloat($(this).find("td:nth-child(5) input").val());
					var amount_after_dis=round(amount,2)-round(discount_amount,2);
					var pnf_amount = (round(amount_after_dis,2)*round(pnf_percentage,3))/100;
					$(this).find("td:nth-child(9) input").val(round(pnf_amount,2));
				}
				total_pnf=total_pnf+round(pnf_amount,2);
				var amount=parseFloat($(this).find("td:nth-child(5) input").val());
				var discount_amount=parseFloat($(this).find("td:nth-child(7) input").val());
				var pnf_amount=parseFloat($(this).find("td:nth-child(9) input").val());
				var taxable_value=(round(amount,2)-round(discount_amount,2))+round(pnf_amount,2);
				$(this).find("td:nth-child(10) input").val(round(taxable_value,2));
				total_taxable_value=total_taxable_value+round(taxable_value,2);
				var cgst_percentage=parseFloat($(this).find("td:nth-child(11) option:selected").attr("percentage"));
				if(isNaN(cgst_percentage)){ 
					 var cgst_amount = 0; 
					$(this).find("td:nth-child(12) input").val(round(cgst_amount,2));
				}else{ 
					var taxable_value=parseFloat($(this).find("td:nth-child(10) input").val());
					var cgst_amount = (round(taxable_value,2)*round(cgst_percentage,3))/100;
					$(this).find("td:nth-child(12) input").val(round(cgst_amount,2));
				}
				total_cgst=total_cgst+round(cgst_amount,2);
				var sgst_percentage=parseFloat($(this).find("td:nth-child(13) option:selected").attr("percentage"));
				if(isNaN(sgst_percentage)){ 
					 var sgst_amount = 0; 
					$(this).find("td:nth-child(14) input").val(round(sgst_amount,2));
				}else{ 
					var taxable_value=parseFloat($(this).find("td:nth-child(10) input").val());
					var sgst_amount = (round(taxable_value,2)*round(sgst_percentage,2))/100;
					$(this).find("td:nth-child(14) input").val(round(sgst_amount,2));
				}
				total_sgst=total_sgst+round(sgst_amount,2);
				var igst_percentage=parseFloat($(this).find("td:nth-child(15) option:selected").attr("percentage"));
				if(isNaN(igst_percentage)){ 
					 var igst_amount = 0; 
					$(this).find("td:nth-child(16) input").val(round(igst_amount,2));
				}else{ 
					var taxable_value=parseFloat($(this).find("td:nth-child(10) input").val());
					var igst_amount = (round(taxable_value,2)*round(igst_percentage,3))/100;
					$(this).find("td:nth-child(16) input").val(round(igst_amount,2));
				}
				total_igst=total_igst+round(igst_amount,2);
					var taxable_value=parseFloat($(this).find("td:nth-child(10) input").val());
					var cgst_amount=parseFloat($(this).find("td:nth-child(12) input").val());
					var sgst_amount=parseFloat($(this).find("td:nth-child(14) input").val());
					var igst_amount=parseFloat($(this).find("td:nth-child(16) input").val());
					taxable_value=parseFloat(round(taxable_value,2));
					cgst_amount=parseFloat(round(cgst_amount,2));
					sgst_amount=parseFloat(round(sgst_amount,2));
					igst_amount=parseFloat(round(igst_amount,2));
					
					var row_total=taxable_value+cgst_amount+sgst_amount+igst_amount;
					row_total=parseFloat(round(row_total,2));
					total_row_amount=total_row_amount+row_total;
					$(this).find("td:nth-child(17) input").val(round(row_total,2));
					grand_total=grand_total+row_total;
			}
			
			var fcgst=parseFloat($('input[name="fright_cgst_amount"]').val());
			if(isNaN(fcgst)){ var fcgst = 0;  }
			
			var fsgst=parseFloat($('input[name="fright_sgst_amount"]').val());
			if(isNaN(fsgst)){ var fsgst = 0;  }
			
			var figst=parseFloat($('input[name="fright_igst_amount"]').val());
			if(isNaN(figst)){ var figst = 0;  }
			
			var fright_amount=parseFloat($('input[name="fright_amount"]').val());
			if(isNaN(fright_amount)){ var fright_amount = 0;  }
			
			var total_fright_amount=parseFloat($('input[name="total_fright_amount"]').val());
			if(isNaN(total_fright_amount)){ var total_fright_amount = 0;  }
			total_tax=total_taxable_value+fright_amount;
			
			$('input[name="total_amt"]').val(round(total_amt,2));
			$('input[name="total_discount"]').val(round(total_discount,2));
			$('input[name="total_pnf"]').val(round(total_pnf,2));
			$('input[name="total_taxable_value"]').val(round(total_tax,2));
			
			total_row_amount=parseFloat(round(total_row_amount,2));
			total_cgst=parseFloat(round(total_cgst,2));
			total_sgst=parseFloat(round(total_sgst,2));
			total_igst=parseFloat(round(total_igst,2));
			total_fright_amount=parseFloat(round(total_fright_amount,2));
			total_debit=total_row_amount+total_fright_amount;
			total_cgst_amt=total_cgst+fcgst;
			total_sgst_amt=total_sgst+fsgst;
			total_igst_amt=total_igst+figst;
			$('input[name="fright_amt"]').val(round(fright_amount,2));
			$('input[name="total_cgst"]').val(round(total_cgst_amt,2));
			$('input[name="total_sgst"]').val(round(total_sgst_amt,2));
			$('input[name="total_igst"]').val(round(total_igst_amt,2));
			$('input[name="all_row_total"]').val(round(total_debit,2));
			
			var all_row_total=parseFloat($('input[name="all_row_total"]').val());
					
			total_taxable_value1=parseFloat(round(total_taxable_value,2));
			total_cgst_amt1=parseFloat(round(total_cgst_amt,2));
			total_sgst_amt1=parseFloat(round(total_sgst_amt,2));
			total_igst_amt1=parseFloat(round(total_igst_amt,2));
			fright_amount1=parseFloat(round(fright_amount,2));
					
					
			grand_total=total_taxable_value1+total_cgst_amt1+total_sgst_amt1+total_igst_amt1+fright_amount1;
			$('input[name="total"]').val(round(total_taxable_value,2));
			$('input[name="total_cgst_amount"]').val(round(total_cgst_amt,2));
			$('input[name="total_igst_amount"]').val(round(total_igst_amt,2));
			$('input[name="total_sgst_amount"]').val(round(total_sgst_amt,2));
			$('input[name="grand_total"]').val(round(grand_total,2));
			var old_due_payment1=parseFloat($('input[name="old_due_payment"]').val());
			var	new_due_payment=grand_total+old_due_payment1;
		    $('input[name="new_due_payment"]').val(round(new_due_payment,2));
		});
		do_ref_total();
	}
	
	
	$('.addrefrow').die().live("click",function() {
		add_ref_row();
	});
	
	function add_ref_row(){
		var tr=$("#sample_ref table.ref_table tbody tr").clone();
		$("table.main_ref_table tbody").append(tr);
		rename_ref_rows();
	}
	
	$('.ref_type').die().live("change",function() {
		var current_obj=$(this);
		
		var ref_type=$(this).find('option:selected').val();
		if(ref_type=="Against Reference"){
			var url="<?php echo $this->Url->build(['controller'=>'ReferenceDetails','action'=>'listRef']); ?>";
			url=url,
			$.ajax({
				url: url+'/<?php echo $c_LedgerAccount->id; ?>',
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
	rename_ref_rows();
	function rename_ref_rows(){
		var i=0;
		$("table.main_ref_table tbody tr").each(function(){
			$(this).find("td:nth-child(1) select").attr({name:"ref_rows["+i+"][ref_type]", id:"ref_rows-"+i+"-ref_type"});
			var is_select=$(this).find("td:nth-child(2) select").length;
			var is_input=$(this).find("td:nth-child(2) input").length;
			
			if(is_select){
				$(this).find("td:nth-child(2) select").attr({name:"ref_rows["+i+"][ref_no]", id:"ref_rows-"+i+"-ref_no"}).rules("add", "required");
			}else if(is_input){ 
				
				$(this).find("td:nth-child(2) input").attr({name:"ref_rows["+i+"][ref_no]", id:"ref_rows-"+i+"-ref_no", class:"form-control input-sm ref_number"}).rules('add', {
							required: true
						});
			}
			
			$(this).find("td:nth-child(3) input").attr({name:"ref_rows["+i+"][ref_amount]", id:"ref_rows-"+i+"-ref_amount"}).rules( "add", "required" );
			$(this).find("td:nth-child(4) select").attr({name:"ref_rows["+i+"][ref_cr_dr]", id:"ref_rows-"+i+"-ref_cr_dr"}).rules("add", "required");
			i++;
		});
		
		/* var is_tot_input=$("table.main_ref_table tfoot tr:eq(1) td:eq(1) input").length;
		if(is_tot_input){
			$("table.main_ref_table tfoot tr:eq(1) td:eq(1) input").attr({name:"ref_rows_total", id:"ref_rows_total"}).rules('add', { equalTo: "#grand-total" });
		} */
	}
	
	$('.deleterefrow').live("click",function() {
		$(this).closest("tr").remove();
		do_ref_total();
	});
	
	
	
	$('.ref_list').live("change",function() {
		var current_obj=$(this);
		var due_amount=$(this).find('option:selected').attr('amt');
		$(this).closest('tr').find('td:eq(2) input').val(round(due_amount,2));
		do_ref_total();
	});
	
	$('.ref_amount_textbox').live("keyup",function() {
		do_ref_total(); 
	});
	$('.cr_dr_amount').live("change",function() {
		do_ref_total();
	});
	do_ref_total();
	function do_ref_total(){
		var main_amount=parseFloat($('input[name="grand_total"]').val());
		
		if(!main_amount){ main_amount=0; }
		
		var total_ref=0;
		var total_ref_cr=0;
		var total_ref_dr=0;
		$("table.main_ref_table tbody tr").each(function(){
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
		var on_acc_cr_dr='Dr';
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
			$("table.main_ref_table tfoot tr:nth-child(1) td:nth-child(3) input").val(round(on_acc,2));
			$("table.main_ref_table tfoot tr:nth-child(1) td:nth-child(4) input").val(on_acc_cr_dr);
		}else{
			on_acc=Math.abs(on_acc);
			$("table.main_ref_table tfoot tr:nth-child(1) td:nth-child(3) input").val(round(on_acc,2));
			$("table.main_ref_table tfoot tr:nth-child(1) td:nth-child(4) input").val('Cr');
		}
	}
});
</script>

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
				echo $this->Form->input('ref_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm  cr_dr_amount','value'=>'Dr','style'=>'vertical-align: top !important;']); ?>
				</td>
				<td><a class="btn btn-xs btn-default deleterefrow" href="#" role="button"><i class="fa fa-times"></i></a></td>
			</tr>
		</tbody>
	</table>
</div>