if ('geolocation' in navigator) {
    $('.js-geolocation').show();
} else {
    $('.js-geolocation').hide();
}
$('.js-geolocation').on('click', function () {
    navigator.geolocation.getCurrentPosition(function (position) {
        loadWeather(position.coords.latitude + ',' + position.coords.longitude);
    });
});
$('.refresh-weather').on('click', function () {
	$.get('user_skin_config.php?operation=get_site_preferences', function (d) {
		if (d.location_1 != '0' && d.location_1 != 'null') 
		{
			loadWeather(d.location_1 + ',' + ''); 
		}
	});
});

function loadWeather(location, woeid) {
    $.simpleWeather({
        location: location,
        woeid: woeid,
        unit: 'f',
        success: function (weather) {
            html = '<h2>' + weather.temp + '&deg;' + weather.units.temp + '</h2>';
            html += '<p><strong>City:</strong> ' + weather.city + ', ' + weather.region + '</p>';
            html += '<p><strong>Currently:</strong> ' + weather.currently + '</p>';
            html += '<p><strong>Temperature:</strong> ' + weather.alt.temp + '&deg;C</p>';
			var nav_weather = weather.temp + '&deg;' + weather.units.temp;
     
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
	
		  
		  
		   html += '<p><strong>Last update:</strong> '+weather.updated+'</p>';
		   
		   html += '<p><a href="'+weather.link+'" target="_new"><button class="btn btn-primary full-width" value="Full weather update">Full weather update</button></a></p>';
		  
		  html += '<center><img alt="image"  class="img-responsive" src="'+weather.image+' "></center>';
	

            $('#weather').html(html);
			$('#weather-on-nav').html(nav_weather);
        },
        error: function (error) {
            $('#weather').html('<p>' + error + '</p>');
        }
    });
}