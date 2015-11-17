<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="change_account_settings.php";
//if(ACCOUNT-SETTINGS != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>
<?php
$connection=databaseconnectivity_open();
$query = "select * from users where `user_id`=\"{$_SESSION["user_id"]}\" LIMIT 1";
$result = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($result))
{
$username = $row["username"];
$email_id = $row["email_id"];
$address = $row["address"];
$phone = $row["phone"];
$country = $row["country"];
$gender = $row["gender"];
$step2override = $row["step2override"];
$city= $row["city"];

}
mysqli_free_result($result);
if(isset($_POST["changepassword"]))
{
	$newpassword=clean_user_input($connection,$_POST["newpassword"]);
	$newconfirm=clean_user_input($connection,$_POST["newconfirm"]);
	$oldpassword=clean_user_input($connection,$_POST["oldpassword"]);
	
	$errors = array();
	$result = check_length($newpassword,15,5);
	if($result!=0)
	{
		$errors["password"]="password length should between 6 to 15 characters";
	}
	if(!$newpassword===$newconfirm)
	{
		$error["passwordnotmatch"]="passwords not matched";
	}
	$password_in_db="";
	$query = "SELECT `password` FROM `users` WHERE `user_id` = \"";
	$query .= "{$_SESSION["user_id"]}\" LIMIT 1";
	$result = mysqli_query($connection, $query);
	while($row = mysqli_fetch_assoc($result))
	{
		$password_in_db=$row["password"];
		
	}
	mysqli_free_result($result);
	if($password_in_db===$oldpassword)
		{
			
		}
	else
	{
		$errors["oldpassword_error"]="Invalid current password";
	}
	if(empty($errors))
			{
				$query="update users set `password`=\"{$newpassword}\" where user_id=\"{$_SESSION["user_id"]}\"";	
				if (mysqli_query($connection, $query)) 
				{
				$_SESSION["message"]="Account information updated. Please login";
				$_SESSION["type"]=1;
				databaseconnectivity_close($connection);
				redirect_to("logout.php");
				}
	databaseconnectivity_close($connection);		
			}
	
	
	
	
}

if(isset($_POST["savechanges"]))
{
	
	$connection=databaseconnectivity_open();
	$username=clean_user_input($connection,$_POST["username"]);
	$oldpassword=clean_user_input($connection,$_POST["oldpassword"]);
	
	$email=clean_user_input($connection,$_POST["email"]);
	$address=clean_user_input($connection,$_POST["address"]);
	$phone=clean_user_input($connection,$_POST["phone"]);
	$gender=clean_user_input($connection,$_POST["gender"]);
	$country=clean_user_input($connection,$_POST["country"]);
	$city=clean_user_input($connection,$_POST["city"]);
	
	
	
	$errors = array();
	$result = check_length($email_id,30,5);
	if($result!=0)
	{
		$errors["email_id"]="Email address length should between 5 to 30 characters";
	}
	//$result!=0?$errors["username"] = "Username too short or too long !":"ok";
		
	$result = check_length($username,16,5);
	if($result!=0)
	{
		$errors["username"]="username length should between 5 to 15 characters";
	}
	
	
	$result = check_length($address,50,6);
	if($result!=0)
	{
		$errors["address"]="address length should between 5 to 15 characters";
	}
	$result = check_length($city,50,2);
	if($result!=0)
	{
		$errors["city"]="city length should between 5 to 15 characters";
	}
	if($_SESSION["email_id"]!=$email)
	{
		$query = "SELECT `email_id` FROM `users` WHERE `email_id` = \"";
		$query .= $email;
		$query .= "\" LIMIT 1";
		
		$result = mysqli_query($connection, $query);
		if(mysqli_num_rows($result) > 0)
		{
			$errors["email_id1"]="Email address is already taken.Please enter another email address";
		}	
	}
	
	$password_in_db="";
	$query = "SELECT `password` FROM `users` WHERE `user_id` = \"";
	$query .= "{$_SESSION["user_id"]}\" LIMIT 1";
	$result = mysqli_query($connection, $query);
	while($row = mysqli_fetch_assoc($result))
	{
		$password_in_db=$row["password"];
		
	}
	mysqli_free_result($result);
	if($password_in_db===$oldpassword)
		{
			
		}
	else
	{
		$errors["currentpassword"]="Invalid current password";
	}
	
	
	if(empty($errors))
			{
				if(!isset($_POST["step2override"]))
				{
					$query = "update users set `step2override`=\"0\" where `user_id`=\"{$_SESSION["user_id"]}\"";
					if (mysqli_query($connection, $query)) 
					{
						//No need to return user about override of step2verification
					}
				}
				$query="update users set`username`=\"{$username}\", email_id=\"{$email}\", address=\"{$address}\", phone=\"{$phone}\", country=\"{$country}\", gender=\"{$gender}\", city=\"{$city}\" where user_id=\"{$_SESSION["user_id"]}\"";	
				if (mysqli_query($connection, $query)) 
				{
				$_SESSION["message"]="Account information updated. Please login";
				$_SESSION["type"]=1;
				databaseconnectivity_close($connection);
				redirect_to("logout.php");
				}
	databaseconnectivity_close($connection);		}
}

?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Change account settings</title>
<?php 
	require_once("comns/head_section.php")
?>

	<link href="css/plugins/switchery/switchery.css" rel="stylesheet">
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">


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
                <div class="col-lg-10">
                    <h2>Change account settings</h2>
                    
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
        <div class="wrapper wrapper-content animated fadeInRight">
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Account Settings</h5>
							<div class="ibox-tools">
							
                               <a data-toggle="modal" class="btn btn-danger" href="#modal-form-changepassword">Change password</a>
							   
									<div id="modal-form-changepassword" class="modal fade" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-body">
													<div class="row">
														<div class="col-sm-6 b-r"><h3 class="m-t-none m-b">Change password</h3>

															<form id="form" action="change_account_settings.php" method="POST">
																<div class="form-group"><label>Current password</label> <input type="password" name="oldpassword" id="oldpassword"placeholder="Current password" class="form-control required"></div>
																<div class="form-group"><label>New password</label> <input type="password" name="newpassword" id="newpassword" placeholder="New password" class="form-control required"></div>
																<div class="form-group"><label>Confirm password</label> <input type="password" name="newconfirm" id="newconfirm" placeholder="Re-enter password" class="form-control required"></div>
																<div>
																	<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit" name="changepassword"><strong>Change password</strong></button>
																	
																</div>
															</form>
														</div>
														<div class="col-sm-6"><h4>For override step 2 verification ?</h4>
														<p>Record pattern</p>
														<p class="text-center">
															<a href="record_pattern.php"><i class="fa fa-sign-in big-icon"></i></a>
														</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
						
							   <a class="btn btn-success" href="record_pattern.php">Record pattern</a>
                            </div>
							
                            
                        </div>
                        <div class="ibox-content">
                            
                            <form id="form" action="change_account_settings.php" method="POST" class="wizard-big">
                                <h1>Accounts</h1>
                                <fieldset>
                                
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Username *</label>
                                                <input id="userName" name="username" type="text" class="form-control required" value="<?php echo $username; ?>">
                                            </div>
                                            
											
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Current Password *</label>
                                                <input id="oldpassword" name="oldpassword" type="password" class="form-control required">
                                            </div>
                                            
                                        </div>
                                    </div>

                                </fieldset>
                               <h1>Profile</h1>
                                <fieldset>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Email *</label>
                                                <input id="email" name="email" type="text" class="form-control required email" value="<?php echo $email_id; ?>">
                                            </div>
                                            <div class="form-group">
												<label>phone *</label>
												
													<input type="text" class="form-control" data-mask="(999) 999-9999" placeholder="" name="phone" value="<?php echo $phone; ?>">
													<span class="help-block">(999) 999-9999</span>
												
											</div>
											
											<div class="form-group">
												<label>Country *</label>
														
															<select tabindex="2" name="country" class="form-control m-b" >
																
																<option value="<?php echo $country; ?>" selected><?php echo $country; ?></option>
																<option value="United States">United States</option>
																<option value="United Kingdom">United Kingdom</option>
																<option value="Afghanistan">Afghanistan</option>
																<option value="Aland Islands">Aland Islands</option>
																<option value="Albania">Albania</option>
																<option value="Algeria">Algeria</option>
																<option value="American Samoa">American Samoa</option>
																<option value="Andorra">Andorra</option>
																<option value="Angola">Angola</option>
																<option value="Anguilla">Anguilla</option>
																<option value="Antarctica">Antarctica</option>
																<option value="Antigua and Barbuda">Antigua and Barbuda</option>
																<option value="Argentina">Argentina</option>
																<option value="Armenia">Armenia</option>
																<option value="Aruba">Aruba</option>
																<option value="Australia">Australia</option>
																<option value="Austria">Austria</option>
																<option value="Azerbaijan">Azerbaijan</option>
																<option value="Bahamas">Bahamas</option>
																<option value="Bahrain">Bahrain</option>
																<option value="Bangladesh">Bangladesh</option>
																<option value="Barbados">Barbados</option>
																<option value="Belarus">Belarus</option>
																<option value="Belgium">Belgium</option>
																<option value="Belize">Belize</option>
																<option value="Benin">Benin</option>
																<option value="Bermuda">Bermuda</option>
																<option value="Bhutan">Bhutan</option>
																<option value="Bolivia, Plurinational State of">Bolivia, Plurinational State of</option>
																<option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
																<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
																<option value="Botswana">Botswana</option>
																<option value="Bouvet Island">Bouvet Island</option>
																<option value="Brazil">Brazil</option>
																<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
																<option value="Brunei Darussalam">Brunei Darussalam</option>
																<option value="Bulgaria">Bulgaria</option>
																<option value="Burkina Faso">Burkina Faso</option>
																<option value="Burundi">Burundi</option>
																<option value="Cambodia">Cambodia</option>
																<option value="Cameroon">Cameroon</option>
																<option value="Canada">Canada</option>
																<option value="Cape Verde">Cape Verde</option>
																<option value="Cayman Islands">Cayman Islands</option>
																<option value="Central African Republic">Central African Republic</option>
																<option value="Chad">Chad</option>
																<option value="Chile">Chile</option>
																<option value="China">China</option>
																<option value="Christmas Island">Christmas Island</option>
																<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
																<option value="Colombia">Colombia</option>
																<option value="Comoros">Comoros</option>
																<option value="Congo">Congo</option>
																<option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
																<option value="Cook Islands">Cook Islands</option>
																<option value="Costa Rica">Costa Rica</option>
																<option value="Cote D'ivoire">Cote D'ivoire</option>
																<option value="Croatia">Croatia</option>
																<option value="Cuba">Cuba</option>
																<option value="Curacao">Curacao</option>
																<option value="Cyprus">Cyprus</option>
																<option value="Czech Republic">Czech Republic</option>
																<option value="Denmark">Denmark</option>
																<option value="Djibouti">Djibouti</option>
																<option value="Dominica">Dominica</option>
																<option value="Dominican Republic">Dominican Republic</option>
																<option value="Ecuador">Ecuador</option>
																<option value="Egypt">Egypt</option>
																<option value="El Salvador">El Salvador</option>
																<option value="Equatorial Guinea">Equatorial Guinea</option>
																<option value="Eritrea">Eritrea</option>
																<option value="Estonia">Estonia</option>
																<option value="Ethiopia">Ethiopia</option>
																<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
																<option value="Faroe Islands">Faroe Islands</option>
																<option value="Fiji">Fiji</option>
																<option value="Finland">Finland</option>
																<option value="France">France</option>
																<option value="French Guiana">French Guiana</option>
																<option value="French Polynesia">French Polynesia</option>
																<option value="French Southern Territories">French Southern Territories</option>
																<option value="Gabon">Gabon</option>
																<option value="Gambia">Gambia</option>
																<option value="Georgia">Georgia</option>
																<option value="Germany">Germany</option>
																<option value="Ghana">Ghana</option>
																<option value="Gibraltar">Gibraltar</option>
																<option value="Greece">Greece</option>
																<option value="Greenland">Greenland</option>
																<option value="Grenada">Grenada</option>
																<option value="Guadeloupe">Guadeloupe</option>
																<option value="Guam">Guam</option>
																<option value="Guatemala">Guatemala</option>
																<option value="Guernsey">Guernsey</option>
																<option value="Guinea">Guinea</option>
																<option value="Guinea-bissau">Guinea-bissau</option>
																<option value="Guyana">Guyana</option>
																<option value="Haiti">Haiti</option>
																<option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
																<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
																<option value="Honduras">Honduras</option>
																<option value="Hong Kong">Hong Kong</option>
																<option value="Hungary">Hungary</option>
																<option value="Iceland">Iceland</option>
																<option value="India">India</option>
																<option value="Indonesia">Indonesia</option>
																<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
																<option value="Iraq">Iraq</option>
																<option value="Ireland">Ireland</option>
																<option value="Isle of Man">Isle of Man</option>
																<option value="Israel">Israel</option>
																<option value="Italy">Italy</option>
																<option value="Jamaica">Jamaica</option>
																<option value="Japan">Japan</option>
																<option value="Jersey">Jersey</option>
																<option value="Jordan">Jordan</option>
																<option value="Kazakhstan">Kazakhstan</option>
																<option value="Kenya">Kenya</option>
																<option value="Kiribati">Kiribati</option>
																<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
																<option value="Korea, Republic of">Korea, Republic of</option>
																<option value="Kuwait">Kuwait</option>
																<option value="Kyrgyzstan">Kyrgyzstan</option>
																<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
																<option value="Latvia">Latvia</option>
																<option value="Lebanon">Lebanon</option>
																<option value="Lesotho">Lesotho</option>
																<option value="Liberia">Liberia</option>
																<option value="Libya">Libya</option>
																<option value="Liechtenstein">Liechtenstein</option>
																<option value="Lithuania">Lithuania</option>
																<option value="Luxembourg">Luxembourg</option>
																<option value="Macao">Macao</option>
																<option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
																<option value="Madagascar">Madagascar</option>
																<option value="Malawi">Malawi</option>
																<option value="Malaysia">Malaysia</option>
																<option value="Maldives">Maldives</option>
																<option value="Mali">Mali</option>
																<option value="Malta">Malta</option>
																<option value="Marshall Islands">Marshall Islands</option>
																<option value="Martinique">Martinique</option>
																<option value="Mauritania">Mauritania</option>
																<option value="Mauritius">Mauritius</option>
																<option value="Mayotte">Mayotte</option>
																<option value="Mexico">Mexico</option>
																<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
																<option value="Moldova, Republic of">Moldova, Republic of</option>
																<option value="Monaco">Monaco</option>
																<option value="Mongolia">Mongolia</option>
																<option value="Montenegro">Montenegro</option>
																<option value="Montserrat">Montserrat</option>
																<option value="Morocco">Morocco</option>
																<option value="Mozambique">Mozambique</option>
																<option value="Myanmar">Myanmar</option>
																<option value="Namibia">Namibia</option>
																<option value="Nauru">Nauru</option>
																<option value="Nepal">Nepal</option>
																<option value="Netherlands">Netherlands</option>
																<option value="New Caledonia">New Caledonia</option>
																<option value="New Zealand">New Zealand</option>
																<option value="Nicaragua">Nicaragua</option>
																<option value="Niger">Niger</option>
																<option value="Nigeria">Nigeria</option>
																<option value="Niue">Niue</option>
																<option value="Norfolk Island">Norfolk Island</option>
																<option value="Northern Mariana Islands">Northern Mariana Islands</option>
																<option value="Norway">Norway</option>
																<option value="Oman">Oman</option>
																<option value="Pakistan">Pakistan</option>
																<option value="Palau">Palau</option>
																<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
																<option value="Panama">Panama</option>
																<option value="Papua New Guinea">Papua New Guinea</option>
																<option value="Paraguay">Paraguay</option>
																<option value="Peru">Peru</option>
																<option value="Philippines">Philippines</option>
																<option value="Pitcairn">Pitcairn</option>
																<option value="Poland">Poland</option>
																<option value="Portugal">Portugal</option>
																<option value="Puerto Rico">Puerto Rico</option>
																<option value="Qatar">Qatar</option>
																<option value="Reunion">Reunion</option>
																<option value="Romania">Romania</option>
																<option value="Russian Federation">Russian Federation</option>
																<option value="Rwanda">Rwanda</option>
																<option value="Saint Barthelemy">Saint Barthelemy</option>
																<option value="Saint Helena, Ascension and Tristan da Cunha">Saint Helena, Ascension and Tristan da Cunha</option>
																<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
																<option value="Saint Lucia">Saint Lucia</option>
																<option value="Saint Martin (French part)">Saint Martin (French part)</option>
																<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
																<option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
																<option value="Samoa">Samoa</option>
																<option value="San Marino">San Marino</option>
																<option value="Sao Tome and Principe">Sao Tome and Principe</option>
																<option value="Saudi Arabia">Saudi Arabia</option>
																<option value="Senegal">Senegal</option>
																<option value="Serbia">Serbia</option>
																<option value="Seychelles">Seychelles</option>
																<option value="Sierra Leone">Sierra Leone</option>
																<option value="Singapore">Singapore</option>
																<option value="Sint Maarten (Dutch part)">Sint Maarten (Dutch part)</option>
																<option value="Slovakia">Slovakia</option>
																<option value="Slovenia">Slovenia</option>
																<option value="Solomon Islands">Solomon Islands</option>
																<option value="Somalia">Somalia</option>
																<option value="South Africa">South Africa</option>
																<option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
																<option value="South Sudan">South Sudan</option>
																<option value="Spain">Spain</option>
																<option value="Sri Lanka">Sri Lanka</option>
																<option value="Sudan">Sudan</option>
																<option value="Suriname">Suriname</option>
																<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
																<option value="Swaziland">Swaziland</option>
																<option value="Sweden">Sweden</option>
																<option value="Switzerland">Switzerland</option>
																<option value="Syrian Arab Republic">Syrian Arab Republic</option>
																<option value="Taiwan, Province of China">Taiwan, Province of China</option>
																<option value="Tajikistan">Tajikistan</option>
																<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
																<option value="Thailand">Thailand</option>
																<option value="Timor-leste">Timor-leste</option>
																<option value="Togo">Togo</option>
																<option value="Tokelau">Tokelau</option>
																<option value="Tonga">Tonga</option>
																<option value="Trinidad and Tobago">Trinidad and Tobago</option>
																<option value="Tunisia">Tunisia</option>
																<option value="Turkey">Turkey</option>
																<option value="Turkmenistan">Turkmenistan</option>
																<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
																<option value="Tuvalu">Tuvalu</option>
																<option value="Uganda">Uganda</option>
																<option value="Ukraine">Ukraine</option>
																<option value="United Arab Emirates">United Arab Emirates</option>
																<option value="United Kingdom">United Kingdom</option>
																<option value="United States">United States</option>
																<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
																<option value="Uruguay">Uruguay</option>
																<option value="Uzbekistan">Uzbekistan</option>
																<option value="Vanuatu">Vanuatu</option>
																<option value="Venezuela, Bolivarian Republic of">Venezuela, Bolivarian Republic of</option>
																<option value="Viet Nam">Viet Nam</option>
																<option value="Virgin Islands, British">Virgin Islands, British</option>
																<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
																<option value="Wallis and Futuna">Wallis and Futuna</option>
																<option value="Western Sahara">Western Sahara</option>
																<option value="Yemen">Yemen</option>
																<option value="Zambia">Zambia</option>
																<option value="Zimbabwe">Zimbabwe</option>
														</select>
														
											</div>
											
                                        </div>
                                        <div class="col-lg-6">
                                            
                                            <div class="form-group">
                                                <label>Address *</label>
                                               <textarea class="form-control" name="address"><?php echo $address; ?></textarea>
                                            </div>
											
											<div class="form-group">
                                                <label>City *</label>
                                               <input type="text" class="form-control" name="city" value="<?php echo $city; ?>">
                                            </div>
											
											<div class="form-group">
												 <label>Gender *</label>
												<div class="radio i-checks"><label> <input type="radio" value="male" name="gender" <?php if($gender=="male") {echo "checked";} ?>> <i></i> Male </label></div>
												<div class="radio i-checks"><label> <input type="radio" value="female" name="gender" <?php if($gender=="female") {echo "checked";} ?>> <i></i> Female </label></div>
											</div>
											
											<div class="form-group">
												 <label>Step 2 verification override *</label>
												<input type="checkbox" class="js-switch" name="step2override" <?php if($step2override!=0) { echo "checked"; }?> />
											</div>
											
											
                                        </div>
                                    </div>
                                </fieldset>

                                
                                
							<button class="btn btn-primary" type="submit" name="savechanges">Save changes</button>
							
                            </form>
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
  
	 <!-- Switchery -->
   <script src="js/plugins/switchery/switchery.js"></script>


	 <!-- Jquery Validate -->
    <script src="js/plugins/validate/jquery.validate.min.js"></script>

    <script>
         $(document).ready(function(){
			 	$.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
			<?php $content = file_get_contents("js/custom.js.php"); echo $content?>
			  var elem = document.querySelector('.js-switch');
			  var switchery = new Switchery(elem, { color: '#1AB394' });

             $("#form").validate({
                 rules: {
                     username: {
                         required: true,
                         minlength: 5,
						 maxlength: 15
						 
                     },
                     newpassword: {
                         required: true,
                         minlength: 6,
						 maxlength:15
                     },
                     newconfirm: {
						 equalTo: "#newpassword"
                     },
					 email: {
                         required: true,
                         minlength: 3,
						 maxlength:35
                     },
					 phone: {
                         required: true,
                         minlength: 10,
						 maxlength:15
                     },
					 country: {
                         required: true,
                         minlength: 1
                     },
					 address: {
                         required: true,
                         minlength: 6,
						 maxlength:50
                     },
					 city: {
                         required: true,
                         minlength: 2,
						 maxlength:50
                     }
					 
                 }
             });
        });
    </script>
    
       
	<!-- Input Mask-->
    <script src="js/plugins/jasny/jasny-bootstrap.min.js"></script>
	
    
	 <!-- iCheck -->
    <script src="js/plugins/iCheck/icheck.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
            });
        </script>

</body>
</html>
