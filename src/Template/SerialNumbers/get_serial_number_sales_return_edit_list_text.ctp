
<?php  foreach($serialnumbers as $option){
	echo $this->Form->input('sr_nos[]', ['hiddenField' => false,'label'=>false,'class'=>'form-control input-sm sr_no','type'=>'text','value'=>$option->name,'readonly'=>'readonly','style'=>'width: 187px;']); ?>
	<?php if(@$parentSerialNo[$option->id]!=$option->id){ ?>
	<a class='btn btn-xs red' href="<?php echo $this->Url->build(['controller'=>'SaleReturns','action'=>'DeleteSerialNumbers',$option->id, $option->sales_return_id,$option->sales_return_row_id,$option->item_id]); ?>"><i class="fa fa-trash"></i> </a>
	
<?php }} ?>

