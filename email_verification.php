<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
if(isset($_GET["code"])&&(isset($_GET["email_id"])))
	{
	$email_id=$_GET["email_id"];
	$code=$_GET["code"];
	$connection = databaseconnectivity_open();
	$email_id = clean_user_input($connection, $email_id);
	$code = clean_user_input($connection, $code);
	$query = "SELECT * FROM `users` WHERE `email_id` = \"";
	$query .= $email_id;
	$query .= "\" AND `code`=\"{$code}\" LIMIT 1";
	$result = mysqli_query($connection, $query);
	if(mysqli_num_rows($result) > 0)
		{
		$query = "UPDATE `users` SET `code`= \"0\" where email_id=\"{$email_id}\"";
		if (mysqli_query($connection, $query)) 
			{
			$_SESSION["message"]="Email is successfully verified. Now enjoy services :)";
			$_SESSION["type"]="0";
			redirect_to("index.php");
			} 
		
		}
	else
		{	
		$_SESSION["message"]="Sorry email address / verification code is invalid";
		$_SESSION["type"]= 2;
		}
	mysqli_free_result($result);		
	databaseconnectivity_close($connection);
	
	}

if(isset($_POST["email_id"]))
	{
	$email_id=$_POST["email_id"];
	$connection = databaseconnectivity_open();
	$email_id = clean_user_input($connection, $email_id);
	$query = "SELECT * FROM `users` WHERE `email_id` = \"";
	$query .= $email_id;
	$query .= "\" LIMIT 1";
	$result = mysqli_query($connection, $query);
	if(mysqli_num_rows($result) > 0)
		{
		$code=rand(1,999999);
		$query = "UPDATE `users` SET `code`= \"{$code}\" where email_id=\"{$email_id}\"";
		if (mysqli_query($connection, $query)) 
			{
			$heading="Dear user";
			$subject_1="Please verify your email address <br>Your verification code is : ".$code;
			$subject_2="We may need to send you critical information about our service and it is important that we have an accurate email address.";
			$button_link_name="confrim email";
			$button_link= SITE_URL."email_verification.php?code=".$code."&email_id=".$email_id;
			$message=email_body($heading,$subject_1,$subject_2,$button_link,$button_link_name);
			$subject = "You are successfully registered on {SITE_NAME}, Please verify your email address";
			sendmail($email_id, $subject, $message);
			$_SESSION["message"]="Verification code is sent to email address.";
			$_SESSION["type"]="1";
			}
		else
			{	
			$_SESSION["message"]="Sorry email address is not registered. You can register now";
			$_SESSION["type"]= 0;
			}
	mysqli_free_result($result);		
	databaseconnectivity_close($connection);
		}
	}
?>	

<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Confirm Email</title>

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

                    <h2 class="font-bold">Email Verification</h2>

                    <p>
                        Enter your email address and verification code.
                    </p>

                    <div class="row">

                        <div class="col-lg-12">
                            <form class="m-t" role="form" action="email_verification.php">
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email address" name="email_id" required="">
                                </div>
								<div class="form-group">
                                    <input type="text" class="form-control" placeholder="Verification key" name="code" required="">
                                </div>

                                <button type="submit" class="btn btn-primary block full-width m-b">Confrim</button>

                            </form>
                        </div>	
                    </div>
					<br>
					<br>
					 <hr>
					 <p>
                        Resend verification code.
                    </p>
					<div class="row">

                        <div class="col-lg-12">
                            <form class="m-t" role="form" action="email_verification.php" method="post" id="form">
                                <div class="form-group">
									<input type="email" class="form-control" placeholder="email" name="email_id" required="">
                                </div>

                                <button type="submit" class="btn btn-primary block full-width m-b">Send</button>

                            </form>
                        </div>
						
                    </div>
					
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
?>