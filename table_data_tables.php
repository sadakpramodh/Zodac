<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="table_data_tables.php";
if(ACCESS_SADAKPRAMODH_PAGES != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME; ?> | sadak pramodh Marklists</title>
<?php 
	require_once("comns/head_section.php")
?>
    <!-- Data Tables -->
    <link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
    <link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">

	 <!-- morris -->
    <link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
	

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
                    <h2>Sadak Pramodh Marks</h2>
                    
                </div>
                
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Marks table</h5>
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

                    <table class="table table-striped table-bordered table-hover dataTables-example" >
                    <thead>
                    <tr>
					<th>Number</th>
                    <th>Standard</th>
					<th>Awarded Marks</th>
					<th>Maximum Marks</th>
					<th>Percentage</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php
					$connection=databaseconnectivity_open();
					
					$query = "select * from marklists order by number asc";
					$result = mysqli_query($connection, $query);
					while($row = mysqli_fetch_assoc($result))
											{
												echo"<tr class=\"gradeX\">";
												echo"<td class=\"center\">{$row["number"]}</td>";
												echo"<td class=\"center\">{$row["standard"]}</td>";
												echo"<td class=\"center\">{$row["awarded_marks"]}</td>";
												echo"<td class=\"center\">{$row["maximum_marks"]}</td>";
												$percentage=$row["awarded_marks"] / $row["maximum_marks"]*100;
												echo"<td class=\"center\">{$percentage}</td>";
												echo"</tr>";
																	
											}
										mysqli_free_result($result);
										databaseconnectivity_close($connection);
					 
					
					?>
					<tfoot>
                    <tr>
					<th>Number</th>
					<th>Standard</th>
					<th>Awarded Marks</th>
					<th>Maximum Marks</th>
					<th>Percentage</th>
					
				</tr>
                    </tfoot>
                    </table>

                    </div>
                </div>
            </div>
            </div>
			
			
			
			
           

        </div>
		<?php
			require_once("footer.php");
			?>
		<?php
			require_once("rightsidebar.php");
		?>
        </div>



<?php require_once("user_skin_config.php"); ?>
	<?php 
		require_once("comns/body_section.php")
	?>

    <script src="js/plugins/jeditable/jquery.jeditable.js"></script>

    <!-- Data Tables -->
    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="js/plugins/dataTables/dataTables.responsive.js"></script>
    <script src="js/plugins/dataTables/dataTables.tableTools.min.js"></script>



	
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {
			$.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
			<?php $content = file_get_contents("js/custom.js.php"); echo $content?>
            $('.dataTables-example').dataTable({
                responsive: true,
                "dom": 'T<"clear">lfrtip',
                "tableTools": {
                    "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
                }
            });

            /* Init DataTables */
            var oTable = $('#editable').dataTable();

            /* Apply the jEditable handlers to the table */
            oTable.$('td').editable( 'http://webapplayers.com/example_ajax.php', {
                "callback": function( sValue, y ) {
                    var aPos = oTable.fnGetPosition( this );
                    oTable.fnUpdate( sValue, aPos[0], aPos[1] );
                },
                "submitdata": function ( value, settings ) {
                    return {
                        "row_id": this.parentNode.getAttribute('id'),
                        "column": oTable.fnGetPosition( this )[2]
                    };
                },

                "width": "90%",
                "height": "100%"
            } );


        });

      
    </script>
<style>
    body.DTTT_Print {
        background: #fff;

    }
    .DTTT_Print #page-wrapper {
        margin: 0;
        background:#fff;
    }

    button.DTTT_button, div.DTTT_button, a.DTTT_button {
        border: 1px solid #e7eaec;
        background: #fff;
        color: #676a6c;
        box-shadow: none;
        padding: 6px 8px;
    }
    button.DTTT_button:hover, div.DTTT_button:hover, a.DTTT_button:hover {
        border: 1px solid #d2d2d2;
        background: #fff;
        color: #676a6c;
        box-shadow: none;
        padding: 6px 8px;
    }

    .dataTables_filter label {
        margin-right: 5px;

    }
</style>

<!-- Morris demo data-->
    <script src="js/demo/morris-demo.js"></script>
 <!-- Morris -->
    <script src="js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="js/plugins/morris/morris.js"></script>
</body>
</html>
