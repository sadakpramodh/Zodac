<?php

include("connection.php");
include("functions.php");

session_check();
?>
<?php
function encrypt_decrypt($action, $string)
	{
	$output = false;
	$encrypt_method="AES-256-CBC";
	$secret_key ="this is my secret key";
	$secret_iv ="this is my secret key";
	
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv),0,16);
	if( $action == 'encrypt')
		{
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		$output = base64_encode($output);
		}
	elseif( $action == 'decrypt')
		{
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key,0, $iv);
		}
	return $output;
	}
	$plain_text="1";
	$a=encrypt_decrypt('encrypt', $plain_text);
	$b=encrypt_decrypt('decrypt', $a);
	echo $plain_text."\n".strlen($plain_text);
	echo"<br>";
	echo $a."\n".strlen($a);
	echo"<br>";
	echo $b."\n".strlen($b);
	
?>