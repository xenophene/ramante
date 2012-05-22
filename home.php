<?php
  /* 
   * The user homepage. Direct the user to index if not logged in.
   * Else, show the menu elements to start a debate, show ongoing debates, 
   * user profile which has points, etc(as will be decided)
   */
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
    $profile = $facebook->api('/me', 'GET');
  }
  catch (FacebookApiException $e) {
    header('Location: index.php');
  }
}
else {
  header('Location: index.php');
}

?>
<!--If we are here, we are definitely logged into FB, so we can use it-->
<!DOCTYPE html>
<html>
<head>
<title>IIT Debates</title>
<script src="includes/jquery.min.js" type="text/javascript"></script>
<script src="includes/script.js" type="text/javascript"></script>
<link rel="stylesheet" href="includes/style.css"/>
</head>
<body>
<div id="header">
<span class="logo">IIT Debates</span>
<span class="options">
  <ul>
  <a href="<?php echo $facebook->getLogoutUrl($params);?>"><li>Log Out</li></a>
  </ul>
</span>
</div>
<!--The user profile is displayed here. Pic, Score, ...-->
<div id="profile">
<a href="<?php echo $profile['url'];?>"><img class="pic" src="<?php echo 'https://graph.facebook.com/'.$user.'/picture?type=large';?>"/></a>
<table class="details">
<tbody>
<tr>
<td><?php echo $profile['name'];?></td>
<td>Honou </td>
</tr>
</tbody>
</table>
</div>
</body>
</html>
