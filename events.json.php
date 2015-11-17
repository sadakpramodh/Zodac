<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
?>

<?php
$connection=databaseconnectivity_open();
$query="SELECT * FROM schedule where user_id={$_SESSION["user_id"]} AND `delete`=\"false\"";
$result = mysqli_query($connection, $query);
$out = array();
while($row = mysqli_fetch_assoc($result))
{
	
	$time = $row["date"];
	$time .= $row["time"];
	$out[]=array(
    'id' => $row["schedule_id"],
    'title' => $row["message"],
	'url' => "todayschedule.php?schedule_id=".$row["schedule_id"],
	'class' => "event-warning",
	
	'start' => strtotime($time)*1000,
	'end' => strtotime($time)*1000
	);
}
$query="SELECT * FROM contacts where user_id={$_SESSION["user_id"]} AND `delete`=\"false\"";
$result = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($result))
{
	
	$time = $row["birthday"];
	$a=explode( '-', $time );
	$te=date("Y");
	$te .=$a[1] . $a[2];
	$out[]=array(
    'id' => $row["contact_id"],
    'title' => $row["contact_name"] ."'s Birthday",
	'url' => "profile.php?contact_id=".$row["contact_id"],
	'class' => "event-important",
	
	'start' => strtotime($te)*1000,
	'end' => strtotime($te)*1000+10
	);
}
$query="SELECT * FROM calendar where user_id={$_SESSION["user_id"]} AND `delete`=\"false\"";
$result = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($result))
{
	
	$time = $row["date"];
	$time .= $row["time"];
	$out[]=array(
    'id' => $row["calendar_id"],
    'title' => $row["title"],
	'url' => "calendar.php?calendar_id=".$row["calendar_id"],
	'class' => "event-success",
	
	'start' => strtotime($time)*1000,
	'end' => strtotime($time)*1000+10
	);
}

mysqli_free_result($result);
databaseconnectivity_close($connection);
echo json_encode(array('success' => 1, 'result' => $out));
exit;
?>
