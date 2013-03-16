<?php
include('holidayCreator.php');
$holidayCreator = new HolidayCreator();
$year = date("Y");
echo $holidayCreator->jsonArray($year);

?>