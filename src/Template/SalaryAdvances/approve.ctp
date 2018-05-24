<div class="portlet light bordered">
    <table class="table table-striped table-hover">
        <tr>
            <th><?= __('Employee Name') ?></th>
            <td><?= h($salaryAdvance->employee->name) ?></td>
        </tr>
		<tr>
            <th><?= __('Amount') ?></th>
            <td><?= $this->Number->format($salaryAdvance->amount) ?></td>
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
					<label class="control-label">Amount</label>
					<?php echo $this->Form->input('amount', ['label' => false, 'type'=>'text', 'placeholder'=>'0.00','value'=>$salaryAdvance->amount]); ?>
				</td>
				<td>
					<label class="control-label">Transaction Date</label>
					<?php echo $this->Form->input('trans_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text']); ?>
				</td>
				<td>
					<label class="control-label">Bank</label><br/>
					<select name="bank_id">
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