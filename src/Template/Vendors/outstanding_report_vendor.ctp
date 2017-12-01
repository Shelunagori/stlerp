<style>
table td, table th{
    white-space: nowrap;
	font-size:12px !important;
}
.clrRed{
	color:red;
}
</style>
<div class="portlet box red">
	<div class="portlet-title">
		<div class="caption">Outstandings for Vendor for <?php echo $to_send['tdate']; ?></div>
	</div>
	<div class="portlet-body">
		<div class="table-toolbar">
			<div class="row">
				<div class="col-md-6"></div>
				<div class="col-md-6" >
					<div class="btn-group" style="float:right;">
						<?php $url=json_encode($to_send, true);
							 echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/Vendors/Vendor-Export-Excel/'.$url.'',['class' =>'btn  green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive" >
			<table class="table table-bordered">
			<thead>
			<tr>
				<th>#</th>
				<th>Customer</th>
				<th>Payment Term</th>
				<th><?php echo $to_send['range0'].' - '.$to_send['range1'].' Days'; ?></th>
				<th><?php echo $to_send['range2'].' - '.$to_send['range3'].' Days'; ?></th>
				<th><?php echo $to_send['range4'].' - '.$to_send['range5'].' Days'; ?></th>
				<th><?php echo $to_send['range6'].' - '.$to_send['range7'].' Days'; ?></th>
				<th><?php echo ' > '.$to_send['range7'].' Days'; ?></th>
				<th>On Account</th>
				<th>Total Outstanding</th>
				<th>No-Due</th>
				<th>Closing Balance</th>
			</tr>
			</thead>
			<tbody>
			<?php 
			$sr=0; $ClosingBalance=0; 
			$ColumnOnAccount=0; $ColumnOutStanding=0; $ColumnNoDue=0; $ColumnClosingBalance=0;
			foreach($LedgerAccounts as $LedgerAccount){ ?>
			<tr>
				<td><?php echo ++$sr; ?></td>
				<td style=" white-space: normal; width: 200px; "><?php echo $LedgerAccount->name.' <br/> '.$LedgerAccount->alias; ?></td>
				<td><?php echo $VendorPaymentTerms[$LedgerAccount->id].' Days'; ?></td>
				<td>
					<?php if(@$Outstanding[$LedgerAccount->id]['Slab1'] > 0){
						echo '<span class="clrRed">'.@$Outstanding[$LedgerAccount->id]['Slab1'].'</span>';
					}else{
						echo '<span>'.@$Outstanding[$LedgerAccount->id]['Slab1'].'</span>';
					} ?>
				</td>
				<td>
					<?php if(@$Outstanding[$LedgerAccount->id]['Slab2'] > 0){
						echo '<span class="clrRed">'.@$Outstanding[$LedgerAccount->id]['Slab2'].'</span>';
					}else{
						echo '<span>'.@$Outstanding[$LedgerAccount->id]['Slab2'].'</span>';
					} ?>
				</td>
				<td>
					<?php if(@$Outstanding[$LedgerAccount->id]['Slab3'] > 0){
						echo '<span class="clrRed">'.@$Outstanding[$LedgerAccount->id]['Slab3'].'</span>';
					}else{
						echo '<span>'.@$Outstanding[$LedgerAccount->id]['Slab3'].'</span>';
					} ?>
				</td>
				<td>
					<?php if(@$Outstanding[$LedgerAccount->id]['Slab4'] > 0){
						echo '<span class="clrRed">'.@$Outstanding[$LedgerAccount->id]['Slab4'].'</span>';
					}else{
						echo '<span>'.@$Outstanding[$LedgerAccount->id]['Slab4'].'</span>';
					} ?>
				</td>
				<td>
					<?php if(@$Outstanding[$LedgerAccount->id]['Slab5'] > 0){
						echo '<span class="clrRed">'.@$Outstanding[$LedgerAccount->id]['Slab5'].'</span>';
					}else{
						echo '<span>'.@$Outstanding[$LedgerAccount->id]['Slab5'].'</span>';
					} ?>
				</td>
				
				<td>
				<?php 
					echo @$Outstanding[$LedgerAccount->id]['OnAccount']; 
					@$ColumnOnAccount+=@$Outstanding[$LedgerAccount->id]['OnAccount'];
				?>
				</td>
				<td>
				<?php $TotalOutStanding=@$Outstanding[$LedgerAccount->id]['Slab1']+@$Outstanding[$LedgerAccount->id]['Slab2']+@$Outstanding[$LedgerAccount->id]['Slab3']+@$Outstanding[$LedgerAccount->id]['Slab4']+@$Outstanding[$LedgerAccount->id]['Slab5']+@$Outstanding[$LedgerAccount->id]['OnAccount']; ?>
				<?php 
				if($TotalOutStanding>0){
					echo '<span class="clrRed"><b>'.@$TotalOutStanding.'</b></span>';
				}elseif($TotalOutStanding<0){
					echo '<span>'.@$TotalOutStanding.'</span>';
				} ?>
				<?php
					@$ColumnOutStanding+=@$TotalOutStanding;
				?>
				</td>
				<td>
					<?php 
					echo @$Outstanding[$LedgerAccount->id]['NoDue'];
					@$ColumnNoDue+=@$Outstanding[$LedgerAccount->id]['NoDue'];
					?>
				</td>
				<td>
				<?php $ClosingBalance=@$Outstanding[$LedgerAccount->id]['Slab1']+@$Outstanding[$LedgerAccount->id]['Slab2']+@$Outstanding[$LedgerAccount->id]['Slab3']+@$Outstanding[$LedgerAccount->id]['Slab4']+@$Outstanding[$LedgerAccount->id]['Slab5']+@$Outstanding[$LedgerAccount->id]['NoDue']+@$Outstanding[$LedgerAccount->id]['OnAccount']; ?>
				<?php if($ClosingBalance!=0){
					echo $ClosingBalance;
				} ?>
				<?php
					@$ColumnClosingBalance+=$ClosingBalance;
				?>
				</td>
			</tr>
			<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="8"><div  align="right">TOTAL</div></th>
					<th><?php echo $ColumnOnAccount; ?></th>
					<th><?php echo $ColumnOutStanding; ?></th>
					<th><?php echo $ColumnNoDue; ?></th>
					<th><?php echo $ColumnClosingBalance; ?></th>
				</tr>
			</tfoot>
			</table>
		</div>
	</div>
</div>