<?php
session_start();
echo"<pre>";
print_r($_SESSION);
echo"<pre>";
require_once('oauth/twitteroauth.php');
require_once('functions.php');

	if(isset($_POST["status"]))
	{
		$status = $_POST["status"];
		if(strlen($status)>=130)
		{
				$status = substr($status,0,130);
		}
		$twitter_access_token = $_SESSION['twitter_access_token'];
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $twitter_access_token['oauth_token'], $twitter_access_token['oauth_token_secret']);

		$connection->post('statuses/update', array('status' => "$status @sadakpramodh"));
		
		return $message = "Tweeted Sucessfully!!";
		
	}


	if(isset($_GET["redirect"]))
	{
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	 
		$twitter_request_token = $connection->getRequestToken(OAUTH_CALLBACK);

		$client_oauth_token = $twitter_token = $twitter_request_token['oauth_token'];
		$client_oauth_token_secret = $twitter_request_token['oauth_token_secret'];
		
		 echo $client_oauth_token;
		 echo $client_oauth_token_secret;
		 $_SESSION["twitter_x_oauth_token"] = $client_oauth_token;
		 $_SESSION["twitter_x_oauth_token_secret"] = $client_oauth_token_secret;
		 $data = $client_oauth_token . '<br>'.$client_oauth_token_secret;
		 $file = fopen("a.txt", 'w');
          fwrite($file,$data);
          fclose($file);
		switch ($connection->http_code) {
		  case 200:
			$url = $connection->getAuthorizeURL($twitter_token);
			header('Location: ' . $url); 
			break;
		  default:
			echo 'Could not connect to Twitter. Refresh the page or try again later.';
		}
		exit;
	}
	
   
    echo '<a href="./index2.php?redirect=true"><img src="./images/lighter.png" alt="Sign in with Twitter"/></a>';


?>