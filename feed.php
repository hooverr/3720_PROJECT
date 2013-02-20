<?php
	
	$year = date('Y'); //date format is full eg 2013
	$month = date('m'); //month format is numerical eg 02 for february
	
	
	//Each day is an array
	//ID is a unique identifier for the doctor
	echo json_encode(array(
	
		array(
			'id' => 1,
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-01",	
		),
		
		array(
		
			'id' => 1,
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-02",
		
		),
		
		array(
			'id' => 1,
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-03",
		
		)
		
		array(
			'id' => 1,
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-01",	
		),
		
		array(
		
			'id' => 2,
			'title' => "DoctorB",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-04",
		
		),
		
		array(
			'id' => 3,
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-05",
		
		)
		
		array(
		
			'id' => 1,
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-06",
		
		),
		
		array(
			'id' => 2,
			'title' => "DoctorB",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-07",
		
		)
		
		array(
			'id' => 3,
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-08",
		
		)
		array(
			'id' => 3,
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-09",
		
		)
		array(
			'id' => 3,
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-10",
		
		)
		
		

	));
?>