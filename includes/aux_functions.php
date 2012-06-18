<?php
/**
  * Auxiliary functions that will be required by all the classes will be
  * implemented here. We will also update the base structure to a Class soon
  */
include('includes/config.php');
/* Auxiliary SQL helper functions */
function fetchAssoc($query) {
  $result = mysql_query($query);
  $row = mysql_fetch_assoc($result);
  return $row;
}
/* query the User Names into an array from Ids */
function namesFromIds($ps) {
  $names = '';
  foreach($ps as $f) {
    $p = trim($f);
    if ($f == '')
      continue;
    $row = fetchAssoc("SELECT * FROM `users` WHERE `fbid`='$p'");
    if ($names == '')
      $names = $row['name'];
    else
      $names = $names . ',' . $row['name'];
  }
  return $names;
}
/* addUsers will take in the fbids and names of new users and add them to the db */
function addUsers($pids, $pnames) {
  $pidArray = explode(',', $pids);
  $pnameArray = explode(',', $pnames);
  for ($i = 0; $i < sizeof($pidArray); $i++) {
    $pid = $pidArray[$i];
    $pname = $pnameArray[$i];
    $result = mysql_query("SELECT * FROM `users` WHERE `fbid`='$pid'");
    if (!mysql_num_rows($result)) {
      mysql_query("INSERT INTO `users` (`fbid`, `name`) VALUES ".
                  "('$pid', '$pname')");
    }
  }
}
/* Using IITD uid assigned, we compute all the followers of this uid. */
function getUserFollowers($userid) {
  $query = "SELECT `fbid`, `name` FROM `follower`, `users` ".
           "WHERE `follower`.`uid`='$userid' AND `users`.`fbid`=`follower`.`follower`";
  $result = mysql_query($query);
  $followerIds = '';
  $followerNames = '';
  while ($row = mysql_fetch_assoc($result)) {
    $followerIds = $followerIds . ',' . $row['fbid'];
    $followerNames = $followerNames . ',' . $row['name'];
  }
  return array(substr($followerIds, 1), substr($followerNames, 1));
}
/* return an array of fbids and their names of the followees of this user */
function getUserFollowees($user) {
  $query = "SELECT `fbid`, `name` FROM `follower`, `users` ".
           "WHERE `follower`='$user' AND `follower`.`uid`=`users`.`uid`";
  $result = mysql_query($query);
  $followeeIds = '';
  $followeeNames = '';
  while ($row = mysql_fetch_assoc($result)) {
    $followeeIds = $followeeIds . ',' . $row['fbid'];
    $followeeNames = $followeeNames . ',' . $row['name'];
  }
  return array(substr($followeeIds, 1), substr($followeeNames, 1));
}
/* returns the current vote tally */
function voteCount($upvotes, $downvotes) {
  if ($upvotes == '')
    $upvote = 0;
  else
    $upvote = sizeof(explode(',', $upvotes));
  if ($downvotes == '')
    $downvote = 0;
  else
    $downvote = sizeof(explode(',', $downvotes));
  return $upvote - $downvote;
}
function voteTally($upvotes, $downvotes) {
  $vote_tally = voteCount($upvotes, $downvotes);
  echo '<span class="votes">'.($vote_tally).' votes</span>';
}
function commentInfo($comment, $authorUid, $authorName) {
  echo '
    <div id="comment" name="'.$comment['comid'].'">
    <span class="author"><img class="author-pic" src="https://graph.facebook.com/'
            .$comment['author'].'/picture?type=small"/>'.$authorName.'</span>
    <br/>
    <span class="comment-data">'.$comment['value'].'</span>
    <br/>';
}
function deleteSupportVote($comment, $user) {
  $dontShow = false;
  if ($comment['author'] == $user) {
    $dontShow = true;
    echo '
    <span class="delete-point votes" title="Delete this point">Delete</span>';
  }
  if (!$dontShow) {
    echo '
    <span class="support-point votes" title="Support this point">Support</span>
    <span class="rebutt-point votes" title="Rebutt this point">Rebutt</span>';
  }
  foreach(explode(',', $comment['upvotes']) as $upvoter) {
    if ($user == $upvoter)
      $dontShow = true;
  }
  foreach(explode(',', $comment['downvotes']) as $downvoter) {
    if ($user == $downvoter)
      $dontShow = true;
  }
  if (!$dontShow) {
    echo '
      <span class="upvote icon-arrow-up" title="Vote Up"></span>
      <span class="downvote icon-arrow-down" title="Vote Down"></span>';
  }
}
function commentsArray($debid) {
  $query = "SELECT * FROM `comments`, `users` WHERE debid='$debid' AND `author`=`fbid`";
  $result = mysql_query($query);
  $comments = array();
  for ($i = 0; $i < mysql_num_rows($result); $i++)
    array_push($comments, mysql_fetch_assoc($result));
  $votes = array();
  foreach ($comments as $key => $row) {
    $votes[$key] = voteCount($row['upvotes'], $row['downvotes']);
  }
  array_multisort($votes, SORT_DESC, $comments);
  return $comments;
}
?>
