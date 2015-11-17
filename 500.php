<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
$page_name="505.php";
require_once("comns/pagelogic.php");

?>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | 500 Error</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">


    <div class="middle-box text-center animated fadeInDown">
        <h1>500</h1>
        <h3 class="font-bold">Internal Server Error</h3>

        <div class="error-desc">
            The server encountered something unexpected that didn't allow it to complete the request. We apologize.<br/>
			
			<?php
			if($_SESSION["message"] != null)
				{
				display_message($_SESSION["message"], $_SESSION["type"]);
				}
			$_SESSION["message"] = null;
			?>
			
            You can go back to main page: <br/><a href="index.php" class="btn btn-primary m-t">Home</a>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>
</html>
