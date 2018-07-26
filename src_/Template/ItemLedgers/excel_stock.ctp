<?php 

	$date= date("d-m-Y"); 
	$time=date('h:i:a',time());

	$filename="Stock_report_".$date.'_'.$time;

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
			<td colspan="6" align="center">
				 Stock Report
				<?php if(!empty($to_date)){
					 echo date('d-m-Y',strtotime($to_date));
				} ?>
			</td>
		</tr>
		<?php if(!empty($ItemCategories) || !empty($ItemsName) || !empty($itemGroups) || !empty($ItemSubGroups) || !empty($stock)){ ?>
		<tr>
			<td align="left" width="10%">
				Category :
				<?php if(!empty($ItemCategories)){ ?><b><?php echo $ItemCategories['name'];  } ?></b>
			</td>
			<td align="left">
				Item : 
				<?php if(!empty($ItemsName)){ ?><b><?php echo $ItemsName['name'];  } ?></b>
			</td>
			<td align="left" colspan="2" width="10%">
				Item Group :
				<?php if(!empty($itemGroups)){ ?><b><?php echo $itemGroups['name'];  } ?></b>
			</td>
			<td align="left" width="10%" >
				Item Sub Group : 
				<?php if(!empty($ItemSubGroups)){ ?><b><?php echo $ItemSubGroups['name'];  } ?></b>
			</td>
			<td align="left" width="10%" >
				Stock :
				<?php if(!empty($stock_status)){ ?><b><?php echo $stock_status;  } ?></b>
			</td>
		</tr>	
		<?php } ?>		
		<tr>
			<th>Sr. No.</th>
			<th>Item</th>
			<th>Current Stock</th>
			<th>Unit</th>
			<th style="text-align:right;">Unit Rate</th>
			<th style="text-align:right;">Amount</th>
		</tr>
		</thead>
		<tbody>
			<?php $total_inv=0; $totalColumn=0; $page_no=0;  $RowTotal=0;
			foreach ($item_stocks as $key=> $item_stock){
			if($item_stock!=0){
				if(@$in_qty[$key]==0){ 
					$per_unit=@$item_rate[$key];
				}else{
					$per_unit=@$item_rate[$key]/@$in_qty[$key];
				}
			}else{ 
				if(@$in_qty[$key]==0){ 
					$per_unit=@$item_rate[$key];
				}else{
					$per_unit=@$item_rate[$key]/@$in_qty[$key];
				}
				
			}
			
			$amount=@$item_stock*abs($per_unit);
			$total_inv+=$amount;
			?>
				
			<tr class="main_tr" id="<?= h($key) ?>">
				<td><?= h(++$page_no) ?></td>
				<td width="90%" id="<?= h($key) ?>" class="loop_class"><button type="button"  class="btn btn-xs tooltips revision_hide show_data" id="<?= h($key) ?>" value="" style="margin-left:5px;margin-bottom:2px;"><i class="fa fa-plus-circle"></i></button>
					<button type="button" class="btn btn-xs tooltips revision_show" style="margin-left:5px;margin-bottom:2px; display:none;"><i class="fa fa-minus-circle"></i></button>
					&nbsp;&nbsp;<?= h($items_names[$key]) ?><div class="show_ledger"></div></td>
				<td><?= h($item_stock) ?></td>
				<td><?= h($items_unit_names[$key]) ?></td>
				<td align="right">
					<?php 
					//pr($key);
					//pr(@$itemSerialNumberStatus[$key]);
					 if(@$itemSerialNumberStatus[$key]==1){
						if($item_stock > 0){
							//echo @$unitRate[$key]."yes";
							echo $this->Number->format(@$unitRate[$key],['places'=>2]);
							$RowTotal=@$unitRate[$key]*$item_stock;
						}else{
							echo '0';
							$RowTotal=0;
						}
					}else{
						if($item_stock > 0){
							$UR=@$sumValue[$key]/$item_stock;
							echo $this->Number->format($UR,['places'=>2]);
							$RowTotal=$UR*@$item_stock;
						}else{
							echo '0';
							$RowTotal=0;
						}
					} 
					
					?>
				</td>
				<td align="right">
					<?php
						echo $this->Number->format($RowTotal,['places'=>2]);
						$totalColumn+=$RowTotal;
					?>
				</td>
			</tr>
			
			<?php } ?>
			<?php if($to_date == date('d-m-Y') && !($stock_status== "All") ){ ?>
			<?php $page_no1=$page_no; foreach($ItemDatas as $key=>$ItemData){ ?>
			
			<tr class="main_tr1" id="<?= h($key) ?>">
				<td><?= h(++$page_no1) ?></td>
				<td width="90%" id="<?= h($key) ?>" class="loop_class"><button type="button"  class="btn btn-xs tooltips revision_hide1 show_data1" id="<?= h($key) ?>" value="" style="margin-left:5px;margin-bottom:2px;"><i class="fa fa-plus-circle"></i></button>
					<button type="button" class="btn btn-xs tooltips revision_show1" style="margin-left:5px;margin-bottom:2px; display:none;"><i class="fa fa-minus-circle"></i></button>
					&nbsp;&nbsp;<?= h($ItemData) ?><div class="show_ledger1"></div></td>
				
				<td><?= h(0) ?></td>
				
				<td><?= h($ItemUnits[$key]) ?></td>
				<td align="right"><?= h($this->Number->format(0,['places'=>2])) ?></td>
				<td align="right"><?= h($this->Number->format(0,['places'=>2])) ?></td>
			</tr>
			<?php } } ?>
			<tr>
				<td colspan="5" align="right">Total</td>
				<td align="right"><?= h($this->Number->format($totalColumn,['places'=>2])) ?></td>
			</tr>
		</tbody>
</table>