<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="dairy.php";
if(DAIRY != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>
<?php
if(isset($_POST["title"]))
{
$pdo = new PDO('mysql:host='. DB_HOST.';dbname='. DB_NAME, DB_USER_NAME, DB_USER_PASSWORD, array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
));
$stmt = $pdo->prepare("INSERT INTO dairy (title,content) VALUES (:title,:content)");
$stmt->bindParam(':title', $title);
$stmt->bindParam(':content', $content);

// insert one row
$title = $_POST['title'];
$content = $_POST['content'];
if($stmt->execute())
$rslt = array('id' => "OK");
$dairy_id = 5;
 $query = $pdo->prepare("SELECT * FROM dairy WHERE dairy_id = :id ");
                $query->bindValue(':id',$dairy_id);
                $query->execute();
                if($query->rowCount() > 0 ){ 
                    $r = $query->fetch();
                ?>
                     <h3 class="title"><?php echo $r['title'];?></h3>
                     <hr/>
                     <div class="content"><?php echo $r['content'];?></div>
                     <hr/>
                     <a href="index.php"><h2><< Back</h2></a>
				<?php }
	/*

	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($rslt);
	*/
	die();
	header("Location:blog_editor.php");
}
   ?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | Dairy</title>

	<?php 
		require_once("comns/head_section.php")
	?>
    <link href="css/plugins/summernote/summernote.css" rel="stylesheet">
    <link href="css/plugins/summernote/summernote-bs3.css" rel="stylesheet">
 

</head>

<body>

    <div id="wrapper">

	   <?php
		require_once("leftnavigationbar.php");
		?>
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
		<?php
				require_once("search.php");
			?> 
       </div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Dairy</h2>
                    
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content">

            
           
            <div class="row">
                <div class="col-lg-12">
					<div id="dairy_actions">
						<a id="add_dairy_option">
						<div class="col-lg-2">
							<div class="widget navy-bg p-lg text-center">
								<div class="m-b-md">
									<i class="fa fa-plus fa-4x"></i>
									<h1 class="m-xs">Add</h1>
									<h3 class="font-bold no-margins">
										Dairy
									</h3>
									
								</div>
							</div>
						
						</div>
						</a>
						<a id="view_dairy">
						<div class="col-lg-2">
								<div class="widget blue-bg p-lg text-center">
									<div class="m-b-md">
										<i class="fa fa-eye fa-4x"></i>
										<h1 class="m-xs">View</h1>
										<h3 class="font-bold no-margins">
											Dairy
										</h3>
										
									</div>
								</div>
								
						</div>
						</a>
					</div>
					<div id="add_dairy">
						<div class="ibox float-e-margins">
							<form id="postForm" action="blog_editor.php" method="POST" enctype="multipart/form-data" onsubmit="return postForm()">
							<div class="ibox-title">
								
								<button id="cancel" class="btn btn-danger" type="reset">Cancel</button>
								<button id="save" class="btn btn-primary"  type="submit">Save</button>
								<div class="ibox-tools">
									<a class="collapse-link">
										<i class="fa fa-chevron-up"></i>
									</a>
									
									<a class="close-link">
										<i class="fa fa-times"></i>
									</a>
								</div>
							</div>
							<div class="ibox-content no-padding">
								<div class="form-group">
								<label>Title</label>
								<input type="text" class="form-control" name="title" placeholder="Enter title">
								</div>
								<div class="form-group">
								<label>Contents</label>
								<textarea class="wrapper p-md" id="summernote" name="content" placeholder="Enter Contents"></textarea>
								</div>
							</div>
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
    <!-- SUMMERNOTE -->
    <script src="js/plugins/summernote/summernote.min.js"></script>

    <script>
        $(document).ready(function(){
			$.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
			<?php $content = file_get_contents("js/custom.js.php"); echo $content?>
			$('#add_dairy').hide();

            $('#summernote').summernote();

       });
       
    
	$("#add_dairy_option").click(function(event){
		$('#dairy_actions').hide();
		$('#add_dairy').show();
		
		
});
	function postForm() {

	$('textarea[name="content"]').html($('#summernote').code());
}
    </script>

</body>
</html>
