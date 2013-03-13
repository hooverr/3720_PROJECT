<?php
if(isset($_REQUEST['month']) && isset($_REQUEST['year']) && isset($_REQUEST['schedule'])){
  $month = json_decode($_REQUEST['month']);
  $year = json_decode($_REQUEST['year']);
  //Remove slashes inserted by server
  $schedule = stripslashes(($_REQUEST['schedule']));
  $schedule = json_decode($schedule);
  
  $mysqli = new mysqli('localhost','robh_user','3720project','robh_3720');
  if ($mysqli->connect_error) {
      die('Connect Error (' . $mysqli->connect_errno . ') '
              . $mysqli->connect_error);
  }

  $insert = implode(",", $schedule);
  
  //Need to pad months that do not have 31 days with null
  if(count($schedule) == 31){
    $query = "INSERT INTO Schedule VALUES($month, $year,".$insert.")"; 
  }else if(count($schedule) == 30){
    $query = "INSERT INTO Schedule VALUES($month, $year,".$insert.",NULL)";
  }else if(count($schedule) == 29){
    $query = "INSERT INTO Schedule VALUES($month, $year,".$insert.",NULL,NULL)";
  }else{
    $query = "INSERT INTO Schedule VALUES($month, $year,".$insert.",NULL,NULL,NULL)";
  }
 
  $mysqli->query($query);
  $mysqli->close();
  }
?>