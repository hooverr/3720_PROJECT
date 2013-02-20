<?php
	
	$year = date('Y');
	$month = date('m');
	
	
	echo json_encode(array(
	
		array(
			'title' => "DoctorA",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-01",		
			
		),
		array(
			'title' => "DoctorB",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-04",
		
		),
		
		array(
			'title' => "DoctorC",
			'contact' => "xxx-xxx-xxxx",
			'start' => "$year-$month-05",
		
		)

	));
?>