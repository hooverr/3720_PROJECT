<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
       
        $username = "robh_user";
        $password = "3720project";
        $link = mysql_connect("localhost",$username,$password);
        if (!$link) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db("robh_3720",$link);
        
        
        $doctorID = filter_var($_POST["doc"],FILTER_SANITIZE_NUMBER_INT);
        $phone = filter_var($_POST["phoneNumber"],FILTER_SANITIZE_NUMBER_FLOAT);
        $date = filter_var($_POST["startDatePicker"],FILTER_SANITIZE_NUMBER_FLOAT);
        $endDate = filter_var($_POST["endDatePicker"],FILTER_SANITIZE_NUMBER_FLOAT);
        $firstName = filter_var($_POST["firstName"],FILTER_SANITIZE_STRING);
        $lastName = filter_var($_POST["lastName"],FILTER_SANITIZE_STRING);
        
        if($_POST["func"] == "Add")
        {
            mysql_query("insert into Doctor(Name,Phone,Start_Date) values ('".$firstName." ".$lastName."','".$phone."','".$date."')") or die(mysql_error());
            print("<b>Doctor added successfully!</b>");
        }
        else if($_POST["func"] == "Remove")
        {
            mysql_query("update Doctor set End_Date = '".$endDate."' where Doctor_ID = ".$doctorID) or die(mysql_error());
            print("<b>Doctor removed successfully!</b>");
        }
        else if($_POST["func"] == "Update")
        {
            $qry = "update Doctor set Name = '".$firstName." ".$lastName."', Phone='".$phone."', Start_Date='".$date."' where Doctor_ID = ".$doctorID;
            mysql_query($qry) or die(mysql_error());
            print("<b>Doctor updated successfully!</b>");
        }
        else
        {
            print("<b>Invalid Action, no data was changed!</b>");
        }
        mysql_close($link);
    }
?>