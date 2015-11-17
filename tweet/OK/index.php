<?php
session_start();
print_r($_SESSION);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>How to post tweet on Twitter with PHP | PGPGang.com</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <style type="text/css">
      #status
    {
        width: 357px;
        height: 28px;
        font-size: 15px;
    }
    img {border-width: 0}
      * {font-family:'Lucida Grande', sans-serif;}
    </style>
  </head>
  <body>
    <div>
      <h2>How to post tweet on Twitter with PHP example.&nbsp;&nbsp;&nbsp;=> <a href="http://www.phpgang.com/">Home</a> | <a href="http://demo.phpgang.com/">More Demos</a></h2>
<?php
require_once('oauth/twitteroauth.php');
require_once('config.php');
if(isset($_POST["status"]))
{
    $status = $_POST["status"];
    if(strlen($status)>=130)
    {
            $status = substr($status,0,130);
    }
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

    $connection->post('statuses/update', array('status' => "$status @sadakpramodh"));
	
    $message = "Tweeted Sucessfully!!";
	
}
if(isset($_GET["redirect"]))
{
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
 
    $request_token = $connection->getRequestToken(OAUTH_CALLBACK);

    $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
     
    switch ($connection->http_code) {
      case 200:
        $url = $connection->getAuthorizeURL($token);
        header('Location: ' . $url); 
        break;
      default:
        echo 'Could not connect to Twitter. Refresh the page or try again later.';
    }
    exit;
}
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    
    echo '<a href="./index.php?redirect=true"><img src="./images/lighter.png" alt="Sign in with Twitter"/></a>';
}
else
{
    echo "<a href='destroysessions.php'>Logout</a><br>";
    echo '<br>'.$message.'<br>
    <form action="index.php" method="post">
        <input type="text" name="status" id="status" placeholder="Write a comment...."><input type="submit" value="Post On My Wall!" style="padding: 5px;">
    </form>';
	 $access_token = $_SESSION['access_token'];
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
	$a = $connection->get("statuses/home_timeline", array("count" => 25, "exclude_replies" => true));
		echo"<br><pre>";
	print_r($a);
	echo"</pre><br><pre>";
	$a = $connection->get("statuses/user_timeline", array("count" => 25, "exclude_replies" => true));
	print_r($a);
		echo"<br></pre>";
}
?>

</body>
</html>