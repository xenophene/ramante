<?php
/**
  * post-vote listens to the requests from client to upvote/downvote a particular
  * comment on some debate uniquely determined by the comid of the comment
  * we simply append the userid of the user which has just upvoted/downvoted
  */
include('includes/config.php');
$comid = mysql_real_escape_string($_POST['comid']);
$userid = mysql_real_escape_string($_POST['userid']);
$upvote = mysql_real_escape_string($_POST['upvote']);
$query = "SELECT * FROM `comments` WHERE `comid`='$comid'";
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);
if ($upvote == 1) {
  $upvotes = $row['upvotes'];
  if ($upvotes == '')
    $upvotes = $userid;
  else
    $upvotes = $upvotes . ",$userid";
  $query = "UPDATE `comments` SET `upvotes`='$upvotes' WHERE `comid`='$comid'";
}
else {
  $downvotes = $row['downvotes'];
  if ($downvotes == '')
    $downvotes = $userid;
  else
    $downvotes = $downvotes . ",$userid";
  $query = "UPDATE `comments` SET `downvotes`='$downvotes' WHERE `comid`='$comid'";
}
$result = mysql_query($query);
?>
