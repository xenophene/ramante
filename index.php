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
  "appId"   => '267545116676306',
  "secret"  => '5e33d3900a4253af9159a512ca49b6d1'
));
$params = array('scope' => 'publish_stream,publish_actions',
	              'next'  => 'http://localhost/iitdebates/home.php');
$user = $facebook->getUser();
/* redirect the user, if he is already correctly logged in */
if ($user) {
  try {
    $profile = $facebook->api('/me');
    $access_token = $facebook->getAccessToken();
    header('Location: home.php');
  }
  catch (FacebookApiException $e) {
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>IIT Debates</title>
    <link rel="icon" href="includes/favicon.ico"/>
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
        <li class="first"><a href="fb-ju-ab.php#join-us">Join Us</a></li>
        <li class="second"><a href="fb-ju-ab.php#feedback">Feedback</a></li>
        <li><a href="fb-ju-ab.php#about">About</a></li>
      </ul>
    </div>
    <script src="includes/jquery.min.js"></script>
  </body>
</html>
