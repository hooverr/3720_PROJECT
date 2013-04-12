<?php
require_once('requestCreator.php');
$requestCreator = new RequestCreator();
echo $requestCreator->jsonString();
?>