<?php
	
	$year = date('Y'); //date format is full eg 2013
	$month = date('m'); //month format is numerical eg 02 for february
	
	
	//Each day is an array
	//ID is unique to the day 02-01-13 mdy
	echo json_encode(array(
	
		array(
			'id' => "02-01-13",
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-01",	
			'backgroundColor' => "#0000FF",
		),
		
		array(
			'id' => "02-02-13",
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-02",
			'backgroundColor' => "#0000FF",
		
		),
		
		array(
			'id' => "02-03-13",
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-03",
			'backgroundColor' => "#0000FF",
		
		),
		
		array(
		
			'id' => "02-04-13",
			'title' => "DoctorB",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-04",
			'backgroundColor' => "#5F9EA0",
		
		),
		
		array(
			'id' => "02-05-13",
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-05",
			'backgroundColor' => "#0000FF",
		
		),
		
		array(
		
			'id' => "02-06-13",
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-06",
			'backgroundColor' => "#0000FF",
		
		),
		
		array(
			'id' => "02-07-13",
			'title' => "DoctorB",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-07",
			'backgroundColor' => "#5F9EA0",
		
		),
		
		array(
			'id' => "02-08-13",
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-08",
			'backgroundColor' => "#DEB887",
		
		),
		array(
			'id' => "02-09-13",
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-09",
			'backgroundColor' => "#DEB887",
		
		),
		array(
			'id' => "02-10-13",
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-10",
			'backgroundColor' => "#DEB887",
		
		),
		
		

	));
?>