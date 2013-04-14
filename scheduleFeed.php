<?php
include('scheduleCreator.php');
$scheduleCreator = new ScheduleCreator();
echo $scheduleCreator->jsonString();
?>