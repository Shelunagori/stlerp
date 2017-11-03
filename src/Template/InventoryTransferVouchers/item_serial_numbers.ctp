<?php
if(!empty($flag)){
	
	$options1=[];
	foreach($inArr as $inArrval){
		 $options1[]=['text' =>$inArrval, 'value' => $inArrval];
	}
	
	echo $this->Form->input('q', ['label'=>false,'options' => $options1,'multiple' => 'multiple','class'=>'form-control input-sm','required select2me']);
}else{
	echo '';
} ?>

