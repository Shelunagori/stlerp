<?php
if(!empty($flag)){
	$selected=[];
	foreach($selectedSerialNumbers as $selectedSerialNumber){
		 $selected[]=$selectedSerialNumber->id;
	}
	$options1=[];
	foreach($SerialNumbers as $SerialNumber){
		 $options1[]=['text' =>$SerialNumber->serial_no, 'value' => $SerialNumber->id];
	}
	
	echo $this->Form->input('q', ['label'=>false,'options' => $options1,'multiple' => 'multiple','class'=>'form-control input-sm','required select2me','value'=>$selected]);
}else{
	echo '';
} ?>

