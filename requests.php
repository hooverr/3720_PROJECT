<!DOCTYPE html> 
<html>
	<head>
		<script type="text/javascript">
	
			
		$(function() {
		$( 'input[name="requestStartDatePicker"]' ).datepicker({ dateFormat: "yy-mm-dd" });
	
		//( 'input[name="endDatePicker"]' ).datepicker({ dateFormat: "yy-mm-dd" });
		
		$('#RequestForm').submit(function(event)
					{
						$.ajax({
						url     : "action/request.php",
						type	: "POST",						
						cache	: false,
						data    : {
								doc		: $('select[name="requestDoctor"]').val(),
								requestType	: $('input:radio[name="requestType"]:checked').val(),
								startDatePicker	: $('input[name="requestStartDatePicker"]').val()
							  },
						success : function( data ) {
							$('#requestMessage').html(data);
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
				
										<label name="docLbl">Select a Doctor:</label>
									</td>
									<td>
										<select name = "requestDoctor"  style="width: 150px;">
											<?php
												
												$username = "robh_user";
												$password = "3720project";
												$link = mysql_connect("localhost",$username,$password);
												if (!$link) {
												    die('Could not connect: ' . mysql_error());
												}
								
												mysql_select_db("robh_3720",$link);
			
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
										<label id="requestStartDatePickerLabel">Select Start Date:</label>
									</td>
									<td>
										<input style="width: 98%;" type="text" name="requestStartDatePicker"></input>
									</td>
								</tr>
								<!--<tr>
									<td>
										<label id="endDatePickerLabel">Select End Date:</label>
									</td>
									<td>
										<input style="width: 98%;" type="text" name="endDatePicker"></input>
									</td>
								</tr>-->
								<tr>
									<td colspan="2">
										<input style="width:100%;" type="submit" value="Request"></input>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div style="text-align: center;" id="requestMessage"> </div>
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
