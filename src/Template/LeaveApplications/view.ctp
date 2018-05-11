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
            <td align="center" width="30%" style="font-size: 12px;"><div align="center" style="font-size: 16px;font-weight: bold;color: #0685a8;">Leave application</div></td>
            <td align="right" width="40%" style="font-size: 12px;">
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
                        <td>Employee Name</td>
                        <td width="20" align="center">:</td>
                        <td><?= h($leaveApplication->employee->name) ?></td>
                    </tr>
                    <tr>
                        <td>Leave Type</td>
                        <td width="20" align="center">:</td>
						<td><?= h($leaveApplication->leave_type->leave_name) ?></td>
					</tr>
					<tr>
                        <td>No of Days</td>
                        <td width="20" align="center">:</td>
						<td><?= h($leaveApplication->day_no) ?></td>
					</tr>
					<tr>
                        <td>Paid/Unpaid</td>
                        <td width="20" align="center">:</td>
						<td><?= h($leaveApplication->leave_mode) ?></td>
                    </tr>
                    <tr>
                        <td>Form</td>
                        <td width="20" align="center">:</td>
                        <td><?= h(date("d-m-Y",strtotime($leaveApplication->from_leave_date))) ?></td>
                    </tr>
					<tr>
						<td>To</td>
						<td width="20" align="center">:</td>
						<td><?= h(date("d-m-Y",strtotime($leaveApplication->to_leave_date))) ?></td>
					</tr>
					<tr>
                        <td>Leave Status</td>
                        <td width="20" align="center">:</td>
						<td><?= h($leaveApplication->leave_status) ?></td>
                    </tr>
        </tr>
    </table>

    
  
</div>

