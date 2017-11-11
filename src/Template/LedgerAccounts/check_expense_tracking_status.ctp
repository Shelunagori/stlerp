<?php 
if(!empty($grn_invoice))
{
	if(@$status=="GRN")
	{
		
			$option='';
			foreach($grn_invoice as $grn1)
			{
				$grn_no=$grn1->grn1.'/GRN-'.str_pad($grn1->grn2, 3, '0', STR_PAD_LEFT).'/'.$grn1->grn3.'/'.$grn1->grn4;
				$option[]=['text' =>$grn_no, 'value' => $grn1->id];
			}
		echo $this->Form->input('q[]', ['label'=>false,'options' => $option,'multiple' => 'multiple','class'=>'form-control select2me grns','style'=>'width:100%']);   
		 
	}
	if(@$status=="INVOICE")
	{
		$option='';
		foreach($grn_invoice as $invoice1)
		{
			$invoice_no=$invoice1->in1.'/IN-'.str_pad($invoice1->in2, 3, '0', STR_PAD_LEFT).'/'.$invoice1->in3.'/'.$invoice1->in4;
			$option[]=['text' =>$invoice_no, 'value' => $invoice1->id];
		}
		echo $this->Form->input('q[]', ['label'=>false,'options' => $option,'multiple' => 'multiple','class'=>'form-control select2me invoices','style'=>'width:100%']);
	}
}
?>