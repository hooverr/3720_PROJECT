<table style="width:100%; text-align: center;" class="ui-widget-content ui-corner-all">
	<tr style="border-style:solid; font-size: small;" class="ui-widget-header ui-corner-all"><th>Name</th><th>Start Date</th><th>End Date</th><th>Weekdays</th><th>Weekends</th><th>Holidays</th><th>Previous Weekdays</th><th>Previous Weekends</th><th>Previous Holidays</th></tr>
	<?php
		
		include('login.php');	
		$colors = array("#7d7d7d","#7d9dbd","#7dbdfd","#7dfd9d","#9d7ddd","#9dbd7d","#9dddbd","#9dfdfd","#bd9d9d","#bdbddd","#bdfd7d","#dd7dbd","#dd9dfd","#dddd9d","#ddfddd","#fd9d7d","#fdbdbd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd","#fdddfd");							
		
		$link = mysql_connect($host,$username,$password);
		if (!$link) {
			die('Could not connect: ' . mysql_error());
		}

		mysql_select_db($database,$link);

		$result = mysql_query('SELECT `Name`,`Start_Date`,`End_Date`,`Weekday`,`Weekend`,`Holiday`,`Previous_Weekday`,`Previous_Weekend`,`Previous_Holiday`,`Doctor`.`Doctor_ID` from Doctor_History, Doctor WHERE Doctor_History.Doctor_ID = Doctor.Doctor_ID');
		if (!$result) {
			die('Invalid query: ' . mysql_error());
		}

		while ($row = mysql_fetch_assoc($result)) {
			$color = $colors[intval($row['Doctor_ID']) % count($colors)];
			echo "<tr><td style=\"background-color: #eeeeee; text-align: left; border:2px solid ".$color.";\">".$row['Name']."</td><td style=\"border-bottom:1px solid #dddddd;\">".$row['Start_Date']."</td><td  style=\"background-color: #eeeeee;  border-bottom:1px solid #dddddd;\">".$row['End_Date']."&nbsp</td><td style=\"border-bottom:1px solid #dddddd;\">".$row['Weekday']."</td><td style=\"background-color: #eeeeee;  border-bottom:1px solid #dddddd;\">".$row['Weekend']."</td><td style=\"border-bottom:1px solid #dddddd;\">".$row['Holiday']."</td><td style=\"background-color: #eeeeee;  border-bottom:1px solid #dddddd;\">".$row['Previous_Weekday']."</td><td style=\"border-bottom:1px solid #dddddd;\">".$row['Previous_Weekend']."</td><td style=\"background-color: #eeeeee;  border-bottom:1px solid #dddddd;\">".$row['Previous_Holiday']."</td></tr>";
		}

		mysql_free_result($result);
		mysql_close($link);
	?>
</table>