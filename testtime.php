


<!DOCTYPE html>
<html>
<head>
    <style>
        .clock {
width: 260px;
margin: 0 auto;
padding: 30px;
color: #FFF;background:#333;
}
.clock ul {
width: 250px;
margin: 0 auto;
padding: 0;
list-style: none;
text-align: center
}

.clock ul li {
display: inline;
font-size: 3em;
text-align: center;
font-family: "Arial", Helvetica, sans-serif;
text-shadow: 0 2px 5px #55c6ff, 0 3px 6px #55c6ff, 0 4px 7px #55c6ff
}
#Date { 
font-family: 'Arial', Helvetica, sans-serif;
font-size: 26px;
text-align: center;
text-shadow: 0 2px 5px #55c6ff, 0 3px 6px #55c6ff;
padding-bottom: 40px;
}

#point {
position: relative;
-moz-animation: mymove 1s ease infinite;
-webkit-animation: mymove 1s ease infinite;
padding-left: 10px;
padding-right: 10px
}

/* Animasi Detik Kedap - Kedip */
@-webkit-keyframes mymove 
{
0% {opacity:1.0; text-shadow:0 0 20px #00c6ff;}
50% {opacity:0; text-shadow:none; }
100% {opacity:1.0; text-shadow:0 0 20px #00c6ff; } 
}

@-moz-keyframes mymove 
{
0% {opacity:1.0; text-shadow:0 0 20px #00c6ff;}
50% {opacity:0; text-shadow:none; }
100% {opacity:1.0; text-shadow:0 0 20px #00c6ff; } 
}
    </style>
</head>
<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
// Making 2 variable month and day
var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ]; 
var dayNames= ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]

// make single object
var newDate = new Date();
// make current time
newDate.setDate(newDate.getDate());
// setting date and time
$('#Date').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

setInterval( function() {
// Create a newDate() object and extract the seconds of the current time on the visitor's
var seconds;
$.get('http://127.0.0.1/readytoupload/time.php?time', function (d) {var seconds = d.seconds});
//var seconds = new Date().getSeconds();
// Add a leading zero to seconds value
$("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
},1000);

setInterval( function() {
// Create a newDate() object and extract the minutes of the current time on the visitor's
var minutes = new Date().getMinutes();
// Add a leading zero to the minutes value
$("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
},1000);

setInterval( function() {
// Create a newDate() object and extract the hours of the current time on the visitor's
var hours;
$.get('http://127.0.0.1/readytoupload/time.php?time', function (d) {hours = parseInt(d.hours)});
//var hours = new Date().getHours();
// Add a leading zero to the hours value
$("#hours").html(( hours < 10 ? "0" : "" ) + hours);
}, 1000); 
});
</script>
<div class="clock">
<div id="Date"></div>
<ul>
<li id="hours"></li>
<li id="point">:</li>
<li id="min"></li>
<li id="point">:</li>
<li id="sec"></li>
</ul>
</div>
    <script type="text/javascript">
        
    </script>
</body>
</html>