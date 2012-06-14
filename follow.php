<?php
/* accept a follow request for a user */
include('includes/config.php');
$followee = $_POST['followee']; // the user who is to be followed
$follower = $_POST['follower']; // the user who wants to follow uid
$follow = $_POST['follow']; // whether to follow or unfollow

if ($follow) {
  $query = "INSERT INTO `iitdebates`.`follower` (`uid`, `follower`) VALUES ".
           "('$followee', '$follower')";
  $result = mysql_query($query);
} else {
  $query = "DELETE FROM `follower` WHERE ".
           "`uid`='$followee' AND `follower`='$follower'";
  $result = mysql_query($query);
}
?>
