<?php 

	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="Trial_Balance".$date.'_'.$time;

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
							<th scope="col"></th>
							<th scope="col" colspan="2" style="text-align:center";><b>Opening Balance</th>
							<th scope="col" colspan="2" style="text-align:center";><b>Transactions</th>
							<th scope="col" colspan="2" style="text-align:center";><b>Closing balance</th>
						</tr>
						<tr>
							<th scope="col"><b>Ledgers</th>
							<th scope="col" style="text-align:center";><b>Debit</th>
							<th scope="col" style="text-align:center";><b>Credit</th>
							<th scope="col" style="text-align:center";><b>Debit</th>
							<th scope="col" style="text-align:center";><b>Credit</th>
							<th scope="col" style="text-align:center";><b>Debit</th>
							<th scope="col" style="text-align:center";><b>Credit</th>
						</tr>
					</thead>
					<tbody>
							<?php $i=1; $totalDr=0; $totalCr=0; foreach($ClosingBalanceForPrint as $key=>$data){  ?>
							<tr>		
								<td>
									<?php echo $data['name']; ?>
								</td>
								<?php if($OpeningBalanceForPrint[$key]['balance'] > 0){ ?>
								<td><?php echo $OpeningBalanceForPrint[$key]['balance'];
										?></td>
								<td><?php echo "-" ?></td>
								<?php }else{ ?>
								<td><?php echo "-"; ?></td>
								<td><?php echo abs($OpeningBalanceForPrint[$key]['balance']);  ?></td>
								<?php } ?>


								
								<td><?php echo abs($TransactionDr[$key]['balance']);
										?></td>
								
								<td><?php echo abs($TransactionCr[$key]['balance']);
									  ?></td>
								
								


								<?php if($data['balance'] > 0){ ?>
								<td><?php echo $data['balance'];
									@$totalDr=@$totalDr+$data['balance'];	?></td>
								<td><?php echo "-" ?></td>
								<?php }else{ ?>
								<td><?php echo "-"; ?></td>
								<td><?php echo abs($data['balance']);  
									@$totalCr=@$totalCr+abs($data['balance']);  ?></td>
								<?php } ?>
								
							</tr>
							<?php } ?>
							<tr>
								<td colspan="5">Opening Stocks</td>
								<td  scope="col" align="left"><?php echo round($itemOpeningBalance,2); ?></td>
								<td></td>
							</tr>
							<tr style="color:red;">
								<td colspan="5">Diff. In Opening Balance</td>
								<?php if($differenceInOpeningBalance > 0){ ?>
									<td></td>
									<td  scope="col" align="left"><?php echo round($differenceInOpeningBalance,2); ?></td>
									
								<?php } else { ?>
										
									<td  scope="col" align="left"><?php 
									$differenceInOpeningBalance=abs($differenceInOpeningBalance);
									echo round($differenceInOpeningBalance,2); ?></td>
									<td></td>
								<?php } ?>
								
							</tr>
							<tr>
								<td colspan="5">Total</td>
								<?php if($differenceInOpeningBalance < 0){ ?>
								<td  scope="col" align="left"><?php echo round($totalDr+$itemOpeningBalance,2); ?></td>
								<td  scope="col" align="left"><?php echo round($totalCr+$differenceInOpeningBalance,2); ?></td>
								<?php } else { ?>
								<td  scope="col" align="left"><?php echo round($totalDr+$differenceInOpeningBalance+$itemOpeningBalance,2); ?></td>
								<td  scope="col" align="left"><?php echo round($totalCr,2); ?></td>
								<?php } ?>
							</tr>
							
					</tbody>
				<tfoot>
			</tfoot>
		</table>