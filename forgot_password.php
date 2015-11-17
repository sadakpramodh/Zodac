<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
$page_name="forgot_password.php";
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
if(isset($_POST["email_id"]))
	{
	$email_id=$_POST["email_id"];
	$errors = array();
	$connection = databaseconnectivity_open();
	$email_id = clean_user_input($connection, $email_id);
	$query = "SELECT * FROM `users` WHERE `email_id` = \"";
	$query .= $email_id;
	$query .= "\" LIMIT 1";
	$result = mysqli_query($connection, $query);
	if(mysqli_num_rows($result) > 0)
		{
		$key =rand(0,999999);
		$query = "UPDATE `users` SET `code`= \"{$key}\", `forget_password`= \"1\" where email_id=\"{$email_id}\"";
		if (mysqli_query($connection, $query)) 
			{
			$message   = "your password reset key is <b>{$key}";
			$message .= "<b>.<br> If you are not requested ignore it.";
			$mail_result = sendmail($email_id, $subject="Password reset request from {SITE_NAME}", $message);
			$_SESSION["message"]="Email sent!";
			$_SESSION["type"]= 1;
			$entercode=1;
			} 
		
		}
	else
		{	
		$_SESSION["message"]="Sorry No such Email address is registered";
		$_SESSION["type"]= 2;
		}
	mysqli_free_result($result);		
	databaseconnectivity_close($connection);
	
	}
	
if(isset($_POST["key"]))
	{
	$key=$_POST["key"];
	$email_id=$_POST["email_id_1"];
	$new_password=$_POST["new_password"];
	$errors = array();
	$connection = databaseconnectivity_open();
	$key = clean_user_input($connection, $key);
	$email_id = clean_user_input($connection, $email_id);
	$new_password = clean_user_input($connection, $new_password);
	$query = "SELECT * FROM `users` WHERE `email_id` = \"";
	$query .= $email_id;
	$query .= "\" AND `code` = \"{$key}\" LIMIT 1";

	$result = mysqli_query($connection, $query);
	if(mysqli_num_rows($result) > 0)
		{
		while ($row = mysqli_fetch_assoc($result)) 
			{
			$query = "UPDATE `users` SET `password`=\"{$new_password}\", `code`= \"0\", `forget_password`= \"0\" where email_id=\"{$email_id}\"";
			if (mysqli_query($connection, $query)) 
				{
				$_SESSION["message"]="Password successfully changed.<br>Please login";
				$_SESSION["type"]=1;
				mysqli_free_result($result);		
				databaseconnectivity_close($connection);
				redirect_to("index.php");
				}
			}	
		}
	else
		{
		$_SESSION["message"]="Invalid Code<br> For new code enter email address again";
		$_SESSION["type"]= 3;
		}
	mysqli_free_result($result);		
	databaseconnectivity_close($connection);
	}
?>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Forgot password</title>

  <?php 
	require_once("comns/head_section.php")
?>

</head>

<body class="gray-bg">

    <div class="passwordBox animated fadeInDown">
        <div class="row">
			<?php
			if($_SESSION["message"] != null)
				{
				display_message($_SESSION["message"], $_SESSION["type"]);
				}
			$_SESSION["message"] = null;
			?>
            <div class="col-md-12">
                <div class="ibox-content">

                    <h2 class="font-bold">Forgot password</h2>

                    <p>
                        Enter your email address.
                    </p>

                    <div class="row">

                        <div class="col-lg-12">
                            <form class="m-t" role="form" action="forgot_password.php" method="post">
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email address" name="email_id" required="">
                                </div>

                                <button type="submit" class="btn btn-primary block full-width m-b">Send Code</button>

                            </form>
                        </div>	
                    </div>
					<?php
					if(isset($entercode)&&(isset($email_id)))
					{
						?>
					 <hr>
					 <p>
                        Enter your reset code.
                    </p>
					<div class="row">

                        <div class="col-lg-12">
                            <form class="m-t" role="form" action="forgot_password.php" method="post" id="form">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Code" name="key" required="">
									<input type="password" class="form-control" placeholder="New password" name="new_password" required="">
									<input type="hidden" class="form-control" placeholder="email" name="email_id_1" required="" value="<?php echo $email_id; ?>">
                                </div>

                                <button type="submit" class="btn btn-primary block full-width m-b">Reset</button>

                            </form>
                        </div>
						
                    </div>
					<?php
					}
					?>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6 pull-left">
                <?php echo SITE_COPY_RIGHT_MESSAGE; ?>
            
               <small><?php echo SITE_COPY_RIGHT_YEAR; ?></small>
            </div>
        </div>
    </div>
	<?php 
		require_once("comns/body_section.php")
	?>
    <!-- Jquery Validate -->
    <script src="js/plugins/validate/jquery.validate.min.js"></script>

    <script>
         $(document).ready(function(){

             $("#form").validate({
                 rules: {
                     new_password: {
                         required: true,
                         minlength: 6,
						 maxlength: 15
						 
                     },
                     key: {
                         required: true
                         
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
