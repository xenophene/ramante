<?php
session_start();
session_destroy();
$_SESSION = array();
header('Location: index.php');
/*
include('includes/facebook.php');
$facebook = new Facebook(array(
"appId"   => '253395578066052',
"secret"  => '23d20951b5546544b2f2e31183e4b5c0',
"cookie"  => false
));
$ret_obj = $facebook->api('/me/iit_debates:create', 'POST',
                          array('name' => 'Facebook Dialogs',
                         'debate'=>'http://samples.ogp.me/351140164958259'
                           ));
*/
?>
