<?php 
$pdf_url=$this->Url->build(['controller'=>'Invoices','action'=>'GstPdf']);
$list_url=$this->Url->build(['controller'=>'Invoices','action'=>'index']);
if($invoice->invoice_type=="GST"){
	$Edit_url=$this->Url->build(['controller'=>'Invoices','action'=>'GstEdit']);
}else{
	$Edit_url=$this->Url->build(['controller'=>'Invoices','action'=>'Edit']);
}
$mail_url=$this->Url->build(['controller'=>'Invoices','action'=>'sendMail']);

//pr($pdf_url); exit;
?>
<table width="100%">
	<tr>
		<td valign="top" style="background: #FFF;">
		<div class="list-group">
			<a href="<?php echo $list_url; ?>" class="list-group-item"><i class="fa fa-chevron-left"></i> Back to Invoices </a>
			<a href="#" class="list-group-item select_term_condition"><i class="fa fa-envelope"></i> Email </a>
			<?php if(in_array(8,$allowed_pages)){
				if(!in_array(date("m-Y",strtotime($invoice->date_created)),$closed_month))
				{ 
				?>
			<?php if($st_year_id==$invoice->financial_year) {?>	
			<a href="<?php echo $Edit_url.'/'.$id; ?>" class="list-group-item"><i class="fa fa-edit"></i> Edit </a>
			
			<?php } } } ?>
			<a href="#" class="list-group-item" onclick="window.close()"><i class="fa fa-times"></i> Close </a>
		</div>
		
		<div style="padding:5px;overflow: auto;">
		<h4>Invoice Type</h4>
		<?= $this->Form->create($invoice) ?>
			
							<?php 
							$options=[];
							$options=[['text'=>'Original','value'=>'ORIGINAL'],['text'=>'Copy','value'=>'COPY'],['text'=>'Duplicate For Transporter','value'=>'DUPLICATE FOR TRANSPORTER']];
							
							?>
							<?php echo $this->Form->input('pdf_to_print', ['options'=>$options,'empty' => "--Select Invoice Type--",'label' => false,'class' => 'form-control input-sm ','required']); ?>

			
			<?= $this->Form->button(__('Update'),['class'=>'btn btn-sm default']) ?>
		
		<?= $this->Form->end() ?>
		</div>
		
		<div style="padding:5px;overflow: auto;">
		<h4>Adjust Font Size</h4>
		<?= $this->Form->create($invoice) ?>
			
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
		<?= $this->Form->create($invoice) ?>
			<?php $sr=0; foreach ($invoice->invoice_rows as $invoiceRows): $sr++;
				echo $this->Form->input('invoice_rows.'.$invoiceRows->id.'.id');
				echo $this->Form->input('invoice_rows.'.$invoiceRows->id.'.height',['label' => 'Row-'.$sr,'class' => 'input-sm quantity','value'=>$invoiceRows->height]);				
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
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
	$('.quantity').die().live("keyup",function() {
			var asc=$(this).val();
			var numbers =  /^[0-9]*\.?[0-9]*$/;
			if(asc==0)
			{
				$(this).val('');
				return false; 
			}
			else if(asc.match(numbers))  
			{  
			} 
			else  
			{  
				$(this).val('');
				return false;  
			}
	});
	
	$('.select_term_condition').die().live("click",function() { 
		var addr=$(this).text();
		$("#myModal2").show();
    });
	$('.closebtn2').on("click",function() { 
		$("#myModal2").hide();
    });
	
	$('.check_value').die().live("change",function() {
		$(".tabl_tc tbody tr").each(function(){
		var v=$(this).find('td:nth-child(1)  input[type="checkbox"]:checked').val();
		if(v){
			$(this).find('td:nth-child(1)  input[type="text"].term').removeAttr("readonly"); ;
			$(this).find('td:nth-child(1)  input[type="text"].term').focus();
		}else{
				
			$(this).find('td:nth-child(1)  input[type="text"].term').attr('readonly','readonly');
		}
		});
	});
	
	$('.insert_tc').die().live("click",function() {
		$('#sortable').html("");
		var i=0;
		var send_data = [];
		$(".tabl_tc tbody tr").each(function(){
			var v=$(this).find('td:nth-child(1)  input[type="checkbox"]:checked').val();
			var term=$(this).find('td:nth-child(1)  input[type="text"].term').val();
			if(term){
			$(this).find('td:nth-child(1)  input[type="checkbox"]:checked').val(term);
			}
			if(v){
				var tc=$(this).find('td:nth-child(1) .check_value').val(); 
				send_data[i++] = tc;
				//send_data[++i]=tc;
			}
		});
		var textdata=$('.textdata').val(); 
		var json_data=JSON.stringify(send_data);
		
		 var id="<?php echo $id; ?>";
		
		var url="<?php echo $this->Url->build(['controller'=>'Invoices','action'=>'sendMail']); ?>";
		url=url+'?id='+id+'&data='+json_data+'&otherData='+textdata;
		alert(url);
		$.ajax({
			url: url,
			type: "GET",
		}).done(function(response) { 
		alert(response);
			alert("Email Send successfully")
		}); 
		
		//console.log(send_data);
		$("#myModal2").hide();
    });
});
</script>

<ol id="sortable"></ol>
<div id="myModal2" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false" style="display: none; padding-right: 12px;"><div class="modal-backdrop fade in" ></div>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id="result_ajax">
			<h4>Terms & Conditions</h4>
				<div style=" overflow: auto; height: 450px;">
				<table class="table table-hover tabl_tc">
					
				<?php foreach ($termsConditions as $termsCondition): ?>
					 <tr>
						<td>
						 <div class="checkbox-list">
							<label>
								<input type="checkbox" name="dummy" value="<?= h($termsCondition->id) ?>" class="check_value"><input style="border: none;
								background: transparent;" type="text" size="60%" class="term" name="terms" value="<?php echo $termsCondition->text_line; ?>" readonly>
							</label>
						 </div>
						
						</td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td>
						 <div class="checkbox-list">
							<label>
								
								<textarea name="delivery_description" class="form-control input-sm textdata" placeholder="Other Description" id="delivery-description" rows="7"><?= h('We now request you to collect the material from transporter and process our invoice for payment of Rs'. h(number_format($invoice->grand_total,2)).'/- in favour of '. h(($company_data->name)).'. in our account No '
									. h(($invoice->company->company_banks[0]->account_no)).' of '.h($invoice->company->company_banks[0]->bank_name) .','. h( $invoice->company->company_banks[0]->branch).',IFSC Code: '.h($invoice->company->company_banks[0]->ifsc_code).', MICR Code:313026002 Branch Code 539406 and our PAN No. is '.h(($invoice->company->pan_no))) ?></textarea>
							</label> 
						 </div>
						
						</td>
				</tr>
				</table>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn default closebtn2">Close</button>
				<button class="btn btn-primary insert_tc">Send Email</button>
			</div>
		</div>
	</div>
</div>
