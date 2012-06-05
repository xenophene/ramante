<?php
/* return my facebook friends names & ids */
include('includes/config.php');
require_once('includes/facebook.php');
$facebook = new Facebook(array(
'appId'   => '253395578066052',
'secret'  => '23d20951b5546544b2f2e31183e4b5c0',
'cookie'  => true
));
$params = array();
$user = $facebook->getUser();
/* send the user to login page if he is not correctly logged in */
if ($user) {
  try {
    $access_token = $facebook->getAccessToken();
    $friends = $facebook->api('/me/friends/', 'GET');
    echo json_encode($friends);
  }
  catch(FacebookApiException $e) {}
}
?>
