<?php
require_once("configurations.php");

function site_init()
	{
	if($_SESSION["siteinit"]== null)
		{
		$_SESSION["email_id"]=null; 
		$_SESSION["username"]=null;
		$_SESSION["user_id"]=null;
		$_SESSION["message"]=null;
		$_SESSION["type"]=null;
		$_SESSION["siteinit"]=1;
		$_SESSION["lockscreen_status"]=null;
		$_SESSION["secure_id"]=null;
		}
	}
function login_status()
	{
	if($_SESSION["email_id"]!=null && $_SESSION["username"]!=null && $_SESSION["user_id"]!=null && $_SESSION["secure_id"]!=null)
		{
			return 1;
		}
	}
function session_check()
	{
	if($_SESSION["email_id"]==null || $_SESSION["username"]==null || $_SESSION["user_id"]==null || $_SESSION["secure_id"]==null)
		{
		redirect_to("login.php");
		}
	else if($_SESSION["lockscreen_status"]==1 || $_SESSION["lockscreen_status"]==2)
		{
		redirect_to("lockscreen.php");	
		}
	
	}
function display_message($message, $type=1)
	{
	if($type==0)
		{
		echo'<div class="alert alert-success alert-dismissable">';
			echo'<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>';
			echo $message;
			//echo'<a class="alert-link" href="#">Alert Link</a>.';
		echo'</div>';
		}
	else if($type==1)
		{
		echo'<div class="alert alert-info alert-dismissable">';
			echo'<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>';
			echo $message;
		
		echo'</div>';
		}
	else if($type==2)
		{
		echo'<div class="alert alert-warning alert-dismissable">';
			echo'<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>';
			echo $message;
			
		echo'</div>';
		}
	else if($type==3)
		{
		echo'<div class="alert alert-danger alert-dismissable">';
			echo'<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>';
			echo $message;
			
		echo'</div>';
		}

	}
function redirect_to($location)
	{
		header("Location: {$location}");
		exit;
	}
function error_reporting_mode()
	{
		return error_reporting(1);
	}
function sendmail($to, $subject, $message=null, $from=SITE_EMAIL_ID, $bcc=null, $cc=null)
	{

	
	if($subject=="" ||$subject==null)
{
$subject="Message from" . SITE_NAME;
}
// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= "To: {$to} \r\n";
	
	$headers .= "From: {$from}" . "\r\n";
	if($cc!=null)
		{
		$headers .= "Cc: {$cc}" . "\r\n";
		}
	if($bcc!=null)
		{
		$headers .= "Bcc: {$bcc}" . "\r\n";
		}
	// Mail it
	$result = mail($to, $subject, $message, $headers);
	return $result;
	}
function check_length($input,$maxlength,$minlength)
	{
	if(strlen($input)>$maxlength)
		{
		//input is max
		return 1;
		}
	elseif(strlen($input)<$minlength)
		{
		//input is min
		return -1;
		}
	else
		{
		//ok
		return 0;
		}
	}
function clean_user_input($connection, $input)
	{
	return mysqli_real_escape_string($connection, $input);
	}
function databaseconnectivity_open()
	{
	$connection = mysqli_connect(DB_HOST, DB_USER_NAME, DB_USER_PASSWORD, DB_NAME);
	if(mysqli_connect_errno())
		{
			echo "connection fail";
		}
	return $connection;
	}

function databaseconnectivity_close($connection)
	{
		
		if(isset($connection))
			{
			mysqli_close($connection);
			}
		
	}
function email_body($heading="Hey",$subject_1,$subject_2=null,$button_link=null,$button_link_name=null)
	{
$message='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta name="viewport" content="width=device-width" />
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<!--link href="styles.css" media="all" rel="stylesheet" type="text/css" /-->
					<style>
					/* -------------------------------------
					GLOBAL
					A very basic CSS reset
				------------------------------------- */
				* {
					margin: 0;
					padding: 0;
					font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
					box-sizing: border-box;
					font-size: 14px;
				}

				img {
					max-width: 100%;
				}

				body {
					-webkit-font-smoothing: antialiased;
					-webkit-text-size-adjust: none;
					width: 100% !important;
					height: 100%;
					line-height: 1.6;
				}

				/* Lets make sure all tables have defaults */
				table td {
					vertical-align: top;
				}

				/* -------------------------------------
					BODY & CONTAINER
				------------------------------------- */
				body {
					background-color: #f6f6f6;
				}

				.body-wrap {
					background-color: #f6f6f6;
					width: 100%;
				}

				.container {
					display: block !important;
					max-width: 600px !important;
					margin: 0 auto !important;
					/* makes it centered */
					clear: both !important;
				}

				.content {
					max-width: 600px;
					margin: 0 auto;
					display: block;
					padding: 20px;
				}

				/* -------------------------------------
					HEADER, FOOTER, MAIN
				------------------------------------- */
				.main {
					background: #fff;
					border: 1px solid #e9e9e9;
					border-radius: 3px;
				}

				.content-wrap {
					padding: 20px;
				}

				.content-block {
					padding: 0 0 20px;
				}

				.header {
					width: 100%;
					margin-bottom: 20px;
				}

				.footer {
					width: 100%;
					clear: both;
					color: #999;
					padding: 20px;
				}
				.footer a {
					color: #999;
				}
				.footer p, .footer a, .footer unsubscribe, .footer td {
					font-size: 12px;
				}

				/* -------------------------------------
					TYPOGRAPHY
				------------------------------------- */
				h1, h2, h3 {
					font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
					color: #000;
					margin: 40px 0 0;
					line-height: 1.2;
					font-weight: 400;
				}

				h1 {
					font-size: 32px;
					font-weight: 500;
				}

				h2 {
					font-size: 24px;
				}

				h3 {
					font-size: 18px;
				}

				h4 {
					font-size: 14px;
					font-weight: 600;
				}

				p, ul, ol {
					margin-bottom: 10px;
					font-weight: normal;
				}
				p li, ul li, ol li {
					margin-left: 5px;
					list-style-position: inside;
				}

				/* -------------------------------------
					LINKS & BUTTONS
				------------------------------------- */
				a {
					color: #1ab394;
					text-decoration: underline;
				}

				.btn-primary {
					text-decoration: none;
					color: #FFF;
					background-color: #1ab394;
					border: solid #1ab394;
					border-width: 5px 10px;
					line-height: 2;
					font-weight: bold;
					text-align: center;
					cursor: pointer;
					display: inline-block;
					border-radius: 5px;
					text-transform: capitalize;
				}

				/* -------------------------------------
					OTHER STYLES THAT MIGHT BE USEFUL
				------------------------------------- */
				.last {
					margin-bottom: 0;
				}

				.first {
					margin-top: 0;
				}

				.aligncenter {
					text-align: center;
				}

				.alignright {
					text-align: right;
				}

				.alignleft {
					text-align: left;
				}

				.clear {
					clear: both;
				}

				/* -------------------------------------
					ALERTS
					Change the class depending on warning email, good email or bad email
				------------------------------------- */
				.alert {
					font-size: 16px;
					color: #fff;
					font-weight: 500;
					padding: 20px;
					text-align: center;
					border-radius: 3px 3px 0 0;
				}
				.alert a {
					color: #fff;
					text-decoration: none;
					font-weight: 500;
					font-size: 16px;
				}
				.alert.alert-warning {
					background: #f8ac59;
				}
				.alert.alert-bad {
					background: #ed5565;
				}
				.alert.alert-good {
					background: #1ab394;
				}

				/* -------------------------------------
					INVOICE
					Styles for the billing table
				------------------------------------- */
				.invoice {
					margin: 40px auto;
					text-align: left;
					width: 80%;
				}
				.invoice td {
					padding: 5px 0;
				}
				.invoice .invoice-items {
					width: 100%;
				}
				.invoice .invoice-items td {
					border-top: #eee 1px solid;
				}
				.invoice .invoice-items .total td {
					border-top: 2px solid #333;
					border-bottom: 2px solid #333;
					font-weight: 700;
				}

				/* -------------------------------------
					RESPONSIVE AND MOBILE FRIENDLY STYLES
				------------------------------------- */
				@media only screen and (max-width: 640px) {
					h1, h2, h3, h4 {
						font-weight: 600 !important;
						margin: 20px 0 5px !important;
					}

					h1 {
						font-size: 22px !important;
					}

					h2 {
						font-size: 18px !important;
					}

					h3 {
						font-size: 16px !important;
					}

					.container {
						width: 100% !important;
					}

					.content, .content-wrap {
						padding: 10px !important;
					}

					.invoice {
						width: 100% !important;
					}
				}

					</style>
				</head>

				<body>

				<table class="body-wrap">
					<tr>
						<td></td>
						<td class="container" width="600">
							<div class="content">
								<table class="main" width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td class="content-wrap">
											<table  cellpadding="0" cellspacing="0">
												<tr>
													<td class="alert alert-good">'.
														 SITE_NAME .'
													</td>
												</tr>
												<tr>
													<td class="content-block"> <h3>';
                                        $message .= $heading; 
										$message .='</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        ';
										$message .= $subject_1;
										$message .='
                                    </td>
                                </tr>';
								if($subject_2!=null)
								{
                                $message .='<tr>
                                    <td class="content-block">';
									
                                        $message .= $subject_2;
                                    $message .='</td>
                                </tr>';
								}
								if($button_link!=null)
								{
                                $message .='<tr>
                                    <td class="content-block aligncenter">
                                        <a href="';
										$message.= $button_link;
										$message.='" class="btn-primary">';
										$message.= $button_link_name;
										$message.='</a>
                                    </td>
                                </tr>';
								}
								$message .='
                              </table>
                        </td>
                    </tr>
                </table>
                <div class="footer">
                    <table width="100%">
                        <tr>
                            <td class="aligncenter content-block">Follow <a href="'. TWITTER_URL .'">' . SITE_NAME .'</a> on Twitter.</td>
                        </tr>
                    </table>
                </div></div>
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>';
return $message;
	}
function check_email_verified($email_id)
	{
	$connection = databaseconnectivity_open();
	$email_id = clean_user_input($connection, $_SESSION["email_id"]);
	$query = "SELECT * FROM `users` WHERE `email_id` = \"";
	$query .= $email_id;
	$query .= "\" LIMIT 1";
	$result = mysqli_query($connection, $query);
	if(mysqli_num_rows($result) > 0)
		{
		while ($row = mysqli_fetch_assoc($result)) 
		{
		if($row["code"]!=0)
			{
				mysqli_free_result($result);		
				databaseconnectivity_close($connection);
				redirect_to("email_verification.php");
			}
		
		}
		}
		mysqli_free_result($result);		
		databaseconnectivity_close($connection);
	}
function connect_user_email()
{
	if($connect_user_email_connection=imap_open( "{" . EMAIL_HOST . ":" . EMAIL_PORT . "}INBOX", EMAIL_USERNAME, EMAIL_PASSWORD ))
		{
			return $connect_user_email_connection;
		}
	else
		{
			return 0;
		}
}
?>
