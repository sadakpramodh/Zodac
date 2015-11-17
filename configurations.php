<?php
//SITE CONFIGUTRATION
define('SITE_NAME','zodac');
define('SITE_VERSION','Alpha 1.0');
define('SITE_LOGO_NAME','KEY');
define('SITE_EMAIL_ID','admin@zodac.esy.es');
define('SITE_COPY_RIGHT_MESSAGE','&copy; zodac -');
define('SITE_COPY_RIGHT_YEAR','2015');
define('SITE_URL','http://sadakpramodh.azurewebsites.net');
define('FACEBOOK_URL','http://facebook.com/sadakpramodhinc');
define('TWITTER_URL','http://twitter.com/sadakpramodhinc');
define('MESSAGE_FROM_ADMIN','Hello users now server is ON For demo purposes.');
define('SITE_STATUS','1');
/*
0-INACTIVE
1-ACTIVE
*/
define('ACCESS_SADAKPRAMODH_PAGES','1');
/*
0-INACTIVE
1-ACTIVE
*/
define('ALLOW_LOGINS','1');
/*
0-INACTIVE
1-ACTIVE
*/
define('ALLOW_REGISTRATIONS','0');
/*
0-INACTIVE
1-ACTIVE
*/

//DATABASE CONFIGURATION	
define('DB_USER_NAME','b340c14f900988');//u742446948_zodac
define('DB_USER_PASSWORD','46d4fa9a');//M2n68amse2@
define('DB_HOST','br-cdbr-azure-south-a.cloudapp.net');//mysql.hostinger.in
define('DB_NAME','sadakpramodh');//u742446948_zodac


//EMAIL SETINGS
define('EMAIL_HOST','mx1.hostinger.in'); 
define('EMAIL_PORT','110/pop3'); 
define('EMAIL_USERNAME','mail@sadakpramodh.esy.es'); 
define('EMAIL_PASSWORD','M2n68amse2@1'); 
 
//ERROR REPORTING
//error_reporting(0);

//ROLES
define('ROLE_NO_ADMIN','0');
define('ROLE_NAME_ADMIN','Admin');

define('ROLE_NO_USER','1');
define('ROLE_NAME_USER','User');


define('ROLE_NO_MODERATE','2');
define('ROLE_NAME_MODERATOR','Moderator');

//APPS
define('INDEX','1');
define('PINBOARD','1');
define('CONTACTS','1');
define('PROFILE','1');
define('CONTACTS_V2','1');
define('PROFILE_V2','1');
define('WEATHER','1');
define('CODE_EDITOR','1');
define('CODE_EDITOR_V2','1');
define('MAIL','1');
define('TODAY_SCHEDULE','1');
define('DAIRY','1');
define('BLOG','1');
define('SEARCH','1');
define('API','1');
define('TIME','1');
define('CALENDAR','1');
define('CALENDAR-DEV','1');
define('MAIL-COMPOSE','1');
define('MAIL-DETAIL','1');
define('MAIL-BOX','1');
define('TWITTER','1');
define('FACEBOOK','1');

//USERS
define('ACCOUNT-SETTINGS','1');

//TWITTER 
define('TWITTER_CONSUMER_KEY', 'QGG0jiJiSyuvC8qE3qzgFpEtt');
define('TWITTER_CONSUMER_SECRET', 'F86wP5TTlunS0Re2oqaqrm8BtNFnbutqS183tw5UUsktwagRFB');
define('TWITTER_OAUTH_CALLBACK', 'http://sadakpramodh.azurewebsites.net/tweet/callback.php');

//FACEBOOK
define('FACEBOOK_APP_ID', '1374739042785433');
define('FACEBOOK_APP_SECRET', '0ab9160267a5e63e5515ea8ca9c0289f');
define('FACEBOOK_OAUTH_CALLBACK', 'http://sadakpramodh.azurewebsites.net/facebook.php?fbTrue=true');

?>