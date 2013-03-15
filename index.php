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
		<script src="resources/js/jquery-1.9.1.js"></script>
		<script src="resources/js/jquery-ui-1.10.2.custom.min.js"></script>
		<link rel="stylesheet" type="text/css" href="resources/css/custom-theme/jquery-ui-1.10.2.custom.min.css"/>
		<script src="resources/js/jquery.maskedinput.min.js"></script> <!-- http://digitalbush.com/projects/masked-input-plugin/ -->
		
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
					disableDragging:true,
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
							url: 'readSchedule.php'
						}
					]
				});
			});
			
			$(function() {
				$( "#navigation" ).tabs({
				  beforeLoad: function( event, ui ) {
				    ui.jqXHR.error(function() {
				      ui.panel.html(
					"Couldn't load this tab. We'll try to fix this as soon as possible. " +
					"If this wouldn't be a demo." );
				    });
				  }
				});
			      });
        function reloadCalendar(){
          $('#calendar').fullCalendar( 'refetchEvents' )
        }
        
        function getDate(){
          var date = $('#calendar').fullCalendar('getDate');
          var month = date.getMonth();
          return month;
        }
          
		</script>
				
	</head>
	<body>
		<div style="width:960px; margin-left:auto; margin-right:auto;">
			
			<header class="ui-state-default ui-corner-all" style="width:934px;">
				<h1>Calls</h1>
			</header>
		
			<div id="navigation">
				<ul>
					<li><a href="#tabs-1">Calendar</a></li>
					<li><a href="doctors.php">Doctors</a></li>
					<li><a href="requests.php">Requests</a></li>
					<li><a href="reports.php">Reports</a></li>
					<li><a href="testing.php">Testing</a></li>
				</ul>
				<div id="tabs-1">
					<div id="calendar"></div>
					
				<!--Include the generate.php page for creating scheduels -->
				<?php include("generateSchedule.php"); ?>
				<input id="schedule" type="button" value="Create Schedule" onclick="prepareAlgorithm(getDate()); window.setTimeout(reloadCalendar(),1500);" />
        </div>
			</div>
		</div>
		<footer></footer>
	</body>
</html>