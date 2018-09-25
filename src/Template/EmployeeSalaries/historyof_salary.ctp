<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
<span class="caption-subject font-blue-steel uppercase">History Of Salary "</span>		</div>
	</div>
	<div class="portlet-body" >
		
			<div class="row">
			 <div class="col-md-12">
			 <table class="table table-hover">
				 <thead>
					<tr>
						<th >Sr. No.</th>
						<th>Effective Date From</th>
						<th>Effective Date To</th>
						<th>Amount</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php $i=0; foreach ($historyOfSalary as $historyofsalary){ $i++;
			
				?>
					<tr class="main_tr">
						<td><?= h($i) ?></td>
						<td><?= h(date('d-m-Y',strtotime($historyofsalary->effective_date_from)))?></td>
						<td><?= h(date('d-m-Y',strtotime($historyofsalary->effective_date_to)))?></td>
						<td><?= h($this->Number->format($historyofsalary->amount,['places'=>2]))?></td>
						
						
					</tr>
				<?php  } ?>
				</tbody>
			</table>
		</div>
		
		</div>
		
	</div>
</div>

<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
	$('.effective_date').live("change",function() {
		var sel=$(this).closest('tr.main_tr');
		var effective_date =sel.find('input.effective_date').val();
		var company_id =sel.attr('row_no');
		var emp_id =$('.emp_id').val();
		
		var url="<?php echo $this->Url->build(['controller'=>'Employees','action'=>'updateffectivedate']); ?>";
		url=url+'/'+emp_id+'/'+effective_date+'/'+company_id;
		
			$.ajax({
				url: url,
				type: 'GET',
			}).done(function(response) {
				
				//alert("Save Minimum Stock");
			});
	});
	
	
});
</script>