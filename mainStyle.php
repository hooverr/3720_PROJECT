<?php
	//Change type of file back to css.
    header("Content-type: text/css; charset: UTF-8");
	
	
	//Create 3 color variables 
	$color1= '#FFFFFF';
	$color2= '#7195A3';
	$color3= '#B06A3B';
	
	/*
	 FUTURE SETTINGS DEPENDING ON DOCTOR

		$mysqli = new mysqli('localhost','robh_user','','robh_3720');
		
		$query = "SELECT * FROM settings WHERE Doctor_ID = 1";
		$result = $mysqli->query($query);
		
		$row = $result->fetch_array(MYSQL_ASSOC);
		printf($row["Color1"]);
		
		$result->close();
		
		$mysqli->close();
	*/

?>
* { 
    margin: 0; 
    padding: 0;
} 

body{
	background-color: <?php echo $color1 ?>;
	font-family: "Times New Roman", Times, serif;
}

header{
	background-color: <?php echo $color2 ?>	;
	text-align: center;
	font-size: 2em;
}

#content {
	background-color: <?php echo $color1 ?>;
	width: 960px;
	margin:25px auto;
	padding-top:25px;

}

#navigation{
	text-align: center;
	background-color: 	<?php echo $color3 ?>;
	font-size: 1.5em;
}
#navigation ul{
	padding:5px 0 5px 0;
}
#navigation li{
	list-style:none;
	display:inline-block;
}

#navigation a{
	text-decoration: none;
	padding:0 5px 0 5px;
	color: #000000;
	line-height:100%;
}

#navigation a:hover{
	color: #FFFFFF;
}

#navigation #current a {
	color: #FFFFFF;
}

#calendar {
		width: 900px;
		margin: 0 auto;
}
			