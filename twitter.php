<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="twitter.php";
if(TWITTER != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>
<?php
require_once('tweet/oauth/twitteroauth.php');
function relativeTime($time)
{
    $delta = strtotime('+2 hours') - strtotime($time);
    if ($delta < 2 * MINUTE) {
        return "1 min ago";
    }
    if ($delta < 45 * MINUTE) {
        return floor($delta / MINUTE) . " min ago";
    }
    if ($delta < 90 * MINUTE) {
        return "1 hour ago";
    }
    if ($delta < 24 * HOUR) {
        return floor($delta / HOUR) . " hours ago";
    }
    if ($delta < 48 * HOUR) {
        return "yesterday";
    }
    if ($delta < 30 * DAY) {
        return floor($delta / DAY) . " days ago";
    }
    if ($delta < 12 * MONTH) {
        $months = floor($delta / DAY / 30);
        return $months <= 1 ? "1 month ago" : $months . " months ago";
    } else {
        $years = floor($delta / DAY / 365);
        return $years <= 1 ? "1 year ago" : $years . " years ago";
    }
}
 
if(isset($_GET["redirect"]))
	{
		$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
	 
		$twitter_request_token = $connection->getRequestToken(TWITTER_OAUTH_CALLBACK);

		$client_oauth_token = $twitter_token = $twitter_request_token['oauth_token'];
		$client_oauth_token_secret = $twitter_request_token['oauth_token_secret'];
		
		 $_SESSION["twitter_x_oauth_token"] = $client_oauth_token;
		 $_SESSION["twitter_x_oauth_token_secret"] = $client_oauth_token_secret;
		 
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
if(isset($_GET["user_timeline"]))
	{
		$db_connection=databaseconnectivity_open();
		$query = "select * from twitter where `user_id`=\"{$_SESSION["user_id"]}\" LIMIT 1";
		$result = mysqli_query($db_connection, $query);
		while($row = mysqli_fetch_assoc($result))
		{
			$secure_id = $row["secure_id"];
			$oauth_token = $row["oauth_token"];
			$oauth_token_secret = $row["oauth_token_secret"];
			$twitter_user_id = $row["twitter_user_id"];
			$screen_name = $row["screen_name"];
			$x_auth_expires = $row["x_auth_expires"];
			$twitter_status = $row["twitter_status"];
		}
		databaseconnectivity_close($db_connection);
		
		$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
		$response = $connection->get("statuses/user_timeline", array("count" => 25, "exclude_replies" => true));
		echo"<ul class=\"list-group\">"; 

		$hashtag_link_pattern = '<a href="http://twitter.com/search?q=%%23%s&src=hash" rel="nofollow" target="_blank">#%s</a>';
		$url_link_pattern = '<a href="%s" rel="nofollow" target="_blank" title="%s">%s</a>';
		$user_mention_link_pattern = '<a href="http://twitter.com/%s" rel="nofollow" target="_blank" title="%s">@%s</a>';
		$media_link_pattern = '<a href="%s" rel="nofollow" target="_blank" title="%s">%s</a>';

		foreach($response as $tweet)
		{
		  echo "<li class=\"list-group-item\">
				<a href=\"{$tweet->user->profile_image_url}\" class=\"pull-left\">
					<img alt=\"{$tweet->user->screen_name}\" class=\"img-circle\" src=\"{$tweet->user->profile_image_url}\">
				</a>
				<a class=\"text-info\" href=\"{$tweet->user->profile_image_url}\">@{$tweet->user->screen_name}</a><p>";
		  $text = $tweet->text;
			$entity_holder = array();
		  foreach($tweet->entities->hashtags as $hashtag)
		  {
			$entity = new stdclass();
			$entity->start = $hashtag->indices[0];
			$entity->end = $hashtag->indices[1];
			$entity->length = $hashtag->indices[1] - $hashtag->indices[0];
			$entity->replace = sprintf($hashtag_link_pattern, strtolower($hashtag->text), $hashtag->text);
			
			$entity_holder[$entity->start] = $entity;
		  }
		  
		  foreach($tweet->entities->urls as $url)
		  {
			$entity = new stdclass();
			$entity->start = $url->indices[0];
			$entity->end = $url->indices[1];
			$entity->length = $url->indices[1] - $url->indices[0];
			$entity->replace = sprintf($url_link_pattern, $url->url, $url->expanded_url, $url->display_url);
			
			$entity_holder[$entity->start] = $entity;
		  }
		  
		  foreach($tweet->entities->user_mentions as $user_mention)
		  {
			$entity = new stdclass();
			$entity->start = $user_mention->indices[0];
			$entity->end = $user_mention->indices[1];
			$entity->length = $user_mention->indices[1] - $user_mention->indices[0];
			$entity->replace = sprintf($user_mention_link_pattern, strtolower($user_mention->screen_name), $user_mention->name, $user_mention->screen_name);
			
			$entity_holder[$entity->start] = $entity;
		  }
		  if(isset($tweet->entities->media)){
		  foreach($tweet->entities->media as $media)
		  {
			$entity = new stdclass();
			$entity->start = $media->indices[0];
			$entity->end = $media->indices[1];
			$entity->length = $media->indices[1] - $media->indices[0];
			$entity->replace = sprintf($media_link_pattern, $media->url, $media->expanded_url, $media->display_url);
			
			$entity_holder[$entity->start] = $entity;
		  }
		  }
		  
		  krsort($entity_holder);
		  foreach($entity_holder as $entity)
		  {
			echo $text = substr_replace($text, $entity->replace, $entity->start, $entity->length)."";
		  }
		  echo"</p><small class=\"block text-muted\"><i class=\"fa fa-clock-o\"></i>{$tweet->created_at}</small>
					</li>
					";
		}


		echo"</ul>";


		die();
	}
if(isset($_GET["display_home_timeline"]))
{
	$db_connection=databaseconnectivity_open();
	$query = "select * from twitter where `user_id`=\"{$_SESSION["user_id"]}\" LIMIT 1";
	$result = mysqli_query($db_connection, $query);
	while($row = mysqli_fetch_assoc($result))
	{
		$secure_id = $row["secure_id"];
		$oauth_token = $row["oauth_token"];
		$oauth_token_secret = $row["oauth_token_secret"];
		$twitter_user_id = $row["twitter_user_id"];
		$screen_name = $row["screen_name"];
		$x_auth_expires = $row["x_auth_expires"];
		$twitter_status = $row["twitter_status"];
	}
	databaseconnectivity_close($db_connection);
	$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
	$response = $connection->get("statuses/home_timeline", array("count" => 25, "exclude_replies" => true, "retweet" => "false", "trim_user" => "false"));
	$hashtag_link_pattern = '<a href="http://twitter.com/search?q=%%23%s&src=hash" rel="nofollow" target="_blank">#%s</a>';
		$url_link_pattern = '<a href="%s" rel="nofollow" target="_blank" title="%s">%s</a>';
		$user_mention_link_pattern = '<a href="http://twitter.com/%s" rel="nofollow" target="_blank" title="%s">@%s</a>';
		$media_link_pattern = '<a href="%s" rel="nofollow" target="_blank" title="%s">%s</a>';
	// if no decode or twitter response errors then proceed.
 
	foreach($response as $tweet)
		{
		// If you are including retweets, you may want to check the status
		// as the main text is truncated as opposed to the original tweet
 
		// If you used the trim_user option, the retweeted user screen name will not be avaialble
		echo "<li class=\"list-group-item\">
				<a href=\"{$tweet->user->profile_image_url}\" class=\"pull-left\">
					<img alt=\"{$tweet->user->screen_name}\" class=\"img-circle\" src=\"{$tweet->user->profile_image_url}\">
				</a>
				<a class=\"text-info\" href=\"{$tweet->user->profile_image_url}\">@{$tweet->user->screen_name}</a><p>";
				
		
			$text = $tweet->text;
		
 
		$entity_holder = array();
		  foreach($tweet->entities->hashtags as $hashtag)
		  {
			$entity = new stdclass();
			$entity->start = $hashtag->indices[0];
			$entity->end = $hashtag->indices[1];
			$entity->length = $hashtag->indices[1] - $hashtag->indices[0];
			$entity->replace = sprintf($hashtag_link_pattern, strtolower($hashtag->text), $hashtag->text);
			
			$entity_holder[$entity->start] = $entity;
		  }
		  
		  foreach($tweet->entities->urls as $url)
		  {
			$entity = new stdclass();
			$entity->start = $url->indices[0];
			$entity->end = $url->indices[1];
			$entity->length = $url->indices[1] - $url->indices[0];
			$entity->replace = sprintf($url_link_pattern, $url->url, $url->expanded_url, $url->display_url);
			
			$entity_holder[$entity->start] = $entity;
		  }
		  
		  foreach($tweet->entities->user_mentions as $user_mention)
		  {
			$entity = new stdclass();
			$entity->start = $user_mention->indices[0];
			$entity->end = $user_mention->indices[1];
			$entity->length = $user_mention->indices[1] - $user_mention->indices[0];
			$entity->replace = sprintf($user_mention_link_pattern, strtolower($user_mention->screen_name), $user_mention->name, $user_mention->screen_name);
			
			$entity_holder[$entity->start] = $entity;
		  }
		  if(isset($tweet->entities->media)){
		  foreach($tweet->entities->media as $media)
		  {
			$entity = new stdclass();
			$entity->start = $media->indices[0];
			$entity->end = $media->indices[1];
			$entity->length = $media->indices[1] - $media->indices[0];
			$entity->replace = sprintf($media_link_pattern, $media->url, $media->expanded_url, $media->display_url);
			
			$entity_holder[$entity->start] = $entity;
		  }
	}

 krsort($entity_holder);
		  foreach($entity_holder as $entity)
		  {
			echo $text = substr_replace($text, $entity->replace, $entity->start, $entity->length)."<br>";
		  }
		   echo"</p><small class=\"block text-muted\"><i class=\"fa fa-clock-o\"></i>{$tweet->created_at}</small>
					</li>
					";

		}
		echo"</ul>";
die();
}
if(isset($_POST["status"]))
{
	$db_connection=databaseconnectivity_open();
	$query = "select * from twitter where `user_id`=\"{$_SESSION["user_id"]}\" LIMIT 1";
	$result = mysqli_query($db_connection, $query);
	while($row = mysqli_fetch_assoc($result))
	{
		$secure_id = $row["secure_id"];
		$oauth_token = $row["oauth_token"];
		$oauth_token_secret = $row["oauth_token_secret"];
		$twitter_user_id = $row["twitter_user_id"];
		$screen_name = $row["screen_name"];
		$x_auth_expires = $row["x_auth_expires"];
		$twitter_status = $row["twitter_status"];
	}
	databaseconnectivity_close($db_connection);
	$status = $_POST["status"];
	if(strlen($status)>=130)
	{
		$status = substr($status,0,130);
	}
	
	$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $oauth_token, $oauth_token_secret);

	$connection->post('statuses/update', array('status' => "$status @sadakpramodh"));
	$rslt = array('status' => "Tweeted");
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($rslt);
	die();
	
}
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Twitter</title>

    <?php 
		require_once("comns/head_section.php")
	?>
</head>

<body>

    <div id="wrapper">

    <?php
		require_once("leftnavigationbar.php");
	?>
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
		<?php
			require_once("search.php");
		?> 
       </div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Twitter</h2>
                    
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
		<?php
		$db_connection=databaseconnectivity_open();
		$query = "select * from twitter where `user_id`=\"{$_SESSION["user_id"]}\" LIMIT 1";
		$result = mysqli_query($db_connection, $query);
		while($row = mysqli_fetch_assoc($result))
		{
			$secure_id = $row["secure_id"];
			$oauth_token = $row["oauth_token"];
			$oauth_token_secret = $row["oauth_token_secret"];
			$twitter_user_id = $row["twitter_user_id"];
			$screen_name = $row["screen_name"];
			$x_auth_expires = $row["x_auth_expires"];
			$twitter_status = $row["twitter_status"];
		}
		databaseconnectivity_close($db_connection);
		if($twitter_status == "verified")
		{
			
		?>
		<?php
		$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
		$response = $connection->get("statuses/user_timeline", array("count" => 25, "exclude_replies" => true));
		foreach($response as $tweet)
		{
		$twitter_user_image = $tweet->user->profile_image_url;
		}
		?>
		
            <div class="row m-b-lg m-t-lg">
                <!--div class="col-md-6">

                    <div class="profile-image">
                        <img src="<?php echo $twitter_user_image; ?>" width="75px" height="75px" class="img-circle circle-border m-b-md" alt="profile">
                    </div>
                    <div class="profile-info">
                        <div class="">
                            <div>
                                <h2 class="no-margins">
                                    <?php echo $screen_name; ?>
                                </h2>
                                <h4></h4>
                                <small>
                                    
                                </small>
                            </div>
                        </div>
                    </div>
                </div-->
                <div class="col-md-12">
                        <div class="ibox-content">
                            <h3>Tweet</h3>
							<form id="form">
                            <div class="form-group">
                                <textarea class="form-control" placeholder="What's happening?" rows="3" name="status" id="status"></textarea>
                            </div>
                            <button class="btn btn-primary btn-block" id="tweet_now">Tweet</button>
							</form>
                        </div>
                    </div>
               


            </div>
            <div class="row">
				<div class="col-lg-6" >
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<i class="fa fa-twitter"></i><h5> User timeline tweets</h5>
							<div class="ibox-tools">
							<a id="refresh_usertimeline">
									<i class="fa fa-refresh"></i>
								</a>
								<a class="collapse-link">
									<i class="fa fa-chevron-up"></i>
								</a>
								
								
								<a class="close-link">
									<i class="fa fa-times"></i>
								</a>
							</div>
						</div>
						<div class="ibox-content no-padding" id="tweet_user_timeline">
						
						</div>
					</div>
				
                </div>

                <div class="col-lg-6" >
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<i class="fa fa-twitter"></i><h5> Home timeline tweets</h5>
							<div class="ibox-tools">
							<a id="refresh_hometimeline">
									<i class="fa fa-refresh"></i>
								</a>
								<a class="collapse-link">
									<i class="fa fa-chevron-up"></i>
								</a>
								
								
								<a class="close-link">
									<i class="fa fa-times"></i>
								</a>
							</div>
						</div>
						<div class="ibox-content no-padding" id="tweet_home_timeline">
							
						</div>
					</div>
				
                </div>
               
            </div>
		<?php
		}
		else
		{
			echo '<center><a class="btn btn-success btn-twitter btn-outline" href="twitter.php?redirect=true">
                            <i class="fa fa-twitter"> </i> Sign in with Twitter
                        </a></center>';
		}
		?>
        </div>
        <?php require_once("footer.php"); ?>

        </div>
		<?php
			require_once("rightsidebar.php");
		?>
        </div>



<?php require_once("user_skin_config.php"); ?>
	
	<?php 
		require_once("comns/body_section.php")
	?>
	    <!-- Jquery Validate -->
    <script src="js/plugins/validate/jquery.validate.min.js"></script>


  

	<script type="text/javascript">

$(document).ready(function () {
$.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
$.get( "twitter.php?user_timeline", function( data ) {
  $( "#tweet_user_timeline" ).html( data );

});
$.get( "twitter.php?display_home_timeline", function( data ) {
  $( "#tweet_home_timeline" ).html( data );

});

 $("#form").validate({
	 rules: {
		 
		 status: {
			 required: true,
			 maxlength: 110
		 }
	 }
 });
<?php $content = file_get_contents("js/custom.js.php"); echo $content?>

});

$("#refresh_usertimeline").click(function(event){
	$.get( "twitter.php?user_timeline", function( data ) {
  $( "#tweet_user_timeline" ).html( data );

});

		   
		});
$("#refresh_hometimeline").click(function(event){
		$.get( "twitter.php?display_home_timeline", function( data ) {
  $( "#tweet_home_timeline" ).html( data );

});

		   
		});		
$("#tweet_now").click(function(event){
	var status= $("#status").val();
	$.post( 
		  "twitter.php",
		  { "status": status },
		  function(data) {
			  if(data) {
					
				toastr.success(data.status, '<?php echo SITE_NAME; ?>');
					
			  }
		  }
	   );
	   
	$.get( "twitter.php?user_timeline", function( data ) {
  $( "#tweet_user_timeline" ).html( data );

});
	   
});



</script>
</body>
</html>
