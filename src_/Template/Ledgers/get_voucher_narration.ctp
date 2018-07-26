<tr>
	<td></td>
	<td colspan="11">
	<table class="table">
		<thead>
			<tr>
				<th><b>Narration :</b></th>
			</tr>
		</thead>
		<tbody>
		<?php $i=0; foreach ($Ledgers as $ledger){ $i++;
			if($voucher_source=="Non Print Payment Voucher"){
				$Receipt=$url_link[$ledger->id];
				$rowsData = $Receipt->nppayment_rows;
			}else if($voucher_source=="Contra Voucher"){
				$Receipt=$url_link[$ledger->id];
				$rowsData = $Receipt->contra_voucher_rows;
			}else if($voucher_source=="Petty Cash Payment Voucher"){
				$Receipt=$url_link[$ledger->id];
				$rowsData = $Receipt->petty_cash_voucher_rows;
			}else if($voucher_source=="Receipt Voucher"){
				$Receipt=$url_link[$ledger->id];
				$rowsData = $Receipt;
				//pr($rowsData);
			}else if($voucher_source=="Journal Voucher"){
				$Receipt=$url_link[$ledger->id];
				$rowsData = $Receipt->journal_voucher_rows;
				//pr($rowsData);
			}else if($voucher_source=="Payment Voucher"){
				$Receipt=$url_link[$ledger->id];
				$rowsData = $Receipt->payment_rows;
				//pr($rowsData);
			}
		}
		$j=1;
	if(sizeof($rowsData) >0 ){
		if($voucher_source=="Receipt Voucher"){ ?>
			<tr>
			<td style="text-align:justify;"><?php echo $j.' . '.$rowsData->narration; ?></td>
			
			</tr>
		<?php }else{
			foreach($rowsData as $row){ ?>
			<tr>
			<td style="text-align:justify;"><?php echo $j.' . '.$row->narration; ?></td>
			
			</tr>
			
		<?php $j++; } 
		}
		
	}else{
		echo "No Data Found!!!";
	}	
	?>	
	</table>
	</td>
</tr>