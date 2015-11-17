<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="record_pattern.php";
?>
<?php
if(isset($_POST["record_pattern"]))
{
	$connection=databaseconnectivity_open();
	$step2override=clean_user_input($connection,$_POST["password"]);
	$currentpassword=clean_user_input($connection,$_POST["currentpassword"]);
	$errors = array();
	$result = check_length($step2override,13,2);
	if($result!=0)
	{
		$errors["step2override"]="pattern is too long or too short";
	}
	$password_in_db="";
	$query = "SELECT `password` FROM `users` WHERE `user_id` = \"";
	$query .= "{$_SESSION["user_id"]}\" LIMIT 1";
	$result = mysqli_query($connection, $query);
	while($row = mysqli_fetch_assoc($result))
	{
		$password_in_db=$row["password"];
		
	}
	mysqli_free_result($result);
	if($password_in_db===$currentpassword)
		{
			
		}
	else
	{
		$errors["currentpassword"]="Invalid current password";
	}
	if(empty($errors))
			{
				$query="update users set `step2override`=\"{$step2override}\" where user_id=\"{$_SESSION["user_id"]}\"";	
				if (mysqli_query($connection, $query)) 
				{
				$_SESSION["message"]="Step 2 override password recorded.";
				$_SESSION["type"]=1;
				databaseconnectivity_close($connection);
				redirect_to("change_account_settings.php");
				}
	databaseconnectivity_close($connection);		
			}
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=320; user-scalable=no; initial-scale=1.0; maximum-scale=1.0" />
    <title>Record Pattern</title>

    <link rel="stylesheet" type="text/css" href="pattern/_style/patternlock.css"/>
    <script src="pattern/_script/patternlock-record_pattern.js"></script>


<style>
    body{
        text-align:center;
        font-family:Arial, Helvetica, sans-serif;
    }
	.gbtnHollow  {
     background:  transparent;
     height:  38px;
     line-height:  40px;
     border:  2px solid white;
     display:  inline-block;
     float:  none;
     text-align:  center;
     width:  120px;
     padding:  0px!important;
     font-size:  14px;
     color:  #fhh;
 }

.gbtnHollow:hover  {
     color:  #fff;
     background:  rgba(255, 255, 255, 0.2);
 }
</style>
</head>

<body>

					<?php
						if(!empty($errors))
							{
							echo "<font color=\"red\"><strong>";
							echo " Please review following errors<br>";
							 foreach ($errors as $error) 
								{
								echo $error . "<br>";
								} 
							echo "</strong></font>";
							
							
							}
					?>
    <form method="post" onsubmit="return submitform()">
        <h2>Please record pattern</h2>
		<a href="index.php">Home</a>
		<br>
		<br>
        <div>
			<input type="submit" value="Record Pattern" class="gbtnHollow" name="record_pattern"/>
			<br>
			<br>
			<label>Current password</label>
			<input type="password" name="currentpassword" required>
			<br>
            <input type="password" id="password" name="password" class="patternlock" />
			<br>
            
        </div>
    </form>

</body>
</html>
