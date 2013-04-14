<?php
include_once('login.php');

if(isset($_REQUEST['month']) && isset($_REQUEST['year']) && isset($_REQUEST['schedule'])){
  $month = json_decode($_REQUEST['month']);
  $year = json_decode($_REQUEST['year']);
  //Remove slashes inserted by server
  $schedule = stripslashes(($_REQUEST['schedule']));
  $schedule = json_decode($schedule);
  //Remove slashes inserted by server
  $doctors = stripslashes(($_REQUEST['doctors']));
  $doctors = json_decode($doctors);
  
  $mysqli = new mysqli($host,$username,$password,$database );
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
  
  foreach($doctors as $doctor) {
	$query = "UPDATE Doctor_History SET Holiday=$doctor[6], Weekend=$doctor[7], Weekday=$doctor[8] WHERE Doctor_ID=$doctor[0]";
	$mysqli->query($query);
  }
  
  $mysqli->close();
  }
?>