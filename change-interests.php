<?php
/* change interests of a user in question */
include('includes/config.php');
$uid = $_POST['uid'];
$interest = $_POST['interests'];
$query = "UPDATE `users` SET `interests`='$interest' WHERE `uid`='$uid'";
$result = mysql_query($query);
?>
