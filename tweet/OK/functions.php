<?php
session_start();
$_SESSION["user_id"] +=1;
$_SESSION["secure_id"] =1;
require_once('oauth/twitteroauth.php');
require_once('config.php');
//DATABASE CONFIGURATION	
define('DB_USER_NAME','root');//u742446948_zodac
define('DB_USER_PASSWORD','');//M2n68amse2@
define('DB_HOST','localhost');//mysql.hostinger.in
define('DB_NAME','test');//u742446948_zodac

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

function display_user_timeline()
{
	$twitter_access_token = $_SESSION['twitter_access_token'];
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $twitter_access_token['oauth_token'], $twitter_access_token['oauth_token_secret']);
	return $a = $connection->get("statuses/user_timeline", array("count" => 25, "exclude_replies" => true));
}
function display_home_timeline()
{
	$twitter_access_token = $_SESSION['twitter_access_token'];
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $twitter_access_token['oauth_token'], $twitter_access_token['oauth_token_secret']);
	return $a = $connection->get("statuses/home_timeline", array("count" => 25, "exclude_replies" => true));
}
?>

