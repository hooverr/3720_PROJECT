<!DOCTYPE html> 
<html>
	<head>
		<title>CPSC 3720 Calls Project</title>
		
		<!--
		 This is the main css file for the project.
		 -->
		<link rel="stylesheet" type="text/css" href="mainStyle.php">
		
		<!--
		 CSS Files for the calendar
		 -->
		 
		<link rel='stylesheet' type='text/css' href='libraries/fullcalendar/fullcalendar.css' />
		<link rel='stylesheet' type='text/css' href='libraries/fullcalendar/fullcalendar.print.css' media='print' />
		
		<!-- 
		 Load jQuery and jQueryui from google
		 -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>
		
		<!-- 
		 Load calendar javascript
		 -->
		<script type='text/javascript' src='libraries/fullcalendar/fullcalendar.min.js'></script>
		
		<!--
		 Load jquery for gCal imports
		 -->
		<script type='text/javascript' src='libraries/fullcalendar/gcal.js'></script>
		
		<!-- 
		 Set up the calendar
		 -->
		<script type='text/javascript'>
			$(document).ready(function() {
				var date = new Date();
				var d = date.getDate();
				var m = date.getMonth();
				var y = date.getFullYear();
				
				$('#calendar').fullCalendar({
					firstDay:0, //Sets the calendars first day, 0 for sunday, 1 for monday etc
					editable:true,
					eventClick: function(event){
						alert('Doctor: ' + event.title + '\n' + 'Contact: ' + event.contact);
					},
					eventSources: [	
						{
							url: 'http://www.google.com/calendar/feeds/usa__en%40holiday.calendar.google.com/public/basic',
							color: 'black',
							textColor: 'white'
						},
						{
							url: 'feeds/2013/februaryFeed.php'
						},
						{
							url: 'feeds/2013/marchFeed.json'
						}
					]
				});
			});
		</script>
				
	</head>
	<body>
		<header>
			<h1>Calls Project</h1>
		</header>
			<nav id="navigation">
				<ul>
					<li id="current"><a href="index.html">Schedule</a></li>
					<li><a href="doctors.php">Doctors</a></li>
					<li><a href="requests.php">Requests</a></li>
					<li><a href="reports.php">Reports</a></li>
				</ul>
			</nav>
		<div id="content">
			<div id="calendar"></div>
		</div>
		<footer></footer>
	</body>
</html>