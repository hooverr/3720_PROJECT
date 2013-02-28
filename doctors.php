<!DOCTYPE html> 
<html>
	<head>
		<title>CPSC 3720 Calls Project</title>
		<!--
		 This is the main css file for the project.
		 -->
		<link rel="stylesheet" type="text/css" href="mainStyle.php">
		<!-- 
		 Load jQuery and jQueryui from google
		 -->
		<link rel="stylesheet" type="text/css" href="resources/css/smoothness/jquery-ui-1.10.1.custom.min.css"/>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>
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
				
			}
			else if($("#funcSelect").val() == "Add")
			{
				$( 'input[name="startDatePicker"]' ).show();
				$( "#startDatePickerLabel" ).show();
				$( 'input[name="endDatePicker"]' ).hide();
				$( "#endDatePickerLabel" ).hide();
			}
			else
			{
				$( 'input[name="startDatePicker"]' ).hide();
				$( "#startDatePickerLabel" ).hide();
				$( 'input[name="endDatePicker"]' ).show();
				$( "#endDatePickerLabel" ).show();
			}
		}
			
			
		$(function() {
		$( 'input[name="startDatePicker"]' ).datepicker({ dateFormat: "yy-mm-dd" });
	
		$( 'input[name="endDatePicker"]' ).datepicker({ dateFormat: "yy-mm-dd" });
		
		changeFunc();
		});
		</script>
	</head>
	<body>
		<header>
			<h1>Calls Project</h1>
		</header>
		<?php
			if($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				list($doctorID, $phone) = split('[|]', $_POST["doc"]);
				$username = "robh_user";
				$password = "3720project";
				$link = mysql_connect("localhost",$username,$password);
				if (!$link) {
				    die('Could not connect: ' . mysql_error());
				}

				mysql_select_db("robh_3720",$link);
				if($_POST["func"] == "Add")
				{
					mysql_query("insert into Doctor(Name,Phone,Start_Date) values ('".$_POST["firstName"]." ".$_POST["lastName"]."','".$_POST["phoneNumber"]."','".$_POST["startDatePicker"]."')") or die(mysql_error());
				}
				else if($_POST["func"] == "Remove")
				{
					mysql_query("update Doctor set End_Date = '".$_POST["endDatePicker"]."' where Doctor_ID = ".$doctorID) or die(mysql_error());
				}
				else
				{
					mysql_query("update Doctor set Name = '".$_POST["firstName"]." ".$_POST["lastName"]."', Phone='".$_POST["phoneNumber"]."', Start_Date='".$_POST["startDatePicker"]."' where Doctor_ID = ".$doctorID) or die(mysql_error());
				}
			}
		?>
		<nav id="navigation">
			<ul>
				<li><a href="index.html">Schedule</a></li>
				<li id="current"><a href="doctors.php">Doctors</a></li>
				<li><a href="requests.php">Requests</a></li>
				<li><a href="reports.php">Reports</a></li>
			</ul>
		</nav>
		<div id="content">
			<center>
				<form id = "DocForm" action="doctors.php" method="post" >
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
					
											<label>Select a Doctor:</label>
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
				
													$result = mysql_query('SELECT * from 3720Project.Doctor');
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
								</table>
							</td>
							<td style="text-align:right;">
								<table>
									<tr>
										<td>
											<label>First Name:</label>
										</td>
										<td>
											<input name="firstName" type="text"></input>
										</td>
									</tr>
									<tr>
										<td>
											<label>Last Name:</label>
										</td>
										<td>
											<input name="lastName"  type="text"></input>
										</td>
									</tr>
									<tr>
										<td>
											<label>Phone Number:</label>
										</td>
										<td>
											<input name="phoneNumber"  type="text"></input>
										</td>
									</tr>
									<tr>
										<td>
											<label id="startDatePickerLabel">Select Start Date:</label>
											<label id="endDatePickerLabel">Select End Date:</label>
										</td>
										<td>
											<?php
												date_default_timezone_set('America/Edmonton');
												echo '<input type="text" value="'.date("Y-m-j").'" name="startDatePicker"></input>';
												echo '<input type="text" value="'.date("Y-m-j").'" name="endDatePicker"></input>';
											
											?>
										</td>
									</tr>
									<tr>
										<td>
											
										</td>
										<td>
											<input style="width:100px;" type="submit" value="Add"></input>
										</td>
									</tr>
								</table>
								
									
							</td>
						</tr>
					</table>
					<?php
						if($_SERVER['REQUEST_METHOD'] == 'POST')
						{
							if($_POST["func"] == "Add")
							{
								echo "<br/><i style=\"color:darkgreen;\">Doctor ".$_POST["firstName"]." ".$_POST["lastName"]." added successfully.</i>";
							}
							else if($_POST["func"] == "Remove")
							{
								echo "<br/><i style=\"color:darkgreen;\">Doctor removed successfully.</i>";
							}
							else
							{
								echo "<br/><i style=\"color:darkgreen;\">Doctor updated successfully.</i>";
							}
						}
					?>
					<table style="position:fixed; bottom:0px; left:0px;" border="1">
						<tr><td>Doctor_ID</td><td>Name</td><td>Phone</td><td>Start_Date</td><td>End_Date</td></tr>
						<?php
							
							$username = "robh_user";
							$password = "3720project";
							$link = mysql_connect("localhost",$username,$password);
							if (!$link) {
							    die('Could not connect: ' . mysql_error());
							}
			
							mysql_select_db("robh_3720",$link);
	
							$result = mysql_query('SELECT * from 3720Project.Doctor');
							if (!$result) {
							    die('Invalid query: ' . mysql_error());
							}
	
							while ($row = mysql_fetch_assoc($result)) {
							    echo "<tr><td>".$row['Doctor_ID']."</td><td>".$row['Name']."</td><td>".$row['Phone']."</td><td>".$row['Start_Date']."</td><td>".$row['End_Date']."</td></tr>";
							}
	
							mysql_free_result($result);
							mysql_close($link);
						?>
					</table>
				</form>
			</center>
		</div>

		<footer></footer>
	</body>
</html>
