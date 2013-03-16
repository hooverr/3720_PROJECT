<?php
class HolidayCreator{
  /*
  List of Alberta Holiday's as of 3/15/2013

  New Year's Day - January 1st
  Alberta Family Day - Third Monday in February
  Good Friday - Friday before Easter
  Victoria Day - Monday before May 25
  Canada Day - July 1, except when it falls on a Sunday, then it is July 2
  Labour Day - First Monday in September
  Thanksgiving Day - Second Monday in October
  Remembrance Day - November 11
  Christmas Day - December 25


  Optional Holidays

  Easter Monday - First Monday following Easter
  Heritage Day - First Monday in August
  Boxing Day - December 26
  */
  
  private function createCalendarHolidayArray($year){
    
    //set the background color for the holidays, assumes using full calendar library
    $backgroundColor = "#111111";
    
    //create array to store holidays
    $holidayArray = array();
    
    //create the static holdays
    $newYearsDay = array('title'=>'New Years Day','start'=> "$year-01-01");
    $remembranceDay = array('title' =>'Remembrance Day','start'=> "$year-11-11");
    $christmasDay = array('title' =>'Christmas Day','start'=> "$year-12-25");
    $boxingDay = array('title' =>'Boxing Day','start'=> "$year-12-26");

    //push the static holidays onto $holidayArray
    $holidayArray[] = $newYearsDay;
    $holidayArray[] = $remembranceDay;
    $holidayArray[] = $christmasDay;
    $holidayArray[] = $boxingDay;

    //Alberta Family Day - Third Monday in February
    //find the first monday. 
    $day = 1;
    while(date("D",mktime(0,0,0,2,$day,$year)) != 'Mon'){
    $day++;
    }
    
    //add 14 to find the 3rd monday
    $day += 14;
    $day = str_pad($day,2,"0",STR_PAD_LEFT);
    $albertaFamilyDay = array('title'=>'Family Day','start'=>"$year-02-$day");
    $holidayArray[] = $albertaFamilyDay;
    
    //Good Friday & Easter Monday
    $easterDayOfMonth = date("j",easter_date($year));
    $easterMonth = date("m",easter_date($year));
    $day = $easterDayOfMonth;
    $month = $easterMonth ;
    while(date("D",mktime(0,0,0,$month,$day,$year)) != 'Fri'){
      $day--;
    }
    $day = str_pad($day,2,"0",STR_PAD_LEFT);
    $month = str_pad($month,2,"0",STR_PAD_LEFT);
    $goodFriday = array('title'=>'Good Friday','start'=>"$year-$month-$day");
    $holidayArray[] = $goodFriday;

    //reset day
    $day = $easterDayOfMonth;
    while(date("D",mktime(0,0,0,$month,$day,$year)) != 'Mon'){
      $day++;
    }
    //if monday following easter is in next month, increment month and reset day 
    if($day > 31){
      $day = $day -31;
      $month++;
     }
    $day = str_pad($day,2,"0",STR_PAD_LEFT);
    $month=str_pad($month,2,"0",STR_PAD_LEFT);
    $easterMonday = array('title'=>'Easter Monday','start'=>"$year-$month-$day");
    $holidayArray[] = $easterMonday;
    
    //Victoria Day
    //find the day of month for may 25
    $day = 25;
    while(date("D",mktime(0,0,0,5,$day,$year)) != 'Mon'){
      $day--;
    }
    $day = str_pad($day,2,"0",STR_PAD_LEFT);
    $victoriaDay = array('title'=>'Victoria Day','start'=>"$year-05-$day");
    $holidayArray[] = $victoriaDay;
    
    //Canada Day
    $day = 1;
    if(date("D",mktime(0,0,0,7,$day,$year)) == 'Sun'){
      $day++;
    } 
    $day = str_pad($day,2,"0",STR_PAD_LEFT);    
    $canadaDay =  array('title'=>'Canada Day','start'=>"$year-07-$day");
    $holidayArray[] = $canadaDay;
    
    //Labour Day
    $day = 1;
    while(date("D",mktime(0,0,0,9,$day,$year)) != 'Mon'){
      $day++;
    }
    $day = str_pad($day,2,"0",STR_PAD_LEFT);
    $labourDay = array('title'=>'Labour Day','start'=>"$year-09-$day");
    $holidayArray[] = $labourDay;
    
    //Thanksgiving Day
    $day = 1;
    while(date("D",mktime(0,0,0,10,$day,$year)) != 'Mon'){
      $day++;
    }
    //second Monday
    $day+=7;
    $day = str_pad($day,2,"0",STR_PAD_LEFT);
    $thanksgivingDay = array('title'=>'Thanksgiving Day','start'=>"$year-10-$day");
    $holidayArray[] = $thanksgivingDay;
    
    //Heritage Day
    $day = 1;
    while(date("D",mktime(0,0,0,8,$day,$year)) != 'Mon'){
      $day++;
    }
    $day = str_pad($day,2,"0",STR_PAD_LEFT);
    $heritageDay = array('title'=>'Heritage Day','start'=>"$year-08-$day");
    $holidayArray[] = $heritageDay;
    
    return $holidayArray;
  }
  public function dateArray($year){
      $holidayArray = $this->createCalendarHolidayArray($year);
      
      foreach($holidayArray as $holiday){
        $dateArray[] = $holiday['start'];
      }   
      return $dateArray;
  }
  
  public function jsonArray($year){
    return json_encode($this->createCalendarHolidayArray($year));
  }
}
?>