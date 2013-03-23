<?php
if(isset($_REQUEST['newDoctor']) && isset($_REQUEST['oldDoctor']) && isset($_REQUEST['day'])&& isset($_REQUEST['month'])&& isset($_REQUEST['year'])){
  $newDoctor = $_REQUEST['newDoctor'];
  $oldDoctor = $_REQUEST['oldDoctor'];
  $month= $_REQUEST['month'];
  $year = $_REQUEST['year'];
  $day = $_REQUEST['day'];
  

  
  //figure out what kind of day it is
  $date = date("D",strtotime("$month/$day/$year"));
  $fp = fopen('log.txt',"w");
  if("$date" == "Sat" || "$date" == "Sun"){
    $type = "Weekend";
  }else{
    $type = "Weekday";
  }
  
  
  include("holidayCreator.php");
  $holidayCreator = new HolidayCreator();
  $holidayArray = $holidayCreator->dateArray($year,NULL); 
  //need padding strings for month and day for comparison
  $dayString = str_pad($day,2,"0",STR_PAD_LEFT);
  $monthString = str_pad($month,2,"0",STR_PAD_LEFT);
  
  foreach($holidayArray as $holiday){
      //A holiday that falls on a weekend, increments weekend not holiday. Holiday incremented on the monday.
      if(("$holiday" == "$year-$monthString-$dayString") && ($type != "Weekend")){
        $type = "Holiday";
      }
  }
  fwrite($fp,$type);
  $mysqli = new mysqli('localhost','robh_user','3720project','robh_3720');
  //set the new doctor to the schedule
  $query = "UPDATE Schedule SET `$day` = $newDoctor WHERE `Month` = $month AND `Year` = $year";
	$mysqli->query($query);
  //decrement old doctors history
  $query = "UPDATE Doctor_History SET `$type`= `$type`-1 WHERE `Doctor_Id` = $oldDoctor";

  $mysqli->query($query);
  //increment new doctors history
  $query = "UPDATE Doctor_History SET `$type`=`$type`+1 WHERE `Doctor_Id` = $newDoctor";
  $mysqli->query($query);
  $mysqli->close();
  
}
?>