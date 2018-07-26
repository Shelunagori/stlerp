<?php //pr($pettyCashReceiptVouchers); exit; ?>
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-globe font-blue-steel"></i>
            <span class="caption-subject font-blue-steel uppercase">Credit Note</span>
        </div>
		<div class="actions">
		
		
		<?= $this->Html->link(
			'Back',
			'/CreditNotes/',
			['class' => 'btn btn-primary']
		); ?>
		
	</div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-12">
                <?php $page_no=$this->Paginator->current('CreditNotes'); $page_no=($page_no-1)*20; ?>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Transaction Date</th>
                            <th>Vocher No</th>
                         
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0; foreach ($creditNotes as $creditNote): $i++;  ?>
                        <tr>
                            <td><?= h(++$page_no) ?></td>
                            <td><?= h(date("d-m-Y",strtotime($creditNote->transaction_date)))?></td>
                            <td><?= h('#'.str_pad($creditNote->voucher_no, 4, '0', STR_PAD_LEFT)) ?></td>
						
                            <td class="actions">
                            <?php echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'view', $creditNote->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View ')); ?>
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
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>
$(document).ready(function() {
	
	$('#close_popup_btn').die().live("click",function() {
		var quote_id=$(this).attr('quote_id'); 
		if((quote_id)){
			 var url="<?php echo $this->Url->build(['controller'=>'CreditNotes','action'=>'cancleCreditNote']); 
			?>";
			url=url+'/'+quote_id, alert(url);
			
			$.ajax({
				url: url,
			}).done(function(response) {
				location.reload();
			}); 
			
		}		
    });
});
</script>