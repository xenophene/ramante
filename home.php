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
if (array_key_exists('uid', $_GET))
  $uid = $_GET['uid'];
else
  $uid = 0; // compare $uid and $user to check if we render our own page or not
/* Here we have determined the FB uid of the user. We have also determined 
   if a request for a particular debater was intended. If this is our own 
   profile, we will show some editing options. Else, we show the follow button*/
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
  <a href="#" class="notifications"><li>Updates</li></a>
  <a href="<?php echo $facebook->getLogoutUrl($params);?>"><li>Log Out</li></a>
  </ul>
</span>
</div>
<!--The user profile is displayed here. Pic, Score, ...-->
<div id="profile">
<a href="<?php echo 'https://facebook.com/'.$profile['username'];?>" target="_blank"><img class="pic" src="<?php echo 'https://graph.facebook.com/'.$user.'/picture?type=normal';?>"/></a>
<table class="details">
<tbody>
<tr><td class="name"><?php echo $profile['name'];?></td></tr>
<tr><td><label class="interest">interested in:</label></td><td><?php echo 'Politics, Hostel Mess';?>
<?php
  if ($uid == 0 or $user == $uid) {
    echo '<label class="add"><a href="#">add/change</a></label>';
  }
?>
</td>
</tr>
<tr><td><label class="interest">debates started:</label></td><td><?php echo 'thrown challenges';?></td></tr>
<tr><td><label class="interest">debates won:</label></td><td><?php echo 'accepted challenges';?></td></tr>
</tbody>
</table>
<ul class="engage">
<?php
  if ($uid == 0 or $user == $uid) {
?>
<li title="start">Start a new debate</li>
<?php
  }
  else { // have the user interact with other's profiles
?>
<li title="invite">Invite</li>
<li title="follow">Follow</li>
<li title="challenge">Challenge</li>
<?php
  }
?>
</ul>
</div>
<div id="content">
<div id="past-debates">
</div>
<div id="updates">
</div>
</div>
</body>
</html>
