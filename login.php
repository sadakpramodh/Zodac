<?php
require_once("configurations.php");
require_once("functions.php");
$page_name="login.php";
session_start();
if(ALLOW_LOGINS=="0")
	{
	$_SESSION["message"]="Sorry logins are not allowed this time. Keep visiting.";
	$_SESSION["type"]=1;
	redirect_to("500.php");
	}
if(!isset($_SESSION["siteinit"])||($_SESSION["siteinit"]==null))
	{
	$_SESSION["siteinit"]=null;
	site_init();
	}

$status = login_status();
if($status)
	{
	redirect_to("index.php");
	}
if(isset($_POST["login"]))
	{
	$email_id=$_POST["email_id"];
	$password=$_POST["password"];
	$errors = array();
	$connection = databaseconnectivity_open();
	$email_id = clean_user_input($connection, $email_id);
	$password = clean_user_input($connection, $password);
	$query = "SELECT * FROM `users` WHERE `email_id` = \"";
	$query .= $email_id;
	$query .= "\" AND `password` = \"";
	$query .= $password."\" LIMIT 1";
	$result = mysqli_query($connection, $query);
	if(mysqli_num_rows($result) > 0)
		{
		while ($row = mysqli_fetch_assoc($result)) 
		{
		$_SESSION["username"]=$row["username"];
		$_SESSION["user_id"]=$row["user_id"];
		$_SESSION["email_id"]=$row["email_id"];
		$_SESSION["address"]=$row["address"];
		$_SESSION["country"]=$row["country"];
		$_SESSION["city"]=$row["city"];
		$_SESSION["secure_id"] = $row["secure_id"];
		mysqli_free_result($result);


		$key=rand(1,999999);
		$query = "UPDATE users set step_two_password=\"{$key}\" where user_id=\"{$_SESSION["user_id"]}\"";
	
		if (mysqli_query($connection, $query)) 
		{
			//custom message with verification key to user
			$heading="Dear {$_SESSION["username"]}";
			$subject_1="Your step two verification code is : ".$key;
			$subject_2="<br>If it is not you login now and change password.";

			$message=email_body($heading,$subject_1,$subject_2);
			$subject = "Your step to verification key from ".SITE_NAME;
				$email1=$_SESSION["email_id"];
			sendmail($email1, $subject, $message);
			$_SESSION["message"]="Step two verification key sent!";
			$_SESSION["type"]=1;
		}
		databaseconnectivity_close($connection);
		$_SESSION["lockscreen_status"]=1;
		redirect_to("index.php");
		}
		}
	else
		{	
		$_SESSION["message"]="Invalid email / password";
		$_SESSION["type"]= 3;
		}
	mysqli_free_result($result);		
	databaseconnectivity_close($connection);
	}
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Login</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name"><?php echo SITE_LOGO_NAME; ?></h1>

            </div>
			<?php
			if($_SESSION["message"] != null)
				{
				display_message($_SESSION["message"], $_SESSION["type"]);
				}
			$_SESSION["message"] = null;
			?>
			
            <h3>Welcome to <?php echo SITE_NAME; ?></h3>
           
            <p>Login</p>
            <form class="m-t" role="form" action="login.php" id="form" method="post">
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" name="email_id">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" name="password">
					
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b" name="login">Login</button>

                <a href="forgot_password.php"><small>Forgot password?</small></a>
                <p class="text-muted text-center"><small>Do not have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="register.php">Create an account</a>
            </form>
            <p class="m-t"> <small><?php echo SITE_COPY_RIGHT_MESSAGE; ?></small> <small><?php echo SITE_COPY_RIGHT_YEAR; ?></small></p>
        </div>
    </div>

     <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- Jquery Validate -->
    <script src="js/plugins/validate/jquery.validate.min.js"></script>

    <script>
         $(document).ready(function(){

             $("#form").validate({
                 rules: {
                     password: {
                         required: true,
                         minlength: 5
						 
                     },
                     username: {
                         required: true,
                         minlength: 6
                     },
                     max: {
                         required: true,
                         maxlength: 4
                     }
                 }
             });
        });
    </script>
</body>
</html>

