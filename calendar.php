<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="calendar.php";
if(CALENDAR != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>
<?php
if(isset($_GET["delete"]))
	{
	$connection=databaseconnectivity_open();
	$query="UPDATE `calendar` SET `delete`=\"true\" WHERE calendar_id={$_GET["delete"]} AND user_id={$_SESSION["user_id"]}";
	if(mysqli_query($connection, $query)) 
	{
    $_SESSION["message"]="Calendar Event deleted";
	$_SESSION["type"]=2;
	} 
	else 
	{
    $_SESSION["message"]="Illegal operation";
	$_SESSION["type"]=3;
	}
		
	databaseconnectivity_close($connection);
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
    		$errors["1"] = "Please fill all fields for Add calendar event";
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
			$time = $hour.":".$minute.":".$second;
			$query = "INSERT INTO `calendar`(`user_id`, `title`, `time`, `date`,`body`,`delete`) VALUES (\"";
			$query .= $_SESSION["user_id"] ."\",\"";
			$query .= $title ."\",\"";
			$query .= $time ."\",\"";
			$query .= $date ."\" ,\"{$body}\",\"false\" )";
			if (mysqli_query($connection, $query)) 
				{
					$_SESSION["message"]="Calendar Event added";
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

    <title><?php echo SITE_NAME; ?> | Calendar</title>


<?php 
	require_once("comns/head_section.php")
?>
  

    <link href="css/plugins/switchery/switchery.css" rel="stylesheet">

	<link rel="stylesheet" href="css/calendar.css">

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
                <div class="col-lg-9">
                    <h2>Calendar</h2>
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
						
	<?php
	
	if(!isset($_GET["calendar_id"]))
		{

		?>	<div class="row">
				<div class="col-lg-12">
					<div class="wrapper wrapper-content animated fadeInUp">
						<div class="page-header">
							<h3></h3>
							<div class="pull-left form-inline">
								<div class="btn-group">
									<button class="btn btn-primary" data-calendar-nav="prev"><< Prev</button>
									<button class="btn btn-default" data-calendar-nav="today">Today</button>
									<button class="btn btn-primary" data-calendar-nav="next">Next >></button>
								</div>
							</div>
							<div div class="pull-right form-inline">
								<div class="btn-group">
									<button class="btn btn-warning" data-calendar-view="year">Year</button>
									<button class="btn btn-warning active" data-calendar-view="month">Month</button>
									<button class="btn btn-warning" data-calendar-view="week">Week</button>
									<button class="btn btn-warning" data-calendar-view="day">Day</button>
								</div>
								
							</div>
							
							
						
		
						</div>
						<br>
							<label class="checkbox">
										<input type="checkbox" value="#events-modal" id="events-in-modal">Open events in modal window 
									</label>
						<div class="row">
							<div class="col-md-9">
							<div id="calendar">
							</div>
							</div>
							<div class="col-md-3">
								<div class="row">
									<!--select id="first_day" class="form-control">
										<option value="" selected="selected">First day of week language-dependant</option>
										<option value="2">First day of week is Sunday</option>
										<option value="1">First day of week is Monday</option>
									</select-->
									<!--select id="language" class="form-control">
										<option value="">Select Language (default: en-US)</option>
										<option value="bg-BG">Bulgarian</option>
										<option value="nl-NL">Dutch</option>
										<option value="fr-FR">French</option>
										<option value="de-DE">German</option>
										<option value="el-GR">Greek</option>
										<option value="hu-HU">Hungarian</option>
										<option value="id-ID">Bahasa Indonesia</option>
										<option value="it-IT">Italian</option>
										<option value="pl-PL">Polish</option>
										<option value="pt-BR">Portuguese (Brazil)</option>
										<option value="ro-RO">Romania</option>
										<option value="es-CO">Spanish (Colombia)</option>
										<option value="es-MX">Spanish (Mexico)</option>
										<option value="es-ES">Spanish (Spain)</option>
										<option value="ru-RU">Russian</option>
										<option value="sk-SR">Slovak</option>
										<option value="sv-SE">Swedish</option>
										<option value="ko-KR">Korean</option>
										<option value="zh-TW">????</option>
										<option value="th-TH">Thai (Thailand)</option>
									</select-->
									
									<!--label class="checkbox">
										<input type="checkbox" id="format-12-hours"> 12 Hour format
									</label>
									<label class="checkbox">
										<input type="checkbox" id="show_wb" checked> Show week box
									</label>
									<label class="checkbox">
										<input type="checkbox" id="show_wbn" checked> Show week box number
									</label-->
								</div>
								
								<h4>Events</h4>
								
								<ul id="eventlist" class="nav nav-list"></ul>
								<p>
                           
                            <button type="button" class="btn btn-w-m btn-success">Calendar Events</button>
                            <button type="button" class="btn btn-w-m btn-info">Profile Events &nbsp; &nbsp;</button>
                            <button type="button" class="btn btn-w-m btn-warning">Schedule Events</button>
                           
        </p>
								
							</div>
						</div>

						<div class="clearfix"></div>
						<br><br>
						<div id="disqus_thread"></div>
						

						<div class="modal fade" id="events-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h3 class="modal-title">Event</h3>
									</div>
									<div class="modal-body" style="height: 400px">
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>
						
						
						
						
					</div>
					
                </div>
				
            </div>
        
		<?php
		}
		
		if(isset($_GET["calendar_id"]))
		{
		$connection=databaseconnectivity_open();
		$query="SELECT * FROM `calendar` WHERE calendar_id={$_GET["calendar_id"]} AND user_id={$_SESSION["user_id"]} AND `delete`=\"false\" LIMIT 1";
		$result = mysqli_query($connection, $query);
		while($row = mysqli_fetch_assoc($result))
		{
		?>	<div class="widget lazur-bg p-xl">

                                <h3>
                                    <?php echo $row["title"]; ?>
                                </h3>
                        <ul class="list-unstyled m-t-md">
                            
							<li>
                                
                                <label>Body</label>
                                <?php echo $row["body"]; ?>
                            </li>
                            <li>
                                
                                <label>Date:</label>
                               <?php echo $row["date"]; ?>
                            </li>
                            <li>
                                
                                <label>Time:</label>
                                <?php echo $row["time"]; ?>
                            </li>
                        </ul>
						<div div class="pull-right form-inline">
								<div class="btn-group">
								<a href="calendar.php?delete=<?php echo $row["calendar_id"]; ?>"><button type="button" class="btn btn-warning btn-sm btn-block"><i class="fa fa-trash-o">  </i>&nbsp; Delete</button></a>
								</div>
								
						</div>

                    </div>
		<?php	
		databaseconnectivity_close($connection);
		}
		}
		?>		
			
		
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
							<h4 class="modal-title">Add Calendar Event!</h4>
							<small class="font-bold">Just use mouse / touch the knob.</small>
						</div>
						<div class="modal-body">
						
							<p>
							
							<form role="form" method="post" action="calendar.php">
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
							<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit" name="submit"><strong>Submit!</strong></button>
								
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


	
	<!--script type="text/javascript" src="components/jquery/jquery.min.js"></script-->
	<script type="text/javascript" src="components/underscore/underscore-min.js"></script>
	<!--script type="text/javascript" src="components/bootstrap3/js/bootstrap.min.js"></script-->
	<script type="text/javascript" src="components/jstimezonedetect/jstz.min.js"></script>
	<!--script type="text/javascript" src="js/language/bg-BG.js"></script>
	<script type="text/javascript" src="js/language/nl-NL.js"></script>
	<script type="text/javascript" src="js/language/fr-FR.js"></script>
	<script type="text/javascript" src="js/language/de-DE.js"></script>
	<script type="text/javascript" src="js/language/el-GR.js"></script>
	<script type="text/javascript" src="js/language/it-IT.js"></script>
	<script type="text/javascript" src="js/language/hu-HU.js"></script>
	<script type="text/javascript" src="js/language/pl-PL.js"></script>
	<script type="text/javascript" src="js/language/pt-BR.js"></script>
	<script type="text/javascript" src="js/language/ro-RO.js"></script>
	<script type="text/javascript" src="js/language/es-CO.js"></script>
	<script type="text/javascript" src="js/language/es-MX.js"></script>
	<script type="text/javascript" src="js/language/es-ES.js"></script>
	<script type="text/javascript" src="js/language/ru-RU.js"></script>
	<script type="text/javascript" src="js/language/sk-SR.js"></script>
	<script type="text/javascript" src="js/language/sv-SE.js"></script>
	<script type="text/javascript" src="js/language/zh-TW.js"></script>
	<script type="text/javascript" src="js/language/cs-CZ.js"></script>
	<script type="text/javascript" src="js/language/ko-KR.js"></script>
	<script type="text/javascript" src="js/language/id-ID.js"></script>
	<script type="text/javascript" src="js/language/th-TH.js"></script-->
	<script type="text/javascript" src="js/calendar.js"></script>
	<script type="text/javascript" src="js/app.js"></script>
	
	
	

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
