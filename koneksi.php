<?php

$host = "localhost";
$user = "root";
$password = "";
$dbname = "test3";
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password, array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
));
