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
$params = array('next' => 'http://localhost/iitdebates/logout.php');
$paramsLogIn = array('scope' => 'publish_stream,publish_actions',
                     'next'  => 'http://localhost/iitdebates/home.php');
$user = $facebook->getUser();
$signed_in = false;
/* are we trying to access someone else's profile? if so, uid is set, so we will
   query our db for uid, get the details and render it from there. else, we will
   render from the userprofile/fb-details obtained above */
if (array_key_exists('uid', $_GET))
  $uid = $_GET['uid'];
else
  $uid = 0; // compare $uid and $user to check if we render our own page or not
/* $user is the fbid which we get if the user is logged in. once the user
   accepts to add iitdebates, we also assign him a $userid which is our id */
/* send the user to login page if he is not correctly logged in */
if ($user and $uid == 0) {
  try {
    /* on reaching here, the user is logged in. but we also need to make sure
       that his/her entry is in our users table */
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
    }
    $query = "SELECT * FROM `users` WHERE `fbid`=$user";
    $result = mysql_query($query);
    $userprofile = mysql_fetch_assoc($result);
    $userid = $userprofile['uid'];
    $signed_in = true;
    if ($userid == $uid and $uid)
      header('Location: home.php');
  }
  catch (FacebookApiException $e) {
    header('Location: index.php');
  }
}
else if ($uid != 0) {
  $userid = $uid;
  $query = "SELECT * FROM `users` WHERE `fbid`=$user";
  $userprofile = fetchAssoc($query);
  $userid = $userprofile['uid'];
}
else {
  header('Location: index.php');
}
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
    <link rel="icon" href="includes/favicon.ico"/>
    <?php
    // uid is the local id of the user whom i am visiting
    // user is the facebook id of me
    // userid is the local id of me
      echo "<script>
        var uid = '$uid';
        var user = '$user';
        var userid = '$userid';
        var followerIds = '$followerIds';
        var followerNames = '$followerNames';
        var followeeIds = '$followeeIds';
        var followeeNames = '$followeeNames';
        </script>
      ";
    ?>
  </head>
  <body>
    <!--Site Header-->
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
          <li class="log-out-link" id="log-out-btn">
            <a href="<?php echo $facebook->getLogoutUrl($params);?>">
              Log Out
            </a>
          </li>
        <?php else:?>
          <li class="log-out-link">
            <a href="<?php echo $facebook->getLoginUrl($paramsLogIn);?>">Sign Up</a>
          </li>
        <?php endif;?>
        </ul>
      </span>
    </div>
    <!--The user profile is displayed here. Pic, Score, ...-->
    <div id="profile">
      <a href="<?php echo 'https://facebook.com/profile.php?id='.$userprofile['fbid'];?>" target="_blank"><img class="pic" src="<?php echo 'https://graph.facebook.com/'.$fbid.'/picture?type=normal';?>"/></a>
      <table class="name-table">
        <thead>
          <tr><td class="name"><?php echo $name;?></td></tr>
        </thead>
      </table>
      <table class="details">
        <tbody>
          <tr>
            <td class="contain-interest"><span id="interested-in" class="interest">interested in:</span></td>
            <td class="interest-elements"><?php if ($userprofile != null) echo $userprofile['interests'];?></td>
            <?php
             if (!$uid): 
            ?>
            <td class="interest-elements-p"><span class="add">+</span></td>
            <?php
              endif;
            ?>
          </tr>
          <tr>
            <td><span id="debating-points" class="interest">debating points:</span></td>
            <td class="debate-score"><?php if ($userprofile != null) echo $userprofile['score'];?></td>
          </tr>
          <!--<tr><td><span id="debates-won" class="interest">debates won:</span></td><td><?php if ($userprofile != null) echo $userprofile['debateswon'];?></td></tr>-->
        </tbody>
      </table>
      <div class="engage">
        <?php
          if ($signed_in and $uid == 0):
        ?>
        <a title="Start a new debate" id="start" class="btn btn-primary usr-engage-btn">Start a new debate</a><br/>
        <a title="View my followers" id="my-followers" class="btn usr-engage-btn">My Followers</a><br/>
        <a title="View my followees" id="my-followees" class="btn usr-engage-btn">My Followees</a>
        <?php
          elseif ($signed_in): // have the user interact with other's profiles
        ?>
        <!--<button title="Invite to my debates" id="invite" class="btn btn-primary usr-engage-btn2">Invite</button><br/>-->
        <a title="Follow this user's activity" id="follow" class="<?php echo $follower;?>"><?php echo $follower_text;?></a><br/>
        <a title="Challenge to a debate" id="challenge" class="btn usr-engage-btn2">Challenge</a>
        <?php
          endif;
        ?>
      </div>
    </div>

  <div id="content">
  <!--The main canvas to show all activity for the user in the form of updates.
      These are either invites from friends for debates, updates in debates
      I follow. (I automatically follow the debates I start or I participate in)-->
      
    <div id="my-debates" class="leftcol">
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
      echo '<thead></thead><tbody><tr id="nill"><td>'.$you.'have any ongoing debates right now</td></tr>';
    }
    while ($row = mysql_fetch_array($result)) {
      $debid = $row['debid'];
      echo '<tr>'.
           '<td class="dname" id="'.$debid.'"><a href="debate.php?debid='.$debid.
           '">'.$row['topic'].'</a></td>'.
           '<td>'.$row['debscore'].'</td>'.
           '<td>'.$row['rating'].'</td>'.
           '<td>';
      $days = (strtotime(date("Y-m-d")) - strtotime($row['startdate'])) / (60 * 60 * 24);
      $daylimit = $row['timelimit'];
      if ($daylimit - $days > 0)
        echo ($daylimit - $days).' days';
      else
        echo 'Closed';
      echo '</td>';
      if (!$uid) {
        echo '<td style="padding:8px 4px 8px 0;"><a href="#" class="close delete-debate">&times;</a></td>';
      }
      echo '</tr>';
    }
    ?>
    </tbody>
    </table>
    </div>
    <div id="my-updates" class="rightcol">
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
    <?php
    else:
      echo '<div id="update">';
      echo "You don't have permission to view ".$userprofile['name']."'s updates.";
      echo '</div>';
    endif;
    ?>
    </div>
    <div class="clear"></div>
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
    <div id="start-debate-form" class="modal hide fade well">
      <div class="modal-header">
        <button type="button" id="cancel-debate" class="close" data-dismiss="modal">x</button>
        <h1>Start a new debate</h1>
      </div>
      <div class="modal-body">
        <form action="debate-create.php" method="POST">
          <input type="text" title="Debate's Topic" name="debate-topic" id="debate-topic" class="input-xxlarge" placeholder="Debate Topic" autocomplete="off"/>
          <textarea class="input-xxlarge" title="Debate's Description" name="debate-desc" id="debate-desc" placeholder="Debate Description" rows="4" autocomplete="off"></textarea>
          <input type="text" name="debate-theme" title="Debate's Themes" id="debate-theme" class="input-xxlarge" placeholder="Debate Themes" autocomplete="off" spellcheck="false"/>
          <input type="text" name="participants" title="Debate's Participants" id="participants" class="input-xxlarge ui-autocomplete-input" placeholder="Challenge Friends" autocomplete="off" spellcheck="false"/>
          <input type="hidden" name="participant-ids" title="Debate's Participants" id="participant-ids"/>
          <div id="radio" title="Enter the debate time limit">
		        <input type="radio" id="time-limit-1" name="time-limit" value="10"  checked="checked" /><label for="time-limit-1">10 days</label>
		        <input type="radio" id="time-limit-2" name="time-limit" value="20" /><label for="time-limit-2">20 days</label>
		        <input type="radio" id="time-limit-3" name="time-limit" value="30" /><label for="time-limit-3">30 days</label>
		        <input type="radio" id="time-limit-4" name="time-limit" value="40" /><label for="time-limit-4">40 days</label>
	        </div>
	        <div id="radio2" title="Set the debate privacy">
	          <input type="radio" id="privacy-1" name="privacy" value="0"  checked="checked" /><label for="privacy-1">Public Debate</label>
		        <input type="radio" id="privacy-2" name="privacy" value="1" /><label for="privacy-2">Private Debate</label>
	        </div>
	        <a class="btn btn-inverse active" title="Post to Facebook" id="post-to-fb" data-toggle="button">Posting to Facebook</a>
	        <input type="hidden" name="post-to-fb-input" id="post-to-fb-input"/>
          <button class="btn btn-primary" id="start-debate" disabled>Start</button>
        </form>
      </div>
    </div>
    <script src="includes/jquery-1.7.2.min.js"></script>
    <script src="includes/bootstrap/js/bootstrap.min.js"></script>
    <script src="includes/js/jquery-ui-min.js"></script>
    <script src="includes/js/tag-it.js"></script>
    <script src="includes/common.js"></script>
    <script src="includes/script.js"></script>
  </body>
</html>
