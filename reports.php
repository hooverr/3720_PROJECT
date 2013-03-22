<!DOCTYPE html> 
<html>
	<head>
		<script type="text/javascript">
	
			
		$(function() {
		$('select[name="ReportMonth"]').val(new Date().getMonth()+1);
		
		$('#ReportForm').submit(function(event)
					{
						window.location.href = "action/report.php?month=" + $('select[name="ReportMonth"]').val();
						
						event.preventDefault();
					});
		});
		</script>
	</head>
	<body>
		<center>
			<form id = "ReportForm">
				<select name = "ReportMonth"  style="width: 150px;">
					<option value="1">January</option>
					<option value="2">Febuary</option>
					<option value="3">March</option>
					<option value="4">April</option>
					<option value="5">May</option>
					<option value="6">June</option>
					<option value="7">July</option>
					<option value="8">August</option>
					<option value="9">September</option>
					<option value="10">October</option>
					<option value="11">November</option>
					<option value="12">December</option>
				</select>
				<input style="width:150px;" type="submit" value="View Report"></input>			
			</form>
		</center>

	</body>
</html>
