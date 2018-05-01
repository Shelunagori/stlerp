<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Employee Attendence List</span>
		</div>
		<div class="actions">
			
		</div>
		<div class="portlet-body">
		
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
							
								<table class="table table-condensed">
									<tbody>
										<tr>
											<td width="15%">
												<input type="text" name="From" class="select_date form-control input-sm date-picker" placeholder="Date From" value="<?php echo @$From; ?>" data-date-format="mm-yyyy" >
											</td>
											<td><button type="button" class="btn btn-primary btn-sm emp_rec"><i class="fa fa-filter"></i> Go</button></td>
										</tr>
									</tbody>
								</table>
							
						</div>
				</div>
				<div class="col-md-6">
				
				<div id="form_attached">
					<div class="box box-primary" id="copy_form">
						
					</div>
				</div>
			</div>
			
			</div>
			
		</div>
	</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>

$(document).ready(function() 
{
	$('.emp_rec').live('click',function(){
		var select_date=$(this).closest('tr').find('.select_date').val();
		
		var url="<?php echo $this->Url->build(['controller'=>'EmployeeAttendances','action'=>'getAttendenceList']); ?>";
		url=url+'/'+select_date, 
		 $.ajax({
			url: url,
		}).done(function(response) {
			$("#copy_form").html(response);
		});
	});
});
</script>