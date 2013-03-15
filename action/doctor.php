<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        list($doctorID, $phone) = split('[|]', $_POST["doc"]);
        $username = "robh_user";
        $password = "3720project";
        $link = mysql_connect("localhost",$username,$password);
        if (!$link) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db("robh_3720",$link);
        if($_POST["func"] == "Add")
        {
            mysql_query("insert into Doctor(Name,Phone,Start_Date) values ('".$_POST["firstName"]." ".$_POST["lastName"]."','".$_POST["phoneNumber"]."','".$_POST["startDatePicker"]."')") or die(mysql_error());
            print("<b>Doctor added successfully!</b>");
        }
        else if($_POST["func"] == "Remove")
        {
            mysql_query("update Doctor set End_Date = '".$_POST["endDatePicker"]."' where Doctor_ID = ".$doctorID) or die(mysql_error());
            print("<b>Doctor removed successfully!</b>");
        }
        else
        {
            mysql_query("update Doctor set Name = '".$_POST["firstName"]." ".$_POST["lastName"]."', Phone='".$_POST["phoneNumber"]."', Start_Date='".$_POST["startDatePicker"]."' where Doctor_ID = ".$doctorID) or die(mysql_error());
            print("<b>Doctor updated successfully!</b>");
        }
        mysql_close($link);
    }
?>