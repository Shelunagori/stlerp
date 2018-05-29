<div class="row">
	<div class="col-md-2" id="qw"></div>
	<div class="col-md-8">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">updating rates of items...</h3>
			</div>
			<div class="panel-body">
				<div class="progress progress-striped active">
					<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="PBar">
						<span class="sr-only">
						40% Complete (success) </span>
					</div>
				</div>
				<table border="1" id="main_table">
					<?php foreach($ItemLedgers as $ItemLedger){
						echo 
						'
						<tr>
							<td>'.$ItemLedger->item_id.'</td>
							<td>'.$ItemLedger->processed_on->format("Y-m-d").'</td>
							<td>0</td>
						</tr>
						';
					} ?>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-2"></div>
</div>
<input  type="text" value="<?php echo $ItemLedgers->count(); ?>" id="Total"/>
<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
<script>
$(document).ready(function() {
	updateRate();
	function updateRate(){
		var rows=$("#main_table tbody tr").length;
		
		var currentRow=$("#main_table tbody tr:first");
		var item_id=currentRow.find('td:nth-child(1)').text();
		var date=currentRow.find('td:nth-child(2)').text();
		var url="<?php echo $this->Url->build(['controller'=>'ItemLedgers','action'=>'updateRate']); ?>";
		
		url=url+'?item_id='+item_id+'&date='+date; alert(url);
		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'text'
		}).done(function(response) {
			if(rows>0){ $('#qw').html(response);
				currentRow.remove();
				var Total=parseInt($('#Total').val());
				var per=round(rows*100/Total,2);
				per=100-per;
				$('#PBar').css('width',per+'%');
				updateRate();
			}
		});
		
	}
});
</script>
<!-- $.ajax({
			url: url,
			type: 'GET',
			dataType: 'text'
		}).done(function(response) {
			currentRow.remove();
			if(rows>0){ console.log(url); updateRate(); }
		}); -->