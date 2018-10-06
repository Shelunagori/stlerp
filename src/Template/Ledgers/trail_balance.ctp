<?php 
$url_excel="/?".$url;



?>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Trial Balance</span>
		</div>
		<div class="actions">
			<?php  echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/Ledgers/testTb/'.$url_excel.'',['class' =>'btn btn-sm green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
		</div>
		<div class="portlet-body form">
	<form method="GET" >
				<table class="table table-condensed" >
				<tbody>
					<tr>
					<td>
						<div class="row">
							
							<div class="col-md-3">
								<?php echo $this->Form->input('From', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker from_date','data-date-format' => 'dd-mm-yyyy','value' => @date('d-m-Y', strtotime($from_date)),'data-date-start-date' => date("d-m-Y",strtotime($financial_year->date_from)),'data-date-end-date' => date("d-m-Y",strtotime($financial_year->date_to))]); ?>
								
							</div>
							<div class="col-md-3">
								<?php echo $this->Form->input('To', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker to_date','data-date-format' => 'dd-mm-yyyy','value' => @date('d-m-Y', strtotime($to_date)),'data-date-start-date' => date("d-m-Y",strtotime($financial_year->date_from)),'data-date-end-date' => date("d-m-Y",strtotime($financial_year->date_to))]); ?>
							</div>
							<div class="col-md-3">
								<?php $showData=['All'=>'All','Closing'=>'Closing','Open_Close'=>'Opening/Closing','Last'=>'Last Year']; ?>
								<?php echo $this->Form->input('show', ['options'=>$showData,'label' => false,'class' => 'form-control input-sm show_data','value'=>@$show]); ?>
							</div>
							<div class="col-md-3">
							<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
							</div>
							
						</div>
					</td>
					</tr>
				</tbody>
			</table>
	</form>
		<!-- BEGIN FORM-->
<?php if(!empty($ClosingBalanceForPrint)){ ?>
<table class="table table-bordered table-hover table-condensed" width="100%">
					<thead>
						<tr>
							<th scope="col"></th>
							<?php if($show=="All"){ ?>
								<th scope="col" colspan="2" style="text-align:center";><b>Opening Balance</th>
								<th scope="col" colspan="2" style="text-align:center";><b>Transactions</th>
								<th scope="col" colspan="2" style="text-align:center";><b>Closing balance</th>
							<?php }else if($show=="Closing"){ ?>
								<th scope="col" colspan="2" style="text-align:center";><b>Closing balance</th>
							<?php }else if($show=="Open_Close"){ ?>
								<th scope="col" colspan="2" style="text-align:center";><b>Opening Balance</th>
								<th scope="col" colspan="2" style="text-align:center";><b>Closing balance</th>
							<?php }else if($show=="Last") {  
									if($st_year_id != 1 || $st_year_id !=2 || $st_year_id != 3){ ?>
								<th scope="col" colspan="2" style="text-align:center";><b>Closing balance</th>
								<th scope="col" colspan="2" style="text-align:center";><b>Last Year Closing balance</th>
							<?php } }?>
						</tr>
						<tr>
								<th scope="col"><b>Ledgers</th>
							<?php if($show=="All"){ ?>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
							<?php }else if($show=="Closing"){ ?>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
							<?php }else if($show=="Open_Close"){ ?>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
							<?php }else if($show=="Last"){ ?>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
								<th scope="col" style="text-align:center";><b>Debit</th>
								<th scope="col" style="text-align:center";><b>Credit</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
							<?php $op_dr_total=0; $op_cr_total=0;$tr_dr_total=0; $tr_cr_total=0;$cl_dr_total=0; $cl_cr_total=0; $i=1; $totalDr=0; $totalCr=0; foreach($ClosingBalanceForPrint as $key=>$data){  ?>
							<tr>
							
							
							<?php if($show=="All"){ ?>
								<td>
									<a href="#" role='button' status='close' class="group_name" group_id='<?php  echo $key; ?>' style='color:black;'>
									<?php echo $data['name']; ?>
									</a>
								</td>
								<?php if($OpeningBalanceForPrint[$key]['balance'] > 0){ ?>
									<td class="first"><?php 
										echo $OpeningBalanceForPrint[$key]['balance'];
										$op_dr_total += $OpeningBalanceForPrint[$key]['balance'];?>
									</td>
									<td class="first"><?php echo "-" ?></td>
									<?php }else{ ?>
										<td class="first"><?php echo "-"; ?></td>
										<td  class="first"><?php echo abs($OpeningBalanceForPrint[$key]['balance']); 
											$op_cr_total += $OpeningBalanceForPrint[$key]['balance'];?>
										</td>
									<?php } ?>
									
									<td  class="first"><?php echo abs($TransactionDr[$key]['balance']);
										$tr_dr_total += $TransactionDr[$key]['balance'];?>
									</td>
								
									<td  class="first"><?php echo abs($TransactionCr[$key]['balance']);
										$tr_cr_total += $TransactionDr[$key]['balance'];?>
									</td>
									
									<?php if($data['balance'] > 0){ ?>
									<td  class="first"><?php echo $data['balance'];
										@$totalDr=@$totalDr+$data['balance'];	
										$cl_dr_total += $data['balance']; ?>
									</td>
									<td class="first"><?php echo "-" ?>
									</td>
								<?php }else{ ?>
									<td class="first"><?php echo "-"; ?></td>
									<td  class="first"><?php echo abs($data['balance']);  
										$cl_cr_total += $data['balance'];
										@$totalCr=@$totalCr+abs($data['balance']);  ?>
									</td>
								<?php } ?>
								
							<?php }else if($show=="Closing"){ ?>
									<td><?php echo $data['name']; ?></td>
									<?php if($data['balance'] > 0){ ?>
									<td><?php echo $data['balance'];
										@$totalDr=@$totalDr+$data['balance'];	
										$cl_dr_total += $data['balance']; ?>
									</td>
									<td><?php echo "-" ?>
									</td>
								<?php }else{ ?>
									<td><?php echo "-"; ?></td>
									<td><?php echo abs($data['balance']);  
										$cl_cr_total += $data['balance'];
										@$totalCr=@$totalCr+abs($data['balance']);  ?>
									</td>
								<?php } ?>
								 
							<?php }else if($show=="Open_Close"){ ?>
									<td><?php echo $data['name']; ?></td>
									<?php if($OpeningBalanceForPrint[$key]['balance'] > 0){ ?>
									<td><?php 
										echo $OpeningBalanceForPrint[$key]['balance'];
										$op_dr_total += $OpeningBalanceForPrint[$key]['balance'];?>
									</td>
									<td><?php echo "-" ?></td>
									<?php }else{ ?>
										<td><?php echo "-"; ?></td>
										<td><?php echo abs($OpeningBalanceForPrint[$key]['balance']); 
											$op_cr_total += $OpeningBalanceForPrint[$key]['balance'];?>
										</td>
									<?php } ?>
									<?php if($data['balance'] > 0){ ?>
									<td><?php echo $data['balance'];
										@$totalDr=@$totalDr+$data['balance'];	
										$cl_dr_total += $data['balance']; ?>
									</td>
									<td><?php echo "-" ?>
									</td>
								<?php }else{ ?>
									<td><?php echo "-"; ?></td>
									<td><?php echo abs($data['balance']);  
										$cl_cr_total += $data['balance'];
										@$totalCr=@$totalCr+abs($data['balance']);  ?>
									</td>
								<?php } ?>
							<?php }else if($show=="Last"){ ?>
									<td><?php echo $data['name']; ?></td>
									<?php if($data['balance'] > 0){ ?>
									<td><?php echo $data['balance'];
										@$totalDr=@$totalDr+$data['balance'];	
										$cl_dr_total += $data['balance']; ?>
									</td>
									<td><?php echo "-" ?>
									</td>
								<?php }else{ ?>
									<td><?php echo "-"; ?></td>
									<td><?php echo abs($data['balance']);  
										$cl_cr_total += $data['balance'];
										@$totalCr=@$totalCr+abs($data['balance']);  ?>
									</td>
								<?php } ?>
								
								<?php if($LastYear[$key]['balance'] > 0){ ?>
									<td><?php 
										echo $LastYear[$key]['balance'];
										$op_dr_total += $LastYear[$key]['balance'];?>
									</td>
									<td><?php echo "-" ?></td>
									<?php }else{ ?>
										<td><?php echo "-"; ?></td>
										<td><?php echo abs($LastYear[$key]['balance']); 
											$op_cr_total += $LastYear[$key]['balance'];?>
										</td>
									<?php } ?>
								
							<?php } ?>
							</tr>
							<?php } ?>
							<?php if($show=="All"){ ?>
							<tr>
								<td >Opening Stocks</td>
								<td  scope="col" align="left"><?php  echo round($itemOpeningBalance,2); ?></td>
								<td colspan="3"></td>
								<td  scope="col" align="left"><?php echo round($itemOpeningBalance,2); ?></td>
								<td ></td>
							</tr>
							<tr>
								<td >Last Year Profit</td>
								<td ></td>
								<td  scope="col" align="left"><b><?php  echo round(@$GrossProfit,2); ?></b></td>
								<td colspan="2"></td>
								<td ></td>
								<td  scope="col" align="left"><b><?php echo round(@$GrossProfit,2); ?></b></td>
								
							</tr>
							<tr>
								<td colspan="1">Row total</td>
								<td  scope="col" align="left"><?php echo round($op_dr_total+@$itemOpeningBalance,2); ?></td>
								<td  scope="col" align="left"><?php echo round(abs($op_cr_total)+abs(@$GrossProfit),2); ?></td>
								<td  scope="col" align="left"><?php echo round($tr_dr_total,2); ?></td>
								<td  scope="col" align="left"><?php echo round(abs($tr_cr_total),2); ?></td>
								<td  scope="col" align="left"><?php echo round($cl_dr_total+@$itemOpeningBalance,2); ?></td>
								<td  scope="col" align="left"><?php echo round(abs($cl_cr_total)+abs(@$GrossProfit),2); ?></td>
								
							</tr>
							
							<tr style="color:red;">
								<td colspan="5">Diff. In Opening Balance</td>
								<?php if($differenceInOpeningBalance > 0){ ?>
									<td></td>
									<td  scope="col" align="left"><?php echo round($differenceInOpeningBalance,2); ?></td>
									
								<?php } else { ?>
										
									<td  scope="col" align="left"><?php 
									$differenceInOpeningBalance=abs($differenceInOpeningBalance);
									echo round($differenceInOpeningBalance,2); ?></td>
									<td></td>
								<?php } ?>
								
								</tr>
								<tr>
									<td colspan="5">Total</td>
									<?php if($differenceInOpeningBalance < 0){ ?>
									<td  scope="col" align="left"><?php echo round($totalDr+$itemOpeningBalance,2); ?></td>
									<td  scope="col" align="left"><?php echo round($totalCr+$differenceInOpeningBalance)+abs(@$GrossProfit,2); ?></td>
									<?php } else { ?>
									<td  scope="col" align="left"><?php echo round($totalDr+$differenceInOpeningBalance+$itemOpeningBalance,2); ?></td>
									<td  scope="col" align="left"><?php echo round(abs($totalCr)+abs(@$GrossProfit),2); ?></td>
									<?php } ?>
								</tr>
							<?php }else if($show=="Closing"){ ?>
								<tr>
									<td >Opening Stocks</td>
									<td  scope="col" align="left"><?php  echo round($itemOpeningBalance,2); ?></td>
									<td ></td>
								</tr>
								<tr>
									<td >Last Year Profit</td>
									<td ></td>
									<td  scope="col" align="left"><b><?php  echo round(@$GrossProfit,2); ?></b></td>
									
								</tr>
								<tr>
									<td ><b>Total</b></td>
									<td  scope="col" align="left"><?php echo round($cl_dr_total+@$itemOpeningBalance,2); ?></td>
									<td  scope="col" align="left"><?php echo round(abs($cl_cr_total)+abs(@$GrossProfit),2); ?></td>
								</tr>
							<?php }else if($show=="Open_Close"){ ?>
							
									<tr>
									<td >Opening Stocks</td>
									<td  scope="col" align="left"><?php  echo round($itemOpeningBalance,2); ?></td>
									<td ></td>
									<td  scope="col" align="left"><?php  echo round($itemOpeningBalance,2); ?></td>
									<td ></td>
								</tr>
								<tr>
									<td >Last Year Profit</td>
									<td ></td>
									<td  scope="col" align="left"><b><?php  echo round(@$GrossProfit,2); ?></b></td>
									
								</tr>
								<tr>
									<td ><b>Total</b></td>
									<td  scope="col" align="left"><?php echo round($op_dr_total+@$itemOpeningBalance,2); ?></td>
									<td  scope="col" align="left"><?php echo round(abs($op_cr_total)+abs(@$GrossProfit),2); ?></td>
									<td  scope="col" align="left"><?php echo round($cl_dr_total+@$itemOpeningBalance,2); ?></td>
									<td  scope="col" align="left"><?php echo round(abs($cl_cr_total)+abs(@$GrossProfit),2); ?></td>
								</tr>
							<?php } ?>
							
					</tbody>
				<tfoot>
			</tfoot>
		</table>
				
<?php } ?>
</div></div>
</div>

<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
	$(".group_name").die().live('click',function(e){
	   var current_obj=$(this);
	   var group_id=$(this).attr('group_id');
	   
	  
      current_obj.closest('tr').find('.first').toggle();
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
		   
		   var url="<?php echo $this->Url->build(['controller'=>'Ledgers','action'=>'firstSubGroupsTb']); ?>";
		   url=url+'/'+group_id +'/'+from_date+'/'+to_date,
//alert(url);
			$.ajax({
				url: url,
			}).done(function(response) {
				current_obj.attr('status','open');
				 current_obj.addClass("group_a");
				current_obj.closest('tr').find('span').addClass("group_a");
				$('<tr class="append_tr row_for_'+group_id+'"><td colspan="7">'+response+'</td></tr>').insertAfter(current_obj.closest('tr'));
			});			   
		}   
	});	
	
	$(".first_grp_name").die().live('click',function(e){ 
	   var current_obj=$(this);
	   var first_grp_id=$(this).attr('first_grp_id');
	   current_obj.closest('tr').find('.second').toggle();
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
		   var url="<?php echo $this->Url->build(['controller'=>'Ledgers','action'=>'secondSubGroupsTb']); ?>";
		   url=url+'/'+first_grp_id +'/'+from_date+'/'+to_date,
		  // alert(url);
			$.ajax({
				url: url,
			}).done(function(response) {
				current_obj.attr('status','open');
				 current_obj.addClass("group_a");
				current_obj.closest('tr').find('span').addClass("group_a");
				$('<tr class="append_tr row_for_'+first_grp_id+'"><td colspan="7">'+response+'</td><td></td></tr>').insertAfter(current_obj.closest('tr'));
			});			   
		}   
	});	

	$(".second_grp_name").die().live('click',function(e){ 
	   var current_obj=$(this);
	   var second_grp_id=$(this).attr('second_grp_id');
	   current_obj.closest('tr').find('.third').toggle();
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
		   var url="<?php echo $this->Url->build(['controller'=>'Ledgers','action'=>'ledgerAccountDataTb']); ?>";
		   url=url+'/'+second_grp_id +'/'+from_date+'/'+to_date,
		//	alert(url);
			$.ajax({
				url: url,
			}).done(function(response) {
				current_obj.attr('status','open');
				 current_obj.addClass("group_a");
				current_obj.closest('tr').find('span').addClass("group_a");
				$('<tr class="append_tr row_for_'+second_grp_id+'"><td colspan="7">'+response+'</td></tr>').insertAfter(current_obj.closest('tr'));
			});			   
		}   
	});
});	
</script>