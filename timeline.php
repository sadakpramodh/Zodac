<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
?>
<div class="col-lg-4">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Today Schedule</h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                        
                                        <a class="close-link">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                <!--div class="ibox-content ibox-heading">
                                    <h3>You have meeting today!</h3>
                                    <small><i class="fa fa-map-marker"></i> Meeting is on 6:00am. Check your schedule to see detail.</small>
                                </div-->
								
								<?php
					
					
									$connection=databaseconnectivity_open();
									$sysdate=date("Y-m-d");
								
									$query = "select * from schedule where `user_id`=\"{$_SESSION["user_id"]}\" AND `date`=\"{$sysdate}\" order by time asc";
									
									$result = mysqli_query($connection, $query);
									if(mysqli_num_rows($result) <= 0)
									{
								?>	<div class="alert alert-info alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										  <center><?php echo "No Schedule"; ?></center>
										</div>
								<?php	
									}
                                echo '<div class="ibox-content inspinia-timeline">';

                                    while($row = mysqli_fetch_assoc($result))
										{
										$date = $row["date"];
										$time = $row["time"];
										$description= $row["description"];
										$fav_icon = $row["fav_icon"];
										$fav_color = $row["fav_color"];
										$schedule_id = $row["schedule_id"];
										$message = $row["message"];
										
										?>
							
				
                                    <div class="timeline-item">
                                        <div class="row">
                                            <div class="col-xs-3 date">
                                                <i class="fa <?php echo $fav_icon; ?>"></i>
                                                <?php echo $time; ?>
                                                <br>
                                                <small class="text-navy"><?php echo $date; ?></small>
                                            </div>
                                            <div class="col-xs-7 content">
                                                <p class="m-b-xs"><strong><?php echo $message; ?></strong></p>
                                                <p><?php echo $description; ?></p>
                                            </div>
                                        </div>
                                    </div>
									
									
									<?php 
										} 
									mysqli_free_result($result);
									databaseconnectivity_close($connection);
									?>

                                </div>
                            </div>
                        </div>