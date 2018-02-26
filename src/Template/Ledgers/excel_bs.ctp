<?php 

	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="Balance_Sheet".$date.'_'.$time;

	header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/vnd.ms-excel");
	header ("Content-Disposition: attachment; filename=".$filename.".xls");
	header ("Content-Description: Generated Report" );  

?>

		<?php $LeftTotal=0; $RightTotal=0; ?>
						<table class="table table-bordered">
						<thead>
							<tr style="background-color: #c4ffbd;">
								<td style="width: 50%;">
									<table width="100%">
										<tbody>
											<tr>
												<td><b>Particulars</b></td>
												<td align="right"><b>Balance</b></td>
											</tr>
										</tbody>
									</table>
								</td>
								<td>
									<table width="100%">
										<tbody>
											<tr>
												<td><b>Particulars</b></td>
												<td align="right"><b>Balance</b></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<table width="100%">
										<tbody>
											<?php foreach($groupForPrint as $key=>$groupForPrintRow){ 
												if(($groupForPrintRow['balance']<0)){ ?>
												<tr>
													<td>
													
														<?php echo $groupForPrintRow['name']; ?>
															 
													</td>
													<td align="right">
														<?php if($groupForPrintRow['balance']!=0){
															echo round(abs($groupForPrintRow['balance']),2);
															$LeftTotal+=abs($groupForPrintRow['balance']);
														} ?>
													</td>
												</tr>
												<?php } ?>
											<?php } ?>
										</tbody>
									</table>
								</td>
								<td>
									<table width="100%">
										<tbody>
											<?php foreach($groupForPrint as $key=>$groupForPrintRow){ 
												if(($groupForPrintRow['balance']>0)){ ?>
												<tr>
													<td>
													
														<?php echo $groupForPrintRow['name']; ?>
															
													</td>
													<td align="right">
														<?php if($groupForPrintRow['balance']!=0){
															echo round(abs($groupForPrintRow['balance']),2); 
															$RightTotal+=abs($groupForPrintRow['balance']); 
														} ?>
													</td>
												</tr>
												<?php } ?>
											<?php } ?>
												<tr>
													<td>Closing Stock</td>
													<td align="right">
														<?php 
														echo round($closingValue,2); 
														$RightTotal+=$closingValue; 
														?>
													</td>
												</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<?php if($GrossProfit!=0){ ?>
							<tr>
								<td>
									<?php 
									if($GrossProfit>0){ ?>
									<table width="100%">
										<tbody>
											<tr>
												<td>Profit & Loss A/c</td>
												<td align="right">
													<?php 
													echo round($GrossProfit,2);
													$LeftTotal+=abs($GrossProfit);
													?>
												</td>
											</tr>
										</tbody>
									</table>
									<?php } ?>
								</td>
								<td>
									<?php if($GrossProfit<0){ ?>
									<table width="100%">
										<tbody>
											<tr>
												<td>Profit & Loss A/c</td>
												<td align="right">
													<?php 
													echo abs($GrossProfit); 
													$RightTotal+=abs($GrossProfit);
													?>
												</td>
											</tr>
										</tbody>
									</table>
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
							<?php if($differenceInOpeningBalance!=0){ ?>
							<tr>
								<td>
									<?php if($differenceInOpeningBalance>0){ ?>
									<table width="100%">
										<tbody>
											<tr>
												<td><span style="color:red;">Difference In Opening Balance</span></td>
												<td align="right">
													<?php 
													echo abs($differenceInOpeningBalance); 
													$LeftTotal+=abs($differenceInOpeningBalance);
													?>
												</td>
											</tr>
										</tbody>
									</table>
									<?php } ?>
								</td>
								<td>
									<?php if($differenceInOpeningBalance<0){ ?>
									<table width="100%">
										<tbody>
											<tr>
												<td><span style="color:red;">Difference In Opening Balance</span></td>
												<td align="right">
													<?php 
													echo abs($differenceInOpeningBalance); 
													$RightTotal+=abs($differenceInOpeningBalance);
													?>
												</td>
											</tr>
										</tbody>
									</table>
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td>
									<table width="100%">
										<tbody>
											<tr>
												<td><b>Total</b></td>
												<td align="right"><b><?php echo round($LeftTotal,2); ?></b></td>
											</tr>
										</tbody>
									</table>
								</td>
								<td>
									<table width="100%">
										<tbody>
											<tr>
												<td><b>Total</b></td>
												<td align="right"><b><?php echo round($RightTotal,2); ?></b></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tfoot>
					</table>