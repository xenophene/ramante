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
"appId"   => '253395578066052',
"secret"  => '23d20951b5546544b2f2e31183e4b5c0',
"cookie"  => false
));
$params = array('next' => 'http://localhost/iitdebates/');
$user = $facebook->getUser();
/* redirect the user, if he is already correctly logged in */
if ($user) {
  try {
    $profile = $facebook->api('/me');
    header('Location: home.php');
  }
  catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>IIT Debates</title>
<script src="includes/jquery.min.js" type="text/javascript"></script>
<script src="includes/script.js" type="text/javascript"></script>
<link rel="stylesheet" href="includes/bootstrap/css/bootstrap.min.css"/>
<link rel="stylesheet" href="includes/welcome.css"/>
</head>
<body>
<div id="desc" class="well">
Have something to ask? Have someone to pick a bone with? Have something to settle? Where is your point of view? <br/><br/>
<span class="welcome">Well, welcome to <b>IIT Debates</b>.</span><br/><br/>
Start your own debates, invite your friends to express their views, get rated and compete among your friends. Get invited to popular debates, give your opinions and get noticed. Raise issues close to your heart and your institute and have them settled the <b>right way</b>.<br/><br/>
  <a href="<?php echo $facebook->getLoginUrl($params);?>" class="btn btn-primary btn-large">Sign in with Facebook</a>
<ul>
<li class="first"><a href="#">Join Us</a></li>
<li class="second"><a href="#">Feedback</a></li>
<li><a href="#">About</a></li>
</ul>
</body>
</html>
