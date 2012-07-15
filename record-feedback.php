<?php
  include('includes/config.php');
  $n = $_GET['cname'];
  $e = $_GET['email'];
  $c = $_GET['comment'];
  $query = "INSERT INTO `feedback` (`name`, `email`, `comment`) ".
           "VALUES ('$n', '$e', '$c')";
  mysql_query($query);
?>
