<?php
define('FACEBOOK_SDK_V4_SRC_DIR', '../fb_sdk/');
require __DIR__ . '..\fb_sdk\autoload.php';
// Make sure to load the Facebook SDK for PHP via composer or manually

use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

if($session) {
  try {
    $user_profile = (new FacebookRequest(
      $session, 'GET', '/me'
    ))->execute()->getGraphObject(GraphUser::className());
    echo "Name: " . $user_profile->getName();
  } catch(FacebookRequestException $e) {
    echo "Exception occured, code: " . $e->getCode();
    echo " with message: " . $e->getMessage();
  }   
}
?>