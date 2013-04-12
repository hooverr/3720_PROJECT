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
        if($_POST["operation"] == "ClearTables")
        {
            mysql_query("delete from Settings;");
            mysql_query("delete from Schedule;");
            mysql_query("delete from Doctor_History;");
            mysql_query("delete from Requests;");
            mysql_query("delete from Doctor;");
            print("<b>Tables Cleared</b>");
        }
        elseif($_POST["operation"] == "ClearSchedule")
        {
            mysql_query("delete from Schedule;");
            print("<b>Schedule Cleared</b>");
        }
        elseif($_POST["operation"] == "FakeDocs")
        {
            mysql_query("INSERT INTO `Doctor` (`Doctor_ID`, `Name`, `Phone`, `Start_Date`, `End_Date`) VALUES(8, 'Rob H', '555-555-5555', '2013-03-11', NULL),(9, 'Dr Phil', '555-555-5555', '2013-03-13', NULL),(10, 'Tom Rickman', '', '2013-03-13', NULL),(11, 'Eric Bounty', '', '2013-03-13', NULL),(12, 'Sam Foreman', '', '2013-03-13', NULL),(13, 'Rich Lebenson', '', '2013-03-13', NULL),(14, 'Franklin Hampson', '', '2013-03-13', NULL),(15, 'Stence Fence', '', '2013-03-13', NULL),(16, 'Super Nova', '', '2013-03-13', NULL),(17, 'James James', '', '2013-03-13', NULL);");
            print("<b>Fake Doctors Added</b>");
            }
        mysql_close($link);
    }
?>