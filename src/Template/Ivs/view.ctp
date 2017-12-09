
<style>
@media print{
	.maindiv{
		width:100% !important;
	}	
	.hidden-print{
		display:none;
	}
}
p{
margin-bottom: 0;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    padding: 5px !important;
}
</style>
<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0 5px 0 20px;  /* this affects the margin in the printer settings */
}
</style>
<a class="btn  blue hidden-print margin-bottom-5 pull-right" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
<div style="border:solid 1px #c7c7c7;background-color: #FFF;padding: 10px;margin: auto;width: 70%;font-size: 12px;" class="maindiv">
<table width="100%" class="divHeader" border="0">
<tr>
    <td><?php echo $this->Html->image('/logos/'.$iv->company->logo, ['width' => '48%']); ?></td>
    <td align="center" width="30%" style="font-size: 12px;"><div align="center" style="font-size: 16px;font-weight: bold;color: #0685a8;">INVENTORY VOUCHER</div></td>
	<td align="right" width="40%" style="font-size: 12px;">
			<span style="font-size: 14px;"><?= h($iv->company->name) ?></span>
			<span><?= $this->Text->autoParagraph(h($iv->company->address)) ?></span>
			<span> <i class="fa fa-phone" aria-hidden="true"></i> <?= h($iv->company->landline_no) ?></span> |
			<?= h($iv->company->mobile_no) ?>
			</td>
	
</tr>
</table>
<div style="border:solid 3px #0685a8;margin-bottom:5px;margin-top: 5px;"></div>
</br>
<table width="100%" style="margin-top: -17px;">
	<tr>
		<td width="50%" valign="top" align="left">
			<table>
				<tr>
					<td><b>Inventory Voucher No</b></td>
					<td width="20%" align="center">:</td>
					<td>
					<?= h('IV/'.str_pad($iv->voucher_no, 4, '0', STR_PAD_LEFT)) ?>
					</td>
				</tr>
				<tr>
					<td><b>Invoice No</b></td>
						<td width="20%" align="center">:</td>
						<td><?= h($iv->invoice->in1.'/IN'.str_pad($iv->invoice->in2, 3, '0', STR_PAD_LEFT).'/'.$iv->invoice->in3.'/'.$iv->invoice->in4) ?>
					</td>
				</tr>
			</table>
	   </td>
	   
	   <td width="50%" valign="top" align="right">
				<table>
					<tr>
						<td><b>Transaction Date</b></td>
						<td width="20%" align="center">:</td>
						<td><?php  if(!empty($iv->transaction_date)){ echo date("d-m-Y",strtotime($iv->transaction_date));}else{echo '-';} ?></td>
					</tr>
					<!--<tr>
						<td><b>Created On</b></td>
						<td width="20%" align="center">:</td>
						<td><?php  if(!empty($iv->created_on)){ echo date("d-m-Y",strtotime($iv->created_on));}else{echo '-';} ?></td>
					</tr>-->
				</table>
			</td>
	</tr>
	
</table>
<table>
<tr>
		<td width="50%" valign="top" align="left" >
			<table width="100%">
				<tr>
					<td width="20%"><b>Customer Name</b></td>
					<td width="5%">:</td>
					<td width="72%"><?php echo $iv->invoice->customer->customer_name ."(". $iv->invoice->customer->alias.")"; ?></td>
				</tr>
			</table>
	   </td>
	</tr>
</table>	
</br>
<?php if(!empty($iv)){ ?>
<div class="portlet-body form">
<table class="table table-bordered table-condensed">
	<thead> 
		<th width="30%" colspan='1'><b>Production</b></th>
		<?php $status=0;
					foreach($iv->iv_rows as $iv_row ){
						
						if( $iv_row->item->item_companies[0]->	serial_number_enable == 1) {
						$status=1;
						}
					} ?>
		<?php if($status==1){ ?>
						<th width="10%"></th>
					<?php } ?>
		<th>
		<?php $status1=0;
					
					foreach($iv->iv_rows as $iv_row ){
						foreach($iv_row->iv_row_items as $iv_row_item ){ 
							if( $iv_row_item->item->item_companies[0]->serial_number_enable == 1) {
							$status1=1;
							}
						}
					}
					
					?>
					
			<table width="97%" align="center">
				<tr>
					<th width="7%">Item</th>
					
					<?php if($status1==1) { ?>
					<th width="10%">Item Serial No</th>
					<?php } ?>
					<td width="1%">Quantity</td>
				</tr>
			</table>
		</th>
		
	</thead>
	
	<tbody>
		<?php foreach ($iv->iv_rows as $iv_row): 
       ?>
		<tr>
			<td valign="top">
			<b><?= $iv_row->item->name ?> ( <?= h($iv_row->quantity) ?> )</b>
			
			
				<?php if($status==1) {  if(!empty($iv_row->item->item_companies[0]->serial_number_enable)){
							if($iv_row->item->item_companies[0]->serial_number_enable == 1) { ?>
							<td width="10%"><table>
							<?php foreach ($iv_row->item->serial_numbers as  $serial_number){ 
							if($serial_number->iv_row_id == $iv_row->id){ ?>
							<tr>
								<td ><?php echo $serial_number->name ?></td>
							</tr>
							<?php }} ?>
							</table>
							</td>
				<?php }}} ?>
			
			
			</td>
			<td>
				<table class="table table-bordered" width='100%'>
					<?php foreach($iv_row->iv_row_items as $iv_row_item): ?> 
					<tr>
						<td width="30%">
							<?= $iv_row_item->item->name?>
						</td>
						<?php 
						if($status1==1) {
						if(!empty($iv_row_item->item->item_companies[0]->serial_number_enable)){
							if($iv_row_item->item->item_companies[0]->serial_number_enable == 1) { ?>
								<td width="50%">
									<table>
										<?php foreach ($iv_row_item->item->serial_numbers as  $serial_number){ 
											if($serial_number->iv_row_items == $iv_row_item->id){ ?>
												<tr>
													<td ><?php echo $serial_number->name ?></td>
												</tr>
											<?php }else{ ?>
												<tr><td></td></tr>
											<?php }} ?>
									</table>
								</td>
								<?php }else{ ?>
								<td >-</td>
								<?php }}  else{ ?> 
								<td>-</td>
						<?php }} ?>
						<td width="8%" ><?= $iv_row_item->quantity?></td>
					</tr>
					<?php  endforeach; ?>
				</table>
			</td>
		</tr>
		<?php  endforeach; ?>
	</tbody>
	
</table>
</br>

</div>
<?php } ?>
<div style="border:solid 1px ;"></div>
	

<table width="96%">
	<tr>
	    <td align="left">
			<table  class="divFooter" >
				<tr>
					<td valign="top" align="left" width="10%"><b>Narration</b></td>
					<td  width="2%" align="center">:</td>
					<td>
					<?= h($iv->narration) ?>
					</td>
				</tr>
			</table></br></br></br>
		</td>
		<td align="right">
		<table >
			<tr>
			    <td align="center">
				<span style="font-size:14px;">For</span> <span style="font-size: 14px;font-weight: bold;"><?= h($iv->company->name)?><br/></span>
				<?php 
				 echo $this->Html->Image('/signatures/'.$iv->creator->signature,['height'=>'50px','style'=>'height:50px;']); 
				 ?></br>
				<span style="font-size: 14px;font-weight: bold;">Authorised Signatory</span>
				</br>
				<span style="font-size:14px;"><?= h($iv->creator->name) ?></span><br/>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</div>

 
 