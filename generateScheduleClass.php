<?php
class GenerateSchedule{
  public function getDoctorHistory(){
    $mysqli = new mysqli('localhost','robh_user','3720project','robh_3720');
    $query = "SELECT d.Doctor_ID, h.Total_Holiday, h.Total_Weekend, h.Total_Weekday, d.Start_Date, d.End_Date " . 
    "FROM Doctor_History as h, Doctor as d " . 
    "WHERE d.Doctor_ID = h.Doctor_ID";
    $docHistory = array(array());
    $counter = 0;
    if($result = $mysqli->query($query)){
      while($row= $result->fetch_assoc()) {
        $docHistory[$counter][0] = $row['Doctor_ID'];
        $docHistory[$counter][1] = $row['Total_Holiday'];
        $docHistory[$counter][2] = $row['Total_Weekend'];
        $docHistory[$counter][3] = $row['Total_Weekday'];
        $docHistory[$counter][4] = $row['Start_Date'];
        $docHistory[$counter][5] = $row['End_Date'];
        $counter++;
      }
      $result->free;
    }
    $mysqli->close();
    return $docHistory;
  }  
  
  public function getDoctorRequests(){
    $mysqli = new mysqli('localhost','robh_user','3720project','robh_3720');
    $query = "SELECT Doctor_ID, Type, Date FROM Requests";
    $docRequests = array(array());
    $counter = 0;
    if($result = $mysqli->query($query)){
      while($row= $result->fetch_assoc()) {
        $day = date('Y-m-d', strtotime($row['Date']));
        $docRequests[$counter][0] = $row['Doctor_ID'];
        $docRequests[$counter][1] = $row['Type'];
        $docRequests[$counter][2] = $day;
        $counter++;  
      }
      $result->free; 
    }
    $mysqli->close();
    return $docRequests;
  }
  public function getSchedules(){
    $mysqli = new mysqli('localhost','robh_user','3720project','robh_3720');
    $yearStart = date('Y') - 1;
    $yearEnd = date('Y') + 1;
    $query = 'SELECT * FROM Schedule WHERE Year BETWEEN '.$yearStart.' AND '.$yearEnd.'';
    $priorSchedules = array(array());
    $counter = 0;
    if($result = $mysqli->query($query)){
      while($row= $result->fetch_assoc()) {
        $priorSchedules[$counter][0] = $row['Year'];
        $priorSchedules[$counter][1] = $row['Month'];
        $priorSchedules[$counter][2] = $row['28'];
        $priorSchedules[$counter][3] = $row['29'];
        $priorSchedules[$counter][4] = $row['30'];
        $priorSchedules[$counter][5] = $row['31'];
        $counter++;
      }
      $result->free; 
    }
    $mysqli->close();
    return $priorSchedules;
  }
  public function getHolidays(){
    $holidays = array();
    return $holidays;
  }
}
?>