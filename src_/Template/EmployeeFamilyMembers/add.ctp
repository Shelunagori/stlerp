<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Employee Family Members'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="employeeFamilyMembers form large-9 medium-8 columns content">
    <?= $this->Form->create($employeeFamilyMember) ?>
    <fieldset>
        <legend><?= __('Add Employee Family Member') ?></legend>
        <?php
            echo $this->Form->input('employee_id', ['options' => $employees]);
            echo $this->Form->input('member_name');
            echo $this->Form->input('relationship');
            echo $this->Form->input('mobile');
            echo $this->Form->input('telephone');
            echo $this->Form->input('address');
        ?>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Department <span class="required" aria-required="true">*</span></label>
					<?php echo $this->Form->input('member_name', ['label' => false,'class' => 'form-control input-sm']); ?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Designation <span class="required" aria-required="true">*</span></label>
					<?php echo $this->Form->input('designation_id', ['empty' => 'Select Designation','options'=>$designations,'label' => false,'class' => 'form-control select2me','placeholder'=>'Designation']); ?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Date Of Joining<span class="required" aria-required="true">*</span></label>
					<?php echo $this->Form->input('join_date', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','placeholder' => 'Date of Joining']); ?>
				</div>
			</div>
		</div>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
