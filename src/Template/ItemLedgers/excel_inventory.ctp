<?php 

	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="Inventory_Daily_report_".$date.'_'.$time;

	header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/vnd.ms-excel");
	header ("Content-Disposition: attachment; filename=".$filename.".xls");
	header ("Content-Description: Generated Report" );

?>
	<table border="1">
		<thead>
			<tr>
				<td colspan="5" align="center">
					 Inventory Daily Report
					<?php if(!empty($from_date) || !empty($to_date)){ echo date('d-m-Y',strtotime($from_date)); ?> TO <?php echo date('d-m-Y',strtotime($to_date));} ?>
				</td>
		    </tr>
			<tr>
				<th width="2%">SR</th>
				<th width="5%">Transaction Date</th>
				<th width="7%">Voucher</th>
				<th width="1%">In/Out</th>
				<th width="10%">Item</th>
			</tr>
		</thead>
			<tbody>
		<?php $srn=0; 
			
			foreach($AllDatas as $key=>$AllData1){ 
				foreach($AllData1  as $key1=>$AllData){ //pr($key1);
					
					$i=0;
					$flag=0; 
					$date="";
					$voucher="";
					$location="";
					$in_out="";
					
					foreach($AllData as $key2=>$itemData) { 
					$row_count=count($itemData->invoice_rows); 
					if($key1=='Invoice'){
						$date=$itemData['date_created'];
						@$voucher=($itemData->in1.'/IN-'.str_pad($itemData->in2, 3, '0', STR_PAD_LEFT).'/'.$itemData->in3.'/'.$itemData->in4);
						if($itemData['invoice_type']=="GST"){
							$location='/Invoices/gst-confirm/'.$itemData->id;
						}else{
							$location='/Invoices/confirm/'.$itemData->id;
						}
						$in_out="Out";
						$voucher_rows=$itemData->invoice_rows;
						
					}
					
					if($key1=='SaleReturns'){
						$date=$itemData['date_created'];
						@$voucher=($itemData->sr1.'/IN-'.str_pad($itemData->sr2, 3, '0', STR_PAD_LEFT).'/'.$itemData->sr3.'/'.$itemData->sr4);
						if($itemData['sale_return_type']=="GST"){
							$location='/sale-returns/gst-sales-edit/'.$itemData->id;
						}else{
							$location='/sale-returns/edit/'.$itemData->id;
						}
						$in_out="In";
						$voucher_rows=$itemData->sale_return_rows;
						
					}
					if($key1=='Grns'){
						$date=$itemData['date_created'];
						@$voucher=($itemData->grn1.'/GRN-'.str_pad($itemData->grn2, 3, '0', STR_PAD_LEFT).'/'.$itemData->grn3.'/'.$itemData->grn4);;
						$location='/Grns/View/'.$itemData->id;
						$in_out="In";
						$voucher_rows=$itemData->grn_rows;
						//pr($i_rows); exit;
					}
					if($key1=='InventoryTransferVouchers'){
						$date=$itemData['transaction_date']; //pr($itemData); exit;
						 if($itemData['in_out']=='in_out')
					    { 
							$voucher=('ITV-'.str_pad($itemData->voucher_no, 4, '0', STR_PAD_LEFT));
							$location='/InventoryTransferVouchers/View/'.$itemData->id;
							
						}
						else if($itemData['in_out']=='in') 
						{ 
							$voucher=('ITV-'.str_pad($itemData->voucher_no, 4, '0', STR_PAD_LEFT));
							$location='/InventoryTransferVouchers/inView/'.$itemData->id;
							
						}else {
							$voucher=('ITV-'.str_pad($itemData->voucher_no, 4, '0', STR_PAD_LEFT));
							$location='/InventoryTransferVouchers/outView/'.$itemData->id;
							
						} 
						$voucher_rows=$itemData->inventory_transfer_voucher_rows;
						//pr($i_rows); exit;
					}
					$IVRs=[];
					$IVRI=[];
					$IVSr=[];
					
					if($key1=='InventoryVouchers')
					{  
						$date=$itemData['transaction_date'];
						@$voucher=('#'.str_pad($itemData->voucher_no, 4, '0', STR_PAD_LEFT));
						$location='/InventoryVouchers/View/'.$itemData->id; //pr($itemData);
						foreach($itemData->iv_rows as $iv_row)
						{ 
							$IVRs[$iv_row->id]=['item_name'=>$iv_row->item->name,'item_qty'=>$iv_row->quantity,'status'=>'In'];
							$iv_id=$iv_row->id;
							foreach($iv_row->iv_row_items as $iv_row_item){ //pr($itemData); 
								$IVRI[$iv_row->id][$iv_row_item->id]=['item_name'=>$iv_row_item->item->name,'item_qty'=>$iv_row_item->quantity,'status'=>'Out'];
								foreach($iv_row_item->serial_numbers as $serial_number){ 
								$IVSr[$iv_row_item->id][$serial_number->id]=$serial_number;
								}
							}
								$voucher_rows=$itemData->iv_rows;
						}
					}
					if($key1=='PurchaseReturns'){
						$date=$itemData['created_on'];
						@$voucher=('#'.str_pad($itemData->voucher_no, 4, '0', STR_PAD_LEFT));
						
						if($itemData['gst_type']=="Gst"){
							$location='/PurchaseReturns/gst-view/'.$itemData->id;
						}else{
							$location='/PurchaseReturns/View/'.$itemData->id;
						}
						
						$in_out="Out";
						$voucher_rows=$itemData->purchase_return_rows;
						//pr($itemData); exit;
					}
					
						
					?>
					<?php if($flag==0){ ?>
						<tr>
							<td style="vertical-align: top !important;" rowspan=""><?php echo ++$srn; ?> </td>
							<td style="vertical-align: top !important;" rowspan=""><?php echo date("d-m-Y",strtotime($date)); ?></td>
							<td style="vertical-align: top !important;" rowspan="">
								<?php echo $voucher;?>
							</td>
							<td style="vertical-align: top !important;" rowspan=""><?php echo $in_out; ?> </td>
							<td>
								<table border="1" width="100%">
									<thead>
										<tr>		
											<th width="10%">Item</th>
											<?php if($key1=="InventoryTransferVouchers" || $key1=="InventoryVouchers"){?>
											<th width="2%">In/Out</th>
											<?php } ?>
											<th width="5%">Quantity</th>
											<th width="12%">Serial No</th>
										</tr>
									</thead>
								<?php if($key1=="InventoryVouchers"){ ?>
									<tbody>
										<?php foreach($IVRs as $key=>$IVR){ ?>
										<tr>
											<td rowspan=""><?php echo $IVR['item_name']?></td>
											<td rowspan=""><?php echo $IVR['status']?></td>
											<td rowspan=""><?php echo $IVR['item_qty']?></td>
											<td rowspan="">
											</td>
										</tr>
										<?php } ?>
										
										<?php foreach($IVRI as $key=>$IVRDatas){ ?>
										<?php foreach($IVRDatas as $key22=>$IVRData){ 
														
										$sr_size=0;
										if(!empty($IVSr[@$key22])){
												$sr_size=sizeof($IVSr[@$key22]);
										}

										?>
										<tr>
											<td rowspan=""><?php echo $IVRData['item_name']?></td>
											<td rowspan=""><?php echo $IVRData['status']?></td>
											<td rowspan=""><?php echo $IVRData['item_qty']?></td>
											<td rowspan="">
											<?php if($sr_size > 0){ ?>
												<?php foreach($IVSr[@$key22] as $serial_number){ 
													echo $serial_number->name; echo ",";
												}?>
												<?php }else{ 
													echo "-";
												?>
												
												<?php } ?>
											</td>
										</tr>
										<?php } } ?>
									</tbody>
									
								<?php }else{ ?>
									<tbody>
										<?php foreach($voucher_rows as $voucher_row){ 
										$sr_size=count($voucher_row->serial_numbers); //pr($sr_size);?>
										<tr>
											<td rowspan=""><?php echo $voucher_row->item->name?></td>
											<?php if($key1=="InventoryTransferVouchers"){?>
											<td rowspan=""><?php echo $voucher_row->status?></td>
											<?php } ?>
											<td rowspan=""><?php echo $voucher_row->quantity?></td>
											<td rowspan="">
												<?php if($voucher_row->serial_numbers){ ?>
												<?php foreach($voucher_row->serial_numbers as $serial_number){ 
													echo $serial_number->name; echo ",";
												}?>
												<?php }else{ 
													echo "-";
												?>
												<?php } ?>
											</td>
										</tr>
										<?php }?>
									</tbody>
								<?php } ?>
								</table>
							</td>
						</tr>
				<?php $i++; } ?>
		<?php } }  }?>
		</tbody>
		</table>