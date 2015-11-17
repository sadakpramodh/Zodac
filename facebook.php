<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="facebook.php";
if(FACEBOOK != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>
<?php
require 'facebook/facebook.php';
// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => FACEBOOK_APP_ID,
  'secret' => FACEBOOK_APP_SECRET,
  'cookie' => true
));
$db_connection=databaseconnectivity_open();
$query = "select * from facebook where `user_id`=\"{$_SESSION["user_id"]}\" LIMIT 1";
$result = mysqli_query($db_connection, $query);
while($row = mysqli_fetch_assoc($result))
{
	$facebook_id = $row["facebook_id"];
	$bio = $row["bio"];
	$birthday = $row["birthday"];
	$email = $row["email"];
	$first_name = $row["first_name"];
	$gender = $row["gender"];
	$last_name = $row["last_name"];
	$link = $row["link"];
	$locale = $row["locale"];
	$name = $row["name"];
	$timezone = $row["timezone"];
	$updated_time = $row[""];
	$verified = $row["verified"];
	$website = $row["website"];
	$token = $row["token"];
}
databaseconnectivity_close($db_connection);
		
		
if(isset($_GET['fbTrue']))
{       
	$token_url = "https://graph.facebook.com/oauth/access_token?"
		. "client_id=".FACEBOOK_APP_ID."&redirect_uri=" . urlencode(FACEBOOK_OAUTH_CALLBACK)
		. "&client_secret=".FACEBOOK_APP_SECRET."&code=" . $_GET['code'];
	$response = file_get_contents($token_url);
	$params = null;
	parse_str($response, $params);

	$graph_url = "https://graph.facebook.com/me/feed?access_token=".$params['access_token'];

	$token = $params['access_token'];
	$graph_url_2 = "https://graph.facebook.com/me?access_token=" .$token;
	$b =json_decode(file_get_contents($graph_url_2));
	$db_connection=databaseconnectivity_open();
	$query = "UPDATE `facebook` SET `secure_id`=\"{$_SESSION["secure_id"]}\",`facebook_id`=\"{$b->id}\",`bio`=\"{$b->bio}\",`birthday`=\"{$b->birthday}\",`email`=\"{$b->email}\",`first_name`=\"{$b->first_name}\",";
	$query .= "`gender`=\"{$b->gender}\",`last_name`=\"{$b->last_name}\",`link`=\"{$b->link}\",`locale`=\"{$b->locale}\",`name`=\"{$b->name}\",`timezone`=\"{$b->timezone}\",`updated_time`=\"{$b->updated_time}\",`verified`=\"{$b->verified}\",";
	$query .="`website`=\"{$b->website}\",`token`=\"{$token}\" where `user_id`=\"{$_SESSION["user_id"]}\"";
	if (mysqli_query($db_connection, $query)) 
	{
		//OK
	}
	databaseconnectivity_close($db_connection);
	
}

if(isset($_POST["me_get_feeds"]))
{
	$content ="";
	if($verified =="1")
	{
		$graph_url = "https://graph.facebook.com/me/feed?access_token=".$token;
		$user =json_decode(file_get_contents($graph_url));
		$content ='<ul class="list-group"><div class="feed-activity-list">';
		foreach($user->data as $data)
		{
			if($data->type == 'status' or $data->type == 'photo' or $data->type == 'video' or $data->type == 'link'){
				if($data->status_type == 'mobile_status_update'){
					$content .= '
					<li class="list-group-item"><div class="feed-element">
						<a href="'.$data->actions[0]->link.'" class="pull-left">
							<img alt="'.$data->from->name.'" class="img-circle" src="http://graph.facebook.com/'.$data->from->id.'/picture?type=small">
						</a>
						<div class="media-body ">
							<strong>'.$data->from->name.'</strong> update status. <br>
							
							<div class="well">
								<a href="'.$data->actions[0]->link.'">'.$data->story.' </a>
							</div>
						<small class="text-muted pull-right">'.$data->updated_time .'</small>
						</div>
						
					</div></li>
					';
				}
				elseif($data->status_type == 'added_photos'){
					$content .= '
					<li class="list-group-item"><div class="feed-element">
						<a href="'.$data->actions[0]->link.'" class="pull-left">
							<img alt="'.$data->from->name.'" class="img-circle" src="http://graph.facebook.com/'.$data->from->id.'/picture?type=small">
						</a>
						<div class="media-body ">
							
							<strong>'.$data->from->name.'</strong> added a picture. <br>
							
							<div class="well">
								<a href="'.$data->actions[0]->link.'"> <img src="'.$data->picture.'"></a>
							</div>
						<small class="text-muted pull-right">'.$data->updated_time .'</small>
						</div>
						
					</div></li>
					';
				}
				elseif($data->status_type == 'shared_story'){
					if($data->type == "link")
					{
						$content .= '
					<li class="list-group-item"><div class="feed-element">
						<a href="'.$data->actions[0]->link.'" class="pull-left">
							<img alt="'.$data->from->name.'" class="img-circle" src="http://graph.facebook.com/'.$data->from->id.'/picture?type=small">
						</a>
						<div class="media-body ">
							<strong>'.$data->from->name.'</strong> shared a link. <br>
							<p>'.$data->story.'</p><br>
							
							<div class="well">
							<a href="'.$data->actions[0]->link.'">
								<p>'.$data->name.'</p>
								<p>'.$data->description.'</p>
							</a>
							</div>
						<small class="text-muted pull-right">'.$data->updated_time .'</small>
						</div>
						
					</div></li>
					';
					}
					if($data->type == "video")
					{
						
						$content .= '
					<li class="list-group-item"><div class="feed-element">
						<a href="'.$data->actions[0]->link.'" class="pull-left">
							<img alt="'.$data->from->name.'" class="img-circle" src="http://graph.facebook.com/'.$data->from->id.'/picture?type=small">
						</a>
						<div class="media-body ">
							<strong>'.$data->from->name.'</strong> shared a link. <br>
							<p>'.$data->message.'</p><br>
							
							<div class="well">
								<a href="'.$data->actions[0]->link.'">
								<a href="'.$data->link.'"><img src="'.$data->picture.'"></a>
								<p>'.$data->name.'</p>
								<p>'.$data->description.'</p>
								</a>
							</div>
							<small class="text-muted pull-right">'.$data->updated_time .'</small>

						</div>
						
					</div></li>
					';
					}
				}
			}
		}
		
		$content .= '</div></ul>';
	}
	else
	{
		$content .='<a class="btn btn-success btn-facebook" href="https://www.facebook.com/dialog/oauth?client_id='.FACEBOOK_APP_ID.'&redirect_uri='.FACEBOOK_OAUTH_CALLBACK.'&scope=email,read_stream">
                            <i class="fa fa-facebook"> </i> Sign in with Facebook
                        </a>';
	}
echo $content;
die();
}
?>
	

<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Facebook</title>

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
                    <h2>Facebook</h2>
                    
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
		
		<?php
		if($verified == "1")
		{
			$facebook_profile_img = "http://graph.facebook.com/{$facebook_id}/picture?type=large";
			
		
		?>
            <div class="row m-b-lg m-t-lg">
                <div class="col-md-6">

                    <div class="profile-image">
                        <img src="<?php echo $facebook_profile_img; ?>" class="img-circle circle-border m-b-md" alt="<?php echo $contact_url_userhead; ?>">
                    </div>
                    <div class="profile-info">
                        <div class="">
                            <div>
                                <h2 class="no-margins">
                                    <?php echo $name; ?>
                                </h2>
                                <h4><?php echo $facebook_id; ?></h4>
                                <small>
                                    <p><b>BIO</b> </p><?php echo $bio; ?>
								</small>
								<small>
									<p><b>Email</b> <?php echo $email; ?></p>
									<p><b>Gender</b> <?php echo $gender; ?></p>
									<p><b>Link</b> <?php echo $link; ?></p>
									<p><b>Birthday</b> <?php echo $birthday; ?></p>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <!--div class="col-md-6">
                        <div class="ibox-content">
                            <h3>Facebook</h3>
							<form id="form">
                            <div class="form-group">
                                <textarea class="form-control" placeholder="What's on your mind?" rows="3" name="status" id="status"></textarea>
                            </div>
                            <button class="btn btn-primary btn-block" id="tweet_now">Post</button>
							</form>
                        </div>
                    </div-->
               


            </div>
            <div class="row">
				<div class="col-lg-6" >
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5> User timeline</h5>
							<div class="ibox-tools">
							<a id="refresh_me_get_feeds">
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
						<div class="ibox-content no-padding" id="me_get_feeds">
						
						</div>
					</div>
				
                </div>

               
            </div>
		<?php
		}
		
		else
		{
			echo '<a class="btn btn-success btn-facebook" href="https://www.facebook.com/dialog/oauth?client_id='.FACEBOOK_APP_ID.'&redirect_uri='.FACEBOOK_OAUTH_CALLBACK.'&scope=email,read_stream">
                            <i class="fa fa-facebook"> </i> Sign in with Facebook
                        </a>';
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
$.post( 
		  "facebook.php",
		  { "me_get_feeds": "true" },
		  function(data) {
			  if(data) {
					
				 $( "#me_get_feeds" ).html( data ); 
					
			  }
		  }
		 
	   );



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


	
$("#refresh_me_get_feeds").click(function(event){
	$.post( 
		  "facebook.php",
		  { "me_get_feeds": "true" },
		  function(data) {
			  if(data) {
					
				 $( "#me_get_feeds" ).html( data ); 
					
			  }
		  }
		 
	   );

});
	   




</script>
</body>
</html>
