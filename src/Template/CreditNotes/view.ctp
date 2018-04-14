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

.table_rows th{
		border: 1px solid  #000;
		
	}
	.table_rows td{
		border: 1px solid  #000;
		
	}
</style>
<a class="btn  blue hidden-print margin-bottom-5 pull-right" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>

<div style="border:solid 1px #c7c7c7;background-color: #FFF;padding: 10px;margin: auto;width: 90%;font-size: 12px;" class="maindiv">	
	<table width="100%" class="divHeader">
		<tr>
			<td width="30%"><?php echo $this->Html->image('/logos/'.$creditNotes->company->logo, ['width' => '40%']); ?></td>
			<td align="center" width="40%" style="font-size: 12px;"><div align="center" style="font-size: 16px;font-weight: bold;color: #0685a8;">CREDIT NOTE</div></td>
			<td align="right" width="40%" style="font-size: 12px;">
			<span style="font-size: 14px;"><?= h($creditNotes->company->name) ?></span>
			<span><?= $this->Text->autoParagraph(h($creditNotes->company->address)) ?></span>
			<span> <i class="fa fa-phone" aria-hidden="true"></i> <?= h($creditNotes->company->landline_no) ?></span> |
			<?= h($creditNotes->company->mobile_no) ?>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<div style="border:solid 2px #0685a8;margin-bottom:5px;margin-top: 5px;"></div>
			</td>
		</tr>
	</table>
	<table width="100%">
		<tr>
			<td width="70%" valign="top" align="left">
				<table>
					<tr>
						<?php  ?>
						<td><?= h($creditNotes->head->name.'('.$creditNotes->head->alias.')') ?>
						<?php echo "<br/>"; echo $partyData->customer_address[0]->address; ?></td>
					</tr>
					
				</table>
			</td>
			<td width="50%" valign="top" align="right">
				<table>
					<tr>
						<td>Voucher No</td>
						<td width="20" align="center">:</td>
						<?php $FY=substr($s_year_from, -2).'-'.substr($s_year_to, -2); ?>
						<td><?= h('CR/'.str_pad($creditNotes->voucher_no, 4, '0', STR_PAD_LEFT).'/'.$FY) ?></td>
					</tr>
					
					<tr>
						<td>Transaction Date</td>
						<td width="20" align="center">:</td>
						<td><?= h(date("d-m-Y",strtotime($creditNotes->transaction_date))) ?></td>
					</tr>
					
				</table>
			</td>
		</tr>
	</table>
	
	<br/>
	<table width="100%" border="1" style="font-size:15px">
		
			<tr >
				<th rowspan="2" style="text-align: center; width:2%;">S.N.</th>
				<th rowspan="2" style="text-align: center; width:25%;">Paid To</th>
				<th rowspan="2" style="text-align: center; width:10%;">Taxable Value</th>
				<th style=" text-align: center; width:15%;" colspan="2" width="15%">CGST</th>
				<th style=" text-align: center; width:15%;" colspan="2" width="15%">SGST</th>
				<th  style=" text-align: center; width:15%;" colspan="2" width="15%">IGST</th>
				<th rowspan="2" style="text-align: center; width:10%;"  width="10%">Total</th>
				
			</tr>
			<tr> 
				<th style="text-align: center;" >%</th>
				<th style="text-align: center;" >Amt</th>
				<th style="text-align: center;" >%</th>
				<th style="text-align: center;" >Amt</th>
				<th style="text-align: center;">%</th>
				<th style="text-align: center;">Amt</th>
			</tr>
		
		<?php $total_cr=0; $total_dr=0; foreach ($creditNotes->credit_notes_rows as $credit_notes_row): ?>
		<tr>
			<td>1</td>
			<td  style="text-align: center;"><?= h($credit_notes_row->ReceivedFrom->name) ?></td>
			<td align="right"><?= h($this->Number->format($credit_notes_row->amount,[ 'places' => 2])) ?> </td>
			<td align="right"><?= h(@$cgst_per[$credit_notes_row->id]['tax_figure']) ?></td>
			<td align="right"><?= h($credit_notes_row->cgst_amount) ?></td>
			<td align="right"><?= h(@$sgst_per[$credit_notes_row->id]['tax_figure']) ?></td>
			<td align="right"><?= h($credit_notes_row->sgst_amount) ?></td>
			<td align="right"><?= h(@$igst_per[$credit_notes_row->id]['tax_figure']) ?></td>
			<td align="right"><?= h($credit_notes_row->igst_amount) ?></td>
			<td align="right"><?= h($credit_notes_row->total_amount) ?></td>
		</tr>
		<tr>
			<td></td>
			<td  colspan="9"  style="text-align: left; line-height:40px; valign:top;"><?= h($credit_notes_row->narration) ?></td>
		</tr>
		<?php endforeach; ?>
		<tr>
			<td colspan="9" align="right" style="border-bottom-style:none;">CGST Total</td>
			<td align="right"><?php echo $creditNotes->cgst_total_amount ?></td>
		</tr>
		<tr>
			<td colspan="9" align="right">SGST Total</td>
			<td align="right"><?php echo $creditNotes->sgst_total_amount ?></td>
		</tr>
		<tr>
			<td colspan="9" align="right">IGST Total</td>
			<td align="right"><?php echo $creditNotes->igst_total_amount ?></td>
		</tr>
		<tr>
			<td colspan="9" align="right">Grand Total</td>
			<td align="right"><?php echo $creditNotes->grand_total ?></td>
		</tr>
<?php 
	$grand_total=explode('.',$creditNotes->grand_total);
	$rupees=$grand_total[0];
	$paisa_text='';
	if(sizeof($grand_total)==2){
		$grand_total[1]=str_pad($grand_total[1], 2, '0', STR_PAD_RIGHT);
		$paisa=(int)$grand_total[1];
		$paisa_text=' and ' . h(ucwords($this->NumberWords->convert_number_to_words($paisa))) .' Paisa';
	}else{
		$paisa_text=""; 
	} 
?>
		
	</table>
	
	
	<br/>
	<div style="border:solid 1px ;"></div>
	<table width="100%" class="divFooter">
		<tr>
			<td align="left" valign="top">
				<table>
					<tr>
						<td style="font-size: 16px;font-weight: bold;">
						Rs: <?= h($this->Number->format($creditNotes->grand_total,[ 'places' => 2])) ?></td>
					</tr>
					<tr>
						<td align="right" style="font-size: 12px;"><?=   h(ucwords($this->NumberWords->convert_number_to_words($rupees))) .'  Rupees ' .h($paisa_text) ?></td>
					</tr>
					<tr>
						<td style="font-size: 12px;">
						
						</td>
					</tr>
				</table>
			</td>
		    <td align="right" valign="top">
				<table style="margin-top:3px;">
					<tr>
					   <td width="100%" align="center" style="float: right;"> 
						<?php 
						 echo $this->Html->Image('/signatures/'.$creditNotes->creator->signature,['height'=>'40px','style'=>'height:40px;']); 
						 ?></br>
						 </hr>
						 <span><b>Prepared By</b></span><br/>
						 <span><?= h($creditNotes->company->name) ?></span><br/>
						</td>
					</tr>
				</table>
			 </td>
		</tr>
	</table>
</div>
