<table class='table' style='border:1px; ' border="1px" >
	<?php foreach($groupForPrint as $key=>$groupForPrintRow){ 
		 ?>
		<tr>
			<td>
				<a href="#" role='button' status='close' class="second_grp_name" second_grp_id='<?php  echo $key; ?>' style='color:black;'>
				<?php echo $groupForPrintRow['name']; ?>
					 </a>  
				
			</td>
			<td align="right">
				<?php if($groupForPrintRow['balance']!=0){
						if($groupForPrintRow['balance'] > 0){
							echo round(abs($groupForPrintRow['balance']),2); echo " Dr.";
						}else{
							echo round(abs($groupForPrintRow['balance']),2); echo " Cr.";
						}
					
					//$LeftTotal+=abs($groupForPrintRow['balance']);
				} ?>
			</td>
		</tr>
		
	<?php } ?>
</table>
