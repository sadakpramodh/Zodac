<?php
/**
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */
session_start();
require_once('oauth/twitteroauth.php');
require_once('config.php');
require_once("functions.php");
$client_oauth_token = $_SESSION["twitter_x_oauth_token"];
$clent_oauth_token_secret = $_SESSION["twitter_x_oauth_token_secret"];

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $client_oauth_token, $clent_oauth_token_secret);

$twitter_access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
//save new access token array in session
print_r($_SESSION);
echo"<pre>";
 print_r($twitter_access_token);
echo"</pre>";
/*
$twitter_access_token["oauth_token"];
$twitter_access_token["oauth_token_secret"];
$twitter_access_token["user_id"];
$twitter_access_token["screen_name"];
$twitter_access_token["x_auth_expires"];
*/
$data = $twitter_access_token["oauth_token"] .
$twitter_access_token["oauth_token_secret"] .
$twitter_access_token["user_id"] .
$twitter_access_token["screen_name"] .
$twitter_access_token["x_auth_expires"];

$file = fopen("b.txt", 'w');
fwrite($file,$data);
fclose($file);
unset($_SESSION["twitter_x_oauth_token"]);
unset($_SESSION["twitter_x_oauth_token_secret"]);
print_r($_SESSION);

if (200 == $connection->http_code) {
$twitter_status = $_SESSION['twitter_status'] = 'verified';
 header('Location: ./index.php');
} else {
  header('Location: ./destroysessions.php');
}
