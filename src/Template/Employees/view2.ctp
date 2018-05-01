<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption" >
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">VIEW EMPLOYEE</span>
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
			<fieldset style="margin-left:  0px;margin-right: 0px;">	
			<legend><b>Personal Information </b></legend>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Name </label>
							<span><?php echo $employee->name; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Date of Birth</label>
							<span><?php echo $employee->dob->format('d-m-Y'); ?></span>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Gender </label>
							<span><?php echo $employee->sex; ?></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Mobile </label>
							<span><?php echo $employee->mobile; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Landline</label>
							<span><?php echo $employee->phone_no; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Personal Email ID </label>
							<span><?php echo $employee->email; ?></span>
						</div>
					</div>
					
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Identity Mark </label>
							<span><?php echo $employee->identity_mark; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Caste</label>
							<span><?php echo $employee->caste; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Religion</label>
							<span><?php echo $employee->religion; ?></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Home State </label>
							<span><?php echo $employee->home_state; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Home District</label>
							<span><?php echo $employee->home_district; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Adhar Card No</label>
							<span><?php echo $employee->adhar_card_no; ?></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Passport No</label>
							<span><?php echo $employee->passport_no; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Height (in feet)</label>
							<span><?php echo $employee->height; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Company Email ID </label>
							<span><?php echo $employee->company_email; ?></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Blood Group </label>
							<span><?php echo $employee->blood_group; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Education Qualification</label>
							<span><?php echo $employee->qualification; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label"> Last Company worked</label>
							<span><?php echo $employee->last_company; ?></span>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Date of Confirmation as Permanent</label>
							<span><?php echo $employee->permanent_join_date; ?></span>
					</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Marital Status<span class="required" aria-required="true">*</span></label>
							<span><?php echo $employee->marital_status; ?></span>
						</div>
					</div>
				</div>
				<div class="row"  id="married_info" style="display:none;">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Spouse Name</label>
							<span><?php echo $employee->spouse_name; ?></span>
						</div>
					</div>
				
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Date Of Anniversary</label>
							<span><?php echo $employee->date_of_anniversary; ?></span>
					</div>
					</div>
						<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Number Of Child</label>
							<span><?php echo $employee->children; ?></span>
					</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label"> Whether Spouse Working?</label>
							<span><?php echo $employee->spouse_working; ?></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Permanent Address<span class="required" aria-required="true">*</span> </label>
							<span><?php echo $employee->permanent_address; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Residence Address </label>
							<span><?php echo $employee->residence_address; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Signature </label>
							<span><?php echo $employee->signature; ?></span>
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
							<span><?php echo $employee->present_state; ?></span>
						</div>
					</div>
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">District</label>
							<span><?php echo $employee->present_district; ?></span>
						</div>
					</div>
			       <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Pin code</label>
							<span><?php echo $employee->present_pin_code; ?></span>
						</div>
					</div>
			</div>
			<div class="col-md-12 pad">
				
			      <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Mobile No</label>
							<span><?php echo $employee->present_mobile_no; ?></span>
						</div>
					</div>
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Phone NO</label>
							<span><?php echo $employee->present_phone_no; ?></span>
						</div>
					</div>
			       <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Email</label>
							<span><?php echo $employee->present_email; ?></span>
						</div>
					</div>
			</div>
			<div class="col-md-12 pad">
				
			        <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Present Address</label>
							<span><?php echo $employee->present_address; ?></span>
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
						<span><?php echo $employee->permanent_state; ?></span>
					</div>
				</div>
			   <div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">District</label>
						<span><?php echo $employee->permanent_district; ?></span>
						
					</div>
				</div>
			   <div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Pin code</label>
						<span><?php echo $employee->permanent_pin_code; ?></span>
					</div>
				</div>
			</div>
			<div class="col-md-12 pad">
			  <div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Mobile No</label>
						<span><?php echo $employee->permanent_mobile_no; ?></span>
					</div>
				</div>
			   <div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Phone NO</label>
						<span><?php echo $employee->permanent_phone_no; ?></span>
					</div>
				</div>
			   <div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Email</label>
						<span><?php echo $employee->permanent_email; ?></span>
					</div>
				</div>
			</div>
			<div class="col-md-12 pad">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Permanent Address</label>
						<span><?php echo $employee->permanent_address; ?></span>
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
							<span><?php echo $employee->nominee_name; ?></span>
						</div>
					</div>
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Relation with the employee</label>
							<span><?php echo $employee->relation_with_employee; ?></span>
						</div>
					</div>
			       <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Type of Nomination</label>
							<span><?php echo $employee->nomination_type; ?></span>
						</div>
					</div>
			</div>
			<div class="col-md-12 pad">
				
			      <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">State</label>
							<span><?php echo $employee->nominee_state; ?></span>
						</div>
					</div> 
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">District</label>
							<span><?php echo $employee->nominee_district; ?></span>
						</div>
					</div>
			       <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Pin Code</label>
							<span><?php echo $employee->nominee_pin_code; ?></span>
						</div>
					</div>
			</div>
			<div class="col-md-12 pad">
				
			        <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Mobile No</label>
							<span><?php echo $employee->nominee_mobile_no; ?></span>
						</div>
					</div>
					
			        <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Present Address</label>
							<span><?php echo $employee->nominee_present_address; ?></span>
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
							<span><?php echo $employee->dipartment_id; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Designation <span class="required" aria-required="true">*</span></label>
							<span><?php echo $employee->designation_id; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Date Of Joining<span class="required" aria-required="true">*</span></label>
							<span><?php echo $employee->join_date; ?></span>
						</div>
					</div>
				</div>
			<div class="col-md-12 pad">
			      <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Date of Appointment</label>
							<span><?php echo $employee->appointment_date; ?></span>
						</div>
					</div>
			       
				   <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Employee ID </label>
							<span><?php echo $employee->employee_id_no; ?></span>
						</div>
					</div>
			       <div class="col-md-4">
						<div class="form-group">
							<label class="control-label  label-css">Date of Joining in the Deptt</label>
							<span><?php echo $employee->dept_joining_date; ?></span>
						</div>
					</div>
			</div>
			<div class="col-md-12 pad">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Initial Designation </label>
						<span><?php echo $employee->initial_designation; ?></span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Office Name at the time of initial joining in Deptt</label>
						<span><?php echo $employee->office_name; ?></span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Mode of Recruitment</label>
						<span><?php echo $employee->recruitment_mode; ?></span>
					</div>
				</div>
			</div>
			<div class="col-md-12 pad">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label  label-css">Reporting To </label>
						<span><?php echo $employee->reporting_to; ?></span>
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
					<td><span><?php echo $employee->account_type; ?></span></td>
					<td><span><?php echo $employee->bank_name; ?></span></td>
					<td><span><?php echo $employee->account_no; ?></span></td>
					<td><span><?php echo $employee->branch_name; ?></span></td>
					<td><span><?php echo $employee->ifsc_code; ?></span></td>
				</tbody>
			</table>	
		</fieldset>	
		
		
		</div>
		<!-- END FORM-->
	</div>
</div>
