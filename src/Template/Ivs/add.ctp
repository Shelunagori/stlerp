<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-purple-intense ">Create Inventory Voucher : Invoice - <?= h(($Invoice->in1." / IN-".str_pad($Invoice->in2, 3, "0", STR_PAD_LEFT)." / ".$Invoice->in3." / ".$Invoice->in4)) ?></span>
		</div>
	</div>
	<div class="portlet-body">
	<?= $this->Form->create($iv) ?>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Transaction Date<span class="required" aria-required="true">*</span></label>
					<?php echo $this->Form->input('transaction_date', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','value' => date("d-m-Y")]); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<table class="table ">
					<thead>
						<tr>
							<th>Invoice item (Production)</th>
							<th>Consumption</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($Invoice->invoice_rows as $invoice_row){ ?>
						<tr>
							<td><?php pr($invoice_row); ?></td>
							<td>2</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Narration</label>
					<?php echo $this->Form->input('narration', ['label' => false,'class' => 'form-control ', 'placeholder' => 'Narration', 'type' => 'textarea']); ?>
				</div>
			</div>
		</div>
	<?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
	</div>
</div>
