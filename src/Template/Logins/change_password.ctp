<?php
$this->Form->templates([
    'inputContainer' => '{{content}}'
]);
?>
<style>
.error{
	color:#aa4e4e !important;
}

</style>
<!-- BEGIN LOGIN FORM -->
	<?= $this->Form->create($login,['id'=>'form_sample_3']) ?>
		<h3 class="form-title">Change Password</h3>
        <div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>
			Enter your password. </span>
		</div>
		
         <?php
		if(!empty($wrong))
		{
		?>
        <div class="alert alert-danger">
			<button class="close" data-close="alert"></button>
			<span>
			<?php echo $wrong; ?> </span>
		</div>
        <?php
		}
		?>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">New Password</label>
			
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<?php echo $this->Form->input('password', ['type'=>'password','label'=>false,'class' => 'form-control password','placeholder'=>'New Password','id'=>'password']); ?>
				
			</div>
		</div>
		 <label for="password" class="error"></label>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Confirm New Password</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<?php echo $this->Form->input('con_password', ['type'=>'password','label'=>false,'class' => 'form-control con_password','id'=>'con_password','placeholder'=>'Confirm Password','required']); ?>
			</div>
		</div>
        <label for="con_password" class="error"></label>
		<div class="form-actions">
			<label class="checkbox">
			<input type="hidden" name="remember" value="1"/> </label>
			<button type="submit" name="login_submit" class="btn green-haze pull-right">
			Reset Password <i class="m-icon-swapright m-icon-white"></i>
			</button>
		</div>
	<?= $this->Form->end() ?>
	<!-- END LOGIN FORM -->
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
	
<?php echo $this->Html->script('/assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>
<?php echo $this->Html->script('/assets/global/scripts/metronic.js'); ?>
<?php echo $this->Html->script('/assets/admin/layout/scripts/layout.js'); ?>
<?php echo $this->Html->script('/assets/admin/layout/scripts/demo.js'); ?>


<script>
$(document).ready(function() {
	$('#form_sample_3').validate({
		rules: {
		"password": {
			required: true
		},
		"con_password": {
			required: true,
			equalTo: "#password"
		}
	},
	messages: {
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
	
});

</script>