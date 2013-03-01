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

    // boolean: day is holiday
    var holiday = false;

    // boolean: weekend day is holiday
    var wEndHoliday = false;

    // integer: holiday array incrementer
    var x = 0;

    // integer: variable to store the previously assigned doctor ID
    var prevDocID;

    // loop through each day of the month to generate the schedule
    for (var i = 0; i < numDays; i++) {
        if (holiday[x] == i) {
            holiday = true;
            x++;
        } else {
            holiday = false;
        }

        // variable: for the day of the week based on month, year, and current iteration through the loop
        var day = new Date(year, month, i + 1).getDay();

        var doctorFound = false;

        switch (day) {

            // weekend cases
            case 0: //Sunday
            case 6: //Saturday
                if (holiday)
                    wEndHoliday = true;


                if (i != 0) {
                    // assign doctor to sched array
                    sched[i] = prevDocID;
                }
                    // currently if the month starts on a weekend it does not carry forward the doctor from the previous month to continue the weekend
                    // so we require scheduling a new doctor
                else {

                    //find appropriate doctor
                    doctors.sort(function (a, b) {
                        if (a[2] == b[2]) return 0;
                        return a[2] < b[2] ? -1 : 1;
                    });

                    requests.sort(function (a, b) {
                        if (a[2] == b[2]) return 0;
                        return a[2] > b[2] ? -1 : 1;
                    });

                    // variable: contain the doctorID to add to the schedule
                    var docID;

                    // for loop to iterate through the doctors array
                    for (var r = 0; r < doctors.length && !doctorFound; r++) {
                        // for loop to iterate through the request array
                        for (var j = 0; j < requests.length && !doctorFound; j++) {
                            // if request on
                            if (requests[j][1] == 1) {
                                // if request is for current day
                                if (requests[j][2] == i) {
                                    // assign doctor id
                                    docID = requests[j][0];
                                    // increment weekend days worked for assigned doctor
                                    if (day = 6) {
                                        doctors[r][2] += 2;
                                    } else {
                                        doctors[r][2] += 1;
                                    }
                                    // assign boolean doctor found
                                    doctorFound = true;
                                }
                            }
                                // if request off
                            else if (requests[j][1] == 0) {
                                // if request is for current day
                                if (requests[j][2] == i) {
                                    // if doctor id matches request doctor id
                                    if (doctors[r][0] == requests[j][0]) {
                                        // breaks nested for loop, moves on to next doctor
                                        break;
                                    }
                                }
                                    // if requests array has moved beyond current day
                                else if (requests[j][2] > i) {
                                    // assign doctor id
                                    docID = doctors[r][0];
                                    // increment weekend days worked for assigned doctor
                                    if (day = 6) {
                                        doctors[r][2] += 2;
                                    } else {
                                        doctors[r][2] += 1;
                                    }
                                    // assign boolean doctor found
                                    doctorFound = true;
                                }
                            }
                        }
                    }

                    // Assign doctor scheduled for weekend day to prevDocID in order to 'reuse' for next day
                    prevDocID = docID;

                    // assign doctor to sched array
                    sched[i] = docID;
                }

                break;

                // weekday cases
            case 1: //Monday
                if (holiday || wEndHoliday) {
                    // reset weekend holiday boolean to false
                    wEndHoliday = false;

                    // assign doctor to sched array
                    sched[i] = prevDocID;
                    break;
                } else {
                    //find appropriate doctor


                    //sched[i] = docID; // commenting these two lines out can carry cause Monday to carry forward,
                    //break;            // using the same calculation and assignment as Tues-Thurs
                }
            case 2: //Tuesday
            case 3: //Wednesday
            case 4: //Thurday
                doctors.sort(function (a, b) {
                    if (a[3] == b[3]) return 0;
                    return a[3] < b[3] ? -1 : 1;
                });

                requests.sort(function (a, b) {
                    if (a[2] == b[2]) return 0;
                    return a[2] > b[2] ? -1 : 1;
                });

                // variable: contain the doctorID to add to the schedule
                var docID;

                // for loop to iterate through the doctors array
                for (var r = 0; r < doctors.length && !doctorFound; r++) {
                    // for loop to iterate through the request array
                    for (var j = 0; j < requests.length && !doctorFound; j++) {
                        // if request on
                        if (requests[j][1] == 1) {
                            // if request is for current day
                            if (requests[j][2] == i) {
                                // assign doctor id
                                docID = requests[j][0];
                                if (holiday) {
                                    // increment holiday days worked for assigned doctor
                                    doctors[r][1] += 1;
                                } else {
                                    // increment weekday days worked for assigned doctor
                                    doctors[r][3] += 1;
                                }
                                // assign boolean doctor found
                                doctorFound = true;
                            }
                        }
                            // if request off
                        else if (requests[j][1] == 0) {
                            // if request is for current day
                            if (requests[j][2] == i) {
                                // if doctor id matches request doctor id
                                if (doctors[r][0] == requests[j][0]) {
                                    // breaks nested for loop, moves on to next doctor
                                    break;
                                }
                            }
                                // if requests array has moved beyond current day
                            else if (requests[j][2] > i) {
                                // assign doctor id
                                docID = doctors[r][0];
                                // increment weekday days worked for assigned doctor
                                doctors[r][3] += 1;
                                // assign boolean doctor found
                                doctorFound = true;
                            }
                        }
                    }
                }

                // Assign doctor to schedule array
                sched[i] = docID;
                break;
            case 5: //Friday
                //find appropriate doctor
                doctors.sort(function (a, b) {
                    if (a[2] == b[2]) return 0;
                    return a[2] < b[2] ? -1 : 1;
                });

                requests.sort(function (a, b) {
                    if (a[2] == b[2]) return 0;
                    return a[2] > b[2] ? -1 : 1;
                });

                // variable: contain the doctorID to add to the schedule
                var docID;

                // for loop to iterate through the doctors array
                for (var r = 0; r < doctors.length && !doctorFound; r++) {
                    // for loop to iterate through the request array
                    for (var j = 0; j < requests.length && !doctorFound; j++) {
                        // if request on
                        if (requests[j][1] == 1) {
                            // if request is for current day
                            if ((requests[j][2] == i) || (requests[j][2] == i + 1) || (requests[j][2] == i + 2)) {
                                // assign doctor id
                                docID = requests[j][0];
                                if (holiday) {
                                    // increment holiday days worked for assigned doctor
                                    doctors[r][1] += 1;
                                    // increment weekend days worked for assigned doctor
                                    if (i + 2 < numDays) {
                                        doctors[r][2] += 2;
                                    } else if (i + 1 < numDays) {
                                        doctors[r][2] += 1;
                                    }
                                } else {
                                    // increment weekday days worked for assigned doctor
                                    doctors[r][3] += 1;
                                    // increment weekend days worked for assigned doctor
                                    if (i + 2 < numDays) {
                                        doctors[r][2] += 2;
                                    } else if (i + 1 < numDays) {
                                        doctors[r][2] += 1;
                                    }
                                }
                                // assign boolean doctor found
                                doctorFound = true;
                            }
                        }
                            // if request off
                        else if (requests[j][1] == 0) {
                            // if request is for current day
                            if ((requests[j][2] == i) || (requests[j][2] == i + 1) || (requests[j][2] == i + 2)) {
                                // if doctor id matches request doctor id
                                if (doctors[r][0] == requests[j][0]) {
                                    // breaks nested for loop, moves on to next doctor
                                    break;
                                }
                            }
                                // if requests array has moved beyond current day
                            else if (requests[j][2] > i) {
                                // assign doctor id
                                docID = doctors[r][0];

                                if (holiday) {
                                    // increment holidays days worked for assigned doctor
                                    doctors[r][1] += 1;
                                } else {
                                    // increment weekday days worked for assigned doctor
                                    doctors[r][3] += 1;
                                }


                                // increment weekend days worked for assigned doctor
                                if (i + 2 < numDays) {
                                    doctors[r][2] += 2;
                                } else if (i + 1 < numDays) {
                                    doctors[r][2] += 1;
                                }

                                // assign boolean doctor found
                                doctorFound = true;
                            }
                        }
                    }
                    if (holiday[x] == i + 3) {
                        // increment holiday days worked for assigned doctor
                        doctors[r][1] += 1;
                    }
                }

                // Assign doctor scheduled for Friday to prevDocID in order to 'reuse' for Saturday and Sunday
                prevDocID = docID;

                // Assign doctor to schedule array
                sched[i] = docID;
                break;
        }
    }
}