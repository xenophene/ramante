<?php
/* follow-debate function */
include('includes/config.php');
include('includes/aux_functions.php');
$debid = $_POST['debid'];
$follower = $_POST['follower'];
$row = fetchAssoc("SELECT * FROM `debates` WHERE `debid`='$debid'");
if ($row['followers'] == '')
  $followers = $follower;
else {
  $followers = $row['followers'].','.$follower;
}
mysql_query("UPDATE `debates` SET `followers`='$followers'".
            " WHERE `debid`='$debid'");
?>
