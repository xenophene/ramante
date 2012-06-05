<?php
/* remove the comment requested by the user */
include('includes/config.php');
$comid = $_POST['comid'];
$query = "DELETE FROM `comments` WHERE `comid`='$comid'";
$result = mysql_query($query);
echo $query;
?>
