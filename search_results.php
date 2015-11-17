<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="search_results.php";
if(SEARCH != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>


<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Search Page</title>
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
			<?php
					require_once("search.php");
			?>  
        <div class="row border-bottom">
        
        </div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-9">
                    <h2>	Search Results</h2>
                    
                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
						<?php if(isset($_GET["search"]))
								{
                            echo'<h2>
                                Results found for: <span class="text-navy">“'.$_GET["search"].'”</span>
                            </h2>';
								}
								?>

                            <div class="search-form">
                                <form action="search_results.php" method="get">
                                    <div class="input-group">
                                        <input type="text" placeholder="Search" name="search" class="form-control input-lg" value="<?php if(isset($_GET["search"]))
								{ echo $_GET["search"];   }?>">
										
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" type="submit">
                                                Search
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <?php
								if(isset($_GET["search"]))
								{
									//print_r($_GET);
									$result_count=0;
									$connection=databaseconnectivity_open();
									$search=clean_user_input($connection,$_GET["search"]);
									$search_resultx=array();
									$query = "SELECT * from pinboard where (user_id=\"{$_SESSION["user_id"]}\") AND `delete`=\"false\" AND (pinboard_id LIKE '%{$search}%' OR time LIKE '%{$search}%' OR date LIKE '%{$search}%' OR title LIKE '%{$search}%' OR body LIKE '%{$search}%')";
									//echo $query;
									$result = mysqli_query($connection, $query);
									while($row = mysqli_fetch_assoc($result))
									{
										echo'<div class="search-result">';
											echo'<h3><a href="'.SITE_URL.'/pinboard.php?pinboard_id='.$row["pinboard_id"].'">'.$row["title"].'</a></h3>';
											echo'<a href="'.SITE_URL.'/pinboard.php?pinboard_id='.$row["pinboard_id"].'" class="search-link">'.SITE_URL.'./pinboard.php?pinboard_id='.$row["pinboard_id"].'</a>';
											echo'<p>'. $row["body"] 
												
											.'</p>';
										echo'</div>';
										echo'<div class="hr-line-dashed"></div>';
										
										
									  
									}
									$result_count +=mysqli_num_rows($result);
									
									$query = "SELECT * FROM `contacts` WHERE (`user_id`=\"{$_SESSION["user_id"]}\") AND `delete`=\"false\" AND (`contact_id` LIKE '%{$search}%' OR `contact_name` LIKE '%{$search}%' OR `nick_name` LIKE '%{$search}%' OR `address_1` LIKE '%{$search}%' OR `country` LIKE '%{$search}%' OR `mobile_number_1` LIKE '%{$search}%' OR `mobile_number_2` LIKE '%{$search}%' OR `email_id_1` LIKE '%{$search}%' OR `email_id_2` LIKE '%{$search}%' OR `aniversary` LIKE '%{$search}%' OR `birthday` LIKE '%{$search}%' OR `eskimi` LIKE '%{$search}%' OR `twitter` LIKE '%{$search}%' OR `facebook` LIKE '%{$search}%' OR `flickr` LIKE '%{$search}%' OR `github` LIKE '%{$search}%' OR `google_plus` LIKE '%{$search}%' OR `linkedin` LIKE '%{$search}%' OR `instagram` LIKE '%{$search}%' OR `pinterest` LIKE '%{$search}%' OR `skype` LIKE '%{$search}%' OR `tumblr` LIKE '%{$search}%' OR `vk` LIKE '%{$search}%' OR `youtube` LIKE '%{$search}%' OR `website` LIKE '%{$search}%' OR `about` LIKE '%{$search}%' OR `user_id` = 1 OR `profile_id` LIKE '%{$search}%' OR `contact_url` LIKE '%{$search}%')";
									//echo $query;
									$result = mysqli_query($connection, $query);
									while($row = mysqli_fetch_assoc($result))
									{
									  echo'<div class="search-result">';
											echo'<h3><a href="'.SITE_URL.'/profile.php?contact_id='.$row["contact_id"].'">'.$row["contact_name"].'</a></h3>';
											echo'<a href="'.SITE_URL.'/profile.php?contact_id='.$row["contact_id"].'" class="search-link">'.SITE_URL.'/profile.php?contact_id='.$row["contact_id"].'</a>';
											echo'<p>'. $row["about"] 
												
											.'</p>';
										echo'</div>';
										echo'<div class="hr-line-dashed"></div>';
										
									  
									}
									$result_count +=mysqli_num_rows($result);
									$query = "SELECT * from calendar where (user_id=\"{$_SESSION["user_id"]}\" AND `delete`=\"false\") AND (calendar_id LIKE '%{$search}%' OR time LIKE '%{$search}%' OR date LIKE '%{$search}%' OR title LIKE '%{$search}%' OR body LIKE '%{$search}%')";
									//echo $query;
									$result = mysqli_query($connection, $query);
									while($row = mysqli_fetch_assoc($result))
									{
									   echo'<div class="search-result">';
											echo'<h3><a href="'.SITE_URL.'/calendar.php?calendar_id='.$row["calendar_id"].'">'.$row["title"].'</a></h3>';
											echo'<a href="'.SITE_URL.'/calendar.php?calendar_id='.$row["calendar_id"].'" class="search-link">'.SITE_URL.'/calendar.php?calendar_id='.$row["calendar_id"].'</a>';
											echo'<p>'. $row["body"] 
												
											.'</p>';
										echo'</div>';
										echo'<div class="hr-line-dashed"></div>';
										
									  
									}
									
									$result_count +=mysqli_num_rows($result);
									$query = "SELECT * from schedule where (user_id=\"{$_SESSION["user_id"]}\" AND `delete`=\"false\") AND (schedule_id LIKE '%{$search}%' OR time LIKE '%{$search}%' OR date LIKE '%{$search}%' OR description LIKE '%{$search}%' OR message LIKE '%{$search}%')";
									//echo $query;
									$result = mysqli_query($connection, $query);
									while($row = mysqli_fetch_assoc($result))
									{
									   echo'<div class="search-result">';
											echo'<h3><a href="'.SITE_URL.'/todayschedule.php?schedule_id='.$row["schedule_id"].'">'.$row["description"].'</a></h3>';
											echo'<a href="'.SITE_URL.'/todayschedule.php?schedule_id='.$row["schedule_id"].'" class="search-link">'.SITE_URL.'/todayschedule.php?schedule_id='.$row["schedule_id"].'</a>';
											echo'<p>'. $row["message"] 
												
											.'</p>';
										echo'</div>';
										echo'<div class="hr-line-dashed"></div>';
									   
									   
									}
									
									if($result_count<=0)
									{
										echo "no results";
									}
									else
									{
										echo "Results Count  ".$result_count;
									}
									//print_r($search_resultx);
								}
							?>
                
                            <div class="hr-line-dashed"></div>
                            
                        </div>
                    </div>
                </div>
        </div>
        </div>
       <?php
			require_once("footer.php");
		?>

        </div>
		<?php
			require_once("rightsidebar.php");
		?>
        </div>
<?php require_once("user_skin_config.php"); ?>
	<?php 
		require_once("comns/body_section.php")
	?>
	<script>
	$(document).ready(function(){
			$.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
			<?php $content = file_get_contents("js/custom.js.php"); echo $content?>
	});
	</script>
	




</body>
</html>
