<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
?>
<div class="footer">
                    <div class="pull-right">
                        <?php echo SITE_VERSION; ?>
                    </div>
                    <div>
                        <?php echo SITE_COPY_RIGHT_MESSAGE; ?> <?php echo SITE_COPY_RIGHT_YEAR; ?>
                    </div>
</div>
