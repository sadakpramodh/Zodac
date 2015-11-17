<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
$page_name="lockscreen.php";
$status = login_status();
if(!$status)
	{
	redirect_to("login.php");
	}
check_email_verified($_SESSION["email_id"]);
?>
<?php
if(isset($_GET["lock"]))
{
	if($_GET["lock"]=="true")
	{
		$_SESSION["lockscreen_status"]=2;
		redirect_to("lockscreen.php");
	}
}

?>
<?php

//$_SESSION["lockscreen_status"]=2;
//$_SESSION["lockscreen_status"]=
//0 --everything OK
//1 --step two verification by email
//2 --due to no response from long-time
if($_SESSION["lockscreen_status"]!=1 && $_SESSION["lockscreen_status"]!=2)
{
	redirect_to("index.php");
}
if(isset($_GET["resendcode"]))
{
	$connection=databaseconnectivity_open();
	$key=rand(1,999999);
	$query = "UPDATE users set step_two_password=\"{$key}\" where user_id=\"{$_SESSION["user_id"]}\"";
	
	if (mysqli_query($connection, $query)) 
	{
		//custom message with verification key to user
		$heading="Dear {$_SESSION["username"]}";
		$subject_1="Your step two verification code is : ".$key;
		$subject_2="<br>If it is not you login now and change password.";

		$message=email_body($heading,$subject_1,$subject_2);
		$subject = "Your step two verification key from ".SITE_NAME;
			
		$email1=$_SESSION["email_id"];
		
		sendmail($email1, $subject, $message);
		$_SESSION["message"]="Step two verification key sent!";
		$_SESSION["type"]=1;
	}
	databaseconnectivity_close($connection);
			
}


if(isset($_POST["submit"]))
{
	
	$connection=databaseconnectivity_open();
	$password= clean_user_input($connection,$_POST["password"]);
	if($_SESSION["lockscreen_status"]==1)
	{
		$query = "SELECT `step_two_password` from users where user_id=\"{$_SESSION["user_id"]}\" LIMIT 1";
		//echo $query;
		$result = mysqli_query($connection, $query);
		while($row = mysqli_fetch_assoc($result))
		{
			$step_two_password = $row["step_two_password"];
			
			if($password == $step_two_password)
			{
				$_SESSION["lockscreen_status"]=0;
				mysqli_free_result($result);
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
				databaseconnectivity_close($connection);
				redirect_to("index.php");
			}
			else
			{
				$_SESSION["message"]="Invalid step two verification code.<br>Try Again";
				$_SESSION["type"]=3;
				
			}
		}
			
	}
	else if($_SESSION["lockscreen_status"]==2)
	{
		$query = "SELECT `password` from users where user_id=\"{$_SESSION["user_id"]}\" LIMIT 1";
		$result = mysqli_query($connection, $query);
		while($row = mysqli_fetch_assoc($result))
		{
			
			if($password == $row["password"])
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
				databaseconnectivity_close($connection);
				redirect_to("index.php");
				
			}
			else
			{
				$_SESSION["message"]="Invalid password.<br>Try Again";
				$_SESSION["type"]=3;
			}
		}
	}
	else
	{
		redirect_to("index.php");
	}
	databaseconnectivity_close($connection);
}
?>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | <?php 
	
		if($_SESSION["lockscreen_status"]==1)
		{
			echo "step two verification"; 
		}
		if($_SESSION["lockscreen_status"]==2)
		{
			echo "Re-enter password"; 
		}
	?>
	
	</title>

  <?php 
	require_once("comns/head_section.php")
?>
</head>

<body class="gray-bg">

						<?php
						if($_SESSION["message"] != null)
							{
							display_message($_SESSION["message"], $_SESSION["type"]);
							}
						$_SESSION["message"] = null;
						?>

<div class="lock-word animated fadeInDown">
    <span class="first-word">LOCKED</span><span>SCREEN</span>
</div>
    <div class="middle-box text-center lockscreen animated fadeInDown">
        <div>
            <div class="m-b-md">
			
			<?php
			
			$connection=databaseconnectivity_open();
			$query = "select * from contacts where profile_id=\"{$_SESSION["user_id"]}\" AND user_id=\"{$_SESSION["user_id"]}\" LIMIT 1";
			$result = mysqli_query($connection, $query);
			while($row = mysqli_fetch_assoc($result))
			{
				$contact_id = $row["contact_id"];
				$contact_url = $row["contact_url"];
			}
			mysqli_free_result($result);
			databaseconnectivity_close($connection);
			
			if (file_exists($contact_url)) {
								echo '<img alt="image"  class="img-circle circle-border" src="'.$contact_url.'" width="120" height="120">';
								}
								else
								{
                                echo'<img alt="image" class="img-circle circle-border" src="img/a2.jpg">';
								}
			?>
			
            </div>
			<br>
            <h3><?php echo $_SESSION["username"]; ?></h3>
			<?php
				if($_SESSION["lockscreen_status"]==2)
				{
					echo'<p>Your are in lock screen. Main app was shut down and you need to enter your password to go back to app.</p>';
				}
				else if($_SESSION["lockscreen_status"]==1)
				{
					echo'<p>Your are in Step two verification. Please enter code that sent to your registered email.</p>';
				}
			
            ?>
            <form class="m-t" role="form" action="lockscreen.php" method="POST">
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="******" required="" name="password">
                </div>
                <button type="submit" name="submit" class="btn btn-primary block full-width">Unlock</button>
				</form>
				<?php
				if($_SESSION["lockscreen_status"]==1)
				{
					echo'<a href="lockscreen.php?resendcode"><button class="btn btn-warning block full-width">Resend code</button></a>';
					$connection=databaseconnectivity_open();
					$query="select step2override from users where user_id=\"{$_SESSION["user_id"]}\" LIMIT 1";
					
					$result = mysqli_query($connection, $query);
					while($row = mysqli_fetch_assoc($result))
					{
						$step2override=$row["step2override"];
					}
					if(!$step2override==0)
					{
						echo'<br><a href="pattern.php"><button class="btn btn-outline btn-danger block full-width">override step 2 verification</button></a>';
					}
					mysqli_free_result($result);
					databaseconnectivity_close($connection);
								
					
				}
				
				if($_SESSION["lockscreen_status"]==2)
				{
					echo'<a href="forgot_password.php"><button class="btn btn-danger block full-width">Forgot password</button></a>';
				}
				?>
				<br>
				<div class="form-group">
				<a href="logout.php"><button class="btn btn-outline btn-warning block full-width">Logout</button></a>
				</div>
        </div>
		
		
		
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>
</php>
