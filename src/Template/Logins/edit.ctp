<style>
.form-actions{
	background-color:white !important;
}
.error{
	color:#aa4e4e !important;
}

</style>

<div class="portlet box blue-hoki">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>Update Password
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<div class="row ">
		<div class="col-md-2"></div>
		<div class="col-md-6">
		 <?= $this->Form->create($login,array("class"=>"form-horizontal",'id'=>'form_sample_3')) ?>
			<div class="form-body">
			<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label">Username</label>
				<br/>
				<div class="row">
					<div class="col-md-10">
						<?php echo $this->Form->input('username1', ['type'=>'text','label'=>false,'class' => 'form-control username','placeholder'=>'Username','id'=>'username','value'=>$login->username,'readonly']); ?>
					</div>
					<div class="col-md-2">
						<span toggle="#username" class="btn btn-primary fa fa-eye toggle-password"></span>
					</div>
				</div>
					
					
			
			</div>
			<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label">New Password</label>

				<div class="row">
					<div class="col-md-12">
					
					<?php echo $this->Form->input('password', ['type'=>'password','label'=>false,'class' => 'form-control password','placeholder'=>'New Password','id'=>'password']); ?>

				</div>
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label">Confirm New Password</label>
				<div class="row">
					<div class="col-md-12">
					
					<?php echo $this->Form->input('con_password', ['type'=>'password','label'=>false,'class' => 'form-control con_password','id'=>'con_password','placeholder'=>'Confirm Password','required']); ?>
				</div>
				</div>
			</div>
			
			<div class="form-actions">
				<button type="submit" name="login_submit" class="btn green-haze pull-right">
				Reset Password <i class="m-icon-swapright m-icon-white"></i>
				</button>
			</div>
			</div>
		<?= $this->Form->end() ?>
		</div>
		<!-- END FORM-->
	</div>
</div>
</div>
<input type="hidden" class="url_check_name" value="<?php echo $this->Url->build(['controller'=>'Logins','action'=>'checkNameExists',$login->id])  ?>">
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
	var url_check_name = $('.url_check_name').val();
	$('#form_sample_3').validate({
		rules: {
		"username": {
			required: true
		},
		"password": {
			required: true
		},
		"con_password": {
			required: true,
			equalTo: "#password"
		}
	},
	messages: {
		"username": {
			required: "Please enter username"
		},
		"password": {
			required: "Please enter current password."
		},
		"con_password": {
			required: "Please enter confirm password.",
			equalTo: "Confirm password should be equal to password."
		}
	},
	ignore: ":hidden:not(select)",
	submitHandler: function (form) {
		//$("#loader-1").show();
		form[0].submit(); 
	}
		
	});
	////
	$('.toggle-password').click(function() {

		$(this).toggleClass('fa-eye-slash');
		var input = $($(this).attr('toggle'));
		
		if (input.attr('type') == 'text') {
			input.removeAttr('readonly');
		} else {
			input.attr('readonly', true);
		}

	});
});

</script>