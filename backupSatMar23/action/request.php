<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        list($doctorID, $phone) = split('[|]', $_POST["doc"]);
        $doctorID = filter_var($doctorID,FILTER_SANITIZE_NUMBER_INT);
        $phone = filter_var($phone,FILTER_SANITIZE_NUMBER_FLOAT);
        $date = filter_var($_POST["startDatePicker"],FILTER_SANITIZE_NUMBER_FLOAT);
        $username = "robh_user";
        $password = "3720project";
        $link = mysql_connect("localhost",$username,$password);
        if (!$link) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db("robh_3720",$link);

        mysql_query("insert into Requests(Doctor_ID,Type,Date) values (".$doctorID.",".(strcmp($_POST["requestType"],"Off")==0?"0":"1").",'".$date."')") or die(mysql_error());

        print("<b>Request made successfully!</b>");
        mysql_close($link);
    }
?>