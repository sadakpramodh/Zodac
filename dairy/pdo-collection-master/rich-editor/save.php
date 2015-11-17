<pre>
<?php
require "koneksi.php";

$stmt = $pdo->prepare("INSERT INTO article (title,content) VALUES (:title,:content)");
$stmt->bindParam(':title', $title);
$stmt->bindParam(':content', $content);

// insert one row
$title = $_POST['title'];
$content = $_POST['content'];
if($stmt->execute())
$rslt = array('id' => "OK");
header('Content-Type: application/json; charset=utf-8');
		echo json_encode($rslt);
   header("Location:index.php");

    
