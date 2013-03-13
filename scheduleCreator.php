<?php
/*
Class used to create the calendar array from
the array of scheduled doctors
*/
class ScheduleCreator{	


  private function getDoctorNames(){
    $mysqli = new mysqli('localhost','robh_user','3720project','robh_3720');
    $query = "SELECT doctor_id, name from Doctor"; 
    if($result = $mysqli->query($query)){
      while($row= $result->fetch_assoc()){
        $nameArray[$row["doctor_id"]] = $row["name"];
       }
      $result->free;
    }
    $mysqli->close();
    return $nameArray;
  }
  
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
    $colors = array("#0000FF","#FF0000","00FF00","#5F9EA0","#008B8B","#B8860B");
    
    $doctorNames = $this->getDoctorNames();
    //index, increments for each day scheduled 
    $day  = 1;	
		foreach($array as $value){
      //add a leading 0 so numbers < 10 display correctly
      $dayString = str_pad($day,2,"0",STR_PAD_LEFT);
			$dayArray = array(
					'id' => "$year-$month-$dayString",
					'title' => $doctorNames[$value],
					'contact' => "xxx-xxx-xxxx",
					'start' => "$year-$month-$dayString",
					'backgroundColor' => $colors[$value % 6]
			);     
      //add the day to the schedule
			$schedule[] = $dayArray;
      $day++;
      //clear the array
			unset($tempArray);	
		}
		return $schedule;
	}
  
  
}
$creator = new ScheduleCreator();
$testArray = array(8,8,9,9,8,9,9,8,9,9,8,9,9,8,9,9,8,9,9,8,9,9,8,9,9);
$testArray2 = array(8,8,9,9,8,9,9,8,9,9,8,9,9,8,9,9,8,9,9,8,9,9,8,9,9);
$jsonArray = $creator->createScheduleArray($testArray,'03','2013');
$jsonArray2 = $creator->createScheduleArray($testArray2,'04','2013');

$test = array();
foreach($jsonArray as $day){
    array_push($test,$day);
}
foreach($jsonArray2 as $day){
    array_push($test,$day);
}

echo json_encode($test);



?>