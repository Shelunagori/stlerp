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
							<th scope="col"><b>Group</b></th>
							<th scope="col" ></th>
						</tr>
					</thead>
					<tbody>
							<?php $i=1; $totalDr=0; $totalCr=0; foreach($ClosingBalanceForPrint as $key=>$data){  
							 $tdsize=(sizeof($data));
							//pr($key); exit;
							?>
							<tr>		
								<td valign="top">
									<?php echo $group_name[$key]; ?>
								</td>
								<td>
								
										<table border="1">
											<thead>
												<tr>
													<th scope="col"><b>Ledgers</th>
													<th scope="col" style="text-align:center";><b>Debit</th>
													<th scope="col" style="text-align:center";><b>Credit</th>
												</tr>
											</thead>
											<tbody>
											<?php foreach($data as $dt){ ?>
												<tr>
													<td>
														<?php echo @$dt['name']; ?>
													</td>
													<?php if($dt['balance'] > 0){ ?>
													<td><?php echo $dt['balance'];
															?></td>
													<td><?php echo "-" ?></td>
													<?php }else{ ?>
													<td><?php echo "-"; ?></td>
													<td><?php echo abs($dt['balance']);  ?></td>
													<?php } ?>
													
												</tr>
												<?php } ?>
											</tbody>
										</table>
								
								</td>
							</tr>
							<?php } ?>
							
					</tbody>
				<tfoot>
			</tfoot>
		</table>