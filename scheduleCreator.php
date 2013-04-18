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
    include('login.php'); 
    $mysqli = new mysqli($host,$username,$password,$database);
    $query = "SELECT doctor_id, name from Doctor"; 
    if($result = $mysqli->query($query)){
      while($row= $result->fetch_assoc()){
        $nameArray[$row["doctor_id"]] = $row["name"];
       }
      $result->free();
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
    include('login.php'); 
    $mysqli = new mysqli($host,$username,$password,$database);
    $query = "SELECT * FROM Schedule" ; 
    $result = $mysqli->query($query);
    $return = array();
    $i=0;
    while($row = $result->fetch_array(MYSQLI_ASSOC)){
      $return[$i] = $row;
      $i++;
    }
    $result->free();
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
      $colors = array("#7d7d7d","#7d9dbd","#7dbdfd","#7dfd9d","#9d7ddd","#9dbd7d","#9dddbd","#9dfdfd","#bd9d9d","#bdbddd","#bdfd7d","#dd7dbd","#dd9dfd","#dddd9d","#ddfddd","#fd9d7d","#fdbdbd","#fdddfd","#555555","#557595","#5595d5","#55d575","#7555b5","#759555","#75b595","#75d5d5","#957575","#9595b5","#95d555","#b55595","#b575d5","#b5b575","#b5d5b5","#d57555","#d59595","#d5b5d5","#2d2d2d","#2d4d6d","#2d6dad","#2dad4d","#4d2d8d","#4d6d2d","#4d8d6d","#4dadad","#6d4d4d","#6d6d8d","#6dad2d","#8d2d6d","#8d4dad","#8d8d4d","#8dad8d","#ad4d2d","#ad6d6d","#ad8dad");
      
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
            'backgroundColor' => $colors[$value % count($colors)]
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