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
  "appId"   => '267545116676306',
  "secret"  => '5e33d3900a4253af9159a512ca49b6d1'
));

$user = $facebook->getUser();
// we relax the condition of sending the user to login page if not logged in
// if the user is not logged in, we dont show the follow/comment boxes and
// instead just show the SignUp button
$signed_in = false;
if ($user) {
  try {
    $profile = $facebook->api('/me', 'GET');
    $signed_in = true;
  } catch (FacebookApiException $e) {
    //header('Location: index.php');
  }
} else {
  //header('Location: index.php');
}
// is the current user $user a participant? this is true if we have just 
// created this debate, else we will figure this out from the db
$is_participant = false;
// check the incoming POST variable to see if debate ID exists. 
// it must always exist, and if not, we dont know what to do!
if (!array_key_exists('debid', $_GET)) { //get the details and add to db
  header('Location: home.php');
  $next_url = 'home.php';
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
  $debatethemes = $debate['themes'];
  $timelimit = $debate['timelimit'];
  $startdate = $debate['startdate'];
  $debscore = $debate['debscore'];
  $privacy = $debate['privacy'];
  $next_url = 'debate.php?debid='.$debid;
}
$params = array('scope' => 'publish_stream,publish_actions',
	'next' => 'http://localhost/iitdebates/'.$next_url);
$params_logout = array('next' => 'http://localhost/iitdebates/logout.php');
$query = "SELECT * FROM `users` WHERE fbid='$userid'";
$creator = fetchAssoc($query);
$comments = commentsArray($debid);
if ($following) {
  $followingClass = 'btn-danger disabled';
  $followingText = 'Following';
}
else {
  $followingClass = 'btn-primary';
  $followingText = 'Follow';
}
if ($signed_in)
  $myname = $profile['name'];
else
  $myname = 'Anonymous';
/* echo back important js state variables */
$creatorname = $creator['name'];
?>
<!DOCTYPE html>
<html>
<head>
<?php
// user is my facebook ID
// userid is the facebook ID of the creator of this debate
// debid is the debate's ID
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
<title><?php echo $debatetopic;?></title>
</head>
<body>
<div id="header">
  <span class="logo"><a href="home.php">IIT Debates</a></span>
  <span class="fb-ju-ab">
    <ul>
      <li><a href="fb-ju-ab.php#feedback" id="fb">Feedback</a></li>
      <li><a href="fb-ju-ab.php#join-us" id="ju">Join Us</a></li>
      <li><a href="fb-ju-ab.php#about" id="ab">About</a></li>
    </ul>
  </span>
  <span class="options">
    <ul>
    <?php if ($signed_in):?>
      <li class="search-form">
      <input class="navbar-search" type="text" id="friend-search" data-provide="typeahead" placeholder="Search" autocomplete="off">
      <div class="icon-search icon-black"></div>
      </li>
      <li class="log-out-link"><a href="home.php">Home</a></li>
      <li class="log-out-link"><a href="<?php echo $facebook->getLogoutUrl($params_logout);?>">Log Out</a></li>
    <?php endif;?>
    </ul>
  </span>
</div>
<div id="profile">
  <div id="debate-details">
    <div class="topic">
      <?php echo $debatetopic;?>
    </div>
    <div class="desc">
      <p id="desc-data">
      <?php 
        echo $debatedesc;
      ?>
      </p>
      <!--<span id="edit-desc" title="Edit Debate Description" class="add">+</span>-->
    </div>
    <div class="deb-themes">
      <?php
        $themes = explode(',', $debatethemes);
        foreach ($themes as $theme) {
          echo '<span class="theme" title="Debate Themes">'.trim($theme).'</span>';
        }
      ;?>
    </div>
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
      if (!$is_participant and $signed_in) {
    ?>
    <a title="Follow Debate" id="follow-debate" class="btn <?php echo $followingClass;?> engage-btn"><?php echo $followingText;?></a><br/>
    <?php
      } else if (!$signed_in) {
    ?>
    <a href="<?php echo $facebook->getLoginUrl($params);?>" class="btn btn-primary engage-btn">Sign in</a><br>
    <?php
      } else {
    ?>
    <a title="Invite friends to this debate" id="invite-to-debate" class="btn btn-primary engage-btn">Invite Friends</a><br/>
    <?php
      }
    ?>
    <a title="See all participants" id="view-participants" class="btn engage-btn">Participants</a><br/>
    <a title="See all followers" id="view-followers" class="btn engage-btn">Followers</a>
  </div>
</div>
<div id="content">
  <div id="yes" class="leftcol">
  <?php
  // show these boxes only if I am a participant or * this is a public debate *
  if (($is_participant or !$privacy) and ($signed_in)):
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
        echo '</div>';
      }
    }
  ?>
  </div>
  </div>
  <div id="no" class="rightcol">
  <?php
    if (($is_participant or !$privacy) and ($signed_in)):
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
        echo '</div>';
      }
    }
  ?>
  </div>
  </div>
  <div style="clear: both;"></div>
</div>
  <!-- Generic Overlay box for which we set the code and call the modal -->
  <div id="overlay" class="modal hide fade">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">x</button>
      <h1></h1>
    </div>
    <div class="modal-body">
    </div>
  </div>
  <script src="includes/jquery-1.7.2.min.js"></script>
  <script src="includes/js/jquery-ui-min.js"></script>
  <script src="includes/js/jquery.autosize-min.js"></script>
  <script src="includes/jquery.easing.1.3.js"></script>
  <script src="includes/bootstrap/js/bootstrap.min.js"></script>
  <script src="includes/js/tag-it.js"></script>
  <script src="includes/js/marked.js"></script>
  <script src="includes/common.js"></script>
  <script src="includes/debate-script.js"></script>
</body>
</html>
