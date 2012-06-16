<?php
  /* 
   * The user homepage. Direct the user to index if not logged in.
   * Else, show the menu elements to start a debate, show ongoing debates, 
   * user profile which has points, etc(as will be decided)
   */
include('includes/config.php');
require_once('includes/facebook.php');
$facebook = new Facebook(array(
"appId"   => '253395578066052',
"secret"  => '23d20951b5546544b2f2e31183e4b5c0',
"cookie"  => false
));
/*
$params = array(
    "appId"   => '253395578066052',
    "secret"  => '23d20951b5546544b2f2e31183e4b5c0',
    "cookie"  => false
    );
    */
$params = array('next' => 'http://localhost/iitdebates/logout.php');    
// global $user;
 $user = $facebook->getUser();
/* $user is the fbid which we get if the user is logged in. once the user
   accepts to add iitdebates, we also assign him a $userid which is our id */
/* send the user to login page if he is not correctly logged in */
if ($user) {
  try {
    /* on reaching here, the user is logged in. but we also need to make sure
       that his/her entry is in our users table */
    $query = "SELECT * FROM `users` WHERE `fbid`=$user";
    $result = mysql_query($query);
//    global $user;
  //  echo $user;
    //echo "alsdkasld";
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
  //  echo $userid;
//    echo "je";
  }
  catch (FacebookApiException $e) {
    header('Location: index.php');
  //  echo $user;
//    echo "redirect";
  }
}
else {
//    echo $user;
    
  header('Location: index.php');
}
/* are we trying to access someone else's profile? if so, uid is set, so we will
   query our db for uid, get the details and render it from there. else, we will
   render from the userprofile/fb-details obtained above */
if (array_key_exists('uid', $_GET))
  $uid = $_GET['uid'];
else
  $uid = 0; // compare $uid and $user to check if we render our own page or not
if ($userid == $uid)
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
/* Here we have determined the FB uid of the user. We have also determined 
   if a request for a particular debater was intended. If this is our own 
   profile, we will show some editing options. Else, we show the follow button*/
?>
<!--If we are here, we are definitely logged into FB, so we can use it-->
<!DOCTYPE html>
<html>
<head>
<title>IIT Debates</title>
<link rel="stylesheet" href="includes/bootstrap/css/bootstrap.min.css"/>
<link rel="stylesheet" href="includes/css/jquery-ui.css"/>
<link rel="stylesheet" href="includes/css/jquery.tagit.css"/>
<link rel="stylesheet" href="includes/style.css"/>
<script src="includes/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="includes/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="includes/js/jquery-ui-min.js" type="text/javascript"></script>
<script src="includes/js/tag-it.js" type="text/javascript"></script>
<script src="includes/script.js" type="text/javascript"></script>
<?php
echo "<script>
  var uid = '$uid';
  var user = '$user';
  var userid = '$userid';
  </script>
";
?>
</head>
<body>
<div id="header">
<span class="logo"><a href="home.php">IIT Debates</a></span>
<span class="options">
  <ul>
  <a href="<?php echo $facebook->getLogoutUrl($params);?>"><li>Log Out</li></a>
  </ul>
</span>
</div>
<!--The user profile is displayed here. Pic, Score, ...-->
<div id="profile">
<a href="<?php echo 'https://facebook.com/profile.php?id='.$userprofile['fbid'];?>" target="_blank"><img class="pic" src="<?php echo 'https://graph.facebook.com/'.$fbid.'/picture?type=normal';?>"/></a>
<table class="details">
<tbody>
<tr><td class="name"><?php echo $name;?></td></tr>
<tr><td class="contain-interest"><span id="interested-in" class="interest">interested in:</span></td>
<td class="interest-elements"><?php if ($userprofile != null) echo $userprofile['interests'];?></td><td class="interest-elements-p"><span class="add">+</span></td>
</tr>
<tr><td><span id="debating-points" class="interest">debating points:</span></td><td class="debate-score"><?php if ($userprofile != null) echo $userprofile['score'];?></td></tr>
<!--<tr><td><span id="debates-won" class="interest">debates won:</span></td><td><?php if ($userprofile != null) echo $userprofile['debateswon'];?></td></tr>-->
</tbody>
</table>
<ul class="engage">
<?php
  if ($uid == 0 or $user == $uid):
?>
<li title="Start a new debate" id="start" class="btn btn-primary">Start a new debate</li>
<?php
  else: // have the user interact with other's profiles
?>
<li title="Invite to my debates" id="invite" class="btn btn-primary">Invite</li>
<li title="Follow this user's activity" id="follow" class="<?php echo $follower;?>"><?php echo $follower_text;?></li>
<li title="Challenge to a debate" id="challenge" class="btn btn-primary">Challenge</li>
<?php
  endif;
?>
</ul>
</div>
<div id="content">
<!--The main canvas to show all activity for the user in the form of updates.
    These are either invites from friends for debates, updates in debates
    I follow. (I automatically follow the debates I start or I participate in)-->
<div id="my-debates">
<span class="home-heading">
<?php
if ($uid)
  echo $userprofile['name']."'s Debates";
else
  echo 'My Debates';
?>
</span>
<table class="table debate-table">
<?php
/* query debates by the user and render here */
$query = "SELECT * FROM `debates` WHERE `participants` LIKE '%$fbid%'";
$result = mysql_query($query);
if (mysql_num_rows($result))
  echo '<thead>
  <tr><td>Debate Name</td><td>Points</td><td>Votes</td><td>Time Left</td></tr>
  </thead>
  <tbody>';
else {
  $you = "You don't ";
  if ($uid)
    $you = $name. " doesn't ";
  echo "<thead></thead><tbody><tr><td>".$you."have any ongoing debates right now</td></tr>";
}
while ($row = mysql_fetch_array($result)) {
  $debid = $row['debid'];
  echo '<tr id="'.$debid.'"><td>'.$row['topic'].'</td>'.
       '<td>'.$row['debscore'].'</td>'.
       '<td>'.$row['rating'].'</td>'.
       '<td>';
  $days = (strtotime(date("Y-m-d")) - strtotime($row['startdate'])) / (60 * 60 * 24);
  $daylimit = $row['timelimit'];
  if ($daylimit - $days > 0)
    echo ($daylimit - $days).' days';
  else
    echo 'Closed';
  echo '</td></tr>';
}
?>
</tbody>
</table>
</div>
<div id="my-updates">
<span class="home-heading">Updates</span>
<?php
  // do a HACK here, and only show your own updates
  if (!$uid):
/**
  * Query updates table and show the updates
  * The debates are coded in the msg field. We have an update for a [d]ebate or
  * a [u]ser. In either case, the first letter is followed by the id of that
  * entity in the db. If some other info is required, it will be appended with
  * semi-colons & can be extended later on.
  */
$query = "SELECT * FROM `updates` WHERE `foruid`='$userid'";
$result = mysql_query($query);
while ($row = mysql_fetch_array($result)) {
  echo '<div id="update">';
  $msg = $row['msg'];
  $tok = explode(';', $msg);
  if (substr($tok[0], 0, 1) == 'd') {
    if ($tok[1] == 'f') { // this is a follow debate request
      $debid = substr($tok[0], 1);
      $query = "SELECT * FROM `debates` WHERE `debid`='$debid'";
      $result = mysql_query($query);
      $row = mysql_fetch_assoc($result);
      echo 'View the debate <a href="debate.php?debid='.$debid.'">'.$row['topic'].'</a>';
    }
  }
  echo '</div>';
}
?>
  <div id="update">
  Welcome to IIT Debates. Thank you for signing up and now you are free to
  debate to your heart's content. To make matters interesting, you have been
  given 100 debating seed points to start with. You need points to start debates, 
  invite your friends to them and rank other debates/comments. You earn
  debating points by defining debates that become popular, writing arguments
  that earn upvotes from debate followers.
  </div>
<?php
else:
  echo '<div id="update">';
  echo "You don't have the permission to view ".$userprofile['name']."'s updates.";
  echo '</div>';
endif;
?>
</div>
</div>
<div id="mask"></div>
<div id="start-debate-form" class="window">
  <form class="well" action="debate.php" method="POST" style="margin: 0px;">
    <p class="emph">Start a new debate</p>
    <input type="text" title="Debate's Topic" name="debate-topic" id="debate-topic" class="input-xxlarge" placeholder="Debate Topic" autocomplete="off"/>
    <textarea class="input-xxlarge" title="Debate's Description" name="debate-desc" id="debate-desc" placeholder="Debate Description" rows="4" autocomplete="off"></textarea>
    <input type="text" name="debate-theme" title="Debate's Themes" id="debate-theme" class="input-xxlarge" placeholder="Debate Themes" autocomplete="off" spellcheck="false"/>
    <input type="text" name="participants" title="Debate's Participants" id="participants" class="input-xxlarge ui-autocomplete-input" placeholder="Challenge Friends" autocomplete="off" spellcheck="false"/><br/>
    <input type="hidden" name="participant-ids" title="Debate's Participants" id="participant-ids"/>
    <div id="radio" title="Enter the debate time limit">
		  <input type="radio" id="time-limit-1" name="time-limit" value="10"  checked="checked" /><label for="time-limit-1">10 days</label>
		  <input type="radio" id="time-limit-2" name="time-limit" value="20" /><label for="time-limit-2">20 days</label>
		  <input type="radio" id="time-limit-3" name="time-limit" value="30" /><label for="time-limit-3">30 days</label>
		  <input type="radio" id="time-limit-4" name="time-limit" value="40" /><label for="time-limit-4">40 days</label>
	  </div>
	  <br/>
    <a class="btn btn-primary" id="start-debate" disabled>Start</a>
    <a class="btn" id="cancel-debate">Cancel</a>
  </form>
</div>
</body>
</html>
