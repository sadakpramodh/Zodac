<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="calendar-dev.php";
if(CALENDAR-DEV != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Calendar</title>
<?php 
	require_once("comns/head_section.php")
?>

    <link href="css/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
    <link href="css/plugins/fullcalendar/fullcalendar.print.css" rel='stylesheet' media='print'>



</head>

<body>

<div id="wrapper">

	<?php
	require_once("leftnavigationbar.php");
	?>
	
<div id="page-wrapper" class="gray-bg">
<div class="row border-bottom">
    <?php
				require_once("search.php");
			?>
	</div>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Calendar</h2>
        
    </div>
</div>
<div class="wrapper wrapper-content">
    <div class="row animated fadeInDown">
        
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?php echo $_SESSION["username"]; ?> Calendar </h5>
                    
                </div>
                <div class="ibox-content">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once("footer.php");
?>

</div>
<?php
require_once("rightsidebar.php");
?>
</div>
<?php require_once("user_skin_config.php"); ?>
<?php 
	require_once("comns/body_section.php")
?>


<!-- Full Calendar -->
<script src="js/plugins/fullcalendar/fullcalendar.min.js"></script>

<script src='js/plugins/fullcalendar/gcal.js'></script>
<script>

    $(document).ready(function() {

           $.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
			<?php $content = file_get_contents("js/custom.js.php"); echo $content?>

        /* initialize the external events
         -----------------------------------------------------------------*/


        $('#external-events div.external-event').each(function() {

            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true // maintain when user navigates (see docs on the renderEvent method)
            });

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 1111999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });

        });


        /* initialize the calendar
         -----------------------------------------------------------------*/
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        $('#calendar').fullCalendar({
			// THIS KEY WON'T WORK IN PRODUCTION!!!
			// To make your own Google API key, follow the directions here:
			// http://fullcalendar.io/docs/google_calendar/
			googleCalendarApiKey: 'AIzaSyBKlgPo7j-Cy8SbD5MI3NX21RU8hUIDxiE',
			eventSources: [
				{
				// your google calendar
				  googleCalendarId:'sadakpromodh@gmail.com',
				  color: 'yellow',   // an option!
				  textColor: 'black', // an option!
				  
				  events: [
						{
							title: 'Event1',
							start: '2015-08-17'
						},
						{
							title: 'Event2',
							start: '2015-08-18'
						}
						// etc...
					],
					color: 'yellow',   // an option!
					textColor: 'black', // an option!
					url: 'http://127.0.0.1/fullcalendar-2.3.2/demos/json/events.json', // use the `url` property
					color: 'yellow',    // an option!
					textColor: 'black'  // an option!
				}
				
		],
				
			
			eventClick: function(event) {
				// opens events in a popup window
				window.open(event.url, 'gcalevent', 'width=700,height=600');
				return false;
			},
			
			loading: function(bool) {
				$('#loading').toggle(bool);
			},
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar
            drop: function() {
                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }
            },
            
        });


    });

</script>
</body>
</html>
