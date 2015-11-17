<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
?> 
<?php
session_start();
require 'FB/src/config.php';
require 'FB/src/facebook.php';
// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => $config['App_ID'],
  'secret' => $config['App_Secret'],
  'cookie' => true
));

if(isset($_GET['fbTrue']))
{
    if(!isset($_SESSION['token'])){
        
        $token_url = "https://graph.facebook.com/oauth/access_token?"
            . "client_id=".$config['App_ID']."&redirect_uri=" . urlencode($config['callback_url'])
            . "&client_secret=".$config['App_Secret']."&code=" . $_GET['code'];
        
        $response = file_get_contents($token_url);
        $params = null;
        parse_str($response, $params);

        $graph_url = "https://graph.facebook.com/me/feed?access_token=".$params['access_token'];
        
        $_SESSION['token'] = $params['access_token'];
    }
    else
    {
        $graph_url = "https://graph.facebook.com/me/feed?access_token=".$_SESSION['token'];
    }
        
    $user = json_decode(file_get_contents($graph_url));
    ?>

                           <div class="col-lg-4">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>Facebook</h5>
                                        <div class="ibox-tools">
                                            
                                           </div>
                                    </div>
                                    <div class="ibox-content">

                                        <div>
										
										
												<?php						
    $content ='<div class="feed-activity-list">';
	/*echo "<pre>";
	print_r($user);
	echo "</pre>";*/
	
    foreach($user->data as $data)
    {
        if($data->type == 'status' or $data->type == 'photo' or $data->type == 'video' or $data->type == 'link'){
	        if($data->status_type == 'mobile_status_update'){
                $content .= '
				<div class="feed-element">
					<a href="'.$data->actions[0]->link.'" class="pull-left">
						<img alt="'.$data->from->name.'" class="img-circle" src="http://graph.facebook.com/'.$data->from->id.'/picture?type=small">
					
					<div class="media-body ">
						
						<strong>'.$data->from->name.'</strong> update status. <br>
						<small class="text-muted">'.$data->updated_time .'</small>
						<div class="well">
							'.$data->story.'
						</div>

					</div>
					</a>
				</div>
                ';
            }
            elseif($data->status_type == 'added_photos'){
                $content .= '
				<div class="feed-element">
					<a href="'.$data->actions[0]->link.'" class="pull-left">
						<img alt="'.$data->from->name.'" class="img-circle" src="http://graph.facebook.com/'.$data->from->id.'/picture?type=small">
					
					<div class="media-body ">
						
						<strong>'.$data->from->name.'</strong> added a picture. <br>
						<small class="text-muted">'.$data->updated_time .'</small>
						<div class="well">
							<img src="'.$data->picture.'">
						</div>

					</div>
					</a>
				</div>
                ';
			}
            elseif($data->status_type == 'shared_story'){
                if($data->type == "link")
                {
                    $content .= '
				<div class="feed-element">
					<a href="'.$data->actions[0]->link.'" class="pull-left">
						<img alt="'.$data->from->name.'" class="img-circle" src="http://graph.facebook.com/'.$data->from->id.'/picture?type=small">
					
					<div class="media-body ">
						<strong>'.$data->from->name.'</strong> shared a link. <br>
						<p>'.$data->story.'</p><br>
						<small class="text-muted">'.$data->updated_time .'</small>
						<div class="well">
							<p>'.$data->name.'</p>
                                            <p>'.$data->description.'</p>
						</div>

					</div>
					</a>
				</div>
                ';
                }
                if($data->type == "video")
                {
					
					$content .= '
				<div class="feed-element">
					<a href="'.$data->actions[0]->link.'" class="pull-left">
						<img alt="'.$data->from->name.'" class="img-circle" src="http://graph.facebook.com/'.$data->from->id.'/picture?type=small">
					
					<div class="media-body ">
						<strong>'.$data->from->name.'</strong> shared a link. <br>
						<p>'.$data->message.'</p><br>
						<small class="text-muted">'.$data->updated_time .'</small>
						<div class="well">
							<a href="'.$data->link.'"><img src="'.$data->picture.'"></a>
							<p>'.$data->name.'</p>
                            <p>'.$data->description.'</p>
						</div>

					</div>
					</a>
				</div>
                ';
                }
            }
        }
    }
    $content .= '</div>';
}
else
{
    $content = '<a href="https://www.facebook.com/dialog/oauth?client_id='.$config['App_ID'].'&redirect_uri='.$config['callback_url'].'&scope=email,read_stream"><img src="FB/images/login-button.png" alt="Sign in with Facebook"/></a>';
    
}

echo $content;
?>
										
										
										
										
										
										
										
										
										
										
										
										
                                           
                                        </div>

                                    </div>
                                </div>

                            </div>