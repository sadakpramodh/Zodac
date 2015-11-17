<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="code_editor_v2.php";
if(CODE_EDITOR_V2 != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>
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
	
}

mysqli_free_result($result);
databaseconnectivity_close($connection);

?>
<?php

if(isset($_GET["savechanges"]))
{
	if(isset($_POST["code"]) && isset($_POST["file_path"]))
	{
		$file_path=$_POST["file_path"];
		$myfile = fopen($file_path, "w") or die("Unable to open file!");
		fwrite($myfile, $_POST["code"]);
		fclose($myfile);
		$_SESSION["message"]="File saved";
		$_SESSION["type"]=0;
	}
	
}

				
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME ; ?> | Code editor</title>

  <?php 
	require_once("comns/head_section.php")
?>
    <link href="css/plugins/codemirror/codemirror.css" rel="stylesheet">
	
     <link href="css/plugins/jsTree/style.min.css" rel="stylesheet">
	
	<link rel="stylesheet" href="css/plugins/codemirror/3024-day.css">
	<link rel="stylesheet" href="css/plugins/codemirror/3024-night.css">
	<link rel="stylesheet" href="css/plugins/codemirror/abcdef.css">
	<link rel="stylesheet" href="css/plugins/codemirror/ambiance.css">
	<link rel="stylesheet" href="css/plugins/codemirror/base16-dark.css">
	<link rel="stylesheet" href="css/plugins/codemirror/base16-light.css">
	<link rel="stylesheet" href="css/plugins/codemirror/blackboard.css">
	<link rel="stylesheet" href="css/plugins/codemirror/cobalt.css">
	<link rel="stylesheet" href="css/plugins/codemirror/colorforth.css">
	<link rel="stylesheet" href="css/plugins/codemirror/dracula.css">
	<link rel="stylesheet" href="css/plugins/codemirror/eclipse.css">
	<link rel="stylesheet" href="css/plugins/codemirror/elegant.css">
	<link rel="stylesheet" href="css/plugins/codemirror/erlang-dark.css">
	<link rel="stylesheet" href="css/plugins/codemirror/icecoder.css">
	<link rel="stylesheet" href="css/plugins/codemirror/lesser-dark.css">
	<link rel="stylesheet" href="css/plugins/codemirror/liquibyte.css">
	<link rel="stylesheet" href="css/plugins/codemirror/material.css">
	<link rel="stylesheet" href="css/plugins/codemirror/mbo.css">
	<link rel="stylesheet" href="css/plugins/codemirror/mdn-like.css">
	<link rel="stylesheet" href="css/plugins/codemirror/midnight.css">
	<link rel="stylesheet" href="css/plugins/codemirror/monokai.css">
	<link rel="stylesheet" href="css/plugins/codemirror/neat.css">
	<link rel="stylesheet" href="css/plugins/codemirror/neo.css">
	<link rel="stylesheet" href="css/plugins/codemirror/night.css">
	<link rel="stylesheet" href="css/plugins/codemirror/paraiso-dark.css">
	<link rel="stylesheet" href="css/plugins/codemirror/paraiso-light.css">
	<link rel="stylesheet" href="css/plugins/codemirror/pastel-on-dark.css">
	<link rel="stylesheet" href="css/plugins/codemirror/rubyblue.css">
	<link rel="stylesheet" href="css/plugins/codemirror/seti.css">
	<link rel="stylesheet" href="css/plugins/codemirror/solarized.css">
	<link rel="stylesheet" href="css/plugins/codemirror/the-matrix.css">
	<link rel="stylesheet" href="css/plugins/codemirror/tomorrow-night-bright.css">
	<link rel="stylesheet" href="css/plugins/codemirror/tomorrow-night-eighties.css">
	<link rel="stylesheet" href="css/plugins/codemirror/ttcn.css">
	<link rel="stylesheet" href="css/plugins/codemirror/twilight.css">
	<link rel="stylesheet" href="css/plugins/codemirror/vibrant-ink.css">
	<link rel="stylesheet" href="css/plugins/codemirror/xq-dark.css">
	<link rel="stylesheet" href="css/plugins/codemirror/xq-light.css">
	<link rel="stylesheet" href="css/plugins/codemirror/yeti.css">
	<link rel="stylesheet" href="css/plugins/codemirror/zenburn.css">
	<link rel="stylesheet" href="js/plugins/codemirror/addon/display/fullscreen.css">


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
                    <h2>Code Editor</h2>
                   
                </div>
                
            </div>
			<?php
			if($_SESSION["message"] != null)
							{
							display_message($_SESSION["message"], $_SESSION["type"]);
							}
						$_SESSION["message"] = null;
						?>
        <div class="wrapper wrapper-content  animated fadeInRight">
            <div class="row">
			
				
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>File name<br></h5>
							<div class="ibox-tools">
                              
                            </div>
                        </div>
                        <div class="ibox-content">

   <strong><p>Choose your theme</p></strong>                        
<select onchange="selectTheme()" id="select" class="form-control m-b valid">
	<option selected>default</option>
	<option>3024-day</option>
	<option>3024-night</option>
	<option>abcdef</option>
	<option>ambiance</option>
	<option>base16-dark</option>
	<option>base16-light</option>
	<option>blackboard</option>
	<option>cobalt</option>
	<option>colorforth</option>
	<option>dracula</option>
	<option>eclipse</option>
	<option>elegant</option>
	<option>erlang-dark</option>
	<option>icecoder</option>
	<option>lesser-dark</option>
	<option>liquibyte</option>
	<option>material</option>
	<option>mbo</option>
	<option>mdn-like</option>
	<option>midnight</option>
	<option>monokai</option>
	<option>neat</option>
	<option>neo</option>
	<option>night</option>
	<option>paraiso-dark</option>
	<option>paraiso-light</option>
	<option>pastel-on-dark</option>
	<option>rubyblue</option>
	<option>seti</option>
	<option>solarized dark</option>
	<option>solarized light</option>
	<option>the-matrix</option>
	<option>tomorrow-night-bright</option>
	<option>tomorrow-night-eighties</option>
	<option>ttcn</option>
	<option>twilight</option>
	<option>vibrant-ink</option>
	<option>xq-dark</option>
	<option>xq-light</option>
	<option>yeti</option>
	<option>zenburn</option>
  </select>
<form method="post" action="code_editor_v2.php?savechanges">
 <div class="form-group"><label>File path</label>
			   <input type="text" id="file_path" class="form-control" name="file_path" value="<?php 
			   
			   if(isset($_GET["file_path"])){
				   echo $_GET["file_path"];
				   }
				else if(isset($_POST["file_path"]))
				{
					echo $_POST["file_path"];
				}
				   ?>">
			  
			   </div>
			    <textarea id="code" name="code"><?php if(isset($_GET["file_path"])){
						$content = file_get_contents($_GET["file_path"]);
						echo $content;}
						else if(isset($_POST["file_path"]))
						{
							$content = file_get_contents($_POST["file_path"]);
							echo $content;
						}
					?></textarea>
 <input type="submit" id="saveedit" class="btn btn-outline btn-success block full-width" value="save changes">
			   

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

    <!-- CodeMirror -->
    <script src="js/plugins/codemirror/codemirror.js"></script>
	<script src="js/plugins/codemirror/addon/hint/show-hint.js"></script>
	<script src="js/plugins/codemirror/addon/hint/javascript-hint.js"></script>
    <script src="js/plugins/codemirror/mode/javascript/javascript.js"></script>
  <script src="js/plugins/codemirror/addon/fold/foldcode.js"></script>
  <script src="js/plugins/codemirror/addon/fold/foldgutter.js"></script>
  <script src="js/plugins/codemirror/addon/fold/brace-fold.js"></script>
  <script src="js/plugins/codemirror/addon/fold/xml-fold.js"></script>
  <script src="js/plugins/codemirror/addon/fold/markdown-fold.js"></script>
  <script src="js/plugins/codemirror/addon/fold/comment-fold.js"></script>
  <script src="js/plugins/codemirror/mode/xml/xml.js"></script>
  <script src="js/plugins/codemirror/addon/selection/active-line.js"></script>
  <script src="js/plugins/codemirror/addon/edit/matchbrackets.js"></script>
  <script src="js/plugins/codemirror/mode/markdown/markdown.js"></script>
  <script src="js/plugins/codemirror/addon/display/fullscreen.js"></script>

<script>
		$(document).ready(function(){
			$.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
			<?php $content = file_get_contents("js/custom.js.php"); echo $content?>
			
		});
		</script>
    <script>
     /* $("#saveedit").click(function(event){
			//var x = document.getElementById["code"].value;
			
			var code  = $("#code").val();
			var file_path = $("#file_path").val();
		
			$.post( 
                  "code_editor_v2.php?savechanges",
                  { "code": code ,
					"file_path": file_path},
                  function(data) {
					  if(data) {
							
							$('#stage').val(data.status);
					  }
                  }
               );
			   
			$("#code").val="fff";
			   
		}); */ 

	 var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
		 lineNumbers: true,
		 matchBrackets: true,
		 styleActiveLine: true,
		 extraKeys: {
			 "Ctrl-Space": "autocomplete",
		 "F11": function(cm) {
          cm.setOption("fullScreen", !cm.getOption("fullScreen"));
        },
        "Esc": function(cm) {
          if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
        }
		},
		 mode: {name: "javascript", globalVars: true},
		 foldGutter: true,
		 gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
		 
	 });
	 var input = document.getElementById("select");
	  function selectTheme() {
		var theme = input.options[input.selectedIndex].textContent;
		editor.setOption("theme", theme);
		location.hash = "#" + theme;
	  }
	  var choice = (location.hash && location.hash.slice(1)) ||
               (document.location.search &&
                decodeURIComponent(document.location.search.slice(1)));
		  if (choice) {
			input.value = choice;
			editor.setOption("theme", choice);
		  }
		  CodeMirror.on(window, "hashchange", function() {
			var theme = location.hash.slice(1);
			if (theme) { input.value = theme; selectTheme(); }
		  });
		
		</script>

</body>
</html>
