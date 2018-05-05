<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Employee Sallary</span>
		</div>
		<div class="actions">
			
		</div>
		<div class="portlet-body">
		<?php echo $this->Form->create($employeeSalary, ['id'=>'form_sample_3']); ?>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
							
								<table class="table table-condensed">
									<tbody>
										<tr>
											<td width="15%"> 
												<select name="From">
													<option value="4-<?php echo $s_year_from; ?>">April <?php echo $s_year_from; ?></option>
													<option value="5-<?php echo $s_year_from; ?>">May <?php echo $s_year_from; ?></option>
													<option value="6-<?php echo $s_year_from; ?>">June <?php echo $s_year_from; ?></option>
													<option value="7-<?php echo $s_year_from; ?>">July <?php echo $s_year_from; ?></option>
													<option value="8-<?php echo $s_year_from; ?>">August <?php echo $s_year_from; ?></option>
													<option value="9-<?php echo $s_year_from; ?>">September <?php echo $s_year_from; ?></option>
													<option value="10-<?php echo $s_year_from; ?>">October <?php echo $s_year_from; ?></option>
													<option value="11-<?php echo $s_year_from; ?>">November <?php echo $s_year_from; ?></option>
													<option value="12-<?php echo $s_year_from; ?>">December <?php echo $s_year_from; ?></option>
													<option value="1-<?php echo $s_year_to; ?>">January <?php echo $s_year_to; ?></option>
													<option value="2-<?php echo $s_year_to; ?>">February <?php echo $s_year_to; ?></option>
													<option value="3-<?php echo $s_year_to; ?>">March <?php echo $s_year_to; ?></option>
												</select>
											</td>
											<td><button type="button" class="btn btn-primary btn-sm emp_rec"><i class="fa fa-filter"></i> Go</button></td>
										</tr>
									</tbody>
								</table>
							
						</div>
				</div>
				<div class="col-md-12">
				
				<div id="form_attached" style="overflow: auto;">
					<div class="box box-primary" id="copy_form">
						
					</div>
				</div>
			</div>
			
			</div>
			
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>

<script>

$(document).ready(function() 
{
	$('.emp_rec').live('click',function(){
		var select_date=$(this).closest('tr').find('select[name=From]').val();
		$("#copy_form").html('<h4>Loading...</h4>');
		var url="<?php echo $this->Url->build(['controller'=>'EmployeeSalaries','action'=>'paySallary']); ?>";
		url=url+'/'+select_date, 
		 $.ajax({
			url: url,
		}).done(function(response) {
			$("#copy_form").html(response);
		});
	});
	
	$('.genertSlry').live('click',function(){
		var select_date=$('select[name=From]').val();
		alert(select_date);
		var url="<?php echo $this->Url->build(['controller'=>'EmployeeSalaries','action'=>'generateSalary']); ?>";
		url=url+'/'+select_date;
		window.location.replace(url);
	});
});
</script>