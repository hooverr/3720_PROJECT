<?php
include_once("holidayCreator.php");
class updateSchedule{
  
  
  private function isHoliday($day,$month,$year){
    $holiday = false;
    $holidayCreator = new HolidayCreator();
    $holidayArray = $holidayCreator->dateArray($year,NULL); 
    //need padding strings for month and day for comparison
    $dayString = str_pad($day,2,"0",STR_PAD_LEFT);
    $monthString = str_pad($month,2,"0",STR_PAD_LEFT);
    
    foreach($holidayArray as $holiday){
        //A holiday that falls on a weekend, increments weekend not holiday. Holiday incremented on the monday.
        if(("$holiday" == "$year-$monthString-$dayString") && ($type != "Weekend")){
          $holiday = true;
        }
    }
    return $holiday;
  }
  
  private function weekend($day,$month,$year,$oldDoctor,$newDoctor){
     $mysqli = new mysqli('localhost','robh_user','3720project','robh_3720');
    //set the new doctor to the schedule
    $query = "UPDATE Schedule SET `$day` = $newDoctor WHERE `Month` = $month AND `Year` = $year";
    $mysqli->query($query);
    //decrement old doctors history
    $query = "UPDATE Doctor_History SET `Weekend`= `Weekend`-1 WHERE `Doctor_Id` = $oldDoctor";
    $mysqli->query($query);
    //increment new doctors history
    $query = "UPDATE Doctor_History SET `Weekend`=`Weekend`+1 WHERE `Doctor_Id` = $newDoctor";
    $mysqli->query($query);
    $mysqli->close();
  }
  
  private function holiday($day,$month,$year,$oldDoctor,$newDoctor){
     $mysqli = new mysqli('localhost','robh_user','3720project','robh_3720');
    //set the new doctor to the schedule
    $query = "UPDATE Schedule SET `$day` = $newDoctor WHERE `Month` = $month AND `Year` = $year";
    $mysqli->query($query);
    //decrement old doctors history
    $query = "UPDATE Doctor_History SET `Holiday`= `Holiday`-1 WHERE `Doctor_Id` = $oldDoctor";
    $mysqli->query($query);
    //increment new doctors history
    $query = "UPDATE Doctor_History SET `Holiday`=`Holiday`+1 WHERE `Doctor_Id` = $newDoctor";
    $mysqli->query($query);
    $mysqli->close();
  }
  
  private function weekday($day,$month,$year,$oldDoctor,$newDoctor){
    $mysqli = new mysqli('localhost','robh_user','3720project','robh_3720');
    //set the new doctor to the schedule
    $query = "UPDATE Schedule SET `$day` = $newDoctor WHERE `Month` = $month AND `Year` = $year";
    $mysqli->query($query);
    //decrement old doctors history
    $query = "UPDATE Doctor_History SET `Weekday`= `Weekday`-1 WHERE `Doctor_Id` = $oldDoctor";
    $mysqli->query($query);
    //increment new doctors history
    $query = "UPDATE Doctor_History SET `Weekday`=`Weekday`+1 WHERE `Doctor_Id` = $newDoctor";
    $mysqli->query($query);
    $mysqli->close();
  }
  
  public function update($day,$month,$year,$oldDoctor,$newDoctor){
    $date = date("D",strtotime("$month/$day/$year"));
    if("$date" == "Fri"){ 
    //updateFriday
      $this->weekday($day,$month,$year,$oldDoctor,$newDoctor);
    //updateSaturday
      $this->weekend($day+1,$month,$year,$oldDoctor,$newDoctor);
    //updateSunday
      $this->weekend($day+2,$month,$year,$oldDoctor,$newDoctor);     
    }else if("$date" == "Sat"){ 
    //updateFriday
      $this->weekday($day-1,$month,$year,$oldDoctor,$newDoctor);
    //updateSaturday
      $this->weekend($day,$month,$year,$oldDoctor,$newDoctor);
    //updateSunday
      $this->weekend($day+1,$month,$year,$oldDoctor,$newDoctor);
      //check if either saturday or sunday is a weekend
      if($this->isHoliday($day,$month,$year) ||$this->isHoliday($day+1,$month,$year) ){
        $this->holiday($day+2,$month,$year,$oldDoctor,$newDoctor);
      }
    }else if("$date" == "Sun"){ 
      //updateFriday
      $this->weekday($day-2,$month,$year,$oldDoctor,$newDoctor);
      //updateSaturday
      $this->weekend($day-1,$month,$year,$oldDoctor,$newDoctor);
      //updateSunday
      $this->weekend($day,$month,$year,$oldDoctor,$newDoctor);
      //check if either saturday or sunday is a weekend
      if($this->isHoliday($day,$month,$year) ||$this->isHoliday($day-1,$month,$year) ){
        $this->holiday($day+1,$month,$year,$oldDoctor,$newDoctor);
      }
    }else{
      $this->weekday($day,$month,$year,$oldDoctor,$newDoctor);   
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