<?php
/**
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */
session_start();
//print_r($_SESSION);
require_once('oauth/twitteroauth.php');
include(dirname(__FILE__)."/../configurations.php");
include(dirname(__FILE__)."/../functions.php");
$client_oauth_token = $_SESSION["twitter_x_oauth_token"];
$clent_oauth_token_secret = $_SESSION["twitter_x_oauth_token_secret"];

$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $client_oauth_token, $clent_oauth_token_secret);

$twitter_access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
//save new access token array in session


$oauth_token = $twitter_access_token["oauth_token"];
$oauth_token_secret = $twitter_access_token["oauth_token_secret"];
$user_id = $twitter_access_token["user_id"];
$screen_name = $twitter_access_token["screen_name"];
$x_auth_expires = $twitter_access_token["x_auth_expires"];

$data = $twitter_access_token["oauth_token"] .
$twitter_access_token["oauth_token_secret"] .
$twitter_access_token["user_id"] .
$twitter_access_token["screen_name"] .
$twitter_access_token["x_auth_expires"];
/*
echo"<pre>";
print_r($twitter_access_token);
echo"</pre>";
echo $data;

$query = "INSERT INTO `twitter` (`user_id`, `secure_id`, `oauth_token`, `oauth_token_secret`, `twitter_user_id`,`screen_name`,`x_auth_expires`,`twitter_status`) VALUES (";
	$query .= "\"{$_SESSION["user_id"]}\",\"{$secure_id}\",\"{$oauth_token}\",\"{$oauth_token_secret}\",\"{$user_id}\",\"{$screen_name}\",\"{$x_auth_expires}\",\"verified\")";
	if (mysqli_query($db_connection, $query)) 
	{
		//OK
	}
*/	

$db_connection=databaseconnectivity_open();

	$query = "UPDATE `twitter` SET `secure_id`=\"{$_SESSION["secure_id"]}\", `oauth_token` = \"{$oauth_token}\", `oauth_token_secret` = \"{$oauth_token_secret}\", `twitter_user_id` = \"{$user_id}\",`screen_name` = \"{$screen_name}\",`x_auth_expires` = \"{$x_auth_expires}\",`twitter_status` = \"verified\" where `user_id` = \"{$_SESSION["user_id"]}\"";
	if (mysqli_query($db_connection, $query)) 
	{
		//OK
	}
	//echo $query;
	databaseconnectivity_close($db_connection);
unset($_SESSION["twitter_x_oauth_token"]);
unset($_SESSION["twitter_x_oauth_token_secret"]);

if (200 == $connection->http_code) {
	$twitter_status = $_SESSION['twitter_status'] = 'verified';
	$url = SITE_URL . "/twitter.php";
	redirect_to($url);
} else {
  echo"something wrong";
}
