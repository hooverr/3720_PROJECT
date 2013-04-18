<table style="width:100%; text-align: center;" class="ui-widget-content ui-corner-all">
	<tr style="border-style:solid; font-size: small;" class="ui-widget-header ui-corner-all"><th>Name</th><th>Start Date</th><th>End Date</th><th>Weekdays</th><th>Weekends</th><th>Holidays</th><th>Previous<br/>Weekdays</th><th>Previous<br/>Weekends</th><th>Previous<br/>Holidays</th></tr>
	<?php
		
		include('login.php');	
		$colors = array("#7d7d7d","#7d9dbd","#7dbdfd","#7dfd9d","#9d7ddd","#9dbd7d","#9dddbd","#9dfdfd","#bd9d9d","#bdbddd","#bdfd7d","#dd7dbd","#dd9dfd","#dddd9d","#ddfddd","#fd9d7d","#fdbdbd","#fdddfd","#555555","#557595","#5595d5","#55d575","#7555b5","#759555","#75b595","#75d5d5","#957575","#9595b5","#95d555","#b55595","#b575d5","#b5b575","#b5d5b5","#d57555","#d59595","#d5b5d5","#2d2d2d","#2d4d6d","#2d6dad","#2dad4d","#4d2d8d","#4d6d2d","#4d8d6d","#4dadad","#6d4d4d","#6d6d8d","#6dad2d","#8d2d6d","#8d4dad","#8d8d4d","#8dad8d","#ad4d2d","#ad6d6d","#ad8dad");
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