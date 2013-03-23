<?php

	// variable: username to use for MySQL connection
	$username = "robh_user";
	// variable: password to use for MySQL connection
	$password = "3720project";
	
	// connection to MySQL
	$link = mysql_connect("localhost",$username,$password);
	
	// checks if connection to MySQL worked
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}

	// selection of DB to use
	mysql_select_db("robh_3720",$link);

	// variable: result of the query to retrieve doctor information to use in algorithm
	$result = mysql_query("SELECT d.Doctor_ID, h.Total_Holiday, h.Total_Weekend, h.Total_Weekday, h.Holiday, h.Weekend, h.Weekday, d.Start_Date, d.End_Date " . 
	"FROM Doctor_History as h, Doctor as d " . 
	"WHERE d.Doctor_ID = h.Doctor_ID");

	// check if the query was successful
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}

	// two-dimensional array to contain doctor information for use in the scheduling algorithm
	$docHistory = array(array());
	// variable: integer counter to move through the docHistory array when inserting doctor information
	$counter = 0;

	while($row = mysql_fetch_assoc($result)) {
		// assign doctor ID to docHistory array
		$docHistory[$counter][0] = $row['Doctor_ID'];
		// assign doctor's Total (real + theoretical) Holiday information to docHistory array
		$docHistory[$counter][1] = $row['Total_Holiday'];
		// assign doctor's Total (real + theoretical) Weekend information to docHistory array
		$docHistory[$counter][2] = $row['Total_Weekend'];
		// assign doctor's Total (real + theoretical) Weekday information to docHistory array
		$docHistory[$counter][3] = $row['Total_Weekday'];
		// assign doctor's start date information to docHistory array
		$docHistory[$counter][4] = $row['Start_Date'];
		// assign doctor's end date information to docHistory array
		$docHistory[$counter][5] = $row['End_Date'];
		// assign doctor's real Holiday information to docHistory array
		$docHistory[$counter][6] = $row['Holiday'];
		// assign doctor's real Weekend information to docHistory array
		$docHistory[$counter][7] = $row['Weekend'];
		// assign doctor's real Weekday information to docHistory array
		$docHistory[$counter][8] = $row['Weekday'];
		// increment counter to use the next position in the docHistory two-dimensional array
		$counter++;
	}

	// variable: result of the query to retrieve request information to use in algorithm
	$result = mysql_query("SELECT Doctor_ID, Type, Date FROM Requests");

	// check if the query was successful
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}

	// two-dimensional array to contain the on/off requests
	$docRequests = array(array());
	// variable: integer counter to move through the docRequests array
	$counter = 0;

	while($row = mysql_fetch_assoc($result)) {
		// variable: date object created from request date in the DB result
		$day = date('Y-m-d', strtotime($row['Date']));
		// assign doctor id to docRequests array
		$docRequests[$counter][0] = $row['Doctor_ID'];
		// assign type of request (0=off, 1=on) to docRequests array
		$docRequests[$counter][1] = $row['Type'];
		// assign date of request to docRequests array
		$docRequests[$counter][2] = $day;
		// increment counter to use next position in the docRequests two-dimensional array
		$counter++;
	}

	// variable: year prior to current year - to retrieve previous schedules
	$yearStart = date('Y') - 1;
	// variable: current year - to retrieve previous schedules
	$yearEnd = date('Y') + 1;

	// variable: result of the query to retrieve previous schedule information to use in algorithm
	$result = mysql_query('SELECT * FROM Schedule WHERE Year BETWEEN '.$yearStart.' AND '.$yearEnd.'');

	// check if the query was successful
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}

	// two-dimensional array to contain the prior schedules
	$priorSchedules = array(array());
	// variable: integer counter to move through the priorSchedules array
	$counter = 0;

	while($row = mysql_fetch_assoc($result)) {
		// assign prior schedule year to priorSchedules array
		$priorSchedules[$counter][0] = $row['Year'];
		// assign prior schedule month to priorSchedules array
		$priorSchedules[$counter][1] = $row['Month'];
		// assign prior schedule docID at the 28th of the month to priorSchedules array
		$priorSchedules[$counter][2] = $row['28'];
		// assign prior schedule docID at the 29th of the month to priorSchedules array
		$priorSchedules[$counter][3] = $row['29'];
		// assign prior schedule docID at the 30th of the month to priorSchedules array
		$priorSchedules[$counter][4] = $row['30'];
		// assign prior schedule docID at the 31th of the month to priorSchedules array
		$priorSchedules[$counter][5] = $row['31'];
		// increment counter to use next position in the priorSchedules two-dimensional array
		$counter++;
	}

	// retrieve holiday information for use in the scheduling algorithm
	require_once('holidayCreator.php');
	$holidayCreator = new HolidayCreator();
	$year = date("Y");
	
	// save array returned by dateArray function containing holidays for current and next year
	$holidays = $holidayCreator->dateArray($year, $year+1);
?>
<script type="text/javascript">
	function prepareAlgorithm(inputMonth, inputYear) {
	
		// variable: array docHistory retrieved from PHP
		var docHistory = <?php echo json_encode($docHistory) ?>;
		// variable: array docRequests retrieved from PHP
		var docRequests = <?php echo json_encode($docRequests) ?>;
		// variable: array prevSched retrieved from PHP
		var prevSched = <?php echo json_encode($priorSchedules) ?>;
		// variable: array holidays retrieved from PHP
		var holidays = <?php echo json_encode($holidays) ?>;
		
		// multi-value return from schedAlgorithm function - contains schedule array and doctors (info) array
		var generateReturn = schedAlgorithm(docHistory, docRequests, inputMonth, inputYear, holidays, prevSched);
		
		//retrieve the schedule array from the multi-value return of schedAlgorithm(...)
		var schedule = generateReturn.sched;
		//retrieve the doctor information array from the multi-value return of schedAlgorithm(...)
		var doctors = generateReturn.doctors;
		
		//month in algorithm is 0-11 and in database is 1-12
		inputMonth += 1;
		
		//prepare month and year variables, as well as schedule and doctor arrays to be posted to writeSchedule.php
		inputMonth = JSON.stringify(inputMonth);
		year = JSON.stringify(inputYear);
		schedule = JSON.stringify(schedule);
		doctors = JSON.stringify(doctors);
		
		//post information to writeSchedule.php to be written to the database
		$.post('writeSchedule.php', { 'month': inputMonth, 'year': inputYear, 'schedule': schedule, 'doctors': doctors })
		.done(function(){
		$('#calendar').fullCalendar( 'refetchEvents' );});
	}
	
	/* 
		Function: schedAlgorithm

		Generates schedule of doctors for a given month

		Parameters:

			doctors - array parameter (multidimensional array containing doctorID, total holidays worked, total weekend days worked, total weekday days worked, start date, end date, holiday days worked, weekend days worked, weekdays worked)
			requests - array parameter (multidimensional array containing doctorID, request on/off, day)
			month - integer argument (0=Jan, 1=Feb, ..., 10=Nov, 11=Dec)
			year - integer argument (2013, 2014, etc) 
			holidays - array parameter (single dimensional int array - day of month holiday)
			prevSched - array containing the previous schedules to determine previous doctor on a month starting on a weekend
		
		Returns:

			an array containing doctorID for each day (array position + 1)

	*/
	function schedAlgorithm(doctors, requests, month, year, holidaysForYear, prevSched) {
		
		var holidays = new Array();
		
		//variable: date variable to temporarily hold the date of a holiday
		var holDate;
		var holDateYear;
		var holDateMonth;
		
		//loop to place only the day of the month for each holiday in the month being scheduled
		for(var holIncrement = 0; holIncrement < holidaysForYear.length; holIncrement++) {
			holDate = new Date(holidaysForYear[holIncrement]);
			//increment date - javascript issue (causes loss of a day when creating date object)
			holDate.setDate(holDate.getDate() + 1);
			//retrieve year from holDate
			holDateYear = holDate.getFullYear();
			//retrieve month from holDate
			holDateMonth = holDate.getMonth();
			if((holDateYear == year) && (holDateMonth == month)) {
				holidays.push(holDate.getDate());
			}
		}
		
		// variable: number of days in the month to be scheduled
		var numDays;

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
		// sort the requests array by date
		reqSorted = sort(requests, 2).slice();
		
		// boolean: day is holiday
		var holiday = false;
		// boolean: weekend day is holiday
		var wEndHoliday = false;
		// integer: incrementer for holiday array
		var holIncrement = 0;
		// integer: variable to store the previously assigned doctor ID
		var prevDocID;
		// variable: contain the doctorID to add to the schedule
		var docPosition;
		// variable: to contain an integer representation of day of week (0=Sun, 1=Mon, ...)
		var day;
		
		// loop through each day of the month to generate the schedule
		for (var dayIncrement = 0; dayIncrement < numDays; dayIncrement++) {

			if(holidays.length != 0) {
				if (holidays[holIncrement] == dayIncrement + 1) {
					holiday = true;
					holIncrement++;
				} else {
					holiday = false;
				}
			}

			// variable: for the day of the week based on month, year, and current iteration through the loop
			day = new Date(year, month, dayIncrement + 1).getDay();

			switch (day) {

				// weekend cases
				case 0: //Sunday
				case 6: //Saturday
					if (holiday)
						wEndHoliday = true;

					if (dayIncrement != 0) {
						// assign doctor to sched array
						sched[dayIncrement] = prevDocID;
						// find doctor id in doctors array
						for(var docIncrement = 0; docIncrement < doctors.length; docIncrement++) {
							if(doctors[docIncrement][0] == prevDocID) {
								doctors = increment(doctors, docIncrement, 'weekend');
								break;
							}
						}
						
					} else {
						// reset prevDocID to 0
						prevDocID = 0;
					
						// variable: integer for previous month
						var prevMonth = month;
						// if month is 12 (algorithm uses 0-11) set prevMonth to 0
						if(month == 12) 
							prevMonth = 0;
						
						// check all previous schedules in prevSched array
						for(var prevSchedIncrement = 0; prevSchedIncrement < prevSched.length; prevSchedIncrement++) {
							// check if the previous schedule month matches month prior to month currently being scheduled
							if(prevSched[prevSchedIncrement][1] == prevMonth) {
								// check if previous schedule year matches year being scheduled (or in case of Jan schedule the previous year)
								if(((month == 0) && (prevSched[prevSchedIncrement][0] == year-1)) || ((month != 0) && (prevSched[prevSchedIncrement][0] == year))) {
									// variable: integer decrementer to go through the last 4 entries in the previous schedule
									var prevSchedDecrement = 5;
									while(prevSchedDecrement >= 2) {
										// check if the entry is not null
										if(prevSched[prevSchedIncrement][prevSchedDecrement] !== null){
											// assign docID from the end of the schedule to the variable prevDocID
											prevDocID = prevSched[prevSchedIncrement][prevSchedDecrement];
											// break out of while loop
											break;
										}
										// decrement to the next last position in the previous schedule information
										prevSchedDecrement--;
									}
								}
							}
						}
						
						if(prevDocID == 0) {
							
							// sort doctors array by weekend days worked
							docSorted = sort(doctors, 2).slice();
							// retrieve position in docSorted array of the doctor to schedul
							docPosition = findDoctor(year, month, dayIncrement, docSorted, reqSorted, false);
							// assign id of doctor to prevDocID variable
							prevDocID = docSorted[docPosition][0];
							
						}
						for(var docIncrement = 0; docIncrement < doctors.length; docIncrement++) {
							if(doctors[docIncrement][0] == prevDocID) {
								doctors = increment(doctors, docIncrement, 'weekend');
								break;
							}
						}
						
						sched[dayIncrement] = prevDocID
					}

					break;

					// weekday cases
				case 1: //Monday
					if (holiday || wEndHoliday) {
						if (dayIncrement != 0) {
							// assign doctor to sched array
							sched[dayIncrement] = prevDocID;
							//increment doctors weekend days worked
							for(var docIncrement = 0; docIncrement < doctors.length; docIncrement++) {
								if(doctors[docIncrement][0] == prevDocID) {
									doctors = increment(doctors, docIncrement, 'holiday');
									break;
								}
							}
						
						} else {
						
							prevDocID = 0;
						
							// variable: integer for previous month
							var prevMonth = month;
							// if month is 12 (algorithm uses 0-11) set prevMonth to 0
							if(month == 12) 
								prevMonth = 0;
							
							for(var prevSchedIncrement = 0; prevSchedIncrement < prevSched.length; prevSchedIncrement++) {
								
								if(prevSched[prevSchedIncrement][1] == prevMonth) {
									if(((month == 0) && (prevSched[prevSchedIncrement][0] == year-1)) || ((month != 0) && (prevSched[prevSchedIncrement][0] == year))) {
										var prevSchedDecrement = 5;
										while(prevSchedDecrement >= 2) {
											if(prevSched[prevSchedIncrement][prevSchedDecrement] !== null){
												prevDocID = prevSched[prevSchedIncrement][prevSchedDecrement];
												break;
											}
											prevSchedDecrement--;
										}
									}
								}
							}
							
							if(prevDocID == 0) {
								
								docSorted = sort(doctors, 1).slice();

								docPosition = findDoctor(year, month, dayIncrement, docSorted, reqSorted, false);

								prevDocID = docSorted[docPosition][0];
								
							}
							for(var docIncrement = 0; docIncrement < doctors.length; docIncrement++) {
								if(doctors[docIncrement][0] == prevDocID) {
									doctors = increment(doctors, docIncrement, 'holiday');
									break;
								}
							}
							
							sched[dayIncrement] = prevDocID
							}
						break;
					} else {
						docSorted = sort(doctors, 3).slice();

						docPosition = findDoctor(year, month, dayIncrement, docSorted, reqSorted, false);

						sched[dayIncrement] = docSorted[docPosition][0];
						docSorted = increment(docSorted, docPosition, 'weekday');
						doctors = docSorted.slice();
						break;
					}
				case 2: //Tuesday
				case 3: //Wednesday
				case 4: //Thurday
					if(holiday){
						docSorted = sort(doctors, 1).slice();
					} else {
						docSorted = sort(doctors, 3).slice();
					}

					docPosition = findDoctor(year, month, dayIncrement, docSorted, reqSorted, false);

					sched[dayIncrement] = docSorted[docPosition][0];
					if (holiday){
						docSorted = increment(docSorted, docPosition, 'holiday');
					}
					else {
						docSorted = increment(docSorted, docPosition, 'weekday');
					}

					doctors = docSorted.slice();
					break;
				case 5: //Friday
					// checks if current day, or following weekend (& Monday), is a holiday
					if (holiday || (holidays[holIncrement] == dayIncrement + 2) 
					|| (holidays[holIncrement] == dayIncrement + 3) || (holidays[holIncrement] == dayIncrement + 4)) {
						docSorted = sort(doctors, 1).slice();
					} else {
						docSorted = sort(doctors, 2).slice();
					}

					docPosition = findDoctor(year, month, dayIncrement, docSorted, reqSorted, true);

					sched[dayIncrement] = docSorted[docPosition][0];
					prevDocID = docSorted[docPosition][0];
					if (holiday){
						docSorted = increment(docSorted, docPosition, 'holiday');
					}
					else {
						docSorted = increment(docSorted, docPosition, 'weekday');
					}
					doctors = docSorted.slice();
					break;
			}
		}
		
		return {
			'sched' : sched,
			'doctors' : doctors
		};
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
			if (parseInt(a[sortBy]) == parseInt(b[sortBy])) return 0;
			return parseInt(a[sortBy]) < parseInt(b[sortBy]) ? -1 : 1;
		});
		
		return arrayToSort;
	}
	
	/*
		Function: increment

		increments the number of worked days in the doctors array for the doctor scheduled

		Parameters:

			doctors - array containing doctor information
			docPosition - position in the array to perform increment on
			dayType - the type of day to be incremented ('weekend','weekday','holiday')
		
		Returns:

			the doctor array

	*/
	function increment(doctors, docPosition, dayType) {
		switch(dayType){
			case 'weekday':
				doctors[docPosition][3] = (parseInt(doctors[docPosition][3]) + 1).toString(); //total (real + theoretical)
				doctors[docPosition][8] = (parseInt(doctors[docPosition][8]) + 1).toString(); //real
				break;
			case 'weekend':
				doctors[docPosition][2] = (parseInt(doctors[docPosition][2]) + 1).toString(); //total
				doctors[docPosition][7] = (parseInt(doctors[docPosition][7]) + 1).toString(); //real
				break;
			case 'holiday':
				doctors[docPosition][1] = (parseInt(doctors[docPosition][1]) + 1).toString(); //total
				doctors[docPosition][6] = (parseInt(doctors[docPosition][6]) + 1).toString(); //real
				break;
		}
		
		return doctors;
	}

	/* 
		Function: findDoctor

		Finds the doctor to be scheduled for the given day

		Parameters:

			year - year being scheduled
			month - month being scheduled
			date - the day of the month (integer)
			doctors - the array of doctors (already sorted by the days worked of given type)
			requests - the array of requests (already sorted by the date of the request)
			weekendCheck - boolean variable to tell if requests need to be checked for the weekend (on Friday)
		
		Returns:

			an integer index in the doctor array of the doctor to be scheduled

	*/
	function findDoctor(year, month, date, doctors, requests, weekendCheck) {
		
		var day = date + 1;
		var schedulingDate = new Date(year, month, day);
		
		// variable: integer to increment through the doctors array
		var doctorIncrement;
		
		// variable: integer to increment through the requests array
		var requestIncrement;
		
		if (requests.length != 0) {

			// for loop to iterate through the request array
			for (requestIncrement = 0; requestIncrement < requests.length; requestIncrement++) {
				// if request on
				if (requests[requestIncrement][1] == 1) {
				
					// variable: date object for date of request
					var reqDate = new Date(requests[requestIncrement][2]);
					// increment for lost day - javascript issue
					reqDate.setDate(reqDate.getDate() + 1);
					
					if(weekendCheck) {
						// if request is for current day, month, year
						if (((reqDate.getDate() == day) || (reqDate.getDate() == day + 1) || (reqDate.getDate() == day + 2))
						&& (reqDate.getFullYear() == year) 
						&& (reqDate.getMonth() == month) ) {
						
							// find the doctor id matching the request's doctor id and return position of doctor in array
							for (doctorIncrement = 0; doctorIncrement < doctors.length; doctorIncrement++) {
								if (doctors[doctorIncrement][0] == requests[requestIncrement][0]) {
									//return position of doctor id matching the doctor id for the request on
									return doctorIncrement;
								}
							}
						}
					} else {
						// check if request is for current day
						if ((reqDate.getDate() == day) && (reqDate.getFullYear() == year) && (reqDate.getMonth() == month) ) {
						
							for (doctorIncrement = 0; doctorIncrement < doctors.length; doctorIncrement++) {
								if (doctors[doctorIncrement][0] == requests[requestIncrement][0]) {
									//return position of doctor id matching the doctor id for the request on
									return doctorIncrement;
								}
							}
						}
					}
				}
					// if request off
				else if (requests[requestIncrement][1] == 0) {
					var reqDate = new Date(requests[requestIncrement][2]);
					
					if(weekendCheck) {
						// if request is for current day, month, year
						if (((reqDate.getDate() == day) || (reqDate.getDate() == day + 1) || (reqDate.getDate() == day + 2))
						&& (reqDate.getFullYear() == year) 
						&& (reqDate.getMonth() == month) ) {
							for (doctorIncrement = 0; doctorIncrement < doctors.length; doctorIncrement++) {
								var docStart = new Date(doctors[doctorIncrement][4]);
								if(doctors[doctorIncrement][5] !== null) {
									var docEnd = new Date(doctors[doctorIncrement][5]);
								}
								else {
									var docEnd = new Date("2999-01-01");
								}
								
								if ((doctors[doctorIncrement][0] != requests[requestIncrement][0]) && (schedulingDate > docStart) && (schedulingDate <= docEnd)) {
									//return position of doctor id matching the doctor id for the request on
									return doctorIncrement;
								}
							}
						}
					} else {
						// if request is for current day
						if ((reqDate.getDate() == day) && (reqDate.getFullYear() == year) && (reqDate.getMonth() == month) ) {
							for (doctorIncrement = 0; doctorIncrement < doctors.length; doctorIncrement++) {
								var docStart = new Date(doctors[doctorIncrement][4]);
								if(doctors[doctorIncrement][5] !== null) {
									var docEnd = new Date(doctors[doctorIncrement][5]);
								}
								else {
									var docEnd = new Date("2999-01-01");
								}
								
								if ((doctors[doctorIncrement][0] != requests[requestIncrement][0]) && (schedulingDate > docStart) && (schedulingDate <= docEnd)) {
									//return position of doctor id matching the doctor id for the request on
									return doctorIncrement;
								}
							}
						}
					}
				}
			}
		}
		for (doctorIncrement = 0; doctorIncrement < doctors.length; doctorIncrement++) {
			var docStart = new Date(doctors[doctorIncrement][4]);
			if(doctors[doctorIncrement][5] !== null) {
				var docEnd = new Date(doctors[doctorIncrement][5]);
			}
			else {
				var docEnd = new Date("2999-01-01");
			}
			
			if ((schedulingDate > docStart) && (schedulingDate <= docEnd)) {
				//return position of doctor id matching the doctor id for the request on
				return doctorIncrement;
			}
		}
	}
</script>