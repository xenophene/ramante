<?php
/**
  * debate.php is the main point to contain info about debates. We can land on
  * this page from 2 points, the debate creation form on home.php (or other 
  * places) and a link to a particular debate. In either case, a unique
  * we will get some data as POST variables. From debate creation, all the
  * relevant information of the debate will be given instead of a debate id
  * so the job is to first create the db entry for this debate
  * The following are the features of any debate:
  * debid, score (as calculated by number of followers/rating) which will be given to winning side,
  * topic, description, timelimit, themes, participants, followers, rating, creator, start-date, winners
  * the participants automatically become followers as well
  * -------------------------------------------------------------
  * the comments for any debate are in a separate table which has
  * comid, score, author, debid, foragnst
  * -------------------------------------------------------------
  */
include('includes/config.php');
include('includes/aux_functions.php');
require_once('includes/facebook.php');
$params = array('next' => 'http://localhost/iitdebates/logout.php');
$facebook = new Facebook(array(
  "appId"   => '253395578066052',
  "secret"  => '23d20951b5546544b2f2e31183e4b5c0',
  "cookie"  => false
));
$user = $facebook->getUser();
/* send the user to login page if he is not correctly logged in */
if ($user) {
  try {
    $profile = $facebook->api('/me', 'GET');
  }
  catch (FacebookApiException $e) {
    header('Location: index.php');
  }
}
else {
  header('Location: index.php');
}
/* is the current user $user a participant? this is true if we have just 
  created this database, else we will figure this out from the db */
$is_participant = false;
/* check the incoming POST variable to see if debate ID exists. if not, we 
  should have the other details which are added to the db. if debid exists, 
  we can simply query the db to render the page */
if (!array_key_exists('debid', $_GET)) { //get the details and add to db
  header('Location: home.php');
}
else { // get the details & render the page
  $debid = $_GET['debid'];
  $query = "SELECT * FROM `debates` WHERE `debid`='$debid'";
  $result = mysql_query($query);
  $debate = mysql_fetch_assoc($result);
  $userid = $debate['creator'];
  // get all the comma separated participant names
  // check if the current user $userid is a participant
  $participants = $debate['participants'];
  $ps = explode(',',$participants);
  $participant_names = '';
  $is_participant = false;
  foreach($ps as $p) {
    if (trim($p) == $user)
      $is_participant = true;
  }
  $participant_names = namesFromIds($ps);
  $followers = $debate['followers'];
  $fs = explode(',',$followers);
  $follower_names = namesFromIds($fs);
  if ($followers == '')
    $follower_qty = 0;
  else
    $follower_qty = sizeof($fs);
  $following = false;
  foreach($fs as $f) {
    $p = trim($f);
    if ($p == $user)
      $following = true;
  }
  $debatetopic = $debate['topic'];
  $debatedesc = $debate['description'];
  $timelimit = $debate['timelimit'];
  $startdate = $debate['startdate'];
  $debscore = $debate['debscore'];
  $privacy = $debate['privacy'];
  // for now allow this special debate to be editable by all
  $is_participant = $is_participant or $debid == 160; 
}
$query = "SELECT * FROM `users` WHERE fbid='$userid'";
$result = mysql_query($query);
$creator = mysql_fetch_assoc($result);
$comments = commentsArray($debid);
if ($following) {
  $followingClass = 'btn-danger disabled';
  $followingText = 'Following';
}
else {
  $followingClass = 'btn-primary';
  $followingText = 'Follow';
}
$myname = $profile['name'];
/* echo back important js state variables */
$creatorname = $creator['name'];
?>
<!DOCTYPE html>
<html>
<head>
<?php
echo "
  <script>
  var user = '$user';
  var userid = '$userid';
  var debid = '$debid';
  var myname = '$myname';
  var username = '$creatorname';
  var participantNames = '$participant_names';
  var participantIds = '$participants';
  var followerIds = '$followers';
  var followerNames = '$follower_names';
  </script>
";
?>
<link rel="stylesheet" href="includes/bootstrap/css/bootstrap.min.css"/>
<link rel="stylesheet" href="includes/css/jquery-ui.css"/>
<link rel="stylesheet" href="includes/style.css"/>
<link rel="icon" href="includes/favicon.ico"/>
<script src="includes/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="includes/js/jquery-ui-min.js" type="text/javascript"></script>
<script src="includes/js/jquery.autosize-min.js" type="text/javascript"></script>
<script src="includes/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="includes/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="includes/debate-script.js" type="text/javascript"></script>
<title><?php echo $debatetopic;?></title>
</head>
<body>
<div id="header">
<span class="logo"><a href="home.php">IIT Debates</a></span>
<span class="fb-ju-ab">
  <ul>
  <li><a href="#" id="fb">Feedback</a></li>
  <li><a href="#" id="ju">Join Us</a></li>
  <li><a href="#" id="ab">About</a></li>
  </ul>
</span>
<span class="options">
  <ul>
  <li class="search-form">
  <input class="navbar-search" type="text" id="friend-search" data-provide="typeahead" placeholder="Search" autocomplete="off">
  <div class="icon-search icon-black"></div>
  </li>
  <li class="log-out-link"><a href="home.php">Home</a></li>
  <li class="log-out-link"><a href="<?php echo $facebook->getLogoutUrl($params);?>">Log Out</a></li>
  </ul>
</span>
</div>
<div id="profile">
<div id="debate-details">
  <span class="topic">
  <?php echo $debatetopic;?>
  </span>
  <br/>
  <span class="desc">
  <?php echo $debatedesc;?>
  </span>
</div>
<table class="d-details">
<tbody>
  <tr><td><span class="interest">Created by:</span></td>
  <td>
  <?php
    echo '<a href="home.php?uid='.$creator['uid'].'">'.$creator['name'].'</a>';
  ?>
  </td>
  </tr>
  <tr><td><span class="interest">Debate Points:</span></td><td><?php echo $debscore;?></td></tr>
  <tr><td><span class="interest"># Followers:</span></td><td><?php echo $follower_qty;?></td></tr>
  <tr><td><span class="interest">Time Left:</span></td>
  <td>
  <?php 
    $days = (strtotime(date("Y-m-d")) - strtotime($startdate)) / (60 * 60 * 24);
    if ($timelimit - $days > 0)
      echo ($timelimit - $days).' days';
    else
      echo 'Closed';
  ?>
  </td>
  </tr>
</tbody>
</table>
<div class="engage">
<?php
  /* check if the current user is a participant or not. if not, we show the
    follow button here. */
  if (!$is_participant) {
?>
<a title="Follow Debate" id="follow-debate" class="btn <?php echo $followingClass;?> engage-btn"><?php echo $followingText;?></a><br/>
<?php
  }
  else {
?>
<a title="Invite friends to this debate" id="invite-to-debate" class="btn btn-primary engage-btn">Invite Friends</a><br/>
<?php
  }
?>
<a title="See all participants" id="view-participants" class="btn btn-primary engage-btn">Participants</a><br/>
<a title="See all followers" id="view-followers" class="btn btn-primary engage-btn">Followers</a>
</div>
</div>
<div id="content">
  <div id="yes">
  <?php
  // show these boxes only if I am a participant or * this is a public debate *
  if ($is_participant or !$privacy):
  ?>
  <textarea class="yes" id="comment-yes" placeholder="I agree..." rows=2></textarea>
  <button id="post-yes" class="btn btn-success comment-post-btn" disabled>Post</button>
  <?php
    endif;
  ?>
  <div id="comments">
  <?php
    /* echo the comments from the comments array for the for side */
    for ($i = 0; $i < sizeof($comments); $i++) {
      $comment = $comments[$i];
      $authorUid = $comment['author'];
      $authorName = $comment['name'];
      if ($comment['foragainst'] == 1) {
        commentInfo($comment, $authorUid, $authorName);
        voteTally($comment['upvotes'], $comment['downvotes']);
        /* only show the upvote/downvote if comment was NOT posted by me & 
           I have not already upvoted or downvoted this comment */
        deleteSupportVote($comment, $user);
        echo '
        </div>';
      }
    }
  ?>
  </div>
  </div>
  <div id="no">
  <?php
    if ($is_participant or !$privacy):
  ?>
  <textarea class="no" id="comment-no" placeholder="I disagree..." rows=2></textarea>
  <button id="post-no" class="btn btn-danger comment-post-btn" disabled>Post</button>
  <?php
    endif;
  ?>
  <div id="comments">
  <?php
    for ($i = 0; $i < sizeof($comments); $i++) {
      $comment = $comments[$i];
      $authorUid = $comment['author'];
      $authorName = $comment['name'];
      if ($comment['foragainst'] == 0) {
        commentInfo($comment, $authorUid, $authorName);
        voteTally($comment['upvotes'], $comment['downvotes']);
        /* only show the upvote/downvote if comment was NOT posted by me & 
           I have not already upvoted or downvoted this comment */
        deleteSupportVote($comment, $user);
        echo '
        </div>';
      }
    }
  ?>
  </div>
  </div>
  <div style="clear: both;"></div>
</div>
<div id="mask"></div>
<div id="overlay" class="window"></div>
</body>
</html>
