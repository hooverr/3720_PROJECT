<!DOCTYPE html> 
<html>
	<head>
		<script type="text/javascript">
			
		function changeFunc()
		{
			$(":submit").val($("#funcSelect").val());
			if($("#funcSelect").val() == "Update")
			{
				var vals = $('select[name="doc"] option:selected').text().split(" ");
				$('input[name="firstName"]').val(vals[0]);
				$('input[name="lastName"]').val(vals[1]);
				$('input[name="phoneNumber"]').val($('select[name="doc"] option:selected').val().split("|")[1]);
				$( 'input[name="startDatePicker"]' ).show();
				$( "#startDatePickerLabel" ).show();
				$( 'input[name="endDatePicker"]' ).hide();
				$( "#endDatePickerLabel" ).hide();
				$( 'select[name="doc"]').show();
				$( 'label[name="docLbl"]').show();
				$( 'tr[name^="edit"]').show();
				
			}
			else if($("#funcSelect").val() == "Add")
			{
				$( 'input[name="startDatePicker"]' ).show();
				$( "#startDatePickerLabel" ).show();
				$( 'input[name="endDatePicker"]' ).hide();
				$( "#endDatePickerLabel" ).hide();
				$( 'select[name="doc"]').hide();
				$( 'label[name="docLbl"]').hide();
				$( 'tr[name^="edit"]').show();
			}
			else
			{
				$( 'input[name="startDatePicker"]' ).hide();
				$( "#startDatePickerLabel" ).hide();
				$( 'input[name="endDatePicker"]' ).show();
				$( "#endDatePickerLabel" ).show();
				$( 'select[name="doc"]').show();
				$( 'label[name="docLbl"]').show();
				$( 'tr[name^="edit"]').hide();
				$( 'tr[name^="editD"]').show();
			}
		}
			
			
		$(function() {
		$( 'input[name="startDatePicker"]' ).datepicker({ dateFormat: "yy-mm-dd" });
	
		$( 'input[name="endDatePicker"]' ).datepicker({ dateFormat: "yy-mm-dd" });
		
		$('#DocForm').submit(function(event)
					{
						$.ajax({
						url     : "action/doctor.php",
						type	: "POST",						
						cache	: false,
						data    : {
								doc		: $('select[name="doc"]').val(),
								func		: $('#funcSelect').val(),
								firstName	: $('input[name="firstName"]').val(),
								lastName	: $('input[name="lastName"]').val(),
								phoneNumber	: $('input[name="phoneNumber"]').val(),
								startDatePicker	: $('input[name="startDatePicker"]').val(),
								endDatePicker	: $('input[name="endDatePicker"]').val()
							  },
						success : function( data ) {
							$('#message').html(data);
							}
						});
						event.preventDefault();
					});
		changeFunc();
		$('input[name="phoneNumber"]').mask("999-999-9999");
		});
		</script>
	</head>
	<body>
		<center>
			<form id = "DocForm">
				<table>

					<tr>
						<td valign="top">
							<table style="text-align: left !important;">
								<tr>
									<td>
										<label>Select an Operation:</label>
									</td>
									<td>
										<select id="funcSelect" name = "func" style="width: 150px;" onchange="changeFunc()">
											<option value="Add">Add a Doctor</option>
											<option value="Remove">Remove a Doctor</option>
											<option value="Update">Update a Doctor</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>
				
										<label name="docLbl">Select a Doctor:</label>
									</td>
									<td>
										<select name = "doc" onchange="changeFunc()" style="width: 150px;">
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
								<tr name="editFirstName">
									<td>
										<label>First Name:</label>
									</td>
									<td>
										<input style="width: 98%;" name="firstName" type="text"></input>
									</td>
								</tr>
								<tr name="editLastName">
									<td>
										<label>Last Name:</label>
									</td>
									<td>
										<input style="width: 98%;" name="lastName"  type="text"></input>
									</td>
								</tr>
								<tr name="editPhone">
									<td>
										<label>Phone Number:</label>
									</td>
									<td>
										<input style="width: 98%;" name="phoneNumber"  type="text"></input>
										
										<script type="text/javascript">
											$(function() 
											{
												$('input[name="phoneNumber"]').mask("999-999-9999");
											});
										</script>
									</td>
								</tr>
								<tr name="editDates">
									<td>
										<label id="startDatePickerLabel">Select Start Date:</label>
										<label id="endDatePickerLabel">Select End Date:</label>
									</td>
									<td>
										<?php
											date_default_timezone_set('America/Edmonton');
											echo '<input style="width: 98%;" type="text" value="'.date("Y-m-j").'" name="startDatePicker"></input>';
											echo '<input style="width: 98%;" type="text" value="'.date("Y-m-j").'" name="endDatePicker"></input>';
										
										?>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<input style="width:100%;" type="submit" value="Add"></input>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div style="text-align: center;" id="message"> </div>
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
