<?php
/* debate-create gets the create debate request from home.php and creates
   the database entries. it then redirects with the debid to debate.php */
require_once('includes/facebook.php');
include('includes/config.php');
include('includes/aux_functions.php');
$facebook = new Facebook(array(
  "appId"   => '267545116676306',
  "secret"  => '5e33d3900a4253af9159a512ca49b6d1'
));
$user = $facebook->getUser();
$profile = $facebook->api('/me');
/* send the user to login page if he is not correctly logged in */
$debatetopic = mysql_real_escape_string(stripslashes($_POST['debate-topic']));
$debatedesc = mysql_real_escape_string(stripslashes($_POST['debate-desc']));
$debatetheme = mysql_real_escape_string(stripslashes($_POST['debate-theme']));
$participants = mysql_real_escape_string(stripslashes($_POST['participant-ids'].$user));
$followers = '';
$follower_names = '';
/* for each of these participants, if there are some not in the db, we add them */
$participant_names = mysql_real_escape_string($_POST['participants'].$profile['name']);
addUsers($participants, $participant_names);
$timelimit = mysql_real_escape_string($_POST['time-limit']);
$privacy = mysql_real_escape_string($_POST['privacy']);
$debscore = 0;
$is_participant = true;
$post_to_fb = $_POST['post-to-fb-input'];
/* check if such an entry exists, else make an entry to the db */
$query = "SELECT * FROM `debates` WHERE `topic`='$debatetopic' AND ".
         "`creator`='$user'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) { //create this debate entry
  $query =  "INSERT INTO `debates` (`topic`, `description`, `timelimit`, `themes`,".
            "`participants`, `followers`, `creator`, `startdate`, `privacy`) VALUES ".
            "('$debatetopic', '$debatedesc', '$timelimit', '$debatetheme', ".
             "'$participants', '$participants', '$user', '".date('Y,m,d')."', '$privacy')";
  $result = mysql_query($query);
}
$query = "SELECT MAX(debid) FROM `debates`";
$result = mysql_query($query);
$debid = mysql_fetch_row($result);
$debid = $debid[0];
$userid = $user;
/*Post message to wall of all the participants*/
if ($post_to_fb == 'Posting to Facebook')
  $ret = postUsers($participants, $participant_names, $debatetopic, $debatedesc,
                   'http://ramante.in/iitdebates/debate.php?debid='.$debid);
header('Location: debate.php?debid='.$debid);
?>
