<?php
	
	$year = date('Y');
	$month = 02;
	
	
	echo json_encode(array(
	
		array(
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-01"
			
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-02"
			
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-03"
			
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-10"
			
			
		),
		array(
			'title' => "DoctorB",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-04"
			
			'title' => "DoctorB",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-06"
			
			'title' => "DoctorB",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-11"
		
		),
		
		array(
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-05"
			
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-07"
			
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-08"
			
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-09"
		
		)

	));
?>