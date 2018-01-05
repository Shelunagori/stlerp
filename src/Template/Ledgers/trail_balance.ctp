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
			<?php  //echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/Ledgers/Export-Ob/'.$url_excel.'',['class' =>'btn btn-sm green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
		</div>
		<div class="portlet-body form">
	<form method="GET" >
				<table class="table table-condensed" >
				<tbody>
					<tr>
					<td>
						<div class="row">
							
							<div class="col-md-3">
								<?php echo $this->Form->input('From', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','value' => @date('d-m-Y', strtotime($from_date)),'data-date-start-date' => date("d-m-Y",strtotime($financial_year->date_from)),'data-date-end-date' => date("d-m-Y",strtotime($financial_year->date_to))]); ?>
								
							</div>
							<div class="col-md-3">
								<?php echo $this->Form->input('To', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','value' => @date('d-m-Y', strtotime($to_date)),'data-date-start-date' => date("d-m-Y",strtotime($financial_year->date_from)),'data-date-end-date' => date("d-m-Y",strtotime($financial_year->date_to))]); ?>
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
<?php if(!empty($LedgerAccounts)){?>
<table class="table table-bordered table-hover table-condensed" width="100%">
					<thead>
						<tr>
							<th scope="col"></th>
							<th scope="col" colspan="2" style="text-align:center";><b>Opening Balance</th>
							<th scope="col" colspan="2" style="text-align:center";><b>Transactions</th>
							<th scope="col" colspan="2" style="text-align:center";><b>Closing balance</th>
						</tr>
						<tr>
							<th scope="col"><b>Ledgers</th>
							<th scope="col" style="text-align:center";><b>Debit</th>
							<th scope="col" style="text-align:center";><b>Credit</th>
							<th scope="col" style="text-align:center";><b>Debit</th>
							<th scope="col" style="text-align:center";><b>Credit</th>
							<th scope="col" style="text-align:center";><b>Debit</th>
							<th scope="col" style="text-align:center";><b>Credit</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$TotalOpeningBalanceDebit=0;
							$TotalOpeningBalanceCredit=0;
							$TotalTransactionsDebit=0;
							$TotalTransactionsCredit=0;
							$ClosingAmmountCredit=0;
							$ClosingAmmountDebit=0;
							foreach($LedgerAccounts as  $LedgerAccount)
							{  
							if($LedgerAccount->alias){
								$ledger_name=@$LedgerAccount->name.'('.@$LedgerAccount->alias.')';
							}else{
								$ledger_name=@$LedgerAccount->name;
							}
							
							if((@$OpeningBalanceDebit[$LedgerAccount->id] > 0 || @$OpeningBalanceCredit[$LedgerAccount->id] > 0) || (@$TransactionsDebit[$LedgerAccount->id] > 0 || @$TransactionsCredit[$LedgerAccount->id] > 0) ){
							?>
							
									<tr>
										<td scope="col"><?php echo $ledger_name;   ?></td>
										
										
										<td scope="col" align="right">
											<?php echo round(@$OpeningBalanceDebit[$LedgerAccount->id],2);
											$opd=@$OpeningBalanceDebit[$LedgerAccount->id]; ?>
											<?php @$TotalOpeningBalanceDebit+=$opd;?>
										</td>
										<td scope="col" align="right">
											<?php echo round(@$OpeningBalanceCredit[$LedgerAccount->id],2);
											$opc=@$OpeningBalanceCredit[$LedgerAccount->id];
											?>
											<?php @$TotalOpeningBalanceCredit+=@$opc;?>
										</td>
										
										
										<td scope="col" align="right">
											<?php echo @$TransactionsDebit[$LedgerAccount->id];?>
											<?php @$TotalTransactionsDebit+=$TransactionsDebit[$LedgerAccount->id];?>
										</td>
										<td scope="col" align="right">
											<?php echo @$TransactionsCredit[$LedgerAccount->id];?>
											<?php @$TotalTransactionsCredit+=$TransactionsCredit[$LedgerAccount->id];?>
										</td>
										
										<?php 
											$totalClosingDr= @$OpeningBalanceDebit[$LedgerAccount->id]+@$TransactionsDebit[$LedgerAccount->id];
											$totalClosingCr= @$OpeningBalanceCredit[$LedgerAccount->id]+@$TransactionsCredit[$LedgerAccount->id];
										?>
										
										<?php if(@$totalClosingDr > @$totalClosingCr){ ?>
										<td scope="col" align="right">
											<?php echo round(@$totalClosingDr-@$totalClosingCr,2); ?>
											<?php @$ClosingAmmountDebit+=@$totalClosingDr-@$totalClosingCr;?>
										</td>
										<td scope="col" align="right">- </td>
										<?php } else if(@$totalClosingDr < @$totalClosingCr){ ?>
										<td scope="col" align="right">- </td>
										<td scope="col" align="right">
											<?php echo round(@$totalClosingCr-@$totalClosingDr,2); ?>
											<?php @$ClosingAmmountCredit+=@$totalClosingCr-@$totalClosingDr ;?>
										</td>
										
										<?php }else{ ?>
											<td scope="col" align="right">- </td>
											<td scope="col" align="right">- </td>
										<?php } ?>
									</tr>
						<?php 
							}	
							}
						?>
							<tr>
								<td colspan="5">Opening Stocks</td>
								<td  scope="col" align="right"><?php echo round($itemOpeningBalance,2); ?></td>
								<td></td>
							</tr>
							<tr style="color:red;">
								<td colspan="5">Diff. In Opening Balance</td>
								<?php if($differenceInOpeningBalance > 0){ ?>
									<td  scope="col" align="right"><?php echo round($differenceInOpeningBalance,2); ?></td>
									<td></td>
								<?php } else { ?>
									<td></td>	
									<td  scope="col" align="right"><?php 
									$differenceInOpeningBalance=abs($differenceInOpeningBalance);
									echo round($differenceInOpeningBalance,2); ?></td>
								<?php } ?>
								
							</tr>
							<tr>
								<td scope="col"><b>Total </td>
								
								<td scope="col" align="right"><b>
									<?php echo round(@$TotalOpeningBalanceDebit,2);?>
								</td>
								<td scope="col" align="right"><b>
								<?php echo round(@$TotalOpeningBalanceCredit,2);?>
								</td>
								

								
								
								<td scope="col" align="right"><b>
									<?php echo @$TotalTransactionsDebit;?>
								</td>
								<td scope="col" align="right"><b>
									<?php echo @$TotalTransactionsCredit;?>
								</td>
								
								<?php 
								if(@$itemOpeningBalance > 0){
									@$ClosingAmmountDebit=@$ClosingAmmountDebit+@$itemOpeningBalance+abs($differenceInOpeningBalance);
								}else{
									@$ClosingAmmountCredit=@$ClosingAmmountCredit+abs(@$itemOpeningBalance)+abs($differenceInOpeningBalance);
								}
								?>
								
								<td scope="col" align="right"><b>
									<?php echo round(@$ClosingAmmountDebit,2);?>
								</td>
								
								<td scope="col" align="right"><b>
								<?php echo round(@$ClosingAmmountCredit,2);?>
								</td>
								
								
							</tr>
					</tbody>
					<tfoot>
					</tfoot>
					</table>
				
<?php } ?>
</div></div>
</div>