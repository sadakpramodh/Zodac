<?php
define('FACEBOOK_SDK_V4_SRC_DIR', 'ProjectBlue/fb/fb_sdk/');
require __DIR__ . '/fb_sdk/autoload.php';
print_r(FACEBOOK_SDK_V4_SRC_DIR);
// Make sure to load the Facebook SDK for PHP via composer or manually

use Facebook\FacebookSession;
// add other classes you plan to use, e.g.:
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

FacebookSession::setDefaultApplication('1374739042785433', '0ab9160267a5e63e5515ea8ca9c0289f');
// Add `use Facebook\FacebookRedirectLoginHelper;` to top of file
$helper = new FacebookRedirectLoginHelper('http://localhost/projectblue/fb/test/');
$loginUrl = $helper->getLoginUrl();
// Use the login url on a link or button to 
// redirect to Facebook for authentication
// Add `use Facebook\FacebookRedirectLoginHelper;` to top of file
$helper = new FacebookRedirectLoginHelper();
try {
  $session = $helper->getSessionFromRedirect();
} catch(FacebookRequestException $ex) {
  // When Facebook returns an error
} catch(\Exception $ex) {
  // When validation fails or other local issues
}
if ($session) {
  // Logged in
}

// Add `use Facebook\FacebookRequest;` to top of file
$request = new FacebookRequest($session, 'GET', '/me');
$response = $request->execute();
$graphObject = $response->getGraphObject();


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