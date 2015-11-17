<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="contacts.php";
if(CONTACTS != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
if(isset($_POST["submit"]))
	{
	$contact_name = $_POST["contact_name"];
	$nick_name = $_POST["nick_name"];
	$address_1 = $_POST["address_1"];
	$country = $_POST["country"];
	$mobile_number_1 = $_POST["mobile_number_1"];
	$mobile_number_2 = $_POST["mobile_number_2"];
	$email_id_1 = $_POST["email_id_1"];
	$email_id_2 = $_POST["email_id_2"];
	$aniversary = $_POST["aniversary"];
	$birthday = $_POST["birthday"];
	$eskimi = $_POST["eskimi"];
	$twitter = $_POST["twitter"];
	$facebook = $_POST["facebook"];
	$flickr = $_POST["flickr"];
	$github = $_POST["github"];
	$google_plus = $_POST["google_plus"];
	$linkedin = $_POST["linkedin"];
	$instagram = $_POST["instagram"];
	$pinterest = $_POST["pinterest"];
	$skype = $_POST["skype"];
	$tumblr = $_POST["tumblr"];
	$vk = $_POST["vk"];
	$youtube = $_POST["youtube"];
	$website = $_POST["website"];
	$about = $_POST["about"];
	$user_id = $_SESSION["user_id"];
	$errors = array();
		if(!isset($contact_name))
			{
    		$errors["1"] = "Please fill necessary fields.";
			}
		if(empty($errors))
			{
			$connection=databaseconnectivity_open();
			$contact_name = clean_user_input($connection,$contact_name);
			$nick_name = clean_user_input($connection,$nick_name);
			$address_1 = clean_user_input($connection,$address_1);
			$country = clean_user_input($connection,$country);
			$mobile_number_1 = clean_user_input($connection,$mobile_number_1);
			$mobile_number_2 = clean_user_input($connection,$mobile_number_2);
			$email_id_1 = clean_user_input($connection,$email_id_1);
			$email_id_2 = clean_user_input($connection,$email_id_2);
			$aniversary = clean_user_input($connection,$aniversary);
			$birthday = clean_user_input($connection,$birthday);
			$eskimi = clean_user_input($connection,$eskimi);
			$twitter = clean_user_input($connection,$twitter);
			$facebook = clean_user_input($connection,$facebook);
			$flickr = clean_user_input($connection,$flickr);
			$github = clean_user_input($connection,$github);
			$google_plus = clean_user_input($connection,$google_plus);
			$linkedin = clean_user_input($connection,$linkedin);
			$instagram = clean_user_input($connection,$instagram);
			$pinterest = clean_user_input($connection,$pinterest);
			$skype = clean_user_input($connection,$skype);
			$tumblr = clean_user_input($connection,$tumblr);
			$vk = clean_user_input($connection,$vk);
			$youtube = clean_user_input($connection,$youtube);
			$website = clean_user_input($connection,$website);
			$about = clean_user_input($connection,$about);
			$user_id = $_SESSION["user_id"];

			$query = "INSERT INTO `contacts`";
			$query .="(contact_name,nick_name,address_1,country,mobile_number_1,mobile_number_2,email_id_1,email_id_2,aniversary,birthday,eskimi,twitter,facebook,flickr,github,google_plus,linkedin,instagram,pinterest,skype,tumblr,vk,youtube,website,about,user_id,profile_id,delete)";
			$query .="VALUES (\"{$contact_name}\",\"{$nick_name}\",\"{$address_1}\",\"{$country}\",\"{$mobile_number_1}\",\"{$mobile_number_2}\",\"{$email_id_1}\",\"{$email_id_2}\",\"{$aniversary}\",\"{$birthday}\",\"{$eskimi}\",\"{$twitter}\",\"{$facebook}\",\"{$flickr}\",\"{$github}\",\"{$google_plus}\",\"{$linkedin}\",\"{$instagram}\",\"{$pinterest}\",\"{$skype}\",\"{$tumblr}\",\"{$vk}\",\"{$youtube}\",\"{$website}\",\"{$about}\",\"{$user_id}\",\"0\",\"false\")";
			
		
			if (mysqli_query($connection, $query)) 
				{
					$_SESSION["message"]="contact added";
					$_SESSION["type"]=1;
				}
			databaseconnectivity_close($connection);
			}
	}
?>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Contacts</title>

  <?php 
	require_once("comns/head_section.php")
?>

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
                    <h2>Contacts</h2>
                    
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
			
			
        <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
		
		<?php
					
					
			$connection=databaseconnectivity_open();
		
			$query = "select * from contacts where `user_id`=\"{$_SESSION["user_id"]}\" AND `delete`=\"false\" order by contact_name asc";
			
			$result = mysqli_query($connection, $query);
			while($row = mysqli_fetch_assoc($result))
				{
				$contact_name = $row["contact_name"];
				$nick_name = $row["nick_name"];
				$address_1= $row["address_1"];
				$country = $row["country"];
				$email_id_1 = $row["email_id_1"];
				$mobile_number_1 = $row["mobile_number_1"];
				$birthday = $row["birthday"];
				$contact_id = $row["contact_id"];
				$contact_url = $row["contact_url"];
				?>
				
				<div class="col-lg-4">
                <div class="contact-box">
				<a href="profile.php?contact_id=<?php echo $contact_id;?>">
                    <div class="col-sm-4">
                        <div class="text-center">
						<?php
						if (file_exists($contact_url)) {
								echo '<img alt="image" class="img-circle m-t-xs img-responsive" src="'.$contact_url.'">';
								}
								else
								{
                                echo'<img alt="image" class="img-circle m-t-xs img-responsive" src="img/a2.jpg">';
								}
								?>
						
						
						
                            
                            <div class="m-t-xs font-bold"><?php echo $nick_name; ?></div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <h3><strong><?php echo $contact_name; ?></strong></h3>
                        <p><i class="fa fa-map-marker"></i> <?php echo $country; ?></p>
                        <address>
                            <?php echo $address_1; ?><br>
                            <abbr title="Phone">P:</abbr> <?php echo $mobile_number_1; ?>
                        </address>
						<p><i class="fa fa-birthday-cake"></i> <?php echo $birthday; ?></p>
                    </div>
                    <div class="clearfix"></div>
                        </a>
                </div>
            </div>
				
		<?php } 
			mysqli_free_result($result);
			databaseconnectivity_close($connection);
		?>

			
            <!--div class="col-lg-4">
                <div class="contact-box">
                    <a href="profile.php">
                    <div class="col-sm-4">
                        <div class="text-center">
                            <img alt="image" class="img-circle m-t-xs img-responsive" src="img/a1.jpg">
                            <div class="m-t-xs font-bold">CEO</div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <h3><strong>Alex Johnatan</strong></h3>
                        <p><i class="fa fa-map-marker"></i> Riviera State 32/106</p>
                        <address>
                            <strong>Twitter, Inc.</strong><br>
                            795 Folsom Ave, Suite 600<br>
                            San Francisco, CA 94107<br>
                            <abbr title="Phone">P:</abbr> (123) 456-7890
                        </address>
                    </div>
                    <div class="clearfix"></div>
                        </a>
                </div>
            </div-->
            
        </div>
        </div>
        <?php
			require_once("footer.php");
		?>

        </div>
		
		
		
		<div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content animated flipInY">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title">Add contact</h4>
							<small class="font-bold">Contact name is mandatory.</small>
						</div>
						<div class="modal-body">
						
							<p>
							
							<form role="form" method="post" action="contacts.php">
							<div class="form-group">
								<label>Contact Name</label> 
								<input name="contact_name" type="text" placeholder="Contact Name" class="form-control" required></div>
							
							<div class="form-group">
								<label>Nick Name</label> 
								<input name="nick_name" type="text" placeholder="Nick Name" class="form-control"></div>
								
							<div class="form-group">
								<label>Address</label> 
								<textarea placeholder="Address" name="address_1" class="form-control"></textarea></div>
								
							<div class="form-group">
								<label>Country</label> 
								<input name="country" type="text" placeholder="Country" class="form-control"></div>
								
							<div class="form-group">
								<label>Mobile Number #1</label> 
								<input name="mobile_number_1" type="text" placeholder="Mobile Number" class="form-control"></div>
								
							<div class="form-group">
								<label>Mobile Number #2</label> 
								<input name="mobile_number_2" type="text" placeholder="Mobile Number" class="form-control"></div>
								
							<div class="form-group">
								<label>Email Address #1</label> 
								<input name="email_id_1" type="email" placeholder="Email Address" class="form-control"></div>
								
							<div class="form-group">
								<label>Email Address #2</label> 
								<input name="email_id_2" type="email" placeholder="Email Address" class="form-control"></div>
								
							<div class="form-group">
								<label>Aniversary</label> 
								<input name="aniversary" type="date" placeholder="" class="form-control"></div>
								
							<div class="form-group">
								<label>Birthday</label> 
								<input name="birthday" type="date" placeholder="" class="form-control"></div>
								
							<div class="form-group">
								<label>Eskimi</label> 
								<input name="eskimi" type="url" placeholder="Eskimi" class="form-control"></div>
								
							<div class="form-group">
								<label>Twitter</label> 
								<input name="twitter" type="url" placeholder="Twitter" class="form-control"></div>
								
							<div class="form-group">
								<label>Facebook</label> 
								<input name="facebook" type="url" placeholder="Facebook" class="form-control"></div>
								
							<div class="form-group">
								<label>Flickr</label> 
								<input name="flickr" type="url" placeholder="Flickr" class="form-control"></div>
								
							<div class="form-group">
								<label>Github</label> 
								<input name="github" type="url" placeholder="Github" class="form-control"></div>
								
							<div class="form-group">
								<label>Google+</label> 
								<input name="google_plus" type="url" placeholder="Google+" class="form-control"></div>
								
							<div class="form-group">
								<label>LinkedIn</label> 
								<input name="linkedin" type="url" placeholder="LinkedIn" class="form-control"></div>
								
							<div class="form-group">
								<label>Instagram</label> 
								<input name="instagram" type="url" placeholder="Instagram" class="form-control"></div>
								
							<div class="form-group">
								<label>PInterest</label> 
								<input name="pinterest" type="url" placeholder="PInterest" class="form-control"></div>
							
							<div class="form-group">
								<label>Skype</label> 
								<input name="skype" type="text" placeholder="Skype" class="form-control"></div>
								
							<div class="form-group">
								<label>Tumblr</label> 
								<input name="tumblr" type="url" placeholder="Tumblr" class="form-control"></div>
								
							<div class="form-group">
								<label>VK</label> 
								<input name="vk" type="url" placeholder="VK" class="form-control"></div>
								
							<div class="form-group">
								<label>Youtube</label> 
								<input name="youtube" type="url" placeholder="Youtube" class="form-control"></div>
								
							<div class="form-group">
								<label>Website</label> 
								<input name="website" type="url" placeholder="Website" class="form-control"></div>
							
				
							
							<div class="form-group">
								<label>About</label> 
								<textarea placeholder="About" name="about" class="form-control"></textarea></div>

						   <div>
							<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit" name="submit"><strong>Add contact!</strong></button>
								
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

    <script>
        $(document).ready(function(){
            $('.contact-box').each(function() {
                animationHover(this, 'pulse');
            });
			$.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
			<?php $content = file_get_contents("js/custom.js.php"); echo $content?>
        });
    </script>

</body>
</html>
