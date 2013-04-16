<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
       
        include('../login.php');
        $link = mysql_connect($host,$username,$password);
        if (!$link) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db($database,$link);
        
        
        $doctorID = filter_var($_POST["doc"],FILTER_SANITIZE_NUMBER_INT);
        $phone = filter_var($_POST["phoneNumber"],FILTER_SANITIZE_NUMBER_FLOAT);
        $date = filter_var($_POST["startDatePicker"],FILTER_SANITIZE_NUMBER_FLOAT);
        $endDate = filter_var($_POST["endDatePicker"],FILTER_SANITIZE_NUMBER_FLOAT);
        $firstName = filter_var($_POST["firstName"],FILTER_SANITIZE_STRING);
        $lastName = filter_var($_POST["lastName"],FILTER_SANITIZE_STRING);
        
        $weekends = filter_var($_POST["weekends"],FILTER_SANITIZE_NUMBER_INT);
        $weekdays = filter_var($_POST["weekdays"],FILTER_SANITIZE_NUMBER_INT);
        $holidays = filter_var($_POST["holidays"],FILTER_SANITIZE_NUMBER_INT);
        
        $weekends = (strlen($weekends)==0?0:$weekends);
        $weekdays = (strlen($weekdays)==0?0:$weekdays);
        $holidays = (strlen($holidays)==0?0:$holidays);
        
        if($_POST["func"] == "Add")
        {
            mysql_query("insert into Doctor(Name,Phone,Start_Date) values ('".$firstName." ".$lastName."','".$phone."','".$date."')") or die(mysql_error());
            $doctorID = mysql_insert_id();
            $qry = "update Doctor_History set `Previous_Weekday` = ".$weekdays.", `Previous_Weekend`=".$weekends.", `Previous_Holiday`=".$holidays." where Doctor_ID = ".$doctorID;
            mysql_query($qry) or die(mysql_error());
            print("Doctor added successfully!");
        }
        else if($_POST["func"] == "Remove")
        {
            mysql_query("update Doctor set End_Date = '".$endDate."' where Doctor_ID = ".$doctorID) or die(mysql_error());
            print("Doctor removed successfully!");
        }
        else if($_POST["func"] == "Update")
        {
            $qry = "update Doctor set Name = '".$firstName." ".$lastName."', Phone='".$phone."', Start_Date='".$date."' where Doctor_ID = ".$doctorID;
            mysql_query($qry) or die(mysql_error());
            print("Doctor updated successfully!");
        }
        else
        {
            print("Invalid Action, no data was changed!");
        }
        mysql_close($link);
    }
?>