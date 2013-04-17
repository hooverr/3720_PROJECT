<?php
/*
  Class: updateSchedule
  
  Updates a schedule based on information given by dialog form.
*/
include_once("holidayCreator.php");
include('login.php');
class updateSchedule{
  
    /*
    Function: isHoliday
     
    Checks if a given day is a holiday
    
    Parameters:
    $day - day to be updated
    $month - month of day to be updated
    $year - year of day to be updated
    $numberOfDaysCurrentMonth - the number of days in the current month
    $numberOfDaysPreviousMonth - the number of days in the previous month
      
    Returns:
      
    A boolean value for if a day is a holiday.
      
  */
  
  private function isHoliday($day,$month,$year,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth){
    //check if day is past number of days for current month or before current month
    if($day < 1){
      $day = $numberOfDaysPreviousMonth - abs($day);
      $month = $month -1;
    }else if ($day > $numberOfDaysCurrentMonth){
      $day = abs($numberOfDaysCurrentMonth - $day);
      $month = $month +1; 
    }
    
    $isHoliday = false;
    $holidayCreator = new HolidayCreator();
    $holidayArray = $holidayCreator->dateArray($year,NULL); 
    //need padding strings for month and day for comparison
    $dayString = str_pad($day,2,"0",STR_PAD_LEFT);
    $monthString = str_pad($month,2,"0",STR_PAD_LEFT);
    
    foreach($holidayArray as $holiday){
        if("$holiday" == "$year-$monthString-$dayString"){
          $isHoliday = true;
        }
    }
    return $isHoliday;
  }
    /*
    Function: weekend
     
    Updates a weekend day in the database.
    
    Parameters:
    $day - day to be updated
    $month - month of day to be updated
    $year - year of day to be updated
    $oldDoctor - id of the previous doctor
    $newDoctor - id of the new doctor
    $numberOfDaysCurrentMonth - the number of days in the current month
    $numberOfDaysPreviousMonth - the number of days in the previous month
      
  */
  private function weekend($day,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth){
    //check if day is past number of days for current month or before current month
    if($day < 1){
      $day = $numberOfDaysPreviousMonth - abs($day);
      $month = $month -1;
    }else if ($day > $numberOfDaysCurrentMonth){
      $day = abs($numberOfDaysCurrentMonth - $day);
      $month = $month +1; 
    }

    include('login.php');
    $mysqli = new mysqli($host,$username,$password,$database);
    $query = "UPDATE Schedule SET `$day` = $newDoctor WHERE `Month` = $month AND `Year` = $year;";
    $query .= "UPDATE Doctor_History SET `Weekend`= `Weekend`-1 WHERE `Doctor_Id` = $oldDoctor;";
    $query .= "UPDATE Doctor_History SET `Weekend`=`Weekend`+1 WHERE `Doctor_Id` = $newDoctor;";
    $mysqli->multi_query($query);
    $mysqli->close();
  }
   /*
    Function: holiday
     
    Updates a holiday day in the database.
    
    Parameters:
    $day - day to be updated
    $month - month of day to be updated
    $year - year of day to be updated
    $oldDoctor - id of the previous doctor
    $newDoctor - id of the new doctor
    $numberOfDaysCurrentMonth - the number of days in the current month
    $numberOfDaysPreviousMonth - the number of days in the previous month
    
      
  */
  private function holiday($day,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth){
    //check if day is past number of days for current month or before current month
    if($day < 1){
      $day = $numberOfDaysPreviousMonth - abs($day);
      $month = $month -1;
    }else if ($day > $numberOfDaysCurrentMonth){
      $day = abs($numberOfDaysCurrentMonth - $day);
      $month = $month +1; 
    }   
    include('login.php');
    $mysqli = new mysqli($host,$username,$password,$database);
    //set the new doctor to the schedule
    $query = "UPDATE Schedule SET `$day` = $newDoctor WHERE `Month` = $month AND `Year` = $year;";
    $query .= "UPDATE Doctor_History SET `Holiday`= `Holiday`-1 WHERE `Doctor_Id` = $oldDoctor;";
    $query .= "UPDATE Doctor_History SET `Holiday`=`Holiday`+1 WHERE `Doctor_Id` = $newDoctor;";
    $mysqli->multi_query($query);
    $mysqli->close();
  }
  /*
    Function: weekday
     
    Updates a weekday day in the database.
    
    Parameters:
    $day - day to be updated
    $month - month of day to be updated
    $year - year of day to be updated
    $oldDoctor - id of the previous doctor
    $newDoctor - id of the new doctor
    $numberOfDaysCurrentMonth - the number of days in the current month
    $numberOfDaysPreviousMonth - the number of days in the previous month
      
  */
  private function weekday($day,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth){
    //check if day is past number of days for current month or before current month\
    if($day < 1){
      $day = $numberOfDaysPreviousMonth - abs($day);
      $month = $month -1;
    }else if ($day > $numberOfDaysCurrentMonth){
      $day = abs($numberOfDaysCurrentMonth - $day);
      $month = $month +1; 
    } 
    include('login.php');
    $mysqli = new mysqli($host,$username,$password,$database);
    //set the new doctor to the schedule
    $query = "UPDATE Schedule SET `$day` = $newDoctor WHERE `Month` = $month AND `Year` = $year;";
    $query .= "UPDATE Doctor_History SET `Weekday`= `Weekday`-1 WHERE `Doctor_Id` = $oldDoctor;";
    $query .= "UPDATE Doctor_History SET `Weekday`=`Weekday`+1 WHERE `Doctor_Id` = $newDoctor;";
    $mysqli->multi_query($query);
    $mysqli->close();
  }
  /*
    Function: update
     
    Parameters:
    $day - day to be updated
    $month - month of day to be updated
    $year - year of day to be updated
    $oldDoctor - id of the previous doctor
    $newDoctor - id of the new doctor
      
  */
  public function update($day,$month,$year,$oldDoctor,$newDoctor){
    $date = date("N",strtotime("$month/$day/$year"));
    
    $numberOfDaysCurrentMonth = date("t",mktime(0,0,0,$month,1,$year));
    $numberOfDaysPreviousMonth = date("t",mktime(0,0,0,$month-1,1,$year));
    
    switch($date){
      case 5:
        //updateFriday
        $this->weekday($day,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        //updateSaturday
        $this->weekend($day+1,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        //updateSunday
        $this->weekend($day+2,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        //check if monday is a holiday 
        if($this->isHoliday($day+3,$month,$year,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth)){
          $this->holiday($day+3,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        }
        break;
      case 6:
        //updateFriday
        $this->weekday($day-1,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        //updateSaturday
        $this->weekend($day,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        //updateSunday
        $this->weekend($day+1,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        //check if monday is a holiday 
        if($this->isHoliday($day+2,$month,$year,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth)){
          $this->holiday($day+2,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        }
        break;
      case 7:
        //updateFriday
        $this->weekday($day-2,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        //updateSaturday
        $this->weekend($day-1,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        //updateSunday
        $this->weekend($day,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        //check if monday is a holiday 
        if($this->isHoliday($day+1,$month,$year,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth)){
          $this->holiday($day+1,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        }
        break;
      case 1:
        if($this->isHoliday($day,$month,$year,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth)){ //monday holiday
          //updateFriday
          $this->weekday($day-3,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
          //updateSaturday
          $this->weekend($day-2,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
          //updateSunday
          $this->weekend($day-1,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
          //update monday(holiday)
          $this->holiday($day,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        }else{ 
          $this->weekday($day,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        }
        break;
      default:
        if($this->isHoliday($day,$month,$year,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth)){ 
          $this->holiday($day,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        }
        else{
          $this->weekday($day,$month,$year,$oldDoctor,$newDoctor,$numberOfDaysCurrentMonth,$numberOfDaysPreviousMonth);
        }
        break;
    }
  } 
}
if(isset($_REQUEST['newDoctor']) && isset($_REQUEST['oldDoctor']) && isset($_REQUEST['day'])&& isset($_REQUEST['month'])&& isset($_REQUEST['year'])){
  $newDoctor = $_REQUEST['newDoctor'];
  $oldDoctor = $_REQUEST['oldDoctor'];
  $month= $_REQUEST['month'];
  $year = $_REQUEST['year'];
  $day = $_REQUEST['day'];

  
  $updater = new updateSchedule;
  $updater->update($day,$month,$year,$oldDoctor,$newDoctor);
}
?>