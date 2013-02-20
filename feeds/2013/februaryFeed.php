<?php
	
	$year = date('Y'); //date format is full eg 2013
	$month = date('m'); //month format is numerical eg 02 for february
	
	
	//Each day is an array
	//ID is unique to the day 020113 mdy
	echo json_encode(array(
	
		array(
			'id' => 020113,
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-01",	
			'backgroundColor' => "#0000FF",
		),
		
		array(
			'id' => 020213,
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-02",
			'backgroundColor' => "#0000FF",
		
		),
		
		array(
			'id' => 020313,
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-03",
			'backgroundColor' => "#0000FF",
		
		),
		
		array(
		
			'id' => 020413,
			'title' => "DoctorB",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-04",
			'backgroundColor' => "#5F9EA0",
		
		),
		
		array(
			'id' => 020513,
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-05",
			'backgroundColor' => "#0000FF",
		
		),
		
		array(
		
			'id' => 020613,
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-06",
			'backgroundColor' => "#0000FF",
		
		),
		
		array(
			'id' => 020713,
			'title' => "DoctorB",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-07",
			'backgroundColor' => "#5F9EA0",
		
		),
		
		array(
			'id' => 020813,
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-08",
			'backgroundColor' => "#DEB887",
		
		),
		array(
			'id' => 020913,
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-09",
			'backgroundColor' => "#DEB887",
		
		),
		array(
			'id' => 021013,
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-10",
			'backgroundColor' => "#DEB887",
		
		),
		
		

	));
?>