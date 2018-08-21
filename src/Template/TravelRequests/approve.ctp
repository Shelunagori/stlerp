<div class="portlet light bordered">
    <table class="table table-striped table-hover">
        <tr>
            <th><?= __('Employee Name') ?></th>
            <td><?= h($travelRequest->employee->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Employee Designation') ?></th>
            <td><?= h($travelRequest->employee_designation) ?></td>
        </tr>
        <tr>
            <th><?= __('Purpose') ?></th>
            <td><?= h($travelRequest->purpose) ?></td>
        </tr>
        <tr>
            <th><?= __('Travel Mode') ?></th>
            <td><?= h($travelRequest->travel_mode) ?></td>
        </tr>
        <tr>
            <th><?= __('Return Travel Mode') ?></th>
            <td><?= h($travelRequest->return_travel_mode) ?></td>
        </tr>
        <tr>
            <th><?= __('Applied Advance Amount') ?></th>
            <td><?= $this->Number->format($travelRequest->applied_advance_amt) ?></td>
        </tr>
        <tr>
            <th><?= __('Travel From Date') ?></th>
            <td><?= h($travelRequest->travel_mode_from_date->format('d-m-Y')) ?></td>
        </tr>
        <tr>
            <th><?= __('Travel To Date') ?></th>
            <td><?= h($travelRequest->travel_mode_to_date->format('d-m-Y')) ?></td>
        </tr>
    </table>
   
    <div class="related">
        <h4><?= __('Travel Schedule Date wise') ?></h4>
        <?php if (!empty($travelRequest->travel_request_rows)): ?>
        <table class="table table-striped table-hover">
            <tr>
                <th><?= __('Party Name') ?></th>
                <th><?= __('Destination') ?></th>
                <th><?= __('Meeting Person') ?></th>
                <th><?= __('Date') ?></th>
                <th><?= __('Reporting Time') ?></th>
            </tr>
            <?php foreach ($travelRequest->travel_request_rows as $travelRequestRows): ?>
            <tr>
                <td><?= h($travelRequestRows->party_name) ?></td>
                <td><?= h($travelRequestRows->destination) ?></td>
                <td><?= h($travelRequestRows->meeting_person) ?></td>
                <td><?= h($travelRequestRows->date->format('d-m-Y')) ?></td>
                <td><?= h($travelRequestRows->reporting_time) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
	<form method="post">
		<label class="control-label  label-css">Advance Amount</label>
		<?php echo $this->Form->input('advance_amt', ['label' => false, 'type'=>'text','placeholder'=>'0.00','style'=> 'text-align:  right;', 'required', 'value'=>$travelRequest->advance_amt]); ?><br/>
		
		<label class="control-label  label-css">Transaction Date</label>
		<?php echo $this->Form->input('trans_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text', 'required', 'value'=>$travelRequest->trans_date==null?'':$travelRequest->trans_date->format('d-m-Y')]); ?><br/>
		
		<label class="control-label  label-css">Bank</label><br/>
		<select name="bank_id">
			<?php foreach($bankCashes as $bank_id=>$bankName){
				if($bank_id == "145"){
					echo '<option value="'.$bank_id.'" selected>'.$bankName.'</option>';
				}else{
					echo '<option value="'.$bank_id.'">'.$bankName.'</option>';
				}
			}?>
		</select>
	<button type="submit" class="btn blue">Approve</button>
	</form>
</div>
