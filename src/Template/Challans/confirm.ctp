<?php 
$pdf_url=$this->Url->build(['controller'=>'Challans','action'=>'pdf']);
$list_url=$this->Url->build(['controller'=>'Challans','action'=>'index']);
$Edit_url=$this->Url->build(['controller'=>'Challans','action'=>'Edit']);
$id = $EncryptingDecrypting->encryptData($id); 
?>
<table width="100%">
	<tr>
		<td valign="top" style="background: #FFF;">
		<div class="list-group">
			<a href="<?php echo $list_url; ?>" class="list-group-item"><i class="fa fa-chevron-left"></i> Back to Challan </a>
			<a href="<?php echo $Edit_url.'/'.$id; ?>" class="list-group-item"><i class="fa fa-edit"></i> Edit </a>
			<a href="#" class="list-group-item" onclick="window.close()"><i class="fa fa-times"></i> Close </a>
		</div>
		<div style="padding:5px;overflow: auto;">
		<h4>Adjust Font Size</h4>
		<?= $this->Form->create($challans) ?>
			
							<?php 
							$options=[];
							$options=[['text'=>'8px','value'=>'8px'],['text'=>'9px','value'=>'9px'],['text'=>'10px','value'=>'10px'],['text'=>'11px','value'=>'11px'],['text'=>'12px','value'=>'12px'],['text'=>'13px','value'=>'13px'],['text'=>'14px','value'=>'14px'],['text'=>'15px','value'=>'15px'],['text'=>'16px','value'=>'16px']];
							
							?>
							<?php echo $this->Form->input('pdf_font_size', ['options'=>$options,'empty' => "--Select Font Size--",'label' => false,'class' => 'form-control input-sm ','required']); ?>

			
			<?= $this->Form->button(__('Update'),['class'=>'btn btn-sm default']) ?>
		
		<?= $this->Form->end() ?>
		</div>
		<div style="padding:5px;height: 400px;overflow: auto;">
		<h4>Adjust height of rows</h4>
		<?= $this->Form->create($challans) ?>
			<?php $sr=0; foreach ($challans->challan_rows as $challan_row): $sr++; 
				echo $this->Form->input('challan_rows.'.$challan_row->id.'.id');
				echo $this->Form->input('challan_rows.'.$challan_row->id.'.height',['label' => 'Row-'.$sr,'class' => 'input-sm quantity','value'=>$challan_row->height]);				
			endforeach; ?>
			<?= $this->Form->button(__('Update'),['class'=>'btn btn-sm default']) ?>
		<?= $this->Form->end() ?>
		</div>
		</td>
		<td width="80%">
			<object data="<?php echo $pdf_url.'/'.$id; ?>" type="application/pdf" width="100%" height="613px">
			  <p>Wait a while, PDf is loading...</p>
			</object>
		</td>
	</tr>
</table>
