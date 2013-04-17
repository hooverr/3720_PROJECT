<?php
        include('login.php');
        print("<select name = \"doc\" onchange=\"changeFunc()\" style=\"width: 150px;\">");
        $link = mysql_connect($host,$username,$password);
        if (!$link) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db($database,$link);

        $result = mysql_query('SELECT * from Doctor');
        if (!$result) {
            die('Invalid query: ' . mysql_error());
        }

        while ($row = mysql_fetch_assoc($result)) {
            echo "<option id=\"docOpt".$row['Doctor_ID']."\" value=\"".$row['Doctor_ID']."\">".$row['Name']."</option>";
        }
        print("</select>");
        mysql_free_result($result);
        mysql_close($link);
?>