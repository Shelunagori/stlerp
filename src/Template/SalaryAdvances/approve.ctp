<div class="portlet light bordered">
    <table class="table table-striped table-hover">
        <tr>
            <th><?= __('Employee Name') ?></th>
            <td><?= h($salaryAdvance->employee->name) ?></td>
        </tr>
		<tr>
            <th><?= __('Applied amount') ?></th>
            <td><?= $this->Number->format($salaryAdvance->applied_amount) ?></td>
        </tr>
		<tr>
            <th><?= __('Reason') ?></th>
            <td><?= $this->Text->autoParagraph(h($salaryAdvance->reason)); ?></td>
        </tr>
    </table>
	<form method="post">
		<table>
			<tr>
				<td>
					<label class="control-label">Approve amount</label>
					<?php echo $this->Form->input('amount', ['label' => false, 'type'=>'text', 'placeholder'=>'0.00','value'=>$salaryAdvance->amount, 'value'=>$salaryAdvance->amount, 'style'=>'text-align:right;', 'required', 'class'=>'form-control input-sm']); ?>
				</td>
				<td>
					<label class="control-label">Transaction Date</label>
					<?php echo $this->Form->input('trans_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text', 'value'=>$salaryAdvance->trans_date==null?'':$salaryAdvance->trans_date->format('d-m-Y') , 'required']); ?>
				</td>
				<td>
					<label class="control-label">Bank</label><br/>
					<select name="bank_id" class="form-control input-sm">
						<?php foreach($bankCashes as $bank_id=>$bankName){
							echo '<option value="'.$bank_id.'">'.$bankName.'</option>';
						}?>
					</select>
				</td>
			</tr>
		</table>
	<button type="submit" class="btn blue">Approve</button>
	</form>
</div>