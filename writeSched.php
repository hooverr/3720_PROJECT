<?php
if(isset($_REQUEST['month']) && isset($_REQUEST['year']) && isset($_REQUEST['schedule'])){
  $month = json_decode($_REQUEST['month']);
  $year = json_decode($_REQUEST['year']);
  $schedule = json_decode($_REQUEST['schedule']);

  $mysqli = new mysqli('localhost','robh_user','3720project','robh_3720');

  if ($mysqli->connect_error) {
      die('Connect Error (' . $mysqli->connect_errno . ') '
              . $mysqli->connect_error);
  }

  $insert = implode(",", $schedule);
  
  $query = "INSERT INTO Schedule VALUES ($month, $year, $insert)";
  $mysqli->query($query);
  $mysqli->close();
  }
?>