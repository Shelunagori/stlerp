<table class='table' style='border:1px; ' border="1px" >
	<?php foreach($ClosingBalanceForPrint as $key=>$data){ 
		 ?>
		<tr>
			<td width="20%">
				<a href="#" role='button' status='close' class="second_grp_name" second_grp_id='<?php  echo $key; ?>' style='color:black;'>
				<?php echo $data['name']; ?>
					 </a>  
				
			</td>
				<?php if($OpeningBalanceForPrint[$key]['balance'] > 0){ ?>
								<td class="third" width="13%"><?php echo $OpeningBalanceForPrint[$key]['balance'];
										?></td>
								<td  class="third" width="13%"><?php echo "-" ?></td>
								<?php }else{ ?>
								<td class="third" width="13%"><?php echo "-"; ?></td>
								<td class="third" width="13%"><?php echo abs($OpeningBalanceForPrint[$key]['balance']);  ?></td>
								<?php } ?>


								
								<td class="third" width="13%"><?php echo abs($TransactionDr[$key]['balance']);
										?></td>
								
								<td class="third" width="13%"><?php echo abs($TransactionCr[$key]['balance']);
									  ?></td>
								
								


								<?php if($data['balance'] > 0){ ?>
								<td class="third" width="13%"><?php echo $data['balance'];
									@$totalDr=@$totalDr+$data['balance'];	?></td>
								<td class="third" width="13%"><?php echo "-" ?></td>
								<?php }else{ ?>
								<td class="third" width="13%"><?php echo "-"; ?></td>
								<td class="third" width="13%"><?php echo abs($data['balance']);  
									@$totalCr=@$totalCr+abs($data['balance']);  ?></td>
								<?php } ?>
							
		</tr>
		
	<?php } ?>
</table>
