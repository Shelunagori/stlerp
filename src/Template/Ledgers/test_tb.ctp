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
							<th scope="col" >First Sub Group</th>
							<th scope="col" >Second Sub Group</th>
							<th scope="col" >Ledgers</th>
							<th scope="col" >Amount</th>
						</tr>
					</thead>
					<tbody>
							<?php
							$groupRowspan=[]; $firstgroupRowspan=[]; $secondgroupRowspan=[];
							foreach($AccountLedgers as $AccountLedger){
								if(@$ClosingBalanceForPrint[$AccountLedger->id]!=0){
									@$groupRowspan[$AccountLedger->account_second_subgroup->account_first_subgroup->account_group->id]=@$groupRowspan[$AccountLedger->account_second_subgroup->account_first_subgroup->account_group->id] + 1;
								
									@$firstgroupRowspan[$AccountLedger->account_second_subgroup->account_first_subgroup->id]=@$firstgroupRowspan[$AccountLedger->account_second_subgroup->account_first_subgroup->id] + 1;
									
									@$secondgroupRowspan[$AccountLedger->account_second_subgroup->id]=@$secondgroupRowspan[$AccountLedger->account_second_subgroup->id] + 1;
								}
							}
							//pr($firstgroupRowspan); exit;
							$groups=[]; $firstgroups=[]; $secondgroups=[];
							foreach($AccountLedgers as $AccountLedger){
							?>
							<?php if(@$ClosingBalanceForPrint[$AccountLedger->id]!=0){ ?>
							<tr>
								<?php if(!in_array($AccountLedger->account_second_subgroup->account_first_subgroup->account_group->id, $groups)){ ?>
								<td valign="top" rowspan="<?php echo $groupRowspan[$AccountLedger->account_second_subgroup->account_first_subgroup->account_group->id]; ?>">
									<?php echo $AccountLedger->account_second_subgroup->account_first_subgroup->account_group->name; ?>
								</td>
								<?php 
								$groups[]=$AccountLedger->account_second_subgroup->account_first_subgroup->account_group->id;
								} ?>
								
								<?php if(!in_array($AccountLedger->account_second_subgroup->account_first_subgroup->id, $firstgroups)){ ?>
								<td valign="top" rowspan="<?php echo $firstgroupRowspan[$AccountLedger->account_second_subgroup->account_first_subgroup->id]; ?>">
									<?php echo $AccountLedger->account_second_subgroup->account_first_subgroup->name; ?>
								</td>
								<?php 
								$firstgroups[]=$AccountLedger->account_second_subgroup->account_first_subgroup->id;
								} ?>
								
								<?php if(!in_array($AccountLedger->account_second_subgroup->id, $secondgroups)){ ?>
								<td valign="top" rowspan="<?php echo $secondgroupRowspan[$AccountLedger->account_second_subgroup->id]; ?>">
									<?php echo $AccountLedger->account_second_subgroup->name; ?>
								</td>
								<?php 
								$secondgroups[]=$AccountLedger->account_second_subgroup->id;
								} ?>
								
								
								
								
								<td valign="top">
									<?php echo $AccountLedger->name; ?>
								</td>
								<?php if($ClosingBalanceForPrint[$AccountLedger->id] > 0){ ?>
								<td valign="top">
									<?= h($this->Number->format(@$ClosingBalanceForPrint[$AccountLedger->id],[ 'places' => 2]))?> Dr.
								</td>
								<?php }else{ ?>
								<td valign="top">
									<?= h($this->Number->format(abs($ClosingBalanceForPrint[$AccountLedger->id]),[ 'places' => 2]))?> Cr.
									
								</td>
								<?php } ?>
								
							</tr>
							<?php } ?>
							<?php } ?>
							
					</tbody>
				<tfoot>
			</tfoot>
		</table>