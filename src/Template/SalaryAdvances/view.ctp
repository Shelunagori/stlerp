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
            <td align="center" width="30%" style="font-size: 12px;"><div align="center" style="font-size: 16px;font-weight: bold;color: #0685a8;">Salary Advance</div></td>
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
                        <td><?= h($salaryAdvance->employee->name) ?></td>
                    </tr>
                   
					<tr>
						<td>Amount</td>
						<td width="20" align="center">:</td>
						 <td><?= h($salaryAdvance->amount) ?></td>
					</tr>
					<tr>
						<td>Reason</td>
						<td width="20" align="center">:</td>
						 <td><?= h($salaryAdvance->reason) ?></td>
					</tr>
					<tr>
						<td>For Month</td>
						<td width="20" align="center">:</td>
						<td><?= h(date("d-m-Y",strtotime($salaryAdvance->for_month))) ?></td>
					</tr>
					<tr>
                        <td>Leave Status</td>
                        <td width="20" align="center">:</td>
						<td><?= h($salaryAdvance->status) ?></td>
                    </tr>
        </tr>
    </table>

    
  
</div>

