<?php
    include("../login.php");
	
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        // split the post data into doctorid and phone number
        list($doctorID, $phone) = split('[|]', $_POST["doc"]);
        // sanitize input
        $doctorID = filter_var($doctorID,FILTER_SANITIZE_NUMBER_INT);
        $phone = filter_var($phone,FILTER_SANITIZE_NUMBER_FLOAT);
        $startDate = filter_var($_POST["startDatePicker"],FILTER_SANITIZE_NUMBER_FLOAT);
        $endDate = filter_var($_POST["endDatePicker"],FILTER_SANITIZE_NUMBER_FLOAT);
    
        // setup connection to the database
        $link = mysql_connect($host,$username,$password);
        if (!$link) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db($database,$link);

        $date = $startDate;
        
        while(strtotime($date) < strtotime("+1 day", strtotime($endDate))) {
                mysql_query("insert into Requests(Doctor_ID,Type,Date) values (".$doctorID.",".(strcmp($_POST["requestType"],"Off")==0?"0":"1").",'".$date."')") or die(mysql_error());
                
                $dateAsDateFormat = strtotime("+1 day", strtotime($date));
                $date = date("Y-m-d", $dateAsDateFormat);
        }
        print("Request made successfully!");
        mysql_close($link);
    }
?>