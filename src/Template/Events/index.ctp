<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Holidays</span>
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<div class="row ">
		<div class="col-md-6">
		 <?= $this->Form->create($event,array("class"=>"form-horizontal",'id'=>'form_sample_3')) ?>
			<div class="form-body">
				<div class="form-group">
					<label class="control-label col-md-3">Holidays Title<span class="required" aria-required="true">
					* </span>
					</label>
					<div class="col-md-9">
						<div class="input-icon right">
							<i class="fa"></i>
							 <?php echo $this->Form->input('event_name', ['label' => false,'class' => 'form-control','required','onkeyup '=>'this.value = ucwords(this.value)']); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
				<?php if(date('d-m-Y',strtotime(@$event->event_start_date)) == "01-01-1970"){
					$start_date = date('d-m-Y');
				}else{
					$start_date = date('d-m-Y',strtotime(@$event->event_start_date));

				} ?>
				<?php if(date('d-m-Y',strtotime(@$event->event_end_date)) == "01-01-1970"){
					$ends_date = date('d-m-Y');
				}else{
					$ends_date = date('d-m-Y',strtotime(@$event->event_end_date));

				} ?>
					<label class="control-label col-md-3">Start Date<span class="required" aria-required="true">
					* </span>
					</label>
					<div class="col-md-9">
						<div class="input-icon right">
							<i class="fa"></i>
							 <?php echo $this->Form->input('event_start_date',['type' => 'text','label' => false,'class' => 'form-control date-picker','data-date-format' => 'dd-mm-yyyy','required','placeholder' => 'Event Start Date','value'=>$start_date]); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">End Date
					</label>
					<div class="col-md-9">
						<div class="input-icon right">
							<i class="fa"></i>
							 <?php echo $this->Form->input('event_end_date',['type' => 'text','label' => false,'class' => 'form-control date-picker','data-date-format' => 'dd-mm-yyyy','placeholder' => 'Event End Date','value'=>$ends_date]); ?>
						</div>
					</div>
				</div>
				<!--<div class="form-group">
					<label class="control-label col-md-3">Time
					</label>
					<div class="col-md-9">
						<div class="input-icon right">
							<i class="fa"></i>
							 <?php echo $this->Form->input('event_time',['type' => 'text','label' => false,'class' => 'form-control timepicker-no-seconds','data-placeholder' => 'Event Time','value'=>@$event->event_time]); ?>
						</div>
					</div>
				</div>-->
				<div class="row">
					<div class="col-md-offset-4 col-md-8">
						<button type="submit" class="btn btn-primary">Add Holidays</button>
					</div>
				</div>
			</div>
		<?= $this->Form->end() ?>
		</div>
		<div class="col-md-6">
		
		<form method="GET" name="form2" >
								<table class="table table-condensed">
									<thead>
										<tr>
											<th>Holidays</th>
											
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<div class="row">
												<div class="col-md-4">
													<input type="text" name="event_name" class="form-control" placeholder="Event Name" value="<?php echo @$event_name; ?>">
												</div>
												<div class="col-md-4">
												<?php
													if(date('d-m-Y',strtotime(@$from_date)) == "01-01-1970"){
														$from_date="";
													}else{
														$from_date = date('d-m-Y',strtotime(@$from_date));
													}

												?>
													<input type="text" name="from_date" class="form-control date-picker" data-date-format = 'dd-mm-yyyy' placeholder="Start Date" value="<?php echo $from_date; ?>">
												</div>
												<div class="col-md-4">
												<?php
													if(date('d-m-Y',strtotime(@$end_date)) == "01-01-1970"){
														$end_date="";
													}else{
														$end_date = date('d-m-Y',strtotime(@$end_date));
													}

												?>
													<input type="text" name="end_date" class="form-control  date-picker" data-date-format = 'dd-mm-yyyy' placeholder="End Date" value="<?php echo $end_date; ?>">
												</div>
												
											</div>
											</td>
											<td><button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button></td>
										</tr>
									</tbody>
								</table>
		</form>
			<div class="portlet-body">
			<div class="table-scrollable">
			<?php $page_no=$this->Paginator->current('Events'); $page_no=($page_no-1)*20; ?>
			<table class="table table-hover">
				<thead>
					<tr>
						<th><?= $this->Paginator->sort('id') ?></th>
						<th><?= $this->Paginator->sort('Holidays Name') ?></th>
						<th><?= $this->Paginator->sort('Date') ?></th>
						<th class="actions"><?= __('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($events as $event): ?>
					<tr>
						<td style="text-align:justify;"><?= h(++$page_no) ?></td>
						<td><?= h($event->event_name) ?></td>
						<td><?= h(date('d-m-Y',strtotime($event->event_start_date))) ?>
							<?php if(date('d-m-Y',strtotime($event->event_end_date)) == "01-01-1970"){
								
							}else{
								echo "To".date('d-m-Y',strtotime($event->event_end_date));
							}
								
							?>
						
						</td>
						
						<td class="actions">
							<?= $this->Html->link(__('Edit'), ['action' => 'index', $event->id]) ?>
							<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $event->id], ['confirm' => __('Are you sure you want to delete # {0}?', $event->id)]) ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			</div>
			<div class="paginator">
				<ul class="pagination">
					<?= $this->Paginator->prev('<') ?>
					<?= $this->Paginator->numbers() ?>
					<?= $this->Paginator->next('>') ?>
				</ul>
				<p><?= $this->Paginator->counter() ?></p>
			</div>
			</div>
		</div>
		<!-- END FORM-->
	</div>
</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
	//--------- FORM VALIDATION
	jQuery.validator.addMethod("alphabetsAndSpacesOnly", function (value, element) {
    return this.optional(element) || /^[a-zA-Z\s]+$/.test(value); });
	
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
				//alphabetsAndSpacesOnly: true,
				maxlength:40,
			},
			
		},

		messages: { // custom messages for radio buttons and checkboxes
			
			name  : {
				alphabetsAndSpacesOnly: "Enter Letters only",
			},
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
			success3.show();
			error3.hide();
			form[0].submit(); // submit the form
		}

	});
	
});
function ucwords(str) { 
			var str = str.toLowerCase(); 
			return (str + '')
					.replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function($1) {
						return $1.toUpperCase();
					});
		}
</script>