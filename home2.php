<?php
  /* 
   * The user homepage. Direct the user to index if not logged in.
   * Else, show the menu elements to start a debate, show ongoing debates, 
   * user profile which has points, etc(as will be decided)
   */
  include('includes/config.php');
  include('includes/aux_functions.php');
  require_once('includes/facebook.php');
  $facebook = new Facebook(array(
    "appId"   => '267545116676306',
    "secret"  => '5e33d3900a4253af9159a512ca49b6d1'
  ));
  $params = array('next' => 'logout.php');
  /* $user is the fbid which we get if the user is logged in. once the user
     accepts to add iitdebates, we also assign him a $userid which is our id */
  /* send the user to login page if he is not correctly logged in */
  $user = $facebook->getUser();
  /*
  if ($user) {
    try {
      // on reaching here, the user is logged in. but we also need to make sure
      // that his/her entry is in our users table
      $query = "SELECT * FROM `users` WHERE `fbid`=$user";
      $result = mysql_query($query);
      if (mysql_num_rows($result) == 0) { // add an entry
        $profile = $facebook->api('/me');
        $name = $profile['name'];
        $query = "INSERT INTO `users` (`fbid`, `name`) VALUES ".
                 "('$user', '$name')";
        mysql_query($query);
        $query = "SELECT MAX(`uid`) FROM `users`";
        $result = mysql_query($query);
        $result = mysql_fetch_assoc($result);
        $userid = $result['MAX(`uid`)'];
        $userprofile = null;
        // insert the default welcome update. and the default debates page
        $query = "INSERT INTO `updates` (`foruid`, `msg`, `timestamp`) VALUES ".
                 "('$userid', 'd160;f', '".date('c')."')";
        $result = mysql_query($query);
      }
      $query = "SELECT * FROM `users` WHERE `fbid`=$user";
      $result = mysql_query($query);
      $userprofile = mysql_fetch_assoc($result);
      $userid = $userprofile['uid'];
    }
    catch (FacebookApiException $e) {
      header('Location: index.php');
    }
  }
  else {
    header('Location: index.php');
  }
  // are we trying to access someone else's profile? if so, uid is set, so we will
  // query our db for uid, get the details and render it from there. else, we will
  // render from the userprofile/fb-details obtained above
  if (array_key_exists('uid', $_GET))
    $uid = $_GET['uid'];
  else
    $uid = 0; // compare $uid and $user to check if we render our own page or not
  if ($userid == $uid and $uid)
    header('Location: home.php');
  if ($uid) {
    $query = "SELECT * FROM `users` WHERE `uid`='$uid'";
    $result = mysql_query($query);
    if (!mysql_num_rows($result))
      header('Location: home.php');
    $userprofile = mysql_fetch_assoc($result);
    // check if user is a follower of uid, in which case show the unfollow button
    $query = "SELECT * FROM `follower` WHERE `uid`='$uid' AND `follower`='$user'";
    $result = mysql_query($query);
    if (!mysql_num_rows($result)) {
      $follower = 'btn btn-primary';
      $follower_text = 'Follow';
    }
    else {
      $follower = 'btn btn-danger';
      $follower_text = 'Unfollow';
    }
  }
  else {
    $follower = 'btn btn-danger';
    $follower_text = 'Unfollow';
  }
  $name = $userprofile['name'];
  $fbid = $userprofile['fbid'];
  $followerDetails = getUserFollowers($userid);
  $followerIds = $followerDetails[0];
  $followerNames = $followerDetails[1];
  $followeeDetails = getUserFollowees($user);
  $followeeIds = $followeeDetails[0];
  $followeeNames = $followeeDetails[1];
*/
// Here we have determined the FB uid of the user. We have also determined 
// if a request for a particular debater was intended. If this is our own 
// profile, we will show some editing options. Else, we show the follow button
?>
<!DOCTYPE html>
<html>
  <head>
    <title>IIT Debates</title>
    <link rel="stylesheet" href="includes/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="includes/css/jquery-ui.css"/>
    <link rel="stylesheet" href="includes/css/jquery.tagit.css"/>
    <link rel="stylesheet" href="includes/style.css"/>
    <link rel="stylesheet" href="includes/style2.css"/>
    <link rel="icon" href="includes/favicon.ico"/>
    <?php
    /*
    echo "<script>
      var uid = '$uid';
      var user = '$user';
      var userid = '$userid';
      var followerIds = '$followerIds';
      var followerNames = '$followerNames';
      var followeeIds = '$followeeIds';
      var followeeNames = '$followeeNames';
      </script>";
    */
    ?>
  </head>
  <body>
    <!--header-->
    <div id="header">
      <span class="logo"><a href="home.php">IIT Debates</a></span>
    </div>
    <div class="container">
      <div class="row">
        <div class="span6 left-col">
          Left column Left column Left column Left column Left column Left column 
          Left column Left column Left column Left column Left column Left column 
          Left column Left column Left column Left column Left column Left column Left column v
          Left column Left column Left column Left column Left column v
          Left column Left column Left column Left column 
        </div>
        <div class="span6 right-col">
          Right column
        </div>
      </div>
    </div>
  </body>
</html>
