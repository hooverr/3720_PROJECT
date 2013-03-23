<?php
if(isset($_REQUEST['newDoctor']) && isset($_REQUEST['oldDoctor']) && isset($_REQUEST['day'])&& isset($_REQUEST['month'])&& isset($_REQUEST['year'])){
  $newDoctor = $_REQUEST['newDoctor'];
  $oldDoctor = $_REQUEST['oldDoctor'];
  $month= $_REQUEST['month'];
  $year = $_REQUEST['year'];
  $day = $_REQUEST['day'];
  
  //figure out what kind of day it is
  //@todo figure out holidays
  $date = date("D",strtotime("$month/$day/$year"));
  
  if($date = 'Sat' || $date = 'Sun'){
    $type = "weekend";
  }else{
    $type = "weekday";
  }
  
  $mysqli = new mysqli('localhost','robh_user','3720project','robh_3720');
  $query = "UPDATE Schedule SET `$day` = $newDoctor WHERE `Month` = $month AND `Year` = $year";
	$mysqli->query($query);
  $mysqli->close();
  
  $fp = fopen('data.txt', 'w');
  fwrite($fp,"$query");
  
}
?>