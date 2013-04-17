<!DOCTYPE html> 
<html>
	<head>
		<script type="text/javascript">
	
			
		$(function() {
		//$( 'input[name="requestStartDatePicker"]' ).datepicker({ dateFormat: "yy-mm-dd" });
		
		$( 'input[name="requestStartDatePicker"]' ).datepicker({ 
				dateFormat: "yy-mm-dd", 
                numberOfMonths: 1,
                onSelect: function(selected) {
					$('input[name="requestEndDatePicker"]').datepicker("option","minDate", selected)
				}

			});
		
		
		//$( 'input[name="requestEndDatePicker"]' ).datepicker({ dateFormat: "yy-mm-dd" });
		
		$( 'input[name="requestEndDatePicker"]' ).datepicker({ 
				dateFormat: "yy-mm-dd", 
                numberOfMonths: 1,
                onSelect: function(selected) {
					$('input[name="requestStartDatePicker"]').datepicker("option","maxDate", selected)
				}

			});
		
		$('#RequestForm').submit(function(event)
					{
						$.ajax({
						url     : "action/request.php",
						type	: "POST",						
						cache	: false,
						data    : {
								doc		: $('select[name="requestDoctor"]').val(),
								requestType	: $('input:radio[name="requestType"]:checked').val(),
								startDatePicker	: $('input[name="requestStartDatePicker"]').val(),
								requestEndDatePicker	: $('input[name="requestEndDatePicker"]').val()
							  },
						success : function( data ) {
							alert(data);
							}
						});
						
						event.preventDefault();
					});
		});
		</script>
	</head>
	<body>
		<center>
			<form id = "RequestForm">
				<table>

					<tr>
						<td valign="top">
							<table style="text-align: left !important;">
								<tr>
									<td>
				
										<label name="reqdocLbl">Select a Doctor:</label>
									</td>
									<td>
										<select name = "requestDoctor"  style="width: 150px;">
											<?php
												include("login.php");
												
												$link = mysql_connect($host,$username,$password);
												if (!$link) {
												    die('Could not connect: ' . mysql_error());
												}
								
												mysql_select_db($database,$link);
			
												$result = mysql_query('SELECT * from Doctor');
												if (!$result) {
												    die('Invalid query: ' . mysql_error());
												}
			
												while ($row = mysql_fetch_assoc($result)) {
												    echo "<option value=\"".$row['Doctor_ID']."|".$row['Phone']."\">".$row['Name']."</option>";
												}
			
												mysql_free_result($result);
												mysql_close($link);
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<label>Request Type:</label>
									</td>
									<td>
										<input type="radio" name="requestType" value="Off" checked></input> Off<br/>
										<input type="radio" name="requestType" value="On"> On</input>
									</td>
								</tr>
								<tr>
									<td>
										<label id="requestStartDatePickerLabel">Select Beginning Date:</label>
									</td>
									<td>
										<input style="width: 98%;" type="text" name="requestStartDatePicker"></input>
									</td>
								</tr>
								<tr>
									<td>
										<label id="requestEndDatePickerLabel">Select Ending Date:</label>
									</td>
									<td>
										<input style="width: 98%;" type="text" name="requestEndDatePicker"></input>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<input style="width:100%;" type="submit" value="Request"></input>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>				
			</form>
		</center>

	</body>
</html>
