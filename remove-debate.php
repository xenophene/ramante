<?php
/* remove-debate will remove myself as the participant from this debate
   if i am the creator, i still remain as one */
include('includes/config.php');
include('includes/aux_functions.php');
$debid = $_POST['debid'];
$user = $_POST['user'];
$row = fetchAssoc("SELECT * FROM `debates` WHERE `debid`='$debid'");
$participants = str_replace($user.',', '', $row['participants']);
$participants = str_replace($user, '', $participants);
mysql_query("UPDATE `debates` SET `participants`='$participants' ".
             "WHERE `debid`='$debid'");
?>
