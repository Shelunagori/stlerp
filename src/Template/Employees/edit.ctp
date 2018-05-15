<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption" >
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">ADD EMPLOYEE</span>
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		 <?= $this->Form->create($employee,['type' => 'file','class'=>'form-horizontal','id'=>'form_sample_3']) ?>
		<div class="form-body">
			<fieldset style="margin-left:  0px;margin-right: 0px;">	
			<legend><b>Personal Information </b></legend>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Name <span class="required" aria-required="true">*</span></label>
							<?php echo $this->Form->input('name', ['label' => false,'class' => 'form-control input-sm firstupercase','id'=>'inputTextBox' ,'placeholder'=>'Name']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Date of Birth<span class="required" aria-required="true">*</span></label>
							<?php echo $this->Form->input('dob',['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','placeholder' => 'Date of Birth','value'=>$employee->dob->format('d-m-Y')]); ?>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Gender <span class="required" aria-required="true">*</span></label>
							<div class="radio-list">
								<div class="radio-inline" data-error-container="#sex_required_error">
									<?php echo $this->Form->radio(
											'sex',
											[
												['value' => 'Male', 'text' => 'Male'],
												['value' => 'Female', 'text' => 'Female']
											]
									); ?>
								</div>
								<div id="sex_required_error"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Mobile <span class="required" aria-required="true">*</span></label>
							<?php echo $this->Form->input('mobile', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Mobile','maxlength'=>10]); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Landline</label>
							<?php echo $this->Form->input('phone_no', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Landline','maxlength'=>15]); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Personal Email ID <span class="required" aria-required="true">*</span></label>
							<?php echo $this->Form->input('email', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Personal Email ID']); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Company Email ID <span class="required" aria-required="true">*</span></label>
							<?php echo $this->Form->input('company_email', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Company Email ID']); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Identity Mark </label>
							<?php echo $this->Form->input('identity_mark', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Identity Mark']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Caste</label>
							<?php echo $this->Form->input('caste', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Caste']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Religion</label>
							<?php echo $this->Form->input('religion', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Religion']); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Home State </label>
							<?php echo $this->Form->input('home_state', ['empty'=> '---Select State---','label' => false,'class'=>'form-control select2me input-sm state_change','options'=>@$states,'div_id'=>'1','district'=>'home_district']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Home District</label>
							<?php echo $this->Form->input('home_district', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Home District']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Adhar Card No</label>
							<?php echo $this->Form->input('adhar_card_no', ['label' => false,'placeholder'=>'Adhar Card No','class'=>'form-control input-sm','maxlength'=>12,'minlength'=>12]); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Passport No</label>
							<?php echo $this->Form->input('passport_no', ['label' => false,'placeholder'=>'Passport No','class'=>'form-control input-sm','type'=>'text','style'=>'resize:none;','rows'=>'2']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Height (in feet)</label>
							<?php
							echo $this->Form->input('height', ['data-placeholder'=>'Height','label' => false,'class'=>'form-control input-sm','placeholder'=>'Height']); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Blood Group </label>
							<?php 
							
							$arrblood=array(""=>"Select Blood Group", 'A+'=>"A+",'A-'=>"A-",'B+'=>"B+",'B-'=>"B-",'AB+'=>"AB+",'AB-'=>"AB-",'O+'=>"O+",'O-'=>"O-");
							echo $this->Form->input('blood_group', ['options'=>$arrblood,'label' => false,'class' => 'form-control select2me','placeholder'=>'Blood Group']);?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Education Qualification</label>
							<?php echo $this->Form->input('qualification', ['label' => false,'class' => 'form-control input-sm','maxlength'=>100,'placeholder'=>'Education Qualification']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label"> Last Company worked</label>
							<?php echo $this->Form->input('last_company', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Last Company Name']); ?>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Date of Confirmation as Permanent</label>
							<?php echo $this->Form->input('permanent_join_date', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','placeholder' => 'Date of Confirmation','value'=>$employee->permanent_join_date->format('d-m-Y')]); ?>
					</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Marital Status<span class="required" aria-required="true">*</span></label>
								<div class="radio-list">
								<div class="radio-inline" data-error-container="#marital_status_required_error">
									<?php echo $this->Form->radio(
											'marital_status',
											[
												['value' => 'Single', 'text' => 'Single', 'id'=>'id_radio1'],
												['value' => 'Married', 'text' => 'Married', 'id'=>'id_radio2'],
												['value' => 'Divorced', 'text' => 'Divorced', 'id'=>'id_radio3']
											]
									); ?>
								</div>
								<div id="marital_status_error"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="row"  id="married_info" style="display:none;">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Spouse Name</label>
							<?php echo $this->Form->input('spouse_name', ['label' => false,'class' => 'form-control input-sm spouse','placeholder'=>'Spouse Name']); ?>
						</div>
					</div>
				
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Date Of Anniversary</label>
							<?php echo $this->Form->input('date_of_anniversary', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker doba','data-date-format' => 'dd-mm-yyyy','placeholder' => 'Date of Anniversary','value'=>$employee->date_of_anniversary->format('d-m-Y')]); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Number Of Child</label>
							<?php echo $this->Form->input('children', ['label' => false,'class' => 'form-control input-sm nochild','placeholder' => 'Number Of Child']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label"> Whether Spouse Working?</label>
								<div class="radio-list">
								<div class="radio-inline">
									<?php echo $this->Form->radio(
											'spouse_working',
											[
												['value' => 'Yes', 'text' => 'Yes'],
												['value' => 'No', 'text' => 'No']
											]
									); ?>
								</div>
								<div id="spouse_working_error"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Permanent Address<span class="required" aria-required="true">*</span> </label>
							<?php echo $this->Form->input('permanent_address', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Permanent Address']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Residence Address <span class="required" aria-required="true">*</span></label>
							<?php echo $this->Form->input('residence_address', ['label' => false,'class' => 'form-control input-sm','placeholder'=>'Residence Address']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Signature <span class="required" aria-required="true">*</span></label>
							<div><?php echo $this->Html->image('/signatures/'.$employee->signature, ['height' => '50px']); ?></div>
							<?php echo $this->Form->input('signature', ['type' => 'file','label' => false]);?>
							<span class="help-block">Upload transparent Signature of size 420 x 165 </span>
						</div>
					</div>
				</div>
			</fieldset>	
			<fieldset style="margin-left:  0px;margin-right: 0px;">		
				<legend><b>Create Ledger </b></legend>	
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Account Category<span class="required" aria-required="true">*</span></label>
							<?php echo $this->Form->input('account_category_id', ['options'=>$AccountCategories,'empty' => "--Select Account Category--",'label' => false,'class' => 'form-control input-sm select2me']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
						<label class="control-label">Account Group <span class="required" aria-required="true">*</span></label>
							<div id="account_group_div">
							<?php echo $this->Form->input('account_group_id', ['options' => $AccountGroups,'label' => false,'empty' => "--Select Account Group--",'class' => 'form-control input-sm select2me','placeholder'=>'Account Group']); ?>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
						<label class="control-label">Account First Sub Group <span class="required" aria-required="true">*</span></label>
							<div id="account_first_subgroup_div">
							<?php echo $this->Form->input('account_first_subgroup_id', ['options' => $AccountFirstSubgroups,'label' => false,'empty' => "--Select First Sub Group--",'class' => 'form-control input-sm select2me','placeholder'=>'Account First Sub Group']); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Account Second Sub Group <span class="required" aria-required="true">*</span></label>
							<div id="account_second_subgroup_div">
							<?php echo $this->Form->input('account_second_subgroup_id', ['empty' => "--Select Second Sub Group--",'options' => $AccountSecondSubgroups,'label' => false,'class' => 'form-control input-sm select2me','placeholder'=>'Account Second Sub Group']); ?>
							</div>
						</div>
					</div>
				</div>
			</fieldset>	
			<fieldset style="margin-left:  0px;margin-right: 0px;">	
			<legend><b>Present Address details  </b></legend>
			<div class="col-md-12 pad">
				
			      <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">State</label>
							<?php  
							echo $this->Form->input('present_state', ['empty'=> '---Select State---','label' => false,'class'=>'form-control select2me input-sm state_change','options'=>@$states,'div_id'=>'2','district'=>'present_district']);
							?>   
						</div>
					</div>
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">District</label>
							<div id="present_district2">
								<?php echo $this->Form->input('present_district', ['empty'=> '---Select District---','label' => false,'class'=>'form-control select2me input-sm','options'=>'']); ?>
							</div>
						</div>
					</div>
			       <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Pin code</label>
							<?php echo $this->Form->input('present_pin_code', ['label' => false,'placeholder'=>'Pin Code','class'=>'form-control input-sm','maxlength'=>6,'minlength'=>6]); ?>
						</div>
					</div>
			</div>
			<div class="col-md-12 pad">
				
			      <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Mobile No</label>
							<?php echo $this->Form->input('present_mobile_no', ['label' => false,'placeholder'=>'Mobile No','class'=>'form-control input-sm','maxlength'=>10,'minlength'=>10]); ?>
						</div>
					</div>
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Phone NO</label>
							<?php echo $this->Form->input('present_phone_no', ['label' => false,'placeholder'=>'Phone No','class'=>'form-control input-sm']); ?>
						</div>
					</div>
			       <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Email</label>
							<?php echo $this->Form->input('present_email', ['label' => false,'placeholder'=>'Email','class'=>'form-control input-sm','type'=>'email']); ?>
						</div>
					</div>
			</div>
			<div class="col-md-12 pad">
				
			        <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Present Address</label>
							<?php echo $this->Form->input('present_address', ['label' => false,'placeholder'=>'Present Address','class'=>'form-control input-sm','type'=>'textarea','rows'=>2]); ?>
						</div>
					</div>
			</div>
		</fieldset>	
		<fieldset style="margin-left:  0px;margin-right: 0px;">		
			<legend><b>Permanent Address details  </b></legend>
			<div class="col-md-12 pad">
			  <div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">State</label>
						<?php echo $this->Form->input('permanent_state', ['empty'=> '---Select State---','label' => false,'class'=>'form-control select2me input-sm state_change','options'=>@$states,'div_id'=>'3','district'=>'permanent_district']); ?>
					</div>
				</div>
			   <div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">District</label>
						<div id="permanent_district3">
						<?php echo $this->Form->input('permanent_district', ['empty'=> '---Select District---','label' => false,'class'=>'form-control select2me input-sm','options'=>'']); ?>
						</div>
					</div>
				</div>
			   <div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Pin code</label>
						<?php echo $this->Form->input('permanent_pin_code', ['label' => false,'placeholder'=>'Pin Code','class'=>'form-control input-sm','maxlength'=>6,'minlength'=>6]); ?>
					</div>
				</div>
			</div>
			<div class="col-md-12 pad">
			  <div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Mobile No</label>
						<?php echo $this->Form->input('permanent_mobile_no', ['label' => false,'placeholder'=>'Mobile No','class'=>'form-control input-sm','maxlength'=>10,'minlength'=>10]); ?>
					</div>
				</div>
			   <div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Phone NO</label>
						<?php echo $this->Form->input('permanent_phone_no', ['label' => false,'placeholder'=>'Phone No','class'=>'form-control input-sm']); ?>
					</div>
				</div>
			   <div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Email</label>
						<?php echo $this->Form->input('permanent_email', ['label' => false,'placeholder'=>'Email','class'=>'form-control input-sm','type'=>'email']); ?>
					</div>
				</div>
			</div>
			<div class="col-md-12 pad">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Permanent Address</label>
						<?php echo $this->Form->input('permanent_address', ['label' => false,'placeholder'=>'Permanent Address','class'=>'form-control input-sm','type'=>'textarea','rows'=>2]); ?>
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset style="margin-left:  0px;margin-right: 0px;">		
			<legend><b>Employee  Nomination Information  </b></legend>
			<div class="col-md-12 pad">
				
			      <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Nominee Name</label>
							<?php echo $this->Form->input('nominee_name', ['label' => false,'class'=>'form-control input-sm','placeholder'=>'Nominee Name']); ?>
						</div>
					</div>
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Relation with the employee</label>
							<?php echo $this->Form->input('relation_with_employee', ['label' => false,'class'=>'form-control input-sm','placeholder'=>'Relation with the employee']); ?>
						</div>
					</div>
			       <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Type of Nomination</label>
							<?php echo $this->Form->input('nomination_type', ['label' => false,'class'=>'form-control input-sm','placeholder'=>'Type of Nomination']); ?>
						</div>
					</div>
			</div>
			<div class="col-md-12 pad">
				
			      <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">State</label>
							<?php echo $this->Form->input('nominee_state', ['empty'=> '---Select State---','label' => false,'class'=>'form-control select2me input-sm state_change','options'=>@$states,'div_id'=>'4','district'=>'nominee_district']); ?>
						</div>
					</div> 
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">District</label>
							<div id="nominee_district4">
								<?php echo $this->Form->input('nominee_district', ['empty'=> '---Select District---','label' => false,'class'=>'form-control select2me input-sm','options'=>'']); ?>
							</div>
						</div>
					</div>
			       <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Pin Code</label>
							<?php echo $this->Form->input('nominee_pin_code', ['label' => false,'placeholder'=>'Pin Code','class'=>'form-control input-sm','maxlength'=>6,'minlength'=>6]); ?>
						</div>
					</div>
			</div>
			<div class="col-md-12 pad">
				
			        <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Mobile No</label>
							<?php echo $this->Form->input('nominee_mobile_no', ['label' => false,'placeholder'=>'Mobile No','class'=>'form-control input-sm','maxlength'=>10,'minlength'=>10]); ?>
						</div>
					</div>
					
			        <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Present Address</label>
							<?php echo $this->Form->input('nominee_present_address', ['label' => false,'placeholder'=>'Present Address','class'=>'form-control input-sm','type'=>'textarea','rows'=>2]);  ?>
						</div>
					</div>
			</div>
		</fieldset>
		<fieldset style="margin-left:  0px;margin-right: 0px;">		
			<legend><b>Employee Professional Information  ( Joining  Details )</b></legend>
			<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Department <span class="required" aria-required="true">*</span></label>
							<?php echo $this->Form->input('dipartment_id', ['empty' => 'Select Department','options' => $departments,'label' => false,'class' => 'form-control select2me']); ?>
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
							<?php echo $this->Form->input('join_date', ['type' => 'text','label' => false,'class' => 'form-control input-sm date-picker','data-date-format' => 'dd-mm-yyyy','placeholder' => 'Date of Joining','value'=>$employee->join_date->format('d-m-Y')]); ?>
						</div>
					</div>
				</div>
			<div class="col-md-12 pad">
			      <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Date of Appointment</label>
							<?php if(empty($employee->appointment_date)){ ?>
									<?php echo $this->Form->input('appointment_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text']); ?>
							<?php }else{  ?>
									<?php echo $this->Form->input('appointment_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text','value'=>$employee->appointment_date->format('d-m-Y')]); ?>
							<?php } ?>
							
						</div>
					</div>
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Employee ID </label>
							<?php echo $this->Form->input('employee_id_no', ['label' => false,'class'=>'form-control input-sm','placeholder'=>'Employee ID','type'=>'text']); ?>
						</div>
					</div>
			       <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Date of Joining in the Deptt</label>
							<?php if(empty($employee->dept_joining_date)){ ?>
									<?php echo $this->Form->input('dept_joining_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text']); ?>
							<?php }else{  ?>
										<?php echo $this->Form->input('dept_joining_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text','value'=>$employee->dept_joining_date->format('d-m-Y')]); ?>
							<?php } ?>
						
						</div>
					</div>
			</div>
			<div class="col-md-12 pad">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Initial Designation </label>
						<?php echo $this->Form->input('initial_designation', ['label' => false,'placeholder'=>'Initial Designation','class'=>'form-control input-sm']); ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Office Name at the time of initial joining in Deptt</label>
						<?php echo $this->Form->input('office_name', ['label' => false,'placeholder'=>'Office Name','class'=>'form-control input-sm']); ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Mode of Recruitment</label>
						<?php echo $this->Form->input('recruitment_mode', ['label' => false,'placeholder'=>'Mode of Recruitment','class'=>'form-control input-sm']); ?>
					</div>
				</div>
			</div>
			<div class="col-md-12 pad">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Reporting To </label>
						<?php echo $this->Form->input('reporting_to', ['options'=>$Employees,'label' => false,'class'=>'form-control input-sm']); ?>
					</div>
				</div>
			</div>
		</fieldset>		
		<fieldset style="margin-left:  0px;margin-right: 0px;">		
			<legend><b>Bank's Detail</b></legend>			
			<table class="table table-condensed tableitm">
				<thead>
					<tr>
						<th><label class="control-label">Account Type<label></th>
						<th><label class="control-label">Bank Name<label></th>
						<th><label class="control-label">Account Number<label></th>
						<th><label class="control-label">Branch Name<label></th>
						<th><label class="control-label">IFSC Code<label></th>
						
					</tr>
				</thead>
				<tbody>
					<td>
						<?php 
						$account_type[]=['value'=>'Current ','text'=>'Current'];
						$account_type[]=['value'=>'Saving ','text'=>'Saving'];
						echo $this->Form->input('account_type', ['empty'=> '--Select--','data-placeholder'=>'Gender','label' => false,'class'=>'form-control select2 input-sm','options'=>@$account_type,'style'=>'width:100%;']);
						?>
					</td>
					<td>
						<?php echo $this->Form->input('bank_name', ['label' => false,'class' => 'form-control input-sm firstupercase allAlpha','placeholder'=>'Bank Name']); ?>
					</td>
					<td>
						<?php echo $this->Form->input('account_no', ['label' => false,'class' => 'form-control input-sm nospace allLetter','maxlength'=>20,'placeholder'=>'Account No']); ?>
					</td>
					<td>
						<?php echo $this->Form->input('branch_name', ['label' => false,'class' => 'form-control input-sm firstupercase allAlpha','placeholder'=>'Branch Name']); ?>
					</td>
					<td>
						<?php echo $this->Form->input('ifsc_code', ['label' => false,'class' => 'form-control input-sm ','id' => 'ifsc_cod','placeholder'=>'IFSC Code']); ?>
					</td>
				</tbody>
			</table>	
		</fieldset>	
				
		<div class="row">
			<div class="col-md-4">
				<label class="control-label">Work In Companies</label>
				<?php echo $this->Form->input('companies._ids', ['label' => false,'options' => $Companies,'multiple' => 'checkbox']); ?>
			</div>
		</div>
		</div>
		
		
			<div class="form-actions">
					<button type="submit" class="btn btn-primary">UPDATE</button>
			</div>
		</div>
		<?= $this->Form->end() ?>
		<!-- END FORM-->
	</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
	//--------- FORM VALIDATION
	var form3 = $('#form_sample_3');
	var error3 = $('.alert-danger', form3);
	var success3 = $('.alert-success', form3);
	form3.validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block help-block-error', // default input error message class
		focusInvalid: true, // do not focus the last invalid input
		rules: {
			name:{
				required: true,
			},
			dipartment_id : {
				  required: true,
			},
			sex : {
				  required: true,
			},
			relation : {
				  required: true,
			},
			mobile : {
				  required: true,
				  digits: true,
				  minlength: 10,
				  maxlength: 10,
			},
			join_date:{
				required: true,
			},
			email:{
				required: true,
			},
			signature:{
				required: true,
			},
			designation_id:{
				required: true,
			},
			permanent_address:{
				required: true,
			},
			residence_address:{
				required: true,
			},
			marital_status:{
				 required: true,
			},

			account_category_id:{
				 required: true,
			},
			account_group_id:{
				 required: true,
			},
			account_first_subgroup_id:{
				 required: true,
			},
			account_second_subgroup_id:{
				 required: true,
			},

			bank_name:{
				 required: true,
			},
			account_no:{
				 required: true,
			},
			branch_name:{
				 required: true,
			},
			ifsc_code:{
				 required: true,
			},
			over_time:{
				required: true,
			}
		},

		messages: { // custom messages for radio buttons and checkboxes
			membership: {
				required: "Please select a Membership type"
			},
			service: {
				required: "Please select  at least 2 types of Service",
				minlength: jQuery.validator.format("Please select  at least {0} types of Service")
			}
		},

		errorPlacement: function (error, element) { // render error placement for each input type
			if (element.parent(".input-group").size() > 0) {
				error.insertAfter(element.parent(".input-group"));
			} else if (element.attr("data-error-container")) { 
				error.appendTo(element.attr("data-error-container"));
			} else if (element.parents('.radio-list').size() > 0) { 
				error.appendTo(element.parents('.radio-list').attr("data-error-container"));
			} else if (element.parents('.radio-inline').size() > 0) { 
				error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
			} else if (element.parents('.checkbox-list').size() > 0) {
				error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
			} else if (element.parents('.checkbox-inline').size() > 0) { 
				error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
			} else {
				error.insertAfter(element); // for other inputs, just perform default behavior
			}
		},

		invalidHandler: function (event, validator) { //display error alert on form submit   
			success3.hide();
			error3.show();
			//Metronic.scrollTo(error3, -200);
		},

		highlight: function (element) { // hightlight error inputs
		   $(element)
				.closest('.form-group').addClass('has-error'); // set error class to the control group
		},

		unhighlight: function (element) { // revert the change done by hightlight
			$(element)
				.closest('.form-group').removeClass('has-error'); // set error class to the control group
		},

		success: function (label) {
			label
				.closest('.form-group').removeClass('has-error'); // set success class to the control group
		},

		submitHandler: function (form) {
			q="ok";
			$("#main_tb tbody tr.tr1").each(function(){
				var w=$(this).find("td:nth-child(3) input").val();
				var r=$(this).find("td:nth-child(4) input").val();
				if(w=="" || r==""){
					q="e";
				}
			});
			if(q=="e"){
				$("#row_error").show();
				return false;
			}else{
				$('#submitbtn').prop('disables',true);
				$('#submitbtn').text('submitting....');
				success3.show();
				error3.hide();
				form[0].submit(); // submit the form
			}
		}

	});
	//--	 END OF VALIDATION
	$("#inputTextBox").keypress(function(event){
        var inputValue = event.which;
        // allow letters and whitespaces only.
        if(!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 0)) { 
            event.preventDefault(); 
        }
    });
	$('.allLetter').live("keyup",function(){
		var inputtxt=  $(this).val();
		var numbers =  /^[0-9]*\.?[0-9]*$/;
		
		if(inputtxt.match(numbers))  
		{  
		} 
		else  
		{  
			$(this).val('');
			return false;  
		}
	});	
	$('.allAlpha').live("keyup",function(){
		var inputtxt=  $(this).val();
		var numbers =  /^[a-zA-Z\s]+$/;
		
		if(inputtxt.match(numbers))  
		{  
		} 
		else  
		{  
			$(this).val('');
			return false;  
		}
	});	
	$('.land_line').live("keyup",function(){
		var inputtxt=  $(this).val();
		var numbers =  /^[0-9,\+-]+$/;
		
		if(inputtxt.match(numbers))  
		{  
		} 
		else  
		{  
			$(this).val('');
			return false;  
		}
	});	
	$('.ifsc_cod').live("keyup",function(){
		var inputtxt=  $(this).val();
		var numbers =  /^[A-Za-z]{4}\d{7}$/;
		
		if(inputtxt.match(numbers))  
		{  
		} 
		else  
		{  
			$(this).val('');
			return false;  
		}
	});

	//account group 
	$('select[name="account_category_id"]').on("change",function() {
	$('#account_group_div').html('Loading...');
	var accountCategoryId=$('select[name="account_category_id"] option:selected').val();
	var url="<?php echo $this->Url->build(['controller'=>'AccountGroups','action'=>'AccountGroupDropdown']); ?>";
	url=url+'/'+accountCategoryId,
	$.ajax({
		url: url,
		type: 'GET',
	}).done(function(response) {
		$('#account_group_div').html(response);
		$('select[name="account_group_id"]').select2();
	});
});
	
	
$('select[name="account_group_id"]').die().live("change",function() {

	$('#account_first_subgroup_div').html('Loading...');
	var accountGroupId=$('select[name="account_group_id"] option:selected').val();
	var url="<?php echo $this->Url->build(['controller'=>'AccountFirstSubgroups','action'=>'AccountFirstSubgroupDropdown']); ?>";
	url=url+'/'+accountGroupId,
	$.ajax({
		url: url,
		type: 'GET',
	}).done(function(response) {
		$('#account_first_subgroup_div').html(response);
		$('select[name="account_first_subgroup_id"]').select2();
	});
});
	
$('select[name="account_first_subgroup_id"]').die().live("change",function() {
	$('#account_second_subgroup_div').html('Loading...');
	var accountFirstSubgroupId=$('select[name="account_first_subgroup_id"] option:selected').val();
	var url="<?php echo $this->Url->build(['controller'=>'AccountSecondSubgroups','action'=>'AccountSecondSubgroupDropdown']); ?>";
	url=url+'/'+accountFirstSubgroupId,
	$.ajax({
		url: url,
		type: 'GET',
	}).done(function(response) {
		$('#account_second_subgroup_div').html(response);
		$('select[name="account_second_subgroup_id"]').select2();
	});
});	
	
	
});
</script>
 <script type="text/javascript">
$(document).ready(function () {
	$('#id_radio2').click(function () {
		$('#married_info').show('fast');
	});
	$('#id_radio1').click(function () {
		$('.spouse').val('');
		$('.doba').val('');
		$('.nochild').val('');
		$('#married_info').hide('fast');
	});
	$('#id_radio3').click(function () {
		$('.spouse').val('');
		$('.doba').val('');
		$('.nochild').val('');
		$('#married_info').hide('fast');
	});
	if ($('#id_radio2').is(':checked')) {
		$('#married_info').show('fast');
	}else{
		$('#married_info').hide('fast');
	}
});
</script>