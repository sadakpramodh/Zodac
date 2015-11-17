<?php
session_start();
require 'src/config.php';
require 'src/facebook.php';
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
        echo $token_url;
        $response = file_get_contents($token_url);
        $params = null;
        parse_str($response, $params);

        $graph_url = "https://graph.facebook.com/me/feed?access_token=".$params['access_token'];
        
        $_SESSION['token'] = $params['access_token'];
		print_r($_SESSION);
		
     $response = file_get_contents($token_url);
     $params = null;
     parse_str($response, $params);
 
     $graph_url = "https://graph.facebook.com/me?access_token=" 
       . $params['access_token'];
 
     $user = json_decode(file_get_contents($graph_url));
     //$content = $user;
	 print_r($user);
    }
    else
    {
        $graph_url = "https://graph.facebook.com/me/feed?access_token=".$_SESSION['token'];
		$graph_url_2 = "https://graph.facebook.com/me?access_token=" .$_SESSION['token'];
    }
      $a =file_get_contents($graph_url);  
	   $b =file_get_contents($graph_url_2);  
	   print_r($b);
	  
    $user = json_decode($a);
	
	

	
}
    ?>
<?php	
if(isset($_SESSION['token'])){
        
          $graph_url = "https://graph.facebook.com/me/feed?access_token=".$_SESSION['token'];
		  $graph_url_2 = "https://graph.facebook.com/me?access_token=" .$_SESSION['token'];
		  $user = json_decode(file_get_contents($graph_url));
		  print_r($graph_url);
		  $b =file_get_contents($graph_url_2); 
 $c = json_decode($b);	
echo"<pre>"; 
	   print_r($c);
	   echo"</pre>";
    }
	?>
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
					</a>
					<div class="media-body ">
						<strong>'.$data->from->name.'</strong> update status. <br>
						
						<div class="well">
							<a href="'.$data->actions[0]->link.'">'.$data->story.' </a>
						</div>
					<small class="text-muted pull-right">'.$data->updated_time .'</small>
					</div>
					
				</div>
                ';
            }
            elseif($data->status_type == 'added_photos'){
                $content .= '
				<div class="feed-element">
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
					
				</div>
                ';
                }
                if($data->type == "video")
                {
					
					$content .= '
				<div class="feed-element">
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
					
				</div>
                ';
                }
            }
        }
    }
    $content .= '</div>';

if($_SESSION["token"]==null)
{
    $content = '<a class="btn btn-success btn-facebook" href="https://www.facebook.com/dialog/oauth?client_id='.$config['App_ID'].'&redirect_uri='.$config['callback_url'].'&scope=email,read_stream">
                            <i class="fa fa-facebook"> </i> Sign in with Facebook
                        </a>';
}  


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
                                           
										   <?php echo $content; ?>
										   
                                        </div>

                                    </div>
                                </div>

                            </div>
		