<!DOCTYPE html> 
<html>

	<head>
		<script type="text/javascript">
			$(function() {
				$( "#accordion" ).accordion({});
				$('#clear').click(function(event)
					{
						$.ajax({
						url     : "action/testing.php",
						type	: "POST",						
						cache	: false,
						data    : {
								operation		: "ClearTables"
							  },
						success : function( data ) {
							$('#TestMessage').html(data);
							}
						});
						event.preventDefault();
					});
				$('#clearSchedule').click(function(event)
					{
						$.ajax({
						url     : "action/testing.php",
						type	: "POST",						
						cache	: false,
						data    : {
								operation		: "ClearSchedule"
							  },
						success : function( data ) {
							$('#TestMessage').html(data);
							}
						});
						event.preventDefault();
					});
				$('#fakeDocs').click(function(event)
					{
						$.ajax({
						url     : "action/testing.php",
						type	: "POST",						
						cache	: false,
						data    : {
								operation		: "FakeDocs"
							  },
						success : function( data ) {
							$('#TestMessage').html(data);
							}
						});
						event.preventDefault();
					});
			});
			
		</script>
	</head>
	<body>
		<div id="accordion">
			<h3>Doctor Table</h3>
			<div>
				<table style="width: 100%;" border="1">
					<tr><td>Doctor_ID</td><td>Name</td><td>Phone</td><td>Start_Date</td><td>End_Date</td></tr>
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
						    echo "<tr><td>".$row['Doctor_ID']."</td><td>".$row['Name']."</td><td>".$row['Phone']."</td><td>".$row['Start_Date']."</td><td>".$row['End_Date']."</td></tr>";
						}
		
						mysql_free_result($result);
						mysql_close($link);
					?>
				</table>
			</div>
			<h3>Doctor_History Table</h3>
			<div>
			<table style="width: 100%;" border="1">
				<tr><td>Doctor_ID</td><td>Weekday</td><td>Weekend</td><td>Holiday</td><td>Vacation</td><td>Theoretical_Weekend</td><td>Theoretical_Weekday</td><td>Theoretical_Holiday</td><td>Total_Holiday</td><td>Total_Weekend</td><td>Total_Weekday</td></tr>
				<?php
					
					$username = "robh_user";
					$password = "3720project";
					$link = mysql_connect("localhost",$username,$password);
					if (!$link) {
					    die('Could not connect: ' . mysql_error());
					}
	
					mysql_select_db("robh_3720",$link);
	
					$result = mysql_query('SELECT * from Doctor_History');
					if (!$result) {
					    die('Invalid query: ' . mysql_error());
					}
	
					while ($row = mysql_fetch_assoc($result)) {
					    echo "<tr><td>".$row['Doctor_ID']."</td><td>".$row['Weekday']."</td><td>".$row['Weekend']."</td><td>".$row['Holiday']."</td><td>".$row['Vacation']."</td><td>".$row['Theoretical_Weekend']."</td><td>".$row['Theoretical_Weekday']."</td><td>".$row['Theoretical_Holiday']."</td><td>".$row['Total_Holiday']."</td><td>".$row['Total_Weekend']."</td><td>".$row['Total_Weekday']."</td></tr>";
					}
	
					mysql_free_result($result);
					mysql_close($link);
				?>
			</table>
			</div>
			<h3>Requests Table</h3>
			<div>
			<table style="width: 100%;" border="1">
				<tr><td>Doctor_ID</td><td>Type</td><td>Date</td></tr>
				<?php
					
					$username = "robh_user";
					$password = "3720project";
					$link = mysql_connect("localhost",$username,$password);
					if (!$link) {
					    die('Could not connect: ' . mysql_error());
					}
	
					mysql_select_db("robh_3720",$link);
	
					$result = mysql_query('SELECT * from Requests');
					if (!$result) {
					    die('Invalid query: ' . mysql_error());
					}
	
					while ($row = mysql_fetch_assoc($result)) {
					    echo "<tr><td>".$row['Doctor_ID']."</td><td>".$row['Type']."</td><td>".$row['Date']."</td></tr>";
					}
	
					mysql_free_result($result);
					mysql_close($link);
				?>
			</table>
			</div>
			<h3>Schedule Table</h3>
			<div>
			<table style="width: 100%;" border="1">
				<tr><td>Month</td><td>Year</td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td><td>8</td><td>9</td><td>10</td><td>11</td><td>12</td><td>13</td><td>14</td><td>15</td><td>16</td><td>17<td><td>18</td><td>19</td><td>20</td><td>21</td><td>22</td><td>23</td><td>24</td><td>25</td><td>26</td><td>27</td><td>28</td><td>29</td><td>30</td><td>31</td></tr>
				<?php
					
					$username = "robh_user";
					$password = "3720project";
					$link = mysql_connect("localhost",$username,$password);
					if (!$link) {
					    die('Could not connect: ' . mysql_error());
					}
	
					mysql_select_db("robh_3720",$link);
	
					$result = mysql_query('SELECT * from Schedule');
					if (!$result) {
					    die('Invalid query: ' . mysql_error());
					}
	
					while ($row = mysql_fetch_assoc($result)) {
					    echo "<tr><td>".$row['Month']."</td><td>".$row['Year']."</td><td>".$row['1']."</td><td>".$row['2']."</td><td>".$row['3']."</td><td>".$row['4']."</td><td>".$row['5']."</td><td>".$row['6']."</td><td>".$row['7']."</td><td>".$row['8']."</td><td>".$row['9']."</td><td>".$row['10']."</td><td>".$row['11']."</td><td>".$row['12']."</td><td>".$row['13']."</td><td>".$row['14']."</td><td>".$row['15']."</td><td>".$row['16']."</td><td>".$row['17']."</td><td>".$row['18']."</td><td>".$row['19']."</td><td>".$row['20']."</td><td>".$row['21']."</td><td>".$row['22']."</td><td>".$row['23']."</td><td>".$row['24']."</td><td>".$row['25']."</td><td>".$row['26']."</td><td>".$row['27']."</td><td>".$row['28']."</td><td>".$row['29']."</td><td>".$row['30']."</td><td>".$row['31']."</td></tr>";
					}
	
					mysql_free_result($result);
					mysql_close($link);
				?>
			</table>
			</div>
			<h3>Settings Table</h3>
			<div>
			<table style="width: 100%;" border="1">
				<tr><td>Doctor_ID</td><td>Color1</td><td>Color2</td><td>Color3</td><td>First_Day_View</td></tr>
				<?php
					
					$username = "robh_user";
					$password = "3720project";
					$link = mysql_connect("localhost",$username,$password);
					if (!$link) {
					    die('Could not connect: ' . mysql_error());
					}
	
					mysql_select_db("robh_3720",$link);
	
					$result = mysql_query('SELECT * from Settings');
					if (!$result) {
					    die('Invalid query: ' . mysql_error());
					}
	
					while ($row = mysql_fetch_assoc($result)) {
					    echo "<tr><td>".$row['Doctor_ID']."</td><td>".$row['Color1']."</td><td>".$row['Color2']."</td><td>".$row['Color3']."</td><td>".$row['First_Day_View']."</td></tr>";
					}
	
					mysql_free_result($result);
					mysql_close($link);
				?>
			</table>
			</div>
			<h3>Tools</h3>
			<div>
				<input id="clear" type="button" value="Clear Tables"></input>
				<input id="clearSchedule" type="button" value="Clear Schedule"></input>
				<input id="fakeDocs" type="button" value="Add 10 Fake Doctors"></input>
				<p id="TestMessage"> </p>
			</div>
		</div>
	</body>
</html>