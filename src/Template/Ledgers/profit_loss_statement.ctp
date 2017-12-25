<?php
$this->set('title', 'Profit & Loss Statement');
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>Profit & Loss Statement
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
											<?php if($openingValue>=0) { ?>
												<tr>
													<td>Opening Stock</td>
													<td align="right">
														<?php 
														echo round($openingValue,2);
														$LeftTotal+=$openingValue;
														?>
													</td>
												</tr>
											<?php } ?>
											<?php foreach($groupForPrint as $groupForPrintRow){ 
												if($groupForPrintRow['balance']>0){ ?>
												<tr>
													<td><?php echo $groupForPrintRow['name']; ?></td>
													<td align="right">
														<?php if($groupForPrintRow['balance']!=0){
															echo abs($groupForPrintRow['balance']);
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
											<?php if($openingValue<0) { ?>
												<tr>
													<td>Opening Stock</td>
													<td align="right">
														<?php 
														echo $openingValue;
														$RightTotal+=$openingValue;
														?>
													</td>
												</tr>
											<?php } ?>
											<?php foreach($groupForPrint as $groupForPrintRow){ 
												if($groupForPrintRow['balance']<0){ ?>
												<tr>
													<td><?php echo $groupForPrintRow['name']; ?></td>
													<td align="right">
														<?php if($groupForPrintRow['balance']!=0){
															echo abs($groupForPrintRow['balance']); 
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
							<tr>
								<td>
									<?php 
									$totalDiff=$RightTotal-$LeftTotal;
									if($totalDiff>=0){ ?>
									<table width="100%">
										<tbody>
											<tr>
												<td>Gross Profit</td>
												<td align="right">
													<?php echo round($totalDiff,2); $LeftTotal+=$totalDiff; ?>
												</td>
											</tr>
										</tbody>
									</table>
									<?php } ?>
								</td>
								<td>
									<?php if($totalDiff<0){ ?>
									<table width="100%">
										<tbody>
											<tr>
												<td>Gross Loss</td>
												<td align="right">
													<?php echo round(abs($totalDiff),2); $RightTotal+=abs($totalDiff); ?>
												</td>
											</tr>
										</tbody>
									</table>
									<?php } ?>
								</td>
							</tr>
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

