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
		<?php echo $this->Form->input('trans_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text','data-date-start-date' => date("d-m-Y")]); ?>
		<select name="bank_id">
			<?php foreach($bankCashes as $bank_id=>$bankName){
				echo '<option value="'.$bank_id.'">'.$bankName.'</option>';
			}?>
		</select>
	<button type="submit" class="btn blue">Approve</button>
	</form>
</div>