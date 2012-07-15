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


/*Posting on the facebook wall of all the participants*/

function postUsers($pids, $pnames,$debatetopic,$debatedesc,$link){
    $pidArray = explode(',', $pids);
    $pnameArray = explode(',', $pnames);
    $facebook = new Facebook(array(
    'appId'   => '253395578066052',
    'secret'  => '23d20951b5546544b2f2e31183e4b5c0',
    'cookie'  => true
    ));
    $queries = array();
    for ($i=0;$i < sizeof($pidArray);$i++){
        $pid = $pidArray[$i];
        $pname = $pnameArray[$i];   
        $to ='/'.$pid.'/feed';
        $body =array('message'=>$debatetopic,'link'=> $link,'description'=>$debatedesc,'name'=>'IIT Debates');
        $queries[] =array('method'=>"POST",'relative_url'=>$to,'body'=> http_build_query($body));
    }
    try{
        $ret_obj = $facebook->api('/?batch='.urlencode(json_encode($queries)), 'POST');          
    } catch (Exception $o){
          $o =-1;    
          echo $o;
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
  echo '<span title="View Vote Count" class="votes vote-store">'.
       '<span class="hide" id="upvoters">'.$upvotes.'</span>'.
       '<span class="hide" id="downvoters">'.$downvotes.'</span>'.
       '<span class="vote-count">'.($vote_tally).'</span>'.
       ' votes</span>';
}
function commentInfo($comment, $authorUid, $authorName) {
  $query = "SELECT `uid` FROM `users` ".
           "WHERE `fbid`='$authorUid'";
  $row = fetchAssoc($query);
  $uid = $row['uid'];
  echo '
    <div class="comment" name="'.$comment['comid'].'">
    <span class="author"><a href="home.php?uid='.$uid.'"><img class="author-pic" src="https://graph.facebook.com/'
            .$comment['author'].'/picture?type=square"/>'.$authorName.'</a></span>
    <span class="comment-data">'.$comment['value'].'</span>
  ';
}
function deleteSupportVote($comment, $user) {
  $dontShow = false;
  if ($comment['author'] == $user and $user) {
    $dontShow = true;
    echo '
    <span class="delete-point votes" title="Delete this point">Delete</span>';
  }
  if (!$dontShow and $user) {
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
  if (!$dontShow and $user) {
    echo '
      <span class="upvote icon-arrow-up" title="Vote Up"></span>
      <span class="downvote icon-arrow-down" title="Vote Down"></span>';
  }
  $parent_id = $comment['parentid'];
  if ($parent_id > 0)
    echo '
      <span class="view-conversation votes"
      name="'.$parent_id.'">View Conversation</span>';
}
function commentsArray($debid) {
  $query = "SELECT * FROM `comments`, `users` WHERE debid='$debid' ".
           "AND `author`=`fbid` ORDER BY `comments`.`comid` DESC";
  $result = mysql_query($query);
  $comments = array();
  for ($i = 0; $i < mysql_num_rows($result); $i++)
    array_push($comments, mysql_fetch_assoc($result));
  $votes = array();
  $comids = array();
  foreach ($comments as $key => $row) {
    $votes[$key] = voteCount($row['upvotes'], $row['downvotes']);
    $comids[$key] = $row['comid'];
  }
  array_multisort($votes, SORT_DESC, $comids, SORT_DESC, $comments);
  return $comments;
}

/**
  * update all the activities in the mother table of updates. 
  * types
  * 0 : created debate
  * 1 : challenged on debate
  * 2 : followed User
  * 3 : followed debate
  * -- TO DO --
  * 4 : invited
  * 5 : voted up/down notification
  */
function updateActivity($source,$type,$target,$sourcename,$targetname) {
  $query = "INSERT INTO `updates` (`source`,`type`,`target`,`sourcename`,`targetname`)".
           " VALUES ('$source','$type','$target','$sourcename','$targetname')";
  $result = mysql_query($query);
}

/* Gets all the activities from the mother updates table
   which are generated by people I follow. */
function getActivities($friends) {
  $friendsArray = $friends;
  $query = "SELECT * FROM `updates` ".
           "WHERE `source` IN (".$friendsArray.")";
  $result = mysql_query($query);
  for ($i = 0; $i < mysql_num_rows($result); $i++) {
    $row = mysql_fetch_assoc($result);
    $type = $row['type'];
    $sourceid = $row['source'];
    $targetid = $row['target'];
    $sourcename = $row['sourcename'];
    $targetname =$row['targetname'];
    echo '<div id="update">';
    switch ($type) {
      case 0: // created debate
        echo '<a href="home.php?uid='.$sourceid.'">'.$sourcename.'</a> started debate '.
             '<a href="debate.php?debid='.$targetid.'">'.$targetname.'</a>';
        break;
      case 1: // challenged on debate
        echo '<a href="home.php?uid='.$sourceid.'">'.$sourcename.'</a> challenged '.
             'to debate on <a href="home.php?uid='.$targetid.'">'.$targetname.'</a>';
        break;
      case 2: // followed User
        echo '<a href="home.php?uid='.$sourceid.'">'.$sourcename.'</a> is now following '.
             '<a href="home.php?uid='.$targetid.'">'.$targetname.'</a>';
        break;
      case 3: // followed Debate
        echo '<a href="home.php?uid='.$sourceid.'">'.$sourcename.'</a> is now following '.
             'the debate <a href="debate.php?debid='.$targetid.'">'.$targetname.'</a>';
        break;
    }
    echo '</div>';
  }
}

function updatetoken($user, $debate, $token) {
  $query = "SELECT `debates` FROM `users` ".
           "WHERE `fbid`='$user'";
  $result = mysql_query($query);
  $row = mysql_fetch_assoc($result);     
  $Dtokens = explode(',', $row['debates']);
  $temp='no';
  for ($i = 0; $i < sizeof($Dtokens); $i++) {
    $t = explode(':', $Dtokens[$i]);
    if($t[0] == $debate) {
      if($t[1] != $token) {
        $Dtokens[$i]=$debate.':'.$token;
        $debates = implode(",",$Dtokens);
        mysql_query("UPDATE `users` SET `debates`='$debates' ".
                    "WHERE `fbid`='$user'");
      }
    }
  }
}

/*Return the array of (debate,change) for the $user */
function debateUpdates($user){
  $query = "SELECT `debates` FROM `users` ".
           "WHERE `fbid`='$user'";
  $result = mysql_query($query);
  $raw = mysql_fetch_assoc($result);
  $Dtokens = explode(',', $raw['debates']);
  $didArray = array();
  $debateArray = array();
  for ($i = 0; $i <sizeof($Dtokens); $i++) {
    $temp = explode(':', $Dtokens[$i]);
    $didArray[] = $temp[0];
    $debateArray[$temp[0]] = intval($temp[1]);
  }
  if(sizeof($didArray) > 1) {
    // only if the user follow at least one debate.   
    $da = implode(',', $didArray);
    $query = "SELECT `debid`,`token` FROM `debates` ".
             "WHERE `debid` IN (".$da.")";
    $res = mysql_query($query);
    for ($i = 0; $i < mysql_num_rows($res); $i++) {
      $row = mysql_fetch_assoc($res);
      $debateArray[$row['debid']] = intval($row['token']) - $debateArray[$row['debid']];
    }
    
  }
  return $debateArray;
}
?>
