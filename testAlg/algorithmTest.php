<html>
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>
<script type="text/javascript">

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

        if (holidays[x] == i + 1) {
            holiday = true;
            x++;
        } else {
            holiday = false;
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
				if(holiday)
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
                if(holiday)
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
					for(r = 0; r < doctors.length; r++){
						if(doctors[r][0] == requests[j][0]){
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
					for(r = 0; r < doctors.length; r++){
						if(doctors[r][0] != requests[j][0]){
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

function prepareAlgorithm() {
    var doctors = new Array();
    for (var i = 1; i <= 8; i++) {
        doctors[i - 1] = new Array(i, 0, 0, 0);
    }

    var requests = new Array();
	for (var i = 1; i <= 8; i++) {
        requests[i - 1] = new Array(i, i % 2, Math.floor(Math.random() * 28));
    }
	
    var month = 7;
    var year = 2013;
    var holidays = new Array();
    holidays[0] = 1;

    var results = new Array();
    results = schedAlgorithm(doctors, requests, month, year, holidays);
	return results;  
}
function postData(){
  var data = prepareAlgorithm();
  

  data = JSON.stringify(data);
  $.post('../scheduleCreator.php', { 'data': data });
  alert(JSON.stringify(data));
	
	document.write(data);
	
}

</script> 

</head>
<body>
<script type ="text/javascript">
postData();
</script>

</body>
</html>