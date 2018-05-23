<html>
<head>
<style>
td > p { margin-top : 0px;  }
p { margin-top : 0px;  }
</style>
</head>
<body>

<table width="100%" style="font-family:Palatino Linotype;">
		<tr>
			<td  align="left" style="font-size: 30px;font-weight: bold;color:#000000;"><?php echo $company ?>
			</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td>
				<?php 
					  $cname = $PurchaseOrders->vendor->company_name;	?>
				<?php echo $cname; ?>
			</td>
		</tr>
		<tr>
			<td>
			
				<?php $add = $this->Text->autoParagraph($PurchaseOrders->vendor->address);
						echo $add;
				?>
			
			</td>
		</tr>
		<tr>
			<td>Dear Sir,<?php echo "<br/>"; ?> </td> 
		</tr>
		<tr>
			<td>
			</td>
		</tr>
		<tr>
			<td>Our delivery for following purchase orders is due and we request to confirm the delivery at earliest.</td>
		</tr>
		
		<tr>
			<td>
					<table border="1" width="80%">
						<tr>
								<th>S.No.</th>
								<th>Our PO No.</th>
								<th>PO Date</th>
								<th>Delivery Date</th>
								<th>Overdue Days</th>
								
						</tr>
						<?php $i=1; foreach($po_no as $key=>$po_no){  
						
						?>
							<?php if($due_day[$key] >= 0){ ?>
							<tr>
								<td width="5%" style="text-align:center;"><?php echo $i++; ?></td>
								<td style="text-align:center;">
								<a href="<?php echo $url.'/'.base64_encode($key); ?>"><?php echo $po_no; ?></a>
								</td>
								<td  width="20%"  align="center"><?php echo $po_date[$key]; ?></td>
								<td  width="20%"  align="center"><?php echo $delevery_date[$key]; ?></td>
								<td   width="20%" align="center"><?php echo $due_day[$key]; ?></td>
							</tr>
							<?php } ?>	
						<?php } ?>
					</table>
			</td>
		</tr>
		<tr>
			<td>
			</td>
		</tr>
		
		<tr>
			<td> We are in immediate requirement of the material. In case you have any further delay please write back to us.<?php echo "<br/>"; ?>
			</td>
		</tr>
		<tr>
		<td>
			</td>
		</tr>
		
		<tr>
			<td>Regards,
			</td>
		</tr>
		<tr>
		<td>
			</td>
		</tr>
		<tr>
			<td>Purchase Executive
			</td>
		</tr>
		<tr>
		<td>Email - purchase@mogragroup.com</td>
		</tr>
		<tr>
			<td>Tel - 8696029999 </td>
		</tr>
		<tr>
		<td>
			</td>
		</tr>
		
		
</table>


</body>


</html>