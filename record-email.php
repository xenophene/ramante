<?php
  include('includes/config.php');
  $e = $_GET['email'];
  $d = $_GET['desc'];
  $query = "INSERT INTO `jobemails` (`email`, `desc`) ".
           "VALUES ('$e', '$d')";
  mysql_query($query);
?>
