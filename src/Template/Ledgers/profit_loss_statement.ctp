<?php
$this->set('title', 'Profit & Loss Statement');
$url_excel="/?".$url; 
?>
<div class="row">
	<div class="col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>Profit & Loss Statement
				</div>
				<div class="actions">
					<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/Ledgers/excelPnl/'.$url_excel.'',['class' =>'btn btn-sm green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
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
				</div>
				
				<div class="row">
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
				</div>
				
				<div class="row">
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