<!DOCTYPE html> 
<html>
	<head>
		<script type="text/javascript">
	
			
		$(function() {
		$('select[name="ReportMonth"]').val(new Date().getMonth()+1);
		
		/*
		    Function: Form Submit
		 
		    Overrides the default form submit to relocate to the report.php page to generate the report. Also includes report paramaters
		 
		 */
		$('#ReportForm').submit(function(event)
					{
						window.location.href = "action/report.php?month=" + $('select[name="ReportMonth"] option:selected').val() + "&type="+ $('input:radio[name="reportType"]:checked').val();
						
						event.preventDefault();
					});
		});
		</script>
	</head>
	<body>
		<center>
			<form id = "ReportForm">
				<select name = "ReportMonth"  style="width: 150px;">
					<?php
						// some month names
						$months[1] = "January";
						$months[2] = "Febuary";
						$months[3] = "March";
						$months[4] = "April";
						$months[5] = "May";
						$months[6] = "June";
						$months[7] = "July";
						$months[8] = "August";
						$months[9] = "September";
						$months[10] = "October";
						$months[11] = "November";
						$months[12] = "December";
						// include login info and connect
						include('login.php');
						$link = mysql_connect($host,$username,$password);
						if (!$link) {
						    die('Could not connect: ' . mysql_error());
						}
		
						mysql_select_db($database,$link);
						// select months and years from the schedule
						$result = mysql_query('SELECT Month,Year from Schedule');
						if (!$result) {
						    die('Invalid query: ' . mysql_error());
						}
						// add options to the report Month/Year selector based on available data
						while ($row = mysql_fetch_assoc($result)) {
						    echo "<option value=\"".$row['Month']."-".$row['Year']."\">".$months[$row['Month']]." ".$row['Year']."</option>";
						}

						mysql_free_result($result);
						mysql_close($link);
					?>
				</select>
				<input style="width:150px;" type="submit" value="View Report"></input>
				<br/>
				<br/>
				<input type="radio" name="reportType" value="internal" checked="checked">Internal Report&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="reportType" value="external">External Report
			</form>
		</center>

	</body>
</html>
