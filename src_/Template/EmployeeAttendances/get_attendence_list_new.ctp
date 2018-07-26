<form method='post' >
<input type="hidden" name="month" value="<?php echo $f[0]; ?>" />
<input type="hidden" name="year" value="<?php echo $f[1]; ?>" />
<table id="main_table" class="table table-condensed table-bordered" style="margin-bottom: 4px;" width="80%">
	<thead>
		<tr>
			<td>S.N</td>
			<td>Employee Name</td>
			<td>Present Day</td>
		</tr>
	</thead>
	<tbody id="main_tbody1">
		<?php $p=1; $i=1; foreach($EmployeeAttendances as $EmployeeAttendance){  ?>
			<tr>
				<td><?php echo $p++; ?>
				<td><?php echo $EmployeeAttendance->employee->name; ?></td>
				<td><input name="attn[<?php echo $EmployeeAttendance->employee->id; ?>]" type="text" value="<?php echo $EmployeeAttendance->present_day; ?>" /></td>
			</tr>
		<?php $i++; }  ?>
	</tbody>
</table>
<button type="submit">Update</button>
</form>

