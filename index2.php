<?php
  /*
   * Version 1. IIT Debates is a site to facilitate meaningful dialogue in
   * between acquintances on issues of mutual concern. I am more likely to be
   * interested in things that my friends are interested in, and I will be
   * more interested in their opinions.
   * Give users the optiond of
   * 1. starting a debate
   * 2. following a debate
   * 3. follow a debater
   * 4. rate debates, debaters (implicitly)
   * For any debate, the user will have the option of starting a new point, 
   * rebutting a point, supporting a point
   * Each debate has a time frame and then the one with the most points wins (?)
   */
include('includes/config.php');
require_once('includes/facebook.php');
$facebook = new Facebook(array(
'appId'   => '267545116676306',
'secret'  => '5e33d3900a4253af9159a512ca49b6d1',
'cookie'  => true
));
$params = array('scope' => 'read_friendlists,friends_photos');
$user = $facebook->getUser();
/* redirect the user, if he is already correctly logged in*/
if ($user) {
  try {
    $profile = $facebook->api('/me');
    $access_token = $facebook->getAccessToken();
    echo '<a href="'.$facebook->getLogoutUrl($params).'">log out</a>';
  }
  catch (FacebookApiException $e) {
    echo 'logged out';
  }
}
else {
  echo 'logged out';
}
?>
<!DOCTYPE html>
<html>
<head>
<title>IIT Debates</title>
<script src="includes/jquery.min.js" type="text/javascript"></script>
<script src="includes/script.js" type="text/javascript"></script>
<link rel="stylesheet" href="includes/welcome.css"/>
</head>
<body>
<p class="well">
Have something to ask? Have someone to pick a bone with? Have to something to settle? Express your point of view? <br/><br/>
<span class="welcome">Well, welcome to <b>IIT Debates</b>.</span><br/><br/>
Start your own debates, invite your friends to express their views, get rated and compete among your friends. Get invited to popular debates, give your opinions and get noticed. Raise issues close to your heart and your institute and have them settled the <b>right way</b>.
</p>
<center>
<div id="login">
  <p><a href="<?php echo $facebook->getLoginUrl($params);?>" class="fb-login">Sign in with Facebook</a></p>
</div>
<div id="bottom-pane" class="well">
  <ul>
  <li><a href="#">Join Us</a></li>
  <li><a href="#">Feedback</a></li>
  <li><a href="#">About</a></li>
  </ul>
</div>
</center>
</body>
</html>
