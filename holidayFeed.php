<?php
require_once('holidayCreator.php');
$holidayCreator = new HolidayCreator();
$year = date("Y");
echo $holidayCreator->jsonString($year);
?>