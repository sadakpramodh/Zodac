<?php

?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="http://127.0.0.1/readytoupload/css/bootstrap.min.css">
<link rel="stylesheet" href="http://127.0.0.1/readytoupload/font-awesome/css/font-awesome.css"> 
<!-- include summernote css/js-->
<link href="dist/summernote.css" rel="stylesheet">

<!---
    Summernote Rich Text Editor Example with PHP & Mysql
    http://hackerwins.github.io/summernote/
-->


<body>
<div class="summernote container">
	
	<div class="row">
	    <div class="col-lg-7">
		<form id="postForm" action="save.php" method="POST" enctype="multipart/form-data" onsubmit="return postForm()">
			
			<b>Title</b>
			<input type="text" class="form-control" name="title">
			<br/>
			<textarea id="summernote" name="content" rows="10"></textarea>
			
			<br/>
			<button type="submit" class="btn btn-primary">Save</button>
			<button type="button" id="cancel" class="btn">Cancel</button>
		    
		</form>
		</div>
		
		<div class="col-lg-4">
		    <table class="table">
	            <thead>
	               <tr>
	                   <th>No</th>
	                   <th>Title</th>
	               </tr>
	            <thead>
	            <tbody>
	                <?php include "view.php"; ?>
	            </tbody>
	        </table>
		    
		</div>
		
	</div>
</div>

<!-- include libries(jQuery, bootstrap) -->
<script src="http://127.0.0.1/readytoupload/js/jquery-2.1.1.js"></script>
<script src="http://127.0.0.1/readytoupload/js/bootstrap.min.js"></script>
<script src="dist/summernote.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$('#summernote').summernote({
		height: "300px",
		styleWithSpan: false
	});
});
function postForm() {

	$('textarea[name="content"]').html($('#summernote').code());
}
</script>
</body>
</html>
