<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
$status = login_status();
if(!$status)
	{
	redirect_to("login.php");
	}
check_email_verified($_SESSION["email_id"]);
if($_SESSION["lockscreen_status"]!=1 && $_SESSION["lockscreen_status"]!=2)
{
	redirect_to("index.php");
}
if(isset($_POST["password"]))
{
	$connection=databaseconnectivity_open();
	$password=clean_user_input($connection,$_POST["password"]);
	$query="SELECT step2override from users where user_id=\"{$_SESSION["user_id"]}\"";
	$result = mysqli_query($connection, $query);
	while($row = mysqli_fetch_assoc($result))
	{
		if($password===$row["step2override"])
		{
			$_SESSION["lockscreen_status"]=0;
			$query = "SELECT `lastpage_viewed` from user_preferences where user_id=\"{$_SESSION["user_id"]}\" LIMIT 1";
				
				$result = mysqli_query($connection, $query);
				while($row = mysqli_fetch_assoc($result))
					{
					if($row["lastpage_viewed"])
						{
							databaseconnectivity_close($connection);
							redirect_to($row["lastpage_viewed"]);
						}
					}
			redirect_to("index.php");
		}
		else{
			echo "<font color=\"red\">Invalid pattern try again!</font>";
		}
	}
}
if($_SESSION["lockscreen_status"]==1)
{
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=320; user-scalable=no; initial-scale=1.0; maximum-scale=1.0" />
    <title>Pattern Lock</title>

    <link rel="stylesheet" type="text/css" href="pattern/_style/patternlock.css"/>
    <script src="pattern/_script/patternlock.js"></script>

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
     color:  #fff;
 }

.gbtnHollow:hover  {
     color:  #fff;
     background:  rgba(255, 255, 255, 0.2);
 }
</style>
</head>

<body>
    <form method="post" action="pattern.php">
        <h2>Please draw pattern</h2>
		
		<a href="index.php"><< Back</a>
		<br>
		<br>
		<br>
        <div>
            <input type="password" id="password" name="password" class="patternlock" />
            <input type="submit" value="login"/>
        </div>
    </form>

</body>
</html>

<?php
}
if($_SESSION["lockscreen_status"]==2)
{
	redirect_to("lockscreen.php");
}
?>
