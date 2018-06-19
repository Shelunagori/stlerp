<?php //pr($pettyCashReceiptVouchers); exit; ?>
<?php $url_excel="/?".$url; ?>
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-globe font-blue-steel"></i>
            <span class="caption-subject font-blue-steel uppercase">Debit-Note Voucher</span>
        </div>
		<div class='actions'>
			<?php echo $this->Html->link( '<i class="fa fa-file-excel-o"></i> Excel', '/DebitNotes/Excel-Export/'.$url_excel.'',['class' =>'btn  green tooltips','target'=>'_blank','escape'=>false,'data-original-title'=>'Download as excel']); ?>
		</div>
	
    <div class="portlet-body">
	<div class='row'>
		<div class="col-md-12">
        <form method="GET" >
				<table class="table table-condensed">
					<tbody>
						<tr>
							<td width="20%"> 
								<div class="input-group" style="" id="pnf_text">
									<span class="input-group-addon">DR-</span>
								<input type="text" name="vouch_no" class="form-control input-sm" placeholder="Voucher No" value="<?php echo @$vouch_no; ?>">
							</div>	
							</td>
							<td width="20%">
								<input type="text" name="From" class="form-control input-sm date-picker" placeholder="Transaction Date From" value="<?php echo @$From; ?>" data-date-format="dd-mm-yyyy" >
							</td>
							<td width="20%">
								<input type="text" name="To" class="form-control input-sm date-picker" placeholder="Transaction Date To" value="<?php echo @$To; ?>" data-date-format="dd-mm-yyyy" >
							</td>
							
							<td ><button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button></td>
						</tr>
					</tbody>
				</table>
				</form>
			</div>
				
            <div class="col-md-12">
                <?php $page_no=$this->Paginator->current('DebitNotes'); $page_no=($page_no-1)*20; ?>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Customer/Supplier</th>
							<th>Vocher No</th>
                            <th>Transaction Date</th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0; foreach ($debitNotes as $debitNote): $i++; 
						$debitNote->id = $EncryptingDecrypting->encryptData($debitNote->id);
						?>
                        <tr>
                            <td><?= h(++$page_no) ?></td>
                            <td>
							<?php if(!empty($debitNote->head->alias)){
								echo $debitNote->head->name.'('.$debitNote->head->alias.')';
							}else{
								echo $debitNote->head->name;
							} ?>
							</td>
							 <td><?= h('#'.str_pad($debitNote->voucher_no, 4, '0', STR_PAD_LEFT)) ?></td>
                            <td><?= h(date("d-m-Y",strtotime($debitNote->transaction_date)))?></td>
                           
						
                            <td class="actions">
							<?php if(in_array(112,$allowed_pages)){
								echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'view', $debitNote->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View ')); } ?>
                             <?php if(in_array(111,$allowed_pages)){
								 echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $debitNote->id],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit')); 
							 } ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="paginator">
                    <ul class="pagination">
                        <?= $this->Paginator->prev('< ' . __('previous')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('next') . ' >') ?>
                    </ul>
                    <p><?= $this->Paginator->counter() ?></p>
                </div>
            </div>
        </div>
		 </div>
    </div>
</div>
