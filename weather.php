<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="weather.php";
if(WEATHER != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Weather</title>

<?php 
	require_once("comns/head_section.php")
?>

</head>

<body>

    <div id="wrapper">

    <?php
	require_once("leftnavigationbar.php");
	?>
        <div id="page-wrapper" class="gray-bg">
		<?php
				require_once("search.php");
			?> 
           <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Weather</h2>
                    
                </div>
                <div class="col-lg-2">

                </div>
            </div>
			<?php
						if($_SESSION["message"] != null)
							{
							display_message($_SESSION["message"], $_SESSION["type"]);
							}
						$_SESSION["message"] = null;
						?>
			
						<?php
						if(!empty($errors))
							{
							echo "<div class=\"form-group\">";
							echo "<div class=\"alert alert-danger alert-dismissible\" role=\"alert\">";
							echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
							echo "<strong>";
							echo " Please review following errors<br>";
							 foreach ($errors as $error) 
								{
								echo $error . "<br>";
								} 
							echo "</strong>";
							echo "</div>";
						
							echo "</div>";
							
							}
					?>
        <div class="wrapper wrapper-content animated fadeInRight">
            
            <div class="row">
                <div class="col-lg-12">
				
				
					
					<form id="search">
					<div class="form-group">
						<label class="control-label">Enter a city</label>
						<input type="text" id="weatherLocation" name="weatherLocation" class="form-control" size="50" />
					</div>
					<div class="form-group">
						<input type="submit" name="submit" value="Search" class="btn btn-w-m btn-success"/>
						<input type="button" class="btn btn-w-m btn-info" id="storelocation" value="Set as default location">
						<button type="button" class="js-weather-geolocation btn btn-w-m btn-default"> <i class="js-weather-geolocation fa fa-location-arrow"></i> Use Your Location</button>
						
					</div>
					</form>

					<div id="weatherList"></div>
					<div id="weather_app"></div>
					
					
					
				
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
	<script src="js/jquery.zweatherfeed.min.js"></script>
	<script type="text/javascript">

	
	$("#storelocation").click(function(){
			var location_name = $("#set_weather_city").val();
			var url_1 = 'user_skin_config.php?set_site_preferences=location_1&value=' + location_name;
			
			$.get(url_1);
			alert( "Location set as default" );
	});
	$('#search').submit( function(e) {
		e.preventDefault();
		weatherGeocode('weatherLocation','weatherList');
	});

	function showLocation(address,woeid) {

		$('#weatherReport').empty();

		$('#weatherReport').weatherfeed([woeid],{
			woeid: true
		});
	}

	function weatherGeocode(search,output) {

		var status;
		var results;
		var html = '';
		var msg = '';

		// Set document elements
		var search = document.getElementById(search).value;
		var output = document.getElementById(output);

		if (search) {

			output.innerHTML = '';

			// Cache results for an hour to prevent overuse
			now = new Date();

			// Create Yahoo Weather feed API address
			var query = 'select * from geo.places where text="'+ search +'"';
			var api = 'http://query.yahooapis.com/v1/public/yql?q='+ encodeURIComponent(query) +'&rnd='+ now.getFullYear() + now.getMonth() + now.getDay() + now.getHours() +'&format=json&callback=?';

			// Send request
			$.ajax({
				type: 'GET',
				url: api,
				dataType: 'json',
				success: function(data) {

					if (data.query.count > 0 ) {						

						html = '<span>'+ data.query.count +' location';

						if (data.query.count > 1) html = html + 's';
						html = html + ' found:</span><ul>';

						// List multiple returns
						if (data.query.count > 1) {
							for (var i=0; i<data.query.count; i++) {
								html = html + '<li>'+ _getWeatherAddress(data.query.results.place[i]) +'</li>';
							}
						} else {
							html = html + '<li>'+ _getWeatherAddress(data.query.results.place) +'</li>';
						}
  	
						html = html + '</ul>';

						output.innerHTML = html;

						// Bind callback links
						$("a.weatherAddress").unbind('click');
						$("a.weatherAddress").click(function(e) {
							e.preventDefault();

							var address = $(this).text();
							var weoid = $(this).attr('rel');

							showWeather(address,weoid);
						}); 

					} else {
						output.innerHTML = 'The location could not be found';
					}
				},
				error: function(data) {
					output.innerHTML = 'An error has occurred';
				}
			});

		} else {

			// No search given
			output.innerHTML = 'Please enter a location or partial address';
		}
	}
	function _getWeatherAddress(data) {

		// Get address
		var address = data.name;
		if (data.admin2) address += ', ' + data.admin2.content;
		if (data.admin1) address += ', ' + data.admin1.content;
		address += ', ' + data.country.content;

		// Get WEOID
		var woeid = data.woeid;

		return '<a class="weatherAddress" href="" rel="'+ woeid +'" title="Click for to see a weather report">'+ address +'</a> <small>('+ woeid +')</small>';
	}
	function showWeather(location, woeid) {
    $.simpleWeather({
        location: location,
        woeid: woeid,
        unit: 'f',
        success: function (weather) {
            html = '<h2 style="font-size: 100px; font-weight: 300; text-shadow: 0px 1px 3px rgba(0, 0, 0, 0.15);">' + weather.temp + '&deg;' + weather.units.temp + '</h2>';
			html += '<img alt="image"  class="img-responsive" src="'+weather.image+' ">';
            html += '<p><strong>City:</strong> ' + weather.city + ', ' + weather.region + '</p>';
            html += '<p><strong>Currently:</strong> ' + weather.currently + '</p>';
            html += '<p><strong>Temperature:</strong> ' + weather.alt.temp + '&deg;C</p>';

     
		  html += '<p><strong>High:</strong> '+ weather.high + '&deg;' + weather.units.temp +' ';
		  html += '<strong>Low:</strong> '+weather.low+ '&deg;' + weather.units.temp + '</p>';
		  html += '<p><strong>Current Condition:</strong> '+weather.text+'</p>';
		  html += '<p><strong>Humidity:</strong> '+weather.humidity+'%</p>';
		  html += '<p><strong>Pressure:</strong> '+weather.pressure+''+weather.units.pressure+'</p>';
		 
		  html += '<p><strong>Visbility:</strong> '+weather.visibility+ ' ' +weather.units.distance+'</p>';
		  html += '<p><strong>Sunrise:</strong> '+weather.sunrise+' ';
		  html += '<strong>Sunset:</strong> '+weather.sunset+'</p>';
		  
	
		  html += '<p><strong>Wind:</strong> Toward '+weather.wind.direction+' '+weather.wind.speed+''+weather.units.distance+'</p>';

		  
		  html += '<p><strong>High:</strong> '+weather.alt.high+ '&deg;' + weather.alt.unit +' '
		  html += '<strong>Low:</strong> '+weather.alt.low+ '&deg;' + weather.alt.unit +'</p>';
	
		  html +='<table class="table table-striped">  <thead> <tr> <th>Date</th> <th>Day</th> <th>High & Low</th> <th>High & Low</th> <th>Image</th><th>Condition</th></tr> </thead> <tbody>';
		  for(var i=0;i<weather.forecast.length;i++) {
		html +='<tr>';
        html += '<strong><td>'+weather.forecast[i].date+'</td>';
        html += '<td>'+weather.forecast[i].day+'</td>';
        
        html += '<td><i class="text-danger fa fa-level-up"></i>'+weather.forecast[i].high+' ';
        html += '<i class="text-warning fa fa-level-down"></i>'+weather.forecast[i].low+'&deg;' + weather.units.temp +'</td>';
        html += '<td><i class="text-danger fa fa-level-up"></i>'+weather.forecast[i].alt.high+' ';
        html += '<i class="text-warning fa fa-level-down"></i>'+weather.forecast[i].alt.low+ '&deg;' + weather.alt.unit +'</td></strong>';
        
        html += '<td><img src="'+weather.forecast[i].thumbnail+'"> </td>';
        html += '<td><strong>'+weather.forecast[i].text+' <strong></td></tr>';
      }
		  html +='</tbody> </table>';
		  html += '<p><a href="'+weather.link+'" target="_new"><button class="btn btn-primary full-width" value="Full weather update">View Source</button></a></p>';
		   html += '<p><strong>Last update:</strong> '+weather.updated+'</p>';
		   html += '<input type="hidden" id="set_weather_city" value="'+weather.city +','+ weather.region + '">';
		   
		   
		  
		 
		  
			$('#storelocation').show();
			$('#weatherList').empty();
            $('#weather_app').html(html);
        },
        error: function (error) {
            $('#weather_app').html('<p>' + error + '</p>');
        }
    });
}
$(document).ready(function () {
	$.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
			<?php $content = file_get_contents("js/custom.js.php"); echo $content?>
	
	
	if ('geolocation' in navigator) {
    $('.js-weather-geolocation').show();
	} else {
    $('.js-weather-geolocation').hide();
	}
$('.js-weather-geolocation').on('click', function () {
    navigator.geolocation.getCurrentPosition(function (position) {
        showWeather(position.coords.latitude + ',' + position.coords.longitude);
    });
});
	$('#storelocation').hide();
	$.get('user_skin_config.php?operation=get_site_preferences', function (d) {
		if (d.location_1 != '0' && d.location_1 != null) 
		{
			loadWeather(d.location_1 + ',' + ''); 
		}
	});
	setInterval(loadWeather, 600000);

	$.get('user_skin_config.php?operation=get_site_preferences', function (d) {
		if (d.location_1 != '0' && d.location_1 != null) 
		{
			showWeather(d.location_1 + ',' + ''); 
		}
	});
	setInterval(showWeather, 600000);
	

	
});

</script>
	</body>
</html>