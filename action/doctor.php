<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        // include the login info
        include('../login.php');
        // get a connection to the server
        $link = mysql_connect($host,$username,$password);
        if (!$link) {
            die('Could not connect: ' . mysql_error());
        }
        // select the database
        mysql_select_db($database,$link);
        
        // here we sanitize variables
        $doctorID = filter_var($_POST["doc"],FILTER_SANITIZE_NUMBER_INT);
        $phone = filter_var($_POST["phoneNumber"],FILTER_SANITIZE_NUMBER_FLOAT);
        $date = filter_var($_POST["startDatePicker"],FILTER_SANITIZE_NUMBER_FLOAT);
        $endDate = filter_var($_POST["endDatePicker"],FILTER_SANITIZE_NUMBER_FLOAT);
        $firstName = filter_var($_POST["firstName"],FILTER_SANITIZE_STRING);
        $lastName = filter_var($_POST["lastName"],FILTER_SANITIZE_STRING);
        
        $weekends = filter_var($_POST["weekends"],FILTER_SANITIZE_NUMBER_INT);
        $weekdays = filter_var($_POST["weekdays"],FILTER_SANITIZE_NUMBER_INT);
        $holidays = filter_var($_POST["holidays"],FILTER_SANITIZE_NUMBER_INT);
        
        //this checks to see if there was a problem with one of the day variables, and fixes issues
        $weekends = (strlen($weekends)==0?0:$weekends);
        $weekdays = (strlen($weekdays)==0?0:$weekdays);
        $holidays = (strlen($holidays)==0?0:$holidays);
        
        //in the case of a addition
        if($_POST["func"] == "Add")
        {
            //insert the doctor info
            mysql_query("insert into Doctor(Name,Phone,Start_Date) values ('".$firstName." ".$lastName."','".$phone."','".$date."')") or die(mysql_error());
            // return the doctor id
            $doctorID = mysql_insert_id();
            // update their history, in case there was previous data set
            $qry = "update Doctor_History set `Previous_Weekday` = ".$weekdays.", `Previous_Weekend`=".$weekends.", `Previous_Holiday`=".$holidays." where Doctor_ID = ".$doctorID;
            mysql_query($qry) or die(mysql_error());
            // here we print the doctor id (to be used on the website), and the success message
            printf("%d-Doctor added successfully!",$doctorID);
        }
        //in the case of a removal
        else if($_POST["func"] == "Remove")
        {
            // update the doctor table, then display a success message
            mysql_query("update Doctor set End_Date = '".$endDate."' where Doctor_ID = ".$doctorID) or die(mysql_error());
            print("Doctor removed successfully!");
        }
        // in the case of an update
        else if($_POST["func"] == "Update")
        {
            // update the doctor data, and display a message
            $qry = "update Doctor set Name = '".$firstName." ".$lastName."', Phone='".$phone."', Start_Date='".$date."' where Doctor_ID = ".$doctorID;
            mysql_query($qry) or die(mysql_error());
            print("Doctor updated successfully!");
        }
        // if we have some kind of strange function, we just display a message
        else
        {
            print("Invalid Action, no data was changed!");
        }
        mysql_close($link);
    }
?>