<?php 

	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="Profit_and_Loss".$date.'_'.$time;

	header ("Expires: 0");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/vnd.ms-excel");
	header ("Content-Disposition: attachment; filename=".$filename.".xls");
	header ("Content-Description: Generated Report" );  

?>

		<?php $LeftTotal=0; $RightTotal=0; ?>
					<table class="table table-bordered" width="60%">
						<thead>
							<tr style="background-color: #c4ffbd;">
									<td width="50%"><b>Particulars</b></td>
									<?php if($st_year_id==1 || $st_year_id==2 ||$st_year_id==3){ ?>
										<td width="25%" align="right"><b>As at 31st March, 2018</b></td>
									<?php }else{ ?>
										<td width="25%" align="right"><b>As at 31st March, 2019</b></td>
										<td width="25%" align="right"><b>As at 31st March, 2018</b></td>
									<?php } ?>
							</tr>
						</thead>
						<tbody>
							
							<?php if($openingValue<0) { ?>
								<tr>
									<td>Opening Stock</td>
									<td align="right">
										<?php 
											echo $openingValue;
											$RightTotal+=$openingValue;
										?>
									</td>
									<td></td>
								</tr>
							<?php } ?>
							<?php foreach($groupForPrint as $key=>$groupForPrintRow){ 
								if($groupForPrintRow['balance']<0){ ?>
								<tr>
									<td>
										<a href="#" role='button' status='close' class="group_name" group_id='<?php  echo $key; ?>' style='color:black;'>
										<?php echo $groupForPrintRow['name']; ?>
											 </a>  
										
									</td>
									<?php if($st_year_id==1 || $st_year_id==2 ||$st_year_id==3){ ?>
										<td align="right">
										<?php if($groupForPrintRow['balance']!=0){
											echo abs($groupForPrintRow['balance']); 
											$RightTotal+=abs($groupForPrintRow['balance']); 
										} ?>
									</td>
									<?php }else{ ?>
									<td align="right">
										<?php if($groupForPrintRow['balance']!=0){
											echo abs($groupForPrintRow['balance']); 
											$RightTotal+=abs($groupForPrintRow['balance']); 
										} ?>
									</td>
									<td></td>
									<?php } ?>
								</tr>
								<?php } ?>
							<?php } ?>
							<tr>
								<td>Closing Stock</td>
								<?php if($st_year_id==1 || $st_year_id==2 ||$st_year_id==3){ ?>
								<td align="right">
									<?php 
									echo round($closingValue,2); 
									$RightTotal+=$closingValue; 
									?>
								</td>
								<?php }else{ ?>
								<td align="right">
									<?php 
									echo round($closingValue,2); 
									$RightTotal+=$closingValue; 
									?>
								</td>
								<td></td>
								<?php } ?>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td><b>Total Revenue</b></td>
								<?php if($st_year_id==1 || $st_year_id==2 ||$st_year_id==3){ ?>
									<td align="right"><b><?php echo round($RightTotal,2); ?></b></td>
								<?php }else{ ?>
									<td align="right"><b><?php echo round($RightTotal,2); ?></b></td>
									<td></td>
								<?php } ?>
							</tr>
						</tfoot>
					</table>
					
					
					
					<table class="table table-bordered" width="60%">
						<thead>
							<tr style="background-color: #c4ffbd;">
									<td width="50%"><b>Particulars</b></td>
									<?php if($st_year_id==1 || $st_year_id==2 ||$st_year_id==3){ ?>
										<td width="25%" align="right"><b>As at 31st March, 2018</b></td>
									<?php }else{ ?>
										<td width="25%" align="right"><b>As at 31st March, 2019</b></td>
										<td width="25%" align="right"><b>As at 31st March, 2018</b></td>
									<?php } ?>
							</tr>
						</thead>
						<tbody>
							
							<?php if($openingValue>=0) { ?>
								<tr>
									<td>Opening Stock</td>
									<?php if($st_year_id==1 || $st_year_id==2 ||$st_year_id==3){ ?>
										<td align="right">
											<?php 
											echo round($openingValue,2);
											$LeftTotal+=$openingValue;
											?>
										</td>
									<?php }else{ ?>
										<td align="right">
											<?php 
											echo round($openingValue,2);
											$LeftTotal+=$openingValue;
											?>
										</td>
										<td></td>
									<?php } ?>
								</tr>
							<?php } ?>
							<?php foreach($groupForPrint as $key=>$groupForPrintRow){ 
								if($groupForPrintRow['balance']>0){ ?>
								<tr>
									<td>
										<a href="#" role='button' status='close' class="group_name" group_id='<?php  echo $key; ?>' style='color:black;'>
										<?php echo $groupForPrintRow['name']; ?>
											 </a>  
										
									</td>
									<?php if($st_year_id==1 || $st_year_id==2 ||$st_year_id==3){ ?>
									<td align="right">
										<?php if($groupForPrintRow['balance']!=0){
														
											echo abs($groupForPrintRow['balance']);
											$LeftTotal+=abs($groupForPrintRow['balance']);
										} ?>
									</td>
									<?php }else{ ?>
									<td align="right">
										<?php if($groupForPrintRow['balance']!=0){
														
											echo abs($groupForPrintRow['balance']);
											$LeftTotal+=abs($groupForPrintRow['balance']);
										} ?>
									</td>
									<td></td>
									<?php } ?>
								</tr>
								<?php } ?>
							<?php } ?>
							
						</tbody>
						<tfoot>
							<tr>
								<td><b>Total Expenses</b></td>
								<?php if($st_year_id==1 || $st_year_id==2 ||$st_year_id==3){ ?>
									<td align="right"><b><?php echo round($LeftTotal,2); ?></b></td>
								<?php }else{ ?>
									<td align="right"><b><?php echo round($LeftTotal,2); ?></b></td>
									<td></td>
								<?php } ?>
							</tr>
						</tfoot>
					</table>
					
					<table class="table table-bordered" width="60%">
						<thead>
						<?php 
							$totalDiff=$RightTotal-$LeftTotal;
							if($totalDiff>=0){  ?>
								<tr style="">
									<td width="50%"><b>Gross Profit</b></td>
									<?php if($st_year_id==1 || $st_year_id==2 ||$st_year_id==3){ ?>
										<td width="25%" align="right"><?php echo round($totalDiff,2); $LeftTotal+=$totalDiff; ?></td>
									<?php }else{ ?>
										<td width="25%" align="right"><?php echo round($totalDiff,2); $LeftTotal+=$totalDiff; ?></td>
										<td width="25%"></td>
									<?php } ?>
								</tr>
						<?php } else if($totalDiff<0){ ?>
								<tr style="">
									<td width="50%"><b>Gross Loss</b></td>
									<?php if($st_year_id==1 || $st_year_id==2 ||$st_year_id==3){ ?>
										<td width="25%" align="right"><?php echo round(abs($totalDiff),2); $RightTotal+=abs($totalDiff); ?></td>
									<?php }else{ ?>
										<td width="25%" align="right"><?php echo round(abs($totalDiff),2); $RightTotal+=abs($totalDiff); ?></td>
										<td width="25%"></td>
									<?php } ?>
								</tr>
						<?php } ?>
						</thead>
					</table>