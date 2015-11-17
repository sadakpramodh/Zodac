<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
?>
 <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Message from Admin</h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                        
                                        <a class="close-link">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <div>

                                        <div class="pull-right text-right">

                                            <br/>-sadak pramodh
                                            
                                        </div>
                                        <h4><?php echo MESSAGE_FROM_ADMIN; ?>
                                            <br/>
                                           
                                        </h4>
                                        </div>
                                    </div>
                                </div>