<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-globe font-blue-steel"></i>
			<span class="caption-subject font-blue-steel uppercase">Employee Record</span>
		</div>
		<div class="actions">
			
		</div>
		<div class="portlet-body">
			<div class="row">
				<table class="table table-condensed">
					<tbody>
						<tr>
							<td width="22%">
								<?php 
								//$employee_record_types[]=['value'=>'Attendance','text'=>'Attendance'];
								//$employee_record_types[]=['value'=>'Over Time','text'=>'Over Time'];
								//$employee_record_types[]=['value'=>'Conveyance','text'=>'Conveyance'];
								echo $this->Form->input('employee_record_type', ['empty'=>'--Select--','options' => @$employees,'label' => false,'class' => 'form-control input-sm select2me employee_record_type','placeholder'=>'','value'=>@$employee_record_type]); ?>
							</td>
							<td width="15%">
								<input type="text" name="month_year" class="form-control input-sm date-picker" placeholder="Transaction Date To" data-date-format="mm-yyyy" value="<?php  echo @$month_year;?>">
							</td>
							<td><button type="button" class="btn btn-primary btn-sm emp_rec"><i class="fa fa-filter"></i> Go</button></td>
						</tr>
					</tbody>
				</table>
				<div class="col-md-12">
				<?php echo $this->Form->create($employeeRecord, ['id'=>'form_sample_3']); ?>
				<div id="form_attached">
				
				</div>
				<?php echo $this->Form->end(); ?>
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
	  var copy=$("#copy_form").clone(); 
	  $("#form_attached").html(copy);
	  add_first_row();
  });
  
  $('.addrow').live('click',function(){ 
		add_row();
	});
	
	function add_first_row()
	{ 
		var tr=$("#main_table tbody tr.main_tr").clone();
		$("#form_attached #main_table #main_tbody1").append(tr);
		
	}
	function add_row()
	{ 
		var tr=$("#main_table #main_tbody2 tr.main_tr").clone();
		$("#form_attached #main_table #main_tbody1").append(tr);
		rename_rows();
		calculate_total();
	}
	
	$('.deleterow').live('click',function() 
	{ 
		var rowCount = $('#main_table tbody#main_tbody1 tr').length; 
		if(rowCount>1)
		{
			$(this).closest('tr').remove();
		}
    });
	function rename_rows()
	{ 
		var i=0;
		$("#main_table tbody#main_tbody1 tr").each(function(){ 
			$(this).find("td:nth-child(1) select").attr({name:"ref_rows["+i+"][ref_type]", id:"ref_rows-"+i+"-ref_type"}).rules("add", "required");
			$(this).find("td:nth-child(2) select").attr({name:"ref_rows["+i+"][ref_type]", id:"ref_rows-"+i+"-ref_type"}).rules("add", "required");
			$(this).find("td:nth-child(3) input").attr({name:"ref_rows["+i+"][ref_no]", id:"ref_rows-"+i+"-ref_no"}).rules("add", "required");
			i++;
		});
	}

	function calculate_total()
	{ 
		var total_cr=0;
		var total_dr=0;
		$("#main_table tbody#main_tbody1 tr").each(function(){ 
				var cr_dr=$(this).find(".cr_dr_amount").val();
				var amount=parseFloat($(this).find(".amount").val());
				if(cr_dr=="Dr"){
						total_dr=total_dr+amount;
				}else{
						total_cr=total_cr+amount;
				}
				$(".total_amount").val(total_dr);
				alert(cr_dr);
		});
	}
  
});
</script>
<div style="display:none;" >
<div class="box box-primary" id="copy_form">
		<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="80%">
			<thead>
				<tr>
					<td>Type</td>
					<td>Dr/Cr</td>
					<td>Amount</td>
					<td>Action</td>
				</tr>
			</thead>
			<tbody id="main_tbody1">
				
			</tbody>
			<tfoot id="main_tfoot1">
				<tr id="tfoot_tr">
					<td colspan="2" align="right">Total</td>
					<td id="tfoot_td"><?php echo $this->Form->input('amount', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm total_amount']); ?></td>
					<td ></td>
				</tr>
			</tfoot>
		</table>
		
</div>

<div class="box box-primary" id="copy_form1">
		<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="80%">
			<tbody id="main_tbody2">
				<tr class="main_tr" class="tab">
					<td style="vertical-align: top !important;width:18%;">
						<?php echo $this->Form->input('employee_salary_division', ['empty'=>'--Select--','options' => @$EmployeeSalaryDivision,'label' => false,'class' => 'form-control input-sm ','placeholder'=>'']); ?>
					</td>
					<td width="15%" style="vertical-align: top !important;">
						<?php echo $this->Form->input('type_cr_dr', ['options'=>['Dr'=>'Dr','Cr'=>'Cr'],'label' => false,'class' => 'form-control input-sm  cr_dr_amount','value'=>'Dr']); ?>
					</td>
					<td width="15%" style="vertical-align: top !important;">
						<?php echo $this->Form->input('amount', ['label' => false,'placeholder'=>'','class'=>'form-control input-sm amount']); ?>
					</td>							  
					<td style="width:5%;" style="vertical-align: top !important;">
						<button type="button" class="addrow btn btn-default btn-xs"><i class="fa fa-plus"></i> </button>
						<a class="btn btn-danger deleterow btn-xs" href="#" role="button" style="margin-bottom: 1px;"><i class="fa fa-times"></i></a>
					</td>
				</tr>
			</tbody>
		</table>
		
</div>
<div class="box-footer">
	<center>
	
	 <button type="submit" class="btn btn-primary" id='submitbtn' >Save</button>
	</center>
</div>
			
</div>