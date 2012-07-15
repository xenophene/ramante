<?php
/* debate-create gets the create debate request from home.php and creates
   the database entries. it then redirects with the debid to debate.php */
require_once('includes/facebook.php');
include('includes/config.php');
include('includes/aux_functions.php');
$facebook = new Facebook(array(
  "appId"   => '253395578066052',
  "secret"  => '23d20951b5546544b2f2e31183e4b5c0',
  "cookie"  => false
));
$user = $facebook->getUser();
$profile = $facebook->api('/me');
/* send the user to login page if he is not correctly logged in */
$debatetopic = mysql_real_escape_string($_POST['debate-topic']);
$debatedesc = mysql_real_escape_string($_POST['debate-desc']);
$debatetheme = mysql_real_escape_string($_POST['debate-theme']);
$participants = mysql_real_escape_string($_POST['participant-ids'].$user);
$followers = '';
$follower_names = '';
/* for each of these participants, if there are some not in the db, we add them */
$participant_names = mysql_real_escape_string($_POST['participants'].$profile['name']);
addUsers($participants, $participant_names);
$timelimit = mysql_real_escape_string($_POST['time-limit']);
$privacy = mysql_real_escape_string($_POST['privacy']);
$debscore = 0;
$is_participant = true;
/* check if such an entry exists, else make an entry to the db */
$query = "SELECT * FROM `debates` WHERE `topic`='$debatetopic' AND ".
         "`creator`='$user'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) { //create this debate entry
  $query =  "INSERT INTO `debates` (`topic`, `description`, `timelimit`, `themes`,".
            "`participants`, `creator`, `startdate`, `privacy`) VALUES ".
            "('$debatetopic', '$debatedesc', '$timelimit', '$debatetheme', ".
             "'$participants', '$user', '".date('Y,m,d')."', '$privacy')";
  $result = mysql_query($query);
}
$query = "SELECT MAX(debid) FROM `debates`";
$result = mysql_query($query);
$debid = mysql_fetch_row($result);
$debid = $debid[0];
$userid = $user;
/*Post message to wall of all the participants*/
$ret = postUsers($participants, $participant_names,$debatetopic,$debatedesc,'http://ramante.in/iitdebates/debate.php?debid='.$debid);

header('Location: debate.php?debid='.$debid);
?>
