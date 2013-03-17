<?php
require_once('scheduleCreator.php');
$scheduleCreator = new ScheduleCreator();
echo $scheduleCreator->jsonString();
?>