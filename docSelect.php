<?php
        // include the login info
        include('login.php');
        // print the select, with it's attributes
        print("<select name = \"doc\" onchange=\"changeFunc()\" style=\"width: 150px;\">");
        
        // connect to the server, and the database
        $link = mysql_connect($host,$username,$password);
        if (!$link) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db($database,$link);
        
        //select all of the doctor information
        $result = mysql_query('SELECT * from Doctor');
        if (!$result) {
            die('Invalid query: ' . mysql_error());
        }
        // and create options within the select, for the doctors 
        while ($row = mysql_fetch_assoc($result)) {
            echo "<option id=\"docOpt".$row['Doctor_ID']."\" value=\"".$row['Doctor_ID']."\">".$row['Name']."</option>";
        }
        print("</select>");
        // free mysql
        mysql_free_result($result);
        mysql_close($link);
?>