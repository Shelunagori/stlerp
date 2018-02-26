<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Balance Sheet');
$url_excel="/?".$url; 
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>Balance Sheet
				</div>
				<div class="actions">
					<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/Ledgers/excelBs/'.$url_excel.'',['class' =>'btn btn-sm green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
				</div>
			</div>
			<div class="portlet-body">
				<form method="get">
						<div class="row">
							<div class="col-md-3">
								<?php echo $this->Form->control('from_date',['class'=>'form-control input-sm date-picker from_date','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($from_date)),'required'=>'required']); ?>
							</div>
							<div class="col-md-3">
								<?php echo $this->Form->control('to_date',['class'=>'form-control input-sm date-picker to_date','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($to_date)),'required'=>'required']); ?>
							</div>
							<div class="col-md-3">
								<span class="input-group-btn">
								<button class="btn blue" type="submit">Go</button>
								</span>
							</div>	
						</div>
				</form>
				<?php if($from_date){ 
				$LeftTotal=0; $RightTotal=0; ?>
				<div class="row">
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
													<a href="#" role='button' status='close' class="group_name" group_id='<?php  echo $key; ?>' style='color:black;'>
														<?php echo $groupForPrintRow['name']; ?>
															 </a>
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
													<a href="#" role='button' status='close' class="group_name" group_id='<?php  echo $key; ?>' style='color:black;'>
														<?php echo $groupForPrintRow['name']; ?>
															 </a>
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
				</div>
				<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
	$(".group_name").die().live('click',function(e){
	   var current_obj=$(this);
	   var group_id=$(this).attr('group_id');
	  
	  if(current_obj.attr('status') == 'open')
	   {
			$('tr.row_for_'+group_id+'').remove();
			current_obj.attr('status','close');
		   $('table > tbody > tr > td> a').removeClass("group_a");
		   $('table > tbody > tr > td> span').removeClass("group_a");

		}
	   else
	   {  
		   var from_date = $('.from_date').val();
		   var to_date = $('.to_date').val();
		   
		   var url="<?php echo $this->Url->build(['controller'=>'Ledgers','action'=>'firstSubGroupsPnl']); ?>";
		   url=url+'/'+group_id +'/'+from_date+'/'+to_date,
//alert(url);
			$.ajax({
				url: url,
			}).done(function(response) {
				current_obj.attr('status','open');
				 current_obj.addClass("group_a");
				current_obj.closest('tr').find('span').addClass("group_a");
				$('<tr class="append_tr row_for_'+group_id+'"><td colspan="2">'+response+'</td></tr>').insertAfter(current_obj.closest('tr'));
			});			   
		}   
	});	
	
	$(".first_grp_name").die().live('click',function(e){ 
	   var current_obj=$(this);
	   var first_grp_id=$(this).attr('first_grp_id');
	  
	  if(current_obj.attr('status') == 'open')
	   {
			$('tr.row_for_'+first_grp_id+'').remove();
			current_obj.attr('status','close');
		   $('table > tbody > tr > td> a').removeClass("group_a");
		   $('table > tbody > tr > td> span').removeClass("group_a");

		}
	   else
	   {  
		   var from_date = $('.from_date').val();
		   var to_date = $('.to_date').val();
		   var url="<?php echo $this->Url->build(['controller'=>'Ledgers','action'=>'secondSubGroupsPnl']); ?>";
		   url=url+'/'+first_grp_id +'/'+from_date+'/'+to_date,
			$.ajax({
				url: url,
			}).done(function(response) {
				current_obj.attr('status','open');
				 current_obj.addClass("group_a");
				current_obj.closest('tr').find('span').addClass("group_a");
				$('<tr class="append_tr row_for_'+first_grp_id+'"><td colspan="2">'+response+'</td></tr>').insertAfter(current_obj.closest('tr'));
			});			   
		}   
	});	

	$(".second_grp_name").die().live('click',function(e){ 
	   var current_obj=$(this);
	   var second_grp_id=$(this).attr('second_grp_id');
	  
	  if(current_obj.attr('status') == 'open')
	   {
			$('tr.row_for_'+second_grp_id+'').remove();
			current_obj.attr('status','close');
		   $('table > tbody > tr > td> a').removeClass("group_a");
		   $('table > tbody > tr > td> span').removeClass("group_a");

		}
	   else
	   {  
		   var from_date = $('.from_date').val();
		   var to_date = $('.to_date').val();
		   var url="<?php echo $this->Url->build(['controller'=>'Ledgers','action'=>'ledgerAccountDataPnl']); ?>";
		   url=url+'/'+second_grp_id +'/'+from_date+'/'+to_date,
			
			$.ajax({
				url: url,
			}).done(function(response) {
				current_obj.attr('status','open');
				 current_obj.addClass("group_a");
				current_obj.closest('tr').find('span').addClass("group_a");
				$('<tr class="append_tr row_for_'+second_grp_id+'"><td colspan="2">'+response+'</td></tr>').insertAfter(current_obj.closest('tr'));
			});			   
		}   
	});
});	
</script>