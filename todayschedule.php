<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="todayschedule.php";

if(TODAY_SCHEDULE != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>
<?php 

if(isset($_GET["delete"]))
	{
		if($_GET["delete"]==true && isset($_GET["schedule_id"]))
		{
		$connection=databaseconnectivity_open();
		$schedule_id=clean_user_input($connection,$_GET["schedule_id"]);
		$query="UPDATE `schedule` SET `delete`=\"true\" WHERE schedule_id=\"{$schedule_id}\" AND user_id=\"{$_SESSION["user_id"]}\"";
	
			if(mysqli_query($connection, $query)) 
			{
				$_SESSION["message"]="schedule deleted";
				$_SESSION["type"]=2;
				redirect_to("todayschedule.php");
			} 
			else 
			{
				$_SESSION["message"]="Illegal operation";
				$_SESSION["type"]=3;
			}
				
			databaseconnectivity_close($connection);	
		}
	}
	
	?>

<?php 
if(isset($_POST)==true && empty($_POST)==false)
{
				$chkbox = $_POST['chk'];
				$date = $_POST['date'];
				$message = $_POST['message'];
				
				$BX_TIME=$_POST['BX_TIME'];
				$BX_DESCRIPTION=$_POST['BX_DESCRIPTION'];			
				$BX_FAVICON=$_POST['BX_FAVICON'];
				$BX_FAVCOLOR=$_POST['BX_FAVCOLOR'];		
?>
<?php
if((!isset($_POST["date"]))||(!isset($_POST["message"])))
			{
    		$errors["1"] = "Please fill necessary fields.";
		
			}
if(empty($errors))
			{
	foreach($BX_TIME as $a => $b){
	
	
	$connection=databaseconnectivity_open();
	/*
	echo $BX_TIME[$a];
	echo $BX_DESCRIPTION[$a];
	echo $BX_FAVICON[$a];
	echo $BX_FAVCOLOR[$a];
	echo $date;
	echo $message;
	*/
	
	$a+1; 
	$time=clean_user_input($connection,$BX_TIME[$a]);
	$description=clean_user_input($connection,$BX_DESCRIPTION[$a]);
	$fav_icon=clean_user_input($connection,$BX_FAVICON[$a]);
	$fav_color=clean_user_input($connection,$BX_FAVCOLOR[$a]);
	$date=clean_user_input($connection,$date);
	$message=clean_user_input($connection,$message[$a]);
	/*
	echo $time;
	echo $description;
	echo $fav_icon;
	echo $fav_color;
	echo $date;
	echo $message;
	
	*/
	
	$query = "INSERT INTO `schedule`";
	$query .="(user_id,date,time,description,fav_icon,fav_color,message,`delete`)";
	$query .=" VALUES (\"{$_SESSION["user_id"]}\",\"{$date}\",\"{$time}\",\"{$description}\",\"{$fav_icon}\",\"{$fav_color}\",\"{$message}\",\"false\")";

		if (mysqli_query($connection, $query)) 
				{
					$_SESSION["message"]="Schedule added";
					$_SESSION["type"]=1;
				}
	databaseconnectivity_close($connection);
	}
	

	}
	
}	
?>	
<?php

if(isset($_POST["modifyschedule"]))
{
	$connection=databaseconnectivity_open();
	$schedule_id=clean_user_input($connection,$_POST["schedule_id"]);
	$date=clean_user_input($connection,$_POST["date"]);
	$time=clean_user_input($connection,$_POST["time"]);
	$description=clean_user_input($connection,$_POST["description"]);
	$fav_icon=clean_user_input($connection,$_POST["fav_icon"]);
	$fav_color=clean_user_input($connection,$_POST["fav_color"]);
	$message=clean_user_input($connection,$_POST["message"]);
	
	$query="update schedule set`date`=\"{$date}\", time=\"{$time}\", description=\"{$description}\", fav_icon=\"{$fav_icon}\", fav_color=\"{$fav_color}\", message=\"{$message}\" where user_id=\"{$_SESSION["user_id"]}\" AND schedule_id=\"{$schedule_id}\"";	
		if (mysqli_query($connection, $query)) 
		{
		$_SESSION["message"]="Schedule modified";
		$_SESSION["type"]=1;
		databaseconnectivity_close($connection);
		
		}
	databaseconnectivity_close($connection);
	
}
?>
	
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | In Timeline</title>

<?php 
	require_once("comns/head_section.php")
?>
	
	<script type="text/javascript" src="js/script.js"></script> 
	<style>
		table, th, td {
			border: 0px solid black;
		}
</style>

</head>

<body>

    <div id="wrapper">

    <!--
			contents in file leftnavigationbar.php
	-->
	
	<?php
		require_once("leftnavigationbar.php");
	?>


        <div id="page-wrapper" class="gray-bg">
        <!--
			contents in file search.php
		-->
        <?php
			require_once("search.php");
		?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Today Schedule</h2>
                
            </div>
            <div class="col-lg-2">

            </div>
			
        </div>
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
        <div class="wrapper wrapper-content">
            <div class="row animated fadeInRight">
                <div class="col-lg-12">
				<?php
					if(isset($_GET["schedule_id"]))
					{
						echo"<div class=\"ibox float-e-margins\">";
						
						
						echo"<div class=\"row\">
                                <div class=\"col-lg-12\">
                                    <div class=\"panel panel-info\">
                                        <div class=\"panel-heading\">
                                            <i class=\"fa fa-info-circle\"></i> Schedule
                                        </div>
                                        <div class=\"panel-body\">";
										
											$connection=databaseconnectivity_open();
											$schedule_id=clean_user_input($connection,$_GET["schedule_id"]);
											$query = "select * from schedule where `user_id`=\"{$_SESSION["user_id"]}\" AND `schedule_id`=\"{$schedule_id}\" AND `delete`=\"false\" LIMIT 1";
											$result = mysqli_query($connection, $query);
											while($row = mysqli_fetch_assoc($result))
											{
												$schedule_id=$row["schedule_id"];
												$date=$row["date"];
												$time=$row["time"];
												$description=$row["description"];
												$fav_icon=$row["fav_icon"];
												$fav_color=$row["fav_color"];
												$message=$row["message"];
											}
											mysqli_free_result($result);
											databaseconnectivity_close($connection);
											?>
                                            <p>
											
										<form role="form" method="post" action="todayschedule.php">
											
												<div class="form-group"><label>Date</label>
													<input type="hidden" class="form-control" name="schedule_id" value="<?php echo $schedule_id; ?>">
													<input type="date" class="form-control" name="date" value="<?php echo $date; ?>">
												</div>
												<div class="form-group"><label>Time</label>
													<input type="time" class="form-control" name="time" value="<?php echo $time; ?>">
												</div>
							
														
												<div class="form-group"><label>Favourite Icon</label>
													<select name="fav_icon" required="required" class="form-control m-b">
														<option value="<?php echo $fav_icon; ?>"><?php echo $fav_icon; ?></option>
														<option value="fa-at">Email</option>
														<option value="fa-bicycle">Cycling</option>
														<option value="fa-birthday-cake">Birthday</option>
														<option value="fa-briefcase">Meeting</option>
														<option value="fa-file-text">Notes</option>
														<option value="fa-coffee">Coffee</option>
														<option value="fa-phone">Phone</option>
														<option value="fa-user-md">Doctor</option>
														<option value="fa-futbol-o">Sports</option>
														<option value="fa-bank">Bank</option>
														<option value="fa-building">Company</option>
														<option value="fa-child">Child</option>
														<option value="fa-database">Database</option>
														<option value="fa-envelop-square">Envelop</option>
														<option value="fa-camera">Camera</option>
														<option value="fa-cloud">Cloud</option>
														<option value="fa-comments">Chat</option>
														<option value="fa-credit-card">Credit Bills</option>
														<option value="fa-users">Clients</option>
														<option value="fa-user">Client</option>
														<option value="fa-code">Code</option>
														<option value="fa-heart-o">Heart</option>
														<option value="fa-moon-o">Moon</option>
														<option value="fa-sun-o">Sun</option>
														
														
													</select>
												</div>
														 
												<div class="form-group"><label>Favourite Color</label>
													<select name="fav_color" required="required" class="form-control m-b">
														<option value="<?php echo $fav_color; ?>"><?php echo $fav_color; ?></option>
														<option value="navy-bg">Navy</option>
														<option value="lazur-bg">Lazur</option>
														<option value="yellow-bg">Yellow</option>
														<option value="blue-bg">Blue</option>
																
													</select>
												</div>
														
												<div class="form-group"><label>Message</label>
													<textarea name="message" class="form-control m-b"><?php echo nl2br($message); ?></textarea>
												</div>
												<div class="form-group"><label>Description</label>
													<textarea name="description" class="form-control m-b"><?php echo nl2br($description); ?></textarea>
												</div>		
												
												
													
							
							<div class="form-group">
								<a href="todayschedule.php?delete=true&schedule_id=<?php echo $schedule_id; ?>"><button class="btn btn-outline btn-danger pull-right m-t-n-xs"><strong>Delete Schedule!</strong></button></a>
								<button class="btn btn-outline btn-primary pull-left m-t-n-xs" type="submit" name="modifyschedule"><strong>Modify Schedule!</strong></button>
							</div>	
							</div>
						   </form>	
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											
											</p>
                                        <?php echo"</div>

                                    </div>
                                </div>";
						
						echo"</div>";
					}
				
				
				
				?>
                <?php
				if(!isset($_GET["schedule_id"])&&!isset($_GET["delete"]))
					{
				echo '<div class="ibox float-e-margins">
                    <div class="text-center float-e-margins p-md">
                    <span>Turn on/off color/background or orientation version: </span>
                    <a href="#" class="btn btn-xs btn-primary" id="lightVersion">Light version</a>
                    <a href="#" class="btn btn-xs btn-primary" id="darkVersion">Dark version</a>
                    <a href="#" class="btn btn-xs btn-primary" id="leftVersion">Left version</a>
                    </div>
                    <div class="ibox-content" id="ibox-content">';

                     
					
						$connection=databaseconnectivity_open();
						$sysdate=date("Y-m-d");
					
						$query = "select * from schedule where `user_id`=\"{$_SESSION["user_id"]}\" AND `date`=\"{$sysdate}\" AND `delete`=\"false\" order by time asc";
						
						$result = mysqli_query($connection, $query);
						if(mysqli_num_rows($result) <= 0)
						{
						echo"<div class=\"alert alert-info alert-dismissable\">
                                <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
                              <center>No Schedule</center>
                            </div>";
							
						}
						echo '<div id="vertical-timeline" class="vertical-container dark-timeline center-orientation">';
						while($row = mysqli_fetch_assoc($result))
							{
							$date = $row["date"];
							$time = $row["time"];
							$description= $row["description"];
							$fav_icon = $row["fav_icon"];
							$fav_color = $row["fav_color"];
							$schedule_id = $row["schedule_id"];
							$message = $row["message"];
							
							
				
				
                            echo"<div class=\"vertical-timeline-block\">
                                <div class=\"vertical-timeline-icon {$fav_color}\">
                                    <i class=\"fa {$fav_icon} \"></i>
                                </div>

                                <div class=\"vertical-timeline-content\">
                                    <h2>{$message}</h2>
                                    <p>
									{$description}
                                    </p>
									<p>
									<a href=\"todayschedule.php?schedule_id=$schedule_id\"><button class=\"btn btn-outline btn-primary pull-left m-t-n-xs\"><strong>Modify Schedule!</strong></button></a>
									<a href=\"todayschedule.php?delete=true&schedule_id=$schedule_id\"><button class=\"btn btn-outline btn-danger pull-right m-t-n-xs\"><strong>Delete Schedule!</strong></button></a>
									</p>
                                    <!--a href=\"#\" class=\"btn btn-sm btn-primary\"> More info</a-->
                                    <span class=\"vertical-date\">
                                        Today <br/>
                                        <small>{$date} | {$time}</small>";
                                    echo'</span>
                                </div>
                            </div>';
						
							} 
						mysqli_free_result($result);
						databaseconnectivity_close($connection);
						
                           
                        echo'</div>';

                    echo'</div>';
                echo'</div>';
					}
				?>
            </div>
            </div>
        </div>
        <?php require_once("footer.php"); ?>
                <!--
                	content in footer.php
                -->

        </div>
		
		
		
		
		
		
		
		
		
		<div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content animated flipInY">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title">Add Schedule</h4>
							<small class="font-bold">Date and Message are mandatory.</small>
						</div>
						<div class="modal-body">
						
							<p>
							
							<form role="form" method="post" action="todayschedule.php">
							<div class="form-group">
								<label class="col-lg-2 control-label">Date</label> 
								<input name="date" type="date" placeholder="Date" class="form-control" required></div>
							
							<p> 
								<!--button class="btn btn-sm btn-primary pull-right m-t-n-xs" onClick="addRow('dataTable')"><strong>Add</strong></button>
								<button class="btn btn-sm btn-danger pull-right m-t-n-xs" onClick="deleteRow('dataTable')"><strong>Delete</strong></button-->
								
								<input type="button" class="btn btn-sm btn-primary" value="Add" onClick="addRow('dataTable')" /> 
								<input type="button" class="btn btn-sm btn-danger" value="Remove" onClick="deleteRow('dataTable')"  />
								<p>(All actions apply only to entries with check marked check boxes only.)</p>
							</p>
				
				
								<table id="dataTable" class="form" border="1">
								  <tbody>
									<tr>
									  <p>
										<td><input type="checkbox" name="chk[]" checked="checked" /></td>
										<td>
											<label class="col-lg-2 control-label">Time &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
											<input type="time" class="form-control" required="required" name="BX_TIME[]">
										 </td>
										 
										 <td>
											<label for="BX_FAVICON" class="col-lg-2 control-label">Favourite Icon</label>
											<select id="BX_FAVICON" class="input-sm form-control input-s-sm inline" name="BX_FAVICON[]" required="required">
												<option>....</option>
												<option value="fa-at">Email</option>
												<option value="fa-bicycle">Cycling</option>
												<option value="fa-birthday-cake">Birthday</option>
												<option value="fa-briefcase">Meeting</option>
												<option value="fa-file-text">Notes</option>
												<option value="fa-coffee">Coffee</option>
												<option value="fa-phone">Phone</option>
												<option value="fa-user-md">Doctor</option>
												<option value="fa-futbol-o">Sports</option>
												<option value="fa-bank">Bank</option>
												<option value="fa-building">Company</option>
												<option value="fa-child">Child</option>
												<option value="fa-database">Database</option>
												<option value="fa-envelop-square">Envelop</option>
												<option value="fa-camera">Camera</option>
												<option value="fa-cloud">Cloud</option>
												<option value="fa-comments">Chat</option>
												<option value="fa-credit-card">Credit Bills</option>
												<option value="fa-users">Clients</option>
												<option value="fa-user">Client</option>
												<option value="fa-code">Code</option>
												<option value="fa-heart-o">Heart</option>
												<option value="fa-moon-o">Moon</option>
												<option value="fa-sun-o">Sun</option>
												
												
											</select>
										 </td>
										 <td>
											<label for="BX_FAVCOLOR" class="col-lg-2 control-label">Favourite Color</label>
											<select id="BX_FAVCOLOR" name="BX_FAVCOLOR[]" class="input-sm form-control input-s-sm inline" required="required">
												<option value="navy-bg">Navy</option>
												<option value="lazur-bg">Lazur</option>
												<option value="yellow-bg">Yellow</option>
												<option value="blue-bg">Blue</option>
												
											</select>
										 </td>
										 
											</p>
									
										<td>
										
										<label class="col-lg-2 control-label">Message</label> 
										<textarea placeholder="Message" name="message[]" class="form-control" required="required"></textarea>
										</td>
										
										<td>
										<label for="BX_DESCRIPTION">Description</label>
										<textarea required="required" class="form-control"  name="BX_DESCRIPTION[]"></textarea>
										</td>
									
									</tr>
									</tbody>
								</table>   
								
							<div>
							<div class="form-group">
								<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit" name="submit"><strong>Add Schedule!</strong></button>
							</div>	
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


    <!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js"></script>


    <!-- Peity -->
    <script src="js/demo/peity-demo.js"></script>


		
    <script>
        $(document).ready(function(){
			$.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
			<?php $content = file_get_contents("js/custom.js.php"); echo $content?>


            // Local script for demo purpose only
            $('#lightVersion').click(function(event) {
                event.preventDefault()
                $('#ibox-content').removeClass('ibox-content');
                $('#vertical-timeline').removeClass('dark-timeline');
                $('#vertical-timeline').addClass('light-timeline');
            });

            $('#darkVersion').click(function(event) {
                event.preventDefault()
                $('#ibox-content').addClass('ibox-content');
                $('#vertical-timeline').removeClass('light-timeline');
                $('#vertical-timeline').addClass('dark-timeline');
            });

            $('#leftVersion').click(function(event) {
                event.preventDefault()
                $('#vertical-timeline').toggleClass('center-orientation');
            });


        });
    </script>


</body>
</html>
