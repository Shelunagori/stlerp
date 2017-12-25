<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Balance Sheet');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>Balance Sheet
				</div>
			</div>
			<div class="portlet-body">
				<form method="get">
						<div class="row">
							<div class="col-md-3">
								<?php echo $this->Form->control('from_date',['class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($from_date)),'required'=>'required']); ?>
							</div>
							<div class="col-md-3">
								<?php echo $this->Form->control('to_date',['class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'label'=>false,'placeholder'=>'DD-MM-YYYY','type'=>'text','data-date-start-date'=>@$coreVariable[fyValidFrom],'data-date-end-date'=>@$coreVariable[fyValidTo],'value'=>date('d-m-Y',strtotime($to_date)),'required'=>'required']); ?>
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
											<?php foreach($groupForPrint as $groupForPrintRow){ 
												if(($groupForPrintRow['balance']<0)){ ?>
												<tr>
													<td><?php echo $groupForPrintRow['name']; ?></td>
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
											<?php foreach($groupForPrint as $groupForPrintRow){ 
												if(($groupForPrintRow['balance']>0)){ ?>
												<tr>
													<td><?php echo $groupForPrintRow['name']; ?></td>
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
