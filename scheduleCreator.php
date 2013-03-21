<?php
/*
  Class: ScheduleCreator
  
  Creates a schedule based on information stored in the database.
  Has the ability to return a jsonString of the created schedule.
*/
class ScheduleCreator{	
  /*
    Function: getDoctorNames
     
    Creates an array of doctors from the database.
      
    Returns:
      
    An array of doctor names, positioned by their id.
      
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
  /*
    Function: createCalendarHolidayArray
     
    Creates an array of scheduled days from the database.
      
    Returns:
      
    An array representing days that have been scheduled.
      
  */
  private function getDatabaseSchedules(){
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
    Function: createScheduleArray
     
    Creates a 2d array of scheduled days.
    
    Returns:
      
    A 2d array of scheduled days that contains the doctor name, day scheduled, and a unique color.
    This assumes use of the FullCalendar library.
      
  */
  private function createScheduleArray(){
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
      $colors = array("#F7D45A","#7DDB90","#B8D7C4","#6600CC","#CC3300","#CCCC33","#FF9966","#6666CC","#0066CC","#8899EF","#7E2447","5C3ABE","#CE6C8C","#8BC532","420B5F");
      
      
      //index, increments for each day scheduled 
      $day  = 1;	
      foreach($createdSchedule as $value){
        //add a leading 0 so numbers < 10 display correctly
        $dayString = str_pad($day,2,"0",STR_PAD_LEFT);
        $monthString = str_pad($month,2,"0",STR_PAD_LEFT);
        $dayArray = array(
            'id' => "$value",
            'title' => $doctorNames[$value],
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
   /*
    Function: jsonString
     
    Creates a jsonstring representation of the schedule.
      
    Returns:
      
    A jsonstring representation of the schedule, used by FullCalendar
      
  */
  public function jsonString(){
    return json_encode($this->createScheduleArray());
  }
}
?>