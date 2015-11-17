<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
?>
<li class="nav-header">

						
                        <div class="dropdown profile-element"> <span>
						<?php
						$connection=databaseconnectivity_open();
						$query = "select * from `contacts` where profile_id=\"{$_SESSION["user_id"]}\" AND user_id=\"{$_SESSION["user_id"]}\" LIMIT 1";
						$result = mysqli_query($connection, $query);
						while($row1 = mysqli_fetch_assoc($result))
						{
							$contact_id_userhead = $row1["contact_id"];
							$contact_url_userhead = $row1["contact_url"];
						}
						mysqli_free_result($result);
						databaseconnectivity_close($connection);
						if (file_exists($contact_url_userhead)) {
								echo ' <img alt="image" class="img-circle" src="'.$contact_url_userhead.' ">';
								}
								else
								{
                                echo'<img alt="image" class="img-circle m-t-xs img-responsive" src="img/a2.jpg">';
								}
						?>
                         
                             </span>
							
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><span class="text-muted text-xs block"><?php echo $_SESSION["username"]; ?></span></strong>
                             </span></span>
							 
							 <span class="text-muted text-xs block"><?php echo $_SESSION["email_id"]; ?> <b class="caret"></b></span>
							 
							 
							 </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="profile.php"><i class="fa fa-user"></i> Profile</a></li>
                                <li><a href="contacts.php"><i class="fa fa-users"></i> Contacts</a></li>
                                
								<li class="divider"></li>
								<li><a href="change_account_settings.php"><i class="fa fa-wrench"></i> Change account settings</a></li>
                                <li class="divider"></li>
								<li><a href="lockscreen.php?lock=true"><i class="fa fa-lock"></i> Lock</a></li>
                                <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            <?php echo SITE_LOGO_NAME; ?>
                        </div>
                    </li>