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


		<!-- BEGIN FORM-->
<?php if(!empty($ClosingBalanceForPrint)){ ?>
<table class="table table-bordered table-hover table-condensed" width="100%">
					<thead>
						<tr>
							<th scope="col"></th>
							<?php if($show=="All"){ ?>
								<th scope="col" colspan="2" style="text-align:center";><b>Opening Balance</th>
								<th scope="col" colspan="2" style="text-align:center";><b>Transactions</th>
								<th scope="col" colspan="2" style="text-align:center";><b>Closing balance</th>
							<?php }else if($show=="Closing"){ ?>
								<th scope="col" colspan="2" style="text-align:center";><b>Closing balance</th>
							<?php }else if($show=="Open_Close"){ ?>
								<th scope="col" colspan="2" style="text-align:center";><b>Opening Balance</th>
								<th scope="col" colspan="2" style="text-align:center";><b>Closing balance</th>
							<?php }else if($show=="Last") {  
									if($st_year_id != 1 || $st_year_id !=2 || $st_year_id != 3){ ?>
								<th scope="col" colspan="2" style="text-align:center";><b>Closing balance</th>
								<th scope="col" colspan="2" style="text-align:center";><b>Last Year Closing balance</th>
							<?php } }?>
						</tr>
						<tr>
								<th scope="col"><b>Ledgers</th>
							<?php if($show=="All"){ ?>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
							<?php }else if($show=="Closing"){ ?>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
							<?php }else if($show=="Open_Close"){ ?>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
							<?php }else if($show=="Last"){ ?>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
							<?php $op_dr_total=0; $op_cr_total=0;$tr_dr_total=0; $tr_cr_total=0;$cl_dr_total=0; $cl_cr_total=0; $i=1; $totalDr=0; $totalCr=0; foreach($ClosingBalanceForPrint as $key=>$data){  ?>
							<tr>
							
							
							<?php if($show=="All"){ ?>
								<td>
									
									<?php echo $data['name']; ?>
									
								</td>
								<?php if($OpeningBalanceForPrint[$key]['balance'] > 0){ ?>
									<td class="first"><?php 
										echo $OpeningBalanceForPrint[$key]['balance'];
										$op_dr_total += $OpeningBalanceForPrint[$key]['balance'];?>
									</td>
									<td class="first"><?php echo "-" ?></td>
									<?php }else{ ?>
										<td class="first"><?php echo "-"; ?></td>
										<td  class="first"><?php echo abs($OpeningBalanceForPrint[$key]['balance']); 
											$op_cr_total += $OpeningBalanceForPrint[$key]['balance'];?>
										</td>
									<?php } ?>
									
									<td  class="first"><?php echo abs($TransactionDr[$key]['balance']);
										$tr_dr_total += $TransactionDr[$key]['balance'];?>
									</td>
								
									<td  class="first"><?php echo abs($TransactionCr[$key]['balance']);
										$tr_cr_total += $TransactionDr[$key]['balance'];?>
									</td>
									
									<?php if($data['balance'] > 0){ ?>
									<td  class="first"><?php echo $data['balance'];
										@$totalDr=@$totalDr+$data['balance'];	
										$cl_dr_total += $data['balance']; ?>
									</td>
									<td class="first"><?php echo "-" ?>
									</td>
								<?php }else{ ?>
									<td class="first"><?php echo "-"; ?></td>
									<td  class="first"><?php echo abs($data['balance']);  
										$cl_cr_total += $data['balance'];
										@$totalCr=@$totalCr+abs($data['balance']);  ?>
									</td>
								<?php } ?>
								
							<?php }else if($show=="Closing"){ ?>
									<td><?php echo $data['name']; ?></td>
									<?php if($data['balance'] > 0){ ?>
									<td><?php echo $data['balance'];
										@$totalDr=@$totalDr+$data['balance'];	
										$cl_dr_total += $data['balance']; ?>
									</td>
									<td><?php echo "-" ?>
									</td>
								<?php }else{ ?>
									<td><?php echo "-"; ?></td>
									<td><?php echo abs($data['balance']);  
										$cl_cr_total += $data['balance'];
										@$totalCr=@$totalCr+abs($data['balance']);  ?>
									</td>
								<?php } ?>
								 
							<?php }else if($show=="Open_Close"){ ?>
									<td><?php echo $data['name']; ?></td>
									<?php if($OpeningBalanceForPrint[$key]['balance'] > 0){ ?>
									<td><?php 
										echo $OpeningBalanceForPrint[$key]['balance'];
										$op_dr_total += $OpeningBalanceForPrint[$key]['balance'];?>
									</td>
									<td><?php echo "-" ?></td>
									<?php }else{ ?>
										<td><?php echo "-"; ?></td>
										<td><?php echo abs($OpeningBalanceForPrint[$key]['balance']); 
											$op_cr_total += $OpeningBalanceForPrint[$key]['balance'];?>
										</td>
									<?php } ?>
									<?php if($data['balance'] > 0){ ?>
									<td><?php echo $data['balance'];
										@$totalDr=@$totalDr+$data['balance'];	
										$cl_dr_total += $data['balance']; ?>
									</td>
									<td><?php echo "-" ?>
									</td>
								<?php }else{ ?>
									<td><?php echo "-"; ?></td>
									<td><?php echo abs($data['balance']);  
										$cl_cr_total += $data['balance'];
										@$totalCr=@$totalCr+abs($data['balance']);  ?>
									</td>
								<?php } ?>
							<?php }else if($show=="Last"){ ?>
									<td><?php echo $data['name']; ?></td>
									<?php if($data['balance'] > 0){ ?>
									<td><?php echo $data['balance'];
										@$totalDr=@$totalDr+$data['balance'];	
										$cl_dr_total += $data['balance']; ?>
									</td>
									<td><?php echo "-" ?>
									</td>
								<?php }else{ ?>
									<td><?php echo "-"; ?></td>
									<td><?php echo abs($data['balance']);  
										$cl_cr_total += $data['balance'];
										@$totalCr=@$totalCr+abs($data['balance']);  ?>
									</td>
								<?php } ?>
								
								<?php if($LastYear[$key]['balance'] > 0){ ?>
									<td><?php 
										echo $LastYear[$key]['balance'];
										$op_dr_total += $LastYear[$key]['balance'];?>
									</td>
									<td><?php echo "-" ?></td>
									<?php }else{ ?>
										<td><?php echo "-"; ?></td>
										<td><?php echo abs($LastYear[$key]['balance']); 
											$op_cr_total += $LastYear[$key]['balance'];?>
										</td>
									<?php } ?>
								
							<?php } ?>
							</tr>
							<?php } ?>
							<?php if($show=="All"){ ?>
							<tr>
								<td >Opening Stocks</td>
								<td  scope="col" align="left"><?php  echo round($itemOpeningBalance,2); ?></td>
								<td colspan="3"></td>
								<td  scope="col" align="left"><?php echo round($itemOpeningBalance,2); ?></td>
								<td ></td>
							</tr>
							<tr>
								<td >Last Year Profit</td>
								<td ></td>
								<td  scope="col" align="left"><b><?php  echo round(@$GrossProfit,2); ?></b></td>
								<td colspan="2"></td>
								<td ></td>
								<td  scope="col" align="left"><b><?php echo round(@$GrossProfit,2); ?></b></td>
								
							</tr>
							<tr>
								<td colspan="1">Row total</td>
								<td  scope="col" align="left"><?php echo round($op_dr_total+@$itemOpeningBalance,2); ?></td>
								<td  scope="col" align="left"><?php echo round(abs($op_cr_total)+abs(@$GrossProfit),2); ?></td>
								<td  scope="col" align="left"><?php echo round($tr_dr_total,2); ?></td>
								<td  scope="col" align="left"><?php echo round(abs($tr_cr_total),2); ?></td>
								<td  scope="col" align="left"><?php echo round($cl_dr_total+@$itemOpeningBalance,2); ?></td>
								<td  scope="col" align="left"><?php echo round(abs($cl_cr_total)+abs(@$GrossProfit),2); ?></td>
								
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
									<td  scope="col" align="left"><?php echo round($totalCr+$differenceInOpeningBalance)+abs(@$GrossProfit,2); ?></td>
									<?php } else { ?>
									<td  scope="col" align="left"><?php echo round($totalDr+$differenceInOpeningBalance+$itemOpeningBalance,2); ?></td>
									<td  scope="col" align="left"><?php echo round(abs($totalCr)+abs(@$GrossProfit),2); ?></td>
									<?php } ?>
								</tr>
							<?php }else if($show=="Closing"){ ?>
								<tr>
									<td >Opening Stocks</td>
									<td  scope="col" align="left"><?php  echo round($itemOpeningBalance,2); ?></td>
									<td ></td>
								</tr>
								<tr>
									<td >Last Year Profit</td>
									<td ></td>
									<td  scope="col" align="left"><b><?php  echo round(@$GrossProfit,2); ?></b></td>
									
								</tr>
								<tr>
									<td ><b>Total</b></td>
									<td  scope="col" align="left"><?php echo round($cl_dr_total+@$itemOpeningBalance,2); ?></td>
									<td  scope="col" align="left"><?php echo round(abs($cl_cr_total)+abs(@$GrossProfit),2); ?></td>
								</tr>
							<?php }else if($show=="Open_Close"){ ?>
							
									<tr>
									<td >Opening Stocks</td>
									<td  scope="col" align="left"><?php  echo round($itemOpeningBalance,2); ?></td>
									<td ></td>
									<td  scope="col" align="left"><?php  echo round($itemOpeningBalance,2); ?></td>
									<td ></td>
								</tr>
								<tr>
									<td >Last Year Profit</td>
									<td ></td>
									<td  scope="col" align="left"><b><?php  echo round(@$GrossProfit,2); ?></b></td>
									
								</tr>
								<tr>
									<td ><b>Total</b></td>
									<td  scope="col" align="left"><?php echo round($op_dr_total+@$itemOpeningBalance,2); ?></td>
									<td  scope="col" align="left"><?php echo round(abs($op_cr_total)+abs(@$GrossProfit),2); ?></td>
									<td  scope="col" align="left"><?php echo round($cl_dr_total+@$itemOpeningBalance,2); ?></td>
									<td  scope="col" align="left"><?php echo round(abs($cl_cr_total)+abs(@$GrossProfit),2); ?></td>
								</tr>
							<?php } ?>
							
					</tbody>
				<tfoot>
			</tfoot>
		</table>
				
<?php } ?>

