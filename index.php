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
          firstDay:1, 
          editable:true,
          disableDragging:true,
          height: 600,
          weekMode:'variable',
          viewDisplay: function(view){
          
            //hide the create schedule button for months prior to current
            //@todo hide button for months that have a schedule
            var calendarViewDate = $('#calendar').fullCalendar('getDate');
            var calendarViewMonth = calendarViewDate.getMonth();
            var currentDate = new Date();
            var currentMonth = currentDate.getMonth();
            if(calendarViewMonth < currentMonth){
              $('#schedule').hide();
        
            }else{
              $('#schedule').show();
            }
          },
          eventClick: function(event){
            $( "#dialog-form").dialog( "open");
            var value = event.id;
            $("#doctor").val(value);
            var date = event.start;
            $("#dateInput").val(date);
            $("#previousDoctor").val(value);
          },
          eventSources: [	
            {
            url: 'scheduleFeed.php'
            },
            {
            url: 'holidayFeed.php',
            color: 'black',
            textColor: 'white',
            }
          ]
        });
        $('#schedule').click(function(){
          $('#phpload').load('generateSchedule.php', function(){
            var date = $('#calendar').fullCalendar('getDate');
            var month = date.getMonth();
            var year = date.getFullYear();
            prepareAlgorithm(month,year);
          });
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
          },
          activate: function( event, ui ) {
            if($('#mainTab').parent().hasClass('ui-tabs-active')){
              $('#calendar').fullCalendar( 'refetchEvents' );
              document.getElementById('schedule').focus();
            }
          }
        });
      });
      
      function update(newDoctor,oldDoctor,date){
       var date = new Date(date);
       var month = date.getMonth() +1; //sending to php which uses a normal month numbering system
       var year = date.getFullYear();
       var day = date.getDate();
       $.post('updateSchedule.php', { 'newDoctor': newDoctor, 'oldDoctor': oldDoctor, 'month': month, 'day':day,'year':year}).done(function(){
       $('#calendar').fullCalendar( 'refetchEvents' );
       $("#dialog-form").dialog( "close");
       }); 
       
      }      
      $(function(){
        $( "#dialog-form").dialog({ 
          autoOpen:false,
          buttons: {
            Update: function(){
              update($("#doctor").val(),$("#previousDoctor").val(),$("#dateInput").val());
          
            },
            Cancel: function() {
              $( this ).dialog( "close" );
            }
          }
        });
      });
    </script>			
  </head>
  <body>
    <header class="ui-state-default ui-corner-all" style="width:934px;">
      <h1>Calls</h1>
    </header>
    <div id="navigation">
      <ul>
        <li><a id="mainTab" href="#tabs-1">Calendar</a></li>
        <li><a href="doctors.php">Doctors</a></li>
        <li><a href="requests.php">Requests</a></li>
        <li><a href="reports.php">Reports</a></li>
      </ul>
      <div id="tabs-1">
        <div id ="dialog-form" title="Update Scheduled Doctor">
          <form>
            <?php
            $mysqli = new mysqli('localhost','robh_user','3720project','robh_3720');
            $query = "SELECT doctor_id, name from Doctor"; 
            if($result = $mysqli->query($query)){
              echo '<select id = "doctor">';
              while($row= $result->fetch_assoc()){
                echo '<option value='.$row["doctor_id"].'>'.$row["name"].'</option>';
              }
              echo '</select>';
              $result->free();
            }
            $mysqli->close(); 
            ?>
            <input id="dateInput" type ="hidden" ></input>
            <input id="previousDoctor" type="hidden"></input>
          </form>
        </div>  
        <div id="calendar"></div>				
        <div id="phpload"></div>
        <input id="schedule" type="button" value="Create Schedule" />
      </div>
    </div>
  </body>
</html>