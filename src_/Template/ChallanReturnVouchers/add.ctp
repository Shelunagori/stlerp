<?php ?>
<style>
.table thead tr th {
    color: #FFF;
	background-color: #254b73;
}
</style>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Add Challan Return Vouchers</span>
		</div>
	</div>
	
	<?php if(!empty($challan)) { ?>
	<div class="portlet-body form">
		<?= $this->Form->create($challanReturnVoucher,['id'=> 'form_sample_3']) ?>
		
			<div class="form-body">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Challan No.</label>
							<br/>
							<?= h(($challan->ch1.'/CH-'.str_pad($challan->ch2, 3, '0', STR_PAD_LEFT).'/'.$challan->ch3.'/'.$challan->ch4)) ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Customer Name</label>
							<br/>
							
							<?php echo @$challan->customer->customer_name; ?>
						</div>
					</div>
					
					<div class="col-md-2 pull-right">
									<div class="form-group">
										<label class="control-label">Date</label>
										<br/>
										<?php echo date("d-m-Y"); ?>
									</div>
							
					</div>
				</div><br/>
				
			
			
			<div style="overflow: auto;">
			<table class="table tableitm" id="main_tb">
				<thead>
					<tr>
						<th width="50">Sr.No. </th>
						<th width="100" style="white-space: nowrap;">Items</th>
						<th width="100">Quantity</th>
						<th width="100">Rate</th>
						<th width="100">Amount</th>
						
					</tr>
				</thead>
				<tbody>
					<?php $total=0; $sum=0;
					$q=0; foreach ($challan->challan_rows as $challan_row){ 
					if($challan_row->challan_type=='Returnable'){
					?>
						<tr class="tr1">
							<td rowspan="2"><?php echo ++$q;  ?></td>
							<td >
								<?php echo $this->Form->input('challan_return_voucher_rows.'.$q.'.challan_row_id', ['label' => false,'type'=>'hidden','value' => @$challan_row->id]); ?>
								<?php echo $this->Form->input('challan_return_voucher_rows.'.$q.'.item_id', ['label' => false,'class' => 'invoice','type'=>'hidden','value' => @$challan_row->item_id]); ?>
								<?php echo $challan_row->item->name; ?>
							</td>
							<td >
								<?php echo $this->Form->input('challan_return_voucher_rows.'.$q.'.quantity', ['label' => false,'class' => 'qty','type'=>'text','value' => @$challan_row->quantity-@$return_qty[@$challan_row->id],'max'=>$challan_row->quantity]); ?>
								<?php //echo $challan_row->quantity; ?>
							</td>
							<td >
								<?php echo $this->Form->input('challan_return_voucher_rows.'.$q.'.rate', ['label' => false,'class' => 'rate','type'=>'hidden','value' => @$challan_row->rate]); ?>
								<?php echo $challan_row->rate; ?>
							</td>
							<td class="rate" >
								<?php echo $this->Form->input('challan_return_voucher_rows.'.$q.'.rate', ['label' => false,'class' => 'hideamt','type'=>'hidden','value' => @$challan_row->amount]); ?>
								<?php echo $this->Form->input('rate1', ['label' => false,'class' => 'showamt','type'=>'text','value' => @$challan_row->amount,'readonly']); ?>
								</td>
						</tr>
						<tr></tr>

					<?php $q++; $total=$total+$sum; } } ?>

				</tbody>
				
			</table>
			</div>
		</div>
		
		<div class="form-actions">
			<div class="row">
				<div class="col-md-3">
					<?= $this->Form->button(__('Create Voucher'),['class'=>'btn btn-primary','id'=>'add_submit','type'=>'Submit']) ?>
				</div>
			</div>
		</div>
	</div>	
	
	<?php } ?> <?= $this->Form->end() ?>
			
</div>	
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>
$(document).ready(function() { 
	
	$('.qty').die().live("keyup",function() { 
		rename_rows();
    });
	
		function rename_rows(){
		var i=0;
			$("#main_tb tbody tr.tr1").each(function(){
				var qty=$(this).find('.qty').val();
				var rate=$(this).find('.rate').val();
				
				var amt=rate*qty;
				$(this).find("td:nth-child(5) input.hideamt").val(round(amt,2))
				$(this).find("td:nth-child(5) input.showamt").val(round(amt,2))
				
				//alert(amt);
			});
		}
});
</script>