<?php
/* Simply query back all the users of the database */
include('includes/config.php');
$query = "SELECT `uid`, `name` FROM `users`";
$result = mysql_query($query);
$rows = array();
while ($row = mysql_fetch_assoc($result))
  array_push($rows, $row);
echo json_encode($rows);
?>
