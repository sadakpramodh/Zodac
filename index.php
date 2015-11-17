<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="index.php";
if(INDEX != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>

<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Dashboard</title>
	<?php 
		require_once("comns/head_section.php")
	?>
</head>

<body>

    <div id="wrapper">
	
	
	<?php
		require_once("leftnavigationbar.php");
	?>

        <div id="page-wrapper" class="gray-bg dashbard-1">
        <!--
			contents in file search.php
		-->
        <?php
			require_once("search.php");
		?>       
                

        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content">
                        <div class="row">
						
						
						
                        <div class="col-lg-4">
                        <!--
						content in messagefromadmin.php
						-->   
						<?php
						require_once("messagefromadmin.php");
						?>
						
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
						
                    <div class="ibox float-e-margins">
						<div class="ibox-title">
							<i class="fa fa-twitter"></i><h5> Tweets</h5>
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
						<?php
						}
						else
						{
							echo '<a class="btn btn-success btn-twitter" href="twitter.php?redirect=true">
                            <i class="fa fa-twitter"> </i> Sign in with Twitter
                        </a>';
						}
						?>
						
						
						
						
						
					
                        </div>
					<!--
						content in facebookfeeds.php
					-->
					<?php
					/*		
						require_once("facebookfeeds.php");
				*/?>
                           <div class="col-lg-4">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>Facebook</h5>
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
                                    <div class="ibox-content">

                                        <div id="me_get_feeds">
                                        </div>

                                    </div>
                                </div>

                            </div>
						
						
						
						<!--
						content in todayschedule.php
						-->
						<?php
							
							require_once("timeline.php");
						?>
                        	
                        </div>
                </div>
				
			
                <?php require_once("footer.php"); ?>
                <!--
                	content in footer.php
                -->
            </div>
        </div>

        </div>
	<!--
		content in chatwidget.php
	-->
	
        </div>
        <!--
		 contents in rightsidebar.php
		-->
		<?php
			require_once("rightsidebar.php");
		?>
    </div>

<?php require_once("user_skin_config.php"); ?>
	
	<?php 
		require_once("comns/body_section.php")
	?>


	

<script type="text/javascript">

$(document).ready(function () {
$.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
$.get( "twitter.php?user_timetine", function( data ) {
  $( "#tweet_user_timeline" ).html( data );

});
$.post( 
		  "facebook.php",
		  { "me_get_feeds": "true" },
		  function(data) {
			  if(data) {
					
				 $( "#me_get_feeds" ).html( data ); 
					
			  }
		  }
		 
	   );
setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 4000,
					positionClass: notification_position
					
					
                };
                toastr.success('Intelligent Dashboard', '<?php echo SITE_NAME; ?>');

            }, 1300);
<?php $content = file_get_contents("js/custom.js.php"); echo $content?>

});

$("#refresh_usertimeline").click(function(event){
	$.get( "twitter.php?user_timetine", function( data ) {
  $( "#tweet_user_timeline" ).html( data );

});

		   
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
