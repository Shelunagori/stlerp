<?php 

	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="Debit_Note_Vouchers".$date.'_'.$time;

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
			<td colspan="3" align="center">
			<b> Debit Note Vouchers
			<?php if(!empty($From) || !empty($To)){ echo date('d-m-Y',strtotime($From)); ?> TO <?php echo date('d-m-Y',strtotime($To));  } ?> 

			</b>
			</td>
		</tr>
		 
                        <tr>
                            <th>Sr. No.</th>
                            <th>Customer/Supplier</th>
							<th>Vocher No</th>
                            <th>Transaction Date</th>
                        </tr>
                   
                    <tbody>
                        <?php $i=1; foreach ($debitNotes as $debitNote):   ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td>
							<?php if(!empty($debitNote->head->alias)){
								echo $debitNote->head->name.'('.$debitNote->head->alias.')';
							}else{
								echo $debitNote->head->name;
							} ?>
							</td>
							 <td><?php $voucher=('DN/'.str_pad($debitNote->voucher_no, 4, '0', STR_PAD_LEFT)); ?>
							<?php 
							$s_year_from = date("Y",strtotime($debitNote->financial_year->date_from));
							$s_year_to = date("Y",strtotime($debitNote->financial_year->date_to));
							$fy=(substr($s_year_from, -2).'-'.substr($s_year_to, -2)); 
							?>
							 
							 
							 
							 <?php echo $voucher.'/'.$fy;?></td>
                            <td><?= h(date("d-m-Y",strtotime($debitNote->transaction_date)))?></td>
                           
						
                          
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
</table>