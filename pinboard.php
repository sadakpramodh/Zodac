<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="pinboard.php";
if(INDEX != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>
<?php
if(isset($_GET["pinboard_id"])&&isset($_GET["delete"]))
	{
		if($_GET["delete"]==true)
		{
			$connection=databaseconnectivity_open();
			$query="UPDATE `pinboard` SET `delete`= \"true\" WHERE pinboard_id={$_GET["pinboard_id"]} AND user_id={$_SESSION["user_id"]}";
			if(mysqli_query($connection, $query)) 
			{
				$_SESSION["message"]="pinboard deleted";
				$_SESSION["type"]=2;
				redirect_to("pinboard.php");
			} 
			else 
			{
				$_SESSION["message"]="Illegal operation";
				$_SESSION["type"]=3;
			}
				
			databaseconnectivity_close($connection);	
		}
	
	}
if(isset($_POST["submit"]))
	{
	//print_r($_POST);
	$title=$_POST["title"];
	$body=$_POST["body"];
	$hour=$_POST["hour"];
	$minute=$_POST["minute"];
	$second=$_POST["second"];
	$date=$_POST["date"];
	
	$errors = array();
		if(!isset($title) || !isset($body) || !isset($hour) || !isset($minute) || !isset($second) || !isset($date))
			{
    		$errors["1"] = "Please fill all fields for pin it";
			}
		if(empty($errors))
			{
			$connection=databaseconnectivity_open();
			$title =clean_user_input($connection, $title);
			$body = clean_user_input($connection, $body);
			$hour = clean_user_input($connection, $hour);
			$minute = clean_user_input($connection, $minute);
			$second = clean_user_input($connection, $second);
			$date = clean_user_input($connection, $date);
			$time = $hour.$minute.$second;
			$query = "INSERT INTO `pinboard`(`time`, `date`, `title`, `body`, `user_id`,`delete`) VALUES (\"";
			$query .= $time ."\",\"";
			$query .= $date ."\",\"";
			$query .= $title ."\",\"";
			$query .= $body ."\",\"{$_SESSION["user_id"]}\",\"false\")";
			if (mysqli_query($connection, $query)) 
				{
					$_SESSION["message"]="pinboard added";
					$_SESSION["type"]=1;
				}
			databaseconnectivity_close($connection);
			}
	}
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Pin Board</title>

<?php 
	require_once("comns/head_section.php")
?>

    <link href="css/plugins/switchery/switchery.css" rel="stylesheet">

    <link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">

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
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-9">
                    <h2>Pin Board</h2>
                </div>
            </div>
			<!--
			pinboard body
			-->
			<?php
						if($_SESSION["message"] != null)
							{
							display_message($_SESSION["message"], $_SESSION["type"]);
							}
						$_SESSION["message"] = null;
						?>
			
						<?php
						if(!empty($errors))
							{
							echo "<div class=\"form-group\">";
							echo "<div class=\"alert alert-danger alert-dismissible\" role=\"alert\">";
							echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
							echo "<strong>";
							echo " Please review following errors<br>";
							 foreach ($errors as $error) 
								{
								echo $error . "<br>";
								} 
							echo "</strong>";
							echo "</div>";
						
							echo "</div>";
							
							}
					
						?>
			
			<div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInUp">
                    <ul class="notes">
					<?php
					$connection=databaseconnectivity_open();
					if(isset($_GET["pinboard_id"]))
					{
						$query = "select * from pinboard where `user_id`=\"{$_SESSION["user_id"]}\" AND `pinboard_id` =\"{$_GET["pinboard_id"]}\" AND `delete`=\"false\" order by date asc";
						$result = mysqli_query($connection, $query);
						while($row = mysqli_fetch_assoc($result))
											{
											$time = $row["time"];
											$date = $row["date"];
											$title = $row["title"];
											$body = $row["body"];
											$pinboard_id = $row["pinboard_id"];
												echo "<li>";
													echo "<div>";
														echo "<small>{$time}&nbsp;&nbsp;&nbsp;{$date}</small>";
														echo "<h4>{$title}</h4>";
														echo "<p>{$body}</p>";
														echo "<a href=\"pinboard.php?pinboard_id={$pinboard_id}&delete=true\"><i class=\"fa fa-trash-o \"></i></a>";
													echo "</div>";
												echo "</li>";
											
											}
										mysqli_free_result($result);
					}
					else
					{
						$query = "select * from pinboard where `user_id`=\"{$_SESSION["user_id"]}\" AND `delete`=\"false\" order by date asc";
						$result = mysqli_query($connection, $query);
						while($row = mysqli_fetch_assoc($result))
											{
											$time = $row["time"];
											$date = $row["date"];
											$title = $row["title"];
											$body = $row["body"];
											$pinboard_id = $row["pinboard_id"];
												echo "<li>";
													echo "<div>";
														echo "<small>{$time}&nbsp;&nbsp;&nbsp;{$date}</small>";
														echo "<h4>{$title}</h4>";
														echo "<p>{$body}</p>";
														echo "<a href=\"pinboard.php?pinboard_id={$pinboard_id}&delete=true\"><i class=\"fa fa-trash-o \"></i></a>";
													echo "</div>";
												echo "</li>";
											
											}
										mysqli_free_result($result);
					}
					
				
					
					databaseconnectivity_close($connection);
					
					?>
					
                        
                        
                    </ul>
                </div>
            </div>
        </div>
			
			
		
        <!--
				contents in file footer.php
		-->
        <?php
			require_once("footer.php");
		?>

		
		
		
            
			<div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content animated flipInY">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title">Pin It!</h4>
							<small class="font-bold">Just use mouse / touch the knob.</small>
						</div>
						<div class="modal-body">
						
							<p>
							
							<form role="form" method="post" action="pinboard.php">
							<div class="form-group"><label>Title</label> <input name="title" type="text" placeholder="Title" class="form-control" required></div>
							<div class="form-group"><label>Body</label> <textarea placeholder="Body" name="body" class="form-control"required></textarea></div>
							
						
							<div class="form-group"><label>Time</label> </div>	
							
							<div style="display: inline; width: 85px; height: 85px;">
							<canvas width="85" height="85"></canvas>
							<input name="hour" type="text" value="01" class="dial m-r-sm" data-fgcolor="#1AB394" data-width="85" data-height="85" data-min="0" data-max="24" style="width: 46px; height: 28px; position: absolute; vertical-align: middle; margin-top: 28px; margin-left: -65px; border: 0px; font-weight: bold; font-style: normal; font-variant: normal; font-stretch: normal; font-size: 17px; line-height: normal; font-family: Arial; text-align: center; color: rgb(26, 179, 148); padding: 0px; -webkit-appearance: none; background: none;">
							</div>
			
							<div style="display: inline; width: 85px; height: 85px;">
							<canvas width="85" height="85"></canvas>
							<input name="minute" type="text" value="02" class="dial m-r-sm" data-fgcolor="#ED5565" data-width="85" data-height="85" data-cursor="true" data-thickness=".3/" data-min="0" data-max="60" style="width: 46px; height: 28px; position: absolute; vertical-align: middle; margin-top: 28px; margin-left: -65px; border: 0px; font-weight: bold; font-style: normal; font-variant: normal; font-stretch: normal; font-size: 17px; line-height: normal; font-family: Arial; text-align: center; color: rgb(237, 85, 101); padding: 0px; -webkit-appearance: none; background: none;">
							</div>
             
							<div style="display: inline; width: 85px; height: 85px;">
							<canvas width="85" height="85"></canvas>
							<input name="second" type="text" value="03" class="dial m-r-sm" data-fgcolor="#2D6665" data-width="85" data-height="85" data-cursor="true" data-thickness=".3/" data-min="0" data-max="60" style="width: 46px; height: 28px; position: absolute; vertical-align: middle; margin-top: 28px; margin-left: -65px; border: 0px; font-weight: bold; font-style: normal; font-variant: normal; font-stretch: normal; font-size: 17px; line-height: normal; font-family: Arial; text-align: center; color: rgb(237, 85, 101); padding: 0px; -webkit-appearance: none; background: none;">
							</div>
											
									
							<!--div class="form-group" id="data_3">
				
							<div class="input-group date">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input name="date" type="text" class="form-control" value="10/11/2013">
							</div>
							</div-->				
		
		
						<div class="form-group">
                                    <label>Date</label>
						
							<input type="date" name="date" class="form-control" required>
							
						</div>
                                
						   
						   <div>
							<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit" name="submit"><strong>Pin It!</strong></button>
								
							</div>
						   </form>
							</p>
						</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
								
							</div>
					</div>
				</div>
			</div>
            
		
		
        <div id="small-chat">
		
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">
				<i class="fa fa-plus"> </i>
			</button>
	
        </div>
		
		
		<?php
			require_once("rightsidebar.php");
		?>
    </div>
	<?php require_once("user_skin_config.php"); ?>
	<?php 
		require_once("comns/body_section.php")
	?>



   <!-- JSKnob -->
   <script src="js/plugins/jsKnob/jquery.knob.js"></script>

  

   <!-- Switchery -->
   <script src="js/plugins/switchery/switchery.js"></script>


  

	
	

    <script>
        $(document).ready(function(){
			$.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
			<?php $content = file_get_contents("js/custom.js.php"); echo $content?>

            var elem = document.querySelector('.js-switch');
            var switchery = new Switchery(elem, { color: '#1AB394' });

            var elem_2 = document.querySelector('.js-switch_2');
            var switchery_2 = new Switchery(elem_2, { color: '#ED5565' });

            var elem_3 = document.querySelector('.js-switch_3');
            var switchery_3 = new Switchery(elem_3, { color: '#1AB394' });


        });
    


        $(".dial").knob();

    

    </script>

	
</body>

</html>
