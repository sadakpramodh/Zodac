<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="mailbox.php";
if(MAIL-BOX != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Mailbox</title>
<?php 
	require_once("comns/head_section.php")
?>
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
    
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
        <div class="wrapper wrapper-content">
		<?php
		if ($email_stream=connect_user_email()) 
				{
					$check = imap_mailboxmsginfo($email_stream);
		?>
        <div class="row">
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-content mailbox-content">
                        <div class="file-manager">
                            <a class="btn btn-block btn-primary compose-mail" href="mail_compose.php">Compose Mail</a>
                            <div class="space-25"></div>
                            <h5>Folders</h5>
                            <ul class="folder-list m-b-md" style="padding: 0">
                                <li><a href="mailbox.php"> <i class="fa fa-inbox "></i> Inbox <span class="label label-warning pull-right"><?php echo $check->Unread; ?></span> </a></li>
                                <li><a href="mailbox.php"> <i class="fa fa-envelope-o"></i> Send Mail</a></li>
                                
                                <li><a href="mailbox.php"> <i class="fa fa-file-text-o"></i> Drafts <span class="label label-danger pull-right"></span></a></li>
                                
                            </ul>
                           
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 animated fadeInRight">
            <div class="mail-box-header">

                <form method="get" action="http://webapplayers.com/inspinia_admin-v2.1/index.php" class="pull-right mail-search">
                    <div class="input-group">
                        <input type="text" class="form-control input-sm" name="search" placeholder="Search email">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-primary">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
                <h2>
				<?php
				
				echo "Inbox (". $check->Nmsgs .")"; 
                echo "<br><small>Size: ". $rest = substr((($check->Size/1024)/1024),0,4) ." Mb</small>";
				?>
                </h2>
                <div class="mail-tools tooltip-demo m-t-md">
                    <div class="btn-group pull-right">
                        <button class="btn btn-white btn-sm"><i class="fa fa-arrow-left"></i></button>
                        <button class="btn btn-white btn-sm"><i class="fa fa-arrow-right"></i></button>

                    </div>
                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="left" title="Refresh inbox"><i class="fa fa-refresh"></i> Refresh</button>
                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Mark as read"><i class="fa fa-eye"></i> </button>
                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Mark as important"><i class="fa fa-exclamation"></i> </button>
                    
                </div>
            </div>
                <div class="mail-box">

                <table class="table table-hover table-mail">
                <tbody>
                
				
				<?php
				
					
					$MC = imap_check($email_stream);

					// Fetch an overview for all messages in INBOX
					$lower_limit=1;
					$higher_limit=$MC->Nmsgs;
					echo $MC->Nmsgs;
					$result = imap_fetch_overview($email_stream,"{$lower_limit}:{$higher_limit}",0);
					//sort($result,$result->msgno);
					foreach ($result as $overview) {
						
						echo'<tr class="';
							if(!$overview->seen)
							{
								 echo'unread';
							}
							else
							{
								echo'read';
							}
						echo'">';
							echo'<td class="check-mail">';
								echo'<input type="checkbox" class="i-checks">';
							echo'</td>';
							echo'<td class="mail-ontact"><a href="mail_detail.php?message_id='. $overview->msgno .'">'. $overview->from .'</a></td>';
							$rest = substr($overview->subject,0,21); 
							echo'<td class="mail-subject"><a href="mail_detail.php?message_id='. $overview->msgno .'">'. $rest .'</a></td>';
							echo'<td class=""></td>';
							$x=$overview->date;
							$temp=array();
							$temp=explode(" ",$x);
							
							echo'<td class="text-right mail-date" data-toggle="tooltip" data-placement="left" title="'.$overview->date.'">'.$temp[1].'-'.$temp[2].'-'.$temp[3].'</td>';
						echo'</tr>';
						
					}
					
				imap_close($email_stream);
				}
				else
				{
					echo "FAIL! Invalid Credentials ";
				}

				
				?>
				
				
                
                
                
                </tbody>
                </table>


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
    <!-- iCheck -->
    <script src="js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function(){
			$.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
			<?php $content = file_get_contents("js/custom.js.php"); echo $content?>
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
</body>
</html>
