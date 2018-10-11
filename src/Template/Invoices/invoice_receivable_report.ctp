<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Invoice Receivable Report</span>
		</div>
		<div class="actions">
		
		</div>
	</div>	
	
	<div class="portlet-body form">
		<form method="GET" >
			<table class="table table-condensed">
				<tbody>
					<tr>
						<td width="20%">
							<input type="text" name="cust_name" class="form-control input-sm" placeholder="Customer Name" value="<?php echo @$cust_name; ?>" >
						</td>
						<td width="20%">
							<?php echo $this->Form->input('salesman', ['empty'=>'--SalesMans--','options' => $SalesMans,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'SalesMan Name','value'=> h(@$salesman) ]); ?>
						</td>
						<td width="20%">
							<?php if(!empty($From)){ ?>
								<input type="text" name="From" class="form-control input-sm date-picker" placeholder="Transaction From" value="<?php echo date('d-m-Y',strtotime($From)); ?>"  data-date-format="dd-mm-yyyy" >
							<?php }else{ ?>
								<input type="text" name="From" class="form-control input-sm date-picker" placeholder="Transaction From" value="<?php echo @$From; ?>"  data-date-format="dd-mm-yyyy" >
							<?php } ?>
							
						</td>
						<td width="20%">
						<?php if(!empty($To)){ ?>
							<input type="text" name="To" class="form-control input-sm date-picker" placeholder="Transaction To" value="<?php echo date('d-m-Y',strtotime(@$To)); ?>"  data-date-format="dd-mm-yyyy" >
						<?php }else{ ?>	
							<input type="text" name="TO" class="form-control input-sm date-picker" placeholder="Transaction TO" value="<?php echo @$TO; ?>"  data-date-format="dd-mm-yyyy" >
						<?php } ?>
						</td>
						<!--<td>
							<select class="form-control input-sm select2me" name="overdue_filter">
								<option value="">Overdue</option>
								<option value="15">1-30</option>
								<option value="">31-45</option>
								<option value="">46-60</option>
								<option value="">61-75</option>
							</select>
						</td>-->
						<td>
							<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		<!-- BEGIN FORM-->
		
		
		
		<div class="table-scrollable">
		<?php $page_no=$this->Paginator->current('Customers'); $page_no=($page_no-1)*20; ?>
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th  style="text-align:left;">Sr.No.</th>
						<th style="text-align:left;">Customer Name</th>
						<th style="text-align:center;">Invoice No</th>
						<th style="text-align:center;">Invoice Date</th>
						<th style="text-align:center;">Payment Terms</th>
						<th style="text-align:center;">Overdue Days</th>
						<th style="text-align:right;">Amount</th>
						<th style="text-align:center;">Reciept No</th>
						<th style="text-align:center;">Reciept Date</th>
						<th style="text-align:center;">Reciept Amount</th>
					</tr>
				</thead>
				<tbody><?php  ?>
				<?php $i=1; $refSize=0; $dataArray=[]; foreach ($Invoices as $invoice):
				
				$refSize=(sizeof($Receiptdatas[$invoice->id])); 
				$invoice_id = $EncryptingDecrypting->encryptData($invoice->id);
				
				if($refSize > 0){
					
				?>
					<tr>
						<td  style="text-align:center;" rowspan="<?php echo $refSize; ?>"><?php echo $i++; ?></td>
						<td  style="text-align:left;" rowspan="<?php echo $refSize; ?>"><?php if(!empty($invoice->customer->alias)){
										echo $invoice->customer->customer_name.'('.$invoice->customer->alias.')';
									}else{
										echo $invoice->customer->customer_name;
									} ?></td>
						<td  style="text-align:center;" rowspan="<?php echo $refSize; ?>">
						<?php
						if(in_array(27,$allowed_pages)){
							if($invoice->invoice_type=="GST"){
								echo $this->Html->link($invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4 ,['controller'=>'Invoices','action' => 'gst-confirm',$invoice_id],array('target'=>'_blank'));
							}else{
								echo $this->Html->link($invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4 ,['controller'=>'Invoices','action' => 'confirm',$invoice_id],array('target'=>'_blank'));
							}
						}else{
							echo $invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4;
						}
						?></td>
						<td  style="text-align:center;" rowspan="<?php echo $refSize; ?>"><?php echo date("d-m-Y",strtotime($invoice->date_created)); ?></td>
						<td  style="text-align:center;" rowspan="<?php echo $refSize; ?>"><?php echo $invoice->customer->payment_terms ?>Days</td>
						<?php
										
										$due_date=date('Y-m-d', strtotime(@$invoice->date_created. ' +'. $invoice->customer->payment_terms .'days'));
										
										$RTD=date('Y-m-d',strtotime($Receiptdatas[$invoice->id][0]->transaction_date));
										$datediff = strtotime($RTD) - strtotime($due_date);
											$Diff=floor($datediff / (60 * 60 * 24));
											//$due_day=strtotime($due_date)-strtotime($RTD);
											//$due_day = date('d-m-Y',strtotime($due_day.'days'));
											?>
						<?php 
							/* $RTD=date('Y-m-d',strtotime(($Receiptdatas[$invoice->id][0]->transaction_date)));
							$CD=date('Y-m-d',strtotime(($invoice->date_created)));
							//pr($CD);
							//pr($RTD); exit;
							$datediff = strtotime($RTD) - strtotime($invoice->date_created);
							$Diff=floor($datediff / (60 * 60 * 24));  */
						?>
						<?php if($Diff > 0){ ?>
							<td  style="text-align:center; color:red;" rowspan="<?php echo $refSize; ?>"><?php echo $Diff; ?> Days</td>
						<?php }else{ ?>
							<td style="text-align:center;">0 Days</td>
						<?php } ?>	
						<td  style="text-align:right;" rowspan="<?php echo $refSize; ?>"><?php echo $this->Number->format($invoice->grand_total,['places'=>2]); ?></td>
						<?php 
							$jk=0; 
								
								
								foreach($Receiptdatas[$invoice->id] as $data){
									@$dataArray[$invoice->id] = @$recieptArray[$data->id];
									if($jk > 0)
									{
										?>
										<tr>
										<?php
									}
									$jk++;
									?>
									
									<td style="text-align:center;">
									<?php 
									$reciept_id = $EncryptingDecrypting->encryptData($data->receipt->id);
									if(in_array(96,$allowed_pages)){
										echo $this->Html->link('#'.str_pad($data->receipt->voucher_no, 4, '0', STR_PAD_LEFT) ,['controller'=>'receipts','action' => 'view',$reciept_id],array('target'=>'_blank'));
									}else{
										echo '#'.str_pad($data->receipt->voucher_no, 4, '0', STR_PAD_LEFT);
									}
									?>
									</td>
									<td  style="text-align:center;"><?php echo date("d-m-Y",strtotime($data->receipt->transaction_date)); ?></td>
									<?php $dr = $data->debit; $cr = $data->credit; ?>
									<td style="text-align:right;"><?php if($dr > 0){ echo $dr; }else if($cr > 0){ echo $cr;} ?></td>
									</tr>
								 <?php } ?>
								   <?php } else{?>
									<td  style="text-align:center;"><?php echo $i++; ?></td>
									<td  style="text-align:left;"><?php if(!empty($invoice->customer->alias)){
										echo $invoice->customer->customer_name.'('.$invoice->customer->alias.')';
									}else{
										echo $invoice->customer->customer_name;
									} ?></td>
									<td  style="text-align:center;">
										<?php
											if(in_array(27,$allowed_pages)){
												if($invoice->invoice_type=="GST"){
													echo $this->Html->link($invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4 ,['controller'=>'Invoices','action' => 'gst-confirm',$invoice_id],array('target'=>'_blank'));
												}else{
													echo $this->Html->link($invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4 ,['controller'=>'Invoices','action' => 'confirm',$invoice_id],array('target'=>'_blank'));
												}
											}else{
												echo $invoice->in1.'/IN-'.str_pad($invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice->in3.'/'.$invoice->in4;
											}
										
										?>
									</td>
									
									<td  style="text-align:center;"><?php echo date("d-m-Y",strtotime($invoice->date_created)); ?></td>
									<td  style="text-align:center;"><?php echo $invoice->customer->payment_terms ?> Days</td> 
									<td  style="text-align:center;">
									<?php
										
											echo "-";
										
										
									?>
									</td> 
									<td  style="text-align:right;"><?php echo $this->Number->format($invoice->total,['places'=>2]); ?></td> 
									<td  style="text-align:center;">-</td> 
									<td  style="text-align:center;">-</td> 
									<td  style="text-align:center;">-</td> 
								   <?php }?>
					</tr>
				<?php  endforeach; ?>
				
				</tbody>
			</table>
			</div>
		
</div>
</div>
