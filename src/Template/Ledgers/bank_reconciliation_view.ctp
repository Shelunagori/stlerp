
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Bank Reconciliation View</span>
		</div>
		<div align="right">
			<?php $today =date('d-m-Y');
						echo $this->Html->link('<i class="fa fa-puzzle-piece"></i> Bank Reconcilation Add',array('controller'=>'Ledgers','action'=>'bankReconciliationAdd','From'=>$today,'To'=>$today),array('escape'=>false)); ?>
		</div>
	
	
	<div class="portlet-body form">
	<form method="GET" >
				<table class="table table-condensed" >
				<tbody>
					<tr>
					<td>
						<div class="row">
							<div class="col-md-4">
									<?php echo $this->Form->input('ledger_account_id', ['empty'=>'--Select--','options' => $banks,'empty' => "--Select Bank--",'label' => false,'class' => 'bank_data form-control input-sm select2me','required','value'=>@$ledger_account_id]); ?>
							</div>
							<div class="col-md-4">
								
								<input type="text" name="From" class="form-control input-sm date-picker" placeholder="Transaction From" value="<?php echo @date('01-04-Y');  ?>" required data-date-format="dd-mm-yyyy" 
								data-date-start-date="<?php echo date("d-m-Y",strtotime($financial_year->date_from)); ?>" data-date-end-date="<?php echo date("d-m-Y",strtotime($financial_year->date_to)) ?>">
							</div>
							<?php if(empty($To)){ ?>
								<div class="col-md-4">
									<?php echo $this->Form->input('	To', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','value' => @date('d-m-Y', strtotime($To)),'data-date-start-date' => date("d-m-Y",strtotime($financial_year->date_from)),'data-date-end-date' => date("d-m-Y",strtotime($financial_year->date_to))]); ?>
								</div>
							<?php }else{ ?>
							<div class="col-md-4">
								<?php echo $this->Form->input('To', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','value' => @$To,'data-date-start-date' => date("d-m-Y",strtotime($financial_year->date_from)),'data-date-end-date' => date("d-m-Y",strtotime($financial_year->date_to))]); ?>
							</div>	
							<?php } ?>	
						</div>
					</td>
					<td><button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button></td>
					</tr>
				</tbody>
			</table>
	</form>
	
		<!-- BEGIN FORM-->
<?php if(!empty($Bank_Ledgers)){  ?>
	<div class="row " id="hide_div">
	
		<div class="col-md-12"> 
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Transaction Date</th>
						<th>Source</th>
						<th style="text-align:right;">Dr</th>
						<th style="text-align:right;">Cr</th>
						<th>Reconcilation Date</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php  $total_balance_acc=0; $total_debit=0; $total_credit=0;
				//pr($Bank_Ledgers->toArray()); exit;
				foreach($Bank_Ledgers as $ledger): 
				
				?>
				<tr>
						<td><?php echo date("d-m-Y",strtotime($ledger->transaction_date)); ?></td>
						<td><?= h($ledger->voucher_source); ?></td>
						
						<td align="right"><?= $this->Number->format($ledger->debit,[ 'places' => 2]); 
							$total_debit+=$ledger->debit; ?></td>
						<td align="right"><?= $this->Number->format($ledger->credit,[ 'places' => 2]); 
							$total_credit+=$ledger->credit; ?></td>
						<td>
							<?php echo date("d-m-Y",strtotime($ledger->reconciliation_date)); ?>
						</td>
						<?php echo $this->Form->input('reconciliation_date', ['type' => 'hidden','label' => false,'class' => 'form-control input-sm date-picker reconciliation_date','data-date-format' => 'dd-mm-yyyy','data-date-start-date' => '+0d','data-date-end-date' => '+60d','placeholder' => 'Reconcilation Date','ledger_id'=>$ledger->id,'value'=>date("d-m-Y",strtotime($ledger->reconciliation_date))]); ?>
						<td>
							<button type="button" ledger_id=<?php echo $ledger->id ?> class="btn btn-primary btn-sm subdate"><i class="fa  fa-arrow-left" ></i></button>	
						</td>
				</tr>
				<?php  endforeach; ?>
				<tr>
					<td colspan="2" align="right">Total</td>
					<td align="right" ><?php echo $this->Number->format($total_debit,['places'=>2]) ;?> Dr</td>
					<td align="right" ><?php echo $this->Number->format($total_credit,['places'=>2]); ?> Cr</td>
					<td align="right" ></td>
					<td align="right" ></td>
				<tr>
				</tbody>
			</table>
			</div>
			
		</div>
<?php } ?>
</div></div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
	$('.subdate').die().click(function() {  
	var t=$(this);
		var ledger_id=$(this).attr('ledger_id');
		
		var date= '00-00-0000';
		var reconciliation_date="yes";
		var url="<?php echo $this->Url->build(['controller'=>'Ledgers','action'=>'dateUpdate']); ?>";
		url=url+'/'+ledger_id+'/'+reconciliation_date,
		
		$.ajax({
			url: url,
		}).done(function(response) { 
			t.closest("tr").hide();
			 window.location.reload(true); 
		});
    });
	
	$('.bank_data').die().live("change",function() { 
		$("#hide_div").hide();
	});

});
</script>
