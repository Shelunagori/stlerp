<?php //pr($challans); exit; ?>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Challans In</span>
		
		</div>
		<div class="actions">
			
		</div>

	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
			
				<?php $page_no=$this->Paginator->current('Challans'); $page_no=($page_no-1)*20; ?>
				<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th>S.No</th>
								<th>Voucher.No</th>
								<th>Customer Name</th>
								
								<!--<th class="actions"><?= __('Actions') ?></th>-->
							</tr>
					
					</thead>

					<tbody>
            <?php foreach ($challanReturnVouchers as $challanReturnVouchers): ?>
            <tr>
				
                <td><?= h(++$page_no) ?></td>
				<td><?= h('#'.str_pad($challanReturnVouchers->voucher_no, 4, '0', STR_PAD_LEFT)) ?></td>
                
				<td>
				<?php if($challanReturnVouchers->challan->customer_id){
					if(!empty($challanReturnVouchers->challan->customer->alias)){
						echo $challanReturnVouchers->challan->customer->customer_name.'('.$challanReturnVouchers->challan->customer->alias.')';
					}else{
						echo $challanReturnVouchers->challan->customer->customer_name;
					}
				  }elseif($challanReturnVouchers->challan->vendor_id){ 
				  echo $challanReturnVouchers->challan->vendor->company_name; 
				  } ?>
				
				</td>
			
               
				<!--<td class="actions">
								<?php if(in_array(28,$allowed_pages)){  ?>
								<?php echo $this->Html->link('<i class="fa fa-search"></i>',['action' => 'confirm', $challanReturnVouchers->id],array('escape'=>false,'target'=>'_blank','class'=>'btn btn-xs yellow tooltips','data-original-title'=>'View as PDF')); ?>
								<?php } ?>
								<?php if(in_array(12,$allowed_pages)){  ?>
								<?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',['action' => 'edit', $challanReturnVouchers->id],array('escape'=>false,'class'=>'btn btn-xs blue tooltips','data-original-title'=>'Edit')); ?>
								<?php } ?>
				</td>-->
            </tr>
            <?php endforeach; ?>
        </tbody>
				</table>
				</div>
			</div>
		</div>
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
	

