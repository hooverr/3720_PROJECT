<?php
	
/*
  Class: RequestCreator
  
  Creates a schedule of requests based on information stored in the database.
  Has the ability to return a jsonString of the created schedule of requests.
*/
class RequestCreator{

	/*
    Function: getDoctorNames
     
    Creates a 2D array of doctors from the database.
      
    Returns:
      
    A 2D array of doctor names and doctorIDs
      
  */
  private function getDoctorNames(){
	include("login.php");
	
	// connection to MySQL ($host, $username, $password from login.php)
	$link = mysql_connect($host,$username,$password);
	
	// checks if connection to MySQL worked
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}

	// selection of DB to use ($database from login.php)
	mysql_select_db($database,$link);

    $result = mysql_query("SELECT * FROM Doctor");
	
	// check if the query was successful
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
	
	$returnArray = array(array());
	$increment = 0;
	
	while($row = mysql_fetch_assoc($result)){
		$returnArray[$increment][0] = $row['Doctor_ID'];
		$returnArray[$increment][1] = $row['Name'];
		$increment++;
	}
	//$result->free();
    //$mysqli->close();
	
	return $returnArray;
  }
  /*
    Function: getDatabaseRequests
     
    Creates a 2D array of requested days on/off from the database.
      
    Returns:
      
    A 2D array representing doctor IDs, type, and days that have been requested on/off.
      
  */
  private function getDatabaseRequests(){
	include("login.php");
	
	// connection to MySQL ($host, $username, $password from login.php)
	$link = mysql_connect($host,$username,$password);
	
	// checks if connection to MySQL worked
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}

	// selection of DB to use ($database from login.php)
	mysql_select_db($database,$link);
	
    $result = mysql_query("SELECT * FROM Requests");
	
    $return = array();
	$increment = 0;
    while($row = mysql_fetch_assoc($result)){
     $return[$increment] = array($row["Doctor_ID"], $row["Type"], $row["Date"]);
	 $increment++;
    }
    //$result->free();
    //$mysqli->close();
   
    return $return;
  }
  /*
    Function: createRequestsArray
     
    Creates a 2d array of requested days on/off.
    
    Returns:
      
    A 2d array of requested days on/off that contains the doctor name, and day requested
    This assumes use of the FullCalendar library.
      
  */
  private function createRequestsArray(){
    
	$doctorNames = $this->getDoctorNames();
	
    $requests = $this->getDatabaseRequests();
	
	$reqArray = array();
	
	foreach($requests as $req) {
		foreach($doctorNames as $doc) {
			if($doc[0] == $req[0]) {
				$dateFromRequest = strtotime($req[2]);
				$dateReq = date('Y-m-d',$dateFromRequest);
				if($req[1] == '0'){
					$color = "#FF0000";
				} else {
					$color = "#00FF00";
				}
				$request = array('title'=>"$doc[1]",'start'=>"$dateReq",'backgroundColor'=>"$color");
				$reqArray[] = $request;
				unset($request);
			}
		}
	}
	
	//$reqArray = array(array('title'=>'TEST','start'=>"2013-04-04",'backgroundColor'=>"#FF0000"),
	//array('title'=>'TEST2','start'=>"2013-04-06",'backgroundColor'=>"#00FF00"));
	
	return $reqArray;
}
	
	/*
    Function: jsonString
     
    Creates a jsonstring representation of the requests on/off.
      
    Returns:
      
    A jsonstring representation of the requests on/off, used by FullCalendar
      
	*/
  public function jsonString(){
    return json_encode($this->createRequestsArray());
  }
}
?>