<?php
include('includes/config.php');
include('includes/aux_functions.php');
/* post-yes will accept the add yes comment request to an ongoing debate
   and add it to the db. the data is received as a POST variable */
$author = mysql_real_escape_string($_POST['author']);
$value = mysql_real_escape_string(stripslashes($_POST['value']));
$debid = mysql_real_escape_string($_POST['debid']);
$foragainst = $_POST['foragainst'];
$parentComId = $_POST['parentId'];
$query = "INSERT INTO `comments` (`author`, `value`, `debid`, `foragainst`, `parentid`)".
         " VALUES ('$author', '$value', '$debid', '$foragainst', '$parentComId')";
$result = mysql_query($query);
$query = "SELECT MAX(comid) FROM `comments`";
$result = mysql_fetch_assoc(mysql_query($query));
/* author will be fbid of the user. if he is not a part of the participants of
   the debate, we will add him. */
$row = fetchAssoc("SELECT * FROM `debates` WHERE `debid`='$debid'");
if (strpos($row['participants'], $author) === false) {
  echo $row['participants'];
  $participants = $row['participants'] . $author;
  echo $participants;
  mysql_query("UPDATE `debates` SET `participants`='$participants' ".
              "WHERE `debid`='$debid'");
}
echo $result['MAX(comid)'];
?>
