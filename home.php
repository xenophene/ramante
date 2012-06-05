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
    $name = $profile['name'];
    /* on reaching here, the user is logged in. but we also need to make sure
       that his/her entry is in our users table */
    $query = "SELECT * FROM `users` WHERE `fbid`=$user";
    $result = mysql_query($query);
    if (mysql_num_rows($result) == 0) { // add an entry
      $query = "INSERT INTO `users` (`fbid`, `name`) VALUES ".
               "('$user', '$name')";
      mysql_query($query);
    }
  }
  catch (FacebookApiException $e) {
    //header('Location: index.php');
  }
}
else {
  //header('Location: index.php');
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
<script src="includes/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="includes/js/jquery-ui-min.js" type="text/javascript"></script>
<script src="includes/js/tag-it.js" type="text/javascript"></script>
<script src="includes/script.js" type="text/javascript"></script>
<link rel="stylesheet" href="includes/bootstrap/css/bootstrap.min.css"/>
<link rel="stylesheet" href="includes/css/jquery-ui.css"/>
<link rel="stylesheet" href="includes/style.css"/>
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
<a href="<?php echo 'https://facebook.com/'.$profile['username'];?>" target="_blank"><img class="pic" src="<?php echo 'https://graph.facebook.com/'.$user.'/picture?type=normal';?>"/></a>
<table class="details">
<tbody>
<tr><td class="name">Ravee<?php //echo $profile['name'];?></td></tr>
<tr><td><span class="interest">interested in:</span></td><td class="interest-elements"><?php echo 'Politics, Hostel Mess';?></td>
<?php
  if ($uid == 0 or $user == $uid) {
    echo '<td><span class="add"><a href="#" title="Add/Remove interests">+</a></span></td>';
  }
?>
</tr>
<tr><td><span class="interest">debating points:</span></td><td><?php echo 'debating points';?></td></tr>
<tr><td><span class="interest">debates won:</span></td><td><?php echo 'accepted challenges';?></td></tr>
</tbody>
</table>
<ul class="engage">
<?php
  if ($uid == 0 or $user == $uid) {
?>
<li title="start" id="start" class="btn btn-primary">Start a new debate</li>
<?php
  }
  else { // have the user interact with other's profiles
?>
<li title="Invite to one of my active debates" id="invite" class="btn btn-primary">Invite</li>
<li title="Follow this user's debates" id="follow" class="btn btn-primary">Follow</li>
<li title="Challenge to a debate" id="challenge" class="btn btn-primary">Challenge</li>
<?php
  }
?>
</ul>
</div>
<div id="content">
<!--The main canvas to show all activity for the user in the form of updates.
    These are either invites from friends for debates, updates in debates
    I follow. (I automatically follow the debates I start or I participate in)-->
<div id="my-debates">
<span class="home-heading">My Debates</span>
<table class="debate-table">
<thead>
<tr><td>Debate Name</td><td>Debate Points</td><td>My Votes</td></tr>
</thead>
<tbody>
<tr>
<td>Should IITs be govt controlled?</td><td>3 points</td><td>44</td>
</tr>
</tbody>
</table>
</div>
<div id="my-updates">
<span class="home-heading">Updates</span>
  <div id="update">
  Hi, this is an update
  </div>
  <div id="update">
  This is the 2nd update
  </div>
</div>
</div>
<div id="mask"></div>
<div id="start-debate-form" class="window">
  <form class="well" action="debate.php" method="POST">
    <p class="emph">Start a new debate</p>
    <input type="text" title="Debate's Topic" name="debate-topic" id="debate-topic" class="input-xxlarge" placeholder="Debate Topic" autocomplete="off"/>
    <textarea class="input-xxlarge" title="Debate's Description" name="debate-desc" id="debate-desc" placeholder="Debate Description" rows="4" autocomplete="off"></textarea>
    <input type="text" name="debate-theme" title="Debate's Themes" id="debate-theme" class="input-xxlarge" placeholder="Debate Themes" autocomplete="off"/>
    <input type="text" name="participants" title="Debate's Participants" id="participants" class="input-xxlarge ui-autocomplete-input" placeholder="Challenge Friends" autocomplete="off" spellcheck="false"/><br/>
    <input type="text" name="participant-ids" title="Debate's Participants" id="participant-ids" class="input-xxlarge ui-autocomplete-input" autocomplete="off" spellcheck="false"/>
    <div id="radio">
		  <input type="radio" id="time-limit-1" name="time-limit"  checked="checked" /><label for="time-limit-1">10 days</label>
		  <input type="radio" id="time-limit-2" name="time-limit" /><label for="time-limit-2">20 days</label>
		  <input type="radio" id="time-limit-3" name="time-limit" /><label for="time-limit-3">30 days</label>
		  <input type="radio" id="time-limit-4" name="time-limit" /><label for="time-limit-4">40 days</label>
	  </div>
	  <br/>
    <a class="btn btn-primary" id="start-debate">Start</a>
    <a class="btn" id="cancel-debate">Cancel</a>
  </form>
</div>

</body>
</html>
