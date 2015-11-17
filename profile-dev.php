<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="profile.php";
if(PROFILE != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>
<?php

if(isset($_GET["delete"]))
	{
		if($_GET["delete"]==true && isset($_GET["contact_id"]))
		{
			$connection=databaseconnectivity_open();
			$query="DELETE FROM `contacts` WHERE contact_id={$_GET["contact_id"]} AND user_id={$_SESSION["user_id"]}";
			if(mysqli_query($connection, $query)) 
			{
			$_SESSION["message"]="Contact deleted";
			$_SESSION["type"]=3;
			redirect_to("contacts.php");
			} 
			else 
			{
			$_SESSION["message"]="Illegal operation";
			$_SESSION["type"]=2;
			}
		}
	}
if(isset($_GET["contact_id"]))
{
	$connection=databaseconnectivity_open();
	$contact_id=clean_user_input($connection,$_GET["contact_id"]);
	$query = "select * from contacts where contact_id=\"{$contact_id}\" AND user_id=\"{$_SESSION["user_id"]}\" LIMIT 1";
	$result = mysqli_query($connection, $query);
	while($row = mysqli_fetch_assoc($result))
	{
		$contact_id = $row["contact_id"];
		$contact_name = $row["contact_name"];
		$nick_name = $row["nick_name"];
		$address_1 = $row["address_1"];
		$country = $row["country"];
		$mobile_number_1 = $row["mobile_number_1"];
		$mobile_number_2 = $row["mobile_number_2"];
		$email_id_1 = $row["email_id_1"];
		$email_id_2 = $row["email_id_2"];
		$aniversary = $row["aniversary"];
		$birthday = $row["birthday"];
		$eskimi = $row["eskimi"];
		$twitter = $row["twitter"];
		$facebook = $row["facebook"];
		$flickr = $row["flickr"];
		$github = $row["github"];
		$google_plus = $row["google_plus"];
		$linkedin = $row["linkedin"];
		$instagram = $row["instagram"];
		$pinterest = $row["pinterest"];
		$skype = $row["skype"];
		$tumblr = $row["tumblr"];
		$vk = $row["vk"];
		$youtube = $row["youtube"];
		$website = $row["website"];
		$about = $row["about"];
		$user_id = $row["user_id"];
		$contact_url = $row["contact_url"];
		$profile_id= $row["profile_id"];
	}
	mysqli_free_result($result);
	databaseconnectivity_close($connection);
}
if(!isset($_GET["contact_id"]))
{
	$connection=databaseconnectivity_open();
	$query = "select * from contacts where profile_id=\"{$_SESSION["user_id"]}\" AND user_id=\"{$_SESSION["user_id"]}\" LIMIT 1";
	$result = mysqli_query($connection, $query);
	while($row = mysqli_fetch_assoc($result))
	{
		$contact_id = $row["contact_id"];
		$contact_name = $row["contact_name"];
		$nick_name = $row["nick_name"];
		$address_1 = $row["address_1"];
		$country = $row["country"];
		$mobile_number_1 = $row["mobile_number_1"];
		$mobile_number_2 = $row["mobile_number_2"];
		$email_id_1 = $row["email_id_1"];
		$email_id_2 = $row["email_id_2"];
		$aniversary = $row["aniversary"];
		$birthday = $row["birthday"];
		$eskimi = $row["eskimi"];
		$twitter = $row["twitter"];
		$facebook = $row["facebook"];
		$flickr = $row["flickr"];
		$github = $row["github"];
		$google_plus = $row["google_plus"];
		$linkedin = $row["linkedin"];
		$instagram = $row["instagram"];
		$pinterest = $row["pinterest"];
		$skype = $row["skype"];
		$tumblr = $row["tumblr"];
		$vk = $row["vk"];
		$youtube = $row["youtube"];
		$website = $row["website"];
		$about = $row["about"];
		$user_id = $row["user_id"];
		$profile_id = $row["profile_id"];
		$contact_url = $row["contact_url"];
	}
	mysqli_free_result($result);
	databaseconnectivity_close($connection);
}

if(isset($_POST["edit"]))
{
	$contact_id = $_POST["contact_id"];
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

			$query = "UPDATE `contacts` SET ";
			$query .="contact_name=\"{$contact_name}\",nick_name=\"{$nick_name}\",address_1=\"{$address_1}\",country=\"{$country}\",";
			$query .="mobile_number_1=\"{$mobile_number_1}\",mobile_number_2=\"{$mobile_number_2}\",email_id_1=\"{$email_id_1}\",";
			$query .="email_id_2=\"{$email_id_2}\",aniversary=\"{$aniversary}\",birthday=\"{$birthday}\",eskimi=\"{$eskimi}\",twitter=\"{$twitter}\"";
			$query .=",facebook=\"{$facebook}\",flickr=\"{$flickr}\",github=\"{$google_plus}\",google_plus=\"{$github}\",linkedin=\"{$linkedin}\"";
			$query .=",instagram=\"{$instagram}\",pinterest=\"{$pinterest}\",skype=\"{$skype}\",tumblr=\"{$tumblr}\",vk=\"{$vk}\",youtube=\"{$youtube}\"";
			$query .=",website=\"{$website}\",about=\"{$about}\" where contact_id=\"{$contact_id}\"";
		
		
			if (mysqli_query($connection, $query)) 
				{
					$_SESSION["message"]="contact updated";
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

    <title><?php echo SITE_NAME; ?> | Profile</title>

<?php 
	require_once("comns/head_section.php")
?>
    <link href="css/plugins/cropper/cropper.min.css" rel="stylesheet">



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
                    <h2>Profile</h2>
                    
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
                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Profile Detail</h5>
                        </div>
						
						
						
			
                        <div>
                            <div class="ibox-content no-padding border-left-right">
								<div class="btn btn-primary" data-target="#cropper-modal" data-toggle="modal">
                                   
								<?php
								//echo $contact_url;
								if (file_exists($contact_url)) {
								echo '<img alt="avatar" class="img-responsive" src="'.$contact_url.'" data-toggle="modal" data-target="#avatar-modal">';
								}
								else
								{
                                echo'<img alt="avatar" class="img-responsive" src="img/profile_big.jpg" data-toggle="modal" data-target="#avatar-modal">';
								}
								?>
								</div>
								
								
								
								
								
								
								
		<div class="modal fade" id="cropper-modal" aria-hidden="true" aria-labelledby="bootstrap-modal-label" role="dialog" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
			<form class="avatar-form" action="crop.php" enctype="multipart/form-data" method="post">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="bootstrap-modal-label">Cropper</h4>
              </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="image-crop">
                                    <img src="img/p3.jpg">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Preview image</h4>
                                <div class="img-preview img-preview-sm"></div>
                                <div class="btn-group">
								<input type="hidden" class="avatar-src" name="avatar_src">
								<input type="hidden" class="avatar-data" name="avatar_data">
                                    <label title="Upload image file" for="inputImage" class="btn btn-primary">
                                        <input type="file" accept="image/*" name="file" id="inputImage" class="hide" name="avatar_file">
                                        Upload new image
                                    </label>
                                    <label title="Donload image" id="download" class="btn btn-primary">Download</label>
									<button type="submit" class="btn btn-primary btn-block avatar-save">Done</button>
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-white" id="zoomIn" type="button">Zoom In</button>
                                    <button class="btn btn-white" id="zoomOut" type="button">Zoom Out</button>
                                    <button class="btn btn-white" id="rotateLeft" type="button">Rotate Left</button>
                                    <button class="btn btn-white" id="rotateRight" type="button">Rotate Right</button>
                                    <button class="btn btn-warning" id="setDrag" type="button">New crop</button>
									
								 
                                </div>
                            </div>
                        </div>
             </form> 
            </div>
          </div>
        </div>				
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
			
								
								
								
								
                            </div>
                            <div class="ibox-content profile-content">
                                <h4><strong><?php echo $contact_name; ?></strong></h4>
                                <p><i class="fa fa-map-marker"></i> <?php echo nl2br($address_1); ?></p>
								<p><?php echo $country; ?></p>
                                <h5>
                                   <i class="fa fa-phone"></i> Mobile Number
                                </h5>
                                <p>
                                    <?php echo $mobile_number_1; ?>
                                </p>
								<h5>
                                    <i class="fa fa-at"></i> Email Id
                                </h5>
                                <p>
                                    <?php echo $email_id_1; ?>
                                </p>
                                
                                <div class="user-button">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="send_mail.php?to_email_id=<?php $email_id_1; ?>"><button type="button" class="btn btn-primary btn-sm btn-block"><i class="fa fa-envelope"></i> Send Mail</button></a>
                                        </div>
                                        
										
										<div class="pull-right">
											<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal2">
											Edit
											</button>
										</div>
										
										
										
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                    </div>
                <div class="col-md-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Contact Details</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                 <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
								<?php
									if($profile_id!=$_SESSION["user_id"])
									{
								?>
                                    <li><a href="profile.php?delete=true&contact_id=<?php echo $contact_id; ?>">Delete</a>
                                    </li>
								<?php
									}
								?>
                                    <li><a data-toggle="modal" data-target="#myModal2">
									Edit
									</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">

								<h5><strong><i class="fa fa-slack"></i> Nickname</strong></h5>
                                <p> <?php echo $nick_name; ?></p>
                                <h5>
                                    <strong><i class="fa fa-at"></i> Email id 2</strong>
                                </h5>
                                <p>
                                    <?php echo $email_id_2; ?>
                                </p>
								 <h5>
                                   <i class="fa fa-phone"></i> Mobile Number 2
                                </h5>
                                <p>
                                    <?php echo $mobile_number_2; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-calendar"></i> Anniversary</strong>
                                </h5>
                                <p>
                                    <?php echo $aniversary; ?>
                                </p>
								
								<h5>
                                    <strong><i class="fa fa-birthday-cake"></i> Birthday</strong>
                                </h5>
                                <p>
                                    <?php echo $birthday; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-bullhorn"></i> Eskimi</strong>
                                </h5>
                                <p>
                                    <?php echo $eskimi; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-twitter"></i> Twitter</strong>
                                </h5>
                                <p>
                                    <?php echo $twitter; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-facebook"></i> Facebook</strong>
                                </h5>
                                <p>
                                    <?php echo $facebook; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-flickr"></i> Flickr</strong>
                                </h5>
                                <p>
                                    <?php echo $flickr; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-github-alt"></i> Github</strong>
                                </h5>
                                <p>
                                    <?php echo $github; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-google-plus"></i> Google +</strong>
                                </h5>
                                <p>
                                    <?php echo $google_plus; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-linkedin"></i> Linkedin</strong>
                                </h5>
                                <p>
                                    <?php echo $linkedin; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-instagram"></i> Instagram</strong>
                                </h5>
                                <p>
                                    <?php echo $instagram; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-pinterest"></i> Pinterest</strong>
                                </h5>
                                <p>
                                    <?php echo $pinterest; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-skype"></i> Skype</strong>
                                </h5>
                                <p>
                                    <?php echo $skype; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-tumblr"></i> Tumblr</strong>
                                </h5>
                                <p>
                                    <?php echo $tumblr; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-vk"></i> VK</strong>
                                </h5>
                                <p>
                                    <?php echo $vk; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-youtube"></i> Youtube</strong>
                                </h5>
                                <p>
                                    <?php echo $youtube; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-rss"></i> Website</strong>
                                </h5>
                                <p>
                                    <?php echo $website; ?>
                                </p>
								<h5>
                                    <strong><i class="fa fa-comment-o"></i> About</strong>
                                </h5>
                                <p>
                                    <?php echo nl2br($about); ?>
                                </p>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--
				contents in file footer.php
		-->
        <?php
			require_once("footer.php");
		?>

        </div>
		
				
		<div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content animated flipInY">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title">Edit contact</h4>
							<small class="font-bold">Contact name is mandatory.</small>
						</div>
						<div class="modal-body">
						
							<p>
							
							<form role="form" method="post" action="profile.php">
							<input name="contact_id" type="hidden" class="form-control" value="<?php echo $contact_id; ?>">
							<div class="form-group">
								<label>Contact Name</label> 
								<input name="contact_name" type="text" placeholder="Contact Name" class="form-control" value="<?php echo $contact_name; ?>" required></div>
							
							<div class="form-group">
								<label>Nick Name</label> 
								<input name="nick_name" type="text" placeholder="Nick Name" class="form-control" value="<?php echo $nick_name; ?>"></div>
								
							<div class="form-group">
								<label>Address</label> 
								<textarea placeholder="Address" name="address_1" class="form-control"><?php echo $address_1; ?></textarea></div>
								
							<div class="form-group">
								<label>Country</label> 
								<input name="country" type="text" placeholder="Country" class="form-control" value="<?php echo $country; ?>"></div>
								
							<div class="form-group">
								<label>Mobile Number #1</label> 
								<input name="mobile_number_1" type="text" placeholder="Mobile Number" class="form-control" value="<?php echo $mobile_number_1; ?>"></div>
								
							<div class="form-group">
								<label>Mobile Number #2</label> 
								<input name="mobile_number_2" type="text" placeholder="Mobile Number" class="form-control" value="<?php echo $mobile_number_2; ?>"></div>
								
							<div class="form-group">
								<label>Email Address #1</label> 
								<input name="email_id_1" type="email" placeholder="Email Address" class="form-control" value="<?php echo $email_id_1; ?>"></div>
								
							<div class="form-group">
								<label>Email Address #2</label> 
								<input name="email_id_2" type="email" placeholder="Email Address" class="form-control" value="<?php echo $email_id_2; ?>"></div>
								
							<div class="form-group">
								<label>Aniversary</label> 
								<input name="aniversary" type="date" placeholder="" class="form-control" value="<?php echo $aniversary; ?>"></div>
								
							<div class="form-group">
								<label>Birthday</label> 
								<input name="birthday" type="date" placeholder="" class="form-control" value="<?php echo $birthday; ?>"></div>
								
							<div class="form-group">
								<label>Eskimi</label> 
								<input name="eskimi" type="url" placeholder="Eskimi" class="form-control" value="<?php echo $eskimi; ?>"></div>
								
							<div class="form-group">
								<label>Twitter</label> 
								<input name="twitter" type="url" placeholder="Twitter" class="form-control" value="<?php echo $twitter; ?>"></div>
								
							<div class="form-group">
								<label>Facebook</label> 
								<input name="facebook" type="url" placeholder="Facebook" class="form-control" value="<?php echo $facebook; ?>"></div>
								
							<div class="form-group">
								<label>Flickr</label> 
								<input name="flickr" type="url" placeholder="Flickr" class="form-control" value="<?php echo $flickr; ?>"></div>
								
							<div class="form-group">
								<label>Github</label> 
								<input name="github" type="url" placeholder="Github" class="form-control" value="<?php echo $github; ?>"></div>
								
							<div class="form-group">
								<label>Google+</label> 
								<input name="google_plus" type="url" placeholder="Google+" class="form-control" value="<?php echo $google_plus; ?>"></div>
								
							<div class="form-group">
								<label>LinkedIn</label> 
								<input name="linkedin" type="url" placeholder="LinkedIn" class="form-control" value="<?php echo $linkedin; ?>"></div>
								
							<div class="form-group">
								<label>Instagram</label> 
								<input name="instagram" type="url" placeholder="Instagram" class="form-control" value="<?php echo $instagram; ?>"></div>
								
							<div class="form-group">
								<label>PInterest</label> 
								<input name="pinterest" type="url" placeholder="PInterest" class="form-control" value="<?php echo $pinterest; ?>"></div>
							
							<div class="form-group">
								<label>Skype</label> 
								<input name="skype" type="text" placeholder="Skype" class="form-control" value="<?php echo $skype; ?>"></div>
								
							<div class="form-group">
								<label>Tumblr</label> 
								<input name="tumblr" type="url" placeholder="Tumblr" class="form-control" value="<?php echo $tumblr; ?>"></div>
								
							<div class="form-group">
								<label>VK</label> 
								<input name="vk" type="url" placeholder="VK" class="form-control" value="<?php echo $vk; ?>"></div>
								
							<div class="form-group">
								<label>Youtube</label> 
								<input name="youtube" type="url" placeholder="Youtube" class="form-control" value="<?php echo $youtube; ?>"></div>
								
							<div class="form-group">
								<label>Website</label> 
								<input name="website" type="url" placeholder="Website" class="form-control" value="<?php echo $website; ?>"></div>
							
				
							
							<div class="form-group">
								<label>About</label> 
								<textarea placeholder="About" name="about" class="form-control"><?php echo $about; ?></textarea></div>

						   <div>
							<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit" name="edit"><strong>Edit contact!</strong></button>
								
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
	 <!-- Image cropper -->
    <script src="js/plugins/cropper/cropper.min.js"></script>



	<!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js"></script>

    <!-- Peity -->
    <script src="js/demo/peity-demo.js"></script>
	
 <script>
        $(document).ready(function(){
			$.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
			<?php $content = file_get_contents("js/custom.js.php"); echo $content?>
            $('.contact-box').each(function() {
                animationHover(this, 'pulse');
            });
			
			$('#cropper-modal').on('shown.bs.modal', function () {
  $image.cropper({
    autoCropArea: 0.5,
    built: function () {
      // Strict mode: set crop box data first
      $image.cropper('setCropBoxData', cropBoxData);
      $image.cropper('setCanvasData', canvasData);
    }
  });
}).on('hidden.bs.modal', function () {
  cropBoxData = $image.cropper('getCropBoxData');
  canvasData = $image.cropper('getCanvasData');
  $image.cropper('destroy');
});
    var $image = $(".image-crop > img")
            $($image).cropper({
                aspectRatio: 1.618,
                preview: ".img-preview",
                done: function(data) {
                    // Output the result data for cropping image.
					var formData = new FormData();



                }
            });

            var $inputImage = $("#inputImage");
            if (window.FileReader) {
                $inputImage.change(function() {
                    var fileReader = new FileReader(),
                            files = this.files,
                            file;

                    if (!files.length) {
                        return;
                    }

                    file = files[0];

                    if (/^image\/\w+$/.test(file.type)) {
                        fileReader.readAsDataURL(file);
                        fileReader.onload = function () {
                            $inputImage.val("");
                            $image.cropper("reset", true).cropper("replace", this.result);
                        };
                    } else {
                        showMessage("Please choose an image file.");
                    }
                });
            } else {
                $inputImage.addClass("hide");
            }

            $("#download").click(function() {
                window.open($image.cropper("getDataURL"));
            });

            $("#zoomIn").click(function() {
                $image.cropper("zoom", 0.1);
            });

            $("#zoomOut").click(function() {
                $image.cropper("zoom", -0.1);
            });

            $("#rotateLeft").click(function() {
                $image.cropper("rotate", 45);
            });

            $("#rotateRight").click(function() {
                $image.cropper("rotate", -45);
            });

            $("#setDrag").click(function() {
                $image.cropper("setDragMode", "crop");
            });
			
		});
    </script>

</body>
</html>
