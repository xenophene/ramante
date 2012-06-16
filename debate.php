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
require_once('includes/facebook.php');
$facebook = new Facebook(array(
"appId"   => '253395578066052',
"secret"  => '23d20951b5546544b2f2e31183e4b5c0',
"cookie"  => false
));
$params = array();
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
  if (!array_key_exists('debate-topic', $_POST))
    header('Location: home.php');
  $debatetopic = mysql_real_escape_string($_POST['debate-topic']);
  $debatedesc = mysql_real_escape_string($_POST['debate-desc']);
  $debatetheme = mysql_real_escape_string($_POST['debate-theme']);
  $participants = mysql_real_escape_string($_POST['participant-ids'].$user);
  $followers = '';
  $follower_names = '';
  $participant_names = mysql_real_escape_string($_POST['participants'].$profile['name']);
  $timelimit = mysql_real_escape_string($_POST['time-limit']);
  $debscore = 0;
  $is_participant = true;
  /* check if such an entry exists, else make an entry to the db */
  $query = "SELECT * FROM `debates` WHERE `topic`='$debatetopic' AND ".
           "`creator`='$user'";
  $result = mysql_query($query);
  if (mysql_num_rows($result) == 0) { //create this debate entry
    $query =  "INSERT INTO `debates` (`topic`, `description`, `timelimit`, `themes`,".
              "`participants`, `creator`, `startdate`) VALUES ".
              "('$debatetopic', '$debatedesc', '$timelimit', '$debatetheme', ".
               "'$participants', '$user', '".date('Y,m,d')."')";
    $result = mysql_query($query);
  }
  $query = "SELECT MAX(debid) FROM `debates`";
  $result = mysql_query($query);
  $debid = mysql_fetch_row($result);
  $debid = $debid[0];
  $userid = $user;
  $startdate = date('Y,m,d');
}
else { // get the details & render the page
  $debid = $_GET['debid'];
  $query = "SELECT * FROM `debates` WHERE `debid`='$debid'";
  $result = mysql_query($query);
  $debate = mysql_fetch_assoc($result);
  $userid = $user;
  // get all the comma separated participant names
  // check if the current user $userid is a participant
  $participants = $debate['participants'];
  $ps = explode(',',$participants);
  $participant_names = '';
  foreach($ps as $p) {
    $is_participant = trim($p) == $user;
    $result = mysql_query("SELECT * FROM `users` WHERE `fbid`='$p'");
    $row = mysql_fetch_assoc($result);
    if ($participant_names == '')
      $participant_names = $row['name'];
    else
      $participant_names = $participant_names . ',' . $row['name'];
  }
  $followers = $debate['followers'];
  $fs = explode(',',$followers);
  $follower_names = '';
  foreach($fs as $f) {
    $p = trim($f);
    if ($p == '')
      continue;
    $result = mysql_query("SELECT * FROM `users` WHERE `fbid`='$p'");
    $row = mysql_fetch_assoc($result);
    if ($follower_names == '')
      $follower_names = $row['name'];
    else
      $follower_names = $follower_names . ',' . $row['name'];
  }
  $debatetopic = $debate['topic'];
  $debatedesc = $debate['description'];
  $timelimit = $debate['timelimit'];
  $startdate = $debate['startdate'];
  $debscore = $debate['debscore'];
  // for now allow this special debate to be editable by all
  $is_participant = $is_participant or $debid == 160; 
}
$query = "SELECT * FROM `users` WHERE fbid='$userid'";
$result = mysql_query($query);
$creator = mysql_fetch_assoc($result);
$follower_qty = 0;
$query = "SELECT * FROM `comments`, `users` WHERE debid='$debid' AND `author`=`fbid`";
$result = mysql_query($query);
$comments = array();
for ($i = 0; $i < mysql_num_rows($result); $i++) {
  array_push($comments, mysql_fetch_assoc($result));
}
/* echo back important js state variables */
$creatorname = $creator['name'];
?>
<!DOCTYPE html>
<html>

<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# iit_debates: http://ogp.me/ns/fb/iit_debates#">
 <meta property="fb:app_id" content="253395578066052" /> 
 <meta property="og:type"   content="iit_debates:debate" /> 
 <meta property="og:url"    content="http://localhost/iitdebates/debate.php" /> 
 <meta property="og:title"  content="Sample Debate" /> 
 <meta property="og:image"  content="https://s-static.ak.fbcdn.net/images/devsite/attachment_blank.png" />
    
<?php
echo "
  <script>
  var userid = '$userid';
  var debid = '$debid';
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
<span class="options">
  <ul>
  <a href="home.php"><li>Home</li></a>
  <a href="<?php echo $facebook->getLogoutUrl($params);?>"><li>Log Out</li></a>
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
<button title="Follow Debate" id="follow-debate" class="btn btn-primary engage-btn">Follow</button><br/>
<?php
  }
  else {
?>
<button title="Invite friends to this debate" id="invite-to-debate" class="btn btn-primary engage-btn">Invite Friends</button><br/>
<?php
  }
?>
<button title="See all participants" id="view-participants" class="btn btn-primary engage-btn">Participants</button><br/>
<button title="See all followers" id="view-followers" class="btn btn-primary engage-btn">Followers</button>
</div>
</div>
<div id="content">
  <div id="yes">
  <?php
  // show these boxes only if I am a participant
  if ($is_participant):
  ?>
  <textarea class="yes" id="comment-yes" placeholder="Speak for the motion" rows=2></textarea>
  <button id="post-yes" class="btn btn-success comment-post-btn" disabled>Post</button>
  <?php
    endif;
  ?>
  <div id="comments">
  <?php
    /* echo the comments from the comments array for the for side */
    for ($i = 0; $i < sizeof($comments); $i++) {
      $comment = $comments[$i];
      if ($comment['foragainst'] == 1) {
        echo '
        <div id="comment" name="'.$comment['comid'].'">
        <span class="author"><img class="author-pic" src="https://graph.facebook.com/'.$userid.'/picture?type=small"/>'.$creator['name'].'</span>
        <br/>
        <span class="comment-data">'.$comment['value'].'</span>
        <br/>';
        $upvotes = sizeof(explode(',', $comment['upvotes']));
        $downvotes = sizeof(explode(',', $comment['downvotes']));
        echo '
        <span class="votes">'.($upvotes - $downvotes).' votes</span>';
        /* only show the upvote/downvote if comment was NOT posted by me & 
           I have not already upvoted or downvoted this comment */
        $dontShow = false;
        if ($comment['author'] == $userid) {
          $dontShow = true;
          echo '
          <span class="delete-point votes" title="Delete this point">Delete</span>';
        }
        foreach(explode(',', $comment['upvotes']) as $upvoter) {
          if ($userid == $upvoter)
            $dontShow = true;
        }
        foreach(explode(',', $comment['downvotes']) as $downvoter) {
          if ($userid == $downvoter)
            $dontShow = true;
        }
        if (!$dontShow) {
          echo '
          <span class="support-point votes" title="Support this point">Support</span>
          <span class="rebutt-point votes" title="Rebutt this point">Rebutt</span>
          <span class="upvote icon-arrow-up" title="Vote Up"></span>
          <span class="downvote icon-arrow-down" title="Vote Down"></span>';
        }
        echo '
        </div>';
      }
    }
  ?>
  </div>
  </div>
  <div id="no">
  <?php
    if ($is_participant):
  ?>
  <textarea class="no" id="comment-no" placeholder="Speak against the motion" rows=2></textarea>
  <button id="post-no" class="btn btn-danger comment-post-btn" disabled>Post</button>
  <?php
    endif;
  ?>
  <div id="comments">
  <?php
    for ($i = 0; $i < sizeof($comments); $i++) {
      $comment = $comments[$i];
      if ($comment['foragainst'] == 0) {
        echo '
        <div id="comment" name="'.$comment['comid'].'">
        <span class="author"><img class="author-pic" src="https://graph.facebook.com/'.$userid.'/picture?type=small"/>'.$creator['name'].'</span>
        <br/>
        <span class="comment-data">'.$comment['value'].'</span>
        <br/>';
        $upvotes = sizeof(explode(',', $comment['upvotes']));
        $downvotes = sizeof(explode(',', $comment['downvotes']));
        echo '
        <span class="votes">'.($upvotes - $downvotes).' votes</span>';
        /* only show the upvote/downvote if comment was NOT posted by me & 
           I have not already upvoted or downvoted this comment */
        $dontShow = false;
        if ($comment['author'] == $userid) {
          $dontShow = true;
          echo '
          <span class="delete-point votes" title="Delete this point">Delete</span>';
        }
        foreach(explode(',', $comment['upvotes']) as $upvoter) {
          if ($userid == $upvoter)
            $dontShow = true;
        }
        foreach(explode(',', $comment['downvotes']) as $downvoter) {
          if ($userid == $downvoter)
            $dontShow = true;
        }
        if (!$dontShow) {
          echo '
          <span class="support-point votes" title="Support this point">Support</span>
          <span class="rebutt-point votes" title="Rebutt this point">Rebutt</span>
          <span class="upvote icon-arrow-up" title="Vote Up"></span>
          <span class="downvote icon-arrow-down" title="Vote Down"></span>';
        }
        echo '
        </div>';
      }
    }
  ?>
  </div>
  </div>
</div>
<div id="mask"></div>
<div id="overlay" class="window">
</div>
</body>
</html>
