<?php
/*
Class used to create the calendar array from
the array of scheduled doctors then output 
a json encoded string that Full Calendar can read
*/
class ScheduleCreator{	
  /*Function to retrieve doctors names from the database
    Returns an array of the names in the position of their id
  */
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
  /*Function to retrieve the schedules from the database
  Returns a 2d array of monthly schedules where each row is a month
  @todo make this work for more than one month
  */
  public function getDatabaseSchedules(){
    $mysqli = new mysqli('localhost','robh_user','3720project','robh_3720');
    $query = "SELECT * FROM Schedule" ; 
    $result = $mysqli->query($query);
    $return = array();
    $i=0;
    while($row = $result->fetch_array(MYSQLI_ASSOC)){
      $return[$i] = $row;
      $i++;
    }
    $result->free;
    $mysqli->close();
   
    return $return;
  }
  /*
  Function that takes an input scheduled doctors array,
  month and year.
  month format: 01,02....12
  year format:  0001,0002....9999
  
  returns a 2d array of days that are represented as an array
  */
  public function createScheduleArray(){
    $doctorNames = $this->getDoctorNames();
    $array = $this->getDatabaseSchedules();
    foreach($array as $row){
      $month = $row['Month'];
      $year = $row['Year'];
      //break out the schedule array from query 
      $createdSchedule = array_slice($row,2);
      //remove any null values
      $createdSchedule = array_filter($createdSchedule);
      
      //Color options for doctors
      //@todo create a color for the number of unique doctors
      $colors = array("#0000FF","#FF0000","00FF00","#5F9EA0","#008B8B","#B8860B");
      
      
      //index, increments for each day scheduled 
      $day  = 1;	
      foreach($createdSchedule as $value){
        //add a leading 0 so numbers < 10 display correctly
        $dayString = str_pad($day,2,"0",STR_PAD_LEFT);
        $monthString = str_pad($month,2,"0",STR_PAD_LEFT);
        $dayArray = array(
            'id' => "$year-$monthString-$dayString",
            'title' => $doctorNames[$value],
            'contact' => "xxx-xxx-xxxx",
            'start' => "$year-$monthString-$dayString",
            'backgroundColor' => $colors[$value % 6]
        );     
        //add the day to the schedule
        $schedule[] = $dayArray;
        $day++;
        //clear the array
        unset($tempArray);	
      }
    }
		return $schedule;
	}
  
  
}
$creator = new ScheduleCreator();
$schedule = $creator->createScheduleArray();
echo json_encode($schedule);
?>