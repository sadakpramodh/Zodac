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
    
    $content = '<style>
		    .container{
			border: solid 1px black;
			
		    }
		    .profile{
			width: 90px;
			padding: 5px;
			vertical-align: top;
		    }
		    .text{
			width: 390px;
			padding: 5px;
			vertical-align: top;
		    }
		    .clean{
			margin: 10px;
		    }
		    .main{
			width: 500px;
			margin-left: auto;
			margin-right: auto;
		    }
		    .link{
			background-color: #f6f7f8;
		    }
		</style>';
    $content .='<div class="main">';
	echo "<pre>";
	print_r($user);
	echo "</pre>";
    foreach($user->data as $data)
    {
        if($data->type == 'status' or $data->type == 'photo' or $data->type == 'video' or $data->type == 'link'){
	        if($data->status_type == 'mobile_status_update'){
                $content .= '
                <table class="container">
                    <tr>
                        <td class="profile"><img src="http://graph.facebook.com/'.$data->from->id.'/picture?type=large" alt="'.$data->from->name.'" width="90" height="90"></td>
                        <td class="text">
                            <strong>'.$data->from->name.' update status</strong><br />
                            <p>'.$data->story.'</p>
                            <a href="'.$data->actions[0]->link.'">View on Facebook</a>
                        </td>
                    </tr>
                </table>
                <div class="clean"></div>
                ';
            }
            elseif($data->status_type == 'added_photos'){
                $content .= '
                <table class="container">
                    <tr>
                        <td class="profile"><img src="http://graph.facebook.com/'.$data->from->id.'/picture?type=large" alt="'.$data->from->name.'" width="90" height="90"></td>
                        <td class="text">
                            <strong>'.$data->from->name.' added a picture</strong><br />
                            <p>'.$data->message.'</p>
                            <p><img src="'.$data->picture.'"></p>
                            <a href="'.$data->actions[0]->link.'">View on Facebook</a>
                        </td>
                    </tr>
                </table>
                <div class="clean"></div>
                ';
            }
            elseif($data->status_type == 'shared_story'){
                if($data->type == "link")
                {
                    $content .= '
                    <table class="container">
                        <tr>
                            <td class="profile"><img src="http://graph.facebook.com/'.$data->from->id.'/picture?type=large" alt="'.$data->from->name.'" width="90" height="90"></td>
                            <td class="text">
                                <strong>'.$data->from->name.' shared a link</strong><br />
                                <p>'.$data->story.'</p>
                                <table class="link">
                                    <tr>
                                        <td valign="top"><a href="'.$data->link.'"><img src="'.$data->picture.'"></a></td>
                                        <td>
                                            <p>'.$data->name.'</p>
                                            <p>'.$data->description.'</p>
                                        </td>
                                    </tr>
                                </table>
                                <a href="'.$data->actions[0]->link.'">View on Facebook</a>
                            </td>
                        </tr>
                    </table>
                    <div class="clean"></div>
                    ';   
                }
                if($data->type == "video")
                {
                    $content .= '
                    <table class="container">
                        <tr>
                            <td class="profile"><img src="http://graph.facebook.com/'.$data->from->id.'/picture?type=large" alt="'.$data->from->name.'" width="90" height="90"></td>
                            <td class="text">
                                <strong>'.$data->from->name.' shared a video</strong><br />
                                <p>'.$data->message.'</p>
                                <table class="link">
                                    <tr>
                                        <td valign="top"><a href="'.$data->link.'"><img src="'.$data->picture.'"></a></td>
                                        <td>
                                            <p>'.$data->name.'</p>
                                            <p>'.$data->description.'</p>
                                        </td>
                                    </tr>
                                </table>
                                <a href="'.$data->actions[0]->link.'">View on Facebook</a>
                            </td>
                        </tr>
                    </table>
                    <div class="clean"></div>
                    ';   
                }
            }
        }
    }
    $content .= '</div>';
}
else
{
    $content = '<a href="https://www.facebook.com/dialog/oauth?client_id='.$config['App_ID'].'&redirect_uri='.$config['callback_url'].'&scope=email,read_stream"><img src="./images/login-button.png" alt="Sign in with Facebook"/></a>';
    
}

echo $content;
?>