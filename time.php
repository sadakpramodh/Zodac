<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="index.php";
if(TIME != 1){redirect_to("404.php");}
?>
<?php
if(isset($_GET["time"]))
{
$connection=databaseconnectivity_open();
$query = "select location_2 from user_preferences where `user_id`=\"{$_SESSION["user_id"]}\" LIMIT 1";
$result = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($result))
					{
					$location_2 = $row["location_2"];
					}
mysqli_free_result($result);
databaseconnectivity_close($connection);
$d = new DateTime("now", new DateTimeZone($location_2));
//echo $d->format(DateTime::W3C); 
//echo"<br>";
//echo $d->format('g:ia \o\n l jS F Y');
$rslt = array('hours' => $d->format('H'), 'minutes' => $d->format('i'), 'seconds' => $d->format('s'));
				

header('Content-Type: application/json; charset=utf-8');
echo json_encode($rslt);
die();
}
$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
echo"<form method=\"post\" action=\"time.php\"><select name=\"time_zone\">";
foreach($tzlist as $key)
{
echo"<option value=\"{$key}\">{$key}</option>";
}
echo"</select> <input type=\"submit\" name=\"submit\"></form>";
if(isset($_POST["submit"]))
{
$d = new DateTime("now", new DateTimeZone($_POST["time_zone"]));
echo $d->format(DateTime::W3C); 
echo"<br>";
echo $d->format('g:ia \o\n l jS F Y');
}

?>