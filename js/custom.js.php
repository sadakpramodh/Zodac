/*setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 4000,
					positionClass: notification_position
					
					
                };
                toastr.success('Intelligent Dashboard', '<?php echo SITE_NAME; ?>');

            }, 1300);*/
	$('#submittimezone').on('click', function () {
		var a = $('#getservertime').val();
		var url_2 = 'user_skin_config.php?set_site_preferences=location_2&value=' + a;
		$.get(url_2);
		getClock_server();
		
		$.get('user_skin_config.php?operation=get_site_preferences', function (d) {
		var location_2 = "<h3>" + d.location_2 + "</h3>";
		$("#location_2").html(location_2);
		getClock_server();
		});
	});
	$('.refresh-time').on('click', function () {
		getClock_server();
		$.get('user_skin_config.php?operation=get_site_preferences', function (d) {
		var location_2 = "<h3>" + d.location_2 + "</h3>";
		$("#location_2").html(location_2);
		});
	});

	$.get('user_skin_config.php?operation=get_site_preferences', function (d) {
		if (d.location_1 != '0' && d.location_1 != null) 
		{
			loadWeather(d.location_1 + ',' + ''); 
		}
	});
	setInterval(loadWeather, 600000);
	var notification_position;
	$.get('user_skin_config.php?operation=get_site_preferences', function (d) {
			
			if (d.notification_position != 0) {
				notification_position = d.notification_position;
				
			}
	});
			

	
        // Set idle time
		$.get('user_skin_config.php?operation=get_site_preferences', function (d) {
			
			if (d.lockscreen_timeout != 0) {
				var value = d.lockscreen_timeout;
				$( document ).idleTimer( parseInt(value) );
				
			}
			});
        
		
		$("#auto_lock").ionRangeSlider({
            min: 1,
            max: 10,
			from: 1,
            type: 'single',
            postfix: " minutes",
            prettify: false,
            hasGrid: true,
	onStart: function (data) {
       $.get('user_skin_config.php?operation=get_site_preferences', function (d) {
			
			if (d.lockscreen_timeout != 0) {
				
				var slider = $("#auto_lock").data("ionRangeSlider");
				slider.update({
				
				from: d.lockscreen_timeout,
				
			});
			}
	   });
    },
	onFinish: function (data) {
		var value = $("#auto_lock").val();
        $.get('user_skin_config.php?set_site_preferences=lockscreen_timeout&value='+ value);
		
		
    },
			 
        });
		
	
$( document ).on( "idle.idleTimer", function(event, elem, obj){
        toastr.options = {
            "positionClass": notification_position,
            "timeOut": 8000
        }

        toastr.warning('Your app is going to lock.','Idle time');
        $('.custom-alert').fadeIn();
        $('.custom-alert-active').fadeOut();

		window.location = "lockscreen.php?lock=true";

    });	
var systime, ser_date, temphours, tempminutes, tempseconds;
$.get('time.php?time', function(d) {temphours=d.hours; tempminutes=d.minutes; tempseconds=d.seconds});

function updateClock() {
  var time = new Date(ser_date + new Date().getTime() - systime)
    //var currentTime = new Date ( );
  var currentHours = time.getHours();
  var currentMinutes = time.getMinutes();
  var currentSeconds = time.getSeconds();

  // Pad the minutes and seconds with leading zeros, if required
  currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
  currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;

  // Choose either "AM" or "PM" as appropriate
  var timeOfDay = (currentHours < 12) ? "AM" : "PM";

  // Convert the hours component to 12-hour format if needed
  currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;

  // Convert an hours component of "0" to "12"
  currentHours = (currentHours == 0) ? 12 : currentHours;

  // Compose the string for display
  var currentTimeString ="<h5>" + currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay + "</h5>";


  $("#clock").html(currentTimeString);

}

function getClock_server() {
	$.get('time.php?time', function(d) {temphours=d.hours; tempminutes=d.minutes; tempseconds=d.seconds});
  setTimeout(function() { 
    var d = {
      hours: temphours,
      minutes: tempminutes,
      seconds: tempseconds
    }

    var tmp = new Date();
    tmp.setMinutes(d.minutes);
    tmp.setSeconds(d.seconds);
    tmp.setHours(d.hours);

    ser_date = tmp.getTime();
    systime = new Date().getTime();

    updateClock();
    setInterval(updateClock, 1000);
  });
}

getClock_server();



