<!DOCTYPE html> 
<html>
	<head>
		<script type="text/javascript">
		var statsVisible = false;
		var doctorData = new Array();
		<?php				
			include('login.php');	
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
			    echo "doctorData[".$row['Doctor_ID']."] = new Array();";
			    echo "doctorData[".$row['Doctor_ID']."][0] = \"".$row['Name']."\";";
			    echo "doctorData[".$row['Doctor_ID']."][1] = \"".$row['Phone']."\";";
			    echo "doctorData[".$row['Doctor_ID']."][2] = \"".$row['Start_Date']."\";";
			    echo "doctorData[".$row['Doctor_ID']."][3] = \"".$row['End_Date']."\";";
			}

			mysql_free_result($result);
			mysql_close($link);
		?>
		function changeFunc()
		{
			$('input[name="docSubmit"]').val($("#funcSelect").val());
			if($("#funcSelect").val() == "Update")
			{
				
				$( 'input[name="startDatePicker"]' ).show();
				$( "#startDatePickerLabel" ).show();
				$( 'input[name="endDatePicker"]' ).hide();
				$( "#endDatePickerLabel" ).hide();
				$( 'select[name="doc"]').show();
				$( 'label[name="docLbl"]').show();
				$( 'tr[name^="edit"]').show();
				$( 'input[name="weekdays"]' ).hide();
				$( 'input[name="weekends"]' ).hide();
				$( 'input[name="holidays"]' ).hide();
				$( 'label[name="weekdayLabel"]' ).hide();
				$( 'label[name="weekendLabel"]' ).hide();
				$( 'label[name="holidayLabel"]' ).hide();
				
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
				$( 'input[name="weekdays"]' ).show();
				$( 'input[name="weekends"]' ).show();
				$( 'input[name="holidays"]' ).show();
				$( 'label[name="weekdayLabel"]' ).show();
				$( 'label[name="weekendLabel"]' ).show();
				$( 'label[name="holidayLabel"]' ).show();
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
				$( 'input[name="weekdays"]' ).hide();
				$( 'input[name="weekends"]' ).hide();
				$( 'input[name="holidays"]' ).hide();
				$( 'label[name="weekdayLabel"]' ).hide();
				$( 'label[name="weekendLabel"]' ).hide();
				$( 'label[name="holidayLabel"]' ).hide();
			}
			var doctorID = $('select[name="doc"] option:selected').val();
			var vals = doctorData[doctorID][0].split(" ");
			$('input[name="firstName"]').val(vals[0]);
			$('input[name="lastName"]').val(vals[1]);
			$('input[name="phoneNumber"]').val( doctorData[doctorID][1]);
			$( 'input[name="startDatePicker"]' ).val( doctorData[doctorID][2]);
			$( 'input[name="endDatePicker"]' ).val( doctorData[doctorID][3]);
		}
			
			
		$(function() {
		if(!statsVisible)
		{
			$('#doctorHistory').hide();
		}
		$( 'input[name="startDatePicker"]' ).datepicker({ dateFormat: "yy-mm-dd", maxDate: "+2M" });
	
		$( 'input[name="endDatePicker"]' ).datepicker({ dateFormat: "yy-mm-dd" });
		$('#statsButton').button().click(function()
		{
			if(statsVisible == true)
			{
				$( "#doctorHistory" ).hide( "blind", 300 );	
			}
			else
			{
				$( "#doctorHistory" ).show( "blind", 300 );	
			}
			statsVisible = !statsVisible;
		});
		$('#DocForm').submit(function(event)
					{
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
									endDatePicker	: $('input[name="endDatePicker"]').val(),
									
									weekdays	: $('input[name="weekdays"]').val(),
									weekends	: $('input[name="weekends"]').val(),
									holidays	: $('input[name="holidays"]').val()
								  },
							success : function( data ) {
									alert(data);
									var doctorID = $('select[name="doc"]').val();
									doctorData[doctorID][0] = $('input[name="firstName"]').val() + ' ' + $('input[name="lastName"]').val();
									doctorData[doctorID][1] = $('input[name="phoneNumber"]').val();
									doctorData[doctorID][2] = $('input[name="startDatePicker"]').val();
									doctorData[doctorID][3] = $('input[name="endDatePicker"]').val();
									$("#docOpt"+doctorID).text(doctorData[doctorID][0]);
								}
							});
					}
						event.preventDefault();
					});
		changeFunc();
		$('input[name="phoneNumber"]').mask("999-999-9999");
		$('input[name="weekdays"]').mask("9?999999999999");
		$('input[name="weekends"]').mask("9?999999999999");
		$('input[name="holidays"]').mask("9?999999999999");
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
												    echo "<option id=\"docOpt".$row['Doctor_ID']."\" value=\"".$row['Doctor_ID']."\">".$row['Name']."</option>";
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
								<tr name="editWeekday">
									<td>
										<label name="weekdayLabel">Previous Weekdays:</label>
									</td>
									<td>
										<input style="width: 98%;" name="weekdays"  type="text"></input>
									</td>
								</tr>
								<tr name="editWeekend">
									<td>
										<label name="weekendLabel">Previous Weekends:</label>
									</td>
									<td>
										<input style="width: 98%;" name="weekends"  type="text"></input>
									</td>
								</tr>
								<tr name="editHoliday">
									<td>
										<label name="holidayLabel">Previous Holidays:</label>
									</td>
									<td>
										<input style="width: 98%;" name="holidays"  type="text"></input>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<input style="width:100%;" name="docSubmit" type="submit" value="Add"></input>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>				
				<input id="statsButton" type="checkbox"/><label for="statsButton" style="width: 100%;">View Doctor Statistics</label>
				<div id='doctorHistory' >
					<table style="width:100%; text-align: center;" class="ui-widget-content ui-corner-all">
						<tr style="border-style:solid; font-size: small;" class="ui-widget-header ui-corner-all"><th>Name</th><th>Start Date</th><th>End Date</th><th>Weekdays</th><th>Weekends</th><th>Holidays</th></tr>
						<?php
							
							include('login.php');	
							$colors = array("#7d7d7d","#7d9dbd","#7dbdfd","#7dfd9d","#9d7ddd","#9dbd7d","#9dddbd","#9dfdfd","#bd9d9d","#bdbddd","#bdfd7d","#dd7dbd","#dd9dfd","#dddd9d","#ddfddd","#fd9d7d","#fdbdbd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd");							
							
							$link = mysql_connect($host,$username,$password);
							if (!$link) {
							    die('Could not connect: ' . mysql_error());
							}
			
							mysql_select_db($database,$link);
			
							$result = mysql_query('SELECT `Name`,`Start_Date`,`End_Date`,`Weekday`,`Weekend`,`Holiday`,`Doctor`.`Doctor_ID` from Doctor_History, Doctor WHERE Doctor_History.Doctor_ID = Doctor.Doctor_ID');
							if (!$result) {
							    die('Invalid query: ' . mysql_error());
							}
			
							while ($row = mysql_fetch_assoc($result)) {
								$color = $colors[intval($row['Doctor_ID']) % count($colors)];
								echo "<tr><td style=\"background-color: #eeeeee; text-align: left; border:2px solid ".$color.";\">".$row['Name']."</td><td style=\"border-bottom:1px solid #dddddd;\">".$row['Start_Date']."</td><td  style=\"background-color: #eeeeee;  border-bottom:1px solid #dddddd;\">".$row['End_Date']."&nbsp</td><td style=\"border-bottom:1px solid #dddddd;\">".$row['Weekday']."</td><td style=\"background-color: #eeeeee;  border-bottom:1px solid #dddddd;\">".$row['Weekend']."</td><td style=\"border-bottom:1px solid #dddddd;\">".$row['Holiday']."</td><td style=\"border-bottom:1px solid #dddddd;\">"."</td></tr>";
							}
			
							mysql_free_result($result);
							mysql_close($link);
						?>
					</table>
				</div>
			</form>
      
 
		</center>

	</body>
</html>
