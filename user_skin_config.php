<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
?>
<?php
if(isset($_GET['set_site_preferences'])) {
	
	$connection=databaseconnectivity_open();
	$value = clean_user_input($connection,$_GET["value"]);
	switch($_GET['set_site_preferences']) {
		case 'top_navbar':
			if($value=="on")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="top_navbar=\"{$value}\", boxed_layout=\"off\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="off")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="top_navbar=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
			}
			break;
		case 'fixed_sidebar':
			if($value=="on")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="fixed_sidebar=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="off")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="fixed_sidebar=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
			}
			break;
		case 'collapse_menu':
			if($value=="on")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="collapse_menu=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="off")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="collapse_menu=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
			}
			break;
		case 'boxed_layout':
			if($value=="on")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="boxed_layout=\"{$value}\", top_navbar=\"off\", fixed_footer=\"off\"  where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="off")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="boxed_layout=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
			}
			break;
		case 'fixed_footer':
			if($value=="on")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="fixed_footer=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="off")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="fixed_footer=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
			}
			break;
		case 'skins':
			if($value=="default")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="skins=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="blue")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="skins=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="ultra")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="skins=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="yellow")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="skins=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			break;
		case 'location_1':
			$query = "UPDATE `user_preferences` SET ";
			$query .="location_1=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
			mysqli_query($connection, $query);
			break;
		case 'location_2':
			$query = "UPDATE `user_preferences` SET ";
			$query .="location_2=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
			mysqli_query($connection, $query);
			break;
		case 'lastpage_viewed':
			$query = "UPDATE `user_preferences` SET ";
			$query .="lastpage_viewed=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
			mysqli_query($connection, $query);
			break;
		case 'notifications':
			if($value=="toast-top-right")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="notification_position=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="toast-bottom-right")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="notification_position=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="toast-bottom-left")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="notification_position=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="toast-top-left")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="notification_position=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="toast-top-full-width")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="notification_position=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="toast-bottom-full-width")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="notification_position=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="toast-top-center")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="notification_position=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
			else if($value=="toast-bottom-center")
			{
				$query = "UPDATE `user_preferences` SET ";
				$query .="notification_position=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
				mysqli_query($connection, $query);
				
			}
		case 'lockscreen_timeout':
			$value *= 1000 * 60;
			$query = "UPDATE `user_preferences` SET ";
			$query .="lockscreen_timeout=\"{$value}\" where user_id=\"{$_SESSION["user_id"]}\"";
			mysqli_query($connection, $query);
			break;
		databaseconnectivity_close($connection);
		die();
		
	}

	
}
		
if(isset($_GET['operation'])) {
	if($_GET['operation']=="get_site_preferences")
	{
		try {
			$connection=databaseconnectivity_open();
			$query = "select * from user_preferences where `user_id`=\"{$_SESSION["user_id"]}\" LIMIT 1";
			$result = mysqli_query($connection, $query);
			while($row = mysqli_fetch_assoc($result))
				{
					$collapse_menu = $row["collapse_menu"];
					$fixed_sidebar = $row["fixed_sidebar"];
					$top_navbar = $row["top_navbar"];
					$boxed_layout = $row["boxed_layout"];
					$fixed_footer = $row["fixed_footer"];
					$skins = $row["skins"];
					$location_1 = $row["location_1"];
					$location_2 = $row["location_2"];
					$notification_position = $row["notification_position"];
					$lockscreen_timeout = $row["lockscreen_timeout"];
					$lastpage_viewed = $row["lastpage_viewed"];
					$rslt = array('collapse_menu' => $collapse_menu, 'fixed_sidebar' => $fixed_sidebar, 'top_navbar' => $top_navbar, 'boxed_layout' => $boxed_layout, 'fixed_footer' => $fixed_footer, 'skins' => $skins, 'location_1' => $location_1, 'location_2' => $location_2, 'notification_position' => $notification_position, 'lockscreen_timeout' => $lockscreen_timeout, 'lastpage_viewed' => $lastpage_viewed);
				}

			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($rslt);
		}
		catch (Exception $e) {
			header($_SERVER["SERVER_PROTOCOL"] . ' 500 Server Error');
			header('Status:  500 Server Error');
			echo $e->getMessage();
		}
		die();
	}
}
	?>
	<div class="theme-config">
    <div class="theme-config-box">
        <div class="spin-icon">
            <i class="fa fa-cogs fa-spin"></i>
        </div>
        <div class="skin-setttings">
            <div class="title">Configuration</div>
            <div class="setings-item">
                    <span>
                        Collapse menu
                    </span>

                <div class="switch">
                    <div class="onoffswitch">
                        <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="collapsemenu">
                        <label class="onoffswitch-label" for="collapsemenu">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="setings-item">
                    <span>
                        Fixed sidebar
                    </span>

                <div class="switch">
                    <div class="onoffswitch">
                        <input type="checkbox" name="fixedsidebar" class="onoffswitch-checkbox" id="fixedsidebar">
                        <label class="onoffswitch-label" for="fixedsidebar">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="setings-item">
                    <span>
                        Top navbar
                    </span>

                <div class="switch">
                    <div class="onoffswitch">
                        <input type="checkbox" name="fixednavbar" class="onoffswitch-checkbox" id="fixednavbar">
                        <label class="onoffswitch-label" for="fixednavbar">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="setings-item">
                    <span>
                        Boxed layout
                    </span>

                <div class="switch">
                    <div class="onoffswitch">
                        <input type="checkbox" name="boxedlayout" class="onoffswitch-checkbox" id="boxedlayout">
                        <label class="onoffswitch-label" for="boxedlayout">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="setings-item">
                    <span>
                        Fixed footer
                    </span>

                <div class="switch">
                    <div class="onoffswitch">
                        <input type="checkbox" name="fixedfooter" class="onoffswitch-checkbox" id="fixedfooter">
                        <label class="onoffswitch-label" for="fixedfooter">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="title">Skins</div>
            <div class="setings-item default-skin">
                    <span class="skin-name ">
                         <a href="#" class="s-skin-0">
                             Default
                         </a>
                    </span>
            </div>
            <div class="setings-item blue-skin">
                    <span class="skin-name ">
                        <a href="#" class="s-skin-1">
                            Blue light
                        </a>
                    </span>
            </div>
			<div class="setings-item ultra-skin">
                    <span class="skin-name ">
                        <a href="#" class="s-skin-2">
                            Ultra Skin
                        </a>
                    </span>
            </div>
            <div class="setings-item yellow-skin">
                    <span class="skin-name ">
                        <a href="#" class="s-skin-3">
                            Yellow/Purple
                        </a>
                    </span>
            </div>
			<div class="title">AUTO LOCK</div>
			<div class="setings-item">
                    
				<input type="text" id="auto_lock" name="auto_lock_time" value="">

            </div>
			<div class="title">NOTIFICATIONS</div>
			<div class="setings-item">
								<div class="form-group" id="positionGroup">
                                        <label>Position</label>
                                    <div class="radio radio-info">
                                        <label >
                                            <input type="radio" name="positions" value="toast-top-right" id="topright"/>Top Right
                                        </label>
                                    </div>
                                    <div class="radio radio-info">
                                        <label >
                                            <input type="radio" name="positions" value="toast-bottom-right" id="bottomright"/>Bottom Right
                                        </label>
                                    </div>
                                    <div class="radio radio-info">
                                        <label >
                                            <input type="radio" name="positions" value="toast-bottom-left" id="bottomleft"/>Bottom Left
                                        </label>
                                    </div>
                                    <div class="radio radio-info">
                                        <label >
                                            <input type="radio" name="positions" value="toast-top-left" id="topleft"/>Top Left
                                        </label>
                                    </div>
                                    <div class="radio radio-info">
                                        <label >
                                            <input type="radio" name="positions" value="toast-top-full-width" id="topfullwidth"/>Top Full Width
                                        </label>
                                    </div>
                                    <div class="radio radio-info">
                                        <label >
                                            <input type="radio" name="positions" value="toast-bottom-full-width" id="bottomfullwidth"/>Bottom Full Width
                                        </label>
                                    </div>
                                    <div class="radio radio-info">
                                        <label >
                                            <input type="radio" name="positions" value="toast-top-center" id="topcenter"/>Top Center
                                        </label>
                                    </div>
                                    <div class="radio radio-info">
                                        <label >
                                            <input type="radio" name="positions" value="toast-bottom-center" id="bottomcenter"/>Bottom Center
                                        </label>
                                    </div>
									

                                </div>
            </div>

            </div>
        </div>
    </div>
</div>
