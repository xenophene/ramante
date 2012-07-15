<?php
/* return my facebook friends names & ids */
include('includes/config.php');
require_once('includes/facebook.php');
$params = array();
$facebook = new Facebook(array(
  "appId"   => '267545116676306',
  "secret"  => '5e33d3900a4253af9159a512ca49b6d1'
));
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
