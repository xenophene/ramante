<?php
include('includes/config.php');
/* post-yes will accept the add yes comment request to an ongoing debate
   and add it to the db. the data is received as a POST variable */
$author = mysql_real_escape_string($_POST['author']);
$value = mysql_real_escape_string($_POST['value']);
$debid = mysql_real_escape_string($_POST['debid']);
$foragainst = $_POST['foragainst'];
$query = "INSERT INTO `comments` (`author`, `value`, `debid`, `foragainst`)".
         " VALUES ('$author', '$value', '$debid', '$foragainst')";
$result = mysql_query($query);
$query = "SELECT MAX(comid) FROM `comments`";
$result = mysql_fetch_assoc(mysql_query($query));
echo $result['MAX(comid)'];
?>
