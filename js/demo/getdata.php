<?php
require_once"../../functions.php";
require_once"../../connection.php";
databaseconnectivity_open();
global $connection;
$query = "select * from marklists order by number asc";
$result = mysqli_query($connection, $query);
$a=array();
$b=array();
while($row = mysqli_fetch_assoc($result))
	{
		
		$a['year']=$row["number"];
		$percentage=$row["awarded_marks"] / $row["maximum_marks"]*100;
		$a['value']=$percentage;
	
							
	}
while($row = mysqli_fetch_assoc($result))
	{
		
		$b['year']=$row["number"];
		$percentage=$row["awarded_marks"] / $row["maximum_marks"]*100;
		$b['value']=$percentage;
	
							
	}
mysqli_free_result($result);
databaseconnectivity_close();


?>