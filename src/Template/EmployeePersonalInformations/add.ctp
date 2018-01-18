<style>
.pad{
	padding-right: 0px;
padding-left: 0px;
}
.form-group
{
	margin-bottom: 0px;
}

fieldset {
 padding: 10px ;
 border: 1px solid #bfb7b7f7;
 margin: 12px;
}
legend{
margin-left: 20px;	
//color:#144277; 
color:#144277c9; 
font-size: 17px;
margin-bottom: 0px;
border:none;
}

.label-css{
	font-size: 11px !important;
}
</style>



				
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">EMPLOYEE PERSONAL INFORMATION</span>
		</div>
	</div>

<div class="portlet-body form">
		<?php echo $this->Form->create($employeePersonalInformation, ['id'=>'form_sample_3','enctype'=>'multipart/form-data']); ?>
				
		<fieldset style="margin-left:  0px;margin-right: 0px;">	
			<legend><b>Personal Information </b></legend>
			
			<div class="form-body">
				<div class="row">
				
			      <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">First Name</label>
							<?php echo $this->Form->input('first_name', ['label' => false,'placeholder'=>'First Name','class'=>'form-control input-sm']); ?>
						</div>
					</div>
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Middle Name</label>
							<?php echo $this->Form->input('middle_name', ['label' => false,'placeholder'=>'Middle Name','class'=>'form-control input-sm']); ?>
						</div>
					</div>
			       <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Last Name</label>
							<?php echo $this->Form->input('last_name', ['label' => false,'placeholder'=>'Last Name','class'=>'form-control input-sm']); ?>
						</div>
					</div>
			</div>
			<div class="row">
				<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Date of Birth</label>
							<?php echo $this->Form->input('date_of_birth', ['label' => false,'placeholder'=>'Date of Joining','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text','placeholder'=>'dd-mm-yyyy']); ?>
						</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Member Type</label><br/>
						
						<?php
						$members[]=['value'=>'Father ','text'=>'Father'];
						$members[]=['value'=>'Mother ','text'=>'Mother'];
						$members[]=['value'=>'Husband ','text'=>'Husband'];
						echo $this->Form->input('family_member_type', ['empty'=> '--Select--','data-placeholder'=>'Member Type','label' => false,'class'=>'form-control select2 input-sm','options'=>@$members,'style'=>'width:100%;']); ?>
							
					</div>
				</div>
				<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Member Name</label>
							<?php echo $this->Form->input('family_member_name', ['label' => false,'placeholder'=>'Member Name','class'=>'form-control input-sm','type'=>'text']); ?>
						</div>
				</div>
				
			</div>
			<div class="row">
			
				<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Gender </label>
							<?php
							$genders[]=['value'=>'Male ','text'=>'Male'];
							$genders[]=['value'=>'Female ','text'=>'Female'];
							$genders[]=['value'=>'Other','text'=>'Other'];
							echo $this->Form->input('gender', ['empty'=> '--Select--','data-placeholder'=>'Gender','label' => false,'class'=>'form-control select2 input-sm','options'=>@$genders,'style'=>'width:100%;']); ?>
						</div>
				</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Identity Mark</label>
						<?php echo $this->Form->input('identity_mark', ['label' => false,'placeholder'=>'Identity Mark','class'=>'form-control input-sm','type'=>'text']); ?>
							<label id="grade-error" class="error" for="grade"></label>
						</div>
					</div>
			
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Caste</label>
						<?php echo $this->Form->input('caste', ['label' => false,'placeholder'=>'Caste','class'=>'form-control input-sm','type'=>'text']); ?>
							<label id="category-error" class="error" for="category"></label>
						</div>
					</div>
					
			</div>
			<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Religion</label>
							<?php 
							echo $this->Form->input('religion', ['label' => false,'placeholder'=>'Religion','class'=>'form-control input-sm','type'=>'text']); ?>
							<label id="classification-error" class="error" for="classification"></label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Home State</label>
							<?php 
							echo $this->Form->input('home_state', ['empty'=> '---Select State---','label' => false,'class'=>'form-control select2me input-sm state_change','options'=>@$states,'div_id'=>'1','district'=>'home_district']); ?>
						</div> 
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Home District</label>
							<div id="home_district1">
								<?php echo $this->Form->input('home_district', ['empty'=> '---Select District---','label' => false,'class'=>'form-control select2me input-sm','options'=>'']); ?>
							</div>
						</div>
					</div>
					
			</div>
			
			
			<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Adhar Card No</label>
							<?php echo $this->Form->input('adhar_card_no', ['label' => false,'placeholder'=>'Adhar Card No','class'=>'form-control input-sm','maxlength'=>12,'minlength'=>12]); ?>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Passport No</label>
							<?php echo $this->Form->input('passport_no', ['label' => false,'placeholder'=>'Passport No','class'=>'form-control input-sm','type'=>'text','style'=>'resize:none;','rows'=>'2']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Martial Status</label>
							<?php echo $this->Form->input('martial_status', ['label' => false,'placeholder'=>'Martial Status','class'=>'form-control input-sm','type'=>'text','style'=>'resize:none;','rows'=>'2']); ?>
						</div>
					</div>
					
			</div>
			
			<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Height (in feet)</label>
							<?php
							echo $this->Form->input('height', ['data-placeholder'=>'Height','label' => false,'class'=>'form-control input-sm','placeholder'=>'Height']); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Category</label>
							<?php echo $this->Form->input('category', ['label' => false,'placeholder'=>'Category','class'=>'form-control input-sm','placeholder'=>'Category']); ?>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Blood Group</label>
							<?php echo $this->Form->input('blood_group', ['label' => false,'placeholder'=>'Blood Group','class'=>'form-control input-sm','type'=>'text']); ?>
						</div>
					</div>
			
			</div>
			<div class="row">
					<div class="col-md-4">
								<div class="form-group">
									<label class="control-label  label-css">Account Type</label>
									<?php
									$account_type[]=['value'=>'Current ','text'=>'Current'];
									$account_type[]=['value'=>'Saving ','text'=>'Saving'];
									echo $this->Form->input('account_type', ['empty'=> '--Select--','data-placeholder'=>'Gender','label' => false,'class'=>'form-control select2 input-sm','options'=>@$account_type,'style'=>'width:100%;']); ?>
								</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Account No</label>
							<?php echo $this->Form->input('account_no', ['label' => false,'options'=>@$state,'placeholder'=>'Account No','class'=>'form-control input-sm']); ?>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">IFSC code of branch</label>
							<?php echo $this->Form->input('branch_ifsc_code', ['label' => false,'placeholder'=>'IFSC Code Of Branch','class'=>'form-control input-sm','type'=>'text','maxlength'=>11,'minlength'=>11]); ?>
						</div>
					</div>
			
			</div>
			<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Bank & Branch</label>
							<?php echo $this->Form->input('bank_branch', ['label' => false,'placeholder'=>'Bank & Branch','class'=>'form-control input-sm','type'=>'text']); ?>
						</div>
					</div>
					<div class="col-md-4">
								<div class="form-group">
									<label class="control-label  label-css">Driving Licence No</label>
									<?php
									echo $this->Form->input('driving_licence_no', ['data-placeholder'=>'Driving Licence No','label' => false,'class'=>'form-control input-sm','placeholder'=>'Driving Licence No']); ?>
								</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Pan card No</label>
							<?php echo $this->Form->input('pan_card_no', ['label' => false,'placeholder'=>'Pan Card No','class'=>'form-control input-sm']); ?>
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
			<legend><b>Employee  Family Information </b></legend>
			<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="100%">
				<thead>
					<tr align="center">
					   <td><label>S.n.<label></td>
					   <td><label>Family Member Name<label></td>
					   <td><label>Relation<label></td>
					   <td><label>Date of Birth<label></td>
					   <td><label>Dependent<label></td>
					   <td><label>Whether Employed (State / Centre / unemployed Private)<label></td>
					   <td></td>
					</tr>
				</thead>
				<tbody id='main_tbody' class="tab">
					
				</tbody>
			</table>
		</fieldset>
		<fieldset style="margin-left:  0px;margin-right: 0px;">		
			<legend><b>Employee Emergency Details </b></legend>
			<table id="main_table2" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="100%">
				<thead>
					<tr align="center">
					   <td><label>S.n.<label></td>
					   <td><label>Name<label></td>
					   <td><label>Relationship<label></td>
					   <td><label>Mobile No<label></td>
					   <td><label>Telephone No<label></td>
					   <td><label>Address<label></td>
					   <td></td>
					</tr>
				</thead>
				<tbody id='main_tbody2' class="tab">
					
				</tbody>
			</table>
		</fieldset>
		<fieldset style="margin-left:  0px;margin-right: 0px;">		
			<legend><b>Employee Reference Details </b></legend>
			<table id="main_table3" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="100%">
				<thead>
					<tr align="center">
					   <td><label>S.n.<label></td>
					   <td><label>Name<label></td>
					   <td><label>Mobile No<label></td>
					   <td><label>Telephone No<label></td>
					   <td><label>Address<label></td>
					   <td></td>
					</tr>
				</thead>
				<tbody id='main_tbody3' class="tab">
					
				</tbody>
			</table>
		</fieldset>
		<fieldset style="margin-left:  0px;margin-right: 0px;">		
			<legend><b>Employee Work Experience </b></legend>
			<table id="main_table4" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="100%">
				<thead>
					<tr align="center">
					   <td><label>S.n.<label></td>
					   <td><label>Period<label></td>
					   <td><label>Name of Company<label></td>
					   <td><label>Designation<label></td>
					   <td><label>Nature of the Duties<label></td>
					   <td></td>
					</tr>
				</thead>
				<tbody id='main_tbody4' class="tab">
					
				</tbody>
			</table>
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
			<div class="col-md-12 pad">
				
			      <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Date of Appointment</label>
							<?php echo $this->Form->input('appointment_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text']); ?>
						</div>
					</div>
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Employee ID </label>
							<?php echo $this->Form->input('employee_id', ['label' => false,'class'=>'form-control input-sm','placeholder'=>'Employee ID','type'=>'text']); ?>
						</div>
					</div>
			       <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Date of Joining in the Deptt</label>
							<?php echo $this->Form->input('dept_joining_date', ['label' => false,'placeholder'=>'dd-mm-yyyy','class'=>'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy', 'type'=>'text']); ?>
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
							<?php echo $this->Form->input('reporting_to', ['label' => false,'placeholder'=>'Reporting To','class'=>'form-control input-sm']); ?>
						</div>
					</div>
			</div>
		</fieldset>
		<fieldset style="margin-left:  0px;margin-right: 0px;">		
			<legend><b>Salary Details -(At The Time of Initial Joining )</b></legend>
			<div class="col-md-12 pad">
				
			        <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Basic Pay</label>
							<?php echo $this->Form->input('basic_pay', ['label' => false,'placeholder'=>'Rs','class'=>'form-control input-sm','type'=>'text']); ?>
						</div>
					</div>
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Date of Retirement </label>
							<?php echo $this->Form->input('retirement_date', ['label' => false,'class'=>'form-control date-picker input-sm','data-date-format'=>'dd-mm-yyyy','placeholder'=>'dd-mm-yyyy','type'=>'text']); ?>
						</div>
					</div>
			        <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Deduction Type (SD)</label>
							<?php echo $this->Form->input('deduction_type', ['label' => false,'placeholder'=>'Deduction Type (SD)','class'=>'form-control input-sm', 'type'=>'text']); ?>
						</div>
					</div>
			</div>
			<div class="col-md-12 pad">
			        <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">GPF Number   </label>
							<?php echo $this->Form->input('gpf_no', ['label' => false,'placeholder'=>'GPF Number ','class'=>'form-control input-sm']); ?>
						</div>
					</div>
			</div>
		</fieldset>
			</div>
			</div>
			<div class="box-footer">
				<center>
				
				 <button type="submit" class="btn btn-primary" id='submitbtn' >Register</button>
				</center>
			</div>
			</div>
			<?php echo $this->Form->end(); ?>
			</div>
					
			
					
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>

$(document).ready(function() 
{
    	//--------- FORM VALIDATION
	var form3 = $('#form_sample_3');
	var error3 = $('.alert-danger', form3);
	var success3 = $('.alert-success', form3);
	form3.validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block help-block-error', // default input error message class
		focusInvalid: true, // do not focus the last invalid input
		rules: {
			first_name:{
				required: true,
			},
			middle_name : {
				  required: true,
			},
			last_name : {
				  required: true,
			},
			date_of_birth : {
				  required: true,
			},			
			family_member_type:{
				required: true
			},
			family_member_name:{
				required: true,
			},
			gender:{
				required: true,
			},
			identity_mark : {
				  required: true,
			},
			caste  : {
				  required: true,
			},
			religion: {
				  required: true,
			},
			home_state: {
				  required: true,
			},
			home_district: {
				required: true,
			},
			adhar_card_no: {
				required: true,
			},
			martial_status: {
				required: true,
			},
			account_type: {
				required: true,
			},
			account_no: {
				required: true,
			},
			branch_ifsc_code: {
				required: true,
			},
			driving_licence_no: {
				required: true,
			},
			bank_branch: {
				required: true,
			},
			present_state: {
				required: true,
			},
			present_district: {
				required: true,
			},
			present_pin_code: {
				required: true,
			},
			present_mobile_no: {
				required: true,
			},
			present_address: {
				required: true,
			},
			permanent_state: {
				required: true,
			},
			permanent_district: {
				required: true,
			},
			permanent_pin_code: {
				required: true,
			},
			permanent_mobile_no: {
				required: true,
			},
			permanent_address: {
				required: true,
			},
			nominee_name: {
				required: true,
			},
			relation_with_employee: {
				required: true,
			},
			nominee_state: {
				required: true,
			},
			nominee_district: {
				required: true,
			},
			nominee_pin_code: {
				required: true,
			},
			nominee_mobile_no: {
				required: true,
			},
			nominee_present_address: {
				required: true,
			},
			appointment_date: {
				required: true,
			},
			employee_id: {
				required: true,
			},
			dept_joining_date: {
				required: true,
			},
			initial_designation: {
				required: true,
			},
			office_name: {
				required: true,
			},
			recruitment_mode: {
				required: true,
			},
			reporting_to: {
				required: true,
			},
			basic_pay: {
				required: true,
			},
			retirement_date: {
				required: true,
			},
			deduction_type: {
				required: true,
			},
			gpf_no: {
				required: true,
			},
			
		},

		messages: { // custom messages for radio buttons and checkboxes
			
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
			//put_code_description();
			success3.hide();
			error3.show();
			//$("#add_submit").removeAttr("disabled");
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
			
			success3.show();
			error3.hide();
			form[0].submit();
		}

	});
	
	//--	 END OF VALIDATION
	$('.add').live('click',function()
	{ 
		add_row();
	});
	add_row();
	function add_row()
	{ 
			var tr=$("#sample_table tbody tr.main_tr").clone();
			$("#main_table tbody#main_tbody").append(tr);
			
			rename_rows();
			rename_rows2();
			rename_rows3();
			rename_rows4();
	}

	
	rename_rows();
	var a=0;
	function rename_rows(){ 
				var i=0;
				$("#main_table tbody#main_tbody tr.main_tr").each(function(){ 
					$(this).find('td:nth-child(1)').html(i+1);
					$(this).find("td:nth-child(2) input.first").attr({name:"employee_personal_information_rows["+i+"][name]", id:"employee_personal_information_rows-"+i+"-name"}).rules("add", "required");
					$(this).find("td:nth-child(2) input.hidden").attr({name:"employee_personal_information_rows["+i+"][detail_type]", id:"employee_personal_information_rows-"+i+"-detail_type"});
					$(this).find("td:nth-child(3) input").attr({name:"employee_personal_information_rows["+i+"][relation]", id:"employee_personal_information_rows-"+i+"-relation"}).rules("add", "required");		
					$(this).find("td:nth-child(4) input").datepicker().attr({name:"employee_personal_information_rows["+i+"][dob]", id:"employee_personal_information_rows-"+i+"-dob"}).rules("add", "required");
					$(this).find("td:nth-child(5) input").attr({name:"employee_personal_information_rows["+i+"][dependent]", id:"employee_personal_information_rows-"+i+"-dependent"}).rules("add", "required");
					$(this).find("td:nth-child(6) select").attr({name:"employee_personal_information_rows["+i+"][whether_employed]", id:"employee_personal_information_rows-"+i+"-whether_employed"}).rules("add", "required");
					
					i++;
				}); 
				a=i;
			}
			
	$('.add2').live('click',function()
	{ 
		add_row2();
	});
	add_row2();
	function add_row2()
	{ 
			var tr=$("#sample_table2 tbody tr.main_tr").clone();
			$("#main_table2 tbody#main_tbody2").append(tr);
			
			rename_rows();
			rename_rows2();
			rename_rows3();
			rename_rows4();
	}
	rename_rows2();
	var j=0;
	var b=0;
	function rename_rows2(){ 
				var ii=0; 
				var j=a; 
				$("#main_table2 tbody#main_tbody2 tr.main_tr").each(function(){ 
					$(this).find('td:nth-child(1)').html(ii+1);
					$(this).find("td:nth-child(2) input.first").attr({name:"employee_personal_information_rows["+j+"][name]", id:"employee_personal_information_rows-"+j+"-name"}).rules("add", "required");
					$(this).find("td:nth-child(2) input.hidden").attr({name:"employee_personal_information_rows["+j+"][detail_type]", id:"employee_personal_information_rows-"+j+"-detail_type"});
					$(this).find("td:nth-child(3) input").attr({name:"employee_personal_information_rows["+j+"][relation]", id:"employee_personal_information_rows-"+j+"-relation"}).rules("add", "required");		
					$(this).find("td:nth-child(4) input").attr({name:"employee_personal_information_rows["+j+"][mobile_no]", id:"employee_personal_information_rows-"+j+"-mobile_no"}).rules("add", "required");
					$(this).find("td:nth-child(5) input").attr({name:"employee_personal_information_rows["+j+"][phone_no]", id:"employee_personal_information_rows-"+j+"-phone_no"});
					$(this).find("td:nth-child(6) textarea").attr({name:"employee_personal_information_rows["+j+"][address]", id:"employee_personal_information_rows-"+j+"-address"});
					
					ii++;
					j++;
				}); 
				b=j;
			}
	$('.add3').live('click',function()
	{ 
		add_row3();
	});
	add_row3();
	var k=0;
	var c=0;
	function add_row3()
	{ 
			var tr=$("#sample_table3 tbody tr.main_tr").clone();
			$("#main_table3 tbody#main_tbody3").append(tr);
			
			rename_rows();
			rename_rows2();
			rename_rows3();
			rename_rows4();
	}
	rename_rows3();
	function rename_rows3(){ 
				var iii=0; 
				k=b;
				$("#main_table3 tbody#main_tbody3 tr.main_tr").each(function(){ 
					$(this).find('td:nth-child(1)').html(iii+1);
					$(this).find("td:nth-child(2) input.first").attr({name:"employee_personal_information_rows["+k+"][name]", id:"employee_personal_information_rows-"+k+"-name"}).rules("add", "required");
					$(this).find("td:nth-child(2) input.hidden").attr({name:"employee_personal_information_rows["+k+"][detail_type]", id:"employee_personal_information_rows-"+k+"-detail_type"});
					$(this).find("td:nth-child(3) input").attr({name:"employee_personal_information_rows["+k+"][mobile_no]", id:"employee_personal_information_rows-"+k+"-mobile_no"}).rules("add", "required");
					$(this).find("td:nth-child(4) input").attr({name:"employee_personal_information_rows["+k+"][phone_no]", id:"employee_personal_information_rows-"+k+"-phone_no"});
					$(this).find("td:nth-child(5) textarea").attr({name:"employee_personal_information_rows["+k+"][address]", id:"employee_personal_information_rows-"+k+"-address"});
					
					iii++;
					k++;
				});
				c=k;
			}
	$('.add4').live('click',function()
	{ 
		add_row4();
	});
	add_row4();
	
	function add_row4()
	{ 
			var tr=$("#sample_table4 tbody tr.main_tr").clone();
			$("#main_table4 tbody#main_tbody4").append(tr);
			
			rename_rows();
			rename_rows2();
			rename_rows3();
			rename_rows4();
	}
	rename_rows4();
	var l=0;
	function rename_rows4(){ 
				var n=0; 
				l=c;
				$("#main_table4 tbody#main_tbody4 tr.main_tr").each(function(){ 
					$(this).find('td:nth-child(1)').html(n+1);
					$(this).find("td:nth-child(2) input.first").attr({name:"employee_personal_information_rows["+l+"][period]", id:"employee_personal_information_rows-"+l+"-period"}).rules("add", "required");
					$(this).find("td:nth-child(2) input.hidden").attr({name:"employee_personal_information_rows["+l+"][detail_type]", id:"employee_personal_information_rows-"+l+"-detail_type"});
					$(this).find("td:nth-child(3) input").attr({name:"employee_personal_information_rows["+l+"][company_name]", id:"employee_personal_information_rows-"+l+"-company_name"}).rules("add", "required");
					$(this).find("td:nth-child(4) input").attr({name:"employee_personal_information_rows["+l+"][designation]", id:"employee_personal_information_rows-"+l+"-designation"});
					$(this).find("td:nth-child(5) textarea").attr({name:"employee_personal_information_rows["+l+"][duties_nature]", id:"employee_personal_information_rows-"+l+"-duties_nature"});
					
					n++;
					l++;
				});
			}
	
	$('.delete-tr').live('click',function() 
	{ 
		var rowCount = $('#main_table tbody#main_tbody tr').length; 
		if(rowCount>1)
		{
			$(this).closest('tr').remove();
			rename_rows();
			rename_rows2();
			rename_rows3();
			rename_rows4();
		}
    });
	
	$('.delete-tr2').live('click',function() 
	{ 
		var rowCount = $('#main_table2 tbody#main_tbody2 tr').length; 
		if(rowCount>1)
		{
			$(this).closest('tr').remove();
			rename_rows();
			rename_rows2();
			rename_rows3();
			rename_rows4();
		}
    });
	
	$('.delete-tr3').live('click',function() 
	{ 
		var rowCount = $('#main_table3 tbody#main_tbody3 tr').length; 
		if(rowCount>1)
		{
			$(this).closest('tr').remove();
			rename_rows();
			rename_rows2();
			rename_rows3();
			rename_rows4();
		}
    });
	
	$('.delete-tr4').live('click',function() 
	{ 
		var rowCount = $('#main_table4 tbody#main_tbody4 tr').length; 
		if(rowCount>1)
		{
			$(this).closest('tr').remove();
			rename_rows();
			rename_rows2();
			rename_rows3();
			rename_rows4();
		}
    });
	
	$('select.state_change').on("change",function() { 
	 
	var stateId       =$(this).val();
	var div           =$(this).attr('div_id');
	var discrict_name =$(this).attr('district');
	$('#'+discrict_name+''+div).html('Loading...');
	var url="<?php echo $this->Url->build(['controller'=>'EmployeePersonalInformations','action'=>'getDistrictByState']); ?>";
	url=url+'/'+stateId+'/'+discrict_name,
	
	$.ajax({
		url: url,
		type: 'GET',
	}).done(function(response) { 
		$('#'+discrict_name+''+div).html(response);
		$('select[name="'+discrict_name+'"]').select2();
	});
	});
});
</script>
<table id="sample_table" style="display:none;" width="100%">
	<tbody>
		<tr class="main_tr" class="tab">
			<td style="width:3%;"></td>
			<td style="vertical-align: top !important;width:18%;" >
				<?php echo $this->Form->input('name', ['label' => false,'class' => 'form-control input-sm first','id'=>'check']); ?>
				<input type="hidden" name="detail_type" value="employee family information" class="hidden">
			</td>
			<td width="14%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('relation', ['label' => false,'class' => 'form-control input-sm count_value','id'=>'check']); ?>
			</td>
			<td width="14%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('dob', ['label' => false,'class' => 'form-control input-sm date-picker','data-date-format'=>'dd-mm-yyyy','placeholder'=>'dd/mm/yyyy']); ?>
			</td>
			<td width="10%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('dependent', ['label' => false,'class' => 'form-control input-sm']); ?>	
			</td>
			<td style="width:15%;vertical-align: top !important;">
				<?php 
				$whether_employeds[]=['value'=>'State','text'=>'State'];
				$whether_employeds[]=['value'=>'Center','text'=>'Center'];
				$whether_employeds[]=['value'=>'Unemployed ','text'=>'Unemployed'];
				$whether_employeds[]=['value'=>'Private','text'=>'Private'];
				echo $this->Form->input('whether_employed', ['empty'=> '---Select---','label' => false,'class'=>'form-control input-sm','options'=>@$whether_employeds]); ?>
			</td>							  
			<td style="width:5%;">
				<button type="button" class="add btn btn-default btn-xs"><i class="fa fa-plus"></i> </button>
				<a class="btn btn-danger delete-tr btn-xs" href="#" role="button" ><i class="fa fa-times"></i></a>
			</td>
		</tr>
	</tbody>
</table>

<table id="sample_table2" style="display:none;" width="100%">
	<tbody>
		<tr class="main_tr" class="tab">
			<td style="width:3%;vertical-align: top !important;"></td>
			<td style="vertical-align: top !important;width:21%;">
				<?php echo $this->Form->input('name', ['label' => false,'class' => 'form-control input-sm first','id'=>'check']); ?>
				<input type="hidden" name="detail_type" value="employee emergency details" class="hidden">
			</td>
			<td width="15%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('relation', ['label' => false,'class' => 'form-control input-sm','id'=>'check']); ?>
			</td>
			<td width="15%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('mobile_no', ['label' => false,'class' => 'form-control input-sm','maxlength'=>10,'minlength'=>10]); ?>
			</td>
			<td width="15%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('phone_no', ['label' => false,'class' => 'form-control input-sm']); ?>	
			</td>
			<td style="width:20%;" style="vertical-align: top !important;">
				<?php 
				echo $this->Form->input('address', ['label' => false,'class'=>'form-control input-sm','type'=>'textarea','rows'=>2]); ?>
			</td>							  
			<td style="width:7%;" style="vertical-align: top !important;">
				<button type="button" class="add2 btn btn-default btn-xs"><i class="fa fa-plus"></i> </button>
				<a class="btn btn-danger delete-tr2 btn-xs" href="#" role="button" style="margin-bottom: 1px;"><i class="fa fa-times"></i></a>
			</td>
		</tr>
	</tbody>
</table>

<table id="sample_table3" style="display:none;" width="100%">
	<tbody>
		<tr class="main_tr" class="tab">
			<td style="width:3%;vertical-align: top !important;"></td>
			<td style="vertical-align: top !important;width:17%;">
				<?php echo $this->Form->input('name', ['label' => false,'class' => 'form-control input-sm first','id'=>'check']); ?>
				<input type="hidden" name="detail_type" value="employee reference details" class="hidden">
			</td>
			<td width="15%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('mobile_no', ['label' => false,'class' => 'form-control input-sm','maxlength'=>10,'minlength'=>10]); ?>
			</td>
			<td width="15%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('phone_no', ['label' => false,'class' => 'form-control input-sm']); ?>	
			</td>
			<td style="width:20%;" style="vertical-align: top !important;">
				<?php 
				echo $this->Form->input('address', ['label' => false,'class'=>'form-control input-sm','type'=>'textarea','rows'=>2]); ?>
			</td>							  
			<td style="width:5%;" style="vertical-align: top !important;">
				<button type="button" class="add3 btn btn-default btn-xs"><i class="fa fa-plus"></i> </button>
				<a class="btn btn-danger delete-tr3 btn-xs" href="#" role="button" style="margin-bottom: 1px;"><i class="fa fa-times"></i></a>

			</td>
		</tr>
	</tbody>
	
<table id="sample_table4" style="display:none;" width="100%">
	<tbody>
		<tr class="main_tr" class="tab">
			<td style="width:3%;vertical-align: top !important;"></td>
			<td style="vertical-align: top !important;width:18%;">
				<?php echo $this->Form->input('period', ['label' => false,'class' => 'form-control input-sm first','id'=>'check']); ?>
				<input type="hidden" name="detail_type" value="employee work experience" class="hidden">
			</td>
			<td width="15%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('company_name', ['label' => false,'class' => 'form-control input-sm']); ?>
			</td>
			<td width="15%" style="vertical-align: top !important;">
				<?php echo $this->Form->input('designation', ['label' => false,'class' => 'form-control input-sm']); ?>	
			</td>
			<td style="width:20%;" style="vertical-align: top !important;">
				<?php 
				echo $this->Form->input('duties_nature', ['label' => false,'class'=>'form-control input-sm','type'=>'text']); ?>
			</td>							  
			<td style="width:5%;" style="vertical-align: top !important;">
				<button type="button" class="add4 btn btn-default btn-xs"><i class="fa fa-plus"></i> </button>
				<a class="btn btn-danger delete-tr4 btn-xs" href="#" role="button" style="margin-bottom: 1px;"><i class="fa fa-times"></i></a>
			</td>
		</tr>
	</tbody>
</table>