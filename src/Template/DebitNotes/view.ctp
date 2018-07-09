<style>
@media print{
	.maindiv{
		width:100% !important;
	}	
	.hidden-print{
		display:none;
	}
.table{
		width: 12% !important;
		margin-left: 19px !important;
		
	}
}

p{
margin-bottom: 0;
}
.table{
		width: 25% !important;
		margin-left: 19px !important;
		
	}
	.tabitem thead tr th {
    color: #FFF;
    background-color: #254b73;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    padding: 5px !important;
}
</style>
<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 5px 5px 5px 5px;  /* this affects the margin in the printer settings */
}

.table_rows th{
		border: 1px solid  #000;
		
	}
	.table_rows td{
		border: 1px solid  #000;
		
	}
.table{
		width: 25% !important;
		margin-left: 19px !important;
		
	}
@page {
      size: landscape;
	  margin-left: 5px !important;
    }
.maindiv {  width:100% !important;
		border: none !important;
	}
  table.report { page-break-after:auto }
  table.report tr    { page-break-inside:avoid; page-break-after:auto }
  table.report td    { page-break-inside:avoid; page-break-after:auto }
  table.report thead { display:table-header-group; border:none !important; padding:10px; }

</style>
<a class="btn  blue hidden-print margin-bottom-5 pull-right" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>

<div style="border:solid 1px #c7c7c7;background-color: #FFF;padding: 10px;margin: auto;width: 55%;font-size: 12px;" class="maindiv">	
	<table width="100%" class="divHeader">
		<tr>
			<td width="30%"><?php echo $this->Html->image('/logos/'.$debitNote->company->logo, ['width' => '40%']); ?></td>
			<td align="center" width="40%" style="font-size: 12px;"><div align="center" style="font-size: 16px;font-weight: bold;color: #0685a8;">DEBIT NOTE</div></td>
			<td align="right" width="40%" style="font-size: 12px;">
			<span style="font-size: 14px;"><?= h($debitNote->company->name) ?></span>
			<span><?= $this->Text->autoParagraph(h($debitNote->company->address)) ?>
			<span> <i class="fa fa-phone" aria-hidden="true"></i> <?= h($debitNote->company->landline_no) ?></span> |
			<?= h($debitNote->company->mobile_no) ?>
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
						<td>Customer/Supplier</td>
						<td width="20" align="center">:</td>
						<td><?php if(!empty($debitNote->customer_suppiler->alias)){
							echo $debitNote->customer_suppiler->name.'('.$debitNote->customer_suppiler->alias.')';
						}else{
							echo $debitNote->customer_suppiler->name;
						}  ?></td>
					</tr>
					<tr>
						<td>Voucher No</td>
						<td width="20" align="center">:</td>
						<td><?= h('DR/'.str_pad($debitNote->voucher_no, 4, '0', STR_PAD_LEFT)) ?></td>
					</tr>
				</table>
			</td>
			<td width="50%" valign="top" align="right">
				<table>
					<tr>
						<td>Transaction Date</td>
						<td width="20" align="center">:</td>
						<td><?= h(date("d-m-Y",strtotime($debitNote->transaction_date))) ?></td>
					</tr>
					<tr>
						<td>Created On</td>
						<td width="20" align="center">:</td>
						<td><?= h(date("d-m-Y",strtotime($debitNote->created_on))) ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	
	<br/>
	<table class="tabitem" width="100%" border="1" style="font-size:15px">
		<thead>
			<tr >
				<th rowspan="2" style="text-align: center; width:2%;">S.N.</th>
				<th rowspan="2" style="text-align: center; width:25%;">Narration</th>
				<th rowspan="2" style="text-align: center; width:8%;">Taxable Value</th>
				<?php if($debitNote->igst_total_amount==0){ ?>
				<th style=" text-align: center; width:15%;" colspan="2" width="12%">CGST</th>
				<th style=" text-align: center; width:15%;" colspan="2" width="12%">SGST</th>
				<?php }else{ ?>
				<th  style=" text-align: center; width:15%;" colspan="2" width="12%">IGST</th>
				<?php } ?>
				<th rowspan="2" style="text-align: center; width:8%;"  width="8%">Total</th>
				
			</tr>
			<tr> 
				<?php if($debitNote->igst_total_amount==0){ ?>
				<th style="text-align: center;" width="6%">%</th>
				<th style="text-align: center;" width="6%">Amt</th>
				<th style="text-align: center;" width="6%">%</th>
				<th style="text-align: center;" width="6%">Amt</th>
				<?php }else{ ?>
				<th style="text-align: center;" width="6%">%</th>
				<th style="text-align: center;" width="6%">Amt</th>
				<?php } ?>
			</tr>
		</thead>
		<?php $i=1; $total_cr=0; $total_dr=0; foreach ($debitNote->debit_notes_rows as $debit_notes_row): ?>
		<tr>
			<td style="text-align: center;"><?php echo $i++; ?></td>
			<td  style="text-align: left;" ><?php echo $debit_notes_row->narration ?></td>
			<td align="right"><?= h($this->Number->format($debit_notes_row->amount,[ 'places' => 2])) ?> </td>
			<?php if($debitNote->igst_total_amount==0){ ?>
			<td align="center"><?= h(@$cgst_per[$debit_notes_row->id]['tax_figure']) ?></td>
			<td align="right"><?= h($this->Number->format($debit_notes_row->cgst_amount,[ 'places' => 2])) ?> </td>
			
			<td align="center"><?= h(@$sgst_per[$debit_notes_row->id]['tax_figure']) ?></td>
			<td align="right"><?= h($this->Number->format($debit_notes_row->sgst_amount,[ 'places' => 2])) ?> </td>
			<?php }else{ ?>
			<td align="center"><?= h(@$igst_per[$debit_notes_row->id]['tax_figure']) ?></td>
			<td align="right"><?= h($this->Number->format($debit_notes_row->igst_amount,[ 'places' => 2])) ?> </td>
			<?php } ?>
			
			<td align="right"><?= h($this->Number->format($debit_notes_row->total_amount,[ 'places' => 2])) ?></td>
		</tr>
		
		<?php endforeach; ?>
		
		<?php if($debitNote->igst_total_amount==0){ ?>
		<tr>
			<td colspan="7" align="right" style="border-bottom-style:none;"><b>CGST Total</b></td>
			<td align="right"><b><?= h($this->Number->format($debitNote->cgst_total_amount,[ 'places' => 2])) ?></b></td>
			
		</tr>
		<tr>
			<td colspan="7" align="right"><b>SGST Total</b></td>
			<td align="right"><b><?= h($this->Number->format($debitNote->cgst_total_amount,[ 'places' => 2])) ?></b></td>
			
		</tr>
		<tr>
			<td colspan="7" align="right"><b>Grand Total</b></td>
			<td align="right"><b><?= h($this->Number->format($debitNote->grand_total,[ 'places' => 2])) ?></b></td>
		</tr>
		<?php }else{ ?>
		<tr>
			<td colspan="5" align="right"><b>IGST Total</b></td>
			<td align="right"><b><?= h($this->Number->format($debitNote->igst_total_amount,[ 'places' => 2])) ?></b></td>
		</tr>
		<tr>
			<td colspan="5" align="right"><b>Grand Total</b></td>
			<td align="right"><?= h($this->Number->format($debitNote->grand_total,[ 'places' => 2])) ?></td>
		</tr>
		<?php } ?>
		
<?php 
	$grand_total=explode('.',$debitNote->grand_total);
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
	
	<table width="100%">
		<tr>
			<th>Ref Type</th>
			<th>Ref No</th>
			<?php if(!empty($ReferenceDetail->debit)){ ?>
				<th>Debit</th>
			<?php }else{ ?>
				<th>Credit</th>
			<?php } ?>
			
		</tr>
		<?php  foreach($ReferenceDetails as $ReferenceDetail){   ?>
		<tr>
			<td width="30%"><?=h($ReferenceDetail->reference_type) ?></td>
			<td><?=h($ReferenceDetail->reference_no) ?></td>
			<?php if(!empty($ReferenceDetail->debit)){ ?>
			<td>Rs.<?=h($this->Number->format($ReferenceDetail->debit,[ 'places' => 2])) ?></td>
			<?php }else{ ?>
			<td>Rs.<?=h($this->Number->format($ReferenceDetail->credit,[ 'places' => 2])) ?></td>
			<?php } ?>
		</tr>
		<?php  } ?>
	</table>
	</br>
	<div style="border:solid 1px ;"></div>
	<table width="100%" class="divFooter">
		<tr>
		   <td align="right">
				<table>
					<tr>
						<td align="center">
						<span style="font-size:14px;font-weight: bold;">For</span> <span style="font-size: 14px;font-weight: bold;"><?= h($debitNote->company->name)?><br/></span>
						<?php 
						 echo $this->Html->Image('/signatures/'.$debitNote->creator->signature,['height'=>'50px','style'=>'height:50px;']); 
						 ?></br>
						<span style="font-size: 14px;font-weight: bold;">Prepared By</span>
						</br>
						<span style="font-size:14px;"><?= h($debitNote->creator->name) ?></span><br/>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
