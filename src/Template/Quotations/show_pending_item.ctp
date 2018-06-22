
<div id="myModal2" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false" style="position:fixed; display:block; padding-right: 12px;"><div class="modal-backdrop fade in" ></div>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id="result_ajax">
			<h4>Item List</h4>
				<div style=" overflow: auto; height: 450px;">
				<table class="table table-hover tabl_tc">
					
					<thead>
						<tr>
						<th>Item Name</th>
						<th>Pending Quantity</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php foreach($salesData as $key=>$data){ ?>
								<tr>
									<td><?php echo $key; ?></td>
									<td><?php echo $data; ?></td>
								</tr>
							<?php } ?>
							
						</tr>
					</tbody>
					
				
				</table>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn default closebtn2">Close</button>
				
			</div>
		</div>
	</div>
</div>

<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
	
	$('.closebtn2').on("click",function() { 
		$("#myModal2").hide();
	});
});	
	
</script>