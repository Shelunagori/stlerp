<style>
@media print{
    .maindiv{
        width:100% !important;
    }   
    .hidden-print{
        display:none;
    }
}
p{
margin-bottom: 0;
}
</style>
<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0 5px 0 20px;  /* this affects the margin in the printer settings */
}
</style>

<div style="border:solid 1px #c7c7c7;background-color: #FFF;padding: 10px;margin: auto;width: 55%; height:100%;font-size: 12px;" class="maindiv">    
        <table width="100%" class="divHeader">
        <tr>
            <td width="30%"></td>
            <td align="center" width="30%" style="font-size: 12px;"><div align="center" style="font-size: 16px;font-weight: bold;color: #0685a8;">Travel Request</div></td>
            <td align="right" width="40%" style="font-size: 12px;">
				<?php if($travelRequest->status=='Pending'){ ?>
				<span class="label label-sm label-danger"><?php echo $travelRequest->status; ?></span>
				<?php } ?>
			</td>
        </tr>
        <tr>
            <td colspan="3">
                <div style="border:solid 2px #0685a8;margin-bottom:5px;margin-top: 5px;"></div>
            </td>
        </tr>
    </table>
    <table width="100%">
					<tr>
                        <td width="40%">Employee Name</td>
                        <td align="center">:</td>
                        <td><?= h($travelRequest->employee->name) ?></td>
						
                    </tr>
                    <tr>
                       
					</tr>
					<tr>
                        <td  width="40%">Purpose</td>
                        <td  align="center">:</td>
						<td><?= h($travelRequest->purpose) ?></td>
					</tr>
					<tr>
                        <td  width="40%">Travel Mode</td>
                        <td  align="center">:</td>
						<td><?= h($travelRequest->travel_mode) ?></td>
                    </tr>
					<tr>
                        <td>Form</td>
                        <td width="20" align="center">:</td>
                        <td><?= h(date("d-m-Y",strtotime($travelRequest->travel_mode_from_date))) ?></td>
                    </tr>
					<tr>
						<td>To</td>
						<td width="20" align="center">:</td>
						<td><?= h(date("d-m-Y",strtotime($travelRequest->travel_mode_to_date))) ?></td>
					</tr>
					<tr>
                        <td  width="40%">Mode Of Travel Return</td>
                        <td  align="center">:</td>
						<td><?= h($travelRequest->return_travel_mode) ?></td>
                    </tr>
					<tr>
                        <td  width="40%">Applied Advance Amount</td>
                        <td  align="center">:</td>
						<td><?= h($travelRequest->applied_advance_amt) ?></td>
                    </tr>
					<tr>
                        <td  width="40%">Approved Advance Amount</td>
                        <td  align="center">:</td>
						<td><?= h($travelRequest->advance_amt) ?></td>
                    </tr>
					<tr>
                        <td  width="40%">Comment</td>
                        <td  align="center">:</td>
						<td><?= h($travelRequest->comment) ?></td>
                    </tr>
					<tr>
                        <td  width="40%">Status</td>
                        <td  align="center">:</td>
						<td><?= h($travelRequest->status) ?></td>
                    </tr>
                   
        </tr>
    </table>
	<br/>
	<br/>
		<fieldset style="margin-left: 2px;margin-right: 16px;">
			<legend><b>Travel Schedule Date wise only </b></legend>
			<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="100%">
				<thead>
					<tr align="center">
					   <td><label>S.n.<label></td>
					   <td><label>Date<label></td>
					   <td><label>Party Name<label></td>
					   <td><label>Destination<label></td>
					   <td><label>Person whom to meet<label></td>
					   <td><label>Reporting time<label></td>
					 
					</tr>
				</thead>
				<tbody>
					<?php $i=1; foreach($travelRequest->travel_request_rows as $travel_request_row) {?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><?= h(date("d-m-Y",strtotime($travel_request_row->date))) ?></td>
						<td><?php echo $travel_request_row->party_name; ?></td>
						<td><?php echo $travel_request_row->destination; ?></td>
						<td><?php echo $travel_request_row->meeting_person; ?></td>
						<td><?php echo $travel_request_row->reporting_time; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</fieldset>
				

    
  
</div>

