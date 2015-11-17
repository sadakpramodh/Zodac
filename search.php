<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
?>
<div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" action="search_results.php" method="get">
                <div class="form-group">
                    <input type="text" placeholder="Search for something..." class="form-control" name="search" id="top-search">
                </div>
            </form>
        </div>
            <ul class="nav navbar-top-links navbar-right pull-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Welcome to <?php echo SITE_NAME; ?>.</span>
                </li>
                
				
				<li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
					
						<?php
						$connection=databaseconnectivity_open();
						$query = "select * from contacts where profile_id=\"{$_SESSION["user_id"]}\" AND user_id=\"{$_SESSION["user_id"]}\" LIMIT 1";
						$result = mysqli_query($connection, $query);
						while($row = mysqli_fetch_assoc($result))
						{
							$contact_id_search = $row["contact_id"];
							$contact_url_search = $row["contact_url"];
						}
						mysqli_free_result($result);
						databaseconnectivity_close($connection);
						if (file_exists($contact_url_search)) {
								echo '<img alt="image" class="img-circle" src="'.$contact_url_search.' " width="35" height="35">';
								}
								else
								{
                                echo'<img alt="image" class="img-circle" src="img/a2.jpg" width="35" height="35">';
								}
						?>
                       
                    </a>
					
                    <ul class="dropdown-menu">
                        <li>
							<a href="logout.php">
								<i class="fa fa-sign-out"></i> Log out
							</a>
						</li>
						
						<li>
							<a href="lockscreen.php?lock=true">
								<i class="fa fa-lock"></i> Lock
							</a>
						</li>
                        <li class="divider"></li>
                        <li>
							<a href="change_account_settings.php">
								<i class="fa fa-wrench"></i> Change account settings
							</a>
						</li>
						<li>
							<a href="contacts.php">
								<i class="fa fa-users"></i> Contacts
							</a>
						</li>
						<li>
							<a href="profile.php">
								<i class="fa fa-user"></i> Profile
							</a>
						</li>

                       
                        
                       
                       
                    </ul>
                </li>
                <?php ?>
				<li>
                    <a class="right-sidebar-toggle">
                        <i class="fa fa-tasks"></i>
                    </a>
                </li>
            </ul>

        </nav>
        </div>