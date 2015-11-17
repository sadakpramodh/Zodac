<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
$page_name="register.php";
if(ALLOW_REGISTRATIONS=="0")
	{
	$_SESSION["message"]="Sorry Registrations are not allowed this time. Keep visiting.";
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
if(isset($_POST["register"]))
	{
		$username = $_POST["username"];
		$password = $_POST["password"];
		$email_id = $_POST["email_id"];
		
		$errors = array();
		$result = check_length($email_id,30,5);
		if($result!=0)
		{
			$errors["email_id"]="Email address length should between 5 to 30 characters";
		}
		//$result!=0?$errors["username"] = "Username too short or too long !":"ok";
    		
		$result = check_length($username,16,5);
		if($result!=0)
		{
			$errors["username"]="username length should between 5 to 15 characters";
		}
		
		$result = check_length($password,15,5);
		if($result!=0)
		{
			$errors["password"]="password length should between 6 to 15 characters";
		}
		
		$connection=databaseconnectivity_open();
		$email_id = clean_user_input($connection, $email_id);
		$query = "SELECT `email_id` FROM `users` WHERE `email_id` = \"";
		$query .= $email_id;
		$query .= "\" LIMIT 1";
		
		$result = mysqli_query($connection, $query);
		if(mysqli_num_rows($result) > 0)
		{
			$errors["email_id"]="Email address is already taken.Please enter another email address";
		}	
		databaseconnectivity_close($connection);
		if(empty($errors))
			{
			$connection=databaseconnectivity_open();
			$email1 = $email_id;
			$username = mysqli_real_escape_string($connection, $username);
			$password = mysqli_real_escape_string($connection, $password);
			

			$query = "INSERT INTO `users`(`username`, `password`, `email_id`, `status`, `code`, `forget_password`, `step2override`) VALUES (\"";
			$query .= $username ."\",\"";
			$query .= $password ."\",\"";
			$query .= $email_id ."\",\"";
			$query .= "1\",\"";
			$verificationkey = rand(1,99999);
			$query .= $verificationkey ."\",\"0\",\"0\")";
			if (mysqli_query($connection, $query)) 
				{
					
				$query="SELECT * from users where email_id=\"{$email1}\" LIMIT 1";
				
				$result = mysqli_query($connection, $query);
				while($row = mysqli_fetch_assoc($result))
					{
						$user_id = $row["user_id"];
						$secure_id = uniqid(SITE_NAME.'_') . "_" . $user_id;
						$query = "UPDATE `users` set secure_id=\"{$secure_id}\" where user_id=\"{$user_id}\"";
						mysqli_query($connection, $query);
						$query="INSERT INTO `contacts`(`profile_id`,`contact_name`,`email_id_1`,`user_id`) VALUES(\"{$user_id}\",\"{$username}\",\"{$email1}\",\"{$user_id}\")";
						
						if (mysqli_query($connection, $query)) 
						{
							$heading="Dear {$username}";
							$subject_1="You are successfully registered on {SITE_NAME}, Please verify your email address <br>Your verification code is : ".$verificationkey;
							$subject_2="We may need to send you critical information about our service and it is important that we have an accurate email address.";
							$button_link_name="confirm email";
							$button_link= SITE_URL."email_verification.php?code=".$verificationkey."&email_id=".$email_id;
							$message=email_body($heading,$subject_1,$subject_2,$button_link,$button_link_name);
							$subject = "You are successfully registered on {SITE_NAME}, Please verify your email address";
							
							sendmail($email1, $subject, $message);
							$_SESSION["message"]="Registration successfully done,<br>Please login";
							$_SESSION["type"]=0;
							$query="INSERT INTO `user_preferences` (`user_id`, `collapse_menu`, `fixed_sidebar`, `top_navbar`, `boxed_layout`, `fixed_footer`, `skins`, `location_1`, `location_2`, `extras`, `lastpage_viewed`, `lockscreen_timeout`, `notification_position`) VALUES ";
							$query .= "(\"{$user_id}\",\"off\",\"off\",\"off\",\"off\",\"off\",\"blue\",\"India\",\"Asia/India\",\"-\",\"index.php\",\"50000\",\"toast-top-right\")";
							mysqli_query($connection, $query);
							$query = "INSERT INTO `twitter` (`user_id`, `secure_id`, `oauth_token`, `oauth_token_secret`, `twitter_user_id`,`screen_name`,`x_auth_expires`,`twitter_status`) VALUES (";
							$query .= "\"{$user_id}\",\"{$secure_id}\",\"\",\"\",\"\",\"\",\"\",\"0\")";
							if (mysqli_query($connection, $query)) 
							{
								//OK
							}
							databaseconnectivity_close($connection);
							redirect_to("login.php");
						}
					}
				
					
				//code is here in previous
				} 
			databaseconnectivity_close($connection);
			}
	}
		
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Register</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen   animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name"><?php echo SITE_LOGO_NAME; ?></h1>

            </div>
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
            <h3>Register to <?php echo SITE_NAME; ?></h3>

            <p>Create account to see it in action.</p>
            <form class="m-t" role="form" action="register.php" method="POST" id="form">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Name" name="username">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" required="" name="email_id">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" name="password">
					<input type="hidden" value="<?php echo $_SESSION["secure_token"]; ?>" name="secure_token" >
					<input type="hidden" value="<?php echo date("d-m-Y")." ".date("h:i:s a"); ?>" name="date_and_time" >
                </div>
                <div class="form-group">
                        <label>By clicking register you are Agreeing the terms and policy </label>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b" name="register" value="Register">Register</button>

                <p class="text-muted text-center"><small>Already have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="login.php">Login</a>
            </form>
            <p class="m-t"> <small><?php echo SITE_COPY_RIGHT_MESSAGE; ?> <?php echo SITE_COPY_RIGHT_YEAR; ?></small> </p>
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
</php>
