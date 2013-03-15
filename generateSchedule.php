<?php
	$username = "robh_user";
	$password = "3720project";
	$link = mysql_connect("localhost",$username,$password);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	
	mysql_select_db("robh_3720",$link);
	
	$result = mysql_query("SELECT Doctor_ID, Total_Holiday, Total_Weekend, Total_Weekday FROM Doctor_History");
	
	$docHistory = array(array());
	$counter = 0;
	
	while($row = mysql_fetch_assoc($result)) {
		$docHistory[$counter][0] = $row['Doctor_ID'];
		$docHistory[$counter][1] = $row['Total_Holiday'];
		$docHistory[$counter][2] = $row['Total_Weekend'];
		$docHistory[$counter][3] = $row['Total_Weekday'];
		$counter++;
	}
	
	$result = mysql_query("SELECT Doctor_ID, Type, Date FROM Requests");
	
	$docRequests = array(array());
	$counter = 0;
	
	while($row = mysql_fetch_assoc($result)) {
		
		$tempDate = $row['Date'];
		
		if((date('Y', $tempDate) == date('Y')) || ((date('Y', $tempDate) == date('Y') + 1) && date('m') == 12)){
			if(date('m', $tempDate) - 1 == (date('m') % 12)) {
				$day = date('j', $tempDate);
				$docRequests[counter][0] = $row['DoctorID'];
				$docRequests[counter][1] = $row['Type'];
				$docRequests[counter][2] = $day;
			}
		}
		
		$counter++;
	}
	
	$month = 0;
	$year = 0;
	if(date('m') == 12){
		$month = 0;
		$year = date('Y') + 1;
	}
	else{
		$month = date('m');
		$year = date('Y');
	}
	
	$holidays = array();
?>
<script type="text/javascript">
	function prepareAlgorithm() {
		var docHistory = <?php echo json_encode($docHistory) ?>;
		
		var docRequests = <?php echo json_encode($docRequests) ?>;
		
		var month = parseInt(<?php echo json_encode($month) ?>);
		var year = parseInt(<?php echo json_encode($year) ?>);
		
		var holidays = <?php echo json_encode($holidays) ?>;
		
		var schedule = schedAlgorithm(docHistory, docRequests, month, year, holidays);
		
		//month in algorithm is 0-11 in database is 1-12
		month += 1;
		
		month = JSON.stringify(month);
		year = JSON.stringify(year);
		schedule = JSON.stringify(schedule);
		$.post('writeSchedule.php', { 'month': month, 'year': year, 'schedule': schedule });
		
	}
	
	/* 
		Function: schedAlgorithm

		Generates schedule of doctors for a given month

		Parameters:

			doctors - array parameter (multidimensional array containing doctorID, holidays worked, weekend days worked, weekday days worked)
			requests - array parameter (multidimensional array containing doctorID, request on/off, day)
			month - integer argument (0=Jan, 1=Feb, ..., 10=Nov, 11=Dec)
			year - integer argument (2013, 2014, etc) 
			holidays - array parameter (single dimensional int array - day of month holiday)
		
		Returns:

			an array containing doctorID for each day (array position + 1)

	*/
	function schedAlgorithm(doctors, requests, month, year, holidays) {
		
		var numDays; // variable: number of days in the month to be scheduled

		// switch statement to assign number of days to schedule
		switch (month) {
			case 0: //January
			case 2: //March
			case 4: //May
			case 6: //July
			case 7: //August
			case 9: //October
			case 11: //December
				numDays = 31;
				break;
			case 3: //April
			case 5: //June
			case 8: //September
			case 10: //November
				numDays = 30;
				break;
			case 1: //February
				// if it is February, determine if the current year is a leap year
				if ((year % 4 == 0) && ((year % 100 != 0) || (year % 400 == 0))) {
					numDays = 29;
				} else {
					numDays = 28;
				}
				break;

		}

		// array: will contain the schedule information
		var sched = new Array();

		// array: will contain the sorted doctor array
		var docSorted = new Array();

		// array: will contain the sorted requests array
		var reqSorted = new Array();

		// boolean: day is holiday
		var holiday = false;

		// boolean: weekend day is holiday
		var wEndHoliday = false;

		// integer: holiday array incrementer
		var x = 0;

		// integer: variable to store the previously assigned doctor ID
		var prevDocID;

		// variable: contain the doctorID to add to the schedule
		var docPosition;

		// variable: to contain an integer representation of day of week (0=Sun, 1=Mon, ...)
		var day
		
		// loop through each day of the month to generate the schedule
		for (var i = 0; i < numDays; i++) {

			if(holidays.length != 0) {
				if (holidays[x] == i + 1) {
					holiday = true;
					x++;
				} else {
					holiday = false;
				}
			}

			// variable: for the day of the week based on month, year, and current iteration through the loop
			day = new Date(year, month, i + 1).getDay();

			switch (day) {

				// weekend cases
				case 0: //Sunday
				case 6: //Saturday
					if (holiday)
						wEndHoliday = true;


					if (i != 0) {
						// assign doctor to sched array
						sched[i] = prevDocID;
						//increment doctors weekend days worked
						doctors[docPosition][2] += 1;
					} else {
						docSorted = sort(doctors, 2).slice();
						reqSorted = sort(requests, 2).slice();

						docPosition = findDoctor(2, docSorted, reqSorted);

						sched[i] = docSorted[docPosition][0];
						docSorted[docPosition][2] += 1;
						doctors = docSorted.slice();
					}

					break;

					// weekday cases
				case 1: //Monday
					if (holiday || wEndHoliday) {
						// reset weekend holiday boolean to false
						wEndHoliday = false;
						sched[i] = doctors[docPosition][0];
						doctors[docPosition][1] += 1;
						break;
					} else {
						docSorted = sort(doctors, 3).slice();
						reqSorted = sort(requests, 2).slice();

						docPosition = findDoctor(2, i, docSorted, reqSorted);

						sched[i] = docSorted[docPosition][0];
						docSorted[docPosition][3] += 1;
						doctors = docSorted.slice();
						break;
					}
				case 2: //Tuesday
				case 3: //Wednesday
				case 4: //Thurday
					docSorted = sort(doctors, 3).slice();
					reqSorted = sort(requests, 2).slice();

					docPosition = findDoctor(3, i, docSorted, reqSorted);

					sched[i] = docSorted[docPosition][0];
					if (holiday)
						docSorted[docPosition][1] += 1;
					else
						docSorted[docPosition][3] += 1;

					doctors = docSorted.slice();
					break;
				case 5: //Friday
					docSorted = sort(doctors, 3).slice();
					reqSorted = sort(requests, 2).slice();

					docPosition = findDoctor(3, i, docSorted, reqSorted);

					sched[i] = docSorted[docPosition][0];
					prevDocID = docSorted[docPosition][0];
					if (holiday)
						docSorted[docPosition][1] += 1;
					else
						docSorted[docPosition][3] += 1;

					doctors = docSorted.slice();
					break;
			}
		}
		
		return sched;
	}

	/* 
		Function: sort

		sort the given multidimensional array in ascending order based on the given parameter to sort by

		Parameters:

			arrayToSort - array to be sorted
			sortBy - the position in the multidimensional array to sort by
		
		Returns:

			the sorted array

	*/
	function sort(arrayToSort, sortBy) {
		// sort array based on sortBy
		arrayToSort.sort(function (a, b) {
			if (a[sortBy] == b[sortBy]) return 0;
			return a[sortBy] < b[sortBy] ? -1 : 1;
		});

		return arrayToSort;
	}

	/* 
		Function: findDoctor

		Finds the doctor to be scheduled for the given day

		Parameters:

			dayType - type of day (holiday = 1, weekend = 2, weekday = 3)
			date - the day of the month (integer)
			doctors - the array of doctors (already sorted by the days worked of given type)
			requests - the array of requests (already sorted by the date of the request)
		
		Returns:

			an integer index in the doctor array of the doctor to be scheduled

	*/
	function findDoctor(dayType, date, doctors, requests) {
		//temporary variable to store docID
		var docID;

		if (requests.length != 0) {
			var r;
			var j;

			// for loop to iterate through the request array
			for (j = 0; j < requests.length; j++) {
				// if request on
				if (requests[j][1] == 1) {
					// if request is for current day
					if (requests[j][2] == date) {
						for (r = 0; r < doctors.length; r++) {
							if (doctors[r][0] == requests[j][0]) {
								//return position of doctor id matching the doctor id for the request on
								return r;
							}
						}
					}
				}
					// if request off
				else if (requests[j][1] == 0) {

					// if request is for current day
					if (requests[j][2] == date) {
						for (r = 0; r < doctors.length; r++) {
							if (doctors[r][0] != requests[j][0]) {
								//return position of doctor id matching the doctor id for the request on
								return r;
							}
						}
					}
				}
			}
		}
		var defaultValue = 0;
		return defaultValue;
	}
</script>
