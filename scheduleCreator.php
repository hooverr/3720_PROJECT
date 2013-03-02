<?php
mb_language('uni');
mb_internal_encoding('UTF-8');
/*
Class used to create the calendar array from
the array of scheduled doctors
*/
class ScheduleCreator{	
  /*
  Function that takes an input scheduled doctors array,
  month and year.
  month format: 01,02....12
  year format:  0001,0002....9999
  
  returns a 2d array of days that are represented as an array
  */
  public function createScheduleArray($array, $month, $year){
  
		//Color options for doctors
    //@todo create a color for the number of unique doctors
		$colors = array("#0000FF","#FF0000","00FF00");
    
    //index, increments for each day scheduled 
    $day  = 01;	
		foreach($array as $value){
			$color = $value % 3;
			$dayArray = array(
					'id' => "$year-$month-$day",
					'title' => "$value",
					'contact' => "xxx-xxx-xxxx",
					'start' => "$year-$month-$day",
					'backgroundColor' => $colors[$color]
			);
      
      //add the day to the schedule
			$schedule[] = $dayArray;
      $day++;
			unset($tempArray);	
		}
		return $schedule;
	}
}

/*Test code 
  This works, just need to implement on live server once scheduling algorithm is complete

$class = new ScheduleCreator();
$testArray= array(1,2,3,3,3,6,7,8);
$temp = $class->createScheduleArray($testArray,02,2013);

//encode the array to be put into the database
$insert = json_encode($temp);
$mysqli = new mysqli('localhost','robh_user','','robh_3720');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

$query = "INSERT INTO schedule VALUES (2, 20,'$insert')";
$mysqli->query($query);




$query2 = "SELECT Schedule FROM schedule WHERE Month = 2 AND Year = 20";

$result = $mysqli->query($query2);

$row = $result->fetch_array(MYSQL_ASSOC);

$decode = json_decode($row["Schedule"]);

//not needed just a test to see if you can return it back to a proper php array
echo var_dump($decode);
$mysqli->close();


*/
?>