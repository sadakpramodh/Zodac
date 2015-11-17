<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
?>
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <!--
						file contents in userheader.php
					-->
					<?php
						require_once("userheader.php");
					?>
					
					<?php if(INDEX == 1) { ?><li <?php if($page_name=="index.php") echo'class="active"'; ?>>
                        <a href="index.php"><i class="fa fa-home"></i> <span class="nav-label">Home</span></a>
                    </li>
					<?php } ?>
                    <?php if(PINBOARD == 1) { ?><li <?php if($page_name=="pinboard.php") echo'class="active"'; ?>>
                        <a href="pinboard.php"><i class="fa fa-pencil"></i> <span class="nav-label">Pinboard</span> <span class="label label-primary pull-right">NEW</span></a>
                    </li>
					<?php } ?>
                   
                    <?php if(CONTACTS == 1) { ?><li <?php if($page_name=="contacts.php") echo'class="active"'; ?>>
                        <a href="contacts.php"><i class="fa fa-users"></i> <span class="nav-label">Contacts</span> </a>
                    </li>
					<?php } ?>
					<?php if(CONTACTS_V2 == 1) { ?><li <?php if($page_name=="contacts_v2.php") echo'class="active"'; ?>>
                        <a href="contacts_v2.php"><i class="fa fa-users"></i> <span class="nav-label">Contact v2</span> </a>
                    </li>
					<?php } ?>
					<?php if(CALENDAR == 1) { ?><li <?php if($page_name=="calendar.php") echo'class="active"'; ?>>
                        <a href="calendar.php"><i class="fa fa-calendar"></i> <span class="nav-label">Calendar</span> </a>
                    </li>
					<?php } ?>
                    <?php if(TODAY_SCHEDULE == 1) { ?><li <?php if($page_name=="todayschedule.php") echo'class="active"'; ?>>
                        <a href="todayschedule.php"><i class="fa fa-clock-o"></i> <span class="nav-label">Today Schedule</span></a>
                    </li>
					<?php } ?>
					<?php if(WEATHER == 1) { ?><li <?php if($page_name=="weather.php") echo'class="active"'; ?>>
                        <a href="weather.php"><i class="fa fa-cloud"></i> <span class="nav-label">Weather</span> <span class="label label-success pull-right" id="weather-on-nav"> </span></a>
                    </li>
					<?php } ?>
					<?php if(TWITTER == 1) { ?><li <?php if($page_name=="twitter.php") echo'class="active"'; ?>>
                        <a href="twitter.php"><i class="fa fa-twitter"></i> <span class="nav-label">Twitter</span> </a>
                    </li>
					<?php } ?>
					<?php if(FACEBOOK == 1) { ?><li <?php if($page_name=="facebook.php") echo'class="active"'; ?>>
                        <a href="facebook.php"><i class="fa fa-facebook"></i> <span class="nav-label">Facebook</span> </a>
                    </li>
					<?php } ?>
					
					<?php
					$connection=databaseconnectivity_open();
					$query = "select * from roles where user_id=\"{$_SESSION["user_id"]}\" LIMIT 1";
					$result = mysqli_query($connection, $query);
					while($row = mysqli_fetch_assoc($result))
					{
						if(ROLE_NO_ADMIN !=$row["role_id"])
						{
							redirect_to("index.php");
						}
						if(ROLE_NAME_ADMIN !=$row["role_name"])
						{
							redirect_to("index.php");
						}
						?>
					<li class="divider"><li>
					
					<li <?php if($page_name=="code_editor.php" || $page_name=="code_editor_v2.php") echo'class="active"'; ?>>
                    <a href="#"><i class="fa fa-shield"></i> <span class="nav-label">Admin</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
						<?php if(CODE_EDITOR == 1) { ?><li <?php if($page_name=="code_editor.php") echo'class="active"'; ?>>
							<a href="code_editor.php"><i class="fa fa-edit"></i> <span class="nav-label">Code Editor</span> </a>
						</li>
						<?php } ?>
						<?php if(CODE_EDITOR_V2 == 1) { ?><li <?php if($page_name=="code_editor_v2.php") echo'class="active"'; ?>>
							<a href="code_editor_v2.php"><i class="fa fa-code"></i> <span class="nav-label">Code Editor V2</span> </a>
						</li>
						<?php } ?>
					</ul>
					</li>
					<?php
					}
					
					mysqli_free_result($result);
					databaseconnectivity_close($connection);

					?>
					
                </ul>

            </div>
        </nav>